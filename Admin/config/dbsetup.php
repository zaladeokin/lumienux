<?php
require_once('autoload.php');
/**
 * Set up all Tables in db
 */
createTable('admin','id INTEGER NOT NULL AUTO_INCREMENT, email varchar(255) NOT NULL UNIQUE, password varchar(255) NOT NULL, PRIMARY KEY(id)', $admin);
createTable('reset_password','admin_id INTEGER NOT NULL, token varchar(255) NOT NULL, expire Timestamp NOT NULL, PRIMARY KEY(admin_id), CONSTRAINT FOREIGN KEY (admin_id) REFERENCEs admin (id) ON DELETE CASCADE', $admin);//Handle if user already exist if exist delete and insert again..
createTable('product','id INTEGER NOT NULL AUTO_INCREMENT, name varchar(255) NOT NULL UNIQUE, description TEXT NOT NULL, price INTEGER NOT NULL, stock INTEGER NOT NULL, category ENUM("1", "2", "3", "4", "5", "6") DEFAULT "6", img VARCHAR(255),sold_product INTEGER DEFAULT "0", PRIMARY KEY(id)', $admin);
createTable('orders','id INTEGER NOT NULL AUTO_INCREMENT, tx_ref varchar(255) NOT NULL, email varchar(255) NOT NULL, amount INTEGER NOT NULL, status ENUM("0", "1") DEFAULT "0", payment_info LONGTEXT, deleted ENUM("0", "1") DEFAULT "0",  PRIMARY KEY(id)', $admin);
createTable('suscriber','id INTEGER NOT NULL AUTO_INCREMENT, email varchar(255) NOT NULL UNIQUE, name varchar(255) NOT NULL, PRIMARY KEY(id)', $admin);