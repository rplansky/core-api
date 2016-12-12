<?php
namespace Boitata\Core\Product;

use Mockery as m;
use TestCase;
use WithFramework;

class RepositoryTest extends TestCase
{
    use WithFramework;

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

    public function testShouldCreateProduct()
    {
        // Set
        $repository = new Repository();
        $product = m::mock(Product::class);
        $attributes = [
            'name' => 'Foo'
        ];

        // Expect
        $product->shouldReceive('fill')
            ->once()
            ->with($attributes)
            ->andReturn(true);

        $product->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $this->instance(Product::class, $product);

        // Assert
        $this->assertEquals($product, $repository->create($attributes));
    }
}
