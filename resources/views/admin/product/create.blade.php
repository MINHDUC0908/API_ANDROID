@extends('admin.layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-lg rounded-3">
        <div class="card-header bg-gradient-primary text-white p-4 rounded-top">
            <div class="d-flex align-items-center">
                <div class="icon-circle bg-white text-primary rounded-circle p-3 me-3">
                    <i class="fas fa-box-open fa-lg"></i>
                </div>
                <div>
                    <h4 class="mb-1">Thêm sản phẩm mới</h4>
                    <p class="text-white-50 mb-0">Điền đầy đủ thông tin sản phẩm vào biểu mẫu dưới đây</p>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Phần thông tin cơ bản -->
                <div class="section-container mb-4">
                    <h5 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-info-circle me-2"></i>Thông tin cơ bản
                    </h5>
                    
                    <div class="row g-3">
                        <!-- Tên sản phẩm -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('product_name') is-invalid @enderror" 
                                    id="product_name" name="product_name" value="{{ old('product_name') }}" 
                                    placeholder="Nhập tên sản phẩm">
                                <label for="product_name">Tên sản phẩm</label>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Giá sản phẩm -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                    id="price" name="price" value="{{ old('price') }}" step="0.01" 
                                    placeholder="Nhập giá sản phẩm">
                                <label for="price">Giá sản phẩm (₫)</label>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Danh mục -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" onchange="fetchBrands()">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                    @endforeach
                                </select>
                                <label for="category_id">Danh mục sản phẩm</label>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Thương hiệu -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                    <option value="">-- Chọn thương hiệu --</option>
                                    <!-- Thương hiệu sẽ được thêm qua AJAX -->
                                </select>
                                <label for="brand_id">Thương hiệu</label>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Phần mô tả -->
                <div class="section-container mb-4">
                    <h5 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-align-left me-2"></i>Mô tả sản phẩm
                    </h5>
                    
                    <div class="row g-3">
                        <!-- Mô tả sản phẩm -->
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="description" class="form-label fw-semibold mb-2">Mô tả chi tiết</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="4" 
                                    placeholder="Nhập mô tả chi tiết về sản phẩm...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Phần màu sắc và số lượng -->
                <div class="section-container mb-4">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h5 class="text-primary mb-0">
                            <i class="fas fa-palette me-2"></i>Màu sắc & Số lượng
                        </h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addMore">
                            <i class="bi bi-plus-lg me-1"></i> Thêm màu sắc
                        </button>
                    </div>
                    <div id="colorQuantityContainer" class="variants-container">
                        <!-- Mẫu ô nhập màu sắc & số lượng -->
                        <div class="color-quantity-group mb-3 p-3 bg-light rounded-3 border-start border-4 border-primary">
                            <div class="row align-items-end">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">
                                            <i class="bi bi-palette2 me-2"></i> Tên màu
                                        </label>
                                        <input type="text" name="name[]" class="form-control" 
                                            placeholder="Tên màu (vd: Đỏ, Xanh dương...)">
                                    </div>
                                </div>
                                
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">
                                            <i class="bi bi-code-square me-2"></i> Mã màu
                                        </label>
                                        <div class="input-group">
                                            <input type="text" name="code[]" class="form-control color-input" 
                                                placeholder="Mã màu (vd: #FF0000, red...)" 
                                                oninput="this.nextElementSibling.style.backgroundColor = this.value">
                                            <div class="input-group-text p-0">
                                                <div class="color-preview w-100 h-100 rounded-end" style="min-width: 50px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                
                                <div class="col-md-2 text-center">
                                    <button type="button" class="btn btn-outline-danger w-100 remove-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                
                                <div class="col-12 mt-3">
                                    <div class="form-floating">
                                        <input type="number" name="quantity[]" class="form-control" placeholder="Nhập số lượng" min="0" value="0">
                                        <label>Số lượng</label>
                                    </div>
                                </div>
                                
                                <div class="col-12 mt-3">
                                    <div class="color-images">
                                        <label class="form-label fw-semibold mb-2">
                                            <i class="fas fa-images me-2"></i> Hình ảnh cho màu này
                                        </label>
                                        <div class="upload-zone p-3 border-2 border-dashed rounded-3 position-relative d-flex justify-content-center align-items-center bg-white" style="min-height: 120px;">
                                            <div class="text-center upload-instruction">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-secondary mb-2"></i>
                                                <p class="mb-0">Kéo thả hoặc chọn ảnh cho màu này</p>
                                            </div>
                                            <input type="file"
                                                class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer color-image-input"
                                                name="color_image_path[0][]"
                                                multiple
                                                onchange="previewColorImages(this)">
                                    
                                            <div class="color-image-preview d-flex flex-wrap w-100 justify-content-center mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Phần hình ảnh chung -->
                <div class="section-container mb-4">
                    <h5 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-images me-2"></i>Hình ảnh sản phẩm chung
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card bg-light border-0 shadow-sm">
                                <div class="card-body">
                                    <label class="form-label fw-semibold mb-2">Ảnh sản phẩm</label>
                    
                                    <!-- Khối chứa nhiều khối upload -->
                                    <div id="upload-wrapper">
                                        <!-- Khối upload mẫu -->
                                        <div class="image-upload-block mb-3">
                                            <div class="upload-zone p-3 border-2 border-dashed rounded-3 position-relative d-flex justify-content-center align-items-center bg-white" style="min-height: 160px;">
                                                <div class="text-center upload-instruction">
                                                    <i class="fas fa-cloud-upload-alt fa-3x text-secondary mb-2"></i>
                                                    <p class="mb-0">Kéo thả hoặc chọn ảnh</p>
                                                </div>
                                                <input type="file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer"
                                                    name="image_path[]" onchange="previewImages(event, this)">
                                                <div class="d-flex flex-wrap w-100 justify-content-center mt-2 image-preview"></div>
                                            </div>
                                        </div>
                                    </div>
                    
                                    <!-- Nút thêm ảnh -->
                                    <button type="button" class="btn btn-outline-primary mt-2" onclick="addImageUpload()">+ Thêm ảnh</button>
                    
                                    <!-- Hiển thị lỗi -->
                                    @error('image_path')
                                        <div class="text-danger mt-2 fs-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
                
                <!-- Nút lưu -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('product.list') }}" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="fas fa-save me-2"></i>Thêm sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Styles chung */
