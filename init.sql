-- honangquy_schema.sql
-- Create the `nhanvien` table if it does not exist.
-- Assumes database `honangquy_db` already exists and uses utf8mb4_unicode_ci.

USE `honangquy_db`;

CREATE TABLE IF NOT EXISTS `nhanvien` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `hoten` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngaysinh` DATE DEFAULT NULL,
  `luong` DOUBLE DEFAULT NULL,
  `phongban` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hinhanh` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional sample inserts (uncomment to use)
-- INSERT INTO nhanvien (hoten, ngaysinh, luong, phongban) VALUES
-- ('Nguyễn Văn A','1990-05-15',12000000,'Kế toán');
