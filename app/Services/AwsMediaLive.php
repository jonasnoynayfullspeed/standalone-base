<?php
use Carbon\Carbon;
use Aws\MediaLive\MediaLiveClient;
use Aws\Credentials\CredentialProvider;
use Log;

class AwsMediaLive
{
    private $client = null;
    private $channelParameter = [];

    public function __construct()
    {
        $profile = null;
        $credentials = base_path(env('FIREBASE_CREDENTIALS'));
        if (file_exists($credentials)) {
            $profile = 'freebitDev';
        }

        $params = [
            'version' => '2017-10-14',
            'region' => env('AWS_MEDIA_LIVE_REGION'),
        ];

        $this->channelParameter = [
            'ChannelId' => env('AWS_MEDIA_LIVE_CHANNEL_ID')
        ];

        if ($profile) {
            $provider = CredentialProvider::ini($profile, $credentials);
            $params['credentials'] = CredentialProvider::memoize($provider);
        }

        try {
            $this->client = new MediaLiveClient($params);
        } catch (Exception $e) {
            Log::error(__METHOD__, $error->getMessage());
            return false;
        }
    }

    /*
        Get channel state from client

        @return string | boolean
    */
    public function getChannelState()
    {
        try {
            $result = $this->client->describeChannel($this->channelParameter);
            return $result["State"];
        } catch (Exception $e) {
            Log::error(__METHOD__, $error->getMessage());
            return false;
        }
    }

    /*
        Check for running pipelines

        @return boolean
    */
    public function hasPipelinesRunning() : bool
    {
        try {
            $result = $this->client->describeChannel($this->channelParameter);
            return $result["PipelinesRunningCount"] && $result["PipelinesRunningCount"] > 0;
        } catch (Exception $e) {
            Log::error(__METHOD__, $error->getMessage());
            return false;
        }
    }

    /*
        Check channel if on maintenance window

        @return boolean
     */
    public function isMaintenanceMode(): bool
    {
        try {
            $result         = $this->client->describeChannel($this->channelParameter);
            $maintenance    = $result['Maintenance'] ?? null;

            if($maintenance) {
                $day            = $maintenance['MaintenanceDay']; //Day of week: TUESDAY
                $scheduleDate   = $maintenance['MaintenanceScheduledDate']; //ISO date and time

                return $this->checkMaintenanceSchedule($day, $scheduleDate);
            }

            return false;
        } catch (Exception $e) {
            Log::error(__METHOD__, $error->getMessage());
            return false;
        }
    }

    /*
        Check if maintenance schedule is within range

        @return boolean
     */
    private function checkMaintenanceSchedule($day, $scheduleDate): bool
    {
        $scheduleDate   = Carbon::parse($scheduleDate);
        $nowDateTime    = Carbon::now();

        if($day == strtoupper($nowDateTime->format('l')) && $scheduleDate->lt($nowDateTime)) {
            return true;
        }

        return false;
    }

    /*
        Get channel state from client

        @return boolean
    */
    public function startChannel(): bool
    {
        try {
            $this->client->startChannel($this->channelParameter);
            return true;
        } catch (Exception $e) {
            Log::error(__METHOD__, $error->getMessage());
            return false;
        }
    }

    /*
        Get channel state from client

        @return boolean
    */
    public function stopChannel(): bool
    {
        try {
            $this->client->stopChannel($this->channelParameter);
            return true;
        } catch (Exception $e) {
            Log::error(__METHOD__, $error->getMessage());
            return false;
        }
    }
}
