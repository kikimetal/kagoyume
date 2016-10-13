<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(MY_HISTORY); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>



<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->
<!-- ページ条件分岐 -->
<?php
$access_chk = false;
if(chk($_SESSION, "login") and chk($_SESSION, "member")):
    $access_chk = true;
    $my_history = null;

    $my_history = $_SESSION["member"]->get_my_history(); // 購入履歴（商品コード）を配列として受け取る。

endif;
?>
<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->




<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("my_history"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(MY_HISTORY); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊購入履歴＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk): ?>

                <h5 class="space20px">これまでに購入した商品</h5>

                <?php if(!$my_history): // 何もなかった ?>
                    <p class="space20px">まだおひとつも購入されていません。</p>
                <?php elseif(is_array($my_history)): // 配列なら検索成功 ?>

                    <div class="center" style="width:500px;">
                        <table>
                            <tr style="height:50px;">
                                <th>商品コード</th><th>発送方法</th><th>購入時期</th>
                            </tr>
                            <?php foreach ($my_history as $item): ?>
                                <tr>
                                    <td><?=h($item["itemCode"])?></td>
                                    <td><?=ex_shipping_type($item["type"])?></td>
                                    <td><?=h($item["buyDate"])?></td>
                                </tr>
                                <tr style="height:30px;"></tr>
                            <?php endforeach; ?>
                        </table>
                    </div>

                <?php else: // そうでないならエラー出してる ?>
                    <p>DBアクセスエラーです。申し訳ございません。</p>
                    <p>エラー内容 : <?=$my_history?></p>
                <?php endif; ?>

                <p class="space20px"><a href="<?=MY_DATA?>"><button>マイページに戻る</button></a></p>

            <?php else: ?>
                <p>ここをみたくば、ログインしてください (///)</p>
            <?php endif; ?>

        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
