<?php

namespace App\Service;

use App\Http\Requests\Settings\Common\LabelSettings;
use App\Http\Requests\Settings\Common\OrderStatusSettings;
use App\Repostory\CommonSettings\CommonSettingsRepository;
use App\Repostory\OzonSettings\OzonSettingsRepository;
use Illuminate\Support\Facades\DB;

class CommonSettingsService
{
    private CommonSettingsRepository $commonSettingsRepository;
    private OzonSettingsRepository $ozonSettingsRepository;
    public function __construct(
        CommonSettingsRepository $commonSettingsRepository,
        OzonSettingsRepository $ozonSettingsRepository
    )
    {
        $this->commonSettingsRepository = $commonSettingsRepository;
        $this->ozonSettingsRepository=$ozonSettingsRepository;
    }

    public function createLabel(LabelSettings $request) :array
    {
        $data = $request->validated();
        $createArr = [
            'name' => $data['label_name'],
            'site_label_id' => $data['label_id'],
            'color' => $data['color']
        ];
        try {
            DB::beginTransaction();
            $this->commonSettingsRepository->createSiteLabel($createArr);
            DB::commit();
            return [
                'err' => 'none'
            ];
        } catch (\Exception $exception){
            DB::rollBack();
            return [
                'err' => $exception->getMessage()
            ];
        }
    }

    public function createSiteStatus(OrderStatusSettings $request): array
    {
        $data = $request->validated();
        $createArr = [
            'name' => $data['status_name'],
            'site_status_id' => $data['status_id'],
            'color' => $data['color']
        ];
        try {
            DB::beginTransaction();
            $this->commonSettingsRepository->createSiteStatus($createArr);
            DB::commit();
            return [
                'err' => 'none'
            ];
        } catch (\Exception $exception){
            DB::rollBack();
            return [
                'err' => $exception->getMessage()
            ];
        }
    }
}
