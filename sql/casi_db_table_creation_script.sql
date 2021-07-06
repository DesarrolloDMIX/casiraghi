drop schema if exists casiraghi_wsi;
create schema casiraghi_wsi;
use casiraghi_wsi;
create table categories (
  id int primary key AUTO_INCREMENT,
  parent_id int,
  `active` bool,
  is_root_category bool,
  `name` varchar(100),
  `description` text,
  `id_prestashop` int
);
create table products (
  `id` int primary key AUTO_INCREMENT,
  `category_id` int,
  `default_image_id` int,
  `default_combination_id` int,
  `price` float,
  `name` varchar(180),
  `description` text,
  `description_short` text,
  `has_combinations` bool,
  `id_prestashop` int
);
create table product_images (
  `id` int primary key AUTO_INCREMENT,
  `product_id` int
);
create table combinations (
  `id` int primary key AUTO_INCREMENT,
  `product_id` int,
  `price` float,
  `id_prestashop` int
);
create table combinations_product_option_values (
  `id` int primary key AUTO_INCREMENT,
  `combination_id` int,
  `product_option_value_id` int
);
create table product_option_values (
  `id` int primary key AUTO_INCREMENT,
  `product_option_id` int,
  `value` varchar(80),
  `id_prestashop` int
);
create table product_options (
  `id` int primary key AUTO_INCREMENT,
  `type` varchar(30),
  `name` varchar(50),
  `id_prestashop` int
);
create table carts (
  `id` int primary key AUTO_INCREMENT,
  `id_prestashop` int
);
create table carts_products (
  `id` int primary key AUTO_INCREMENT,
  `cart_id` int,
  `product_id` int
);
create table payment_requests (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `email` VARCHAR(120),
  `token` VARCHAR(120),
  `amount` FLOAT,
  `card_type_id` INT,
  `installments` INT
)