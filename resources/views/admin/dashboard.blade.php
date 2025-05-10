@extends('admin.layouts.app')

@section('content')
<style>
    .dashboard-card {
        border-radius: 12px;
        padding: 20px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .card-icon {
        font-size: 2rem;
        opacity: 0.7;
    }
    .card-gradient-primary { background: linear-gradient(45deg, #4facfe, #00f2fe); }
    .card-gradient-success { background: linear-gradient(45deg, #42e695, #3bb2b8); }
    .card-gradient-warning { background: linear-gradient(45deg, #ff9a44, #fc6076); }
    .card-gradient-danger { background: linear-gradient(45deg, #f7797d, #fbd786); }
    
    /* Đảm bảo tất cả các biểu đồ có kích thước giống nhau */
    .chart-container {
        width: 100%;
        max-width: 600px;
        margin: auto;
        padding: 20px 0;
    }
    
    canvas {
        width: 100% !important;
        height: 300px !important;
    }
    
    .card {
        min-height: 400px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    h2, h1 {
        text-align: center;
        margin-top: 40px;
    }
    canvas#orderStatusChart {
        max-width: 400px;
        max-height: 400px;
        margin: auto;
    }
    
    /* New styles */
    .table-responsive {
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    
    .dashboard-table {
        margin-bottom: 0;
    }
    
    .dashboard-table thead {
        background-color: #f8f9fa;
    }
    
    .dashboard-table th {
        font-weight: 600;
        border-bottom-width: 1px;
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 30px;
    }
    
    .low-stock {
        color: #721c24;
        background-color: #f8d7da;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .date-badge {
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #343a40;
        position: relative;
        padding-left: 15px;
    }
    
    .section-title:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: linear-gradient(45deg, #4facfe, #00f2fe);
        border-radius: 4px;
    }
    
    .card-dashboard {
        border-radius: 10px;
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }
    
    .card-dashboard:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .card-header-custom {
        background-color: #fff;
        border-bottom: 1px solid #f1f1f1;
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: #343a40;
    }
    
    .bg-purple-gradient {
        background: linear-gradient(45deg, #a166ab, #5073b8);
    }
    
    .bg-orange-gradient {
        background: linear-gradient(45deg, #FFA500, #FF6347);
    }
    
    .top-product-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .top-product-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .product-image {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        margin-right: 15px;
    }
    
    .product-details {
        flex-grow: 1;
    }
    
    .product-name {
        font-weight: 600;
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    
    .product-category {
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    .product-sales {
        font-weight: 600;
        color: #28a745;
    }
    
    .activity-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .activity-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1rem;
        color: #495057;
    }
    
    .order-icon { background-color: #cce5ff; color: #0d6efd; }
    .product-icon { background-color: #d1e7dd; color: #198754; }
    .user-icon { background-color: #f8d7da; color: #dc3545; }
    
    .activity-content {
        flex-grow: 1;
    }
    
    .activity-title {
        font-weight: 600;
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    
    .activity-time {
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    .progress-thin {
        height: 5px;
        border-radius: 5px;
    }



    /* Sản phẩm bán chạy nhất */
    .card-dashboard {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card-header-custom {
        padding: 15px 20px;
        background: linear-gradient(to right, #f8f9fa, #e9ecef);
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .top-product-item {
        border-radius: 10px;
        background-color: #fff;
        transition: all 0.3s ease;
    }

    .top-product-item:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }

    .product-image-wrapper {
        height: 180px;
        overflow: hidden;
        position: relative;
    }

    .product-image {
        height: 100%;
        transition: transform 0.5s ease;
    }
    .product-badge {
        top: 10px;
        left: 10px;
        z-index: 2;
    }

    .product-name {
        font-size: 1rem;
        line-height: 1.4;
    }

    .ratings {
        font-size: 0.8rem;
    }

</style>

{{-- Thông báo --}}
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

<div class="container-fluid p-4">
    <div class="row g-3">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card card-gradient-primary">
                <i class="bi bi-people card-icon"></i>
                <div>
                    <h5>Users</h5>
                    <p class="fs-4 fw-bold">{{ $user }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card card-gradient-success">
                <i class="bi bi-boxes card-icon"></i>
                <div>
                    <h5>Products</h5>
                    <p class="fs-4 fw-bold">{{$product}}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card card-gradient-warning">
                <i class="bi bi-cart-check card-icon"></i>
                <div>
                    <h5>Orders</h5>
                    <p class="fs-4 fw-bold">{{ number_format($countOrder) }}</p>
                </div>
            </div>
        </div>
        @php
            $total = array_sum(array_column($order->toArray(), 'total_amount'));
        @endphp
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card card-gradient-danger">
                <i class="bi bi-cash-coin card-icon"></i>
                <div>
                    <h5>Revenue</h5>
                    <p class="fs-4 fw-bold">{{ number_format($total) }}₫</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sản phẩm gần hết hàng và Người dùng mới nhất -->
    <div class="row mt-4">
        <!-- Sản phẩm gần hết hàng -->
        <div class="col-md-6 mb-4">
            <div class="card card-dashboard h-100">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                        Sản phẩm gần hết hàng
                    </div>
                    <a href="{{ route('product.list') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover dashboard-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Màu sắc</th>
                                    <th>Còn lại</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($colors as $color)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($color->product->images[0]->image_path ?? 'images/no-image.png') }}"
                                                 alt="{{ $color->product->name }}" class="product-image">
                                            {{-- <div>{{ $color->product->product_name }}</div> --}}
                                        </div>
                                    </td>
                                    <td>{{ $color->product->category->category_name ?? 'N/A' }}</td>
                                    <td>{{ $color->name }}</td>
                                    @php
                                        // Lấy quantity nhỏ nhất trong các màu low-stock
                                        $minQty = $color->quantity;
                                        $threshold = 10;
                                        $percentLeft = ($minQty / $threshold) * 100;
                                        $progressClass = $percentLeft < 10 
                                                        ? 'bg-danger' 
                                                        : ($percentLeft < 30 
                                                            ? 'bg-warning' 
                                                            : 'bg-success');
                                    @endphp
                                    <td>
                                        <div class="progress progress-thin">
                                            <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $percentLeft }}%;" aria-valuenow="{{ $minQty }}" aria-valuemin="0" aria-valuemax="{{ $threshold }}"></div>
                                        </div>
                                        <span class="small text-muted">{{ $minQty }} sản phẩm</span>
                                    <td>
                                        <span class="status-badge low-stock">
                                            <i class="bi bi-exclamation-circle me-1"></i>Low Stock
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="bi bi-check-circle text-success fs-4 d-block mb-2"></i>
                                        Không có sản phẩm nào gần hết hàng
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Người dùng mới nhất -->
        <div class="col-md-6 mb-4">
            <div class="card card-dashboard h-100">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-person-plus text-primary me-2"></i>
                        Người dùng mới nhất
                    </div>
                    <a href="" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover dashboard-table">
                            <thead>
                                <tr>
                                    <th>Người dùng</th>
                                    <th>Email</th>
                                    <th>Ngày đăng ký</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers ?? [] as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($user->image ? 'imageUser/' . $user->image : 'imageUser/user.png') }}" alt="" class="user-avatar me-2">
                                            <div>{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="date-badge">
                                            <i class="bi bi-calendar-date me-1"></i>
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="" class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="bi bi-people text-primary fs-4 d-block mb-2"></i>
                                        Chưa có người dùng mới
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Đơn hàng gần đây và Sản phẩm bán chạy -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card card-dashboard h-100">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-receipt text-success me-2"></i>
                        Đơn hàng gần đây
                    </div>
                    <a href="{{ route('orders.list') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover dashboard-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders ?? [] as $order)
                                <tr>
                                    <td>
                                        <div class="fw-bold">
                                            #{{ $order->order_number }}
                                        </div>
                                        <div class="small text-muted">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td>{{ $order->user->name ?? $order->customer_name }}</td>
                                    <td>{{ number_format($order->total_amount) }}₫</td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-warning',
                                                'processing' => 'bg-info',
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                            ];
                                            $statusClass = $statusClasses[$order->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="bi bi-cart-x text-secondary fs-4 d-block mb-2"></i>
                                        Chưa có đơn hàng nào
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hoạt động mới nhất -->
        <div class="col-md-6 mb-4">
            <div class="card card-dashboard h-100">
                <div class="card-header-custom">
                    <i class="bi bi-activity text-danger me-2"></i>
                    Hoạt động gần đây
                </div>
                <div class="card-body">
                    @forelse($recent_activities ?? [] as $activity)
                    <div class="activity-item">
                        <div class="activity-icon 
                            {{ $activity->activity_type == 'order' ? 'order-icon' : 
                            ($activity->activity_type == 'product' ? 'product-icon' : 'user-icon') }}">
                            <i class="bi 
                                {{ $activity->activity_type == 'order' ? 'bi-cart-check' : 
                                ($activity->activity_type == 'product' ? 'bi-box' : 'bi-person') }}"></i>
                        </div>
                        <div class="activity-content">
                            <h6 class="activity-title">
                                @if($activity->activity_type == 'order')
                                    Đơn hàng #{{ $activity->order_number}} đã được {{ $activity->action ?? '' }} bởi {{ $activity->user->role}} {{ $activity->user->name }}
                                @elseif($activity->activity_type == 'product')
                                    Sản phẩm {{ $activity->product_name}} đã được {{ $activity->action ?? '' }} bởi {{ $activity->user->role}} {{ $activity->user->name }}
                                @endif
                            </h6>
                            <span class="date-badge">
                                <i class="bi bi-calendar-date me-1"></i>
                                {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x text-secondary fs-4 d-block mb-2"></i>
                        Chưa có hoạt động nào gần đây
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm chưa được bán -->
    <div class="col-md-12 mb-4">
        <div class="card card-dashboard h-100">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-box-seam text-danger me-2"></i>
                    Sản phẩm chưa được bán
                </div>
                <a href="" class="btn btn-sm btn-outline-danger">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover dashboard-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Ngày thêm</th>
                                <th>Giảm giá sản phẩm</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unsoldProducts ?? [] as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($product->images ?  $product->images[0]->image_path : 'imageProduct/default.png') }}"
                                            alt="" class="user-avatar me-2">
                                        <div>{{ $product->product_name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <i class="bi bi-calendar-date me-1"></i>
                                        {{ \Carbon\Carbon::parse($product->created_at)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($product->discount)
                                        <span class="badge bg-success rounded-pill px-3 py-2 fw-bold">
                                            {{ $product->discount->discount_value }}%
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2 fw-bold">
                                            Không có
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="bi bi-box text-danger fs-4 d-block mb-2"></i>
                                    Tất cả sản phẩm đều đã được bán
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Biểu đồ thống kê -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header-custom">
                    <i class="bi bi-graph-up text-primary me-2"></i>
                    Doanh thu trong 7 ngày qua
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card card-dashboard">
                <div class="card-header-custom">
                    <i class="bi bi-pie-chart text-success me-2"></i>
                    Tỷ lệ trạng thái đơn hàng
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top sản phẩm -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card card-dashboard shadow-sm">
                <div class="card-header-custom bg-gradient">
                    <i class="bi bi-trophy-fill text-warning me-2"></i>
                    <span class="fw-bold">Sản phẩm bán chạy nhất</span>
                    <a href="#" class="float-end text-decoration-none text-muted small">Xem tất cả</a>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        @forelse($topProducts as $product)
                        <div class="col-md-3 col-sm-6">
                            <div class="top-product-item position-relative shadow-sm rounded overflow-hidden h-100">
                                <div class="product-badge position-absolute">
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">
                                        <i class="bi bi-fire me-1"></i>Hot
                                    </span>
                                </div>
                                <div class="product-image-wrapper">
                                    <img src="{{ asset($product->images[0]->image_path) }}" alt="{{ $product->name }}" 
                                        class="product-image img-fluid w-100 object-fit-cover">
                                </div>
                                <div class="product-details p-3">
                                    <h5 class="product-name fw-semibold text-truncate mb-1">{{ $product->name }}</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="product-category text-muted small">
                                            <i class="bi bi-tag me-1"></i>{{ $product->category->category_name ?? 'N/A' }}
                                        </span>
                                        <span class="product-price fw-bold text-primary">
                                            {{ number_format($product->price) }}₫
                                        </span>
                                    </div>
                                    <div class="product-sales d-flex justify-content-between align-items-center border-top pt-2">
                                        @php
                                            $rating = $product->average_rating ?? 0; // Giá trị xếp hạng, mặc định là 0 nếu không có
                                            $fullStars = floor($rating); // Số sao đầy đủ
                                            $halfStar = ($rating - $fullStars) >= 0.5; // Nếu còn ít nhất 0.5 sao, thì có một sao rưỡi
                                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0); // Số sao trống
                                        @endphp
                                        <div class="ratings">
                                            @for ($i = 0; $i < $fullStars; $i++)
                                                <i class="bi bi-star-fill text-warning"></i> <!-- Sao đầy đủ -->
                                            @endfor
                                            @if ($halfStar)
                                                <i class="bi bi-star-half text-warning"></i> <!-- Sao rưỡi -->
                                            @endif
                                            @for ($i = 0; $i < $emptyStars; $i++)
                                                <i class="bi bi-star text-warning"></i> <!-- Sao trống -->
                                            @endfor
                                            @if ($rating > 0)
                                                <span class="ms-1 small text-muted">({{ number_format($rating, 1) }})</span>
                                            @endif
                                        </div>
                                        <span class="badge bg-success rounded-pill fw-normal px-3">
                                            <i class="bi bi-bag-check me-1"></i>{{ $product->total_sold }} đã bán
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-bag-x text-secondary fs-1 d-block mb-3 opacity-50"></i>
                                <h5 class="text-muted">Chưa có dữ liệu sản phẩm bán chạy</h5>
                                <p class="text-muted small">Các sản phẩm bán chạy sẽ được hiển thị tại đây khi có dữ liệu</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Thống kê đơn hàng (Biểu đồ tròn)
    function fetchChartData(url, callback) {
        fetch(url)
            .then(response => response.json())
            .then(callback)
            .catch(error => console.error('Lỗi tải dữ liệu:', error));
    }
    fetchChartData('/api/statistics/orderStatusStats', data => {
        new Chart(document.getElementById('orderStatusChart'), {
            type: 'pie',
            data: {
                labels: data.map(item => item.status),
                datasets: [{
                    data: data.map(item => item.count),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                aspectRatio: 1 
            }
        });
    });
    // Doanh thu theo tuần
        fetchChartData('/api/statistics/weeklyRevenueStats', data => {
            new Chart(document.getElementById('weeklyRevenueChart'), {
                type: 'line',
                data: {
                    labels: data.map(item => 'Tuần ' + item.week + ' - ' + item.year),
                    datasets: [{
                        label: 'Doanh thu tuần',
                        data: data.map(item => item.revenue),
                        borderColor: '#FF6384',
                        fill: false,
                    }]
                },
                options: { responsive: true }
            });
        });
                fetchChartData('/api/statistics/dailyRevenueStats', data => {
            new Chart(document.getElementById('dailyRevenueChart'), {
                type: 'line',
                data: {
                    labels: data.map(item => item.date),
                    datasets: [{
                        label: 'Doanh thu ngày',
                        data: data.map(item => item.revenue),
                        borderColor: '#36A2EB',
                        fill: false,
                    }]
                },
                options: { responsive: true }
            });
        });
</script>
@endsection