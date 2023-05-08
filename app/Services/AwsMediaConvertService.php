<?php

use Log;
use Aws\MediaConvert\MediaConvertClient;
use Aws\Credentials\CredentialProvider;

class AwsMediaConvertService
{
    private $client = null;
    private $parameterFile = null;

    public function __construct()
    {
        $profile = null;
        $credentials = base_path(env('AWS_CREDENTIALS'));
        if (file_exists($credentials)) {
            $profile = 'freebitDev';
        }

        $this->parameterFile = json_decode(file_get_contents(base_path(env('PARAMETER_FILE'))), true);

        $params = [
            'version' => '2017-08-29',
            'region' => env('AWS_MEDIA_CONVERT_REGION'),
        ];

        if ($profile) {
            $provider = CredentialProvider::ini($profile, $credentials);
            $params['credentials'] = CredentialProvider::memoize($provider);
        }

        try {
            $client = new MediaConvertClient($params);
            $result = $client->describeEndpoints([]);
            $endPoint = $result['Endpoints'][0]['Url'] ?? null;
            $params['endpoint'] = $endPoint;
            $this->client = new MediaConvertClient($params);
        } catch (Exception $e) {
            Log::error(__METHOD__, $error->getMessage());
            return false;
        }
    }

    /*
        @param string sourceFile
        @param string destDir
        @param string destDirDrmIos 
    */
    public function createJob($sourceFile, $destDir, $destDirDrmIos, $destDirThumbnail)
    {
        $this->parameterFile["Settings"]["Inputs"][0]["FileInput"] = $sourceFile;
        $this->parameterFile["Settings"]["OutputGroups"][0]["OutputGroupSettings"]["HlsGroupSettings"]["Destination"] = $destDir;
        $this->parameterFile["Settings"]["OutputGroups"][1]["OutputGroupSettings"]["HlsGroupSettings"]["Destination"] = $destDirDrmIos;
        $this->parameterFile["Settings"]["OutputGroups"][2]["OutputGroupSettings"]["FileGroupSettings"]["Destination"] = $destDirThumbnail;

        try {
            $result = $this->client->createJob($this->parameterFile);
            $info = pathinfo($sourceFile);
            $array = [
                'movie' => $info['filename'] . '.m3u8',
                'thumbnail' => $info['filename'] . '_thumbnail' . '.0000000' . '.jpg',
            ];

            return $array;

        } catch (Exception $e) {
            Log::error(__METHOD__, $error->getMessage());
            return false;
        }
    }
}
