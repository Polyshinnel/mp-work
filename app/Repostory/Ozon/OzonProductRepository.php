<?php

namespace App\Repostory\Ozon;

use App\Models\OzonOrderProducts;
use App\Models\Products;

class OzonProductRepository
{
    public function getProduct(string $offerId): ?Products
    {
        return Products::where('offer_id', $offerId)->first();
    }

    public function createProduct(array $array): Products
    {
        return Products::create($array);
    }

    public function createProductOrderRecord(array $array): void
    {
        OzonOrderProducts::create($array);
    }
}
