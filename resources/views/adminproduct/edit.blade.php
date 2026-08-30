@extends('adminview.dashboard')

@section('title', 'Edit Product')

@section('content')
    <div class="container-fluid" style="max-width: 720px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1 fw-semibold">Edit Product</h4>
                <p class="text-muted small mb-0">Update product information</p>
            </div>
            <a href="{{ route('admin.productpage') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.productedit', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Name --}}
                    <div class="mb-4">
                        <label for="name" class="form-label fw-medium">Product Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                            class="form-control form-control-lg @error('name') is-invalid @enderror"
                            placeholder="e.g. iPhone 15 Pro">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">Ks</span>
                            <input type="number" name="price" class="form-control"
                                value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                        </div>
                    </div>

                    {{-- Current Photo --}}
                    <div class="mb-4">
                        <label class="form-label fw-medium">Product Image</label>

                        @if($product->photoupload)
                            <div class="mb-3 text-center">
                                <img src="{{ asset('images/' . $product->photoupload) }}" alt="Current photo"
                                    class="rounded-3 shadow-sm" style="max-height: 180px; object-fit: contain;">
                                <p class="text-muted small mt-2 mb-0">Current photo</p>
                            </div>
                        @endif

                        <div class="upload-box text-center p-4 rounded-3 mb-2" id="uploadBox">
                            <i class="fa-solid fa-cloud-arrow-up fa-2x text-muted mb-2"></i>
                            <p class="mb-1 text-muted small">Click to upload new image</p>
                            <p class="mb-0 text-muted" style="font-size: 0.75rem;">Leave empty to keep current photo</p>
                            <input type="file" name="photoupload" id="photoupload" accept="image/*" class="d-none">
                        </div>

                        <div id="imagePreviewWrap" class="d-none text-center mb-2">
                            <img id="imagePreview" src="" alt="Preview" class="rounded-3 shadow-sm"
                                style="max-height: 180px; object-fit: contain;">
                            <p class="text-muted small mt-2 mb-0">New photo preview</p>
                        </div>

                        @error('photoupload')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.productpage') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <style>
        .upload-box {
            border: 2px dashed var(--bs-border-color);
            background-color: var(--bs-tertiary-bg);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .upload-box:hover {
            border-color: var(--bs-primary);
            background-color: rgba(13, 110, 253, 0.05);
        }

        .form-control {
            border-radius: 10px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uploadBox = document.getElementById('uploadBox');
            const fileInput = document.getElementById('photoupload');
            const previewWrap = document.getElementById('imagePreviewWrap');
            const previewImg = document.getElementById('imagePreview');

            if (uploadBox && fileInput) {
                uploadBox.addEventListener('click', () => fileInput.click());

                fileInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            previewImg.src = e.target.result;
                            previewWrap.classList.remove('d-none');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endsection