<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Settings\NotificationType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSellerPrice;
use App\Models\ProductStock;
use App\Models\Order;
use App\Models\Seller;
use App\Models\ProductShippingDelivery;
use App\Models\ShippingDelivery;
use App\Models\Tax;
use App\Traits\Notify;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class SellerProductController extends Controller
{


    use Notify;
    public function __construct(){
        $this->middleware(['permissions:view_seller']);
        $this->middleware(['permissions:create_product'])->only('create');
        $this->middleware(['permissions:update_product'])->only('edit', 'update');
    }

    private function ensureSellerPriceOffer(Product $product): void
    {
        $sellerId = (int) $product->seller_id;

        if ($sellerId <= 0 || !Seller::whereKey($sellerId)->exists()) {
            $sellerId = (int) $product->sellerPriceOffers()
                ->whereHas('seller')
                ->orderBy('id')
                ->value('seller_id');
        }

        if ($sellerId > 0 && Seller::whereKey($sellerId)->exists()) {
            ProductSellerPrice::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'seller_id' => $sellerId,
                ],
                [
                    'seller_price' => $product->price,
                    'quantity' => (int) $product->stock->sum('qty'),
                    'assigned_by' => auth('admin')->id(),
                    'submitted_at' => $product->updated_at,
                ]
            );
        }
    }

    public function create()
    {
        $title = translate('Add new seller product');
        $attributes = Attribute::with('value')->get();
        $brands = Brand::where('status', 1)->select('id', 'name')->get();
        $shippingDeliveries = ShippingDelivery::get(['name','id']);
        $categories = Category::where('status', "1")->select('id', 'name')->with('parent')->get();
        $taxes = Tax::active()->get();

        return view('admin.product.create', compact('title', 'categories', 'brands', 'attributes', 'shippingDeliveries', 'taxes'))
            ->with('sellerProductMode', true);
    }

    public function index()
    {
        $title = translate('Seller all products');
        $products = Product::latest()
                                ->sellerProduct()
                                ->search()
                                ->physical()
                                ->with('seller', 'category', 'order')
                                ->paginate(site_settings('pagination_number',10));
        return view('admin.seller_product.index', compact('title', 'products'));
    }

    public function new()
    {
        $title = translate('Seller new products');
        $products = Product::sellerProduct()
                                ->physical()
                                ->search()
                                ->new()
                                ->latest()
                                ->with('seller', 'category', 'order')
                                ->paginate(site_settings('pagination_number',10));
        return view('admin.seller_product.index', compact('title', 'products'));
    }

    public function approved()
    {
        $title = translate('Seller approved products');
        $products = Product::sellerProduct()
                                     ->physical()
                                     ->search()
                                     ->published()
                                     ->latest()
                                     ->with('seller', 'category', 'order')
                                     ->paginate(site_settings('pagination_number',10));

        return view('admin.seller_product.index', compact('title', 'products'));
    }

    public function refuse()
    {
        $title = translate('Seller cancel products');
        $products = Product::sellerProduct()
                                    ->physical()
                                    ->search()
                                    ->inactive()
                                    ->latest()
                                    ->with('seller', 'category', 'order')
                                    ->paginate(site_settings('pagination_number',10));
        return view('admin.seller_product.index', compact('title', 'products'));
    }

    public function trashed()
    {
        $title = translate('Trashed products');
        $products = Product::with(['seller','category','order'])
                                    ->sellerProduct()
                                    ->search()
                                    ->onlyTrashed()
                                    ->paginate(site_settings('pagination_number',10));

        return view('admin.seller_product.index', compact('title', 'products'));
    }

    public function details($id)
    {
        $title = translate('Product details');
        $product = Product::whereNotNull('seller_id')->where('id', $id)->firstOrFail();
        $this->ensureSellerPriceOffer($product);

        $product->load([
            'shippingDelivery' => function ($q) {
                return $q->with(['shippingDelivery']);
            },
            'sellerPriceOffers.seller',
        ])->loadCount('rating');
        return view('admin.seller_product.details', compact('title','product'));
    }

    public function edit($id)
    {
        $title = translate('Update seller product');
        $product = Product::with(['seller', 'sellerPriceOffers'])
            ->whereNotNull('seller_id')
            ->where('id', $id)
            ->firstOrFail();
        $attributes = Attribute::with('value')->get();
        $brands = Brand::where('status', 1)->select('id', 'name')->get();
        $shippingDeliveries = ShippingDelivery::get(['name', 'id']);
        $categories = Category::where('status', '1')->select('id', 'name')->with('parent')->get();
        $taxes = Tax::with(['products' => function (BelongsToMany $q) use ($product): BelongsToMany {
            return $q->where('product_id', $product->id);
        }])->active()->get();

        $this->ensureSellerPriceOffer($product);
        $product->load('sellerPriceOffers');

        $selectedSellerIds = $product->sellerPriceOffers->pluck('seller_id')->map(fn ($sellerId) => (int) $sellerId)->values()->all();

        return view('admin.product.edit', compact('title', 'product', 'attributes', 'brands', 'categories', 'shippingDeliveries', 'taxes', 'selectedSellerIds'))
            ->with('sellerProductMode', true)
            ->with('sellerProductUpdateMode', true);
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        $product = Product::whereNotNull('seller_id')
            ->where('id', $id)
            ->firstOrFail();
        $featuredImage = $product->featured_image;
        $resolvedPrice = $request->filled('price') ? $request->price : $product->price;
        $resolvedDiscountPercentage = $request->filled('discount_percentage')
            ? $request->discount_percentage
            : $product->discount_percentage;

        if ($request->hasFile('featured_image')) {
            try {
                $featuredImage = store_file($request->featured_image, file_path()['product']['featured']['path']);
            } catch (\Exception $exp) {
            }
        }

        $product->update([
            'name'=> $request->filled('name') ? $request->name : $product->name,
            'slug'=> $request->filled('slug') ? make_slug($request->slug) : $product->slug,
            'point'=> $request->filled('point') ? $request->point : $product->point,
            'hsn'=> $request->filled('hsn') ? $request->hsn : $product->hsn,
            'product_type' => Product::PHYSICAL,
            'price'=> $resolvedPrice,
            'weight'=> $request->filled('weight') ? $request->weight : $product->weight,
            'shipping_fee'=> $request->filled('shipping_fee') ? $request->shipping_fee : $product->shipping_fee,
            'shipping_fee_multiply'=> $request->shipping_fee_multiply ?  $request->shipping_fee_multiply : 0,
            'discount'=> !is_null($resolvedDiscountPercentage) && $resolvedDiscountPercentage !== ''
                ? $resolvedPrice - ($resolvedPrice * $resolvedDiscountPercentage / 100)
                : $product->discount,
            'discount_percentage'=> $resolvedDiscountPercentage,
            'minimum_purchase_qty'=> $request->filled('minimum_purchase_qty') ? $request->minimum_purchase_qty : $product->minimum_purchase_qty,
            'maximum_purchase_qty'=> $request->filled('maximum_purchase_qty') ? $request->maximum_purchase_qty : $product->maximum_purchase_qty,
            'brand_id'=> $request->filled('brand_id') ? $request->brand_id : $product->brand_id,
            'category_id'=> $request->filled('category_id') ? $request->category_id : $product->category_id,
            'sub_category_id'=> $request->filled('subcategory_id') ? $request->subcategory_id : $product->sub_category_id,
            'short_description'=>  $request->filled('short_description') ? build_dom_document($request->short_description) : $product->short_description,
            'description'=> $request->filled('description') ? build_dom_document($request->description) : $product->description,
            'warranty_policy' => $request->has('warranty_policy') ? $request->warranty_policy : $product->warranty_policy,
            'featured_image'=> $featuredImage,
            'status'=> $request->has('status') ? $request->status : $product->status,
            'meta_title'=> $request->filled('meta_title') ? $request->meta_title : $product->meta_title,
            'meta_keywords'=> $request->has('meta_keywords') ? $request->meta_keywords : $product->meta_keywords,
            'meta_description'=> $request->filled('meta_description') ? $request->meta_description : $product->meta_description,
            'meta_image'=> $featuredImage,
        ]);

        if ($request->has('tax_id') && is_array($request->input('tax_id'))) {
            $taxes = [];
            $taxAmounts = $request->input('tax_amount');
            $taxTypes = $request->input('tax_type');
            foreach ($request->input('tax_id') as $index => $taxId) {
                $val['product_id'] = $product->id;
                $val['tax_id'] = $taxId;
                $val['amount'] = @$taxAmounts[$index] ?? 0;
                $val['type'] = @$taxTypes[$index] ?? 0;
                array_push($taxes, $val);
            }

            $product->taxes()->detach();
            $product->taxes()->attach($taxes);
        }

        if ($request->hasFile('gallery_image')) {
            $galleryImage = array_filter($request->gallery_image);
            \App\Http\Utility\ProductGallery::imageStore($request, $galleryImage, $product->id);
        }

        $collection = collect($request);
        $choice_options = array();
        if ($request->has('choice_no') && isset($collection['choice_no']) && $collection['choice_no']) {
            $str = '';
            $item = array();

            foreach ($collection['choice_no'] as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['attribute_id'] = $no;
                $attribute_data = array();
                foreach ($collection[$str] as $key => $eachValue) {
                    array_push($attribute_data, $eachValue);
                }
                unset($collection[$str]);

                $item['values'] = $attribute_data;
                array_push($choice_options, $item);
            }
        }

        if ($request->has('choice_no')) {
            $choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);
            if (isset($collection['choice_no']) && $collection['choice_no']) {
                $attributes = json_encode($collection['choice_no']);
                unset($collection['choice_no']);
            } else {
                $attributes = json_encode(array());
            }

            $product->attributes_value = $choice_options;
            $product->attributes = $attributes;
            $product->save();

            foreach ($product->stock as $stock) {
                $stock->delete();
            }

            $this->stockStore($request->only([
                'choice_no', 'product_id',
            ]), $product);
        }

        if ($request->has('shipping_delivery_id')) {
            ProductShippingDelivery::where('product_id', $product->id)->delete();
            if ($request->shipping_delivery_id[0] == 0) {
                $shippingDeliveryIds = ShippingDelivery::pluck('id');

                foreach ($shippingDeliveryIds as $value) {
                    ProductShippingDelivery::create([
                        'product_id' => $product->id,
                        'shipping_delivery_id' => $value,
                    ]);
                }
            } else {
                foreach ($request->shipping_delivery_id as $value) {
                    ProductShippingDelivery::create([
                        'product_id' => $product->id,
                        'shipping_delivery_id' => $value,
                    ]);
                }
            }
        }

        $sellerIds = array_values(array_unique(array_filter((array) $request->input('seller_ids', []))));
        $sellerIds = Seller::whereIn('id', $sellerIds)
            ->pluck('id')
            ->map(fn ($sellerId) => (int) $sellerId)
            ->values()
            ->all();

        if (count($sellerIds) > 0) {
            ProductSellerPrice::where('product_id', $product->id)
                ->whereNotIn('seller_id', $sellerIds)
                ->delete();

            foreach ($sellerIds as $sellerId) {
                ProductSellerPrice::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'seller_id' => (int) $sellerId,
                    ],
                    [
                        'assigned_by' => auth('admin')->id(),
                        'quantity' => 0,
                    ]
                );
            }

            $primarySellerId = (int) ($sellerIds[0] ?? $product->seller_id);
            if ($primarySellerId > 0 && Seller::whereKey($primarySellerId)->exists() && (int) $product->seller_id !== $primarySellerId) {
                $product->seller_id = $primarySellerId;
                $product->save();
            }
        }

        return back()->with('success', translate('Seller product has been updated'));
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:products,id',
        ]);
        $product = Product::whereNotNull('seller_id')->where('id', $request->id)->delete();
        return back()->with('success',translate("Seller product has been deleted"));
    }

    public function approvedBy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:products,id',
        ]);


        $product = Product::sellerProduct()->physical()->where('id', $request->id)->firstOrFail();
        $product->status = Product::PUBLISHED;
        $product->save();
        return back()->with('success',translate("Seller product has been approved"));
    }

    public function cancelBy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:products,id',
        ]);

        $product = Product::sellerProduct()->physical()->where('id', $request->id)->firstOrFail();
        $product->status = Product::INACTIVE;
        $product->save();
        return back()->with('success',translate("Seller product has been inactive"));
    }

    public function restore(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:products,id'
        ]);
        $product = Product::whereNotNull('seller_id')->onlyTrashed()->where('id', $request->id)->restore();
        return back()->with('success',translate("Seller product has been restore"));
    }




    public function singleProductAllOrder($id)
    {
        $product = Product::sellerProduct()->physical()->where('id', $id)->firstOrFail();
        $title = ucfirst($product->name). " all orders log";
        $orders = Order::sellerOrder()->physicalOrder()->whereHas('orderDetails', function($q) use ($product){
            $q->where('product_id', $product->id);
        })->orderBy('id', 'DESC')->with('customer')->paginate(site_settings('pagination_number',10));
        return view('admin.seller_order.index', compact('title', 'orders'));
    }

    public function singleProductPlacedOrder($id)
    {
        $product = Product::sellerProduct()->physical()->where('id', $id)->firstOrFail();
        $title = ucfirst($product->name). " placed orders log";
        $orders = Order::sellerOrder()->physicalOrder()->whereHas('orderDetails', function($q) use ($product){
            $q->where('product_id', $product->id)->where('status', 1);
        })->orderBy('id', 'DESC')->with('customer')->paginate(site_settings('pagination_number',10));
        return view('admin.seller_order.index', compact('title', 'orders'));
    }

    public function singleProductDeliveredOrder($id)
    {
        $product = Product::sellerProduct()->physical()->where('id', $id)->firstOrFail();
        $title = ucfirst($product->name). " delivered orders log";
         $orders = Order::sellerOrder()->physicalOrder()->whereHas('orderDetails', function($q) use ($product){
            $q->where('product_id', $product->id)->where('status', 5);
        })->orderBy('id', 'DESC')->with('customer')->paginate(site_settings('pagination_number',10));
        return view('admin.seller_order.index', compact('title', 'orders'));
    }


    public function sellerProductUpdateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'status' => 'required|in:0,1,2',
        ]);

        $product = Product::with(['seller'])->where('id', $request->id)->first();
        $product->status = $request->status;
        $product->save();


        #FIREBASE NOTIFICATIONS
        if($product->seller &&  $product->seller->fcm_token){
            $payload = (object) [
                "title"               => translate('Product'),
                "message"             => translate('Your product status has been updated by system admin'),
                "product_uid"         => $product->uid,
                "type"                => NotificationType::PRODUCT_UPDATE->value,
            ];
            $this->fireBaseNotification($product->seller->fcm_token,$payload);
        }

        return back()->with('success',translate("Product status has been successfully updated"));
    }

    public function productGalleryImageDelete($id)
    {
        $productGallery = ProductImage::findOrFail($id);
        Product::whereNotNull('seller_id')->where('id', $productGallery->product_id)->firstOrFail();
        $file = file_path()['product']['gallery']['path'].'/'.$productGallery->image;
        if (file_exists($file)) {
            @unlink($file);
        }
        $productGallery->delete();

        return back()->with('success', translate("Gallery image has been deleted"));
    }

    public function stockStore(array $data, $product)
    {
        $collection = collect($data);
        $options = array();

        if ($collection->has('choice_no')) {
            foreach ($collection['choice_no'] as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();

                foreach (request()[$name] as $key => $eachValue) {
                    array_push($data, $eachValue);
                }
                array_push($options, $data);
            }
        }

        $combinations = $this->combineAttr($options);

        if (count($combinations[0]) > 0) {
            $product->variant_product = 1;
            $product->save();
            foreach ($combinations as $key => $combination) {
                $str = $this->combinationString($combination, $collection);

                $stock = new ProductStock();
                $stock->product_id = $product->id;
                $stock->attribute_value = $str;
                $stock->display_name = request()['display_name_' . str_replace('.', '_', $str)];
                $stock->price = request()['price_' . str_replace('.', '_', $str)];
                $stock->qty = request()['qty_' . str_replace('.', '_', $str)];
                $stock->save();
            }
        }
    }

    public static function combinationString($combination, $collection)
    {
        $str = '';
        foreach ($combination as $key => $item) {
            if ($key > 0) {
                $str .= '-' . str_replace(' ', '', $item);
            } else {
                $str .= str_replace(' ', '', $item);
            }
        }
        return $str;
    }

    public function combineAttr($arrays)
    {
        $resultArr = array(array());

        foreach ($arrays as $key => $arr_values) {
            $tempArr = array();
            foreach ($resultArr as $resultArr_item) {
                foreach ($arr_values as $arr_val) {
                    $tempArr[] = array_merge($resultArr_item, array($key => $arr_val));
                }
            }
            $resultArr = $tempArr;
        }

        return $resultArr;
    }
}
