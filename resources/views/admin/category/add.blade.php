@extends('admin.layouts.app')

@section('content')
<style>
    .form-label {
        color: #4a5568;
        font-weight: 600;
    }

    .form-control {
        border-radius: 0.375rem;
    }

    .btn {
        border-radius: 0.375rem;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }
</style>
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded-3 shadow-sm mb-4">
        <div>
            <h5 class="mb-0 text-primary">
                <i class="fas fa-plus-circle me-2"></i>Thêm danh mục mới
            </h5>
            <p class="text-muted mb-0">Điền thông tin danh mục cần thêm</p>
        </div>
        <div>
            <a href="{{ route('category.list') }}" class="btn btn-light btn-md">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show border-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow border-0 rounded-3">
        <div class="card-body">
            <form action="{{ route('category.store') }}" method="POST">
                @csrf

                <!-- Category Name -->
                <div class="mb-3">
                    <label for="category_name" class="form-label fw-semibold">Tên danh mục</label>
                    <input type="text" class="form-control @error('category_name') is-invalid @enderror" 
                           id="category_name" name="category_name" 
                           value="{{ old('category_name') }}" placeholder="Nhập tên danh mục...">
                    @error('category_name')
                        <div class="text-danger mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu danh mục
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
