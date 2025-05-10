<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Bán Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: "DejaVu Sans", sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.4;
            font-size: 0.9rem;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            border-radius: 8px;
            overflow: hidden;
        }
        .invoice-title-container {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #eaeaea;
        }
        
        .invoice-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .invoice-separator {
            color: #95a5a6;
            margin: 5px 0;
            font-size: 0.9rem;
        }
        
        .invoice-content {
            padding: 15px;
        }
        
        .section-title {
            background-color: #f1f5f9;
            padding: 8px 12px;
            margin-bottom: 12px;
            font-weight: 600;
            color: #2c3e50;
            border-radius: 4px;
            border-left: 3px solid #3498db;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }
        
        .shipping-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #eaeaea;
            font-size: 0.85rem;
        }
        
        .shipping-info p {
            margin: 0;
            line-height: 1.5;
        }
        
        .order-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 0 5px rgba(0,0,0,0.03);
            font-size: 0.85rem;
        }
        
        .order-table th {
            background-color: #3498db;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-weight: 500;
            font-size: 0.8rem;
        }
        
        .order-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .order-table tr:last-child td {
            border-bottom: none;
        }
        
        .order-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .section-card {
            margin-top: 15px;
            margin-bottom: 15px;
            border: 1px solid #e6e6e6;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .section-header {
            padding: 10px 15px;
            border-bottom: 1px solid #e6e6e6;
            font-weight: 600;
            font-size: 0.9rem;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>
    <div class="container invoice-container">
        <div class="invoice-title-container">
            <h1 class="invoice-title"><i class="bi bi-receipt"></i> HÓA ĐƠN BÁN HÀNG</h1>
            <div class="invoice-separator">••• <i class="bi bi-stars"></i> •••</div>
        </div>

        <div class="invoice-content">
            <div class="section-title">
                <i class="bi bi-truck"></i> THÔNG TIN VẬN CHUYỂN
            </div>
            <div class="shipping-info">
                <p><i class="bi bi-person-fill"></i> <strong>Khách hàng:</strong> {{ $order->shippingAddress->name }}</p>
                <p><i class="bi bi-telephone"></i> <strong>Điện thoại:</strong> 0{{ $order->shippingAddress->phone }}</p>
                <p><i class="bi bi-geo-alt"></i> <strong>Địa chỉ:</strong> {{ $order->shippingAddress->address }}, {{ $order->shippingAddress->ward_name }}, {{ $order->shippingAddress->district_name }}, {{ $order->shippingAddress->province_name }}</p>
            </div>
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
        <div class="section-title">
            <i class="bi bi-cart-check"></i> CHI TIẾT ĐƠN HÀNG
        </div>
            <div class="table-responsive">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-box-seam"></i> Sản phẩm</th>
                            <th><i class="bi bi-123"></i> SL</th>
                            <th><i class="bi bi-tag"></i> Giá</th>
                            <th><i class="bi bi-calculator"></i> Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr>
                                <td>{{$item->product->product_name}}</td>
                                <td>{{$item->quantity}}</td>
                                <td>{{number_format($item->price, 0, ',', '.')}}₫</td>
                                <td>{{number_format($item->price * $item->quantity, 0, ',', '.')}}₫</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Tổng giảm giá:</td>
                            <td class="text-end text-danger">
                                -{{ number_format($order->orderItems->sum(fn($item) => $item->product->discount ? $item->price * ($item->product->discount->discount_value / 100) * $item->quantity : 0)) }}₫
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                            <td class="text-end total-price">{{ number_format($order->total_price) }}₫</td>
                        </tr>
                    </tfoot>      
                </table>
            </div>
        </div>
    </div>
</div>