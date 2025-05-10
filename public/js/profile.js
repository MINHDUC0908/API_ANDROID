document.addEventListener('DOMContentLoaded', function() {
    // Bật/tắt chế độ hiển thị mật khẩu
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Hiển thị tên tệp khi được chọn
    const fileInput = document.getElementById('image-upload');
    const fileNameDisplay = document.getElementById('file-name');
    
    if (fileInput && fileNameDisplay) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileNameDisplay.textContent = this.files[0].name;
                
                // Đọc và hiển thị preview ảnh
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImages = document.querySelectorAll('.img-thumbnail');
                    previewImages.forEach(img => {
                        img.src = e.target.result;
                    });
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                fileNameDisplay.textContent = '';
            }
        });
    }
    
    // Kiểm tra độ mạnh của mật khẩu
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('password-strength-bar');
    const lengthCheck = document.getElementById('length-check');
    const uppercaseCheck = document.getElementById('uppercase-check');
    const lowercaseCheck = document.getElementById('lowercase-check');
    const numberCheck = document.getElementById('number-check');
    const specialCheck = document.getElementById('special-check');
    const changePasswordBtn = document.getElementById('change-password-btn');
    
    if (passwordInput && strengthBar) {
        passwordInput.addEventListener('input', checkPasswordStrength);
        if (confirmInput) {
            confirmInput.addEventListener('input', validateForm);
        }
    }
    
    function checkPasswordStrength() {
        const password = passwordInput.value;
        let strength = 0;
        let hasLength = false;
        let hasUpper = false;
        let hasLower = false;
        let hasNumber = false;
        let hasSpecial = false;
        
        // Check length
        if (password.length >= 8) {
            strength += 20;
            hasLength = true;
            updateCheckItem(lengthCheck, true);
        } else {
            updateCheckItem(lengthCheck, false);
        }
        
        // Check uppercase letters
        if (/[A-Z]/.test(password)) {
            strength += 20;
            hasUpper = true;
            updateCheckItem(uppercaseCheck, true);
        } else {
            updateCheckItem(uppercaseCheck, false);
        }
        
        // Check lowercase letters
        if (/[a-z]/.test(password)) {
            strength += 20;
            hasLower = true;
            updateCheckItem(lowercaseCheck, true);
        } else {
            updateCheckItem(lowercaseCheck, false);
        }
        
        // Check numbers
        if (/[0-9]/.test(password)) {
            strength += 20;
            hasNumber = true;
            updateCheckItem(numberCheck, true);
        } else {
            updateCheckItem(numberCheck, false);
        }
        
        // Check special characters
        if (/[^A-Za-z0-9]/.test(password)) {
            strength += 20;
            hasSpecial = true;
            updateCheckItem(specialCheck, true);
        } else {
            updateCheckItem(specialCheck, false);
        }
        
        // Update strength bar
        strengthBar.style.width = strength + '%';
        
        // Update color based on strength
        if (strength < 40) {
            strengthBar.className = 'progress-bar bg-danger';
        } else if (strength < 70) {
            strengthBar.className = 'progress-bar bg-warning';
        } else {
            strengthBar.className = 'progress-bar bg-success';
        }
        
        validateForm();
    }
    
    function updateCheckItem(element, isValid) {
        if (isValid) {
            element.classList.remove('text-muted');
            element.classList.add('valid');
            element.querySelector('i').className = 'fas fa-check-circle me-1';
        } else {
            element.classList.remove('valid');
            element.classList.add('text-muted');
            element.querySelector('i').className = 'far fa-circle me-1';
        }
    }
    
    function validateForm() {
        const password = passwordInput.value;
        const confirmed = confirmInput.value;
        const currentPassword = document.getElementById('current_password').value;
        
        // Enable button only if all conditions are met
        if (
            password.length >= 8 &&
            /[A-Z]/.test(password) &&
            /[a-z]/.test(password) &&
            /[0-9]/.test(password) &&
            /[^A-Za-z0-9]/.test(password) &&
            password === confirmed &&
            currentPassword.length > 0
        ) {
            changePasswordBtn.disabled = false;
        } else {
            changePasswordBtn.disabled = true;
        }
    }
    
    // Ensure tab navigation works with URL hash
    const triggerTabList = document.querySelectorAll('#profileTab button');
    if (window.location.hash) {
        const targetTab = document.querySelector(`button[data-bs-target="${window.location.hash}"]`);
        if (targetTab) {
            targetTab.click();
        }
    }
    
    triggerTabList.forEach(triggerEl => {
        triggerEl.addEventListener('click', function() {
            const tabTarget = this.getAttribute('data-bs-target');
            history.pushState(null, null, tabTarget);
        });
    });
});