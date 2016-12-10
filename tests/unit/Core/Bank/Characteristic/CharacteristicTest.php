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

    public function testCharacteristicHaveTheRightRulesToBeValid()
    {
        $rules = [
            'name' => 'required',
            'type' => 'required|in:float,int,string,option,regex',
            'position' => 'required|integer|min:1',
            'values' => 'required_if:type,option',
            'regexDescription' => 'required_if:type,regex',
            'regexExpression' => 'required_if:type,regex',
        ];

        $this->assertSame($rules, (new Characteristic())->rules);
    }
}
