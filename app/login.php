<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output("login.php"); // ログファイルに追記 // 引数のstringを追記、改行
session_start_anyway();
?>


<!-- ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:. -->
<!-- ログイン処理 -->
<?php
$refresh_top_flg = false;
$no_match = false;


if(chk_post("mode", "from_login")):
    // このページのログインフォームから来た時の処理
    $input_name = chk_post("name");
    $input_pass = chk_post("password");
    // ゲストクラスよりログインを試みる
    $user = new Guest;
    $result = $user->login($input_name, $input_pass);
    if($result === true){ // ログインに成功
        $_SESSION["login"] = true;
        $refresh_top_flg = true; // ☆*:.★*:.☆*:.★*:.☆*:.★*:.　　仮設置　　☆*:.★*:.☆*:.★*:.☆:.★*:.☆*:.★*:.直前のページもドルを実装する必要あり
    }else{                          // 失敗
        $_SESSION["login"] = false;
        $no_match = true;
    }

elseif(chk_post("mode", "from_logout_confirm")):
    // ログアウト確認から飛んできたので、ログアウトする
    $_SESSION = array(); // 空にすることで ["login"] ["member"] ともに消失 すべてのカートも消去
    $from_logout_confirm = true;

elseif(!empty($_SESSION["member"])):
    // ログインできている状態で、外からこのページに飛んできた時（ログアウト意思）
    // $_SESSION = array();
    // $refresh_top_flg = true;
    $from_logout = true;

endif;
?>
<!-- ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:. -->
<!-- 状態に応じてしたでHTML分岐 -->


<!DOCTYPE html>
<html lang="ja">
<!-- ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:. -->
    <!-- ログインの成功失敗で分岐 -->
    <?php if($refresh_top_flg): ?>
        <meta http-equiv="refresh" content="1;top.php">
    <?php endif; ?>
<!-- ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:. -->

    <?php Html::head(); // head要素まるまる // 引数に<title>入力可能 // CSS読み込みもここ ?>
    <body>
        <?php Html::nav(); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる ?>
        <?php Html::header("＊ログイン＊"); // 大見出し // 引数のstringを表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center">
            <!-- ページメイン処理 -->

            <?php if(!empty($from_logout)): ?>
                <p>ログアウトしてよろしいですか？</p>
                <form class="" action="" method="post">
                    <input type="hidden" name="mode" value="from_logout_confirm">
                    <button type="submit">ログアウトする</button>
                </form>
            <?php elseif(!empty($from_logout_confirm)): ?>
                <p>ログアウトしました！</p>
            <?php else: ?>

                <div class="space20px">
                    <?php if($no_match): ?>
                        <p>...そのユーザーは見つかりませんでした(><)</p>
                        <p>パスワードとか間違ってるかもよ(><)</p>
                    <?php endif; ?>
                </div>

                <form class="login" action="<?=LOGIN?>" method="post">
                    <p>ユーザーネーム: <input type="text" name="name" value="" placeholder="username"></p>
                    <p>パスワード: <input type="password" name="password" value="" placeholder="password"></p>
                    <p style="position:relative;right:37px;margin-top:10px;"><button type="submit">.:*☆ろぐいん☆*:.</button></p>
                    <input type="hidden" name="mode" value="from_login">
                </form>

                <p class="space20px">
                    <a href="<?=REGISTRATION?>">->新規ユーザー登録はこちら</a>
                </p>

            <?php endif; ?>

        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
