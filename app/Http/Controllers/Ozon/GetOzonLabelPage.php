<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use App\Repostory\Ozon\OzonRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $resultLabel = $this->ozonApi->getLabels($taskId);
            if($resultLabel)
            {
                $resultLabel = json_decode($resultLabel, true);
                if(isset($resultLabel['result']['status']))
                {
                    if($resultLabel['result']['status'] == 'completed')
                    {
                        $url = $resultLabel['result']['file_url'];
                    }
                }
            }

            $url = NULL;
            $count = 20;
            while ($count > 0)
            {
                $url = $this->checkLabelsTask($taskId);
                if($url)
                {
                    break;
                }
                $count--;
            }

            if($url)
            {
                return redirect($url);
            }
        }


        return back();
    }

    public function getLabels($jsonArr)
    {
        $url = 'https://api-seller.ozon.ru/v2/posting/fbs/package-label';

        $fileName = time().'.pdf';
        $clientId = sprintf('Client-Id: %s', config('ozon.ozon_client_id'));
        $apiKey = sprintf('Api-Key: %s', config('ozon.ozon_api_key'));
        $json = json_encode($jsonArr);
        $headers = [
            'Content-Type: application/json',
            $clientId,
            $apiKey
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        //file_put_contents($fileName, $response);

        $info = Storage::disk('labels')->put($fileName, $response);
        return '/public/ozon-labels/'.$fileName;
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
