SET NAMES utf8mb4;
SET time_zone = '+07:00';

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
  status ENUM('active', 'locked') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS profiles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL UNIQUE,
  full_name VARCHAR(120) NOT NULL,
  phone VARCHAR(20),
  address VARCHAR(255),
  avatar VARCHAR(255),
  CONSTRAINT fk_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  slug VARCHAR(120) NOT NULL UNIQUE,
  description VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS authors (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  biography TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS books (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  title VARCHAR(190) NOT NULL,
  slug VARCHAR(210) NOT NULL UNIQUE,
  isbn VARCHAR(20) NOT NULL UNIQUE,
  publisher VARCHAR(150) NOT NULL,
  published_year SMALLINT UNSIGNED NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  sale_price DECIMAL(12,2),
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  cover_image VARCHAR(255) NOT NULL,
  short_description VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  featured TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('published', 'draft') NOT NULL DEFAULT 'published',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_books_title (title),
  INDEX idx_books_category (category_id),
  CONSTRAINT fk_books_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS book_authors (
  book_id BIGINT UNSIGNED NOT NULL,
  author_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (book_id, author_id),
  CONSTRAINT fk_book_authors_book FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
  CONSTRAINT fk_book_authors_author FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS news (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(190) NOT NULL,
  slug VARCHAR(210) NOT NULL UNIQUE,
  excerpt VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  image VARCHAR(255),
  published_at DATETIME NOT NULL,
  status ENUM('published', 'draft') NOT NULL DEFAULT 'published',
  CONSTRAINT fk_news_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contacts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(20),
  subject VARCHAR(190) NOT NULL,
  message TEXT NOT NULL,
  status ENUM('new', 'read', 'replied') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT IGNORE INTO users (id, email, password_hash, role) VALUES
  (1, 'admin@mocthu.vn', '$2y$10$wf4N.uGE0S7e8R.NHaEGQeCyTfSdSkt5h4sdDivrVElBzvUbqzGJ2', 'admin'),
  (2, 'reader@mocthu.vn', '$2y$10$wf4N.uGE0S7e8R.NHaEGQeCyTfSdSkt5h4sdDivrVElBzvUbqzGJ2', 'customer');

INSERT IGNORE INTO profiles (id, user_id, full_name, phone, address) VALUES
  (1, 1, 'Quản trị Mộc Thư', '0909000001', 'TP. Hồ Chí Minh'),
  (2, 2, 'Nguyễn Minh An', '0909000002', 'Gò Vấp, TP. Hồ Chí Minh');

INSERT IGNORE INTO categories (id, name, slug, description) VALUES
  (1, 'Văn học', 'van-hoc', 'Tiểu thuyết, truyện ngắn và tản văn chọn lọc.'),
  (2, 'Khoa học', 'khoa-hoc', 'Khám phá khoa học, công nghệ và vũ trụ.'),
  (3, 'Thiếu nhi', 'thieu-nhi', 'Sách nuôi dưỡng trí tưởng tượng cho trẻ.'),
  (4, 'Kinh doanh', 'kinh-doanh', 'Quản trị, kinh tế và kỹ năng nghề nghiệp.'),
  (5, 'Kỹ năng sống', 'ky-nang-song', 'Phát triển bản thân và sống cân bằng.');

INSERT IGNORE INTO authors (id, name, biography) VALUES
  (1, 'An Nhiên', 'Tác giả Việt viết về thiên nhiên và đời sống nội tâm.'),
  (2, 'Trần Nhật Minh', 'Nhà nghiên cứu khoa học phổ thông và giáo dục STEM.'),
  (3, 'Lê Hoài Thương', 'Tác giả sách thiếu nhi với nhiều câu chuyện giàu trí tưởng tượng.'),
  (4, 'Phạm Đức Long', 'Chuyên gia quản trị và phát triển tổ chức.'),
  (5, 'Nguyễn Hà Vy', 'Tác giả về tâm lý ứng dụng và lối sống tối giản.');

INSERT IGNORE INTO books (id, category_id, title, slug, isbn, publisher, published_year, price, sale_price, stock, cover_image, short_description, description, featured) VALUES
  (1, 1, 'Ngôi Nhà Bên Hồ', 'ngoi-nha-ben-ho', '9786040010011', 'Mộc Thư', 2025, 168000, 139000, 32, '/assets/cover-literary.png', 'Một hành trình trở về, chữa lành và tìm lại ký ức.', 'Tiểu thuyết giàu cảm xúc về một người trẻ trở lại căn nhà cũ bên hồ và học cách đối diện với những điều chưa kịp nói.', 1),
  (2, 1, 'Mùa Gió Qua Thung Lũng', 'mua-gio-qua-thung-lung', '9786040010028', 'Nhã Nam', 2024, 145000, NULL, 18, '/assets/cover-literary.png', 'Những câu chuyện nhỏ giữa núi rừng và con người.', 'Tập truyện ngắn nhẹ nhàng, ghi lại vẻ đẹp của những cuộc gặp gỡ tưởng như tình cờ.', 0),
  (3, 2, 'Bản Đồ Của Những Vì Sao', 'ban-do-cua-nhung-vi-sao', '9786040010035', 'Tri Thức', 2025, 220000, 189000, 24, '/assets/cover-science.png', 'Khám phá vũ trụ bằng ngôn ngữ gần gũi và trực quan.', 'Cuốn sách đưa người đọc qua lịch sử thiên văn học, các hành tinh và những câu hỏi lớn về vũ trụ.', 1),
  (4, 2, 'Tương Lai Của Trí Tuệ', 'tuong-lai-cua-tri-tue', '9786040010042', 'Khoa Học', 2025, 198000, 175000, 15, '/assets/cover-science.png', 'AI, con người và những lựa chọn của thế kỷ mới.', 'Một góc nhìn cân bằng về trí tuệ nhân tạo, đạo đức công nghệ và tương lai việc làm.', 1),
  (5, 3, 'Con Thuyền Giấy Đi Tìm Ánh Sáng', 'con-thuyen-giay', '9786040010059', 'Kim Đồng', 2024, 98000, 79000, 46, '/assets/cover-children.png', 'Cuộc phiêu lưu kỳ diệu qua khu rừng đom đóm.', 'Câu chuyện minh họa giúp trẻ học về lòng dũng cảm, tình bạn và sự tò mò với thế giới.', 1),
  (6, 3, 'Khu Rừng Kể Chuyện', 'khu-rung-ke-chuyen', '9786040010066', 'Kim Đồng', 2023, 89000, NULL, 39, '/assets/cover-children.png', 'Mười hai truyện kể ấm áp trước giờ đi ngủ.', 'Những câu chuyện ngắn về muông thú, cây cối và bài học yêu thương dành cho trẻ nhỏ.', 0),
  (7, 4, 'Quản Trị Bằng Sự Tử Tế', 'quan-tri-bang-su-tu-te', '9786040010073', 'Lao Động', 2025, 185000, 159000, 21, '/assets/cover-science.png', 'Xây đội ngũ hiệu quả bằng niềm tin và minh bạch.', 'Các nguyên tắc quản trị thực tế giúp tổ chức phát triển bền vững mà không đánh mất yếu tố con người.', 0),
  (8, 4, 'Tư Duy Sản Phẩm', 'tu-duy-san-pham', '9786040010080', 'Công Thương', 2024, 210000, 179000, 17, '/assets/cover-science.png', 'Từ vấn đề người dùng đến sản phẩm có giá trị.', 'Hướng dẫn nghiên cứu, thử nghiệm và phát triển sản phẩm dành cho đội ngũ hiện đại.', 1),
  (9, 5, 'Sống Chậm Giữa Thành Phố', 'song-cham-giua-thanh-pho', '9786040010097', 'Mộc Thư', 2024, 135000, 115000, 28, '/assets/cover-literary.png', 'Những thực hành nhỏ để tìm lại nhịp sống của mình.', 'Cuốn sách đưa ra các bài tập đơn giản về chú tâm, sắp xếp không gian và quản lý năng lượng.', 0),
  (10, 5, 'Một Ngày Đủ Đầy', 'mot-ngay-du-day', '9786040010103', 'Mộc Thư', 2025, 155000, NULL, 30, '/assets/cover-children.png', 'Thiết kế một ngày cân bằng, tập trung và có ý nghĩa.', 'Gợi ý xây dựng thói quen lành mạnh, làm việc sâu và nuôi dưỡng các mối quan hệ.', 0);

INSERT IGNORE INTO book_authors (book_id, author_id) VALUES
  (1,1),(2,1),(3,2),(4,2),(5,3),(6,3),(7,4),(8,4),(9,5),(10,5);

INSERT IGNORE INTO news (id, user_id, title, slug, excerpt, content, image, published_at) VALUES
  (1, 1, '5 cách xây dựng thói quen đọc sách mỗi ngày', '5-cach-xay-dung-thoi-quen-doc-sach', 'Bắt đầu từ mười phút và tạo một không gian đọc thật dễ chịu.', 'Thói quen đọc không bắt đầu bằng mục tiêu quá lớn. Hãy chọn một khung giờ cố định, đặt sách trong tầm mắt và ghi lại điều bạn tâm đắc sau mỗi chương.', '/assets/bookstore-hero.png', '2026-08-20 09:00:00'),
  (2, 1, 'Mộc Thư ra mắt tủ sách khoa học cho người trẻ', 'tu-sach-khoa-hoc-cho-nguoi-tre', 'Những cuốn sách giúp kiến thức phức tạp trở nên gần gũi.', 'Tủ sách mới tập trung vào thiên văn, công nghệ, môi trường và tư duy phản biện với hình thức trình bày trực quan.', '/assets/cover-science.png', '2026-08-16 10:30:00'),
  (3, 1, 'Ngày hội đổi sách cũ lấy cây xanh', 'doi-sach-cu-lay-cay-xanh', 'Mang sách đã đọc đến và nhận một chậu cây nhỏ cho góc học tập.', 'Chương trình được tổ chức vào cuối tuần nhằm kéo dài vòng đời của sách và kết nối cộng đồng yêu đọc.', '/assets/cover-children.png', '2026-08-10 08:00:00');
