<?php

namespace Boitata\Core\Bank\Characteristic;

use TestCase;

class CharacteristicTest extends TestCase
{
    public function testCharacteristicShouldBeEmbeddedDocument()
    {
        // Set
        $characteristic = new Characteristic();

        // Assert
        $this->assertNull($characteristic->getCollectionName());
    }

    public function testCharacteristicShouldHaveName()
    {
        // Set
        $characteristic = new Characteristic();
        $name = 'Voltage';

        // Expect
        $characteristic->name = $name;

        // Assert
        $this->assertEquals($name, $characteristic->getName());
    }

    public function testCharacteristicHaveTheRightRulesToBeValid()
    {
        // Set
        $rules = [
            'name' => 'required',
            'type' => 'required|in:float,int,string,option,regex',
            'position' => 'required|integer|min:1',
            'values' => 'required_if:type,option',
            'regexDescription' => 'required_if:type,regex',
            'regexExpression' => 'required_if:type,regex',
        ];

        // Assert
        $this->assertSame($rules, (new Characteristic())->rules);
    }
}
