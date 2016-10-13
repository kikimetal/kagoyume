<!--
○ トップページ。ルート ここである
○ こ システム 簡単な説明が記載されている。テキスト 自由
○ キーワード検索フォームが設置されている。検索 遷移先  searchで、GETメソッド。未入力
ならエラーを表示
-->

<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output("top.php");
?>



<!DOCTYPE html>
<html lang="ja">
    <?php Html::head() ?>
    <body>
        <?php Html::nav(TOP) ?>
        <?php Html::header("&nbsp;今日は何買う？", TOP) ?>

        <article class="">
        <!-- ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:. -->
            <!-- ヤフー検索フォーム -->
                <form class="yahoo_api right border" action="<?=SEARCH?>" class="Search">
                    <p>
                        表示順序:
                        <select name="sort">
                            <?php foreach ($sortOrder as $key => $value) { ?>
                                <option value="<?php echo h($key); ?>"><?php echo h($value);?></option>
                            <?php } ?>
                        </select>
                    </p>
                    <p>
                        カテゴリ:
                        <select name="category_id">
                            <?php foreach ($categories as $id => $name) { ?>
                                <option value="<?php echo h($id); ?>"><?php echo h($name);?></option>
                            <?php } ?>
                        </select>
                    </p>
                    <p>キーワード検索：<input type="text" name="query" value="" placeholder="キーワード"/></p>
                    <p><button type="submit">yahoo ショッピング検索ー！</button>
                </form>
        <!-- ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:. -->
        </article>

        <!-- 確認用 -->
        <!-- <?php echo "ログイン状況: ";var_dump(chk($_SESSION, "member")); ?>
        <?php if(!chk($_SESSION, "member")){echo BR,"now user is Guest";} ?>
        <?php var_dump($_SESSION["member"]->name); ?>
        <?php var_dump($_SESSION["login"]); ?> -->

        <footer>
            <!-- <?php Html::return_top(); ?> -->
            <p><a>＊かごゆめ＊</a></p>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
