<?php

namespace App\Repostory\Ozon;

use App\Models\OzonOrderProducts;
use App\Models\Products;
use Illuminate\Support\Collection;

class OzonProductRepository
{
    public function getProduct(string $offerId): ?Products
    {
        return Products::where('offer_id', $offerId)->first();
    }

    public function getProductById(int $id): ?Products
    {
        return Products::find($id);
    }

    public function createProduct(array $array): Products
    {
        return Products::create($array);
    }

    public function createProductOrderRecord(array $array): void
    {
        OzonOrderProducts::create($array);
    }

    public function getOzonOrderProduct(int $orderId): ?Collection
    {
        return OzonOrderProducts::where('ozon_order_id', $orderId)->get();
    }
}
