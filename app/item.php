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
session_start_anyway();
?>


<?php
// ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
// 受けとった_GETで商品コード検索
$access_chk = false; // 不正アクセスチェック

$hit = array();

if(!empty($_GET["itemcode"])):
    // SEARCHから_GETで受け取ったitemcodeで 商品コード検索
    $url = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemLookup?appid=$appid&image_size=300&itemcode=".$_GET["itemcode"]."&responsegroup=medium";
    $xml = simplexml_load_file($url);
    $hit = $xml->Result->Hit[0];
    $access_chk = true; // きちんとクエリストリングがあった時

    // ここからログインへ飛んでも、帰ってこれるように保存
    $_SESSION["last_searched_itemcode"] = $_GET["itemcode"];
endif;
// ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.
?>



<!DOCTYPE html>
<html lang="ja">
    <?php session_start_anyway(); ?>
    <?php Html::head("item"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込みもここ ?>
    <body>
        <?php Html::nav(ITEM); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊詳細＊"); // 大見出し // 引数のstringを表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk): ?>

                <div class="result_wrapper space20px">
                    <p><h5><?= h($hit->Name); ?></h5></p>
                    <p class="space20px">★ おかね: <?=h($hit->Price);?> 円 ★</p>
                    <div class="center"><img src=<?= h($hit->ExImage->Url); ?>></div>
                    <p><?= h($hit->Description); ?></p>
                    <p class="space20px">★ レビュー平均評価: <?= h($hit->Review->Rate); ?> ★</p>
                </div>

                <form class="space20px" action="<?=ADD?>" method="post">
                    <input type="hidden" name="itemcode" value="<?=$_SESSION["last_searched_itemcode"]?>">
                    <input type="hidden" name="name" value="<?= h($hit->Name); ?>">
                    <input type="hidden" name="image" value="<?= h($hit->Image->Medium); ?>">
                    <input type="hidden" name="price" value="<?= h($hit->Price); ?>">
                    <input type="hidden" name="mode" value="from_item">
                    <button type="sybmit">★カートに追加する★</button>
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
