# Cách chạy dự án Mộc Thư

Dự án chạy giống nhau trên Windows, macOS và Linux.

## 1. Cài Docker

Cài Docker Desktop và mở Docker Desktop trước khi chạy dự án.

## 2. Khởi động

Mở Terminal hoặc PowerShell tại thư mục dự án, chạy đúng một lệnh:

```bash
docker compose up --build -d
```

Mở trình duyệt tại: http://localhost:8080

Docker tự cài đúng PHP 8.3, Apache, MySQL 8.4, tạo 8 bảng và dữ liệu mẫu. Không cần cài Node.js, PHP, MySQL hoặc thư viện riêng trên máy.

## 3. Đăng nhập quản trị

```text
Email: admin@mocthu.vn
Mật khẩu: password
```

Trang quản trị: http://localhost:8080/admin/index.php

## 4. Dừng dự án

```bash
docker compose down
```

Database vẫn được giữ. Muốn xóa dữ liệu và tạo lại dữ liệu mẫu:

```bash
docker compose down -v
docker compose up --build -d
```

## Lỗi thường gặp

- Không mở được trang: kiểm tra Docker Desktop đang chạy, sau đó chạy `docker compose ps`.
- Cổng 8080 đang bận: đổi `8080:80` thành `8081:80` trong `docker-compose.yml`, rồi mở http://localhost:8081.
- Vừa sửa `Dockerfile` hoặc file trong `docker/`: chạy lại `docker compose up --build -d`.
- Xem lỗi: chạy `docker compose logs -f web db`.

Không cần chạy lệnh build Node.js. Bootstrap đã nằm trong source nên giao diện không phụ thuộc CDN.
