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
</style>
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded-3 shadow-sm mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">
                <i class="fas fa-box-open me-2"></i>Quản lý sản phẩm
            </h2>
            <p class="text-muted mb-0">Quản lý và cập nhật thông tin sản phẩm của bạn</p>
        </div>
        <div>
            <a href="{{ route('product.create') }}" class="btn btn-primary btn-md">
                <i class="fas fa-plus-circle me-2"></i>Thêm sản phẩm mới
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

    <div class="card shadow border-0 rounded-3">
        <div class="card border-0 rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary">
                    <i class="fas fa-list me-2"></i>Danh sách sản phẩm
                </h5>
                <!-- Ô tìm kiếm -->
                <div class="search-container">
                    <input type="text" id="searchProduct" class="form-control px-4" 
                        placeholder="🔍 Tìm kiếm sản phẩm..." onkeyup="filterTable()">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="searchProduct">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="py-3 text-center" width="5%">ID</th>
                            <th scope="col" class="py-3" width="25%">Sản phẩm</th>
                            <th scope="col" class="py-3" width="15%">Giá</th>
                            <th scope="col" class="py-3" width="15%">Danh mục</th>
                            <th scope="col" class="py-3" width="15%">Thương hiệu</th>
                            <th scope="col" class="py-3" width="10%">Ảnh</th>
                            <th scope="col" class="py-3 text-center" width="15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="text-center align-middle">{{ $product->id }}</td>
                                <td class="align-middle">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $product->product_name }}</span>
                                        <small class="text-muted">SKU: PRD-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</small>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="fw-bold text-primary">
                                        {{ number_format($product->price, 0, ',', '.') }}₫
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3">
                                        <i class="fas fa-tag me-1"></i>{{ $product->category->category_name }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3">
                                        <i class="fas fa-building me-1"></i>{{ $product->brand->brand_name }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    @if($product->images)
                                        <img src="{{ asset( $product->images[0]->image_path) }}" 
                                             alt="{{ $product->product_name }}"
                                             class="rounded shadow-sm"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <span class="badge bg-light text-secondary">
                                            <i class="fas fa-image-slash me-1"></i>Không có ảnh
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('product.show', ['id' => $product->id]) }}" 
                                           class="btn btn-light btn-sm"
                                           data-bs-toggle="tooltip"
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye text-info"></i>
                                        </a>
                                        <a href="{{ route('product.edit', ['id' => $product->id]) }}" 
                                           class="btn btn-light btn-sm"
                                           data-bs-toggle="tooltip"
                                           title="Chỉnh sửa">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>
                                        <form action="{{ route('product.destroy', ['id' => $product->id]) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-light btn-sm"
                                                    data-bs-toggle="tooltip"
                                                    title="Xóa sản phẩm"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">
                                                <i class="fas fa-trash text-danger"></i>
                                            </button>
                                        </form>
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
                Hiển thị <strong>{{ $products->firstItem() }}</strong> - <strong>{{ $products->lastItem() }}</strong> trong tổng số <strong>{{ $products->total() }}</strong> thương hiệu
            </small>
        </div>
        <div>
            {{ $products->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
<script>
    function filterTable() {
        let input = document.getElementById("searchProduct").value.toLowerCase();
        let rows = document.querySelectorAll("#searchProduct tbody tr");

        rows.forEach(row => {
            let color = row.cells[1].textContent.toLowerCase();

            if (color.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
    // Khởi tạo chú giải công cụ
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection