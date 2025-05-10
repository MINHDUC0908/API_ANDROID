@extends('admin.layouts.app')

@section('content')
<style>
    .form-label {
        color: #4a5568;
        font-weight: 600;
    }

    .form-control, .form-select {
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
    <div class="card shadow border-0 rounded-3">
        <!-- Header -->
        <div class="card-header bg-white py-3 d-flex align-items-center">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-plus-circle me-2"></i>Thêm thương hiệu mới
            </h5>
        </div>

        <!-- Form -->
        <div class="card-body">
            <form action="{{ route('brand.store') }}" method="POST">
                @csrf

                <!-- Brand Name -->
                <div class="mb-3">
                    <label for="brand_name" class="form-label fw-semibold">Tên thương hiệu</label>
                    <input type="text" class="form-control @error('brand_name') is-invalid @enderror" 
                           id="brand_name" name="brand_name" 
                           value="{{ old('brand_name') }}" placeholder="Nhập tên thương hiệu...">
                    @error('brand_name')
                        <div class="text-danger mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Danh mục -->
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-semibold">Danh mục</label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}" {{ old('category_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="text-danger mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Nút Submit -->
                <div class="d-flex justify-content-end">
                    <a href="{{ route('brand.list') }}" class="btn btn-light me-2">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu thương hiệu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
