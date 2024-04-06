<?php

global $con;

// config table
DBOld::execute('
	CREATE TABLE IF NOT EXISTS config (
		id INT(3) AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(30),
		value VARCHAR(300) 
	)
');

if (!DBOld::exists('config WHERE name="public"')) DBOld::add('config (name, value) VALUES (?, ?)', ['public', 1]);

// users table
DBOld::execute('
	CREATE TABLE IF NOT EXISTS users (
		id INT(11) AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(50) DEFAULT "",
		password VARCHAR(128) DEFAULT "",
		email VARCHAR(50) DEFAULT "",
		firstName VARCHAR(50) DEFAULT "",
		lastName VARCHAR(50) DEFAULT "",
		groupId INT(11) DEFAULT 0,
		admin TINYINT(1) DEFAULT 0,
		time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	)
');

// password resets
DBOld::execute('
	CREATE TABLE IF NOT EXISTS user_resets (
		id INT(11) AUTO_INCREMENT PRIMARY KEY,
		userID INT(11),
		security VARCHAR(10),
		time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	)
');
