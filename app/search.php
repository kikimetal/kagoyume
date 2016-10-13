<!--
検索結果ページ
○ topから検索により遷移できる。 YahooショッピングAPIに直接検索キーワードを渡し、そ 結 果を受け取り&表示している
○ 検索キーワード、検索結果数を表示
○ 縦 リスト型に表示。サムネイルと、そ 横に商品名、金額が載っている。クリックで itemへ
○ 結果 上位 10件まで
-->
<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
session_start_anyway();
Log::output("search.php");
?>

<?php
// ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:::::.:.:...........................
// ページアクセス時の分岐

if(chk($_POST, "mode", "change_offset")): // このページで次の１０件前の１０件を押してきた時

    $query = $_SESSION["search"]["query"];
    $sort = $_SESSION["search"]["sort"];
    $category_id = $_SESSION["search"]["category_id"];
    // 検索結果の調節 // セッションにも格納しておく
    if($_POST["change_value"] === "back"){
        $_SESSION["search"]["offset"] = $_POST["now_offset"] - 10;
    }else{
        $_SESSION["search"]["offset"] = $_POST["now_offset"] + 10;
    }
    $offset = $_SESSION["search"]["offset"];

elseif(chk($_GET, "mode", "last_searched")): // ログインページからの帰還 // アイテム詳細ページからの帰還

    $query = $_SESSION["search"]["query"];
    $sort = $_SESSION["search"]["sort"];
    $category_id = $_SESSION["search"]["category_id"];
    $offset = $_SESSION["search"]["offset"]; // 表示結果の調節

else: // 普通にアクセスした時

    $query = !empty($_GET["query"]) ? $_GET["query"] : "";
    $sort =  !empty($_GET["sort"]) && array_key_exists($_GET["sort"], $sortOrder) ? $_GET["sort"] : "-score";
    $category_id = !empty($_GET["category_id"]) && ctype_digit($_GET["category_id"]) && array_key_exists($_GET["category_id"], $categories) ? $_GET["category_id"] : 1;

    // _SESSIONに保存して、item.php とかに行っても検索ワードとか保持
    $_SESSION["search"]["query"] = $query;
    $_SESSION["search"]["sort"] = $sort;
    $_SESSION["search"]["category_id"] = $category_id;
    $offset = 0; // 表示結果の調節
    $_SESSION["search"]["offset"] = $offset;

endif;
// ..................:.:.:::::::☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.







// 上の分岐の結果の値たちを、URLにまとめて YahooAPIに渡す
$hits = array();
$hits_flg = false; // 検索に成功したかどうか trueで成功

if($query == ""):
// 検索キーワードが未入力で検索かけた時
    $hits = "no_search_word";
else:
// 検索キーワードが入っていた時
    $query4url = rawurlencode($query);
    $sort4url = rawurlencode($sort);
    // 商品検索--v
    $url = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemSearch?appid=$appid&query=$query4url&category_id=$category_id&sort=$sort4url&hits=10&offset=$offset";
    // 商品コード検索--v
    // $url = "http://shopping.yahooapis.jp/ShoppingWebService/V1/itemLookup?appid=$appid&itemcode=choro_IPL-2PS&image_size=600";

        // 確認用
        // var_dump($url);

    $xml = simplexml_load_file($url);
    // サーチ条件を保存しておく // ログイン後に戻ってくる時に使ってあげる


    // 上のどっちかで出てきた$xml に対して結果を導く分岐処理
    if($xml["totalResultsReturned"] != 0){ // 検索件数が0件でない場合,変数$hitsに検索結果を格納します。
        $hits = $xml->Result->Hit;
        $hits_flg = true; // 検索成功！
    }else{
        $hits = "no_hit"; // 検索結果が 0件
    }
endif;
?>







