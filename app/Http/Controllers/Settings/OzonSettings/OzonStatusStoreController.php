<?php

namespace App\Http\Controllers\Settings\OzonSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Ozon\OzonStatusSettings;
use Illuminate\Http\Request;

class OzonStatusStoreController extends Controller
{
    public function __invoke(OzonStatusSettings $request)
    {
        $data = $request->validated();
    }
}
