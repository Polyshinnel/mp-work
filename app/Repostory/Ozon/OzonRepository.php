<?php

namespace App\Repostory\Ozon;

use App\Models\OzonOrder;
use Illuminate\Database\Eloquent\Collection;

class OzonRepository
{
    public function createOzonOrder(array $createArr): void
    {
        OzonOrder::create($createArr);
    }

    public function updateOzonOrder(array $updateArr, int $ozonOrderId): void
    {
        OzonOrder::where('id', $ozonOrderId)->update($updateArr);
    }

    public function getFilteredOzonPacking(array $filter): ?OzonOrder
    {
        return OzonOrder::where($filter)->first();
    }

    public function getOzonOrderById(int $id): ?OzonOrder
    {
        return OzonOrder::find($id);
    }

    public function getOzonOrderListByStatus(int $statusId): ?Collection
    {
        return OzonOrder::where('ozon_status_id', $statusId)->get();
    }

    public function getOzonWatchableOrderList(array $statusList): ?Collection
    {
        return OzonOrder::whereIn('ozon_status_id', $statusList)->get();
    }

    public function updateOzonOrderByPostingId(string $postingId, array $updateArr): void
    {
        OzonOrder::where('ozon_posting_id', $postingId)->update($updateArr);
    }
}
