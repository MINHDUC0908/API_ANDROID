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
            <button>
                <a href="{{ route('discount.create') }}">
                    <i class="fas fa-plus-circle me-1"></i> Thêm giảm giá mới
                </a>
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


    @foreach ($discounts as $discount)
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