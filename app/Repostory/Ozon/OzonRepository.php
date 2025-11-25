<?php

namespace App\Repostory\Ozon;

use App\Models\OzonOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class OzonRepository
{
    public function createOzonOrder(array $createArr): OzonOrder
    {
        return OzonOrder::create($createArr);
    }

    public function updateOzonOrder(array $updateArr, int $ozonOrderId): void
    {
        OzonOrder::where('id', $ozonOrderId)->update($updateArr);
    }

    public function updateOzonOrderByOzonPostingId(array $updateArr, string $siteOrderId): void
    {
        OzonOrder::where('ozon_posting_id', $siteOrderId)->update($updateArr);
    }

    public function getFilteredOzonPacking(array $filter): ?OzonOrder
    {
        return OzonOrder::where($filter)->first();
    }

    public function getFilteredOzonOrders(array $filter): ?Collection
    {
        return OzonOrder::where($filter)->get();
    }

    public function getPaginatedOrders(array $filter, int $perPage = 20, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->buildBaseOrderQuery();

        foreach ($filter as $column => $value) {
            $query->where($column, $value);
        }

        if ($search !== null && $search !== '') {
            $search = trim($search);
            $numericSearch = preg_replace('/\D+/', '', $search);
            $startsWithLetter = preg_match('/^[\p{L}]/u', $search) === 1;

            if ($startsWithLetter) {
                $prefix = null;
                $orderDigits = null;

                if (preg_match('/^([\p{L}]+)[^\d]*(\d+)?$/u', $search, $matches)) {
                    $prefix = Str::lower($matches[1]);
                    $orderDigits = $matches[2] ?? null;
                }

                if ($prefix !== null) {
                    $query->whereHas('siteInfo', function ($q) use ($prefix) {
                        $q->whereRaw('LOWER(prefix) = ?', [$prefix]);
                    });
                }

                if ($orderDigits !== null) {
                    $query->where(function ($q) use ($orderDigits) {
                        $q->whereRaw('CAST(site_order_id AS CHAR) LIKE ?', ["%{$orderDigits}%"]);
                    });
                }
            } else {
                $query->where(function ($q) use ($search, $numericSearch) {
                    $q->where('ozon_posting_id', 'like', "%{$search}%");

                    if ($numericSearch !== '') {
                        $q->orWhereRaw('CAST(site_order_id AS CHAR) LIKE ?', ["%{$numericSearch}%"]);
                    }
                });
            }
        }

        return $query->paginate($perPage);
    }

    private function buildBaseOrderQuery(): Builder
    {
        return OzonOrder::with([
            'siteLabel',
            'siteStatus',
            'ozonWarehouse',
            'siteInfo',
            'ozonStatus',
        ])->orderByDesc('id');
    }

    public function getAllOrders(): ?Collection
    {
        return OzonOrder::all();
    }

    public function getOzonOrderById(int $id): ?OzonOrder
    {
        return OzonOrder::find($id);
    }

    public function getOzonOrderByPosting(string $postingId): ?OzonOrder
    {
        return OzonOrder::where(['ozon_posting_id' => $postingId])->first();
    }

    public function getOzonOrderListByStatus(int $statusId): ?Collection
    {
        return OzonOrder::where('ozon_status_id', $statusId)->get();
    }

    public function getOzonWatchableOrderList(array $statusList): ?Collection
    {
        return OzonOrder::whereIn('ozon_status_id', $statusList)->get();
    }

    public function getSiteStatusWatchableList(array $siteStatusList): ?Collection
    {
        return OzonOrder::whereIn('site_status_id', $siteStatusList)->get();
    }

    public function updateOzonOrderByPostingId(string $postingId, array $updateArr): void
    {
        OzonOrder::where('ozon_posting_id', $postingId)->update($updateArr);
    }
}
