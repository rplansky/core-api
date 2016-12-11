<?php

namespace Boitata\Core\Product;

use ArrayIterator;
use Boitata\Core\Bank\Characteristic\Characteristic;
use Mockery as m;
use PHPUnit_Framework_TestCase;
use TestCase;

class ProductTest extends TestCase
{
    public function testProductShouldHaveCharacteristics()
    {
        // Set
        $product = m::mock(Product::class.'[embedsMany]');
        $product->shouldAllowMockingProtectedMethods();
        $collection = new ArrayIterator();

        // Expect
        $product->shouldReceive('embedsMany')
            ->with(Characteristic::class, 'characteristics')
            ->once()
            ->andReturn($collection);

        // Assert
        $this->assertEquals($collection, $product->characteristics());
    }
}
