<?php

namespace App\Service;

use App\Http\Controllers\Ozon\OzonApi;
use App\Models\OzonOrder;
use App\Models\Products;
use App\Repostory\Ozon\OzonProductRepository;

class OzonProductService
{
    private OzonProductRepository $ozonProductRepository;
    private OzonApi $ozonApi;

    public function __construct(OzonProductRepository $ozonProductRepository, OzonApi $ozonApi)
    {
        $this->ozonProductRepository = $ozonProductRepository;
        $this->ozonApi = $ozonApi;
    }
    private function getOzonProductInfo($productArr): Products
    {
        $dbProduct = $this->ozonProductRepository->getProduct($productArr['offer_id']);
        if(!$dbProduct){
            $ozonProduct = $this->ozonApi->getFilteredProducts($productArr['offer_id']);
            $ozonProduct = json_decode($ozonProduct, true);

            $ozonProductId = $ozonProduct['result']['items'][0]['product_id'];
            $createArr = [
                'offer_id' => $productArr['offer_id'],
                'name' => $productArr['name'],
                'product_id' => $ozonProductId,
                'ozon_sku' => $productArr['sku'],
            ];
            return $this->ozonProductRepository->createProduct($createArr);
        }
        return $dbProduct;
    }

    public function addProductsToOrder(OzonOrder $order, array $products): void
    {
        $orderId = $order->id;
        foreach($products as $product){
            $productInfo = $this->getOzonProductInfo($product);
            $createArr = [
                'ozon_order_id' => $orderId,
                'product_id' => $productInfo->id,
                'quantity' => $product['quantity'],
            ];
            $this->ozonProductRepository->createProductOrderRecord($createArr);
        }
    }
}
