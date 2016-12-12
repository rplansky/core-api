<?php
namespace Boitata\Http\Controllers\Api\Transformers;

use TestCase;
use Boitata\Core\Bank\Characteristic\Characteristic as CoreCharacteristic;
use Boitata\Core\Product\Product as CoreProduct;
use Mockery as m;

class CharacteristicTest extends TestCase
{
    public function testShouldPrepareCharacteristicData()
    {
        // Set
        $product = m::mock(CoreProduct::class);
        $characteristicInstance = new CoreCharacteristic;
        $transformer = new Characteristic();

        $expected = [
            ['name' => 'Voltage', 'values' => '110', 'suffix' => 'V', 'type' => 'string'],
        ];

        $characteristicInstance->fill(['name' => 'Voltage', 'values' => '110', 'suffix' => 'V', 'type' => 'string']);

        // Expect
        $product->shouldReceive('characteristics')
            ->once()
            ->andReturn([
                $characteristicInstance
            ]);

        // Assert
        $this->assertEquals($expected, $transformer->transform($product));
    }
}