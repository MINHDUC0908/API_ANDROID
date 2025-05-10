@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3 d-flex align-items-center">
            <i class="fas fa-tags me-2 fs-5"></i>
            <h5 class="mb-0">Chi tiết thương hiệu</h5>
        </div>
        <div class="card-body">
            <div class="mb-3 d-flex align-items-center">
                <i class="fas fa-box text-primary me-2"></i>
                <h6 class="fw-bold text-primary mb-0">Tên thương hiệu:</h6>
            </div>
            <p class="fs-6 text-muted ms-4">{{ $brand->brand_name }}</p>

            <div class="mb-3 d-flex align-items-center">
                <i class="fas fa-folder text-primary me-2"></i>
                <h6 class="fw-bold text-primary mb-0">Danh mục:</h6>
            </div>
            <p class="fs-6 text-muted ms-4">{{ $brand->category->category_name }}</p>

            <div class="d-flex justify-content-end">
                <a href="{{ route('brand.list') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
