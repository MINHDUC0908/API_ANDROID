@extends('admin.layouts.app')

@section('content')
<style>
    /* Custom styles */
    .table th {
        font-weight: 600;
        color: #4a5568;
    }
    
    .table td {
        border-bottom: 1px solid #f0f0f0;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5em 1em;
    }
    
    .btn-sm {
        padding: 0.4rem;
        line-height: 1;
        border-radius: 0.375rem;
    }
    
    .btn-light {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }
    
    .btn-light:hover {
        background: #e9ecef;
        border-color: #dee2e6;
    }
    
    .alert {
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .card {
        overflow: hidden;
    }

    .color-preview {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* New styles for color management */
    .color-filter-item {
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .color-filter-item:hover {
        transform: scale(1.1);
    }
    
    .color-filter-item.active {
        transform: scale(1.2);
        box-shadow: 0 0 0 2px #fff, 0 0 0 4px #0d6efd;
    }
    
    .filter-bar {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .product-filter-dropdown {
        max-height: 300px;
        overflow-y: auto;
    }
</style>
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded-3 shadow-sm mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">
                <i class="fas fa-palette me-2"></i>Quản lý màu sắc
            </h2>
            <p class="text-muted mb-0">Quản lý màu sắc và số lượng cho từng sản phẩm</p>
        </div>
        <div>
            <a href="{{ route('colors.create') }}" class="btn btn-primary btn-md">
                <i class="fas fa-plus-circle me-2"></i>Thêm màu mới
            </a>
        </div>
    </div>

    {{-- Thông báo --}}
    @if(session('status'))
        <div class="alert custom-alert alert-success alert-dismissible fade show border-0 shadow" role="alert" id="status-alert">
            <div class="d-flex align-items-center">
                <div class="alert-icon-container me-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h5 class="alert-heading mb-1">Thành công!</h5>
                    <p class="mb-0">{{ session('status') }}</p>
                </div>
            </div>
            <div class="progress mt-2" style="height: 3px;">
                <div id="alert-progress-bar" class="progress-bar bg-white" style="width: 100%;"></div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter Bar -->
    <div class="filter-bar shadow-sm mb-4">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="searchColor" class="form-control border-start-0" 
                           placeholder="Tìm kiếm theo tên màu hoặc sản phẩm..." onkeyup="filterTable()">
                </div>
            </div>
            <div class="col-lg-4">
                <select class="form-select" id="productFilter" onchange="filterTable()">
                    <option value="">Tất cả sản phẩm</option>
                    @php
                        $uniqueProducts = $colors->pluck('product')->unique('id');
                    @endphp
                    @foreach($uniqueProducts as $product)
                        @if($product)
                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <select class="form-select" id="stockFilter" onchange="filterTable()">
                    <option value="">Tất cả số lượng</option>
                    <option value="outOfStock">Hết hàng (0)</option>
                    <option value="lowStock">Sắp hết (1-10)</option>
                    <option value="inStock">Còn hàng (>10)</option>
                </select>
            </div>
        </div>
        
        <!-- Color filter pills -->
        <div class="mt-3">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="text-muted me-2">Lọc theo màu:</span>
                <span class="color-filter-item rounded-circle p-1 active" data-color="" 
                      style="width: 30px; height: 30px; background: linear-gradient(45deg, #ff0000, #00ff00, #0000ff); border: 1px solid #dee2e6;"
                      onclick="setColorFilter(this, '')">
                </span>
                @php
                    $uniqueColorValues = $colors->pluck('name')->unique();
                @endphp
                @foreach($uniqueColorValues as $colorValue)
                    <span class="color-filter-item rounded-circle" data-color="{{ $colorValue }}" 
                          style="width: 25px; height: 25px; background-color: {{ $colorValue }}; border: 1px solid #dee2e6;"
                          onclick="setColorFilter(this, '{{ $colorValue }}')">
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card shadow border-0 rounded-3">
        <div class="card border-0 rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary">
                    <i class="fas fa-list me-2"></i>Danh sách màu sắc
                </h5>
                <span class="badge bg-primary" id="filteredCount">{{ $colors->count() }} màu sắc</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="colorTable">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="py-3 text-center" width="5%">ID</th>
                            <th scope="col" class="py-3" width="25%">Màu sắc</th>
                            <th scope="col" class="py-3" width="35%">Sản phẩm</th>
                            <th scope="col" class="py-3" width="20%">Số lượng</th>
                            <th scope="col" class="py-3 text-center" width="15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($colors as $color)
                            <tr data-color="{{ $color->name }}" data-product="{{ $color->product ? $color->product->id : '' }}" data-quantity="{{ $color->quantity }}">
                                <td class="text-center align-middle">{{ $color->id }}</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="color-preview me-2 rounded-circle"
                                             style="width: 25px; height: 25px; background-color: {{ $color->name }}; border: 1px solid #dee2e6;">
                                        </div>
                                        <span class="fw-bold">{{ $color->name }}</span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    @if($color->product)
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $color->product->product_name }}</span>
                                            <small class="text-muted">SKU: {{ $color->product->sku ?? 'PRD-' . str_pad($color->product->id, 5, '0', STR_PAD_LEFT) }}</small>
                                        </div>
                                    @else
                                        <span class="badge bg-light text-secondary">
                                            <i class="fas fa-exclamation-circle me-1"></i>Chưa có sản phẩm
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($color->quantity > 10)
                                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3">
                                            <i class="fas fa-cubes me-1"></i>{{ number_format($color->quantity) }} sản phẩm
                                        </span>
                                    @elseif($color->quantity > 0)
                                        <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ number_format($color->quantity) }} sản phẩm
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3">
                                            <i class="fas fa-times-circle me-1"></i>Hết hàng
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('colors.edit', $color->id) }}" 
                                           class="btn btn-light btn-sm"
                                           data-bs-toggle="tooltip"
                                           title="Chỉnh sửa">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-light btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteColorModal"
                                                data-color-id="{{ $color->id }}"
                                                data-color-name="{{ $color->color }}"
                                                title="Xóa thuộc tính"
                                                onclick="prepareDelete({{ $color->id }}, '{{ $color->color }}')"
                                                data-bs-placement="top">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted">
            <small>
                Hiển thị <strong>{{ $colors->firstItem() }}</strong> - <strong>{{ $colors->lastItem() }}</strong> trong tổng số <strong>{{ $colors->total() }}</strong> màu sắc
            </small>
        </div>
        <div>
            {{ $colors->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
<div class="modal fade" id="deleteColorModal" tabindex="-1" aria-labelledby="deleteColorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteColorModalLabel">Xác nhận xóa màu sắc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa màu sắc <strong id="deleteColorName"></strong> của sản phẩm hiện tại không?</p>
                <p class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteColorForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Biến lưu giá trị filter
    let currentColorFilter = '';
    
    // Thiết lập filter màu sắc
    function setColorFilter(element, color) {
        // Bỏ active tất cả các màu
        document.querySelectorAll('.color-filter-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Thêm active cho màu được chọn
        element.classList.add('active');
        
        // Lưu filter hiện tại
        currentColorFilter = color;
        
        // Lọc bảng
        filterTable();
    }
    
    // Hàm lọc bảng
    function filterTable() {
        let searchInput = document.getElementById("searchColor").value.toLowerCase();
        let productFilter = document.getElementById("productFilter").value;
        let stockFilter = document.getElementById("stockFilter").value;
        let rows = document.querySelectorAll("#colorTable tbody tr");
        let visibleCount = 0;

        rows.forEach(row => {
            let colorName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
            let productId = row.getAttribute("data-product");
            let colorCode = row.getAttribute("data-color");
            let quantity = parseInt(row.getAttribute("data-quantity"));
            let productName = "";
            
            // Lấy tên sản phẩm nếu có
            let productCell = row.querySelector("td:nth-child(3)");
            if (productCell) {
                productName = productCell.textContent.toLowerCase();
            }
            
            // Kiểm tra điều kiện tìm kiếm
            let matchSearch = colorName.includes(searchInput) || productName.includes(searchInput);
            
            // Kiểm tra điều kiện lọc sản phẩm
            let matchProduct = !productFilter || productId === productFilter;
            
            // Kiểm tra điều kiện lọc màu
            let matchColor = !currentColorFilter || colorCode === currentColorFilter;
            
            // Kiểm tra điều kiện lọc tồn kho
            let matchStock = true;
            if (stockFilter === 'outOfStock') {
                matchStock = quantity === 0;
            } else if (stockFilter === 'lowStock') {
                matchStock = quantity > 0 && quantity <= 10;
            } else if (stockFilter === 'inStock') {
                matchStock = quantity > 10;
            }
            
            // Hiển thị hoặc ẩn dòng
            if (matchSearch && matchProduct && matchColor && matchStock) {
                row.style.display = "";
                visibleCount++;
            } else {
                row.style.display = "none";
            }
        });
        
        // Cập nhật số lượng kết quả
        document.getElementById("filteredCount").textContent = visibleCount + " màu sắc";
    }
    
    // Thiết lập xóa màu sắc
    function prepareDelete(colorId, colorName) {
        document.getElementById("deleteColorName").textContent = colorName;
        document.getElementById("deleteColorForm").action = `/admin/colors/${colorId}`;
    }
    
    // Khởi tạo chú giải công cụ và thông báo tự động đóng
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Thiết lập đóng thông báo thành công sau 5 giây
        var statusAlert = document.getElementById('status-alert');
        if (statusAlert) {
            var progressBar = document.getElementById('alert-progress-bar');
            var width = 100;
            var timer = setInterval(function() {
                width -= 2;
                if (progressBar) progressBar.style.width = width + '%';
                if (width <= 0) {
                    clearInterval(timer);
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(statusAlert);
                    if (bsAlert) bsAlert.close();
                }
            }, 100);
        }
    });
</script>
@endsection