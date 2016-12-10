<?php

namespace Boitata\Core\Bank\Characteristic;

use MongolidLaravel\MongolidModel;

class Characteristic extends MongolidModel
{
    /**
     * Rules to a characteristic be valid.
     *
     * @var array
     */
    public $rules = [
        'name'              => 'required',
        'type'              => 'required|in:float,int,string,option,regex',
        'position'          => 'required|integer|min:1',
        'values'            => 'required_if:type,option',
        'regexDescription'  => 'required_if:type,regex',
        'regexExpression'   => 'required_if:type,regex',
    ];

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
