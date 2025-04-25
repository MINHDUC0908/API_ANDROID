<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin liên hệ</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" style="padding: 20px; background-color: #f4f4f4;">
        <tr>
            <td align="center">
                <table width="600" style="background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="background-color: #FFD700; padding: 20px; text-align: center;">
                            <img src="https://via.placeholder.com/150x50?text=Logo" alt="Logo" style="max-width: 150px;">
                            <h1 style="color: #000; font-size: 24px; margin: 10px 0;">Thông tin liên hệ mới</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="color: #333;">Thông tin từ khách hàng</h2>
                            <p style="color: #555;">Bạn vừa nhận được một tin nhắn liên hệ từ khách hàng:</p>
                            <table width="100%" style="background-color: #f9f9f9; border-radius: 4px; margin: 20px 0; padding: 10px;">
                                <tr>
                                    <td><strong>Họ và tên:</strong></td>
                                    <td>{{ $name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Số điện thoại:</strong></td>
                                    <td>{{ $phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tin nhắn:</strong></td>
                                    <td>{{ $messageContent }}</td>
                                </tr>
                            </table>
                            <p style="color: #555;">Vui lòng liên hệ lại với khách hàng để hỗ trợ kịp thời.</p>
                            <p>
                                <a href="tel:{{ $phone }}" style="background-color: #FFD700; padding: 12px 20px; color: #000; text-decoration: none; border-radius: 4px; font-weight: bold;">Gọi ngay</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #333; color: #fff; text-align: center; padding: 20px;">
                            <p>Liên hệ: <a href="mailto:support@yourwebsite.com" style="color: #FFD700;">support@yourwebsite.com</a> | Hotline: 0123 456 789</p>
                            <p>© 2025 Your Company. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
