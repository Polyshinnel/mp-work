<?php

namespace App\Http\Controllers\Settings\CommonSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Common\LabelSettings;
use Illuminate\Http\Request;

class LabelsStoreController extends Controller
{
    public function __invoke(LabelSettings $request)
    {
        $data = $request->validated();

    }
}
