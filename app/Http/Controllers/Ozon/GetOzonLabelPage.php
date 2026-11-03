<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use App\Repostory\Ozon\OzonRepository;
use Illuminate\Http\Request;

class GetOzonLabelPage extends Controller
{
    private OzonRepository $ozonRepository;
    private OzonApi $ozonApi;

    public function __construct(OzonRepository $ozonRepository, OzonApi $ozonApi)
    {
        $this->ozonRepository = $ozonRepository;
        $this->ozonApi = $ozonApi;
    }

    public function __invoke(Request $request)
    {
        $orders = $request->query('orders');
        $postingArr = [];
        foreach ($orders as $order) {
            $ozonOrder = $this->ozonRepository->getOzonOrderById($order);
            if ($ozonOrder) {
                $postingArr[] = $ozonOrder->ozon_posting_id;
            }
        }
        $resultTask = $this->ozonApi->getLabelsTask($postingArr);
        $taskId = NULL;
        $url = NULL;
        if($resultTask)
        {
            $resultTask = json_decode($resultTask, true);
            if(isset($resultTask['result']['tasks']))
            {
                foreach ($resultTask['result']['tasks'] as $task)
                {
                    if($task['task_type'] == 'small_label')
                    {
                        $taskId = $task['task_id'];
                    }
                }
            }
        }


        if($taskId)
        {
            $count = 20;
            while ($count > 0)
            {
                $url = $this->checkLabelsTask($taskId);
                if($url)
                {
                    break;
                }
                $count--;
                if ($count > 0) {
                    sleep(1);
                }
            }

            if($url)
            {
                return redirect($url);
            }
        }


        return back();
    }

    public function checkLabelsTask($taskId): ?string
    {
        $resultLabel = $this->ozonApi->getLabels($taskId);
        if($resultLabel)
        {
            $resultLabel = json_decode($resultLabel, true);
            if(isset($resultLabel['result']['status']))
            {
                if($resultLabel['result']['status'] == 'completed')
                {
                   return $resultLabel['result']['file_url'];
                }
            }
        }
        return NULL;
    }
}
