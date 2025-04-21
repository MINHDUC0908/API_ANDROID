<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f9f9f9; color: #333;">
    <table style="width: 100%; max-width: 600px; margin: 20px auto; background-color: #fff; border: 1px solid #ddd; border-collapse: collapse;">
        <tr>
            <td style="padding: 20px; text-align: center; background-color: #f2f2f2; font-size: 24px; font-weight: bold;">
                Cảm ơn bạn đã đặt hàng tại <span style="color: black">DUC</span><span style="color: #FDC407">COMPUTER</span>!
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; padding-bottom: 0;">
                <p style="margin: 0 0 10px;">Mã đơn hàng: <strong>{{ $order->order_number }}</strong></p>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">
                <p style="margin: 0 0 10px;">Địa chỉ giao hàng: <strong>{{ $order->shippingAddress->name }}; </strong><strong>0{{ $order->shippingAddress->phone }}; </strong><strong>{{ $order->shippingAddress->address }}; </strong><strong>{{ $order->shippingAddress->ward }}; </strong><strong>{{ $order->shippingAddress->district }}; </strong><strong>{{ $order->shippingAddress->province }}</strong></p>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <h3 style="margin: 0 0 10px; font-size: 18px; border-bottom: 2px solid #ddd; padding-bottom: 5px;">Thông tin đơn hàng:</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;">Sản phẩm</th>
                            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;">Màu sắc</th>
                            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;">Giá</th>
                            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;">Số lượng</th>
                            <th style="border: 1px solid #ddd; padding: 8px; background-color: #f2f2f2;">Tổng cộng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $item['product']['product_name'] }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">
                                @if(isset($item['colors']['color']) && $item['colors']['color'])
                                    {{ $item['colors']['color'] }}
                                @else
                                    None
                                @endIF
                            </td>                            
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($item['product']['price'], 0, ',', '.') }} VND</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $item['quantity'] }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($item['product']['price'] * $item['quantity'], 0, ',', '.') }} VND</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p style="margin-top: 10px; display: flex; justify-content: end; ">Tổng cộng: <strong>{{ number_format($totalPrice, 0, ',', '.') }} VND</strong></p>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; text-align: center; background-color: #f2f2f2;">
                <p style="margin: 0;">Chúng tôi sẽ liên hệ với bạn để xác nhận thời gian giao hàng.</p>
                <p style="margin: 0;">Trân trọng,</p>
                <span style="color: black">DUC</span><span style="color: #FDC407">COMPUTER</span>
            </td>
        </tr>
    </table>
</body>
</html>

