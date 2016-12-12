<?php
namespace Boitata\Core\Product;

/**
 * Class Repository
 * @package Boitata\Core\Product
 *
 * This class is responsible to encapsulate all queries to Product entity.
 */
class Repository
{
    /**
     * Returns a product with the ID given or raise NotFoundException.
     *
     * @param $id
     * @return \Mongolid\ActiveRecord
     */
    public function firstOrFail($id)
    {
        return Product::firstOrFail(['_id' => $id]);
    }

    /**
     * Persist product at db.
     *
     * @param $attributes
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function create($attributes)
    {
        $product = app(Product::class);
        $product->fill($attributes);

        $product->save();

        return $product;
    }
}