@extends('admin.layouts.app')

@section('main_content')
<div class="page-content">
    <div class="container-fluid">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ translate($title) }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ translate('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.product.seller.index') }}">{{ translate('Seller Products') }}</a></li>
                    <li class="breadcrumb-item active">{{ translate('Update Seller Product') }}</li>
                </ol>
            </div>
        </div>

        <form autocomplete="off" class="needs-validation" action="{{ route('admin.product.seller.update', $product->id) }}" method="POST">
            @csrf
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="card">
                        <div class="card-header border-bottom-dashed">
                            <h5 class="card-title mb-0">{{ translate('Seller Product Update Access') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                {{ translate('Only price, quantity, and HSN can be updated from this admin seller-product form.') }}
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ translate('Product') }}</label>
                                    <input type="text" class="form-control" value="{{ $product->name }}" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ translate('Seller') }}</label>
                                    <input type="text" class="form-control" value="{{ $product->seller?->name ?? translate('N/A') }}" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="hsn">{{ translate('HSN') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="hsn" name="hsn" min="0" value="{{ old('hsn', $product->hsn) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="price">{{ translate('Price') }} <span class="text-danger">*</span></label>
                                    <input type="number" step="any" class="form-control" id="price" name="price" min="0.01" value="{{ old('price', $sellerPriceOffer->seller_price ?? $product->price) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="hsn">{{ translate('HSN') }}</label>
                                    <input type="number" class="form-control" id="hsn" name="hsn" min="0" value="{{ old('hsn', $product->hsn ?? '') }}" placeholder="{{ translate('Enter HSN code') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="quantity">{{ translate('Quantity') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="0" value="{{ old('quantity', $sellerPriceOffer->quantity ?? 0) }}" required>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.product.seller.index') }}" class="btn btn-light">{{ translate('Back') }}</a>
                                <button type="submit" class="btn btn-primary">{{ translate('Update Product') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
