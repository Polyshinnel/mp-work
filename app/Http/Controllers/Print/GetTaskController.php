<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GetTaskController extends Controller
{
    public function __invoke(int $taskId)
    {
        $data = PrintJob::find($taskId);
        if($data)
        {
            $file = $data->file;
            //$filePath = '/public/ozon-labels/1732705130.pdf';
            $filePath = '/public/'.$file;
            if (!Storage::exists($filePath)) {
                return response()->json([
                    'error' => 'File not found'
                ], 404);
            }

            $fileContents = Storage::get($filePath);
            $base64Content = base64_encode($fileContents);
            return response()->json([
                'task_id' => $data->id,
                'filename' => basename($filePath),
                'mime_type' => Storage::mimeType($filePath),
                'size' => Storage::size($filePath),
                'content' => $base64Content,
                'encoding' => 'base64'
            ]);

        }
        return response()->json([
            'message' => 'No job found'
        ]);
    }
}
