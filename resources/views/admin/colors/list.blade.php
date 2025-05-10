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

    <div class="card shadow border-0 rounded-3">
        <div class="card border-0 rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary">
                    <i class="fas fa-list me-2"></i>Danh sách màu sắc
                </h5>
                <!-- Ô tìm kiếm -->
                <div class="search-container">
                    <input type="text" id="searchColor" class="form-control px-4" 
                        placeholder="🔍 Tìm kiếm màu sắc..." onkeyup="filterTable()">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="SearchColor">
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
                            <tr>
                                <td class="text-center align-middle">{{ $color->id }}</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="color-preview me-2 rounded-circle"
                                             style="width: 25px; height: 25px; background-color: {{ $color->name }}; border: 1px solid #dee2e6;">
                                        </div>
                                        <span class="fw-bold">{{ $color->color }}</span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    @if($color->product)
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $color->product->product_name }}</span>
                                            <small class="text-muted">SKU: PRD-{{ str_pad($color->product->id, 5, '0', STR_PAD_LEFT) }}</small>
                                        </div>
                                    @else
                                        <span class="badge bg-light text-secondary">
                                            <i class="fas fa-exclamation-circle me-1"></i>Chưa có sản phẩm
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3">
                                        <i class="fas fa-cubes me-1"></i>{{ number_format($color->quantity) }} sản phẩm
                                    </span>
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
                                                data-bs-target="#deleteImageModal"
                                                title="Xóa thuộc tính"
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
                Hiển thị <strong>{{ $colors->firstItem() }}</strong> - <strong>{{ $colors->lastItem() }}</strong> trong tổng số <strong>{{ $colors->total() }}</strong> thương hiệu
            </small>
        </div>
        <div>
            {{ $colors->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
<div class="modal fade" id="deleteImageModal" tabindex="-1" aria-labelledby="deleteImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteColorModalLabel">Xác nhận xóa màu sắc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn màu sắc của sản phẩm hiện tại không?</p>
                <p class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="{{ route('colors.destroy', $color->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" >Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function filterTable() {
        let input = document.getElementById("searchColor").value.toLowerCase();
        let rows = document.querySelectorAll("#SearchColor tbody tr");

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