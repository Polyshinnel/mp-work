<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateLabelRequest;
use App\Models\PrintJob;
use App\Service\PrintLabelPdfService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class GeneratedLabelController extends Controller
{
    public function __invoke(GenerateLabelRequest $request, PrintLabelPdfService $pdfService)
    {
        $data = $request->validated();
        $quantity = (int) ($data['quantity'] ?? 1);
        $filename = 'generated-labels/'.Str::uuid().'.pdf';

        try {
            $contents = $pdfService->generate(
                $data['type'],
                $data['data'],
                $data['sign'] ?? null,
                $quantity
            );

            if (!Storage::disk('labels')->put($filename, $contents)) {
                return response()->json(['status' => 'fail'], 500);
            }

            PrintJob::create([
                'seat_id' => $data['seat_id'],
                'file' => 'ozon-labels/'.$filename,
            ]);

            return response()->json(['status' => 'ok'], 202);
        } catch (Throwable $exception) {
            Storage::disk('labels')->delete($filename);
            report($exception);

            return response()->json(['status' => 'fail'], 500);
        }
    }
}
