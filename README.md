
# API Bán Hàng Điện Tử

## Giới Thiệu

Đây là API bán hàng điện tử được phát triển bằng Laravel. API này cung cấp các chức năng quản lý sản phẩm, danh mục, đơn hàng, và người dùng. Dự án này sử dụng Laravel Sanctum để xác thực API, cung cấp các phương thức đăng ký, đăng nhập và quản lý sản phẩm, cũng như quản lý đơn hàng.

## Chức Năng Chính

- **Xác thực người dùng:** Đăng ký và đăng nhập người dùng sử dụng Laravel Sanctum.
- **Quản lý sản phẩm:** Tạo, cập nhật, xóa và hiển thị thông tin sản phẩm điện tử.
- **Quản lý đơn hàng:** Tạo, theo dõi và quản lý trạng thái đơn hàng.
- **Quản lý danh mục sản phẩm:** Cho phép phân loại sản phẩm theo danh mục (ví dụ: điện thoại, máy tính, v.v.).
- **Hỗ trợ tìm kiếm:** Cung cấp các phương thức tìm kiếm sản phẩm theo tên, loại và giá cả.

## Cài Đặt

### 1. Cài Đặt Dự Án

1. Clone dự án về máy của bạn:

   ```bash
   git clone https://github.com/MINHDUC0908/API_ANDROID
   cd API_ANDROID
   ```

2. Cài đặt các dependencies:

   Sau khi đã clone xong dự án, cài đặt các thư viện PHP yêu cầu cho dự án bằng Composer:

   ```bash
   composer install
   ```

### 2. Cấu Hình Môi Trường

1. Sao chép file `.env.example` thành `.env`:

   ```bash
   cp .env.example .env
   ```

2. Mở file `.env` và cập nhật các thông tin kết nối cơ sở dữ liệu của bạn, ví dụ:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

3. Tạo một key ứng dụng Laravel:

   ```bash
   php artisan key:generate
   ```

4. Cấu hình queue (nếu sử dụng hàng đợi):

   Trong file `.env`, thay đổi cấu hình hàng đợi nếu bạn đang sử dụng cơ sở dữ liệu cho hàng đợi:

   ```env
   QUEUE_CONNECTION=database
   ```

5. Tạo bảng hàng đợi trong cơ sở dữ liệu:

   ```bash
   php artisan queue:table
   php artisan migrate
   ```
### 3. Cài Đặt Cơ Sở Dữ Liệu

### 1. Bảng `categories`

```sql
CREATE TABLE categories (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
);
```

---

### 2. Bảng `brands`

