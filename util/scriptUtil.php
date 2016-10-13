<?php
require_once "defineUtil.php";

// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★----------------------
// なるべくクラスを利用して慣れていくスタイル
// --------------------☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆

// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★----------------------
// ゲスト含む全ユーザー
abstract class User {
    // やっぱやめた...
}


// 会員
class Member extends User {

    public $userID;
    public $name;
    public $mail;
    public $address;
    public $total;
    public $password;

    public function __construct($array=array()) { // 引数にDBから拾ったレコード array[0] を入れてあげること！
        $this->userID = chk($array, "userID");
        $this->name = chk($array, "name");
        $this->mail = chk($array, "mail");
        $this->address = chk($array, "address");
        $this->total = chk($array, "total");
        $this->password = chk($array, "password");
    }
    // ログインユーザーのログアウト処理 は _SESSION = array() で済むから関数いらない


    public function buy($total, $itemcode_list, $shipping_type){
        // 返り値となるエラー
        $error = null;
        // メンバークラスのインスタンス内の総購入金額を更新
        $this->total += $total;

        $db_access = new DBaccess;
        // DBの総購入金額の更新  ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
        $result = $db_access->update("user_t", "total", $this->total, "userID", $this->userID);
        // ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
        if($result){ // エラーならエラーが返ってきてる
            return $result;
        }

        // 商品購入履歴の追加  ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*
        $insert_params_arr = array();
        foreach ($itemcode_list as $itemcode):
            $insert_params_arr[] = array(
                                "userID" => $_SESSION["member"]->userID,
                                "itemCode" => $itemcode,
                                "type" => $shipping_type
                                );
        endforeach;

        $insert_result = array();
        foreach ($insert_params_arr as $insert_params):
            $insert_result[] = $db_access->insert("buy_t", $insert_params, "buyDate");
            // DBアクセスが連続で複数回行われる、これは一括して１回のアクセスで挿入できるようにしたいが、も少し時間が必要、やればできるので今回は割愛
        endforeach;

        foreach ($insert_result as $key => $value):
            if($value){
                return $value; // 挿入中にエラーが出た場合 // エラ−最初の１個しか返せないんだけど今回は許して...
            }
        endforeach; // ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*

        return null; // 諸々書き込み成功で null を返す
    }

    // 購入履歴ゲット
    public function get_my_history(){
        $arr = array("userID" => $this->userID);
        $db_access = new DBaccess;
        $result = $db_access->select("buy_t", $arr, "itemCode, type, buyDate");
        return $result; // 成功すれば配列を、エラーでエラーを返す
    }

    public function delete_me(){
        $db_access = new DBaccess;
        $result = $db_access->update("user_t", "deleteFlg", 1, "userID", $this->userID);
        return $result;
    }

    public function update_info($arr=array()){
        $db_access = new DBaccess;
        foreach ($arr as $key => $value){
            $result = $db_access->update("user_t", $key, $value, "userID", $this->userID);
            if($result){
                return $result;
            }
        }
        return $result;
    }

} // class Member

// ゲストさん
class Guest extends User {
    // ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
    // 引数に与えるユーザーネームとパスワードでログインを試みる
    // 成功で true を返し、$_SESSION["login"] = true
    // 失敗で エラーメッセージを返す
    public function login($name, $password) {
        $params = array("name" => $name, "password" => $password);
        $db_access = new DBaccess;
        $result = $db_access->select("user_t", $params); // 未ヒットで空の配列、ヒットで配列、エラーでPDOEception が帰ってくる

        // var_dump($result);

        if(empty($result)){
            return false;
        }elseif(is_array($result)){
            if($result[0]["deleteFlg"] != 0){
                return false;
            }else{
                session_start_anyway();
                $_SESSION["member"] = new Member($result[0]);
                return true;
            }
        }else{
            return $result;
        }
    }
    // ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.


