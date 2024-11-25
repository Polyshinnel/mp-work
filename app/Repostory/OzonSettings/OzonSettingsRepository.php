<?php

namespace App\Repostory\OzonSettings;

use App\Models\OzonStatusList;
use App\Models\OzonWarehouse;
use Illuminate\Database\Eloquent\Collection;

class OzonSettingsRepository
{
    public function createOzonStatus(array $createArr): void
    {
        OzonStatusList::create($createArr);
    }

    public function updateOzonStatus(array $updateArr, int $statusId): void
    {
        OzonStatusList::where('id', $statusId)->update($updateArr);
    }

    public function deleteOzonStatus(int $statusId): void
    {
        OzonStatusList::where('id', $statusId)->delete();
    }

    public function getOzonStatusList(): Collection
    {
        return OzonStatusList::all();
    }

    public function getOzonStatusById(int $id): ?OzonStatusList
    {
        return OzonStatusList::find($id);
    }

    public function getOzonStatusByName(string $statusName): ?OzonStatusList
    {
        return OzonStatusList::where('ozon_status_name', $statusName)->first();
    }

    public function createOzonWarehouse(array $createArr): void
    {
        OzonWarehouse::create($createArr);
    }

    public function updateOzonWarehouse(array $updateArr, int $id): void
    {
        OzonWarehouse::where('id', $id)->update($updateArr);
    }

    public function deleteOzonWarehouse(int $id): void
    {
        OzonWarehouse::where('id', $id)->delete();
    }

    public function getOzonWarehouseList(): Collection
    {
        return OzonWarehouse::all();
    }

    public function getOzonWarehouseById(int $id): OzonWarehouse
    {
        return OzonWarehouse::find($id);
    }
}
