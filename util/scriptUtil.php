<?php
require_once "defineUtil.php";

// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★----------------------
// なるべくクラスを利用して慣れていくスタイル
// --------------------☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆

// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★----------------------
// ゲスト含む全ユーザー
abstract class User {
    public $name;
    // public function search_product() {}
    abstract public function add_cart();
    abstract public function delete_cart();
}


// 会員
class Member extends User {
    public $userID;
    public $name;
    // public $password;
    public $mail;
    public $address;
    public $total;
    public function __construct($array=array()) {
        $this->userID = chk($array, "userID");
        $this->name = chk($array, "name");
        $this->mail = chk($array, "mail");
        $this->address = chk($array, "address");
        $this->total = chk($array, "total");
    }
    // ログインユーザーのログアウト処理
    public function logout() {
            session_start_anyway();
            $_SESSION = array();
            // セッションを切断するにはセッションクッキーも削除する。
            if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000,
                            $params["path"], $params["domain"],
                            $params["secure"], $params["httponly"]
                    );
            }
            // 最終的に、セッションを破壊する
            session_destroy();
    }




    // 買い物かごへ追加
    public function add_cart() {

    }
    // 買い物かごから削除
    public function delete_cart() {

    }
    // かごの中身を買う
    public function buy() {

    }
}

// ゲストさん
class Guest extends User {
    // ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
    // 引数に与えるユーザーネームとパスワードでログインを試みる
    // 成功で true を返し、$_SESSION["login"] = true
    // 失敗で エラーメッセージを返す
    public function login($name, $password) {
        $params = array("name" => $name, "password" => $password);
        $db_access = new DBaccess;
        $result = $db_access->select_all("user_t", $params); // 未ヒットで空の配列、ヒットで配列、エラーでPDOEception が帰ってくる

        // var_dump($result);

        if(empty($result)){
            return false;
        }elseif(is_array($result)){
            if($result[0]["deleteFlg"] != 0){
                return false;
            }
            session_start_anyway();
            $_SESSION["member"] = new Member($result[0]);
            return true;
        }else{
            return $result;
        }
    }
    // ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.

    public function entry() {}
    public function add_cart() {}
    public function delete_cart() {}
}
// --------------------☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆







// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★----------------------

// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
// 今回の目玉性的クラス
class Html {
    public static function return_top() {
        ?>
        <p><strong><a href="<?=TOP?>">＊かごゆめ＊ トップページ</a></strong></p>
        <?php
    }
    // nav
    public static function nav() {
        session_start_anyway();
        if(empty($_SESSION["login"])){ // login===falseだったら
            ?>
            <nav class="center guest_nav">
                <ul>
                    <li><a href="#"><div>ようこそ! ゲストさん!</div></a></li>
                    <li><a href="<?=REGISTRATION?>"><div>新規登録</div></a></li>
                    <li><a href="<?=LOGIN?>"><div>ログイン</div></a></li>
                </ul>
            </nav>
            <?php
        }else{ // login===trueだったら
            ?>
            <nav class="center user_nav">
                <ul>
                    <li><a href="#"><div>ようこそ! <?= $_SESSION["member"]->name ?>さん!</div></a></li>
                    <li><a href="#"><div>マイページ</div></a></li>
                    <li><a href="<?=LOGIN?>"><div>ログアウト</div></a></li>
                </ul>
            </nav>
            <?php
        }
    }
    // head の中身
    public static function head($title = null) {
        ?>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width,initial-scale=1">
            <title><?php if($title){echo $title;}else{echo "kagoyume!";} ?></title>
            <link rel="stylesheet" href="<?=CSS?>" media="screen" title="no title">
        </head>
        <?php
    }
    public static function header($header, $href=null) {
        ?>
        <header class="space20px">
            <h1><a href="<?=$href?>"><?=$header?></a></h1>
        </header>
        <?php
    }
    public static function address() {
        ?>
        <address class="">
            2016 all rights reserved kikimetal.com from little-twin-stars.
        </address>
        <?php
    }
    public static function wrapper() {
        ?>
        <aside class="design">
            <div class="wrapper"></div>
        </aside>
        <?php
    }
}

// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★----------------------
// 引数に入れられたstringをlog.txt へ追記。$str末尾で改行。
class Log {
    public static function output($str) {
        session_start_anyway();
        if(!empty($_SESSION["login"]) and !empty($_SESSION["menber"]["name"])){
            $name = $_SESSION["menber"]["name"];
        }else{
            $name = "Guest";
        }
        $log = "[".date("Y-m-d H:i:s", time())."][user:".$name."][access:".$str."]";
        $txt = fopen(LOGS_DIR, "a");
        fwrite($txt, $log.PHP_EOL);
        fclose($txt);
    }
}
// ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
// とにかくこれを使っておけばどこでもエラーを出さずにsession_startできる！ セッションネームは"kagoyume"
function session_start_anyway() {
    if(session_status() !== 2):
        $name = "kagoyume";
        session_name($name);
        session_set_cookie_params(60 * 60 * 24); // 有効期限は24時間
        session_start();
        session_regenerate_id();
        // echo "<p style='position: fixed; left: 10px; top: 10px;'>session_started !!</p>";
    endif;
}
// ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
// 第１引数に指定したstrのキーの、ポスト[キー]の存在を確認後、第２引数と値を比較、真偽値を返す
// 第２引数がなかった場合、_POST[第１引数]の存在を確認後、その値を返す
function chk_post($key, $chk_value=null) {
    if(!empty($_POST[$key]) and $chk_value === null){
        return $_POST[$key];
    }
    elseif(!empty($_POST[$key]) and $_POST[$key] === $chk_value){
        return true;
    }else{
        return false;
    }
}

// ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
// 上の万能バージョン を追加
// 第１引数に割り込み、$_POST, $_GET, $_SESSION など入れれば、それに対応したものとなる、これ便利すぎて上のいらないや...
// 第２引数に指定したstrのキーの、配列[キー]の存在を確認後、第３引数と値を比較、真偽値を返す
// 第３引数がなかった場合、_POST[第２引数]の存在を確認後、その値を返す
function chk($target=array(), $key=null, $value=null){
    if(!empty($target) and $key === null and $value === null){
        return $target;
    }elseif(!empty($target[$key]) and $value === null){
        return $target[$key];
    }
    elseif(!empty($target[$key]) and $target[$key] === $value){
        return true;
    }else{
        return false;
    }
}





// horizon
// ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*
// ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
// -*-☆-*-★-*-☆-*-★-*-☆-*-★-*-☆-*-★-*-☆-*-★-*-☆-*-★-*-☆-*-★-*-☆-*-★-*-

// ----------------------------------------------------------------------
// **********************************************************************
// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★:::::::::::...........
// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆::::::::::::::::................
// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆****************................
// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
// *--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*
// *-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*
// *:*☆*:*★*:*☆*:*★*:*☆*:*★*:*☆*:*★*:*☆*:*★*:*☆*:*★*:*☆*:*★*:*☆*:*★*:.
// *:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
// *:.*☆*:.*★*:.*☆*:.*★*:.*☆*:.*★*:.*☆*:.*★*:.*☆*:.*★*:.*☆*:.*★*:.*☆*:.*★*:.*☆*:.*★*:.*
// *•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*
// *•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*
// *•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*
// *•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*
// *•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*
// *•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*
// *•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*
// *•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*
// *•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*•-•*
// *--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*
