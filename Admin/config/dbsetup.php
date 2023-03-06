<?php
require_once('autoload.php');
/**
 * Set up all Tables in db
 */
createTable('admin','id INTEGER NOT NULL AUTO_INCREMENT, email varchar(255) NOT NULL UNIQUE, password varchar(255) NOT NULL, PRIMARY KEY(id)', $admin);
createTable('reset_password','admin_id INTEGER NOT NULL, token varchar(255) NOT NULL, expire Timestamp NOT NULL, PRIMARY KEY(admin_id), CONSTRAINT FOREIGN KEY (admin_id) REFERENCEs admin (id) ON DELETE CASCADE', $admin);//Handle if user already exist if exist delete and insert again..
createTable('product','id INTEGER NOT NULL AUTO_INCREMENT, name varchar(255) NOT NULL UNIQUE, description TEXT NOT NULL, price INTEGER NOT NULL, stock INTEGER NOT NULL, category ENUM("1", "2", "3", "4", "5", "6") DEFAULT "6", img VARCHAR(255), PRIMARY KEY(id)', $admin);
//createTable('service','id INTEGER NOT NULL AUTO_INCREMENT, title varchar(255) NOT NULL, description TEXT NOT NULL, price INTEGER NOT NULL,PRIMARY KEY(id)', $admin);
//createTable('setup_plan','id INTEGER NOT NULL AUTO_INCREMENT, title varchar(255) NOT NULL, description LONGTEXT NOT NULL, price INTEGER NOT NULL,PRIMARY KEY(id)', $admin);
createTable('suscriber','id INTEGER NOT NULL AUTO_INCREMENT, email varchar(255) NOT NULL UNIQUE, name varchar(255) NOT NULL, PRIMARY KEY(id)', $admin);