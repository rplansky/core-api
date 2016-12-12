<?php
namespace Boitata\Http\Controllers\Api\Transformers;


use Boitata\Core\Product\Product;

class Characteristic
{
    /**
     * Transform characteristic attributes before send to front end.
     *
     * @param Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        $data = [];

        foreach ($product->characteristics() as $index => $characteristic) {
            $data[$index]['suffix'] = $characteristic->suffix;
            $data[$index]['name'] = $characteristic->name;
            $data[$index]['values'] = $characteristic->values;
            $data[$index]['prefix'] = $characteristic->prefix;
            $data[$index]['type'] = $characteristic->type;
            $data[$index] = array_filter($data[$index]);
        }

        return $data;
    }
}