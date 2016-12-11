<?php
/**
 * Created by PhpStorm.
 * User: xps
 * Date: 10/12/16
 * Time: 19:25
 */

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
}