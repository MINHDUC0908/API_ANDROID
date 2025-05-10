@extends('admin.layouts.app')

@section('content')
<style>
    /* Variables for consistent colors */
    :root {
        --primary-color: #4361ee;
        --primary-light: rgba(67, 97, 238, 0.1);
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a56d4);
        --success-color: #2ecc71;
        --success-light: rgba(46, 204, 113, 0.1);
        --success-gradient: linear-gradient(135deg, #2ecc71, #27ae60);
        --warning-color: #f39c12;
        --info-color: #3498db;
        --danger-color: #e74c3c;
        --secondary-color: #6c757d;
        --dark-color: #2d3748;
        --light-color: #f8f9fa;
        --border-radius: 0.75rem;
        --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        --hover-transform: translateY(-3px);
    }
    
    /* Card styling */
    .main-card {
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        transition: all 0.3s ease;
        border: none;
        overflow: hidden;
    }
    
    .main-card:hover {
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.12);
    }
    
    .section-card {
        border-radius: var(--border-radius);
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }
    
    .section-card:hover {
        box-shadow: var(--box-shadow);
    }
    
    .section-header {
        padding: 1rem 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .section-header i {
        font-size: 1.1rem;
        width: 24px;
        text-align: center;
    }
    
    .section-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        background: var(--primary-gradient);
    }
    
    /* Table styling */
    .info-table th {
        width: 220px;
        vertical-align: middle;
        padding: 0.75rem 1rem;
        color: var(--dark-color);
        background-color: var(--primary-light) !important;
        border-color: rgba(67, 97, 238, 0.2);
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .info-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }
    
    .info-table i {
        width: 24px;
        text-align: center;
        margin-right: 0.5rem;
        color: var(--primary-color);
    }
    
    .items-table th {
        text-transform: uppercase;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        background: var(--dark-color);
        color: white;
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }
    
    .items-table td {
        padding: 1rem;
        vertical-align: middle;
    }
    
    .items-table tr:hover {
        background-color: var(--primary-light);
    }
    
    /* Badge styling */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Product image */
    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.5rem;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .product-image:hover {
        transform: scale(1.05);
    }
    
    /* Color preview */
    .color-preview {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-block;
        border: 2px solid white;
        box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
        margin-right: 0.75rem;
    }
    
    /* Back button */
    .back-btn {
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .back-btn:hover {
        transform: var(--hover-transform);
        box-shadow: var(--box-shadow);
    }
    
    .back-btn i {
        font-size: 1.1rem;
    }
    
    /* Price styling */
    .price-value {
        font-weight: 700;
        color: var(--success-color);
    }
    
    .total-price {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--danger-color);
    }
    
    /* Order ID styling */
    .order-title {
        position: relative;
        display: inline-block;
        padding-bottom: 0.5rem;
    }
    
    .order-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 3px;
        width: 50%;
        background: var(--primary-gradient);
        border-radius: 50px;
    }
    
    /* Payment section */
    .payment-table thead th {
        background-color: var(--success-light) !important;
        color: var(--dark-color);
        border-color: rgba(46, 204, 113, 0.2);
        padding: 0.75rem 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .payment-table tbody td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
    }
    
    .payment-table i {
        margin-right: 0.5rem;
        width: 18px;
        text-align: center;
    }
    
    .payment-badge {
        padding: 0.4rem 1rem;
        border-radius: 50rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .info-table th {
            width: 150px;
        }
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="main-card p-4">
        <!-- Order Header -->
        <div style="display: flex; align-items: center; justify-content: space-between">
            <h2 class="text-primary fw-bold order-title mb-4">
                <i class="fas fa-receipt me-2"></i> Chi tiết đơn hàng #{{ $order->order_number }}
            </h2>
            <a href="{{ route('orders.print', $order->id) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2">
                <i class="bi bi-printer"></i> In hóa đơn
            </a>            
        </div>
        <div class="section-card">
            <div class="section-header bg-white text-primary">
                <i class="fas fa-info-circle"></i> Thông tin đơn hàng
            </div>
            <div class="card-body">
                <table class="table info-table mb-0">
                    <tbody>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> Mã đơn hàng:</th>
                            <td class="fw-bold">{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-user"></i> Khách hàng:</th>
                            <td>{{ $order->customer->name }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-envelope"></i> Email:</th>
                            <td>{{ $order->customer->email }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-calendar-alt"></i> Ngày tạo:</th>
                            <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-exclamation-circle"></i> Trạng thái:</th>
                            <td>
                                @php
                                    $statusClasses = [
                                        'Waiting for confirmation' => 'bg-warning-subtle text-warning border border-warning',
                                        'Processing' => 'bg-info-subtle text-info border border-info',
                                        'Delivering' => 'bg-primary-subtle text-primary border border-primary',
                                        'Completed' => 'bg-success-subtle text-success border border-success',
                                        'Cancel' => 'bg-danger-subtle text-danger border border-danger'
                                    ];
                                    $statusIcons = [
                                        'Waiting for confirmation' => 'fas fa-hourglass-half',
                                        'Processing' => 'fas fa-cogs',
                                        'Delivering' => 'fas fa-truck',
                                        'Completed' => 'fas fa-check-circle',
                                        'Cancel' => 'fas fa-times-circle'
                                    ];
                                @endphp
                                <span class="status-badge {{ $statusClasses[$order->status] }}">
                                    <i class="{{ $statusIcons[$order->status] }}"></i>
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-wallet"></i> Phương thức thanh toán:</th>
                            <td>{{ $order->payment_method }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-money-check-alt"></i> Trạng thái thanh toán:</th>
                            <td>
                                @php
                                    $paymentStatusClasses = [
                                        'Paid' => 'bg-success-subtle text-success border border-success',
                                        'Pending' => 'bg-warning-subtle text-warning border border-warning',
                                        'Failed' => 'bg-danger-subtle text-danger border border-danger',
                                        'Unpaid' => 'bg-secondary-subtle text-secondary border border-secondary'
                                    ];
                                    $paymentStatusIcons = [
                                        'Paid' => 'fas fa-check-circle',
                                        'Pending' => 'fas fa-hourglass-half',
                                        'Failed' => 'fas fa-times-circle',
                                        'Unpaid' => 'fas fa-ban'
                                    ];
                                @endphp
                                <span class="status-badge {{ $paymentStatusClasses[$order->payment_status] ?? 'bg-secondary' }}">
                                    <i class="{{ $paymentStatusIcons[$order->payment_status] ?? 'fas fa-question-circle' }}"></i>
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-dollar-sign"></i> Tổng giá:</th>
                            <td class="total-price">{{ number_format($order->total_price) }} ₫</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng:</th>
                            <td>
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-home text-danger mt-1"></i>
                                    <div>
                                        {{ $order->shippingAddress->address }}, 
                                        {{ $order->shippingAddress->ward_name }}, 
                                        {{ $order->shippingAddress->district_name }}, 
                                        {{ $order->shippingAddress->province_name }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if ($order->discount_amount > 0 && isset($order->coupon))
            <div class="card-body text-center bg-info p-3 rounded shadow-sm" style="margin-bottom: 15px;">
                <p class="mb-0 text-success fw-bold">
                    <i class="fas fa-gift text-danger"></i> 🎉 Chúc mừng! Bạn đã áp dụng mã giảm giá  
                </p>
                <p class="mt-1 mb-0 text-primary fs-5">
                    <span class="badge bg-danger text-white px-3 py-2 fs-6 shadow">
                        {{ $order->coupon->code }}
                    </span>
                </p>
                <p class="mt-2 text-dark">
                    <i class="fas fa-tags text-warning"></i> Số tiền được giảm: 
                    <strong class="text-danger fs-5">{{ number_format($order->discount_amount) }}₫</strong>
                </p>
            </div>
        @endif

    
        <!-- Payment Info Card -->
        @if ($order->payments && $order->payments->count() > 0)
            <div class="section-card">
                <div class="section-header bg-white text-success">
                    <i class="fas fa-credit-card"></i> Thông tin thanh toán
                </div>
                <div class="card-body">
                    <table class="table payment-table mb-0">
                        <thead>
                            <tr>
                                <th><i class="fas fa-university"></i> Cổng Thanh Toán</th>
                                <th><i class="fas fa-receipt"></i> Mã Giao Dịch</th>
                                <th><i class="fas fa-money-bill-wave"></i> Số Tiền</th>
                                <th class="text-center"><i class="fas fa-exclamation-circle"></i> Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->payments as $payment)
                                <tr>
                                    <td class="fw-bold text-secondary"><i class="fas fa-wallet"></i> {{ $payment->payment_gateway }}</td>
                                    <td><i class="fas fa-barcode"></i> {{ $payment->transaction_id }}</td>
                                    <td class="price-value"><i class="fas fa-coins"></i> {{ number_format($payment->amount, 2) }} ₫</td>
                                    <td class="text-center">
                                        @php
                                            $paymentClasses = [
                                                'success' => 'bg-success-subtle text-success border border-success',
                                                'pending' => 'bg-warning-subtle text-warning border border-warning',
                                                'failed' => 'bg-danger-subtle text-danger border border-danger'
                                            ];
                                            $paymentIcons = [
                                                'success' => 'fas fa-check-circle',
                                                'pending' => 'fas fa-hourglass-half',
                                                'failed' => 'fas fa-times-circle'
                                            ];
                                            $paymentLabels = [
                                                'success' => 'Thành Công',
                                                'pending' => 'Đang Chờ',
                                                'failed' => 'Thất Bại'
                                            ];
                                        @endphp
                                        <span class="payment-badge {{ $paymentClasses[$payment->status] }}">
                                            <i class="{{ $paymentIcons[$payment->status] }}"></i>
                                            {{ $paymentLabels[$payment->status] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <div>Đơn hàng chưa được thanh toán online.</div>
            </div>
        @endif
        <!-- Product List Card -->
        <div class="section-card">
            <div class="section-header bg-white text-dark">
                <i class="fas fa-box-open"></i> Danh sách sản phẩm
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table items-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center"><i class="fas fa-image"></i> Hình ảnh</th>
                                <th><i class="fas fa-tag"></i> Tên sản phẩm</th>
                                <th class="text-center"><i class="fas fa-palette"></i> Màu sắc</th>
                                <th class="text-end"><i class="fas fa-money-bill-wave"></i> Giá</th>
                                <th class="text-center"><i class="fas fa-sort-numeric-up"></i> Số lượng</th>
                                <th class="text-end"><i class="fas fa-calculator"></i> Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="text-center">
                                        <img src="{{ asset('imgProduct/' . $item->product->images) }}" 
                                             alt="{{ $item->product->product_name }}" 
                                             class="product-image">
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ Str::limit($item->product->product_name, 50) }}</div>
                                        <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($item->color->color))
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="color-preview"
                                                     style="background-color: {{ $item->color->color }};">
                                                </div>
                                                <span>{{ $item->color->color }}</span>
                                            </div>
                                        @else
                                            <span class="text-secondary">Không có</span>
                                        @endif
                                    </td>
                                    <td class="text-end price-value">{{ number_format($item->price) }} ₫</td>
                                    <td class="text-center">
                                        <span class="fw-bold badge bg-warning">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-end total-price">{{ number_format($item->price * $item->quantity, 2) }} ₫</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Tổng giảm giá:</td>
                                <td class="text-end text-danger">
                                    -{{ number_format($order->orderItems->sum(fn($item) => $item->product->discount ? $item->price * ($item->product->discount->discount_value / 100) * $item->quantity : 0)) }} ₫
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Tổng cộng:</td>
                                <td class="text-end total-price">{{ number_format($order->total_price) }} ₫</td>
                            </tr>
                        </tfoot>                        
                    </table>
                </div>
            </div>
        </div>

        <!-- Back button -->
        <div class="mt-4 text-center">
            <a href="{{ route('orders.list') }}" class="btn btn-primary back-btn shadow">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách đơn hàng
            </a>
        </div>
    </div>
</div>
@endsection