<?php

namespace Modules\Home\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\AwsMediaLiveService;
use Modules\Home\Models\LiveStreaming;
use Illuminate\Support\Facades\Validator;
use App\Repositories\LiveStreamingRepository;

class LiveController extends Controller
{

    public LiveStreamingRepository $liveStreamingRepository;

    public function __construct(LiveStreamingRepository $liveStreamingRepository)
    {
        $this->liveStreamingRepository = $liveStreamingRepository;
    }

    /**
     * Get live status
     *
     * @return JsonResponse
     */
    public function getOnAirLive(Request $request)
    {
        $onAirLive = $this->liveStreamingRepository->getActiveStream();

        if (! $onAirLive) {
            abort(404);
        }

        return ApiResponse::success(compact('onAirLive'));
    }

    /**
     * Create new live
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createLive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'scope' => 'required'
        ]);

        if($validator->fails()) {
            return ApiResponse::badRequest('Invalid request input');
        }

        $liveStreaming = new LiveStreaming();
        $liveStreaming->setToModel($request->only(['title', 'scope']));
        $liveStreaming = $this->liveStreamingRepository->createLive($liveStreaming);

        if(! $liveStreaming) {
            return ApiResponse::notAcceptable();
        }

        $awsMediaLiveService = new AwsMediaLiveService();

        if($awsMediaLiveService->getChannelState() == AwsMediaLiveService::STATE_MAINTENANCE) {
            return ApiResponse::systemError('ただいまメンテナンス中です');
        }

        if(! $awsMediaLiveService->startChannel()) {
            return ApiResponse::systemError('配信は開始されましたが、Aws MediaLive のチャンネルを開始できませんでした。');
        }

        $state = $awsMediaLiveService->getChannelState();

        return ApiResponse::success(compact('state'));
    }

    /**
     * Stop a livestream
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function stopLive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'scope' => 'required'
        ]);

        if($validator->fails()) {
            return ApiResponse::badRequest('Invalid request input');
        }

        $liveStreaming = $this->liveStreamingRepository->getActiveStream();
        $liveStreaming->onAir = false;
        $liveStreaming->setToModel($request->only(['title', 'scope']));
        $liveStreaming = $this->liveStreamingRepository->stopLive($liveStreaming);

        if(! $liveStreaming) {
            return ApiResponse::notAcceptable();
        }

        $awsMediaLiveService = new AwsMediaLiveService();

        if($awsMediaLiveService->getChannelState() == AwsMediaLiveService::STATE_MAINTENANCE) {
            return ApiResponse::systemError('ただいまメンテナンス中です');
        }

        if(! $awsMediaLiveService->stopChannel()) {
            return ApiResponse::systemError('配信は終了しましたが、Aws MediaLive のチャンネルを停止できませんでした。');
        }

        $state = $awsMediaLiveService->getChannelState();

        return ApiResponse::success(compact('state'));
    }
}
