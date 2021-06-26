<?php
  session_start();
  $mode = 'input';
  $errmessage = array();
  if( isset($_POST['back']) && $_POST['back'] ){
    // 何もしない
  } else if( isset($_POST['confirm']) && $_POST['confirm'] ){
      // 確認画面
    if( !$_POST['fullname'] ) {
        $errmessage[] = "名前を入力してください";
    } else if( mb_strlen($_POST['fullname']) > 100 ){
        $errmessage[] = "名前は100文字以内にしてください";
    }
      $_SESSION['fullname'] = htmlspecialchars($_POST['fullname'], ENT_QUOTES);

      if( !$_POST['email'] ) {
          $errmessage[] = "Eメールを入力してください";
      } else if( mb_strlen($_POST['email']) > 200 ){
          $errmessage[] = "Eメールは200文字以内にしてください";
    } else if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
        $errmessage[] = "メールアドレスが不正です";
      }
      $_SESSION['email']    = htmlspecialchars($_POST['email'], ENT_QUOTES);

      if( !$_POST['message'] ){
          $errmessage[] = "お問い合わせ内容を入力してください";
      } else if( mb_strlen($_POST['message']) > 500 ){
          $errmessage[] = "お問い合わせ内容は500文字以内にしてください";
      }
      $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

      if( $errmessage ){
        $mode = 'input';
    } else {
        $mode = 'confirm';
    }
  } else if( isset($_POST['send']) && $_POST['send'] ){
    // 送信ボタンを押したとき
    $message  = "お問い合わせを受け付けました \r\n"
              . "名前: " . $_SESSION['fullname'] . "\r\n"
              . "email: " . $_SESSION['email'] . "\r\n"
              . "お問い合わせ内容:\r\n"
              . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
      mail($_SESSION['email'],'お問い合わせありがとうございます',$message);
    mail('nishidesu4649@icloud.com','お問い合わせありがとうございます',$message);
    $_SESSION = array();
    $mode = 'send';
  } else {
    $_SESSION['fullname'] = "";
    $_SESSION['email']    = "";
    $_SESSION['message']  = "";
  }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
        <title>欅祭事項委員会</title>
        <link rel = "stylesheet" href = "inquiry.css">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="inquiry.js"></script>
    </head>
    <body>
       
        <header>
            <center>
            <div class="logo">
                <p>欅祭実行委員会</p>
            </div>
            </center>
            <button type="button" class="btn js-btn">
                <span class="btn-line"></span>
              </button>
              <nav>
                <ul class="menu">
                    <li class="menu-list">
                        <a class="ml" href="../ホーム/home.html">ホーム</a>
                    </li>
                    <li class="menu-list">
                        <a class="ml" href="../活動紹介/active.html">活動紹介</a>
                    </li>
                    <li class="menu-list">
                        <a class="ml" href="../企画/index.html">企画</a>
                    </li>
                    <li class="menu-list">
                        <a class="ml" href="../募集/together.html">メンバー募集</a>
                    </li>
                    <li class="menu-list">
                        <a class="ml" href="../イベント/event.html">イベント</a>
                    </li>
                    <li class="menu-list">
                        <a class="ml" href="../写真/photo.html">写真一覧</a>
                    </li>
                    <li class="menu-list">
                        <a class="ml" href="../アクセス/acsses.html">アクセス</a>
                    </li>
                    <li class="menu-list">
                        <a class="ml" href="../ゲスト/gest.html">ゲスト</a>
                    </li>
                    <li class="menu-list">
                        <a class="ml" href="../注意/caution.html">注意</a>
                    </li>
                    <li class="menu-list">
                        <a class="ml" href="../スケジュール/plan.html">スケジュール</a>
                    </li>
                </ul>
              </nav>
        </header>
        <center>
        <div class="container">
            <div class="sub">お問い合わせ</div>
            <div class="moji">
                欅祭実行委員会及び、欅祭に関してのお問い合わせに関しては、メールもしくは、
                SNSから気軽にお問い合わせください
            </div>
            <h1>連絡先/ＳＮＳ</h1>
            <section class="form">
                <h2>メール</h2>

                <?php if( $mode == 'input' ){ ?>
                    <!-- 入力画面 -->
                    <?php
                      if( $errmessage ){
                        echo '<div style="color:red;">';
                        echo implode('<br>', $errmessage );
                        echo '</div>';
                      }
                    ?>
                    <form action="inquiry.php" method="post">
                        <dl>
                            <dt>お名前</dt>
                            <dd>
                                <input type="text"name="fullname" class="name"
                                value="<?php echo $_SESSION["fullname"] ?>">
                            </dd>
                            <dt>メールアドレス</dt>
                            <dd>
                                <input type="email"name="email" class="email" 
                                value="<?php echo $_SESSION["email"] ?>">
                            </dd>
                            <dt>メッセージ</dt>
                            <dd>
                                <textarea name="message" class="message">
                                <?php echo $_SESSION["message"] ?>
                                </textarea>
                            </dd>
                        </dl>

                      <input type="submit" name="confirm"  value="確認" />
                    </form>
                  <?php } else if( $mode == 'confirm' ){ ?>
                    <!-- 確認画面 -->
                    <form action="./inquiry.php" method="post">
                      名前    <?php echo $_SESSION['fullname'] ?><br>
                      Eメール <?php echo $_SESSION['email'] ?><br>
                      お問い合わせ内容<br>
                      <?php echo nl2br($_SESSION['message']) ?><br>
                      <input type="submit" name="back" value="戻る" />
                      <input type="submit" name="send" value="送信" />
                    </form>
                  <?php } else { ?>
                    <!-- 完了画面 -->
                    送信しました。お問い合わせありがとうございました。<br>
                  <?php } ?>
            </section>
            <h2>住所</h2>

            <p>
                〒862-8652<br>
                熊本県熊本市東区渡鹿9－1－1<br>
                東海大学熊本キャンパス<br>
                シエスタ　３階
            </p>
            <h2>E-mail</h2>
           <p>
            tokai.keyaki2021@gmail.com
           </p>
            <div class="all-menu">
                <a class="bt" href="../募集/together.html">メンバー募集</a>
                <a  class="bt" href="https://twitter.com/tokai_toyu2016?ref_src=twsrc%5Etfw">Twitter：@欅祭実行委員会</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                <a  class="bt" href="https://www.instagram.com/tokai_keyaki">Instagram:@keyakisai</a>
            </div>
        </div>
        <footer>
            <p><small>&copy;欅祭実行委員会</small></p>
        </footer>
        </center>
    </body>
</html>
