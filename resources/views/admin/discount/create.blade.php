@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-3 text-gray-800 font-weight-light">Thêm mới giảm giá sản phẩm</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3 bg-light py-2 px-3 rounded shadow-sm" style="font-size: 0.85rem;">
            <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/admin/discounts" class="text-decoration-none">Quản lý giảm giá</a></li>
            <li class="breadcrumb-item active">Thêm mới</li>
        </ol>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="font-size: 0.85rem;">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="font-size: 0.85rem;">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Form thêm mới giảm giá -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-gradient-primary text-white py-2" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                    <h5 class="mb-0 font-weight-light" style="font-size: 0.9rem;"><i class="fas fa-tag me-2"></i>Thêm mới giảm giá</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('discount.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="product_id" class="small text-muted mb-1">Sản phẩm <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-light"><i class="fas fa-box-open"></i></span>
                                    <select class="form-select form-select-sm" id="product_id" name="product_id" required>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('product_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="discount_type" class="small text-muted mb-1">Loại giảm giá <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-light"><i class="fas fa-percentage"></i></span>
                                    <select class="form-select form-select-sm" id="discount_type" name="discount_type" required>
                                        <option value="">-- Chọn loại giảm giá --</option>
                                        <option value="percent">Phần trăm (%)</option>
                                        <option value="fixed">Số tiền cố định</option>
                                    </select>
                                </div>
                                @error('discount_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="discount_value" class="small text-muted mb-1">Giá trị giảm giá <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-light"><i class="fas fa-tags"></i></span>
                                    <input class="form-control form-control-sm" id="discount_value" type="number" name="discount_value" min="0" step="0.01" required />
                                    <span class="input-group-text discount-symbol">%</span>
                                </div>
                                @error('discount_value')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="small text-muted mb-1">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-light"><i class="fas fa-calendar-plus"></i></span>
                                    <input class="form-control form-control-sm" id="start_date" type="date" name="start_date" required />
                                </div>
                                @error('start_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="small text-muted mb-1">Ngày kết thúc <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-light"><i class="fas fa-calendar-minus"></i></span>
                                    <input class="form-control form-control-sm" id="end_date" type="date" name="end_date" required />
                                </div>
                                @error('end_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="status" class="small text-muted mb-1">Trạng thái</label>
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-light"><i class="fas fa-toggle-on"></i></span>
                                    <select class="form-select form-select-sm" id="status" name="status" required>
                                        <option value="1">Kích hoạt</option>
                                        <option value="0">Không kích hoạt</option>
                                    </select>
                                </div>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3 mb-0">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-primary px-4">
                                    <i class="fas fa-save me-1"></i>Lưu giảm giá
                                </button>
                                <a href="{{ route('discount.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Hủy
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted"><i class="far fa-clock me-1"></i>Thao tác gần đây: {{ now()->format('H:i d/m/Y') }}</div>
                        <div class="small">
                            <a href="{{ route('discount.index') }}" class="text-decoration-none">
                                <i class="fas fa-list me-1"></i>Xem danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hướng dẫn thêm mới -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-gradient-info text-white py-2" style="background: linear-gradient(135deg, #36b9cc 0%, #1a8a98 100%);">
                    <h5 class="mb-0 font-weight-light" style="font-size: 0.9rem;"><i class="fas fa-info-circle me-2"></i>Hướng dẫn</h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom" style="font-size: 0.8rem;">
                        <h6 class="text-primary" style="font-size: 0.85rem;"><i class="fas fa-lightbulb me-1"></i>Cách thêm mới giảm giá</h6>
                        <ol class="ps-3 mb-0">
                            <li class="mb-1">
                                <span class="fw-medium">Chọn sản phẩm:</span> Chọn sản phẩm cần áp dụng giảm giá
                            </li>
                            <li class="mb-1">
                                <span class="fw-medium">Loại giảm giá:</span>
                                <ul class="ps-3 my-1 small">
                                    <li><span class="fw-medium">Phần trăm (%):</span> Giảm theo % giá sản phẩm</li>
                                    <li><span class="fw-medium">Số tiền cố định:</span> Giảm một số tiền nhất định</li>
                                </ul>
                            </li>
                            <li class="mb-1">
                                <span class="fw-medium">Giá trị giảm giá:</span>
                                <ul class="ps-3 my-1 small">
                                    <li>Nếu chọn <em>phần trăm</em>: Nhập số từ 1-100</li>
                                    <li>Nếu chọn <em>số tiền cố định</em>: Nhập số tiền cần giảm</li>
                                </ul>
                            </li>
                            <li class="mb-1">
                                <span class="fw-medium">Thời gian:</span> Chọn ngày bắt đầu và kết thúc
                            </li>
                            <li class="mb-1">
                                <span class="fw-medium">Trạng thái:</span> Kích hoạt/không kích hoạt
                            </li>
                        </ol>
                    </div>
                    
                    <div class="p-3" style="font-size: 0.8rem;">
                        <h6 class="text-warning" style="font-size: 0.85rem;"><i class="fas fa-exclamation-triangle me-1"></i>Lưu ý quan trọng</h6>
                        <ul class="ps-3 mb-0">
                            <li class="mb-1">Mỗi sản phẩm chỉ áp dụng một chương trình giảm giá tại một thời điểm</li>
                            <li class="mb-1">Nếu chọn giảm giá theo phần trăm, giá trị không được vượt quá 100%</li>
                            <li class="mb-1">Ngày kết thúc phải sau ngày bắt đầu</li>
                        </ul>
                    </div>
                </div>
                <div class="card-footer bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary rounded-pill">
                            <i class="fas fa-question-circle me-1"></i>Trợ giúp
                        </span>
                        <a href="#" class="text-decoration-none small">
                            <i class="fas fa-external-link-alt me-1"></i>Xem thêm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm JS cho việc kiểm tra form -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hiển thị thông báo khi chọn loại giảm giá
        const discountType = document.getElementById('discount_type');
        const discountValue = document.getElementById('discount_value');
        const discountSymbol = document.querySelector('.discount-symbol');
        
        // Thiết lập trạng thái ban đầu
        if (discountType.value === '') {
            discountSymbol.style.display = 'none';
        }
        
        discountType.addEventListener('change', function() {
            if (this.value === 'percentage') {
                discountValue.setAttribute('max', '100');
                discountValue.setAttribute('placeholder', 'Từ 1-100');
                discountSymbol.textContent = '%';
                discountSymbol.style.display = 'block';
            } else if (this.value === 'fixed') {
                discountValue.removeAttribute('max');
                discountValue.setAttribute('placeholder', 'Nhập số tiền');
                discountSymbol.textContent = 'đ';
                discountSymbol.style.display = 'block';
            } else {
                discountSymbol.style.display = 'none';
            }
        });
        
        // Kiểm tra ngày hợp lệ
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        
        // Thiết lập ngày mặc định
        const today = new Date();
        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1;
        let dd = today.getDate();
        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;
        const formattedToday = yyyy + '-' + mm + '-' + dd;
        
        startDate.value = formattedToday;
        
        // Thiết lập ngày mặc định cho ngày kết thúc (7 ngày sau)
        const nextWeek = new Date(today);
        nextWeek.setDate(nextWeek.getDate() + 7);
        const nyyyy = nextWeek.getFullYear();
        let nmm = nextWeek.getMonth() + 1;
        let ndd = nextWeek.getDate();
        if (ndd < 10) ndd = '0' + ndd;
        if (nmm < 10) nmm = '0' + nmm;
        const formattedNextWeek = nyyyy + '-' + nmm + '-' + ndd;
        
        endDate.value = formattedNextWeek;
        
        endDate.addEventListener('change', function() {
            if (startDate.value && this.value) {
                if (new Date(this.value) < new Date(startDate.value)) {
                    // Toast thông báo thay vì alert
                    const toastContainer = document.createElement('div');
                    toastContainer.style.position = 'fixed';
                    toastContainer.style.top = '10px';
                    toastContainer.style.right = '10px';
                    toastContainer.style.zIndex = '9999';
                    
                    const toast = document.createElement('div');
                    toast.className = 'toast align-items-center text-white bg-danger border-0';
                    toast.setAttribute('role', 'alert');
                    toast.setAttribute('aria-live', 'assertive');
                    toast.setAttribute('aria-atomic', 'true');
                    toast.style.minWidth = '250px';
                    
                    toast.innerHTML = `
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-exclamation-circle me-1"></i> Ngày kết thúc phải sau ngày bắt đầu!
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    `;
                    
                    toastContainer.appendChild(toast);
                    document.body.appendChild(toastContainer);
                    
                    const bsToast = new bootstrap.Toast(toast, {
                        delay: 3000,
                        autohide: true
                    });
                    bsToast.show();
                    
                    // Đặt lại giá trị ngày kết thúc
                    this.value = '';
                }
            }
        });
    });
</script>
@endsection