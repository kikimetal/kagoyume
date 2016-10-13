<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(MY_UPDATE_RESULT); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>



<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->
<!-- ページ条件分岐 -->
<?php
$access_chk = false;
if(chk($_SESSION,"login") and chk($_SESSION,"my_update_ticket") and $_SESSION["my_update_ticket"] > time() - 10): // ５分でタイムアウト
    $access_chk = true;
    unset($_SESSION["my_update_ticket"]);

    // ユーザーの入力情報を受け取って、解放
    $user_info = $_SESSION["my_update_user_info"];
    unset($_SESSION["my_update_user_info"]);

    // メンバークラスのインスタンス内の各変数も変えてあげる
    $_SESSION["member"]->name = $user_info["name"];
    $_SESSION["member"]->password = $user_info["password"];
    $_SESSION["member"]->mail = $user_info["mail"];
    $_SESSION["member"]->address = $user_info["address"];

    $update_result = $_SESSION["member"]->update_info($user_info); // 成功なら null が帰ってくる エラーはエラー




elseif(chk($_SESSION,"login") and chk($_SESSION,"my_update_ticket")):
    $access_chk = "time_out";
    unset($_SESSION["my_update_ticket"]);

elseif(chk($_SESSION,"login") and chk($_SESSION,"member") and chk($_POST,"mode","from_my_data_update")):
    $access_chk = "refresh";
    $_SESSION["my_update_ticket"] = time(); // チケット発行

    // _POST で受け取った値を格納、からのstr"" が来たら、元の値を入れてあげる。
    $user_info["name"] = $_POST["name"]?$_POST["name"]:$_SESSION["member"]->name;
    $user_info["password"] = $_POST["password"]?$_POST["password"]:$_SESSION["member"]->password;
    $user_info["mail"] = $_POST["mail"]?$_POST["mail"]:$_SESSION["member"]->mail;
    $user_info["address"] = $_POST["address"]?$_POST["address"]:$_SESSION["member"]->address;

    $_SESSION["my_update_user_info"] = $user_info;

endif;
?>
<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->




<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("my_update_result"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(MY_UPDATE_RESULT); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊更新結果＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk === "refresh"): ?>

                <meta http-equiv="refresh" content="2;<?=MY_UPDATE_RESULT?>">
                <h3 class="space40px">データの更新中です。しばらくお待ちください。</h3>

            <?php elseif($access_chk === "time_out"): ?>

                <p class="space20px">セッションタイムアウト : 登録情報を変更したい場合はもう一度やり直してください。</p>
                <p class="space20px"><a href="<?=MY_DATA_UPDATE?>">登録情報変更ページはこちら。</a></p>

            <?php elseif($access_chk): ?>

                    <?php if(!$update_result): // アップデート成功！ ?>
                            <h5 class="space20px">登録情報を更新しました！</h5>
                            <p><a href="<?=MY_DATA?>">マイページで更新を確認するにはこちら。</a></p>
                    <?php else: // エラーが帰ってきた時 ?>
                            <p>DBアクセスエラーです。申し訳ございません。</p>
                            <p>エラー内容 : <?=$update_result?></p>
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
