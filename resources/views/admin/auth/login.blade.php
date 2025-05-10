<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Login Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                              url('https://images.unsplash.com/photo-1497436072909-60f360e1d4b1?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2560&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(76, 81, 191, 0.7) 0%, rgba(102, 126, 234, 0.4) 100%);
            z-index: 1;
        }
        
        .login-container {
            width: 85%;
            max-width: 460px;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(8px);
            perspective: 1000px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transform-style: preserve-3d;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }
        
        .login-card:hover {
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.4);
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 35px;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(rgba(255, 255, 255, 0.2), transparent 65%);
            transform: rotate(30deg);
            pointer-events: none;
        }
        
        .login-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .login-header p {
            font-size: 16px;
            opacity: 0.85;
        }
        
        .login-body {
            padding: 40px;
            background: white;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #4a5568;
            transition: color 0.3s;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i.input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #a0aec0;
            transition: all 0.3s;
        }
        
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #a0aec0;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        .form-control {
            width: 100%;
            padding: 16px 16px 16px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: all 0.3s;
            background-color: #f8fafc;
            font-family: 'Montserrat', sans-serif;
        }
        
        .form-control.password {
            padding-right: 45px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }
        
        .form-control:focus + i.input-icon {
            color: #667eea;
        }
        
        .form-group:focus-within label {
            color: #667eea;
        }
        
        .error-message {
            color: #e53e3e;
            font-size: 13px;
            margin-top: 6px;
            display: flex;
            align-items: center;
        }
        
        .error-message i {
            margin-right: 6px;
            font-size: 14px;
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .custom-checkbox {
            display: none;
        }
        
        .checkbox-label {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
            font-size: 14px;
            color: #4a5568;
            user-select: none;
        }
        
        .checkbox-label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            transition: all 0.3s;
            background-color: #f8fafc;
        }
        
        .custom-checkbox:checked + .checkbox-label::before {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .custom-checkbox:checked + .checkbox-label::after {
            content: '✓';
            position: absolute;
            left: 6px;
            top: 40%;
            transform: translateY(-50%);
            color: white;
            font-size: 13px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px 30px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            letter-spacing: 0.5px;
            font-family: 'Montserrat', sans-serif;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #a0aec0;
            font-size: 14px;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #e2e8f0;
        }
        
        .divider::before {
            margin-right: 15px;
        }
        
        .divider::after {
            margin-left: 15px;
        }
        
        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            transition: all 0.3s;
            cursor: pointer;
            color: #4a5568;
            font-size: 20px;
        }
        
        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .social-btn.google:hover {
            color: #ea4335;
            border-color: #ea4335;
        }
        
        .social-btn.facebook:hover {
            color: #3b5998;
            border-color: #3b5998;
        }
        
        .social-btn.twitter:hover {
            color: #1da1f2;
            border-color: #1da1f2;
        }
        
        .forgot-password {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .forgot-password:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 40px;
            font-size: 13px;
            color: #718096;
        }
        
        @media (max-width: 576px) {
            .login-container {
                width: 95%;
            }
            
            .login-header {
                padding: 25px;
            }
            
            .login-body {
                padding: 25px;
            }
            
            .form-footer {
                flex-direction: column;
                gap: 20px;
            }
            
            .btn-login {
                width: 100%;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-container {
            animation: fadeIn 0.8s ease-out;
        }
    </style>
</head>
<body>
    <div class="background-overlay"></div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Sign in to continue your journey</p>
            </div>

            <div class="login-body">
                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-with-icon">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <input type="password" class="form-control password" id="password" name="password" placeholder="Enter your password">
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-footer">
                        <div class="remember-me">
                            <input type="checkbox" name="remember" id="remember" class="custom-checkbox">
                            <label for="remember" class="checkbox-label">Remember me</label>
                        </div>
                        <button type="submit" class="btn-login">Sign In</button>
                    </div>
                    
                    <a href="#" class="forgot-password">Forgot your password?</a>
                    
                    <div class="divider">or continue with</div>
                    
                    <div class="social-login">
                        <div class="social-btn google">
                            <i class="fab fa-google"></i>
                        </div>
                        <div class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                        </div>
                        <div class="social-btn twitter">
                            <i class="fab fa-twitter"></i>
                        </div>
                    </div>
                </form>
                
                <div class="login-footer">
                    &copy; 2025 Your Company. All rights reserved.
                </div>
            </div>
        </div>
    </div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Chức năng ẩn/hiện mật khẩu
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');
            
            togglePassword.addEventListener('click', function () {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text'; // Hiện mật khẩu
                    this.classList.remove('fa-eye'); 
                    this.classList.add('fa-eye-slash'); // Đổi icon thành "ẩn mật khẩu"
                } else {
                    passwordField.type = 'password'; // Ẩn mật khẩu
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye'); // Đổi icon thành "hiện mật khẩu"
                }
                // Thêm hiệu ứng phóng to khi click
                this.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-50%)'; // Đưa icon về vị trí ban đầu
                }, 200);
            });
        });
    </script>
</body>
</html>