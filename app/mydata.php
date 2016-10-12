<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(MYDATA); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>




<?php
$access_chk = false;
if(!empty($_SESSION["member"]) and !empty($_SESSION["login"])){
    $access_chk = true;
}
?>





<!-- ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:::::.:.:........................... -->
<?php
// どこから飛んできたか保存 // and リンク生成
$return_link = save_and_create_return_link();
?>
<!-- ..................:.:.:::::::☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:. -->



<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("mypage"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊マイページ＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk): ?>

                    <!-- 戻るボタン -->
                    <?php if($return_link): ?>
                        <p><a href="<?=$return_link?>"><button> 戻る </button></a></p>
                    <?php endif; ?>


                    <?php if(chk($_SESSION, "search")): ?>
                            <!-- 検索画面に戻るボタン -->
                            <form class="center space20px" action="<?=SEARCH?>" method="get">
                                <input type="hidden" name="mode" value="last_searched">
                                <button type="submit">検索ページに戻る</button>
                            </form>
                    <?php endif; ?>

            <?php else: ?>

                    <p>ここをみたくば、ログインしてください。</p>

            <?php endif; ?>

        </article>

        <!-- 確認用 -->
        <p>
            <br>現在のreturn_link:<br>
            <?php var_dump($return_link); ?>
        </p>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
