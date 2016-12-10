<?php
namespace Boitata\Core\Product;

use Boitata\Core\Bank\Characteristic\Characteristic;
use MongolidLaravel\MongolidModel;

class Product extends MongolidModel {

    /**
     * Collection's name.
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
}