<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DUC COMPUTER</title>
    <link rel="icon" href="{{ asset('icon/image.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ secure_asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('js/app.js') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/status.css') }}">
    @yield('header')
    @yield('style')
</head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="content w-100" id="main-content">
            <!-- Navbar -->
            @include('admin.partials.navbar')

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="{{ asset('js/profile.js') }}" ></script>
    <script src="{{ asset('js/chat.js') }}" ></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//cdn.ckeditor.com/4.21.0/full/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('parameter');
        CKEDITOR.replace('description');
        CKEDITOR.replace('outstanding');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
              "stateSave": true, // Kích hoạt lưu trạng thái
              "pageLength": 20, // Số lượng mặc định mỗi trang là 5
              "lengthMenu": [ [20, 40, 80, 100], [20, 40, 80, 100] ],
                "language": {
                    "lengthMenu": "Hiển thị _MENU_ mục mỗi trang", // Thay đổi tiêu đề "Entries per page"
                    "search": "Tìm kiếm:", // Tùy chỉnh "Search"
                    "searchPlaceholder": "Tìm kiếm danh mục...", // Tùy chỉnh văn bản placeholder trong ô tìm kiếm
                    "zeroRecords": "Không tìm thấy kết quả",
                    // "info": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ mục",
                }
            });
        });
    </script>
        <script>
            $(document).ready(function() {
                $('#myTable1').DataTable({
                  "stateSave": true, // Kích hoạt lưu trạng thái
                    "pageLength": 20, // Số lượng mặc định mỗi trang là 5
                    "lengthMenu": [ [20, 40, 80, 100], [20, 40, 80, 100] ],
                    "language": {
                        "lengthMenu": "Hiển thị _MENU_ mục mỗi trang", // Thay đổi tiêu đề "Entries per page"
                        "search": "Tìm kiếm:", // Tùy chỉnh "Search"
                        "searchPlaceholder": "Tìm kiếm đơn hàng...", // Tùy chỉnh văn bản placeholder trong ô tìm kiếm
                        "zeroRecords": "Không tìm thấy kết quả",
                        // "info": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#myTable2').DataTable({
                  "stateSave": true, // Kích hoạt lưu trạng thái
                  "pageLength": 20, // Số lượng mặc định mỗi trang là 5
                  "lengthMenu": [ [20, 40, 80, 100], [20, 40, 80, 100] ],
                    "language": {
                        "lengthMenu": "Hiển thị _MENU_ mục mỗi trang", // Thay đổi tiêu đề "Entries per page"
                        "search": "Tìm kiếm:", // Tùy chỉnh "Search"
                        "searchPlaceholder": "Tìm kiếm...", // Tùy chỉnh văn bản placeholder trong ô tìm kiếm
                        "zeroRecords": "Không tìm thấy kết quả",
                        // "info": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    }
                });
            });
        </script>
    <script>
        const toggleSidebar = () => {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("main-content");
            const icon = document.getElementById("toggle-icon");
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("collapsed");
        };
    </script>
</body>
</html>