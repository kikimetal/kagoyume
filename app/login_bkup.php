<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(TOP."を閲覧");
?>

<!DOCTYPE html>
<html lang="ja">
    <?php Html::head(); ?>
    <body>
        <?php Html::nav(); ?>
        <?php Html::header("＊かごゆめ＊", TOP); ?>

        <article class="">
        </article>

        <aside class="design">
            <div class="wrapper"></div>
        </aside>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
        </footer>
    </body>
</html>
