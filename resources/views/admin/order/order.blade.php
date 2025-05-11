@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/order.css') }}">
<div class="container-fluid px-4 py-4">
    <!-- Header với gradient và hiệu ứng nổi bật -->
    <div class="d-flex justify-content-between align-items-center page-header p-4 rounded-3 shadow-sm mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-shopping-cart me-2"></i>Quản lý đơn hàng
            </h2>
            <p class="mb-0">Theo dõi và cập nhật trạng thái đơn hàng</p>
        </div>
        <div>
            <span class="badge bg-white text-primary shadow-sm">
                <i class="fas fa-chart-bar me-1"></i> {{ $orders->total() }} đơn hàng
            </span>
        </div>
    </div>

    <!-- Card chính với hiệu ứng hover -->
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-list me-2"></i>Danh sách đơn hàng
            </h5>
            <div class="d-flex gap-2 align-items-center">
                <span class="text-muted me-2">Lọc theo:</span>
                <form action="{{ route('orders.list') }}" method="GET">
                    <select name="status" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()">
                        <option value="Waiting for confirmation" {{ $status == 'Waiting for confirmation' ? 'selected' : '' }}>Đang chờ xác nhận</option>
                        <option value="Processing" {{ $status == 'Processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="Delivering" {{ $status == 'Delivering' ? 'selected' : '' }}>Đang vận chuyển</option>
                        <option value="Completed" {{ $status == 'Completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                        <option value="Cancel" {{ $status == 'Cancel' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3">Mã đơn hàng</th>
                            <th class="py-3">Khách hàng</th>
                            <th class="py-3">Tổng giá</th>
                            <th class="py-3 text-center">Trạng thái</th>
                            <th class="py-3">Địa chỉ giao hàng</th>
                            <th class="py-3">Ngày tạo</th>
                            <th class="py-3 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="order-number">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-hashtag text-muted me-2"></i>
                                        {{ $order->order_number }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light p-2 me-2">
                                            <div class="flex align-items-center justify-content-center">
                                                @if($order->user->image)
                                                    <img src="{{ asset('imageUser/' . $order->user->image) }}" 
                                                        alt="{{ $order->user->name }}" 
                                                        class="rounded-circle"
                                                        style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%;">
                                                @else
                                                    <img src="{{ asset('icon/avatar.jpg') }}"
                                                        alt="{{ $order->user->name }}" 
                                                        class="rounded-circle"
                                                        style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%;">
                                                @endif                                            
                                            </div>
                                        </div>
                                        {{ $order->user->name }}
                                    </div>
                                </td>
                                <td class="price-value">{{ number_format($order->total_amount) }} ₫</td>
                                <td class="text-center">
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        @php
                                            $statusIcons = [
                                                'Waiting for confirmation' => 'clock',
                                                'Processing' => 'sync',
                                                'Delivering' => 'shipping-fast',
                                                'Completed' => 'check-circle',
                                                'Cancel' => 'ban'
                                            ];
                                            
                                            $statusMap = [
                                                'Waiting for confirmation' => ['Processing', 'btn-warning', 'Đang chờ xác nhận'],
                                                'Processing' => ['Delivering', 'btn-info', 'Đang xử lý'],
                                                'Delivering' => ["Completed", 'btn-primary', 'Đang vận chuyển'],
                                                'Completed' => [null, 'btn-success', 'Đã hoàn thành'],
                                                'Cancel' => [null, 'btn-secondary', 'Đã hủy']
                                            ];
                                        @endphp
                                        @if ($statusMap[$order->status][0])
                                            <button type="submit" name="status" value="{{ $statusMap[$order->status][0] }}" class="btn {{ $statusMap[$order->status][1] }} btn-sm status-btn shadow-sm">
                                                <i class="fas fa-{{ $statusIcons[$order->status] }} me-1"></i>
                                                {{ $statusMap[$order->status][2] }}
                                            </button>
                                        @else
                                            <button type="button" class="btn {{ $statusMap[$order->status][1] }} btn-sm status-btn shadow-sm" disabled>
                                                <i class="fas fa-{{ $statusIcons[$order->status] }} me-1"></i>
                                                {{ $statusMap[$order->status][2] }}
                                            </button>
                                        @endif
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                        <span class="text-truncate" style="max-width: 200px;" title="{{ $order->shippingAddress->address }}">
                                            {{ $order->shippingAddress->address }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-calendar-alt text-muted me-2"></i>
                                        {{ $order->created_at->format('d-m-Y H:i') }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm action-btn shadow-sm">
                                        <i class="fas fa-eye"></i> Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Thêm footer card với bóng mờ -->
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    <small>
                        Hiển thị <strong>{{ $orders->firstItem() }}</strong> - <strong>{{ $orders->lastItem() }}</strong> trong tổng số <strong>{{ $orders->total() }}</strong> đơn hàng
                    </small>
                </div>
                <div>
                    {{ $orders->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection