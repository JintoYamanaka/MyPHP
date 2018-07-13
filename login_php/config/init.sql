create database dotinstall_sns_php;

grant all on dotinstall_sns_php.* to dbuser@localhost identified by 'mu4uJsif';

use dotinstall_sns_php

create table users (
  id int not null auto_increment primary key,
  email varchar(255) unique,
  password varchar(255),
  created datetime,
  modified datetime
);

desc users;
