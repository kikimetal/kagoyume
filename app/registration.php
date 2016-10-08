<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output("registration.php"); // ログファイルに追記 // 引数のstringを追記、改行
?>



<!DOCTYPE html>
<html lang="ja">
    <?php session_start_anyway(); ?>
    <?php Html::head(); // head要素まるまる // 引数に<title>入力可能 // CSS読み込みもここ ?>
    <body>
        <?php Html::nav(); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる ?>
        <?php Html::header("＊新規ユーザー登録＊"); // 大見出し // 引数のstringを表示 // 第２引数にリンク先を追加可能 ?>

        <?php if(empty($_SESSION["login"])): // ログインしてない時 ?>

            <article class="center">
                <!-- ページメイン処理 -->
                <p class="space20px">登録情報を入力してね！</p>
                <form class="login" action="<?REGISTRATION_CONFIRM?>" method="post">
                    <p>ユーザーネーム: <input type="text" name="name" value="" placeholder="username"></p>
                    <p>パスワード: <input type="password" name="password" value="" placeholder="password"></p>
                    <p style="position:relative;right:37px;margin-top:10px;"><button type="submit">.:*☆とうろく☆*:.</button></p>
                    <input type="hidden" name="mode" value="from_registration">
                </form>
                <p class="space20px">
                    <a href="<?=LOGIN?>">->登録済みの方はこちらからログイン</a>
                </p>
            </article>

        <?php else: ?>

            <article class="center">
                <p>*別のユーザーを登録したい場合は一度ログアウトを行った上でお願いします。</p>
            </article>

        <?php endif; ?>
        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
