@extends('adminview.dashboard')

@section('title', 'Create Order')

@section('content')
  <div class="container-fluid" style="max-width: 900px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-1 fw-semibold">Create New Order</h4>
        <p class="text-muted small mb-0">Create a new order for a customer</p>
      </div>
      <a href="{{ route('admin.orderpage') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back
      </a>
    </div>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <form action="{{ route('admin.orderstore') }}" method="POST">
          @csrf

          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Customer</label>
              <select name="user_id" class="form-select" required>
                <option value="">-- Select Customer --</option>
                @foreach($users as $user)
                  <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Product</label>
              <select name="product_id" id="productSelect" class="form-select" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                  <option value="{{ $product->id }}" data-price="{{ $product->price ?? 0 }}"
                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                    {{ $product->name }} - {{ number_format($product->price ?? 0, 2) }} Baht
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Quantity</label>
              <input type="number" name="quantity" id="quantityInput" class="form-control"
                value="{{ old('quantity', 1) }}" min="1" required>
            </div>

            <div class="col-md-4">
              <label class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Total Price</label>
              <input type="text" id="totalPricePreview" class="form-control" value="0.00 Baht" readonly>
              <small class="text-muted">Auto-calculated</small>
            </div>

          </div>

          <div class="mt-4 d-flex justify-content-end gap-2">
            <a href="{{ route('admin.orderpage') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary px-4">
              <i class="fa-solid fa-check me-1"></i> Create Order
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<style>
  /* ===== No blue focus border ===== */
  .form-control:focus,
  .form-select:focus {
    border-color: #ced4da !important;
    box-shadow: none !important;
    outline: none !important;
  }

  [data-bs-theme="dark"] .form-control:focus,
  [data-bs-theme="dark"] .form-select:focus {
    border-color: #495057 !important;
    box-shadow: none !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const productSelect = document.getElementById('productSelect');
    const quantityInput = document.getElementById('quantityInput');
    const totalPricePreview = document.getElementById('totalPricePreview');

    function updateTotalPreview() {
      const selectedOption = productSelect.options[productSelect.selectedIndex];
      const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
      const quantity = parseInt(quantityInput.value) || 0;
      const total = price * quantity;

      totalPricePreview.value = total.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' Baht';
    }

    if (productSelect) productSelect.addEventListener('change', updateTotalPreview);
    if (quantityInput) quantityInput.addEventListener('input', updateTotalPreview);
  });
</script>
@endsection