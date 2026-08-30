@extends('adminview.dashboard')

@section('title', 'Detail')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-semibold">Order #{{ $order->id }}</h4>
            <p class="text-muted small mb-0">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <a href="{{ route('admin.orderpage') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        {{--===== Customer Info ===== --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Customer Info</h6>
                    <p class="mb-1"><strong>Name:</strong> {{ $order->user->name ?? 'Deleted User' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->user->email ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{--===== Product Info ===== --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Product Info</h6>
                    <div class="d-flex gap-3">
                        <img src="{{ asset('images/' . ($order->product->photoupload ?? '')) }}" class="rounded"
                            style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <div class="fw-semibold">{{ $order->product->name ?? 'Deleted Product' }}</div>
                            <div class="text-muted">Quantity: {{ $order->quantity }}</div>
                            <div class="fw-bold mt-1">Total: {{ number_format($order->total_price, 2) }} Ks</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--===== Status Update ===== --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Update Status</h6>
                    <form action="{{ route('admin.orderstatus', $order->id) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select" style="max-width: 220px;">
                            @foreach(['pending', 'processing', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection