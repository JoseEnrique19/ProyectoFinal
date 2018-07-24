drop database log;
create database log;
	use log;

create table users(
	id int(20) not null auto_increment primary key,
	google_id varchar(50) not null unique,
	email varchar(100) not null unique
);

