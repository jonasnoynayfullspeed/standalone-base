<?php

namespace App\Services;

use Log;
use Carbon\Carbon;
use Aws\MediaLive\MediaLiveClient;
use Aws\Credentials\CredentialProvider;

class AwsMediaLiveService
{
    private $client = null;
    private $channelParameter = [];
    private $channelDescription = null;

    // State changes in permanent channel
    CONST   STATE_IDLE          = 'IDLE',        //Channel is on standby and ready to start
            STATE_STARTING      = 'STARTING',    //Channel is loading configuration to run
            STATE_RUNNING       = 'RUNNING',     //Channel is live and is running
            STATE_STOPPING      = 'STOPPING',    //Channel is loading configuration to stop
            STATE_ERROR         = 'ERROR',       //Error in the APK / Cannot receive channel
            STATE_MAINTENANCE   = 'MAINTENANCE'; //Channel is on maintenance

    public function __construct()
    {
        $profile = null;
        $credentials = base_path(env('AWS_CREDENTIALS'));
        if (file_exists($credentials)) {
            $profile = 'test';
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

    /**
     * Define channel description
     *
     * @return array
     */
    private function defineChannelDescription()
    {
        if($this->channelDescription == null) {
            $this->channelDescription = $this->client->describeChannel($this->channelParameter);
        }

        return $this->channelDescription;
    }

    /**
     * Get Channel State
     *
     * @return string
     */
    public function getChannelState()
    {
        try {
            $channel = $this->defineChannelDescription();
            $state = $channel['State'] ?? null;

            if( !$state) {
                return self::STATE_ERROR;
            }
    
            if($state == self::STATE_IDLE && $this->hasPipeLinesRunning()) {
                return self::STATE_STOPPING;
            }
    
            if($this->isMaintenanceMode()) {
                return self::STATE_MAINTENANCE;
            }

            return $state;
        } catch (Exception $e) {
            Log::error(__METHOD__, $e->getMessage());
            return false;
        }
    }

    /**
     * Check if channel has pipes running
     *
     * @return boolean
     */
    public function hasPipelinesRunning() : bool
    {
        $channel = $this->defineChannelDescription();
        return $channel["PipelinesRunningCount"] && $channel["PipelinesRunningCount"] > 0;
    }

    /**
     * Check channel if on maintenance window
     *
     * @return boolean
     */
    public function isMaintenanceMode(): bool
    {
        $channel = $this->defineChannelDescription();
        $maintenance    = $channel['Maintenance'] ?? null;

        if($maintenance) {
            $day            = $maintenance['MaintenanceDay']; //Day of week: TUESDAY
            $scheduleDate   = $maintenance['MaintenanceScheduledDate'] ?? $maintenance['MaintenanceStartTime']; //ISO date and time

            return $this->checkMaintenanceSchedule($day, $scheduleDate);
        }

        return false;
    }

    /**
     * Check if maintenance schedule is within range
     *
     * @param string $day
     * @param string $scheduleDate
     * @return boolean
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

    /**
     * Start channel
     *
     * @return boolean
     */
    public function startChannel(): bool
    {
        try {
            $this->client->startChannel($this->channelParameter);
            return true;
        } catch (Exception $e) {
            Log::error(__METHOD__, $e->getMessage());
            return false;
        }
    }

    /**
     * End channel
     * 
     * @return boolean
     */
    public function stopChannel(): bool
    {
        try {
            $this->client->stopChannel($this->channelParameter);
            return true;
        } catch (Exception $e) {
            Log::error(__METHOD__, $e->getMessage());
            return false;
        }
    }
}
