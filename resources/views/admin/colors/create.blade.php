@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title text-white mb-0">Thêm Màu Sắc Mới</h4>
                        <a href="{{ route('colors.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('colors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="name" class="form-label font-weight-bold">Tên màu sắc <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Ví dụ: Đỏ, Xanh lá, Xanh dương..." required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="code" class="form-label font-weight-bold">Mã màu sắc <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                               id="code" name="code" value="{{ old('code') }}" 
                                               placeholder="#RRGGBB" required>
                                        <div class="input-group-append">
                                            <input type="color" class="form-control form-control-color" 
                                                   id="colorPicker" value="#563d7c"
                                                   style="width: 50px; height: 38px;">
                                        </div>
                                    </div>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="product_id" class="form-label font-weight-bold">Sản phẩm <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('product_id') is-invalid @enderror" 
                                            id="product_id" name="product_id" required>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="quantity" class="form-label font-weight-bold">Số lượng <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" value="{{ old('quantity', 0) }}" 
                                           min="0" placeholder="Nhập số lượng" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label font-weight-bold">Hình ảnh màu sắc</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('images') is-invalid @enderror" 
                                               id="images" name="images[]" multiple accept="image/*">
                                        <label class="custom-file-label" for="images">Chọn hình ảnh</label>
                                        @error('images')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Có thể chọn nhiều hình ảnh. Định dạng: JPG, PNG, JPEG.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="image-preview-container d-flex flex-wrap" id="imagePreviewContainer"></div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 text-center">
                                <hr>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save mr-2"></i> Lưu Màu Sắc
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg px-5 ml-2">
                                    <i class="fas fa-redo mr-2"></i> Làm Mới
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Hiển thị màu đã chọn -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Xem trước màu đã chọn</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div id="colorPreview" style="width: 100px; height: 100px; border-radius: 8px; border: 1px solid #ddd;"></div>
                        <div class="ml-4">
                            <h4 id="colorNamePreview">Tên màu</h4>
                            <p id="colorCodePreview" class="mb-0">Mã màu: #000000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Chọn sản phẩm',
            allowClear: true
        });

        // Đồng bộ giữa input text và color picker
        $('#colorPicker').on('input', function() {
            $('#code').val($(this).val());
            updateColorPreview();
        });

        $('#code').on('input', function() {
            $('#colorPicker').val($(this).val());
            updateColorPreview();
        });

        $('#name').on('input', function() {
            updateColorPreview();
        });

        // Cập nhật xem trước màu
        function updateColorPreview() {
            const colorCode = $('#code').val() || '#000000';
            const colorName = $('#name').val() || 'Tên màu';
            
            $('#colorPreview').css('background-color', colorCode);
            $('#colorNamePreview').text(colorName);
            $('#colorCodePreview').text('Mã màu: ' + colorCode);
        }

        // Xem trước hình ảnh
        $('#images').on('change', function() {
            const files = Array.from(this.files);
            const container = $('#imagePreviewContainer');
            container.empty();

            files.forEach(file => {
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = $(`
                            <div class="position-relative mr-3 mb-3">
                                <img src="${e.target.result}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `);
                        container.append(preview);
                        
                        preview.find('button').on('click', function() {
                            preview.remove();
                        });
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Hiển thị tên file đã chọn
            if (files.length > 0) {
                $(this).next('.custom-file-label').html(files.length + ' tệp đã chọn');
            } else {
                $(this).next('.custom-file-label').html('Chọn hình ảnh');
            }
        });

        // Khởi tạo hiển thị màu
        updateColorPreview();
    });
</script>
@endpush
@endsection