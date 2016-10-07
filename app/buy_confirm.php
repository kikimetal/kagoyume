<!-- ○ 購入確認ページ
○ カートに追加順で商品 名前 (リンクなし )、金額が表示される
○ 合計金額が表示され、そ 下に発送方法を選択するラジオボタンがある。
○ 「上記 内容で購入する」ボタンと「カートに戻る」ボタンがある。 -->

<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output("buy_confirm.php"); // ログファイルに追記 // 引数のstringを追記、改行
?>

<!DOCTYPE html>
<html lang="ja">
    <?php session_start_anyway(); ?>
    <?php Html::head(); // head要素まるまる // 引数に<title>入力可能 // CSS読み込みもここ ?>
    <body>
        <?php Html::nav(); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる ?>
        <?php Html::header("＊購入確認ページ＊"); // 大見出し // 引数のstringを表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center">
            <!-- ページメイン処理 -->
            ここに処理
        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
