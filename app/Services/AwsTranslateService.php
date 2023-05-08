<?php

namespace App\Services;

use Aws\Translate\TranslateClient;
use Aws\Credentials\CredentialProvider;

class AwsTranslateService
{
    private $translateClient;
    private $parameter = [];
    private $expire = 0;

    public function __construct()
    {
        $parameter = [
            'region' => env('AWS_TRANSLATE_REGION'),
            'version' => '2017-07-01',
        ];

        $credentials = base_path(env('AWS_CREDENTIALS'));
        if (file_exists($credentials)) {
            $profile = 'freebitDev';
            $provider = CredentialProvider::ini($profile, $credentials);
            $parameter['credentials'] = CredentialProvider::memoize(
                CredentialProvider::ini($profile, $credentials)
            );
        }

        // expire 時の 更新用に
        $this->parameter = $parameter;
        $this->expire = time() + 3500;
        $this->translateClient = new TranslateClient($parameter);
    }

    /*
        翻訳
        @param string sourceLanguage
        @param string targetLanguage
        @param string text
        @return
    */
    public function translate($sourceLanguage = 'auto', $targetLanguage, $text)
    {
        $this->renewClient();

        $result = $this->translateClient->translateText([
            'SourceLanguageCode' => $sourceLanguage,
            'TargetLanguageCode' => $targetLanguage,
            'Text' => $text,
        ]);
        return $result['TranslatedText'] ?? null;
    }


    private function renewClient()
    {
        if ($this->expire > time()) {
            return;
        }

        $this->expire = time() + 3500;
        $this->translateClient = new TranslateClient($parameter);
    }
}
