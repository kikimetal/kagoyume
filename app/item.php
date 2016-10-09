<!--
○ 商品詳細ページ
○ serchまた cartから遷移できる。商品 IDをGETメソッドにより受け渡す
○ YahooショッピングAPIから取得したデータで、より詳細な情報 (概要や評価値)、が表示される
○ 「カートに追加する」ボタンがあり、クリックすると add.phpに遷移する。
-->

<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(ITEM); // ログファイルに追記 // 引数のstringを追記、改行
?>


<?php
// ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
// 受けとった_GETで商品コード検索
$access_chk = false; // 不正アクセスチェック

$hits = array();

if(!empty($_GET["itemcode"])):
    // SEARCHから_GETで受け取ったitemcodeで 商品コード検索
    $url = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemLookup?appid=$appid&image_size=300&itemcode=".$_GET["itemcode"];
    $xml = simplexml_load_file($url);
    $hits = $xml->Result->Hit;
    $access_chk = true; // きちんとクエリストリングがあった時
endif;
// ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
?>



<!DOCTYPE html>
<html lang="ja">
    <?php session_start_anyway(); ?>
    <?php Html::head(); // head要素まるまる // 引数に<title>入力可能 // CSS読み込みもここ ?>
    <body>
        <?php Html::nav(); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる ?>
        <?php Html::header("＊詳細＊"); // 大見出し // 引数のstringを表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk): ?>

                <div class="result_wrapper">
                    <p><h5><?= $hits[0]->Name; ?></h5></p>
                    <div class="center"><img src=<?= $hits[0]->ExImage->Url; ?>></div>
                </div>
                <!-- 検索画面に戻るボタン -->
                <form class="center space20px" action="search.php" method="post">
                    <input type="hidden" name="mode" value="return_search">
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
