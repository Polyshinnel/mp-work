<?php

namespace App\Service;

use App\Http\Requests\Settings\Ozon\OzonStatusSettings;
use App\Http\Requests\Settings\Ozon\OzonWarehouseSettings;
use App\Repostory\OzonSettings\OzonSettingsRepository;
use Illuminate\Support\Facades\DB;

class OzonSettingsService
{
    private OzonSettingsRepository $ozonSettingsRepository;

    public function __construct(OzonSettingsRepository $ozonSettingsRepository)
    {
        $this->ozonSettingsRepository = $ozonSettingsRepository;
    }

    public function createOzonWarehouse(OzonWarehouseSettings $request): array
    {
        $data = $request->validated();
        $createArr = [
            'name' => $data['warehouse_name'],
            'type' => $data['type'],
            'warehouse_id' => $data['warehouse_id']
        ];
        try {
            DB::beginTransaction();
            $this->ozonSettingsRepository->createOzonWarehouse($createArr);
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

    public function createOzonStatus(OzonStatusSettings $request)
    {
        $data = $request->validated();
        $createArr = [
            'name' => $data['status_name'],
            'ozon_status_name' => $data['ozon_status_name'],
            'color' => $data['status_color'],
            'watch_label' => $data['watch_label'],
            'watch_ozon_status' => $data['watch_ozon_status']
        ];
        try {
            DB::beginTransaction();
            $this->ozonSettingsRepository->createOzonStatus($createArr);
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

    public function getOzonSettingsAssociativeData()
    {
        $ozonSettings = [];
        $warehouses = $this->ozonSettingsRepository->getOzonWarehouseList();
        $ozonStatusList = $this->ozonSettingsRepository->getOzonStatusList();
        if(!$warehouses->isEmpty() && !$ozonStatusList->isEmpty())
        {
            foreach ($warehouses as $warehouse)
            {
                $ozonSettings['warehouses'][$warehouse->warehouse_id] = $warehouse->id;
            }

            foreach ($ozonStatusList as $status)
            {
                $ozonSettings['ozon_status'][$status->ozon_status_name] = $status->id;
            }

            return $ozonSettings;
        }

        return [];
    }

    public function getOzonWatchableStatusList(): array
    {
        $watchableStatusList = [];
        $ozonStatusList = $this->ozonSettingsRepository->getOzonStatusList();
        if(!$ozonStatusList->isEmpty())
        {
            foreach ($ozonStatusList as $status)
            {
                if($status->watch_ozon_status)
                {
                    $watchableStatusList[] = $status->id;
                }
            }
        }

        return $watchableStatusList;
    }
}
