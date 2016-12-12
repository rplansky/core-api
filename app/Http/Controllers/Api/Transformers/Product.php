<?php
namespace Boitata\Http\Controllers\Api\Transformers;

use Boitata\Core\Product\Product as CoreProduct;

/**
 * Class Product
 * @package Boitata\Http\Controllers\Api\Transformers
 *
 * This class is responsible to prepare the Product data before to sent it to the client.
 */
class Product
{
    /**
     * Transform the data to send to client.
     *
     * @param CoreProduct $product
     * @return array
     */
    public function transform(CoreProduct $product)
    {
        $characteristicTransformer = app(Characteristic::class);
        $attributes = [];

        $attributes['name'] = $product->getName();
        $attributes['id'] = $product->getId();

        $attributes['characteristics'] = $characteristicTransformer->transform($product);

        return $attributes;
    }
}