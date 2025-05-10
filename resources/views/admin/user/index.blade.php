@extends('admin.layouts.app')

@section('content')
<style>
    .table th {
        font-weight: 600;
        color: #4a5568;
        text-align: center;
    }
    .table td {
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
        text-align: center;
    }
    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
        font-size: 0.85rem;
    }
    .btn-sm {
        padding: 0.3rem;
        line-height: 1;
        border-radius: 0.375rem;
        transition: 0.2s ease-in-out;
    }
    .btn-light {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }
    .btn-light:hover {
        background: #e0e0e0;
        border-color: #ccc;
    }
    .alert {
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .card {
        overflow: hidden;
    }
</style>
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded-3 shadow-sm mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">
                <i class="fas fa-users me-2"></i>Quản lý nhân sự
            </h2>
            <p class="text-muted mb-0">Quản lý và cập nhật thông tin nhân sự</p>
        </div>
        <div>
            <a href="{{route("staff.create")}}" class="btn btn-primary btn-md">
                <i class="fas fa-plus-circle me-2"></i>Thêm nhân sự mới
            </a>
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

    <div class="card shadow border-0 rounded-3">
        <div class="card border-0 rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary">
                    <i class="fas fa-list me-2"></i>Danh sách nhân sự
                </h5>
                <!-- Ô tìm kiếm -->
                <div class="search-container">
                    <input type="text" id="searchBrand" class="form-control px-4" 
                        placeholder="🔍 Tìm kiếm nhân sự..." onkeyup="filterTable()">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="searchUser">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="py-3 text-center">ID</th>
                            <th scope="col" class="py-3">Hình ảnh</th>
                            <th scope="col" class="py-3">Tên</th>
                            <th scope="col" class="py-3">Email</th>
                            <th scope="col" class="py-3">Vai trò</th>
                            <th scope="col" class="py-3">Ngày tạo</th>
                            <th scope="col" class="py-3 text-center">Trạng thái</th>
                            <th scope="col" class="py-3 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>                                    
                                <td class="text-center align-middle">{{ $user->id }}</td>
                                <td class="align-middle text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        @if($user->image)
                                            <img src="{{ asset('storage/profile_images/' . $user->image) }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="img-thumbnail rounded-circle"
                                                 style="width: 45px; height: 45px; object-fit: cover;">
                                        @else
                                            <div class="bg-primary text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 45px; height: 45px; border-radius: 50%; border: 5px solid white; box-shadow: 0 0 0 1px #dee2e6;">
                                                <span class="small fw-medium">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle">{{ $user->name }}</td>
                                <td class="align-middle">{{ $user->email }}</td>
                                <td class="align-middle">{{ $user->role }}</td>
                                <td class="align-middle">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="text-center align-middle">
                                    @if($user->staffProfile->status == "active")
                                        <span class="badge bg-success">Đang làm</span>
                                    @else
                                        <span class="badge bg-danger">Bị khóa</span>
                                    @endif
                                </td>                                
                                <td class="align-middle">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- <a href="{{ route('phan-vai-tro', ['id' => $user->id]) }}" class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Gán vai trò">
                                            <i class="fas fa-user-tag text-success"></i>
                                        </a>
                                        <a href="{{ route('phan-quyen', ['id' => $user->id]) }}" class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Gán quyền">
                                            <i class="fas fa-shield-alt text-secondary"></i>
                                        </a> --}}
                                        <a href="{{ route('staff.edit', $user->id) }}" class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>
                                        {{-- <form action="{{ route('user.toggleLock', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Khóa tài khoản">
                                                <i class="fas {{ $user->is_locked ? 'fa-lock text-danger' : 'fa-unlock text-success' }}"></i>
                                            </button> --}}
                                        </form>  
                                        <form action="{{ route('staff.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Xóa nhân sự" onclick="return confirm('Bạn có chắc chắn muốn xóa nhân sự này không?')">
                                                <i class="fas fa-trash text-danger"></i>
                                            </button>
                                        </form>
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
<script>
    function filterTable() {
        let input = document.getElementById("searchBrand").value.toLowerCase();
        let rows = document.querySelectorAll("#searchUser tbody tr");

        rows.forEach(row => {
            let name = row.cells[2].textContent.toLowerCase();
            let email = row.cells[3].textContent.toLowerCase();
            let role = row.cells[4].textContent.toLowerCase();
            if (name.includes(input) || email.includes(input) || role.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection
