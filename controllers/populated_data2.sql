-- Generated MariaDB 10 DML Script

-- Populate admins
INSERT INTO admins (username, password, fullname) VALUES ('Dwi_Setiawan', 'Admin 1', 'Dwi Pearl Setiawan');
INSERT INTO admins (username, password, fullname) VALUES ('Karna_Mustika', 'Admin 2', 'Karna Rowan Mustika');
INSERT INTO admins (username, password, fullname) VALUES ('Gaiman_Nuraini', 'Admin 3', 'Gaiman Violet Nuraini');
INSERT INTO admins (username, password, fullname) VALUES ('Zahra_Tamba', 'Admin 4', 'Zahra Lynn Tamba');
INSERT INTO admins (username, password, fullname) VALUES ('Sherly_Harini', 'Admin 5', 'Sherly Annabel Harini');

-- Populate stores
INSERT INTO store (store_name, phone, address) VALUES ('Elvina Tbk', '+62271686230','Jln. Padmasari no 8');
INSERT INTO store (store_name, phone, address) VALUES ('Fa Adriansyah', '+62259941514','Jln. Purwanti no 6');
INSERT INTO store (store_name, phone, address) VALUES ('UD Ardana (Persero) Tbk', '+6266764942694','Psr. Eka no 92');
INSERT INTO store (store_name, phone, address) VALUES ('KKB Antoni', '+6248707971654','Jr. Prasetyo no 8');
INSERT INTO store (store_name, phone, address) VALUES ('PT Dirgantara', '+6232805362517','Kpg. Elvina no 52');

-- Populate products
INSERT INTO products (product_name, price, stock) VALUES ('Sepeda Asbestos Khusus', 57000, 189);
INSERT INTO products (product_name, price, stock) VALUES ('Keripik Aluminium Buatan Tangan', 45000, 146);
INSERT INTO products (product_name, price, stock) VALUES ('Motor Sutra Luar Biasa', 11000, 164);
INSERT INTO products (product_name, price, stock) VALUES ('Handuk Beton Rustik', 86000, 142);
INSERT INTO products (product_name, price, stock) VALUES ('Pizza Baja Cerdas', 95000, 36);
INSERT INTO products (product_name, price, stock) VALUES ('Komputer Asbestos Buatan Sendiri', 48000, 72);
INSERT INTO products (product_name, price, stock) VALUES ('Topi Plastik Elegan', 51000, 175);
INSERT INTO products (product_name, price, stock) VALUES ('Tas Sutra Elegan', 25000, 24);
INSERT INTO products (product_name, price, stock) VALUES ('Kemeja Aluminium Menawan', 89000, 95);
INSERT INTO products (product_name, price, stock) VALUES ('Tas Granit Lezat', 21000, 142);

-- Populate transactions and transaction_details
INSERT INTO transactions (store_id, admin_id, sub_total, transaction_date) VALUES (2 ,4, 0, '2025-12-16 06:13:47');
INSERT INTO transaction_details (transaction_id, product_id, quantity, base_price) VALUES (1, 5, 5, 95000);
INSERT INTO transaction_details (transaction_id, product_id, quantity, base_price) VALUES (1, 4, 5, 86000);
INSERT INTO transaction_details (transaction_id, product_id, quantity, base_price) VALUES (1, 8, 5, 25000);
INSERT INTO transaction_details (transaction_id, product_id, quantity, base_price) VALUES (1, 3, 5, 11000);
UPDATE transactions SET sub_total=1085000 WHERE transaction_id=1;
INSERT INTO transactions (store_id, admin_id, sub_total, transaction_date) VALUES (2 ,5, 0, '2026-04-15 20:30:23');
INSERT INTO transaction_details (transaction_id, product_id, quantity, base_price) VALUES (2, 2, 5, 45000);
UPDATE transactions SET sub_total=225000 WHERE transaction_id=2;
INSERT INTO transactions (store_id, admin_id, sub_total, transaction_date) VALUES (1 ,3, 0, '2026-06-06 04:10:28');
INSERT INTO transaction_details (transaction_id, product_id, quantity, base_price) VALUES (3, 6, 1, 48000);
INSERT INTO transaction_details (transaction_id, product_id, quantity, base_price) VALUES (3, 7, 2, 51000);
INSERT INTO transaction_details (transaction_id, product_id, quantity, base_price) VALUES (3, 9, 4, 89000);
INSERT INTO transaction_details (transaction_id, product_id, quantity, base_price) VALUES (3, 3, 1, 11000);
UPDATE transactions SET sub_total=517000 WHERE transaction_id=3;
