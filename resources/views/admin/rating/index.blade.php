@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
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
    <div class="card shadow border-0 rounded-3 overflow-hidden">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 d-flex align-items-center text-primary">
                <i class="bi bi-star-fill text-primary me-2 fs-4"></i>
                Quản Lý Đánh Giá
            </h5>            
            <span class="badge bg-white text-primary">{{ count($ratings) }} đánh giá</span>
        </div>
        
        <!-- Thanh tìm kiếm và lọc -->
        <div class="card-body bg-light py-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" placeholder="Tìm theo tên khách hàng hoặc sản phẩm..." onkeyup="filterTable()">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="ratingFilter" class="form-select">
                        <option selected value="default">Lọc theo đánh giá</option>
                        <option value="5">5 sao</option>
                        <option value="4">4 sao</option>
                        <option value="3">3 sao</option>
                        <option value="2">2 sao</option>
                        <option value="1">1 sao</option>
                    </select>
                </div>                
                <div class="col-md-3">
                    <select class="form-select" id="filterSelect">
                        <option selected value="default">Lọc theo trạng thái</option>
                        <option value="haveImage">Có hình ảnh</option>
                        <option value="noImage">Không có hình ảnh</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary w-100" id="btnFilter">
                        <i class="bi bi-funnel-fill me-1"></i> Lọc
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="ratingTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Người Dùng</th>
                            <th class="py-3">Sản Phẩm</th>
                            <th class="py-3 text-center">Đánh Giá</th>
                            <th class="py-3 text-center">Hình Ảnh</th>
                            <th class="py-3">Bình Luận</th>
                            <th class="py-3 text-center">Ngày Đánh Giá</th>
                            <th class="py-3 text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody id="ratingTableBody">
                        @foreach ($ratings as $key => $rating)
                            <tr class="{{ $key % 2 == 0 ? 'bg-white' : 'bg-light-subtle' }}" id="rating-row-{{ $rating->id }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative">
                                            <img src="{{ asset('imageUser/' . $rating->user->image) }}" 
                                                alt="Avatar" 
                                                class="rounded-circle border  border-primary shadow-sm"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-white" 
                                                style="width: 12px; height: 12px;"></span>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 fw-semibold">{{ $rating->user->name }}</h6>
                                            <small class="text-muted">ID: #{{ $rating->user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(isset($rating->product->images[0]))
                                            <img src="{{ asset($rating->product->images[0]->image_path) }}"
                                                alt="Ảnh sản phẩm"
                                                style="width: 60px; height: 60px; object-fit: cover;" class="rounded shadow-sm">
                                        @else
                                            <span class="badge bg-light text-secondary">
                                                <i class="fas fa-image-slash me-1"></i>Không có ảnh {{ $rating->product->images[0]}}
                                            </span>
                                        @endif
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-truncate" style="max-width: 150px;">{{ $rating->product->product_name }}</h6>
                                            <small class="text-muted">SKU: {{ $rating->product->sku ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $rating->rating ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }} mx-1"></i>
                                        @endfor
                                    </div>
                                    <span class="badge bg-{{ $rating->rating >= 4 ? 'success' : ($rating->rating >= 3 ? 'warning' : 'danger') }} mt-1">
                                        {{ $rating->rating }}/5
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($rating->image)
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal{{ $rating->id }}">
                                            <img src="{{ asset($rating->image) }}" 
                                                alt="Hình ảnh đánh giá" 
                                                class="rounded shadow-sm border"
                                                style="width: 60px; height: 60px; object-fit: cover; transition: transform 0.2s;">
                                        </a>
                                    @else
                                        <span class="badge bg-secondary py-2 px-3">
                                            <i class="bi bi-camera-slash me-1"></i> Không có ảnh
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-chat-quote text-primary"></i>
                                        </div>
                                        <span class="d-inline-block" style="max-width: 200px;">
                                            @if (strlen($rating->comment) > 50)
                                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $rating->comment }}">
                                                    {{ substr($rating->comment, 0, 50) }}...
                                                </span>
                                            @else
                                                {{ $rating->comment }}
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge bg-light text-dark mb-1">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ $rating->created_at->format('d-m-Y') }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $rating->created_at->format('H:i') }}
                                        </small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $rating->id }}">
                                            <i class="bi bi-eye me-1"></i> Xem
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteRatingModal-{{ $rating->id }}"
                                        >
                                            <i class="bi bi-trash me-1"></i> Xóa
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Xem Chi Tiết --}}
@foreach ($ratings as $rating)
    <div class="modal fade" id="viewModal{{ $rating->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $rating->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewModalLabel{{ $rating->id }}">Chi Tiết Đánh Giá</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <img src="{{ asset('imageUser/' . $rating->user->image) }}" 
                                    alt="Avatar" 
                                    class="rounded-circle border border-primary shadow-sm mb-3"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                                <h5 class="mb-0 fw-bold">{{ $rating->user->name }}</h5>
                                <p class="text-muted">Khách hàng</p>
                                
                                <div class="d-flex justify-content-center mt-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $rating->rating ? 'bi-star-fill text-warning' : 'bi-star text-secondary' }} fs-4 mx-1"></i>
                                    @endfor
                                </div>
                                <span class="badge bg-{{ $rating->rating >= 4 ? 'success' : ($rating->rating >= 3 ? 'warning' : 'danger') }} mt-2 fs-6 px-3 py-2">
                                    {{ $rating->rating }}/5
                                </span>
                                
                                <div class="mt-4">
                                    <p class="mb-1 text-muted small">Đánh giá vào lúc:</p>
                                    <p class="mb-0 fw-medium">{{ $rating->created_at->format('d-m-Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($rating->product->images[0]->image_path) }}" 
                                            alt="Product"
                                            class="rounded shadow-sm border me-3"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $rating->product->product_name }}</h6>
                                            <small class="text-muted">SKU: {{ $rating->product->sku ?? 'N/A' }} | ID: #{{ $rating->product->id }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Nội dung đánh giá:</h6>
                                    <div class="bg-light rounded p-3 mb-4">
                                        <p class="mb-0">{{ $rating->comment }}</p>
                                    </div>
                                    
                                    @if ($rating->image)
                                    <div>
                                        <h6 class="fw-bold mb-3">Hình ảnh đánh giá:</h6>
                                        <img src="{{ asset($rating->image) }}" 
                                            alt="Hình ảnh đánh giá" 
                                            class="img-fluid rounded shadow-sm border"
                                            style="max-height: 200px; object-fit: contain;">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-danger" >
                        <i class="bi bi-trash me-1"></i> Xóa Đánh Giá
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if ($rating->image)
        {{-- Modal Hiển thị Hình Ảnh --}}
        <div class="modal fade" id="imageModal{{ $rating->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $rating->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="imageModalLabel{{ $rating->id }}">Hình Ảnh Đánh Giá</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <img src="{{ asset($rating->image) }}" 
                            alt="Hình ảnh đánh giá" 
                            class="img-fluid"
                            style="max-height: 80vh; width: 100%; object-fit: contain;">
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <a href="{{ asset($rating->image) }}" download class="btn btn-primary">
                            <i class="bi bi-download me-1"></i> Tải Xuống
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@foreach ($ratings as $rating)
    {{-- Modal Xác nhận Xóa --}}
    <div class="modal fade" id="deleteRatingModal-{{$rating->id}}" tabindex="-1" aria-labelledby="deleteRatingModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div class="mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-1"></i>
                    </div>
                    <h4 class="mb-3">Bạn chắc chắn muốn xóa?</h4>
                    <p class="text-muted mb-0">Hành động này không thể hoàn tác. Đánh giá sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                    </button>
                    <button type="submit" class="btn btn-danger px-4 deleteRatingButton"
                    data-id="{{ $rating->id }}">
                    <i class="bi bi-trash me-2"></i>Xác nhận xóa
                </button>
                
                </div>
            </div>
        </div>
    </div>
@endforeach
<script src="{{asset("js/rating.js")}}"></script>
<script>
    
</script>
@endsection