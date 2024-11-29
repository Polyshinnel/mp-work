<?php

namespace App\Http\Controllers\Ozon;

use App\Http\Controllers\Controller;
use App\Repostory\Ozon\OzonRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GetOzonLabelPage extends Controller
{
    private OzonRepository $ozonRepository;

    public function __construct(OzonRepository $ozonRepository)
    {
        $this->ozonRepository = $ozonRepository;
    }

    public function __invoke(Request $request)
    {
        $orders = $request->query('orders');
        $postingsArr = ['posting_number' => []];
        foreach ($orders as $order) {
            $ozonOrder = $this->ozonRepository->getOzonOrderById($order);
            if ($ozonOrder) {
                $postingsArr['posting_number'][] = $ozonOrder->ozon_posting_id;
            }
        }
        $link = $this->getLabels($postingsArr);
        return Storage::download($link);
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
}
