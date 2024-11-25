<?php

namespace App\Http\Controllers\Settings\OzonSettings;

use App\Http\Controllers\BasePageController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repostory\OzonSettings\OzonSettingsRepository;
use Illuminate\Http\Request;

class OzonStatusPageController extends BasePageController
{
    private OzonSettingsRepository $ozonSettingsRepository;

    public function __construct(OzonSettingsRepository $ozonSettingsRepository)
    {
        $this->ozonSettingsRepository = $ozonSettingsRepository;
    }

    public function __invoke(Request $request)
    {

        $pageInfo = $this->getBasePageInfo($request);
        $statusList = $this->ozonSettingsRepository->getOzonStatusList();

        return view(
            'pages.Settings.Ozon.settings-ozon-statuses',
            [
                'pageInfo' => $pageInfo,
                'link' => '/settings',
                'statusList' => $statusList
            ]
        );
    }
}