<!doctype html>
<html lang="ja">
    <?php session_start_anyway(); ?>
    <?php Html::head(); // head要素まるまる // 引数に<title>入力可能 // CSS読み込みもここ ?>
    <body>
        <?php Html::nav(SEARCH); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊かごゆめ - 商品検索＊"); // 大見出し // 引数のstringを表示 // 第２引数にリンク先を追加可能 ?>
        <article>
            <!-- <p>$url: <br> <?php if(!empty($url)){echo $url;} ?></p> -->

        <!-- ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★---------------------- -->
        <!-- ヤフー検索フォーム -->
            <form class="yahoo_api right border" action="<?=SEARCH?>" class="Search" method="get">
                <p>
                    表示順序:
                    <select name="sort">
                        <?php foreach($sortOrder as $key => $value): ?>
                            <option value="<?php echo h($key); ?>" <?php if($sort == $key) echo "selected"; ?>><?php echo h($value);?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    カテゴリ:
                    <select name="category_id">
                        <?php foreach($categories as $id => $name): ?>
                            <option value="<?php echo h($id); ?>" <?php if($category_id == $id) echo "selected"; ?>><?php echo h($name);?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>キーワード検索：<input type="text" name="query" value="<?php echo h($query); ?>"/></p>
                <p><button type="submit">yahoo ショッピング検索ー！</button>
            </form>
        <!-- ☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★☆★---------------------- -->

        <!-- 検索後の処理 -->
            <?php if($hits_flg): ?>

                <!-- 検索結果の詳細 -->
                <div class="index_hits">
                    <p>
                        検索結果: <?= $xml["totalResultsAvailable"]; ?> 件ヒット
                    </p>
                    <p class="border_left">
                        <!-- 注意: $offsetの中身は 0 が商品の1件目なので、+1 してげなきゃいけない -->
                        <?php echo "表示: ",$offset + 1,"件目 〜 "; ?>
                        <?php if($xml["totalResultsAvailable"] < $offset + 10){echo $xml["totalResultsAvailable"];}else{echo $offset + 10;}?>
                        <?php echo "件目"; ?>
                    </p>
                    <p>
                        <form class="" action="" method="post">
                            <input type="hidden" name="now_offset" value="<?=$offset?>">
                            <input type="hidden" name="mode" value="change_offset">
                            <button type="submit" name="change_value" value="back" <?php if(empty($offset) or $offset < 10){echo "disabled";} ?>><-前の10件</button>
                            (´,,･ω･,,`)
                            <button type="submit" name="change_value" value="next" <?php if(10 >= $xml["totalResultsAvailable"] or (!empty($offset) and 10 >= $xml["totalResultsAvailable"] - $offset)){echo "disabled";} ?>>次の10件-></button>
                        </form>
                    </p>
                </div>
            <?php endif; ?>


            <div class="result_wrapper"><!-- 商品一覧が出てくる場所、画面白い領域 -->
                <!-- ヒットしなかった時、検索ワードが入力されていない時の処理 -->
                <?php if($hits === "no_search_word"): ?>

                    <div class="Item center">
                        <p class="space60px">検索キーワードを入力してね >.<</p>
                    </div>

                <?php elseif($hits === "no_hit"): ?>

                    <div class="Item center">
                        <p class="space40px">何もヒットしなかったよ(；.；)</p>
                        <p class="space20px">別のキーワードで試してみて(；.；)</p>
                    </div>


                <!-- ヒットした時 -->
                <?php elseif($hits_flg === true): ?>

                    <!-- 検索結果の一覧 -->
                    <?php foreach ($hits as $hit): ?>
                        <div class="Item">
                            <div class="product_image">
                                <a href="<?php echo "item.php?itemcode=",h($hit->Code); ?>">
                                    <img src="<?php echo h($hit->Image->Medium); ?>" />
                                </a>
                            </div>
                            <div class="product_name">
                                <h4><a href="<?php echo "item.php?itemcode=",h($hit->Code); ?>"><?php echo h($hit->Name); ?></a><br>おかね: <?php echo h($hit->Price); ?>円</h4>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>
            </div> <!-- result_wrapper -->
        </article>

        <!-- <?php echo BR,"現在のvar_dump(_SESSION[search]): ",BR; ?>
        <?php var_dump($_SESSION["search"]); ?> -->

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
