<?php
namespace Boitata\Http\Controllers\Api\V1;

use Boitata\Core\Product\Repository;
use Boitata\Http\Controllers\Api\Controller;
use Boitata\Http\Controllers\Api\Transformers\Product as Transformer;

class ProductsController extends Controller
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * ProductsController constructor.
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Transformer $transformer
     * @param $id
     * @return \Boitata\Http\Controllers\Api\JsonResponse
     */
    public function show(Transformer $transformer, $id)
    {
        $product = $this->repository->firstOrFail($id);

        return $this->respond($transformer->transform($product));
    }
}