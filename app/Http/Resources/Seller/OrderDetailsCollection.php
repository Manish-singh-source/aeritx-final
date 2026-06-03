<?php

namespace App\Http\Resources\Seller;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderDetailsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'data' => $this->collection->map(function($item) use ($request) {
                // if $item is a JsonResource, extract the underlying model
                $model = is_object($item) && isset($item->resource) ? $item->resource : $item;

                // only attempt to read relations when we have an object model
                $shop = is_object($model) ? optional($model->product->seller->sellerShop) : null;
                $sellerAddress = $shop ? $shop->only(['name','email','phone','address']) : null;

                // convert item to array, passing the request to resources toArray
                $arr = is_array($item)
                    ? $item
                    : (method_exists($item, 'toArray') ? $item->toArray($request) : (array) $item);

                $arr['seller_address'] = $sellerAddress;
                return $arr;
            })->values()->all(),
        ];
    }
}
