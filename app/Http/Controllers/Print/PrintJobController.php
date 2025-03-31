<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrintJobRequest;
use App\Models\PrintJob;

class PrintJobController extends Controller
{
    public function __invoke(PrintJobRequest $request)
    {
        $data = $request->validated();
        $createArr = [
            'seat_id' => $data['seat_id'],
            'file' => $data['file']
        ];
        PrintJob::create($createArr);
        return response()->json(['status' => 'success']);
    }
}
