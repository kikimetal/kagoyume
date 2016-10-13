<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(BUY_COMPLETE); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>



<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->
<!-- ページ条件分岐 -->
<?php
$access_chk = false;
// if(chk($_SESSION["buy_complete_ticket"]) and $_SESSION["buy_complete_ticket"] < time() + 10): // refresh から１０秒以上どっかうろついたらタイムアウト
if(chk($_SESSION, "buy_complete_ticket") and $_SESSION["buy_complete_ticket"] < time() + 10 and chk($_SESSION, "login")): // １０秒どっかで遊んでたらタイムアウト
    // 購入完了手続き
    $access_chk = true;
    $item_list = $_SESSION["member_cart"][$_SESSION["member"]->userID];
    $shipping_type = $_SESSION["buy_shipping_type"];

    unset($_SESSION["buy_complete_ticket"]);
    unset($_SESSION["buy_shipping_type"]);
    // カートの中身を破棄
    $_SESSION["member_cart"][$_SESSION["member"]->userID] = array();

    $total = 0;
    $itemcode_list = array();
    foreach ($item_list as $item) {
        // 合計金額を出す
        $total += $item["price"];
        // 今回、購入履歴として保存しておくのはitemcode だけなので、それだけの配列を生成
        $itemcode_list[] = $item["itemcode"];
    }

    $buy_result = $_SESSION["member"]->buy($total, $itemcode_list, $shipping_type); // 購入
    // !!!!! 注意: この関数は内部でトランザクションを使用していないため、商品テーブルに商品が追加されないが、総購入金額だけ上書きされる場合あり！でも今はその記述は割愛！




elseif(chk($_POST, "mode", "from_buy_confirm")):
    $access_chk = "refresh";
    $_SESSION["buy_complete_ticket"] = time(); // チケット発行して、リフレッシュ
    $_SESSION["buy_shipping_type"] = $_POST["shipping_type"];

endif;
?>
<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->




<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("buy_complete"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(BUY_COMPLETE); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊購入完了＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk === "refresh"): ?>

                <meta http-equiv="refresh" content="2;<?=BUY_COMPLETE?>">
                <h3 class="space40px">購入処理中。しばらくお待ちください。</h3>

            <?php elseif($access_chk): ?>

                <?php if($buy_result === null): // null ならいろいろ成功している、袖なかったらエラーが入っている ?>
                <?php else: ?>
                    <p>DBアクセスエラーが発生しました。大変ご迷惑をおかけします。</p>
                    <p>エラー内容 : <?=$buy_result?></p>
                <?php endif; ?>

            <?php else: ?>
                <p>不正アクセスです : カートから購入手続きを踏んでください。</p>
            <?php endif; ?>

        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
