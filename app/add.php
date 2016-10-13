<!--
○ カートに追加ページ
○ 商品 情報を受け取り、クッキーやセッションに追加する
○ 画面に 「カートに追加しました」と表示
-->
<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(ADD); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>



<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->
<?php
$access_chk = false;
if(chk($_POST, "mode", "from_item")){
    $access_chk = true;
    $item["itemcode"] = chk($_POST, "itemcode");
    $item["name"] = chk($_POST, "name");
    $item["image"] = chk($_POST, "image");
    $item["price"] = chk($_POST, "price");

    if(!empty($_SESSION["login"])){
        $_SESSION["member_cart"][$_SESSION["member"]->userID][] = $item;
    }else{
        $_SESSION["guest_cart"][] = $item; // これで連続して　かつ常に次の要素番号に保存される
    }

    // すでに追加済みです！の分岐処理があると良いか？
}
// var_dump($_SESSION["guest_cart"]);
?>
<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->




<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("add"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(ADD); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊追加確認＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk): ?>

                <p>カートに追加しました！</p>

                <!-- 詳細画面に戻るボタン -->
                <form class="center space20px" action="<?=ITEM?>" method="get">
                    <input type="hidden" name="itemcode" value="<?=$_SESSION["last_searched_itemcode"];?>">
                    <button type="submit">商品詳細に戻る</button>
                </form>

                <!-- 検索画面に戻るボタン -->
                <form class="center space20px" action="<?=SEARCH?>" method="get">
                    <input type="hidden" name="mode" value="last_searched">
                    <button type="submit">検索ページに戻る</button>
                </form>


            <?php else: ?>
                <p>商品検索を行った上で商品を選択してお越しください。</p>
                <p><a href="<?=SEARCH?>">商品検索ページはこちら。</a></p>
            <?php endif; ?>

        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
