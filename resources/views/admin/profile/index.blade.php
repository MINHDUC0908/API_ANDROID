@extends('admin.layouts.app')
@section('header')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection
@section('content')
<div class="py-4 px-4">
    <div class="row justify-content-center">
        <div class="">
            <!-- Thẻ hồ sơ -->
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <!-- Tiêu đề hồ sơ -->
                <div class="py-5 px-4" style="background: linear-gradient(to right, rgba(13, 110, 253, 0.2), rgba(13, 110, 253, 0.5));">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1 text-black font-bold">Thông tin tài khoản</h4>
                            <p class="mb-0 text-gray-800 font-medium">Quản lý thông tin cá nhân và bảo mật tài khoản</p>
                        </div>
                        {{-- <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="" class="btn btn-outline-dark">
                                <i class="fas fa-tachometer-alt me-1"></i> Quay lại dashboard
                            </a>
                        </div> --}}
                    </div>
                </div>                

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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            

                <!-- Điều hướng Tab -->
                <ul class="nav nav-tabs nav-fill" id="profileTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-3" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab" aria-selected="true">
                            <i class="fas fa-user me-2"></i>Thông tin cá nhân
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3" id="avatar-tab" data-bs-toggle="tab" data-bs-target="#avatar-tab-pane" type="button" role="tab" aria-selected="false">
                            <i class="fas fa-image me-2"></i>Ảnh đại diện
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-tab-pane" type="button" role="tab" aria-selected="false">
                            <i class="fas fa-lock me-2"></i>Đổi mật khẩu
                        </button>
                    </li>
                </ul>
                <!-- Modal hiển thị ảnh lớn -->
                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-transparent border-0">
                            <div class="modal-body p-0">
                                <img src="{{ asset('imageUser/' . $user->image) }}"  id="modalImage" class="img-fluid rounded shadow" alt="Profile Image">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Nội dung Tab -->
                <div class="tab-content p-4" id="profileTabContent">
                    <!-- Tab Thông tin cá nhân -->
                    <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                        <div class="row">
                            <!-- Ảnh hồ sơ hiện tại -->
                            <div class="col-md-4 text-center mb-4">
                                <div class="position-relative mx-auto mb-3" style="width: 150px; height: 150px;">
                                    @if($user->image)
                                        <img src="{{ asset('imageUser/' . $user->image) }}" 
                                            alt="{{ $user->name }}" 
                                            class="img-thumbnail rounded-circle w-100 h-100 object-fit-cover border-3"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#imageModal">
                                    @else
                                        <div class="bg-light rounded-circle w-100 h-100 d-flex align-items-center justify-content-center border">
                                            <i class="fas fa-user fa-4x text-secondary"></i>
                                        </div>
                                    @endif
                                </div>
                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-calendar-alt me-1"></i> Tham gia: {{ $user->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            

                            <!-- Cập nhật biểu mẫu -->
                            <div class="col-md-8">
                                <form action="{{ route('profile.update', ['id' => $user->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input readonly  type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                            @if($user->email_verified_at)
                                                <span class="input-group-text bg-success-subtle text-success">
                                                    <i class="fas fa-check-circle me-1"></i> Đã xác thực
                                                </span>
                                            @else
                                                <span class="input-group-text bg-warning-subtle text-warning">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> Chưa xác thực
                                                </span>
                                            @endif
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                        </div>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Giới tính</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                                @if(empty($user->gender))
                                                    <option value="">Vui lòng chọn</option>
                                                @endif
                                                <option value="Nam" {{ old('gender', $user->gender) == 'Nam' ? 'selected' : '' }}>Nam</option>
                                                <option value="Nữ" {{ old('gender', $user->gender) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                            </select>                                            
                                        </div>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="address" class="form-label">Địa chỉ</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Cập nhật thông tin
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab Ảnh Hồ Sơ -->
                    <div class="tab-pane fade" id="avatar-tab-pane" role="tabpanel" aria-labelledby="avatar-tab" tabindex="0">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="text-center mb-4">
                                    <h5 class="mb-3">Ảnh đại diện hiện tại</h5>
                                    <div class="position-relative d-inline-block mb-3">
                                        @if($user->image)
                                            <img src="{{ asset('imageUser/' . $user->image) }}" alt="{{ $user->name }}" 
                                                class="img-thumbnail rounded-circle" style="width: 180px; height: 180px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center border" style="width: 180px; height: 180px;">
                                                <i class="fas fa-user fa-5x text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            
                                <form action="{{route('profile.image', ["id" => $user->id])}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="card bg-light border-0 mb-4">
                                        <div class="card-body">
                                            <div class="text-center py-4">
                                                <div class="mb-3">
                                                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                                    <h6>Chọn ảnh để tải lên</h6>
                                                    <p class="text-muted small">Hỗ trợ JPG, JPEG, PNG. Tối đa 2MB.</p>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="image-upload" class="btn btn-outline-primary">
                                                        <i class="fas fa-folder-open me-1"></i> Chọn ảnh
                                                    </label>
                                                    <input type="file" id="image-upload" name="image" class="d-none" accept="image/*">
                                                    <div id="file-name" class="mt-2 text-muted small"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="d-grid">
                                                @if($user->image)
                                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteImageModal">
                                                        <i class="fas fa-trash-alt me-1"></i> Xóa ảnh
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-outline-danger" disabled>
                                                        <i class="fas fa-trash-alt me-1"></i> Xóa ảnh
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-upload me-1"></i> Tải lên
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                
                                <div class="alert alert-info d-flex" role="alert">
                                    <div class="flex-shrink-0 me-2">
                                        <i class="fas fa-info-circle mt-1"></i>
                                    </div>
                                    <div>
                                        Ảnh đại diện sẽ được hiển thị trên hồ sơ của bạn. Ảnh đẹp sẽ giúp người dùng dễ dàng nhận diện bạn.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab Mật khẩu -->
                    <div class="tab-pane fade" id="password-tab-pane" role="tabpanel" aria-labelledby="password-tab" tabindex="0">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <h5 class="mb-4">Thay đổi mật khẩu</h5>
                                
                                <form action="{{route("profile.updatePassword", ['id' => $user->id])}}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="password-strength mb-4">
                                        <p class="mb-2">Độ mạnh mật khẩu:</p>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-danger" role="progressbar" id="password-strength-bar" style="width: 0%"></div>
                                        </div>
                                        <ul class="password-requirements mt-3">
                                            <li id="length-check" class="text-muted"><i class="far fa-circle me-1"></i> Ít nhất 8 ký tự</li>
                                            <li id="uppercase-check" class="text-muted"><i class="far fa-circle me-1"></i> Ít nhất 1 chữ in hoa</li>
                                            <li id="lowercase-check" class="text-muted"><i class="far fa-circle me-1"></i> Ít nhất 1 chữ thường</li>
                                            <li id="number-check" class="text-muted"><i class="far fa-circle me-1"></i> Ít nhất 1 số</li>
                                            <li id="special-check" class="text-muted"><i class="far fa-circle me-1"></i> Ít nhất 1 ký tự đặc biệt</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary" id="change-password-btn" disabled>
                                            <i class="fas fa-save me-1"></i> Đổi mật khẩu
                                        </button>
                                    </div>
                                </form>
                                
                                <div class="alert alert-warning d-flex mt-4" role="alert">
                                    <div class="flex-shrink-0 me-2">
                                        <i class="fas fa-exclamation-triangle mt-1"></i>
                                    </div>
                                    <div>
                                        Sau khi thay đổi mật khẩu thành công, bạn sẽ được đăng xuất và cần đăng nhập lại bằng mật khẩu mới.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Xóa hộp thoại xác nhận hình ảnh -->
<div class="modal fade" id="deleteImageModal" tabindex="-1" aria-labelledby="deleteImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteImageModalLabel">Xác nhận xóa ảnh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa ảnh đại diện hiện tại không?</p>
                <p class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="{{route("profile.deleteImage", ["id" => $user->id])}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa ảnh</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection