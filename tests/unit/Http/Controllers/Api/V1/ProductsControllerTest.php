<?php
namespace Boitata\Http\Controllers\Api\V1;

use Boitata\Core\Product\Product;
use Boitata\Core\Product\Repository;
use Boitata\Http\Controllers\Api\Transformers\Product as ProductTransformer;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use TestCase;
use Mockery as m;

class ProductsControllerTest extends TestCase
{
    use WithoutMiddleware, \WithFramework;

    public function testGetProductInformation()
    {
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

        $repository->shouldReceive('firstOrFail')
            ->once()
            ->andReturn($product);

        $transformer->shouldReceive('transform')
            ->once()
            ->andReturn($response);

        $this->app->instance(Repository::class, $repository);
        $this->app->instance(ProductTransformer::class, $transformer);

        $this->call('GET', "/api/v1/product/{$id}");
        $this->assertJsonResponse($response);
    }
}