```sql
CREATE TABLE brands (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    brand_name VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    logo_brand TEXT COLLATE utf8mb4_unicode_ci,
    category_id BIGINT(20) UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    view INT(11) DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

---

### 3. Bảng `products`

```sql
CREATE TABLE products (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    product_name VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    description TEXT COLLATE utf8mb4_unicode_ci,
    price DECIMAL(10,2),
    brand_id BIGINT(20) UNSIGNED,
    category_id BIGINT(20) UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    view_count INT(11) DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (brand_id) REFERENCES brands(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```


4. Bảng images
CREATE TABLE images (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    image_path TEXT COLLATE utf8mb4_unicode_ci,
    product_id BIGINT(20) UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    color_id BIGINT(20) UNSIGNED,
    PRIMARY KEY (id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (color_id) REFERENCES colors(id)
) 

5. Bảng colors
CREATE TABLE colors (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    code VARCHAR(50) COLLATE utf8mb4_unicode_ci,
    product_id BIGINT(20) UNSIGNED,
    quantity INT(11) DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) 

6. Bảng carts 
CREATE TABLE carts (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED,
    status VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
) 

7. Bảng cart_items 
CREATE TABLE cart_items (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    cart_id BIGINT(20) UNSIGNED,
    product_id BIGINT(20) UNSIGNED,
    color_id BIGINT(20) UNSIGNED,
    quantity INT(11) DEFAULT 1,
    total DECIMAL(12,2) DEFAULT 0.00,
    checked TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (cart_id) REFERENCES carts(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (color_id) REFERENCES colors(id)
) 


8. Bảng shipping_address
CREATE TABLE shipping_address (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED,
    name VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    phone VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    province VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    district VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    ward VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    address VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
) 

9. Bảng orders
CREATE TABLE orders (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED,
    order_number VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    total_amount DECIMAL(12,2),
    status ENUM('waiting_for_confirmation', 'processing', 'delivered') COLLATE utf8mb4_unicode_ci DEFAULT 'waiting_for_confirmation',
    payment_method VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    payment_status VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT 'unpaid',
    created_at TIMESTAMP NULL,
    shipping_address_id BIGINT(20) UNSIGNED,
    coupon_id BIGINT(20) UNSIGNED,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (shipping_address_id) REFERENCES shipping_address(id),
    FOREIGN KEY (coupon_id) REFERENCES coupon(id)
) 

10. Bảng order_items
CREATE TABLE order_items (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id BIGINT(20) UNSIGNED,
    product_id BIGINT(20) UNSIGNED,
    quantity INT(11),
    color_id BIGINT(20) UNSIGNED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (color_id) REFERENCES colors(id)
) 

11. Bảng payments
CREATE TABLE payments (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id BIGINT(20) UNSIGNED,
    payment_gateway VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    transaction_id VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    amount DECIMAL(12,2),
    status ENUM('pending', 'success', 'failed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (order_id) REFERENCES orders(id)
) 

12. Bảng contacts
CREATE TABLE contacts (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    email VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    message TEXT COLLATE utf8mb4_unicode_ci,
    phone INT(11),
    isReplied INT(11),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
) 

13. Bảng coupons
CREATE TABLE coupons(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(255) COLLATE utf8mb4_unicode_ci,
    discount_amount DECIMAL(10,2),
    express_at DATETIME,
    quantity INT(11),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
)

14. Bảng vouchers
CREATE TABLE vouchers(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    type ENUM('percent', 'fixed', 'freeship') COLLATE utf8mb4_unicode_ci NOT NULL,
    value INT(11) NOT NULL,
    max_discount INT(11) DEFAULT NULL,
    min_order_amount INT(11) NOT NULL,
    quantity INT(11) NOT NULL DEFAULT 0,
    used INT(11) NOT NULL DEFAULT 0,
    user_id BIGINT(20) UNSIGNED DEFAULT NULL,
    start_date TIMESTAMP NULL DEFAULT NULL,
    end_date TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id)
    FOREIGN KEY (user_id) REFERENCES users(id)
)

15. Bảng loyalty_points
CREATE TABLE loyalty_points(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED DEFAULT NULL,
    total_points INT(11),
    lifetime_points INT(11),
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id)
    FOREIGN KEY (user_id) REFERENCES users(id)
)

16. Bảng point_transactions
CREATE TABLE point_transactions(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED DEFAULT NULL,
    type ENUM(earn', 'redeem) COLLATE utf8mb4_unicode_ci NOT NULL,
    points INT(11),
    description VARCHAR(255),
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id)
    FOREIGN KEY (user_id) REFERENCES users(id)
)

17. Bảng ratings 
CREATE TABLE ratings(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id BIGINT(20),
    user_id BIGINT(20),
    rating TINYINT(3),
    image TEXT NOT NULL,
    comment VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id)
    FOREIGN KEY (product_id) REFERENCES products(id)
    FOREIGN KEY (user_id) REFERENCES users(id)
)

18. Bảng discounts
CREATE TABLE discounts(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id BIGINT(20),
    discount_type ENUM('percent', 'fixed'),
    discount_value DECIMAL(10,2),
    start_date DATETIME,
    end_start DATETIME,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id)
    FOREIGN KEY (product_id) REFERENCES products(id)
)

19. Bảng users
CREATE TABLE users (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    email VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    remember_token VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    phone VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    image VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    gender ENUM('male', 'female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    birth_date DATE DEFAULT NULL,
    role ENUM('user', 'admin', 'shipper', 'staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
    PRIMARY KEY (id)
)

20. Bảng staff_profile
CREATE TABLE staff_profile (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED NOT NULL,
    salary DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    status VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
    position VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    department VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    start_date DATE DEFAULT NULL,
    end_date DATE DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id)
    FOREIGN KEY (user_id) REFERENCES users(id)
)


### 4. Chạy Dự Án

1. Chạy các migrations để tạo bảng trong cơ sở dữ liệu:

   ```bash
   php artisan migrate
   ```

2. Nếu bạn có các dữ liệu mẫu (seeders), bạn có thể chạy lệnh sau để thêm dữ liệu mẫu vào cơ sở dữ liệu:

   ```bash
   php artisan db:seed
   ```

3. Khởi động máy chủ phát triển của Laravel:

   ```bash
   php artisan serve
   ```

   Dự án sẽ chạy trên cổng `8000` mặc định.


