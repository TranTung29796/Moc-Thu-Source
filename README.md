# Mộc Thư - Website sách PHP & MySQL

Đồ án website sách responsive, xây dựng bằng HTML5, CSS3, Bootstrap 5.3.3, JavaScript, PHP 8.3 và MySQL 8.4.

## Chức năng

- Trang chủ có slider, sách nổi bật và tin tức.
- Trang giới thiệu, sách, chi tiết sách, tin tức, liên hệ có Google Maps iframe.
- Tìm kiếm Ajax, lọc danh mục và phân trang sách.
- Đăng ký, đăng nhập, đăng xuất, session, cookie ghi nhớ email và phân quyền.
- Quản trị thống kê, thêm, sửa, xóa sách và upload ảnh bìa.
- Validation ở trình duyệt và máy chủ, CSRF token, PDO prepared statement.
- Responsive cho máy tính, máy tính bảng và điện thoại.

## Cơ sở dữ liệu

MySQL gồm 8 bảng: `users`, `profiles`, `categories`, `authors`, `books`, `book_authors`, `news`, `contacts`. Schema có khóa chính, khóa ngoại, quan hệ 1-1, 1-n và n-n.

## Chạy dự án

Yêu cầu duy nhất là Docker Desktop. Chạy:

```bash
docker compose up --build -d
```

Mở [http://localhost:8080](http://localhost:8080).

Tài khoản quản trị mẫu:

```text
Email: admin@mocthu.vn
Mật khẩu: password
```

Xem hướng dẫn tiếng Việt tại `StartRun.md`.

Báo cáo Word 26 trang nằm tại `docs/BAO_CAO_GIUA_KY_MOC_THU.docx`; hướng dẫn triển khai chi tiết nằm tại `docs/HUONG_DAN_TRIEN_KHAI_MOC_THU.md`.

## Cấu trúc

```text
admin/                 Trang quản trị và CRUD sách
api/                   API tìm kiếm Ajax
config/                Cấu hình ứng dụng và PDO MySQL
database/schema.sql    8 bảng và dữ liệu mẫu
docker/                Cấu hình Apache/PHP
includes/              Layout, xác thực và hàm dùng chung
public/assets/         CSS, JavaScript, Bootstrap và hình ảnh
*.php                  Các trang công khai
docker-compose.yml     Môi trường PHP 8.3 + MySQL 8.4
```

## Lệnh hữu ích

```bash
docker compose logs -f
docker compose down
docker compose down -v
```

Lệnh cuối xóa cả database và đưa dữ liệu về trạng thái mẫu ở lần chạy kế tiếp.
