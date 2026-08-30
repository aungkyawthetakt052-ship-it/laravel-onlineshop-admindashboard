@extends('adminview.dashboard')

@section('title', 'Product Management')

@section('content')

  {{--===== Success Alert ======--}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow" id="successAlert"
      role="alert" style="z-index: 1055;">
      <strong>{{ session('success') }}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <script>
      setTimeout(() => {
        const el = document.getElementById('successAlert');
        if (el) {
          el.classList.remove('show');
          setTimeout(() => el.remove(), 300);
        }
      }, 3000);
    </script>
  @endif
  {{--===== End Success Alert ======--}}

  {{--===== Header + Search =====--}}
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
      <h4 class="mb-1 fw-semibold">Product Management</h4>
      <p class="text-muted small mb-0">Manage all products in your store</p>
    </div>

    <div class="d-flex gap-2 flex-wrap">
      <form action="{{ route('admin.productpage') }}" method="GET" class="d-flex">
        <div class="input-group" style="max-width: 320px;">
          <input type="text" name="search" class="form-control" placeholder="Search product name..."
            value="{{ request('search') }}">
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-search"></i>
          </button>
          @if(request('search'))
            <a href="{{ route('admin.productpage') }}" class="btn btn-outline-secondary" title="Clear">
              <i class="fa-solid fa-xmark"></i>
            </a>
          @endif
        </div>
      </form>

      <a href="{{ route('admin.productcreatepage') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> Add Product
      </a>
    </div>
  </div>
  {{--===== End Header + Search =====--}}

  {{--===== Products Table ===== --}}
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-3">ID</th>
            <th>Product</th>
            <th>Image</th>
            <th>Price</th>
            <th>Created</th>
            <th class="text-end pe-3">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $product)
            <tr>
              <td class="ps-3 text-muted">#{{ $product->id }}</td>

              {{--===== Product Name ===== --}}
              <td>
                <span class="fw-medium">{{ $product->name }}</span>
              </td>

              {{--===== Image ===== --}}
              <td>
                <img src="{{ asset('images/' . $product->photoupload) }}" alt="{{ $product->name }}"
                  class="product-thumb preview-image" data-bs-toggle="modal" data-bs-target="#imagePreviewModal"
                  data-image="{{ asset('images/' . $product->photoupload) }}">
              </td>

              {{--===== Price ===== --}}
              <td>
                <span class="fw-semibold">{{ number_format($product->price, 2) }} Baht</span>
              </td>

              {{--===== Created ===== --}}
              <td class="text-muted small">
                {{ $product->created_at?->format('d M Y') ?? '-' }}
              </td>

              {{--===== Action ===== --}}
              <td class="text-end pe-3">
                <div class="dropdown">
                  <button class="btn btn-sm btnDropdown border-0" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                      <a class="dropdown-item" href="{{ route('admin.productdetailpage', $product->id) }}">
                        <i class="fa-solid fa-eye me-2 text-info"></i> View
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('admin.producteditpage', $product->id) }}">
                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Edit
                      </a>
                    </li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                    <li>
                      <form action="{{ route('admin.productdelete', $product->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="dropdown-item text-danger delete-btn">
                          <i class="fa-solid fa-trash me-2"></i> Delete
                        </button>
                      </form>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-5">
                <i class="fa-solid fa-box-open fa-2x mb-2 d-block opacity-50"></i>
                No products found
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{--===== Image Preview Modal ===== --}}
  <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-transparent border-0">
        <div class="modal-body text-center p-0 position-relative">
          <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
            data-bs-dismiss="modal" style="z-index: 10;"></button>
          <img src="" id="previewImage" class="img-fluid rounded shadow" style="max-height: 85vh; object-fit: contain;">
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <style>
    /* Table header - theme aware */
    .table thead th {
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      border-bottom: 1px solid var(--bs-border-color) !important;
    }

    [data-bs-theme="dark"] .table thead th {
      background-color: #2a2a3d !important;
      color: #adb5bd !important;
    }

    [data-bs-theme="light"] .table thead th {
      background-color: #f8f9fa !important;
      color: #6c757d !important;
    }

    /* Product thumbnail */
    .product-thumb {
      width: 56px;
      height: 56px;
      object-fit: cover;
      border-radius: 8px;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      border: 1px solid var(--bs-border-color);
    }

    .product-thumb:hover {
      transform: scale(1.08);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Search clear button */
    .input-group .btn-outline-secondary:hover {
      background-color: #dc3545;
      border-color: #dc3545;
      color: #fff;
    }
  </style>

  <script>
    document.querySelectorAll('.preview-image').forEach(img => {
      img.addEventListener('click', function () {
        document.getElementById('previewImage').src = this.getAttribute('data-image');
      });
    });
  </script>
@endsection