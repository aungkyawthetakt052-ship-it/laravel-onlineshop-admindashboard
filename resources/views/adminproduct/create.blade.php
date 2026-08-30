@extends('adminview.dashboard')

@section('title', 'Create Product')

@section('content')
  <div class="container-fluid" style="max-width: 720px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-1 fw-semibold">Create Product</h4>
        <p class="text-muted small mb-0">Add a new product to your store</p>
      </div>
      <a href="{{ route('admin.productpage') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back
      </a>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <form action="{{ route('admin.productcreate') }}" method="POST" enctype="multipart/form-data">
          @csrf
          {{-- Product Name --}}
          <div class="mb-4">
            <label for="name" class="form-label fw-medium">Product Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
              class="form-control form-control-lg @error('name') is-invalid @enderror" placeholder="e.g. iPhone 15 Pro"
              autofocus>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Product Price --}}
          <div class="mb-3">
            <label class="form-label">Price</label>
            <div class="input-group">
              <span class="input-group-text">Baht</span>
              <input type="number" name="price" class="form-control" value="{{ old('price') }}" step="0.01" min="0"
                required>
            </div>
          </div>

          {{-- Product Image --}}
          <div class="mb-4">
            <label class="form-label fw-medium">Product Image <span class="text-danger">*</span></label>

            {{-- Upload Box (ပုံမရွေးခင် ပြ) --}}
            <div class="upload-box text-center p-4 rounded-3" id="uploadBox">
              <i class="fa-solid fa-cloud-arrow-up fa-2x text-muted mb-2"></i>
              <p class="mb-1 text-muted small">Click to upload image</p>
              <p class="mb-0 text-muted" style="font-size: 0.75rem;">PNG, JPG up to 2MB</p>
              <input type="file" name="photoupload" id="photoupload" accept="image/*" class="d-none">
            </div>

            {{-- Preview Box (ပုံရွေးပြီးမှ ပြ) --}}
            <div id="imagePreviewWrap" class="d-none">
              <div class="preview-card position-relative rounded-3 overflow-hidden">
                <img id="imagePreview" src="" alt="Preview" class="preview-img">

                <div class="preview-overlay">
                  <button type="button" class="btn btn-sm btn-light me-2" id="changeImageBtn" title="Change image">
                    <i class="fa-solid fa-camera"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-danger" id="removeImageBtn" title="Remove image">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </div>
              <p class="text-muted small text-center mt-2 mb-0">Click camera to change · trash to remove</p>
            </div>

            @error('photoupload')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.productpage') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary px-4">
              <i class="fa-solid fa-upload me-1"></i> Upload Product
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
      background-color: rgba(13, 110, 253, 0.06);
    }

    /* Preview card */
    .preview-card {
      width: 100%;
      max-width: 320px;
      margin: 0 auto;
      background: var(--bs-tertiary-bg);
      border: 1px solid var(--bs-border-color);
      aspect-ratio: 1 / 1;
    }

    .preview-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .preview-overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.45);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.2s ease;
    }

    .preview-card:hover .preview-overlay {
      opacity: 1;
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const uploadBox = document.getElementById('uploadBox');
      const fileInput = document.getElementById('photoupload');
      const previewWrap = document.getElementById('imagePreviewWrap');
      const previewImg = document.getElementById('imagePreview');
      const changeBtn = document.getElementById('changeImageBtn');
      const removeBtn = document.getElementById('removeImageBtn');

      function showPreview(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          previewImg.src = e.target.result;
          uploadBox.classList.add('d-none');
          previewWrap.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
      }

      function clearPreview() {
        fileInput.value = '';
        previewImg.src = '';
        previewWrap.classList.add('d-none');
        uploadBox.classList.remove('d-none');
      }

      uploadBox.addEventListener('click', () => fileInput.click());
      changeBtn.addEventListener('click', () => fileInput.click());
      removeBtn.addEventListener('click', clearPreview);

      fileInput.addEventListener('change', function () {
        if (this.files[0]) {
          showPreview(this.files[0]);
        }
      });
    });
  </script>
@endsection