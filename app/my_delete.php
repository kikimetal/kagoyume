<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(MY_DELETE); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
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




<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("my_delete"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(MY_DELETE); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊退会確認＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk): ?>

                <h5 class="space20px">本当に退会しますか？</h5>

                <!-- ここに[member]->中身のリスト表示 -->
                <h4 class="space20px">あなたの情報</h4>
                <div style="position:relative;left:40px;width:300px;text-align:left;margin:auto;">
                    <ul style="font-size:110%;margin:auto;">
                        <li style="margin:20px;"><p>＊名前</p><p style="position:relative;left:26px;"><?=$_SESSION["member"]->name?></p></li>
                        <li style="margin:20px;"><p>＊めーる</p><p style="position:relative;left:26px;"><?=$_SESSION["member"]->mail?></p></li>
                        <li style="margin:20px;"><p>＊じゅうしょ</p><p style="position:relative;left:26px;"><?=$_SESSION["member"]->address?></p></li>
                        <li style="margin:20px;"><p>＊使ってしまったお金</p><p style="position:relative;left:26px;"><?=$_SESSION["member"]->total?$_SESSION["member"]->total:0?>円</p></li>
                    </ul>
                </div>

                <div class="space20px">
                    <form class="" action="<?=MY_DELETE_RESULT?>" method="post">
                        <input type="hidden" name="mode" value="from_my_delete">
                        <button type="submit">退会する</button>
                    </form>
                </div>
                <div class="space20px">
                    <form class="" action="<?=MY_DATA?>" method="post">
                        <input type="hidden" name="mode" value="from_my_delete">
                        <button type="submit">いいえ、マイページに戻る</button>
                    </form>
                </div>

            <?php else: ?>
                <p class="space20px">ここを見たくば、ログインしてください。</p>
            <?php endif; ?>

        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
