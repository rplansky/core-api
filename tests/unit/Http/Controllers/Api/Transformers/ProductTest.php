<?php
namespace Boitata\Http\Controllers\Api\Transformers;

use Mockery as m;
use Boitata\Core\Product\Product as ProductCore;
use TestCase;
use WithFramework;

class ProductTest extends TestCase
{
    use WithFramework;

    public function testShouldPrepareProductData()
    {
        // Set
        $product = m::mock(ProductCore::class);
        $transformer = new Product();
        $characteristicTransformer = m::mock(Characteristic::class);

        $expected = [
            'id' => 'id',
            'name' => 'foo',
            'characteristics' => [
                ['name' => 'Voltage', 'values' => '110', 'suffix' => 'V', 'type' => 'string'],
                ['name' => 'size', 'values' => 10, 'prefix' => 'between', 'suffix' => 'meters', 'type' => 'float'],
                ['name' => 'size', 'values' => 10, 'prefix' => 'between', 'suffix' => 'meters', 'type' => 'int'],
                ['name' => 'size', 'values' => 10, 'prefix' => 'between', 'suffix' => 'meters', 'type' => 'option'],
            ]
        ];

        // Expect
        $product->shouldReceive('getName')
            ->once()
            ->andReturn('foo');

        $product->shouldReceive('getId')
            ->once()
            ->andReturn('id');

        $characteristicTransformer->shouldReceive('transform')
            ->with($product)
            ->once()
            ->andReturn(
                [
                    ['name' => 'Voltage', 'values' => '110', 'suffix' => 'V', 'type' => 'string'],
                    ['name' => 'size', 'values' => 10, 'prefix' => 'between', 'suffix' => 'meters', 'type' => 'float'],
                    ['name' => 'size', 'values' => 10, 'prefix' => 'between', 'suffix' => 'meters', 'type' => 'int'],
                    ['name' => 'size', 'values' => 10, 'prefix' => 'between', 'suffix' => 'meters', 'type' => 'option'],
                ]
            );

        $this->instance(Characteristic::class, $characteristicTransformer);

        // Assert
        $this->assertEquals($expected, $transformer->transform($product));
    }
}
