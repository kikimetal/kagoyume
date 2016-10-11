<?php
const ROOT_URL = "http://localhost/camp/11/";
const CSS = ROOT_URL."app/css/style.css";

// page
const APP = ROOT_URL."app/";

const TOP = "top.php";

const LOGIN = "login.php";
const SEARCH = "search.php";
const ITEM = "item.php";
const ADD = "add.php";
const CART = "cart.php";

const BUY_CONFIRM = "buy_confirm.php";
const BUY_COMPLETE = "buy_complete.php";

const MYDATA = "mydata.php";
const MYDATA_UPDATE = "mydata_update.php";
const MY_UPDATE_RESULT = "my_update_result.php";
const MY_DELETE = "my_delete.php";
const MY_DELETE_RESULT = "my_delete_result.php";
const MY_HISTORY = "my_history.php";

const REGISTRATION = "registration.php";
const REGISTRATION_CONFIRM = "registration_confirm.php";
const REGISTRATION_COMPLETE = "registration_complete.php";



// log
const LOGS_DIR = "../logs/logs.txt";
// yahoo_api
const YAHOO_API_COMMON = "../config/common.php";
// DB
const DATABASE = "kagoyume_db";
const DB_USERNAME = "kiki";
const DB_PASSWORD = "metal";
const PDO_DSN = "mysql:host=localhost;dbname=".DATABASE.";charset=utf8";
// html
const BR = "<br>";

//

/*
(object)$_SESSION["member"] == ログイン成功時に生成される Memberクラスのオブジェクト
    中身に、ID, 名前、メール、住所、合計金額 を持つ
(array)$_SESSION["guest"] == アイテムカートにものを追加した時点で、その内容が保持される配列
(array)$_SESSION["search"] == 検索条件の保持


*/
