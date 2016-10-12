<?php
require_once "../util/defineUtil.php";
require_once "../util/dbaccessUtil.php";
require_once "../util/scriptUtil.php";
require_once YAHOO_API_COMMON;
Log::output(REGISTRATION_CONFIRM); // ログファイルに追記 // 引数のstrを[$strへアクセス]という形でログ追記、改行
session_start_anyway();
?>



<!-- ☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-*☆*-*★*-* -->
<?php
$access_chk = false;
$_SESSION["registration"]["user_info"] = null;

// ポストから入力データを受け取りセッションに保存
if(chk($_POST, "mode", "from_registration")):
    $access_chk = true;
    foreach ($_POST as $key => $value) {
        if($key == "mode"){
            continue;
        }
        $_SESSION["registration"]["user_info"][$key] = $value;
    }



    $_SESSION["registration"]["user_unanswered_flg"] = null; // 未入力記憶のリセット
    $attention_list = null;

    foreach($_SESSION["registration"]["user_info"] as $key => $value):
            if($value === ""):
                    $_SESSION["registration"]["user_unanswered_flg"][$key] = true;
                    switch($key):
                        case "name":
                            $attention_list[] = "*名前が未入力です！";
                            break;
                        case "password":
                            $attention_list[] = "*パスワードが未入力です！";
                            break;
                        case "mail":
                            $attention_list[] = "*めーるが未入力です！";
                            break;
                        case "address":
                            $attention_list[] = "*じゅうしょが未入力です！";
                            break;
                    endswitch;
            else:
                    $_SESSION["registration"]["user_unanswered_flg"][$key] = null;
            endif;
    endforeach;



    if(!$attention_list): // 未入力が１個もなければ
            // 新規登録のための _SESSIONでチケット発行 and チケット有効期限管理
            $_SESSION["registration"]["ticket"] = true;
            $_SESSION["registration"]["last_access_time"] = time();


            // $confirm_list = array();
            //
            // foreach($_SESSION["registration"]["user_info"] as $key => $value):
            //         switch($key):
            //                 case "name":
            //                         $confirm_list[$key] = $value;
            //
            //         endswitch;
            // endforeach;

            $confirm_list = $_SESSION["registration"]["user_info"];


    endif;


else:
    unset($_SESSION["registration"]); // 登録に関するすべての情報を破棄 // もちろんチケットも失う
endif;
?>
<!-- ☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:.☆*:.★*:. -->




<!DOCTYPE html>
<html lang="ja">
    <?php Html::head("registration_confirm"); // head要素まるまる // 引数に<title>入力可能 // CSS読み込み済み ?>
    <body>
        <?php Html::nav(REGISTRATION_CONFIRM); // ページ最上のユーザーナビ // ログイン状態によって表示内容が変わる // 引数は現在ページの定数 ?>
        <?php Html::header("＊登録確認＊"); // 大見出し // 第１引数のstringを見出し表示 // 第２引数にリンク先を追加可能 ?>

        <article class="center"><!-- ページメイン処理 -->

            <?php if($access_chk): // 登録ページからアクセスしてきた時 ?>
                    <?php if($attention_list): // 未入力があった時 ?>

                            <div style="position:relative;left:17px;margin:auto;width:200px;text-align:left;">
                                    <?php foreach ($attention_list as $value): ?>
                                            <p><span class="crimson"><?=$value?></span></p>
                                    <?php endforeach; ?>
                            </div>

                            <form class="space10px" action="<?=REGISTRATION?>" method="post">
                                    <input type="hidden" name="mode" value="from_registration_confirm">
                                    <button type="submit">入力フォームへ戻る</button>
                            </form>
                    <?php else: // 未入力が無かった時 ?>
                            <p>すべての入力を確認！</p>
                            <div class="space20px">
                                <p>名前: <?=$confirm_list["name"]?></p>
                                <p>パスワード: <?=$confirm_list["password"]?></p>
                                <p>めーる: <?=$confirm_list["mail"]?></p>
                                <p>じゅうしょ: <?=$confirm_list["address"]?></p>
                            </div>
                            <p>この情報で登録しますか？</p>
                            <form class="" action="<?=REGISTRATION_COMPLETE?>" method="post">
                                <input type="hidden" name="mode" value="from_registration_confirm">
                                <button type="submit">はい</button>
                            </form>
                            <form class="" action="<?=REGISTRATION?>" method="post">
                                <input type="hidden" name="mode" value="from_registration_confirm">
                                <button type="submit">いいえ、戻る</button>
                            </form>
                    <?php endif; ?>


            <?php else: // ダイレクトリンクなど ?>
                <p>不正アクセスです : トップページからやり直してください</p>
            <?php endif; ?>

        </article>

        <footer>
            <?php Html::return_top(); ?>
            <?php Html::address(); ?>
            <?php Html::wrapper(); ?>
        </footer>
    </body>
</html>
