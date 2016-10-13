<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(MY_DATA); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>


<?php
$access_chk = false;
if(chk($_SESSION, "login") and chk($_SESSION, "member")):
    $access_chk = true;
endif;
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
        <?php Html::nav(MY_DATA); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊マイページ＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk): ?>

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
                        <form class="" action="<?=MY_DATA_UPDATE?>" method="post">
                            <input type="hidden" name="mode" value="from_my_data">
                            <button type="submit">登録情報を変更する</button>
                        </form>
                    </div>
                    <div class="space20px">
                        <form class="" action="<?=MY_DELETE?>" method="post">
                            <input type="hidden" name="mode" value="from_my_data">
                            <button type="submit">退会(削除)する</button>
                        </form>
                    </div>
                    <div class="space20px">
                        <form class="" action="<?=MY_HISTORY?>" method="post">
                            <!-- <input type="hidden" name="mode" value="from_my_data"> -->
                            <button type="submit">購入履歴を確認する</button>
                        </form>
                    </div>


                    <?php Html::hr() ?>



                    <!-- 戻るボタン -->
                    <?php if($return_link): ?>
                        <p class="right"><a href="<?=$return_link?>"><button> 戻る </button></a></p>
                    <?php endif; ?>


                    <?php if(chk($_SESSION, "search")): ?>
                            <!-- 検索画面に戻るボタン -->
                            <form class="right space20px" action="<?=SEARCH?>" method="get">
                                <input type="hidden" name="mode" value="last_searched">
                                <button type="submit">検索ページに戻る</button>
                            </form>
                    <?php endif; ?>

            <?php else: ?>

                    <p>ここをみたくば、ログインしてください。</p>

            <?php endif; ?>

        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
