<?php

namespace Boitata\Core\Bank\Characteristic;

class CharacteristicTest extends \PHPUnit_Framework_TestCase
{
    public function testCharacteristicShouldBeEmbeddedDocument()
    {
        $characteristic = new Characteristic();

        $this->assertNull($characteristic->getCollectionName());
    }

    public function testCharacteristicShouldHaveName()
    {
        // Range
        $characteristic = new Characteristic();
        $name = 'Voltage';

        // Expect
        $characteristic->name = $name;

        // Assert
        $this->assertEquals($name, $characteristic->getName());
    }
}
