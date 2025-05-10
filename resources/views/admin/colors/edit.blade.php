@extends('admin.layouts.app')

@section('content')
<style>
    #product_id {
        width: 100%;
        max-height: 250px;
        overflow-y: auto;
    }
    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid #ccc;
        display: inline-block;
        margin-left: 10px;
        z-index: 100;
    }
</style>

<div class="container mt-4">
    <!-- Tiêu đề -->
    <div class="card shadow-lg p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2><i class="bi bi-palette me-2"></i>Edit Color</h2>
            <a href="{{ route('colors.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-2"></i> Back to List
            </a>
        </div>

        <!-- Form chỉnh sửa -->
        <form action="{{ route('colors.update', $color->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Color Name with Preview -->
            <div class="mb-3 d-flex align-items-center">
                <div class="flex-grow-1">
                    <label for="color" class="form-label fw-bold">
                        <i class="bi bi-palette2 me-2"></i>Color Name
                    </label>
                    <input type="text" id="color" name="color" class="form-control"
                           value="{{ old('color', $color->color) }}" 
                           placeholder="Enter color name or hex code..."
                           oninput="document.getElementById('colorPreview').style.backgroundColor = this.value">
                    <small class="form-text text-muted">Ex: red, navy blue, yellow or #ff0000</small>
                    @error('color')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div id="colorPreview" class="color-preview" style="background-color: {{ old('color', $color->color) }}"></div>
            </div>

            <!-- Select Product -->
            <div class="mb-3">
                <label for="product_id" class="form-label fw-bold">
                    <i class="bi bi-box-seam me-2"></i>Product
                </label>
                <select id="product_id" name="product_id" class="form-select">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $color->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->product_name }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Quantity -->
            <div class="mb-3">
                <label for="quantity" class="form-label fw-bold">
                    <i class="bi bi-sort-numeric-up me-2"></i>Quantity
                </label>
                <input type="number" id="quantity" name="quantity" class="form-control"
                       value="{{ old('quantity', $color->quantity) }}" min="0" placeholder="Enter quantity">
                @error('quantity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('colors.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý cập nhật màu sắc
        var colorInput = document.getElementById('color');
        var colorPreview = document.getElementById('colorPreview');
        
        // Hàm cập nhật màu
        function updateColor() {
            var colorValue = colorInput.value.trim();
            colorPreview.style.backgroundColor = colorValue;
        }
        
        // Đăng ký nhiều loại sự kiện để đảm bảo cập nhật trong mọi trường hợp
        colorInput.addEventListener('input', updateColor);
        colorInput.addEventListener('change', updateColor);
        colorInput.addEventListener('keyup', updateColor);
        colorInput.addEventListener('paste', function() {
            setTimeout(updateColor, 10);
        });
        
        // Cập nhật ban đầu
        updateColor();
    });
</script>
@endsection