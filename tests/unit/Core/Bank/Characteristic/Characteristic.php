<?php

namespace Boitata\Core\Bank\Characteristic;

class Characteristic extends \PHPUnit_Framework_TestCase
{
    public function testCharacteristicShouldBeEmbeddedDocument()
    {
        $characteristic = new self();

        $this->assertNull($characteristic->getCollectionName());
    }
}
