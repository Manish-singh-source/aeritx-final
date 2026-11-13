<?php

namespace App\Http\Resources;

use App\Enums\ProductStatus;
use App\Enums\StatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $shop = $this->resource?->sellerShop;
        $shopName = $shop?->name ?: ($this->resource?->name ?: 'seller');

        return [
            "id"              => $this->id,
            "name"            => $shop?->name,
            'logo'            => show_image(file_path()['shop_logo']['path'].'/'.($shop?->shop_logo ?? '')),
            'banner'          => show_image(file_path()['shop_first_image']['path'].'/'.($shop?->shop_first_image ?? '')),
            'created_at'      => diff_for_humans($this->created_at),
            'email'           => ($this->email),
            'phone'           => ($this->phone),
            'rating'          => $this->rating ? $this->rating : 0,
            'total_products'  => $this->product_count,
            'short_details'   => $shop?->short_details,
            'whatsapp_number' => $shop?->whatsapp_number,
            'is_whatsapp_order_active'  => 
                                            $shop?->whatsapp_order == StatusEnum::true->status() 
                                               ? true : false,
            'total_followers' => $this->follow?->count() ?? 0,
            'link'            => route('seller.store.visit',[make_slug($shopName), $this->id]),
            'followers'       => (array)($this->follow?->pluck('following_id')->toArray() ?? [])
     ];
    }
}
