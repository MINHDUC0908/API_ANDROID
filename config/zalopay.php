<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ZaloPay Configuration
    |--------------------------------------------------------------------------
    |
    | Các thông tin cấu hình cho tích hợp cổng thanh toán ZaloPay.
    | Bạn nên cấu hình các giá trị này trong file .env để tiện thay đổi
    | giữa môi trường local, staging và production.
    |
    */

    'app_id' => env('APP_ID', 0),

    'key1' => env('KEY1', ''),

    'key2' => env('KEY2', ''),

    'endpoint' => env('ZALOPAY_ENDPOINT', 'https://sb-openapi.zalopay.vn/v2/create'),
];
