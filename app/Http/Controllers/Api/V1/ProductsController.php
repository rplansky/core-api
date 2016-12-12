<?php
namespace Boitata\Http\Controllers\Api\V1;

use Boitata\Core\Product\Repository;
use Boitata\Http\Controllers\Api\Controller;
use Boitata\Http\Controllers\Api\Transformers\Product as Transformer;
use Illuminate\Http\Request;

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

    public function create(Request $request)
    {
        $product = $this->repository->create($request->all());

        if ($product->errors()->count()) {
            return $this->respondWithErrors($product->errors()->all(), 422);
        }

        return $this->respond(['success' => true, 'errors' => []], 201);
    }
}