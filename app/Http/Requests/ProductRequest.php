<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\General\FileExtentionCheckRule;
use App\Rules\General\FileLengthCheckRule;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $adminInhouseCreate = $this->routeIs('admin.item.product.inhouse.store');
        $priceRule = 'nullable|numeric|gt:0';

        $sellerIds = array_filter((array) $this->input('seller_ids', []));

        if (
            $this->routeIs('seller.product.store')
            || (
                ! $adminInhouseCreate
                && (
                    ($this->input('seller_id') !== null && $this->input('seller_id') !== '')
                    || count($sellerIds) > 0
                )
            )
        ) {
            $priceRule = 'required|numeric|gt:0';
        }

        $pointRule = $adminInhouseCreate ? 'nullable|numeric|gt:-1|max:2000000' : 'required|numeric|gt:-1|max:2000000';
        $shippingFeeRule = $adminInhouseCreate ? 'nullable|numeric|gt:-1' : 'required|numeric|gt:-1';
        $sellerRule = $adminInhouseCreate && count($sellerIds) > 0 ? 'required|array|min:1' : 'nullable|array|min:1';

       return [
            'name' => 'required|max:255',
            'slug'    => 'required|max:191',
            'price' => $priceRule,
            'hsn' => 'nullable|numeric|min:0',
            'seller_ids' => $sellerRule,
            'seller_ids.*' => 'nullable|exists:sellers,id',
            'point' => $pointRule,
            'weight' => 'nullable|numeric|gt:-1',
            'shipping_fee' => $shippingFeeRule,
            'discount_percentage' => 'nullable|numeric|gt:-1',
            'minimum_purchase_qty' => 'required|integer|min:1',
            'maximum_purchase_qty' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'short_description' => 'required',
            'description' => 'required',
            'warranty_policy' => 'nullable|string',
            'featured_image' => ['required',new FileExtentionCheckRule(file_format())],
            'gallery_image.*' => ['required',new FileExtentionCheckRule(file_format())],
            'meta_title' => 'nullable|max:250',
            'meta_keywords.*' => 'nullable|max:250',
            'meta_description' => 'nullable|max:500',
            'choice_no' => 'required',
            'status' => 'nullable|in:0,1,2',
            'tax_id'=> "nullable|array",
            'tax_id.*'=> "nullable|exists:taxes,id",
            'tax_amount'=> "nullable|array",
            'tax_amount.*'=> "nullable|numeric|gt:-1",
            'tax_type'=> "nullable|array",
            'tax_type.*'=> ["nullable",Rule::in(['0', '1'])]
  
        ];
    }


    public function messages()
    {
       return [
            'name.required' => 'Product title is required',
            'price.required' => 'Product Regular price is required',
            'hsn.numeric' => 'HSN must be a valid number',
            'minimum_purchase_qty.required' => 'Minimum Purchase Quantity is Required',
            'maximum_purchase_qty.required' => 'Maximum Purchase Quantity is Required',
            'category_id.required' => 'Category is required',
            'short_description.required' => 'Short Description is required',
            'description.required' => 'Description is required',
            'featured_image.required' => "Feature Image is required",
            'gallery_image.*.required' => "Gallery Image is Required",
            'choice_no.required' => 'Product Stock is Required',
        ];
    }
}
