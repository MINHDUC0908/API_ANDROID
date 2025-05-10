@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded-3 shadow-sm mb-4">
            <div>
                <h4 class="fw-bold text-primary mb-1">
                    <i class="fas fa-edit me-2"></i>Chỉnh sửa danh mục
                </h4>
                <p class="text-muted mb-0">Cập nhật thông tin danh mục</p>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('category.update', ['id' => $category->id]) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="category_name" class="form-label fw-bold text-dark">
                        <i class="fas fa-folder me-2"></i>Tên danh mục
                    </label>
                    <input type="text" class="form-control @error('category_name') is-invalid @enderror" 
                           id="category_name" name="category_name" 
                           value="{{ old('category_name', $category->category_name) }}">
                    @error('category_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('category.list') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
