<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\InfoRepository;
use Modules\Home\Models\Info;

class InfoController extends Controller
{
    /**
     * @var InfoRepository
     */
    protected $infoRepository;


    public function __construct(InfoRepository $infoRepository)
    {
        $this->infoRepository = $infoRepository;
    }

    /**
     * Find info from repository
     *
     * @param Request $request
     * @param Info $info
     * 
     * @return void
     */
    public function index(Request $request, Info $info)
    {
       $result = $this->infoRepository->find($info);

       if(! $result) {
        abort(404);
       }

       echo $result->title;
    }
}
