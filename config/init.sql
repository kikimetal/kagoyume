-- ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★
-- 今回使うテーブル
-- ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★

CREATE DATABASE kagoyume_db;

GRANT ALL ON kagoyume_db .* to kiki@localhost;

use kagoyume_db;

CREATE TABLE user_t (
    userID int auto_increment primary key,
    name varchar(255) not null,
    password varchar(255),
    mail varchar(255),
    address text,
    total int,
    newDate datetime,
    deleteFlg int
);

CREATE TABLE buy_t (
    buyID int auto_increment primary key,
    userID int,
    itemCode varchar(255),
    type int,
    buyDate datetime,
    foreign key (userID) references user_t (userID)
);
