<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(REGISTRATION); // ログファイルに追記 // 引数のstringを追記、改行
session_start_anyway();
?>



<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->

<?php
// 初期化
$user_info = array();
$unans_flg = array();
$i_color["name"] = null;
$i_color["password"] = null;
$i_color["mail"] = null;
$i_color["address"] = null;

$p_color["name"] = null;
$p_color["password"] = null;
$p_color["mail"] = null;
$p_color["address"] = null;

// フォーム初期値
$name = null;
$password = null;
$mail = null;
$address = null;

if(chk($_POST, "mode", "from_registration_confirm")):
    // <!-- confirm から戻ってきた時の 未入力リストへの処理 -->

    $unans_flg = $_SESSION["registration"]["user_unanswered_flg"];
    foreach ($unans_flg as $key => $value) {
        if($value){
            $i_color[$key] = "style='border:1px solid crimson;'";
            $p_color[$key] = "style='color:crimson'";
        }
    }


    $user_info = $_SESSION["registration"]["user_info"];

    foreach($user_info as $key => $value){
        $$key = $value;
    }

endif;

// $_SESSION["registration"]["user_unanswered_flg"] = null; // 未入力記憶のリセット
// $_SESSION["registration"]["user_info"] = null;
?>
<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->




<!DOCTYPE html>
<html lang="ja">
    <?php session_start_anyway(); ?>
    <?php Html::head("registration"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込みもここ ?>
    <body>
        <?php Html::nav(REGISTRATION); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊新規ユーザー登録＊"); // 大見出し // 引数のstringを表示 // 第２引数にリンク先を追加可能 ?>

        <?php if(empty($_SESSION["login"])): // ログインしてない時 ?>

            <article class="center">
                <!-- ページメイン処理 -->
                <p class="space20px">登録情報を入力してね！</p>

                <form class="login" action="<?=REGISTRATION_CONFIRM?>" method="post">
                    <p <?=$p_color["name"]?>>ユーザーネーム: <input <?=$i_color["name"]?> type="text" name="name" value="<?=$name?>" placeholder="username"></p>
                    <p <?=$p_color["password"]?>>パスワード: <input <?=$i_color["password"]?> type="password" name="password" value="<?=$password?>" placeholder="password"></p>
                    <p <?=$p_color["mail"]?>>めーる: <input <?=$i_color["mail"]?> type="text" name="mail" value="<?=$mail?>" placeholder="kago@yume.com"></p>
                    <p <?=$p_color["address"]?>>じゅうしょ: <input <?=$i_color["address"]?> type="text" name="address" value="<?=$address?>" placeholder="address"></p>

                    <input type="hidden" name="mode" value="from_registration">

                    <p style="position:relative;right:37px;margin-top:10px;"><button type="submit">.:*☆とうろく☆*:.</button></p>
                </form>

                <p class="space20px">
                    <a href="<?=LOGIN?>">->登録済みの方はこちらからログイン</a>
                </p>


                <?php if(chk($_SESSION, "search")): ?>
                        <!-- 検索画面に戻るボタン -->
                        <form class="center space20px" action="<?=SEARCH?>" method="get">
                            <input type="hidden" name="mode" value="last_searched">
                            <button type="submit">検索ページに戻る</button>
                        </form>
                <?php endif; ?>

            </article>


        <?php else: // ログインしてるのに無理やりダイレクトリンクで来た時 ?>

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
