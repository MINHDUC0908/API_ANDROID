@extends('admin.layouts.app')

@section('content')
<style>
    .table th {
        font-weight: 600;
        color: #4a5568;
    }
    .table td {
        border-bottom: 1px solid #f0f0f0;
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
</style>
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded-3 shadow-sm mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">
                <i class="fas fa-list me-2"></i>Quản lý danh mục
            </h2>
            <p class="text-muted mb-0">Danh sách các danh mục sản phẩm</p>
        </div>
        <div>
            <a href="{{ route('category.create') }}" class="btn btn-primary btn-md">
                <i class="fas fa-plus-circle me-2"></i>Thêm danh mục mới
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
                    <i class="fas fa-list me-2"></i>Danh sách danh mục
                </h5>
                <!-- Ô tìm kiếm -->
                <div class="search-container">
                    <input type="text" id="searchCategory" class="form-control px-4" 
                        placeholder="🔍 Tìm kiếm danh mục..." onkeyup="filterTable()">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="searchCategory">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="py-3 text-center" width="10%">ID</th>
                            <th scope="col" class="py-3">Tên danh mục</th>
                            <th scope="col" class="py-3 text-center" width="20%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="text-center align-middle">{{ $category->id }}</td>
                                <td class="align-middle">
                                    <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3">
                                        <i class="fas fa-tag me-1"></i>{{ $category->category_name }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('category.edit', ['id' => $category->id]) }}" 
                                           class="btn btn-light btn-sm"
                                           data-bs-toggle="tooltip"
                                           title="Chỉnh sửa">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>
                                        <form action="{{ route('category.delete', ['id' => $category->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này không?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Xóa">
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
                Hiển thị <strong>{{ $categories->firstItem() }}</strong> - <strong>{{ $categories->lastItem() }}</strong> trong tổng số <strong>{{ $categories->total() }}</strong> thương hiệu
            </small>
        </div>
        <div>
            {{ $categories->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
<script>
    function filterTable() {
        let input = document.getElementById("searchCategory").value.toLowerCase();
        let rows = document.querySelectorAll("#searchCategory tbody tr");

        rows.forEach(row => {
            let color = row.cells[1].textContent.toLowerCase();

            if (color.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection
