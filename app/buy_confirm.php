<!-- ○ 購入確認ページ
○ カートに追加順で商品 名前 (リンクなし )、金額が表示される
○ 合計金額が表示され、そ 下に発送方法を選択するラジオボタンがある。
○ 「上記 内容で購入する」ボタンと「カートに戻る」ボタンがある。 -->

<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(BUY_CONFIRM); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>



<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->
<!-- ページ条件分岐 -->
<?php
$access_chk = false;
if(chk($_SESSION, "buy_confirm_ticket") and $_SESSION["buy_confirm_ticket"] < time() + 10 and chk($_SESSION, "login")): // １０秒どっかで遊んでたらタイムアウト
    $access_chk = true;
    $item_list = $_SESSION["member_cart"][$_SESSION["member"]->userID];
    unset($_SESSION["buy_confirm_ticket"]);

    $total = 0;
    foreach ($item_list as $item) {
        $total += $item["price"];
    }

elseif(chk($_POST, "mode", "buy_from_cart") and chk($_SESSION, "login")): // ログイン時かつ正規手順
    $access_chk = "refresh";
    $_SESSION["buy_confirm_ticket"] = time(); // フォームのリロード連打の対策、チケット発行して、リフレッシュ

elseif(chk($_POST, "mode", "buy_from_cart")): // ログインされてない時、正規手順
    $access_chk = "plz_login";

endif;
?>
<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->




<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("buy_confirm"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(BUY_CONFIRM); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊購入確認＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk === "refresh"): ?>

                <meta http-equiv="refresh" content="3;<?=BUY_CONFIRM?>">
                <h3 class="space40px">カートの情報を取得中。しばらくお待ちください。</h3>

            <?php elseif($access_chk === "plz_login"): ?>

                <p>購入する際はログインしてください。</p>
                <p><a href="<?=LOGIN?>">->ログインはこちら</a></p>

            <?php elseif($access_chk): ?>

                <h5 class="space20px">以下の商品を購入してよろしいですか？</h5>

                <div class="center" style="width:600px;">
                    <table>
                        <tr style="height:50px;">
                            <th>商品名</th><th>ねだん</th>
                        </tr>
                        <?php foreach ($item_list as $item): ?>
                            <tr>
                                <td><?=h($item["name"])?></td><td><?=h($item["price"])?>円</td>
                            </tr>
                            <tr style="height:30px;"></tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <h5 class="space20px">合計金額: <?=$total?>円</h5>

                <div class="space20px">
                    <form class="space10px" action="<?=BUY_COMPLETE?>" method="post">
                        <p class="space10px">発送方法を選択してください。</p>
                        <p class="space10px">
                            <label><input type="radio" name="shipping_type" value="1" checked>クレジット</label>
                            <label><input type="radio" name="shipping_type" value="2">コンビニ振込</label>
                            <label><input type="radio" name="shipping_type" value="3">銀行振込</label>
                            <label><input type="radio" name="shipping_type" value="4">代引き</label>
                        </p>
                        <input type="hidden" name="mode" value="from_buy_confirm">
                        <p class="space10px"><button type="submit">この内容で購入する！</button></p>
                    </form>
                    <form class="space40px" action="<?=CART?>" method="post">
                        <button type="submit">カートに戻る</button>
                    </form>
                </div>


            <?php else: ?>
                <p>不正なアクセスです : カートの購入ボタンよりお越しください。</p>
            <?php endif; ?>

        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
