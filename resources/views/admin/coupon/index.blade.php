@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded-3 shadow-sm mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">
                <i class="fas fa-tag me-2"></i>Quản lý mã giảm giá
            </h2>
            <p class="text-muted mb-0">Danh sách các mã giảm giá khả dụng trong hệ thống</p>
        </div>
        <div>
            <a href="{{ route('coupon.create') }}" class="btn btn-primary btn-md">
                <i class="fas fa-plus-circle me-2"></i>Thêm mã giảm giá mới
            </a>
        </div>
    </div>

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
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-list me-2"></i>Danh sách mã giảm giá
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 text-center">ID</th>
                            <th class="py-3">Mã giảm giá</th>
                            <th class="py-3">Số tiền giảm</th>
                            <th class="py-3 text-center">Số lượng mã giảm giá</th>
                            <th class="py-3">Ngày hết hạn</th>
                            <th class="py-3 text-center">Trạng thái</th>
                            <th class="py-3">Ngày tạo</th>
                            <th class="py-3 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td class="text-center align-middle">{{ $coupon->id }}</td>
                            <td class="align-middle">
                                <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3 py-2">
                                    <i class="fas fa-ticket-alt me-1"></i>{{ $coupon->code }}
                                </span>
                            </td>
                            <td class="align-middle fw-semibold text-primary">
                                {{ number_format($coupon->discount_amount, 0, ',', '.') }}đ
                            </td>
                            <td class="align-middle fw-semibold text-primary text-center">
                                <i class="fas fa-ticket-alt me-2 small text-danger"></i>{{ $coupon->quantity }}
                            </td>
                            <td class="align-middle">
                                <span class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                @if($coupon->is_used)
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="fas fa-times-circle me-1"></i>Đã sử dụng
                                    </span>
                                @elseif($coupon->expires_at < now())
                                    <span class="badge bg-warning px-3 py-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>Hết hạn
                                    </span>
                                @else
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>Khả dụng
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle text-muted">
                                <i class="far fa-clock me-1"></i>
                                {{ $coupon->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="align-middle text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('coupon.edit', ['id' => $coupon->id]) }}" class="btn btn-light btn-sm"                                           
                                        data-bs-toggle="tooltip"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <form action="{{ route('coupon.destroy', ['id' => $coupon->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light btn-sm" 
                                            data-bs-toggle="tooltip"
                                            title="Xóa mã giảm giá">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center py-5">
                                    <i class="fas fa-ticket-alt text-muted mb-3" style="font-size: 3rem;"></i>
                                    <p class="text-muted mb-0">Không có mã giảm giá nào trong hệ thống</p>
                                    <a href="{{ route('coupon.create') }}" class="btn btn-outline-primary btn-sm mt-3">
                                        <i class="fas fa-plus-circle me-1"></i>Thêm mã giảm giá mới
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection
