<?php
namespace Boitata\Core\Product;

use ArrayIterator;
use Boitata\Core\Bank\Characteristic\Characteristic;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class ProductTest extends PHPUnit_Framework_TestCase
{
    public function testProductShouldHaveCharacteristics()
    {
        // Range
        $product = m::mock(Product::class . '[embedsMany]');
        $product->shouldAllowMockingProtectedMethods();
        $collection = new ArrayIterator;

        // Expectations
        $product->shouldReceive('embedsMany')
            ->with(Characteristic::class, 'characteristics')
            ->once()
            ->andReturn($collection);

        // Assertions
        $this->assertEquals($collection, $product->characteristics());
    }
}
