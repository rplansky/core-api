<?php
namespace Boitata\Http\Controllers\Api\Transformers;

use Mockery as m;
use Boitata\Core\Product\Product as ProductCore;
use TestCase;

class ProductTest extends TestCase
{

    public function testShouldPrepareProductData()
    {
        $product = m::mock(ProductCore::class);
        $transformer = new Product();

        $expected = [
            'id' => 'id',
            'name' => 'foo'
        ];

        $product->shouldReceive('getName')
            ->once()
            ->andReturn('foo');

        $product->shouldReceive('getId')
            ->once()
            ->andReturn('id');

        $this->assertEquals($expected, $transformer->transform($product));
    }
}
