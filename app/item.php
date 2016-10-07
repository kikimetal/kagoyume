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
Log::output("item.php");
?>

<?php // 受けとった_GETで商品コード検索
$access_chk = false; // 不正アクセスチェック

$hits = array();

if(!empty($_GET["itemcode"])):
    $url = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemLookup?appid=$appid&image_size=300&itemcode=".$_GET["itemcode"];
    $xml = simplexml_load_file($url);
    $hits = $xml->Result->Hit;
    $access_chk = true; // きちんとクエリストリングがあった時
endif;
?>

<!DOCTYPE html>
<html lang="ja">
    <?php Html::head(); ?>
    <body>

        <?php Html::nav(); ?>

        <header>
            <h3>商品詳細っ</h3>
        </header>

        <article>
            <p><h5><?= $hits[0]->Name; ?></h5></p>
            <div class="center"><img src=<?= $hits[0]->ExImage->Url; ?>></div>

        <!-- 検索画面に戻るボタン -->
        <form class="center space20px" action="search.php" method="post">
            <input type="hidden" name="mode" value="return_search">
            <button type="submit">検索ページに戻る</button>
        </form>

        </article>

        <footer><?php Html::return_top(); ?></footer>
        <?php Html::address(); ?>
    </body>
</html>
