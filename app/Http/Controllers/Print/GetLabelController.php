<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ozon\OzonApi;
use App\Http\Controllers\Yandex\YandexApi;
use App\Http\Requests\CreateLabelRequest;
use App\Models\PrintJob;
use Illuminate\Http\Request;
use Log;
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

        if($data['type_label'] === 'ozon_ip') {
            $file = $this->getOzonLabel($data['order_id'], true);
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

    private function getOzonLabel($postingId, $ozonIp = false)
    {
        $postingArr = [
            $postingId
        ];
        $resultTask = $this->ozonApi->getLabelsTask($postingArr, $ozonIp);
        $taskId = NULL;
        $url = NULL;
        if($resultTask)
        {
            $resultTask = $this->normalizeApiResponse($resultTask);
            if(!$resultTask)
            {
                return null;
            }
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

            $count = 10;
            for ($i = 0; $i < $count; $i++) {
                $resultLabel = $this->ozonApi->getLabels($taskId, $ozonIp);

                if ($resultLabel) {
                    $resultLabel = $this->normalizeApiResponse($resultLabel);
                    if(!$resultLabel)
                    {
                        return null;
                    }
                    if (isset($resultLabel['result']['status'])) {
                        if ($resultLabel['result']['status'] == 'completed') {
                            $url = $resultLabel['result']['file_url'];
                        }
                    }
                }

                if($url){
                    break;
                }
                sleep(1);
            }

            if(!$url)
            {
                Log::error("Не удалось получить файл с сервера Ozon для заказа $postingId");
            }
        }

        if($url)
        {
            $fileName = time().'.pdf';
            $fileContent = file_get_contents($url);
            Storage::disk('labels')->put($fileName, $fileContent);
            return 'ozon-labels/'.$fileName;
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
            $resultLabel = $this->normalizeApiResponse($resultLabel);
            if(!$resultLabel)
            {
                return null;
            }
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

    private function normalizeApiResponse($response): ?array
    {
        if(is_array($response))
        {
            Log::info('Ответ уже в формате массива');
            Log::info($response);
            return $response;
        }

        if(is_string($response))
        {
            $decoded = json_decode($response, true);
            if(json_last_error() === JSON_ERROR_NONE)
            {
                return $decoded;
            }

            Log::error('Ошибка декодирования JSON: '.json_last_error_msg());
            return null;
        }

        Log::error('Неожиданный тип ответа от API: '.gettype($response));
        return null;
    }
}
