<?php
namespace Boitata\Core\Product;

use Mockery as m;
use TestCase;

class RepositoryTest extends TestCase
{
    public function testShouldReturnAProduct()
    {
        // Set
        $repository = new Repository();
        $product = new Product();

        // Expect
        Product::shouldReceive('firstOrFail')
            ->once()
            ->andReturn($product);

        // Assert
        $this->assertEquals($product, $repository->firstOrFail('123'));
    }
}
