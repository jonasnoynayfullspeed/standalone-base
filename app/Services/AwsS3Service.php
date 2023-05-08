<?php

namespace App\Services;

use Aws\S3\S3Client;
use Aws\Credentials\CredentialProvider;
use Aws\S3\Exception\S3Exception;

class AwsS3Service
{
    private $s3Client;
    private $bucket;

    public function __construct($s3Config)
    {
        $parameter = [
            'region' => $s3Config->region,
            'version' => '2006-03-01',
        ];

        $credentials = base_path(env('AWS_CREDENTIALS'));
        if (file_exists($credentials)) {
            $profile = 'freebitDev';
            $provider = CredentialProvider::ini($profile, $credentials);
            $parameter['credentials'] = CredentialProvider::memoize(
                CredentialProvider::ini($profile, $credentials)
            );
        }

        $this->s3Client = new S3Client($parameter);
        $this->bucket = $s3Config->bucket;
    }

    /*
        @return bool|string
    */
    public function putObject($key, $body, $acl = 'private', $contentType = null)
    {
        try {
            $param = [
                'Bucket' => $this->bucket,
                'Key'    => $key,
                'Body'   => $body,
                'ACL'    => $acl,
            ];
            if ($contentType) {
                $param['ContentType'] = $contentType;
            }
            $result = $this->s3Client->putObject($param);
            return isset($result['ObjectURL']) ? true : 'invalid response';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /*
        @return bool|string
    */
    public function deleteObject($key)
    {
        try {
            $result = $this->s3Client->deleteObject([
                'Bucket' => $this->bucket,
                'Key'    => $key
            ]);
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /*
        @return bool|string
    */
    public function getObject($key)
    {
        try {
            $this->s3Client->registerStreamWrapper();
            $s3Url = 's3://' . $this->bucket . '/' . $key;
            $response = file_get_contents($s3Url);
            if (! $response) {
                return false;
            }
            return $response;
        } catch (Exception $e) {
            return false;
        }
    }

    /*
        @return bool|string
    */
    public function getFilemtime($key)
    {
        // そもそも存在しないならfalseで返す
        if (! $this->s3Client->doesObjectExist($this->bucket, $key)) {
            return false;
        }
        
        try {
            $this->s3Client->registerStreamWrapper();
            $s3Url = 's3://' . $this->bucket . '/' . $key;
            $response = filemtime($s3Url);
            if (! $response) {
                return false;
            }
            return $response;
        } catch (Exception $e) {
            return false;
        }
    }

    /*
        @return bool
    */
    public function existsObject($key)
    {
        return $this->s3Client->doesObjectExist($this->bucket, $key);
    }

    /*
        @return bool|string
    */
    public function deleteMatchedPrefix($prefix)
    {
        try {
            $result = $this->s3Client->listObjects([
                'Bucket' => $this->bucket,
                'Prefix' => $prefix
            ]);
            $object = [];
            foreach ($result['Contents'] as $info) {
                $object[] = ['Key' => $info['Key']];
            }
            $this->s3Client->deleteObjects([
                'Bucket'  => $this->bucket,
                'Delete' => [
                    'Objects' => $object
                ],
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /*
        create upload ID

        @param string key
    */
    public function createMultipartUploadId($key)
    {
        $result = $this->s3Client->createMultipartUpload([
            'Bucket' => $this->bucket,
            'Key' => $key
        ]);

        return $result['UploadId'];
    }

    /*
        create presigned urls or upload urls

        @param string key
        @param string uploadId
        @param int partNumberEnd (last chunk part)
    */
    public function createPresignUrls($key, $uploadId, $partNumberEnd)
    {
        $presignedUrls = [];

        // 1 up to last part
        foreach (range(1, $partNumberEnd) as $partNumber) {
            $cmd = $this->s3Client->getCommand('UploadPart', [
                'Bucket' => $this->bucket,
                'Key' => $key,
                'UploadId' => $uploadId,
                'PartNumber' => $partNumber,
                'ACL' => 'private'
            ]);

            // NOTE: may need to adjust expiration time, right now it's 20 mins.
            $request = $this->s3Client->createPresignedRequest($cmd, '+20 minutes');

            $url = (string) $request->getUri();
            array_push($presignedUrls, $url);
        }

        return $presignedUrls;
    }

    /*
        complete the upload (merged file parts)

        @param string key
        @param string uploadId
        @param array parts
    */
    public function completeMultipartUpload($key, $uploadId, $parts)
    {
        $result = $this->s3Client->completeMultipartUpload([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'MultipartUpload' => [
                'Parts' => $parts
            ],
            'UploadId' => $uploadId
        ]);

        $fileUrl = 's3://' . $this->bucket . '/' . $key;

        return $fileUrl;
    }
}
