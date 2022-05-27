<?php
session_start();

require('dbconnect.php');

if (!$_SESSION['student_number']) {
    header('Location: login.php');
}

if(!empty($_POST)){
    if($_POST['band'] == ""){
        $error['band'] = "blank";
    }
    if(empty($error)){
        $_SESSION['register'] = $_POST;
        $_SESSION['register']['date'] = $_GET['date'];
        $_SESSION['register']['time'] = $_GET['time'];
        header('Location: register-check.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>スタジオ表登録</title>
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

    <div class="register-content wrapper">
        <h2 class="page-title">スタジオ表登録</h2>
        <div class="register-form">
            <form action="" method="post" name="register">
            <p class="register-check">予約日:<?php echo htmlspecialchars($_GET['date'], ENT_QUOTES); ?></p>
            <p class="register-check">時間:<?php echo htmlspecialchars($_GET['time'], ENT_QUOTES); ?>限</p>
            <p class="register-check">バンド名:<input type="text" name="band" class="register-band">
            <?php if($error['band']=="blank"): ?>
                <p class="error">バンド名を入力してください</p>
            <?php endif; ?>
            <div class="check-button">
            <a class="back-button" href="index.php">戻る</a>
            <p><input class="register-button" type="submit" value="確認する"></p>
            </div>
        </div>
    </div>
</body>
</html>