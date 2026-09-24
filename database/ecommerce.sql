-- ============================================
-- AGORA E-COMMERCE DATABASE
-- ============================================

-- Create the database
CREATE DATABASE agora;

-- Select the database
USE agora;


-- ============================================
-- BUSINESSES TABLE
-- ============================================

CREATE TABLE businesses (
    business_id INT AUTO_INCREMENT PRIMARY KEY,
    business_name VARCHAR(100) NOT NULL
);


-- ============================================
-- USERS TABLE
-- ============================================

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    business_id INT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('agora_admin', 'business_admin', 'seller', 'buyer') NOT NULL,

    FOREIGN KEY (business_id) REFERENCES businesses(business_id)
);


-- ============================================
-- PRODUCTS TABLE
-- ============================================

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,

    FOREIGN KEY (seller_id) REFERENCES users(user_id)
);


-- ============================================
-- ORDERS TABLE
-- ============================================

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (buyer_id) REFERENCES users(user_id)
);


-- ============================================
-- ORDER ITEMS TABLE
-- ============================================

CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,

    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);


-- ============================================
-- SAMPLE BUSINESSES
-- ============================================

INSERT INTO businesses (business_name)
VALUES
('NZ Mega Supplies'),
('Fox Butcher');


-- ============================================
-- SAMPLE USERS
-- ============================================

INSERT INTO users (
    business_id,
    full_name,
    email,
    password,
    role
)
VALUES
(1, 'Sam Colo', 'sam@nzmega.co.nz', 'password123', 'business_admin'),
(1, 'James Kennedy', 'james@nzmega.co.nz', 'password123', 'seller'),
(2, 'Lisa Schmitt', 'lisa@foxbutcher.co.nz', 'password123', 'buyer'),
(NULL, 'Simon Lau', 'simon@agora.co.nz', 'password123', 'agora_admin');


-- ============================================
-- SAMPLE PRODUCTS
-- ============================================

INSERT INTO products (
    seller_id,
    product_name,
    description,
    price,
    quantity
)
VALUES
(2, 'Chicken Breast', 'Fresh chicken breast', 12.50, 50),
(2, 'Beef Steak', 'Premium beef steak', 18.00, 30),
(2, 'Fresh Salmon', 'Fresh NZ salmon fillet', 15.00, 20);


-- ============================================
-- TEST QUERIES
-- ============================================

-- Simple query: display all products
SELECT *
FROM products;


-- Complex JOIN query:
-- Display seller, business and product information
SELECT
    users.full_name AS seller,
    businesses.business_name,
    products.product_name,
    products.price,
    products.quantity
FROM products
JOIN users
    ON products.seller_id = users.user_id
JOIN businesses
    ON users.business_id = businesses.business_id;


-- Search query:
-- Find products containing "Chicken"
SELECT *
FROM products
WHERE product_name LIKE '%Chicken%';



-- complex testing
SELECT
    businesses.business_name,
    users.full_name AS seller,
    COUNT(products.product_id) AS total_products,
    SUM(products.quantity) AS total_stock,
    AVG(products.price) AS average_price
FROM products
JOIN users
    ON products.seller_id = users.user_id
JOIN businesses
    ON users.business_id = businesses.business_id
GROUP BY
    businesses.business_id,
    businesses.business_name,
    users.user_id,
    users.full_name
ORDER BY average_price DESC;