<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\PrintJob;
use Illuminate\Http\Request;

class ConfirmWorkController extends Controller
{
    public function __invoke(int $taskId)
    {
        $printJob = PrintJob::find($taskId);
        $printJob->delete();
        return response()->json(['message' => 'Task id=' . $taskId . ' has been deleted successfully']);
    }
}
