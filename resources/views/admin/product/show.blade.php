@extends('admin.layouts.app')

@section('content')
<div class="product-page">
    <div class="">
        <div class="product-container bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="row">
                <!-- Gallery Section -->
                <div class="col-lg-6 p-0">
                    <div class="gallery-section">
                        <!-- Ảnh chính -->
                        <div class="main-image-container position-relative overflow-hidden">
                            <img id="mainImage" src="{{ asset($product->images[0]->image_path ?? 'path/to/default.jpg') }}"
                                 class="w-100 h-100 object-cover" alt="{{ $product->product_name }}">
                
                            <!-- Nút điều hướng -->
                            <div class="image-navigation">
                                <button class="nav-btn prev-btn" onclick="prevImage()">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="nav-btn next-btn" onclick="nextImage()">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                
                        <!-- Danh sách ảnh thu nhỏ -->
                        <div class="thumbnails-wrapper py-3 px-4">
                            <div class="thumbnails-slider d-flex gap-2">
                                @foreach ($product->images as $index => $img)
                                    <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}"
                                        onclick="changeImage(this, {{ $index }})"
                                        data-src="{{ asset($img->image_path) }}">
                                        <img src="{{ asset($img->image_path) }}" alt="Thumbnail {{ $index }}"
                                            class="thumbnail-img" style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>                
                
                <!-- Product Info Section -->
                <div class="col-lg-6">
                    <div class="product-info-section p-5">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('product.list') }}">Sản phẩm</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $product->product_name }}</li>
                            </ol>
                        </nav>
                        
                        <div class="product-header mt-4">
                            <h1 class="product-title">{{ $product->product_name }}</h1>
                            <div class="product-meta">
                                <div class="product-brand">
                                    <span class="brand-label">Thương hiệu:</span>
                                    <span class="brand-value">{{ $product->brand->brand_name }}</span>
                                </div>
                                <div class="product-id">
                                    <span class="id-label">Mã SP:</span>
                                    <span class="id-value">{{ $product->id }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-price-section my-4">
                            <div class="current-price">{{ number_format($product->price, 0, ',', '.') }}₫</div>
                            @if(isset($product->original_price) && $product->original_price > $product->price)
                                <div class="original-price">{{ number_format($product->original_price, 0, ',', '.') }}₫</div>
                                <div class="discount-badge">
                                    {{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}% GIẢM
                                </div>
                            @endif
                        </div>
                        
                        <div class="product-details">
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">Danh mục:</span>
                                    <span class="detail-value">{{ $product->category->category_name }}</span>
                                </div>
                            </div>
                            
                            @if(isset($product->stock))
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">Tình trạng:</span>
                                    <span class="detail-value {{ $product->stock > 0 ? 'in-stock' : 'out-of-stock' }}">
                                        {{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                    </span>
                                </div>
                            </div>
                            @endif
                            
                            @if(isset($product->created_at))
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">Ngày thêm:</span>
                                    <span class="detail-value">{{ $product->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        @if(isset($product->short_description))
                        <div class="product-description my-4">
                            <p>{{ $product->short_description }}</p>
                        </div>
                        @endif
                        
                        <div class="admin-actions mt-5">
                            <a href="{{ route('product.edit', ['id' => $product->id]) }}" class="btn-edit">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            <form action="{{ route('product.destroy', ['id' => $product->id]) }}" method="POST" 
                                  class="delete-form" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                            <a href="{{ route('product.list') }}" class="btn-back">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Description Tab -->
            @if(isset($product->description))
            <div class="row">
                <div class="col-12">
                    <div class="product-tabs">
                        <ul class="nav nav-tabs" id="productTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab">
                                    Mô tả sản phẩm
                                </a>
                            </li>
                            @if(isset($product->specifications))
                            <li class="nav-item">
                                <a class="nav-link" id="specs-tab" data-toggle="tab" href="#specs" role="tab">
                                    Thông số kỹ thuật
                                </a>
                            </li>
                            @endif
                        </ul>
                        
                        <div class="tab-content p-4" id="productTabsContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel">
                                <div class="product-description-content">
                                    {!! $product->description !!}
                                </div>
                            </div>
                            
                            @if(isset($product->specifications))
                            <div class="tab-pane fade" id="specs" role="tabpanel">
                                <div class="product-specs-content">
                                    {!! $product->specifications !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Base Styles */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f8f9fa;
    color: #333;
}


.product-container {
    overflow: hidden;
    transition: all 0.3s ease;
}

/* Gallery Section */
.gallery-section {
    background-color: #f7f7f7;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.main-image-container {
    height: 450px;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

#mainImage {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.5s ease;
}

.image-navigation {
    position: absolute;
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 0 20px;
    z-index: 10;
}

.nav-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.8);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.nav-btn:hover {
    background-color: #fff;
    transform: scale(1.1);
}

.nav-btn i {
    color: #333;
    font-size: 18px;
}

.image-counter {
    position: absolute;
    bottom: 15px;
    right: 15px;
    background-color: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
}

/* Thumbnails Slider */
.thumbnails-wrapper {
    background-color: #fff;
    border-top: 1px solid #eee;
}

.thumbnails-slider {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 10px 0;
    scrollbar-width: thin;
}

.thumbnails-slider::-webkit-scrollbar {
    height: 5px;
}

.thumbnails-slider::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.thumbnails-slider::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 10px;
}

.thumbnail-item {
    width: 80px;
    height: 80px;
    border-radius: 4px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.thumbnail-item:hover {
    border-color: #ddd;
    transform: translateY(-2px);
}

.thumbnail-item.active {
    border-color: #4a89dc;
}

.thumbnail-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Product Info Section */
.product-info-section {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.breadcrumb {
    font-size: 14px;
}

.breadcrumb a {
    color: #4a89dc;
    text-decoration: none;
}

.breadcrumb-item+.breadcrumb-item::before {
    content: "›";
    color: #aaa;
}

.product-header {
    margin-bottom: 20px;
}

.product-title {
    font-size: 28px;
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: 15px;
    color: #333;
}

.product-meta {
    display: flex;
    gap: 20px;
    color: #666;
    font-size: 14px;
}

.product-brand, .product-id {
    display: flex;
    align-items: center;
    gap: 5px;
}

.brand-label, .id-label {
    color: #888;
}

.brand-value, .id-value {
    font-weight: 500;
}

/* Price Section */
.product-price-section {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 25px 0;
}

.current-price {
    font-size: 32px;
    font-weight: 700;
    color: #e53935;
}

.original-price {
    font-size: 18px;
    color: #999;
    text-decoration: line-through;
}

.discount-badge {
    background-color: #e53935;
    color: white;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
}

/* Product Details */
.product-details {
    margin: 25px 0;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-icon {
    width: 40px;
    height: 40px;
    background-color: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.detail-icon i {
    color: #4a89dc;
    font-size: 18px;
}

.detail-content {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 14px;
    color: #888;
    margin-bottom: 3px;
}

.detail-value {
    font-weight: 500;
}

.in-stock {
    color: #43a047;
}

.out-of-stock {
    color: #e53935;
}

/* Product Description */
.product-description p {
    color: #666;
    line-height: 1.6;
}

/* Admin Actions */
.admin-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn-edit, .btn-delete, .btn-back {
    padding: 12px 20px;
    border-radius: 6px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
    cursor: pointer;
}

.btn-edit {
    background-color: #4a89dc;
    color: white;
    border: none;
}

.btn-edit:hover {
    background-color: #3a70c0;
    transform: translateY(-2px);
}

.btn-delete {
    background-color: #e53935;
    color: white;
    border: none;
}

.btn-delete:hover {
    background-color: #d32f2f;
    transform: translateY(-2px);
}

.btn-back {
    background-color: #f8f9fa;
    color: #666;
    border: 1px solid #ddd;
}

.btn-back:hover {
    background-color: #f1f1f1;
    transform: translateY(-2px);
}

.delete-form {
    display: inline;
}

/* Product Tabs */
.product-tabs {
    margin-top: 30px;
    background-color: #fff;
    border-top: 1px solid #eee;
}

.nav-tabs {
    border-bottom: 1px solid #eee;
}

.nav-tabs .nav-link {
    color: #666;
    font-weight: 500;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 15px 25px;
    border-radius: 0;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link.active {
    color: #4a89dc;
    border-bottom: 3px solid #4a89dc;
    background-color: transparent;
}

.tab-content {
    padding: 30px;
}

.product-description-content,
.product-specs-content {
    color: #666;
    line-height: 1.8;
    font-size: 15px;
}

.product-description-content h2,
.product-specs-content h2 {
    font-size: 22px;
    margin: 25px 0 15px;
    color: #444;
}

.product-description-content ul,
.product-specs-content ul {
    padding-left: 20px;
    margin-bottom: 20px;
}

.product-description-content li,
.product-specs-content li {
    margin-bottom: 10px;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .main-image-container {
        height: 350px;
    }
    
    .product-info-section {
        padding: 30px 20px;
    }
    
    .product-title {
        font-size: 24px;
    }
    
    .current-price {
        font-size: 28px;
    }
}

@media (max-width: 767px) {
    .main-image-container {
        height: 300px;
    }
    
    .thumbnail-item {
        width: 60px;
        height: 60px;
    }
    
    .admin-actions {
        flex-wrap: wrap;
    }
    
    .product-tabs .nav-link {
        padding: 10px 15px;
        font-size: 14px;
    }
}

@media (max-width: 576px) {
    .main-image-container {
        height: 250px;
    }
    
    .product-title {
        font-size: 20px;
    }
    
    .current-price {
        font-size: 24px;
    }
    
    .btn-edit, .btn-delete, .btn-back {
        padding: 10px 15px;
        font-size: 14px;
    }
}
</style>

<script>
    let currentIndex = 0;
    const allThumbnails = document.querySelectorAll('.thumbnail-item');
    const totalImages = allThumbnails.length;

    function changeImage(element, index) {
        const newSrc = element.getAttribute('data-src');
        document.getElementById('mainImage').src = newSrc;

        // Cập nhật trạng thái active
        document.querySelectorAll('.thumbnail-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        currentIndex = index;
    }

    function prevImage() {
        if (totalImages > 0) {
            currentIndex = (currentIndex - 1 + totalImages) % totalImages;
            changeImage(allThumbnails[currentIndex], currentIndex);
        }
    }

    function nextImage() {
        if (totalImages > 0) {
            currentIndex = (currentIndex + 1) % totalImages;
            changeImage(allThumbnails[currentIndex], currentIndex);
        }
    }

    // ✅ Tự động slide mỗi 3 giây
    setInterval(() => {
        nextImage();
    }, 3000);
</script>
@endsection