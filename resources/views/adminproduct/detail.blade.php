@extends('adminview.dashboard')

@section('title', 'Product Detail')

@section('content')

    {{--===== Header ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1 fw-semibold">Product Detail</h4>
            <p class="text-muted small mb-0">View full product information</p>
        </div>

        <a href="{{ route('admin.productpage') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="row g-4">

                {{--===== Product Image ===== --}}
                <div class="col-md-4 text-center">
                    <img src="{{ asset('images/' . $product->photoupload) }}" alt="{{ $product->name }}"
                        class="img-fluid rounded shadow-sm" style="max-height: 260px; object-fit: cover;">
                </div>

                {{--===== Product Info ===== --}}
                <div class="col-md-8">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th style="width: 160px;" class="text-muted">Product ID</th>
                            <td>#{{ $product->id }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Product Name</th>
                            <td class="fw-semibold">{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Image File</th>
                            <td>{{ $product->photoupload }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Created At</th>
                            <td>{{ $product->created_at?->format('d M Y, h:i A') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Updated At</th>
                            <td>{{ $product->updated_at?->format('d M Y, h:i A') ?? '-' }}</td>
                        </tr>
                    </table>

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('admin.producteditpage', $product->id) }}" class="btn btn-primary">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit Product
                        </a>
                        <form action="{{ route('admin.productdelete', $product->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-btn">
                                <i class="fa-solid fa-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection