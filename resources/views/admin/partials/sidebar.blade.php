<style>
    .scrollbar-overlay::-webkit-scrollbar {
        width: 1px;                /* Độ rộng thanh cuộn */
    }

    .scrollbar-overlay::-webkit-scrollbar-thumb {
        background-color: #ccc;    /* Màu của thanh kéo */
        border-radius: 4px;        /* Bo góc cho đẹp */
    }

    .scrollbar-overlay::-webkit-scrollbar-track {
        background-color: transparent; /* Nền trong suốt */
    }
</style>
<nav class="sidebar vh-100 overflow-y-auto scrollbar-overlay" id="sidebar">
    <div class="header">
        <span class="header-icon bi bi-person-circle"></span>
        <span class="header-text">Admin</span>
    </div>
    <div class="sidebarMenu nav flex-column mt-3" id="">
        <li class="nav-item">
            <a href="{{route('home')}}" class="nav-link">
                <i class="bi bi-house-door me-2"></i> <span>Trang chủ</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('staff.index')}}" class="nav-link">
                <i class="bi bi-people me-2"></i> <span>Quản lý nhân sự</span>
            </a>
        </li> 
        <li class="nav-item">
            <a href="{{ route('category.list') }}" class="nav-link">
                <i class="bi bi-tags me-2"></i> <span>Quản lý danh mục</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('brand.list') }}" class="nav-link">
                <i class="bi bi-building me-2"></i> <span>Quản lý thương hiệu</span>
            </a>
        </li>  
        <li class="nav-item">
            <a href="{{route('product.list')}}" class="nav-link">
                <i class="bi bi-box me-2"></i> <span>Quản lý sản phẩm</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('colors.index')}}" class="nav-link">
                <i class="bi bi-palette me-2"></i> <span>Quản lý màu sắc & số lượng</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('image.index')}}" class="nav-link">
                <i class="bi bi-palette me-2"></i> <span>Quản lý hình ảnh sản phẩm</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('orders.list')}}" class="nav-link">
                <i class="bi bi-cart-check me-2"></i><span>Quản lý đơn hàng</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('coupon.index')}}" class="nav-link">
                <i class="fas fa-tag me-2"></i><span>Quản lý mã giảm giá</span>
            </a>
        </li>      
        <li class="nav-item">
            <a href="d" class="nav-link">
                <i class="bi bi-person-lines-fill me-2"></i> <span>Quản lý người dùng</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="c" class="nav-link">
                <i class="bi bi-chat-left-text me-2"></i> <span>Quản lý bình luận</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('rating.index') }}" class="nav-link ">
                <i class="bi bi-star left-text me-2"></i>
                <span class="text-gray-700">Quản lý đánh giá</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('discount.index') }}" class="nav-link ">
                <i class="bi bi-fire me-2"></i>
                <span class="text-gray-700">Quản lý giảm giá sản phẩm</span>
            </a>
        </li> 
    </div>
</nav>
