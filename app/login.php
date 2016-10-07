<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output("login.php"); // ログファイルに追記 // 引数のstringを追記、改行
?>

<!DOCTYPE html>
<html lang="ja">
    <?php session_start_anyway(); ?>
    <?php Html::head(); // head要素まるまる // 引数に<title>入力可能 // CSS読み込みもここ ?>
    <body>
        <?php Html::nav(); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる ?>
        <?php Html::header("＊ログイン＊"); // 大見出し // 引数のstringを表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center">
            <!-- ページメイン処理 -->
            <form class="login" action="<?=LOGIN?>" method="post">
                <p>ユーザーネーム: <input type="text" name="user_name" value=""></p>
                <p>パスワード: <input type="password" name="user_password" value=""></p>
                <button type="submit">ログインっ</button>
            </form>

            <div class="space20px"> </div>

            <p class="space20px">
                <a href="<?=REGISTRATION?>">->新規会員登録はこちら</a>
            </p>
        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
