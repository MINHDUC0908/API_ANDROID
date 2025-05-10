@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded-3 shadow-sm mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">
                <i class="fas fa-edit me-2"></i>Chỉnh sửa thương hiệu
            </h4>
            <p class="text-muted mb-0">Cập nhật thông tin thương hiệu</p>
        </div>
    </div>

    <div class="card shadow border-0 rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('brand.update', $brand->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="brand_name" class="form-label fw-semibold">Tên thương hiệu</label>
                    <input type="text" class="form-control" id="brand_name" name="brand_name" 
                           value="{{ old('brand_name', $brand->brand_name) }}">
                    @error('brand_name')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-semibold">Danh mục</label>
                    <select name="category_id" id="category_id" class="form-select">
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}" {{ old('category_id', $brand->category_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('brand.list') }}" class="btn btn-secondary me-2">
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