.section-container {
    background-color: #f8f9fc;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.section-container:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}

/* Card header */
.bg-gradient-primary {
    background: linear-gradient(to right, #4e73df, #224abe);
}

.icon-circle {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0.125rem 0.25rem rgba(78, 115, 223, 0.3);
}

/* Form controls */
.form-floating>.form-control, 
.form-floating>.form-select {
    height: calc(3.5rem + 2px);
    padding: 1rem 0.75rem;
}

.form-floating>label {
    padding: 1rem 0.75rem;
}

.form-control:focus, 
.form-select:focus {
    border-color: #bac8f3;
    box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
}

textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

/* Upload zone */
.border-dashed {
    border-style: dashed;
    border-color: #bac8f3;
    border-width: 2px;
    transition: all 0.3s ease;
}

.upload-zone:hover {
    border-color: #4e73df;
    background-color: #f8f9fc !important;
}

.cursor-pointer {
    cursor: pointer;
}

/* Color-quantity groups */
.color-quantity-group {
    position: relative;
    transition: all 0.3s ease;
}

.color-quantity-group:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.color-preview {
    width: 40px;
    height: 38px;
    border: 1px solid #ced4da;
    transition: all 0.3s ease;
}

.variants-container {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 5px;
}

.variants-container::-webkit-scrollbar {
    width: 5px;
}

.variants-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.variants-container::-webkit-scrollbar-thumb {
    background: #bac8f3;
    border-radius: 10px;
}

.variants-container::-webkit-scrollbar-thumb:hover {
    background: #4e73df;
}

/* Image previews */
.color-image-preview img,
#image-preview img,
#description-image-preview img {
    height: 80px;
    width: auto;
    object-fit: cover;
    margin: 5px;
    border-radius: 4px;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

/* Buttons */
.btn-success {
    background-color: #1cc88a;
    border-color: #1cc88a;
}
.btn-success:hover {
    background-color: #17a673;
    border-color: #169b6b;
}

.btn-outline-danger {
    border-radius: 8px;
}

.btn-outline-danger:hover {
    background-color: #e74a3b;
    color: white;
}

.btn-outline-secondary {
    color: #5a5c69;
    border-color: #5a5c69;
}

.btn-outline-secondary:hover {
    background-color: #5a5c69;
    color: white;
}
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchBrands();
    });

    document.getElementById('addMore').addEventListener('click', function () {
        let container = document.getElementById('colorQuantityContainer');
        let firstRow = container.querySelector('.color-quantity-group');
        let newRow = firstRow.cloneNode(true);

        // Reset giá trị trong input mới
        newRow.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
        newRow.querySelector('.color-preview').style.backgroundColor = '';
        newRow.querySelector('input[type="number"]').value = '0';
        newRow.querySelector('.color-image-preview').innerHTML = '';

        // Thêm vào DOM
        container.appendChild(newRow);
    });

    document.getElementById('colorQuantityContainer').addEventListener('click', function (e) {
        if (e.target.closest('.remove-btn')) {
            let allRows = document.querySelectorAll('.color-quantity-group');
            if (allRows.length > 1) {
                e.target.closest('.color-quantity-group').remove();
            }
        }
    });

    function previewImages() {
        var preview = document.getElementById('image-preview');
        var instructionDiv = document.querySelector('#images').closest('.upload-zone').querySelector('.upload-instruction');
        preview.innerHTML = "";

        var files = document.getElementById('images').files;
        if (files.length > 0) {
            instructionDiv.style.display = 'none';
            
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var imgContainer = document.createElement('div');
                    imgContainer.className = 'position-relative m-2';
                    
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid rounded';
                    img.style.height = '120px';
                    img.style.objectFit = 'cover';
                    
                    imgContainer.appendChild(img);
                    preview.appendChild(imgContainer);
                }
                reader.readAsDataURL(files[i]);
            }
        } else {
            instructionDiv.style.display = 'block';
        }
    }

    function previewDescriptionImage() {
        var preview = document.getElementById('description-image-preview');
        var instructionDiv = document.querySelector('#description_image').closest('.upload-zone').querySelector('.upload-instruction');
        preview.innerHTML = "";

        var files = document.getElementById('description_image').files;
        if (files.length > 0) {
            instructionDiv.style.display = 'none';
            
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var imgContainer = document.createElement('div');
                    imgContainer.className = 'position-relative m-2';
                    
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid rounded';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    
                    imgContainer.appendChild(img);
                    preview.appendChild(imgContainer);
                }
                reader.readAsDataURL(files[i]);
            }
        } else {
            instructionDiv.style.display = 'block';
        }
    }

    function previewColorImages(inputElement) {
        var preview = inputElement.nextElementSibling;
        var instructionDiv = inputElement.previousElementSibling;
        preview.innerHTML = "";

        var files = inputElement.files;
        if (files.length > 0) {
            instructionDiv.style.display = 'none';
            
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var imgContainer = document.createElement('div');
                    imgContainer.className = 'position-relative m-2';
                    
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid rounded';
                    img.style.height = '80px';
                    img.style.width = 'auto';
                    img.style.objectFit = 'cover';
                    
                    imgContainer.appendChild(img);
                    preview.appendChild(imgContainer);
                }
                reader.readAsDataURL(files[i]);
            }
        } else {
            instructionDiv.style.display = 'block';
        }
    }

    function fetchBrands() {
        var categoryId = document.getElementById('category_id').value;
        var brandSelect = document.getElementById('brand_id');
        
        // Clear previous options
        brandSelect.innerHTML = '<option value="">-- Chọn thương hiệu --</option>';
        
        if (categoryId) {
            fetch(`/product/brands/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(brand => {
                            var option = document.createElement('option');
                            option.value = brand.id;
                            option.textContent = brand.brand_name;
                            brandSelect.appendChild(option);
                        });
                    } else {
                        var option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Không có thương hiệu';
                        brandSelect.appendChild(option);
                    }
                })
                .catch(error => console.error('Error fetching brands:', error));
        }
    }

    function addImageUpload() {
        // Lấy khối đầu tiên để sao chép
        const firstBlock = document.querySelector('.image-upload-block');
        const clone = firstBlock.cloneNode(true);

        // Xóa ảnh xem trước
        const preview = clone.querySelector('.image-preview');
        preview.innerHTML = '';

        // Reset file input
        const input = clone.querySelector('input[type="file"]');
        input.value = '';
        input.setAttribute('onchange', 'previewImages(event, this)');

        // Thêm khối mới vào
        document.getElementById('upload-wrapper').appendChild(clone);
    }

    function previewImages(event, input) {
        const previewContainer = input.closest('.image-upload-block').querySelector('.image-preview');
        previewContainer.innerHTML = '';

        const files = event.target.files;
        if (!files || files.length === 0) return;

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = "img-thumbnail m-1";
                img.style.maxWidth = "100px";
                img.style.maxHeight = "100px";
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endsection