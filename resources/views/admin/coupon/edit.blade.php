@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-2 position-relative">
                    <div class="d-flex align-items-center fw-bold text-primary mb-1">
                        <div class="position-absolute start-0 top-0 bottom-0 w-1 bg-warning"></div>
                        <i class="fas fa-ticket-alt ms-3 me-2"></i>
                        <h5 class="card-title mb-0 fw-bold">Chỉnh sửa mã giảm giá</h5>
                    </div>
                    <div class="position-absolute top-0 end-0 p-2 opacity-50">
                        <i class="fas fa-tags fa-lg text-white-50"></i>
                    </div>
                </div>
                
                <div class="card-body p-3 bg-light">
                    <form action="{{ route('coupon.update', $coupon->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Sử dụng phương thức PUT để cập nhật dữ liệu -->
                        
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="form-label fw-bold text-primary mb-1 d-flex align-items-center small">
                                        <span class="badge bg-primary text-white rounded-circle me-2">1</span>
                                        <i class="fas fa-barcode me-1"></i> Mã giảm giá
                                    </label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-key text-primary small"></i>
                                        </span>
                                        <input type="text" 
                                            class="form-control border-start-0 bg-white @error('code') is-invalid @enderror" 
                                            id="code" 
                                            name="code" 
                                            value="{{ old('code', $coupon->code) }}" 
                                            required>
                                    </div>
                                    @error('code')
                                        <div class="alert alert-danger mt-1 py-1 px-2 small">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_amount" class="form-label fw-bold text-success mb-1 d-flex align-items-center small">
                                        <span class="badge bg-success text-white rounded-circle me-2">2</span>
                                        <i class="fas fa-percentage me-1"></i> Số tiền giảm
                                    </label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-dollar-sign text-success small"></i>
                                        </span>
                                        <input type="number" 
                                            step="0.01" 
                                            class="form-control border-start-0 bg-white @error('discount_amount') is-invalid @enderror" 
                                            id="discount_amount" 
                                            name="discount_amount" 
                                            value="{{ old('discount_amount', $coupon->discount_amount) }}" 
                                            required>
                                        <select class="form-select border-start-0 bg-white small" name="discount_type">
                                            <option value="fixed" {{ $coupon->discount_type == 'fixed' ? 'selected' : '' }}>VNĐ</option>
                                            <option value="percent" {{ $coupon->discount_type == 'percent' ? 'selected' : '' }}>%</option>
                                        </select>
                                    </div>
                                    @error('discount_amount')
                                        <div class="alert alert-danger mt-1 py-1 px-2 small">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expires_at" class="form-label fw-bold text-danger mb-1 d-flex align-items-center small">
                                        <span class="badge bg-danger text-white rounded-circle me-2">3</span>
                                        <i class="fas fa-calendar-alt me-1"></i> Ngày hết hạn
                                    </label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-clock text-danger small"></i>
                                        </span>
                                        <input type="datetime-local" 
                                            class="form-control border-start-0 bg-white @error('expires_at') is-invalid @enderror" 
                                            id="expires_at" 
                                            name="expires_at"
                                            value="{{ old('expires_at', date('Y-m-d\TH:i', strtotime($coupon->expires_at))) }}" 
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
                                        <span class="badge bg-danger text-white rounded-circle me-2">3</span>
                                        <i class="fas fa-tags me-1"></i> Số lượng mã giảm giá
                                    </label>
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-hashtag text-danger small"></i>
                                        </span>
                                        <input type="number"
                                             class="form-control border-start-0 bg-white @error('quantity') is-invalid @enderror"
                                             id="quantity"
                                             name="quantity"
                                             placeholder="Nhập số lượng mã giảm giá"
                                             value="{{ old('quantity', $coupon->quantity) }}"
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
                        
                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-1 shadow me-2" style="min-width: 160px;">
                                <i class="fas fa-save me-1"></i> Cập nhật
                            </button>
                            <a href="{{ route('coupon.index') }}" class="btn btn-outline-secondary px-3 py-1">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-white py-2 d-flex justify-content-between align-items-center border-top">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-1"></i>
                        <span class="text-muted small">Chỉnh sửa thông tin mã giảm giá và nhấn "Cập nhật"</span>
                    </div>
                    <div class="progress" style="width: 120px; height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
