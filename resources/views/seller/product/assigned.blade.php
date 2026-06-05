@extends('seller.layouts.app')
@section('main_content')
<div class="page-content">
    <div class="container-fluid">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ translate($title) }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ translate('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ translate('Assigned Products') }}</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <h5 class="card-title mb-0">{{ translate('Assigned Product List') }}</h5>
            </div>

            <div class="card-body pt-0">
                <div class="table-responsive table-card">
                    <table class="table table-hover table-nowrap align-middle mb-0">
                        <thead class="text-muted table-light">
                            <tr class="text-uppercase">
                                <th>{{ translate('Product') }}</th>
                                <th>{{ translate('Base Price') }}</th>
                                <th>{{ translate('Your Price') }}</th>
                                <th>{{ translate('Quantity') }}</th>
                                <th>{{ translate('Added Amount') }}</th>
                                <th>{{ translate('Total Added') }}</th>
                                <th>{{ translate('Updated At') }}</th>
                                <th>{{ translate('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                                @php
                                    $product = $assignment->product;
                                    $basePrice = (float) ($product->price ?? 0);
                                    $sellerPrice = is_null($assignment->seller_price) ? null : (float) $assignment->seller_price;
                                    $quantity = (int) ($assignment->quantity ?? 0);
                                    $addedAmount = is_null($sellerPrice) ? null : ($sellerPrice - $basePrice);
                                    $totalAdded = is_null($sellerPrice) ? null : ($sellerPrice - $basePrice) * $quantity;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $product?->name }}</div>
                                        <div class="text-muted small">{{ $product?->category?->name ? get_translation($product->category->name) : '' }}</div>
                                    </td>
                                    <td>{{ short_amount($basePrice) }} {{ default_currency()->name }}</td>
                                    <td>{{ is_null($sellerPrice) ? translate('Pending') : short_amount($sellerPrice).' '.default_currency()->name }}</td>
                                    <td>{{ $quantity }}</td>
                                    <td>{{ is_null($addedAmount) ? translate('Pending') : short_amount($addedAmount).' '.default_currency()->name }}</td>
                                    <td>{{ is_null($totalAdded) ? translate('Pending') : short_amount($totalAdded).' '.default_currency()->name }}</td>
                                    <td>{{ $assignment->submitted_at ? get_date_time($assignment->submitted_at, 'd M Y h:i A') : translate('Not updated yet') }}</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           class="btn btn-sm btn-primary update-assigned-price"
                                           data-bs-toggle="modal"
                                           data-bs-target="#updatePriceModal"
                                           data-id="{{ $assignment->id }}"
                                           data-price="{{ $assignment->seller_price }}"
                                           data-quantity="{{ $assignment->quantity }}">
                                            {{ translate('Update Price & Quantity') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">{{ translate('No assigned products found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper d-flex justify-content-end mt-4">
                    {{ $assignments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updatePriceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="assigned-price-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ translate('Seller price') }}</label>
                        <input type="number" step="any" name="seller_price" id="seller_price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ translate('Quantity') }}</label>
                        <input type="number" step="1" min="0" name="quantity" id="quantity" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script-push')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('assigned-price-form');
        const priceInput = document.getElementById('seller_price');
        const quantityInput = document.getElementById('quantity');

        document.querySelectorAll('.update-assigned-price').forEach(function (button) {
            button.addEventListener('click', function () {
                const assignmentId = this.dataset.id;
                const price = this.dataset.price || '';
                const quantity = this.dataset.quantity || 0;
                form.action = `{{ url('/seller/assigned/product/price/update') }}/${assignmentId}`;
                priceInput.value = price;
                quantityInput.value = quantity;
            });
        });
    });
</script>
@endpush
