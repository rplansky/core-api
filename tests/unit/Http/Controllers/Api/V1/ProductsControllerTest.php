<?php
namespace Boitata\Http\Controllers\Api\V1;

use Boitata\Core\Product\Product;
use Boitata\Core\Product\Repository;
use Boitata\Http\Controllers\Api\Transformers\Product as ProductTransformer;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\MessageBag;
use TestCase;
use Mockery as m;
use WithFramework;

class ProductsControllerTest extends TestCase
{
    use WithoutMiddleware, WithFramework;

    public function testGetProductInformation()
    {
        // Set
        $repository = m::mock(Repository::class);
        $product = new Product;
        $transformer = m::mock(ProductTransformer::class);
        $id = "507f191e810c19729de860ea";
        $response = [
            'id' => $id,
            'name' => 'Lawn mower 110',
            'characteristics' => [
                ['name' => 'Voltage', 'type' => 'string', 'values' => '110', 'layoutPos' => 'V'],
                ['name' => 'Fuel', 'type' => 'string', 'values' => 'Gasoline'],
            ]
        ];

        // Expect
        $repository->shouldReceive('firstOrFail')
            ->once()
            ->andReturn($product);

        $transformer->shouldReceive('transform')
            ->once()
            ->andReturn($response);

        $this->app->instance(Repository::class, $repository);
        $this->app->instance(ProductTransformer::class, $transformer);

        // Act
        $this->call('GET', "/api/v1/product/{$id}");

        // Assert
        $this->assertJsonResponse($response);
        $this->assertResponseStatus(200);
    }

    public function testShouldCreateProductsSuccessfully()
    {
        // Set
        $repository = m::mock(Repository::class);
        $product = m::mock(Product::class);
        $message = new MessageBag();

        $attributes = [];
        $response = [
            'success' => true,
            'errors' => []
        ];

        // Expect
        $this->instance(Repository::class, $repository);
        $repository->shouldReceive('create')
            ->once()
            ->with($attributes)
            ->andReturn($product);

        $product->shouldReceive('errors')
            ->once()
            ->andReturn($message);

        // Act
        $this->call('POST', "/api/v1/product", $attributes);

        // Assert
        $this->assertJsonResponse($response);
        $this->assertResponseStatus(201);
    }

    public function testShouldNotCreateProductsIfHasSomeErrors()
    {
        // Set
        $repository = m::mock(Repository::class);
        $product = m::mock(Product::class);
        $message = new MessageBag([['name' => 'Should not be blank.']]);

        $attributes = [];
        $response = [
            'errors' => [
                'name' => 'Should not be blank.'
            ]
        ];

        // Expect
        $this->instance(Repository::class, $repository);
        $repository->shouldReceive('create')
            ->once()
            ->with($attributes)
            ->andReturn($product);

        $product->shouldReceive('errors')
            ->twice()
            ->andReturn($message);

        // Act
        $this->call('POST', "/api/v1/product", $attributes);

        // Assert
        $this->assertResponseStatus(422);
        $this->assertJsonResponse($response);
    }
}
