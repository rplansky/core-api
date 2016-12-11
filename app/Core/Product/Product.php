<?php

namespace Boitata\Core\Product;

use Boitata\Core\Bank\Characteristic\Characteristic;
use MongolidLaravel\MongolidModel;

/**
 * Class Product
 * @package Boitata\Core\Product
 *
 * This entity is one of core modules of boitata. With it, you can manage all your products easily.
 */
class Product extends MongolidModel
{
    /**
     * Collection's name.
     *
     * @var string
     */
    protected $collection = 'products';

    /**
     * Embeds multiple characteristics.
     *
     * @return \Mongolid\Cursor\EmbeddedCursor
     */
    public function characteristics()
    {
        return $this->embedsMany(Characteristic::class, 'characteristics');
    }


    /**
     * Returns the product's name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the product id.
     *
     * @return string
     */
    public function getId()
    {
        return (string) $this->_id;
    }
}
