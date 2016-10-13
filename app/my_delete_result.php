<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(MY_DELETE_RESULT); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>



<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->
<!-- ページ条件分岐 -->
<?php
$access_chk = false;
if(chk($_SESSION, "my_delete_ticket") and $_SESSION["my_delete_ticket"] < time() + 10 and chk($_SESSION, "login")): // １０秒どっかで遊んでたらタイムアウト
    $access_chk = true;
    unset($_SESSION["my_delete_ticket"]); // チケット消費

    // ユーザー削除
    $delete_result = $_SESSION["member"]->delete_me(); // エラーでエラーが帰る

    if(!$delete_result): // 成功なら
        $_SESSION["member_cart"][$_SESSION["member"]->userID] = null;
        $_SESSION["member"] = null;
        $_SESSION["login"] = false;
    endif;


elseif(chk($_POST, "mode", "from_my_delete") and chk($_SESSION, "login")):
    $access_chk = "refresh";
    $_SESSION["my_delete_ticket"] = time();

endif;
?>
<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->




<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("my_delete_result"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(MY_DELETE_RESULT); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊退会完了＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk === "refresh"): ?>
                <meta http-equiv="refresh" content="2;<?=MY_DELETE_RESULT?>">
                <h3 class="space40px">データを削除中です...</h3>
            <?php elseif($access_chk): ?>

                <?php if(!$delete_result): ?>
                    <p>退会が完了しました。</p>
                <?php else: ?>
                    <p>DBアクセスエラーが発生しました。申し訳ございません。退会が完了していない恐れがあります。</p>
                    <p>エラー内容 : <?=$delete_result?></p>
                <?php endif; ?>

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
