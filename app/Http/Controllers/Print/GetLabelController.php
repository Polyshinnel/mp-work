<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ozon\OzonApi;
use App\Http\Controllers\Yandex\YandexApi;
use App\Http\Requests\CreateLabelRequest;
use App\Models\PrintJob;
use Illuminate\Http\Request;
use Storage;

class GetLabelController extends Controller
{
    private OzonApi $ozonApi;
    private YandexApi $yandexApi;

    public function __construct(OzonApi $ozonApi, YandexApi $yandexApi)
    {
        $this->ozonApi = $ozonApi;
        $this->yandexApi = $yandexApi;
    }

    public function __invoke(CreateLabelRequest $request)
    {
        $data = $request->validated();
        $file = null;
        if($data['type_label'] === 'ozon') {
            $file = $this->getOzonLabel($data['order_id']);
        }
        if($data['type_label'] === 'yandex') {
            $file = $this->getYandexLabel($data['campaign_id'], $data['order_id']);
        }
        if($file) {
            $createArr = [
                'seat_id' => $data['seat_id'],
                'file' => $file,
            ];
            PrintJob::create($createArr);
            return response()->json(['status' => 'ok']);
        }
        return response()->json(['status' => 'fail'], 500);
    }

    private function getOzonLabel($postingId)
    {
        $postingArr = [
            $postingId
        ];
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


        if($taskId) {
            $resultLabel = $this->ozonApi->getLabels($taskId);
            if ($resultLabel) {
                $resultLabel = json_decode($resultLabel, true);
                if (isset($resultLabel['result']['status'])) {
                    if ($resultLabel['result']['status'] == 'completed') {
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
        }


        if($url)
        {
            $fileName = time().'.pdf';
            $fileContent = file_get_contents($url);
            Storage::disk('labels')->put($fileName, $fileContent);
            return '/public/ozon-labels/'.$fileName;
        }
        return null;
    }

    private function getYandexLabel(string $campaignId, string $orderId): bool|string
    {
        return $this->yandexApi->getDeliveryLabel($campaignId, $orderId);
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
