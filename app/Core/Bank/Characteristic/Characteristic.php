<?php

namespace Boitata\Core\Bank\Characteristic;

use MongolidLaravel\MongolidModel;

class Characteristic extends MongolidModel
{
    /**
     * Collection's name.
     *
     * @var null
     */
    protected $collection = null;

    /**
     * Returns characteristic's name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
