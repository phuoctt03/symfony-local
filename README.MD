# Hướng Dẫn Thiết Lập Symfony Sử Dụng XAMPP (MariaDB)

## 1. Cài đặt và Khởi Động XAMPP

- **Tải và cài đặt XAMPP:**  
  [Link tải XAMPP](https://www.apachefriends.org/index.html)

- **Khởi động dịch vụ:**  
  Mở **XAMPP Control Panel** và bấm **Start** cho **Apache** và **MySQL** (thực chất là MariaDB).

## 2. Tạo Database cho Symfony

1. **Truy cập phpMyAdmin:**  
   Mở trình duyệt và truy cập [http://localhost/phpmyadmin](http://localhost/phpmyadmin).

2. **Tạo Database:**  
   - Chọn tab **Databases**.
   - Nhập tên database (ví dụ: `symfony`) vào ô **Create database**.
   - Chọn **utf8mb4_general_ci** cho Collation.
   - Bấm **Create**.

## 3. Cấu hình tệp `.env` trong Symfony

Mở tệp `.env` trong thư mục gốc của dự án Symfony và tìm dòng sau:

```env
DATABASE_URL="mysql://username:password@host:port/database_name"
```

Cập nhật thành:

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/symfony"
```

- **root:** Tên người dùng mặc định của MariaDB.
- **@127.0.0.1:** Địa chỉ localhost cho MariaDB.
- **3306:** Cổng mặc định của MariaDB.
- **symfony:** Tên database đã tạo.

## 4. Cập nhật Schema cho Database

1. **Cài đặt các gói Composer:**

   ```bash
   composer install
   ```

2. **Cập nhật Schema:**  

   ```bash
   php bin/console doctrine:schema:update --force
   ```

## 5. Tối ưu hóa và Chạy Ứng Dụng Symfony

1. **Tối ưu hóa:**  

   ```bash
   composer optimize
   ```

2. **Khởi chạy dự án:**  

   ```bash
   composer start
   ```

## 6. Truy cập Ứng Dụng

- Mở trình duyệt và truy cập [http://localhost:3000](http://localhost:3000) để kiểm tra ứng dụng.

## Lưu ý

- Đảm bảo dịch vụ **MySQL (MariaDB)** đang hoạt động trên XAMPP.
- Kiểm tra phiên bản PHP trong XAMPP để đảm bảo tương thích với Symfony.

