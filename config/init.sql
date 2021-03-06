-- ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★
-- 今回使うテーブル
-- ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★

CREATE DATABASE kagoyume_db;

GRANT ALL ON kagoyume_db .* to kiki@localhost;

use kagoyume_db;

CREATE TABLE user_t (
    userID int auto_increment primary key,
    name varchar(255) not null,
    password varchar(255) not null,
    mail varchar(255),
    address text,
    total int,
    newDate datetime,
    deleteFlg int default 0
);

CREATE TABLE buy_t (
    buyID int auto_increment primary key,
    userID int not null,
    itemCode varchar(255) not null,
    type int not null,
    buyDate datetime,
    foreign key (userID) references user_t (userID)
);

INSERT INTO user_t (name, password) VALUES ("himechan", "kawaii");
