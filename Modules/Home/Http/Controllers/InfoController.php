<?php

namespace Modules\Home\Http\Controllers;

use App\Repositories\InfoRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
     * Find info from repository.
     *
     * @return void
     */
    public function index(Request $request, Info $info)
    {
        $result = $this->infoRepository->find($info);

        if (! $result) {
            abort(404);
        }

        echo $result->title;
    }
}
