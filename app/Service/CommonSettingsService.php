<?php

namespace App\Service;

use App\Http\Requests\Settings\Common\LabelSettings;
use App\Http\Requests\Settings\Common\OrderStatusSettings;
use App\Http\Requests\Settings\Common\SiteSettingsRequest;
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

    public function createSiteSettings(SiteSettingsRequest $request)
    {
        $data = $request->validated();
        $createArr = [
            'db_name' => $data['site_db'],
            'prefix' => $data['site_prefix'],
            'host' => $data['site_name']
        ];
        try {
            DB::beginTransaction();
            $this->commonSettingsRepository->createSiteSetting($createArr);
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

    public function getAllOzonWarehousesIds(): array
    {
        $warehousesIds = [];
        $warehouses = $this->ozonSettingsRepository->getOzonWarehouseList();
        if(!$warehouses->isEmpty()){
            foreach ($warehouses as $warehouse){
                $warehousesIds[] = $warehouse->warehouse_id;
            }
        }
        return $warehousesIds;
    }

    public function getOzonStatusWatchList(): array
    {
        $result = $this->ozonSettingsRepository->getOzonStatusByLabelWatch();
        $statusList = [];
        if(!$result->isEmpty()){
            foreach ($result as $item){
                $statusList[] = $item->ozon_status_name;
            }
        }
        return $statusList;
    }
}
