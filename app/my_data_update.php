<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(MY_DATA_UPDATE); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>



<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->
<!-- ページ条件分岐 -->
<?php
$access_chk = false;
if(chk($_SESSION, "login") and chk($_SESSION, "member")):

    $access_chk = true;

endif;
?>
<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->


<!-- ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:. -->
<?php
// フォーム初期値
$name = $_SESSION["member"]->name;
$password = $_SESSION["member"]->password;
$mail = $_SESSION["member"]->mail;
$address = $_SESSION["member"]->address;


?>
<!-- ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:. -->






<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("my_data_update"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(MY_DATA_UPDATE); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊登録情報変更＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk): ?>

                <p class="space20px">変更したいところ変えてね。</p>
                <p class="space20px">未入力にしちゃったら更新しないからね。</p>
                <form class="login" action="<?=MY_UPDATE_RESULT?>" method="post">
                    <p>ユーザーネーム: <input type="text" name="name" value="<?=$name?>" placeholder="username"></p>
                    <p>パスワード: <input type="password" name="password" value="<?=$password?>" placeholder="password"></p>
                    <p>めーる: <input type="text" name="mail" value="<?=$mail?>" placeholder="kago@yume.com"></p>
                    <p>じゅうしょ: <input type="text" name="address" value="<?=$address?>" placeholder="address"></p>

                    <input type="hidden" name="mode" value="from_my_data_update">

                    <p style="position:relative;right:69px;margin-top:10px;"><button type="submit">.:*☆変更☆*:.</button></p>
                </form>

            <?php else: ?>
                <p>不正なアクセスです : トップページからやり直してください。</p>
            <?php endif; ?>

        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
