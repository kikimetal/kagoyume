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
if(chk($_POST, "mode", "clear_cart")){ // カートからすべてクリア、配列丸ごと解放
    if(empty($_SESSION["login"])){
        $_SESSION["guest_cart"] = array();
    }else{
        $_SESSION["member_cart"][$_SESSION["member"]->userID] = array();
    }
}elseif(chk($_POST, "mode", "delete_one_from_cart")){ // カートから１点削除、配列のキー指定で変数解放
    if(empty($_SESSION["login"])){
        unset($_SESSION["guest_cart"][$_POST["delete_key"]]);
    }else{
        unset($_SESSION["member_cart"][$_SESSION["member"]->userID][$_POST["delete_key"]]);
    }
}

$item_list = array();

if(empty($_SESSION["login"])){
    $item_list = $_SESSION["guest_cart"];
}else{
    $item_list = $_SESSION["member_cart"][$_SESSION["member"]->userID];
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

            <!-- <?php var_dump($_SESSION["guest_cart"]); ?> -->

            <?php if($item_list): ?>

                <div class="result_wrapper"><!-- 商品一覧が出てくる場所、画面白い領域 -->

                    <!-- 一覧 -->
                    <?php foreach ($item_list as $key => $item): ?>
                        <div class="Item">
                            <div class="product_image">
                                <a href="item.php?itemcode=<?= h($item["itemcode"]); ?>">
                                    <img src="<?= h($item["image"]); ?>">
                                </a>
                            </div>
                            <div class="product_name">
                                <h4><a href="item.php?itemcode=<?= h($item["itemcode"]); ?>"><?= h($item["name"]); ?></a><br>おかね: <?php echo h($item["price"]); ?>円</h4>
                                <form class="" action="<?=CART?>" method="post">
                                    <input type="hidden" name="mode" value="delete_one_from_cart">
                                    <input type="hidden" name="delete_key" value="<?=$key?>">
                                    <button type="submit">カートから削除</button>
                                    <!-- 削除確認とか盛り込んでる暇なし -->
                                </form>
                            </div>
                        </div><!-- Item -->
                    <?php endforeach; ?>

                    <div class="center">
                        <form class="space20px" action="<?=BUY_CONFIRM?>" method="post">
                            <input type="hidden" name="mode" value="buy_from_cart">
                            <button type="submit">買っちゃう</button>
                        </form>
                    </div>

                </div><!-- result_wrapper -->

                <div class="space20px">
                    <form class="" action="<?=CART?>" method="post">
                        <input type="hidden" name="mode" value="clear_cart">
                        <button type="submit">カートをすべてクリアする</button>
                    </form>
                </div>

            <?php else: ?>

                <p>カートは空っぽのようだ...</p>

            <?php endif; ?>


            <?php Html::hr() ?>


            <?php if(chk($_SESSION, "search")): ?>
                    <!-- 検索画面に戻るボタン -->
                    <form class="right space20px" action="<?=SEARCH?>" method="get">
                        <input type="hidden" name="mode" value="last_searched">
                        <button type="submit">検索ページに戻る</button>
                    </form>
            <?php endif; ?>


        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
