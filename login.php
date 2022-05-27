<?php
session_start();

require('dbconnect.php');

if(!empty($_POST)){

    // データベースから学籍番号とパスワードを取ってくる
    if($_POST['student_number'] !== '' && $_POST['password'] !== ''){
        $login = $db->prepare('SELECT * FROM members WHERE student_number=?');
        $login->execute(array(
            $_POST['student_number']
        ));
    $member = $login->fetch();

    // パスワードが一致しているか確認
        if(password_verify($_POST['password'], $member['password'])){
            $_SESSION['student_number'] = $member['student_number'];
            header('Location: index.php'); exit();

    // 一致しなかった場合
        }else{
            $error['login'] = 'failed';
        }
    }else{ 
        $error['login'] = 'blank';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ログイン</title>
    <meta name="description" content="大阪工業大学のスタジオ表">
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- ヘッダー -->
    <header class="page-header">
        <h1><a href="index.php"><img src="images/oit-keionbu.jpg" alt="大阪工業大学"></a></h1>
        <div class="menu">
            <?php 
            if (!isset($_SESSION['student_number'])) {
                print('<a href="login.php" class="login">ログイン</a>');
                print('<a href="signup.php" class="signup">新規作成</a>');
            }else{
                print('<a href="logout.php" class="logout">ログアウト</a>');
            }
            ?>
            <a href="index.php" class="studio">スタジオ表</a>
        </div>
    </header>
    <div class="login-content wrapper">
        <h2 class="page-title">ログイン</h2>
        <div class="login-form">
            <form action="" method="post" name="login">
            <p>学籍番号:<input type="text" name="student_number" placeholder="e1x00000" class="login-student_number"></p>
            <p>パスワード:<input type="password" name="password" placeholder="パスワード"class="login-password"></p>
            <?php if ($error['login'] === 'blank'): ?>
            <p class="error">*学籍番号とパスワードを入力してください</p>
            <?php endif; ?>
            <?php if ($error['login'] === 'failed'): ?>
            <p class="error">*パスワードが間違っています</p>
            <?php endif; ?>
            <p><input type="submit" value="ログインする"class="login-button"></p>
            </form>
        </div>
    </div>
</body>
</html>