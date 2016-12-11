<?php
namespace Boitata\Core\Product;

use Mockery as m;
use TestCase;

class RepositoryTest extends TestCase
{
    public function testShouldReturnAProduct()
    {
        $repository = new Repository();
        $product = new Product();

        Product::shouldReceive('firstOrFail')
            ->once()
            ->andReturn($product);

        $this->assertEquals($product, $repository->firstOrFail('123'));
    }
}
