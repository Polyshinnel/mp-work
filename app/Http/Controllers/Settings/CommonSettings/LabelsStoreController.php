<?php

namespace App\Http\Controllers\Settings\CommonSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Common\LabelSettings;
use App\Service\CommonSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabelsStoreController extends Controller
{
    private CommonSettingsService $settingsService;

    public function __construct(CommonSettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function __invoke(LabelSettings $request)
    {
        $result = $this->settingsService->createLabel($request);
        if($result['err'] != 'none') {
            $path = '/settings/common/marks/add?hasErr='.$result['err'];
            return response()->redirectTo($path);
        }
        return response()->redirectTo('/settings/common/marks');
    }
}