    // 登録したい カラム名 => 値 の配列を渡す
    // 挿入成功で null エラーでエラーを返す
    public function entry($array) {
        $db_access = new DBaccess;
        $result = $db_access->insert("user_t", $array, "newDate");

        return $result;
    }
} // class Guest
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
    // nav // 第１引数に現在ページ(define済みの定数でURL表記, e.g)ITEM, SEARCH)を入れてあげると、ログインフォームへジャンプする際に_GETでどのページから来たかを付与する
    public static function nav($page=null) {
        session_start_anyway();
        if(empty($_SESSION["login"]) and empty($_SESSION["member"])){ // login===falseだったら
            ?>
            <nav class="center guest_nav">
                <ul>
                    <li><a href="<?=CART?>"><div>ようこそ! ゲストさん!(カート)</div></a></li>
                    <li><a href="<?=REGISTRATION?>"><div>新規登録</div></a></li>
                    <li><a href="<?=LOGIN?><?php if($page){echo "?from=".$page;} ?>"><div>ログイン</div></a></li>
                </ul>
            </nav>
            <?php
        }else{ // login===trueだったら
            ?>
            <nav class="center user_nav">
                <ul>
                    <li><a href="<?=CART?>"><div>ようこそ! <?= $_SESSION["member"]->name ?>さん!(カート)</div></a></li>
                    <li><a href="<?=MY_DATA?><?php if($page){echo "?from=".$page;} ?>"><div>マイページ</div></a></li>
                    <li><a href="<?=LOGIN?><?php if($page){echo "?from=".$page;} ?>"><div>ログアウト</div></a></li>
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
    public static function address(){
        ?>
        <address class="">
            2016 all rights reserved kikimetal.com from little-twin-stars.
        </address>
        <?php
    }
    public static function wrapper(){
        ?>
        <aside class="design">
            <div class="wrapper"></div>
        </aside>
        <?php
    }

    public static function hr(){
        ?>
        <div class="hr space20px"></div>
        <?php
    }
}

// ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★----------------------
// 引数に入れられたstringをlog.txt へ追記。$str末尾で改行。
class Log {
    public static function output($str, $str02=null) {
        session_start_anyway();
        if(!empty($_SESSION["login"]) and !empty($_SESSION["member"]->name)){
            $name = $_SESSION["member"]->name;
        }else{
            $name = "Guest";
        }
        $log = "[".date("Y-m-d H:i:s", time())."][user:".$name."][access:".$str."]".$str02;
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
        session_set_cookie_params(60 * 60 * 24 * 7); // 有効期限は７日間
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
    if(!empty($target[$key]) and $value === null){
        return $target[$key];
    }elseif(!empty($target[$key]) and $target[$key] === $value){
        return true;
    }else{
        return false;
    }
}


// ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:::::.:.:...........................
function save_and_create_return_link(){ // クエリストリングの ?from= をチェックし、場合分けしてurl生成
    if(chk($_GET, "from")){
        $_SESSION["from_page"] = $_GET["from"];
    }
    $page = chk($_SESSION, "from_page");
    switch ($page):
            case SEARCH:
                    $link = SEARCH."?mode=last_searched";
                    break;
            case ITEM:
                    $link = ITEM."?itemcode=".$_SESSION["last_searched_itemcode"];
                    break;
            case CART:
            case MY_DATA:
            case MY_HISTORY:
                    $link = $page;
                    break;


            // case REGISTRATION:
            // case REGISTRATION_CONFIRM:
            // case REGISTRATION_COMPLETE:
            // case TOP:
            //         $link = TOP;
            //         break;
            // case true:
            //         $link = $page;
            //         break;
            default:
                    $link = TOP;
                    break;
    endswitch;
    return $link;
}
// ..................:.:.:::::::☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.

function ex_shipping_type($num){
    switch ($num) {
        case 1:
            return "クレジット";
            break;
        case 2:
            return "コンビニ振込";
            break;
        case 3:
            return "銀行振込";
            break;
        case 4:
            return "代引き";
            break;
        default:
            return "error";
            break;
    }
}


// すぺしゃるきゃらずはえっち
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
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

// ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:::::.:.:...........................
    // ここに処理
// ..................:.:.:::::::☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
