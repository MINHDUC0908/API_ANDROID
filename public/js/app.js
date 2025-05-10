document.addEventListener('DOMContentLoaded', function() {
    const statusAlert = document.getElementById('status-alert');
    const progressBar = document.getElementById('alert-progress-bar');
    
    if (statusAlert) {
        // Hiệu ứng thanh tiến trình giảm dần
        let width = 100;
        const interval = 50; // Cập nhật mỗi 50ms
        const totalTime = 3000; // 3 giây
        const steps = totalTime / interval;
        const decrement = 100 / steps;
        
        const timer = setInterval(function() {
            width -= decrement;
            progressBar.style.width = width + '%';
            
            if (width <= 0) {
                clearInterval(timer);
            }
        }, interval);
        
        // Bắt đầu hiệu ứng mờ dần sau 2.5 giây
        setTimeout(function() {
            statusAlert.classList.add('fading');
        }, 2500);
        
        // Đóng alert sau 3 giây
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(statusAlert);
            bsAlert.close();
        }, 3000);
        
        // Nhấp vào alert để tạm dừng đếm ngược
        statusAlert.addEventListener('mouseenter', function() {
            clearInterval(timer);
        });
        
        statusAlert.addEventListener('mouseleave', function() {
            // Tính thời gian còn lại dựa trên chiều rộng thanh tiến trình
            const remainingPercentage = parseFloat(progressBar.style.width);
            const remainingTime = (remainingPercentage / 100) * totalTime;
            
            // Khởi động lại bộ đếm với thời gian còn lại
            width = remainingPercentage;
            const newSteps = remainingTime / interval;
            const newDecrement = remainingPercentage / newSteps;
            
            const newTimer = setInterval(function() {
                width -= newDecrement;
                progressBar.style.width = width + '%';
                
                if (width <= 0) {
                    clearInterval(newTimer);
                }
            }, interval);
        });
    }
});
document.addEventListener("DOMContentLoaded", function () {
    const sidebarLinks = document.querySelectorAll(".sidebarMenu .nav-link");

    sidebarLinks.forEach(function (link) {
        link.addEventListener("click", function (e) {
            // Loại bỏ class 'selected' khỏi tất cả các mục
            document.querySelectorAll(".sidebarMenu .nav-item").forEach(item => {
                item.classList.remove("selected");
            });

            // Thêm class 'selected' vào mục cha của link được click
            this.parentElement.classList.add("selected");

            // Chuyển hướng đến trang tương ứng
            window.location.href = this.href;
        });
    });

    // Giữ lại trạng thái 'selected' khi tải lại trang
    const currentPath = window.location.pathname;
    sidebarLinks.forEach(function (link) {
        if (link.pathname === currentPath) {
            link.parentElement.classList.add("selected");
        }
    });
});
