@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-percentage text-primary me-2"></i>
        Quản lý giảm giá
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><i class="fas fa-tags"></i> Quản lý giảm giá</li>
    </ol>
    
    @if(session('status'))
        <div class="alert custom-alert alert-success alert-dismissible fade show border-0 shadow" role="alert" id="status-alert">
            <div class="d-flex align-items-center">
                <div class="alert-icon-container me-3">
                    <i class="fas fa-check-circle fa-lg"></i>
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
    
    @if(session('error'))
        <div class="alert custom-alert alert-error alert-dismissible fade show border-0 shadow" role="alert" id="status-alert">
            <div class="d-flex align-items-center">
                <div class="alert-icon-container me-3">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                </div>
                <div>
                    <h5 class="alert-heading mb-1">Error!</h5>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
            </div>
            <div class="progress mt-2" style="height: 3px;">
                <div id="alert-progress-bar" class="progress-bar bg-white" style="width: 100%;"></div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary bg-gradient text-white">
            <div>
                <i class="fas fa-tag me-1"></i>
                Danh sách giảm giá
            </div>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addDiscountModal">
                <i class="fas fa-plus-circle me-1"></i> Thêm giảm giá mới
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="discountsTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i> ID</th>
                            <th><i class="fas fa-box-open me-1"></i> Sản phẩm</th>
                            <th><i class="fas fa-tag me-1"></i> Loại giảm giá</th>
                            <th><i class="fas fa-money-bill-wave me-1"></i> Giá trị</th>
                            <th><i class="far fa-calendar-alt me-1"></i> Thời gian</th>
                            <th><i class="fas fa-toggle-on me-1"></i> Trạng thái</th>
                            <th><i class="fas fa-cogs me-1"></i> Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($discounts as $discount)
                        <tr>
                            <td>{{ $discount->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-box-open text-primary me-2"></i>
                                    {{ $discount->product->product_name }}
                                </div>
                            </td>
                            <td>
                                @if($discount->discount_type == 'percent')
                                    <span class="badge bg-primary">
                                        <i class="fas fa-percent me-1"></i> Phần trăm
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="fas fa-dollar-sign me-1"></i> Cố định
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($discount->discount_type == 'percent')
                                    <span class="fw-bold text-primary">
                                        <i class="fas fa-percent"></i> {{ $discount->discount_value }}%
                                    </span>
                                @else
                                    <span class="fw-bold text-info">
                                        <i class="fas fa-dollar-sign"></i> {{ number_format($discount->discount_value, 0, ',', '.') }}đ
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small>
                                    <div><i class="fas fa-play-circle text-success me-1"></i> <strong>Bắt đầu:</strong> {{ $discount->start_date ? date('d/m/Y H:i', strtotime($discount->start_date)) : 'N/A' }}</div>
                                    <div><i class="fas fa-stop-circle text-danger me-1"></i> <strong>Kết thúc:</strong> {{ $discount->end_date ? date('d/m/Y H:i', strtotime($discount->end_date)) : 'N/A' }}</div>
                                </small>
                            </td>
                            <td class="text-center">
                                @if($discount->status)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Kích hoạt
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> Không kích hoạt
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-warning edit-discount" data-id="{{ $discount->id }}" data-bs-toggle="modal" data-bs-target="#editDiscountModal-{{$discount->id}}" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-discount" data-id="{{ $discount->id }}" data-bs-toggle="modal" data-bs-target="#deleteDiscountModal-{{$discount->id}}" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
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
</div>


<!-- Add Discount Modal -->
<div class="modal fade" id="addDiscountModal" tabindex="-1" aria-labelledby="addDiscountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addDiscountModalLabel">Thêm giảm giá mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route("discount.store")}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="product_id" class="form-label">Sản phẩm <span class="text-danger">*</span></label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                @foreach($products as $product)
                                    @if(!$discounts->contains('product_id', $product->id))
                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                    @endif
                                @endforeach
                            </select>                            
                            @error('product_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="discount_type" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                            <select class="form-select" id="discount_type" name="discount_type" required>
                                <option value="percent">Phần trăm (%)</option>
                                <option value="fixed">Số tiền cố định (VNĐ)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="discount_value" class="form-label">Giá trị <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="discount_value" name="discount_value" min="0" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Ngày bắt đầu</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Ngày kết thúc</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                                <label class="form-check-label" for="status">Kích hoạt</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($discounts as $discount)
<div class="modal fade" id="editDiscountModal-{{$discount->id}}" tabindex="-1" aria-labelledby="editDiscountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editDiscountModalLabel">Chỉnh sửa giảm giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('discount.update', $discount->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_product_id_{{$discount->id}}" class="form-label">Sản phẩm <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_product_id_{{$discount->id}}" name="product_id" required>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $product->id == $discount->product_id ? 'selected' : '' }}>
                                        {{ $product->product_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_discount_type_{{$discount->id}}" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_discount_type_{{$discount->id}}" name="discount_type" required>
                                <option value="percent" {{ $discount->discount_type == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                                <option value="fixed" {{ $discount->discount_type == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_discount_value_{{$discount->id}}" class="form-label">Giá trị <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_discount_value_{{$discount->id}}" name="discount_value" min="0" step="0.01" value="{{ $discount->discount_value }}" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_start_date_{{$discount->id}}" class="form-label">Ngày bắt đầu</label>
                            <input type="datetime-local" class="form-control" id="edit_start_date_{{$discount->id}}" name="start_date" value="{{ $discount->start_date }}">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_end_date_{{$discount->id}}" class="form-label">Ngày kết thúc</label>
                            <input type="datetime-local" class="form-control" id="edit_end_date_{{$discount->id}}" name="end_date" value="{{ $discount->end_date }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit_status_{{$discount->id}}" name="status" value="1" {{ $discount->status == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="edit_status_{{$discount->id}}">Kích hoạt</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteDiscountModal-{{$discount->id}}" tabindex="-1" aria-labelledby="deleteDiscountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteDiscountModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa giảm giá này không?</p>
                <p class="text-danger"><small>Hành động này không thể hoàn tác.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteDiscountForm" method="POST" action="{{route("discount.destroy", $discount->id)}}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection