<?php

?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>
    <link rel="stylesheet" href="../style/user.css">
</head>

<body>
    <form action="contact.php" method="POST">
      <h2 class="form-header">お問い合わせ</h2>
      <div>
          <label for="name" class="form-label">お名前</label>
          <input type="text" id="name" name="name" value="<?php echo isset($original['name']) ? $original['name'] : null;?>" required/>
          <?php echo isset($flash['name']) ? $flash['name'] : null ?>
      </div>
      <div>
          <label for="email" class="form-label">メールアドレス</label>
          <input type="email" id="email" name="email" value="<?php echo isset($original['email']) ? $original['email'] : null;?>" required/>
          <?php echo isset($flash['email']) ? $flash['email'] : null ?>
      </div>
      <div>
          <label for="message" class="form-label">お問い合わせ本文</label>
          <textarea id="message" name="message"><?php echo isset($original['message']) ? $original['message'] : null;?></textarea>
      </div>
      <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" />
      <button type="submit" class="form-button">送信</button>
    </form>
</body>

</html>
