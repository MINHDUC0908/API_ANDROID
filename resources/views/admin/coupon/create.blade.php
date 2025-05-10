@extends('admin.layouts.app')  

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                <!-- Header Card với gradient và hiệu ứng -->
                <div class="card-header bg-gradient-primary text-white py-2 position-relative">
                    <div class="d-flex align-items-center fw-bold text-primary mb-1">
                        <div class="position-absolute start-0 top-0 bottom-0 w-1 bg-warning"></div>
                        <i class="fas fa-ticket-alt ms-3 me-2"></i>
                        <h5 class="card-title mb-0 fw-bold">Thêm mã giảm giá mới</h5>
                    </div>
                    <div class="position-absolute top-0 end-0 p-2 opacity-50">
                        <i class="fas fa-tags fa-lg text-white-50"></i>
                    </div>
                </div>
                
                <!-- Body Card với shadow nội bộ và spacing tốt hơn -->
                <div class="card-body p-3 bg-light">
                    <form action="{{ route('coupon.store') }}" method="POST">
                        @csrf
                        
                        <div class="row gy-3">
                            <!-- Mã giảm giá được làm nổi bật -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="form-label fw-bold text-primary mb-1 d-flex align-items-center small">
                                        <span class="badge bg-primary text-white rounded-circle me-2">1</span>
                                        <i class="fas fa-barcode me-1"></i> Mã giảm giá
                                    </label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-key text-primary small"></i></span>
                                        <input type="text" 
                                            class="form-control border-start-0 bg-white @error('code') is-invalid @enderror" 
                                            id="code" 
                                            name="code" 
                                            placeholder="Nhập mã giảm giá (VD: SUMMER2025)"
                                            value="{{ old('code') }}"
                                            required>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" title="Tạo mã ngẫu nhiên">
                                            <i class="fas fa-random"></i>
                                        </button>
                                    </div>
                                    @error('code')
                                        <div class="alert alert-danger mt-1 py-1 px-2 small">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Số tiền giảm với màu sắc highlight -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_amount" class="form-label fw-bold text-success mb-1 d-flex align-items-center small">
                                        <span class="badge bg-success text-white rounded-circle me-2">2</span>
                                        <i class="fas fa-percentage me-1"></i> Số tiền giảm
                                    </label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-dollar-sign text-success small"></i></span>
                                        <input type="number" 
                                            step="0.01" 
                                            class="form-control border-start-0 bg-white @error('discount_amount') is-invalid @enderror" 
                                            id="discount_amount" 
                                            name="discount_amount" 
                                            placeholder="Nhập số tiền giảm"
                                            value="{{ old('discount_amount') }}"
                                            required>
                                        <select class="form-select border-start-0 bg-white small" style="max-width: 100px">
                                            <option value="fixed">VNĐ</option>
                                            <option value="percent">%</option>
                                        </select>
                                    </div>
                                    @error('discount_amount')
                                        <div class="alert alert-danger mt-1 py-1 px-2 small">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Ngày hết hạn với hiệu ứng đẹp -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expires_at" class="form-label fw-bold text-danger mb-1 d-flex align-items-center small">
                                        <span class="badge bg-danger text-white rounded-circle me-2">3</span>
                                        <i class="fas fa-calendar-alt me-1"></i> Ngày hết hạn
                                    </label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-clock text-danger small"></i></span>
                                        <input type="datetime-local" 
                                            class="form-control border-start-0 bg-white @error('expires_at') is-invalid @enderror" 
                                            id="expires_at" 
                                            name="expires_at"
                                            value="{{ old('expires_at') }}"
                                            required>
                                    </div>
                                    @error('expires_at')
                                        <div class="alert alert-danger mt-1 py-1 px-2 small">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity" class="form-label fw-bold text-danger mb-1 d-flex align-items-center small">
                                        <span class="badge bg-danger text-white rounded-circle me-2">4</span>
                                        <i class="fas fa-ticket-alt me-1"></i> Số lượng mã giảm giá
                                    </label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-tags text-danger small"></i></span>
                                        <input type="number"
                                             class="form-control border-start-0 bg-white @error('quantity') is-invalid @enderror"
                                             id="quantity"
                                             name="quantity"
                                            placeholder="Nhập số lượng mã giảm giá"
                                            value="{{ old('quantity') }}"
                                            required>
                                    </div>
                                    @error('quantity')
                                        <div class="alert alert-danger mt-1 py-1 px-2 small">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nút hành động nhỏ hơn -->
                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-1 shadow me-2" style="min-width: 160px;">
                                <i class="fas fa-plus-circle me-1"></i> Thêm mã
                            </button>
                            <a href="{{ route('coupon.index') }}" class="btn btn-outline-secondary px-3 py-1">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Footer thu nhỏ -->
                <div class="card-footer bg-white py-2 d-flex justify-content-between align-items-center border-top">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-1"></i>
                        <span class="text-muted small">Điền đầy đủ thông tin để tiếp tục</span>
                    </div>
                    <div class="progress" style="width: 120px; height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Panel ghi chú bên phải -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-lg overflow-hidden h-100">
                <div class="card-header bg-gradient-info text-white">
                    <div class="d-flex align-items-center fw-bold text-primary mb-1">
                        <div class="position-absolute start-0 top-0 bottom-0 w-1 bg-warning"></div>
                        <i class="fas fa-ticket-alt ms-3 me-2"></i>
                        <h5 class="card-title  mb-0 fw-bold">Ghi chú quan trọng</h5>
                    </div>
                </div>
                
                <div class="card-body bg-light">
                    <!-- Danh sách ghi chú -->
                    <div class="list-group list-group-flush">
                        <div class="list-group-item bg-transparent border-0 border-bottom py-3 px-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <span class="badge bg-primary rounded-circle p-2">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Quy tắc đặt mã giảm giá</h6>
                                    <p class="text-muted mb-0 small">Sử dụng chữ in hoa, không dấu và không quá 10 ký tự. Nên dùng các mã có tính gợi nhớ như SUMMER25, TET2025...</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item bg-transparent border-0 border-bottom py-3 px-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <span class="badge bg-success rounded-circle p-2">
                                        <i class="fas fa-percentage"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Mức giảm giá hợp lý</h6>
                                    <p class="text-muted mb-0 small">Nên giới hạn mức giảm không quá 50% và có điều kiện áp dụng rõ ràng. Kiểm tra lại tỷ suất lợi nhuận trước khi áp dụng.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item bg-transparent border-0 py-3 px-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <span class="badge bg-danger rounded-circle p-2">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Lưu ý về thời hạn</h6>
                                    <p class="text-muted mb-0 small">Đặt thời hạn hợp lý và nhớ thông báo cho khách hàng trước 24h khi mã sắp hết hạn. Tránh đặt thời hạn quá ngắn.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Thống kê nhanh -->
                    <div class="alert alert-info mt-4">
                        <h6 class="fw-bold"><i class="fas fa-chart-pie me-2"></i>Thống kê mã giảm giá</h6>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span>Đang hoạt động:</span>
                            <span class="badge bg-success">12 mã</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span>Sắp hết hạn (7 ngày):</span>
                            <span class="badge bg-warning text-dark">3 mã</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span>Đã sử dụng tháng này:</span>
                            <span class="badge bg-info">256 lần</span>
                        </div>
                    </div>
                </div>
                
                <!-- Footer với action bổ sung -->
                <div class="card-footer bg-white text-center py-3">
                    <a href="#" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-book me-1"></i> Xem hướng dẫn
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-history me-1"></i> Lịch sử mã
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection