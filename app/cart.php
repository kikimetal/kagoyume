<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(CART); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>



<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->
<?php
if(chk($_POST, "mode", "clear_cart")){
    if(empty($_SESSION["login"])){
        $_SESSION["guest"]["cart"] = array();
    }else{
        $_SESSION["member"]->cart = array();
    }
}

$item_list = array();

if(empty($_SESSION["login"])){
    $item_list = $_SESSION["guest"]["cart"];
}else{
    $item_list = $_SESSION["member"]->cart;
}


// ログイン関数の方でゲストカートの中身をごっそりメンバーカートに移すforeachで
?>
<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->




<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("cart"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(CART); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊カート＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <!-- <?php var_dump($_SESSION["guest"]["cart"]); ?> -->

            <?php if($item_list): ?>

                <div class="result_wrapper"><!-- 商品一覧が出てくる場所、画面白い領域 -->

                    <!-- 一覧 -->
                    <?php foreach ($item_list as $item): ?>
                        <div class="Item">
                            <div class="product_image">
                                <a href="item.php?itemcode=<?= h($item["itemcode"]); ?>">
                                    <img src="<?= h($item["image"]); ?>">
                                </a>
                            </div>
                            <div class="product_name">
                                <h4><a href="item.php?itemcode=<?= h($item["itemcode"]); ?>"><?= h($item["name"]); ?></a><br>おかね: <?php echo h($item["price"]); ?>円</h4>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

                <div class="space20px">
                    <form class="" action="<?=CART?>" method="post">
                        <input type="hidden" name="mode" value="clear_cart">
                        <button type="submit">カートをクリアする</button>
                    </form>
                </div>

            <?php else: ?>

                <p>カートは空っぽのようだ...</p>

            <?php endif; ?>


        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
