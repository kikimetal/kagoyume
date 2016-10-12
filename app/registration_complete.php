<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(REGISTRATION_COMPLETE); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>



<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->
<!-- ページ条件分岐 -->

<?php
$access_chk = false;
if(
    chk($_POST, "mode", "from_registration_confirm")
        and !empty($_SESSION["registration"]["ticket"])
            and !empty($_SESSION["registration"]["last_access_time"])
                and time() - $_SESSION["registration"]["last_access_time"] < 60*5){

    $access_chk = true;
    $confirm_list = $_SESSION["registration"]["user_info"];

    // DB INSERT !!
    $user = new Guest;
    $user->entry($confirm_list);

} // endif
unset($_SESSION["registration"]); // 登録に関するすべての情報を破棄 // もちろんチケットも失う
?>

<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->




<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("registratiom_complete"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(REGISTRATION_COMPLETE); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊登録完了＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk): ?>

                <p>以下の情報で登録しました！</p>
                <div class="space20px">
                    <p>名前: <?=$confirm_list["name"]?></p>
                    <p>パスワード: <?=$confirm_list["password"]?></p>
                    <p>めーる: <?=$confirm_list["mail"]?></p>
                    <p>じゅうしょ: <?=$confirm_list["address"]?></p>
                </div>

                <h4 class="space20px"><a href="<?=TOP?>">->トップへ戻る</a></h4>
                <h4 class="space20px"><a href="<?=LOGIN?>">->ログインする</a></h4>

            <?php else: ?>
                <p>不正アクセスです : トップページからやり直してください</p>
            <?php endif; ?>

        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
