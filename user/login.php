<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン - ECサイト</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <div class="login-container">
        <h2>ログイン</h2>

        <!-- エラーメッセージ表示用の部分 -->
        <?php
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            if ($error === 'invalid_credentials') {
                echo '<p style="color: red;">ログインに失敗しました。入力情報を確認してください。</p>';
            } elseif ($error === 'user_not_found') {
                echo '<p style="color: red;">ユーザーが見つかりませんでした。</p>';
            }
        }
        ?>
        <!-- エラーメッセージ表示用の部分 -->

        
        <form action="../user/login_process.php" method="post">
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="login-btn">ログイン</button>
            </div>
            <div class="form-footer">
                <a href="register.html">アカウント作成</a>
                <a href="forgot_password.html">パスワードを忘れた方</a>
            </div>
            <div class="form-footer">
              <button onclick="history.back()" class="back-btn">前のページに戻る</button>
            </div>
            <div class="form-footer">
              <button onclick="location.href='../index.php'" class="back-btn">ホームへ戻る</button>
          </div>
        </form>
    </div>
</body>
</html>
