<?php
session_start();
require('dbconnect.php');

if (!$_SESSION['student_number']) {
    header('Location: login.php');
}

if(!empty($_POST)){
    $check = $db->prepare('SELECT COUNT(*) AS cnt FROM posts WHERE date=? AND timetable=?');
    $check->execute(array($_SESSION['register']['date'], $_SESSION['register']['time']));
    $record = $check->fetch();

    // URLに手入力された場合・既に登録されている場合
    if($record['cnt']>=1){
        $error['time'] = 'duplicate';
    }else{
        $statement = $db->prepare('INSERT INTO posts SET student_number=?, 
    band_name=?, date=?, timetable=?, created=NOW()');
    echo $ret = $statement->execute(array(
        $_SESSION['student_number'],
        $_SESSION['register']['band'],  
        $_SESSION['register']['date'],
        $_SESSION['register']['time'],
    ));
    unset($_SESSION['register']);
    header('Location: index.php');
    exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>バンド登録の確認</title>
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
        <h2 class="page-title">確認画面</h2>
        <div class="register-check-form">
            <?php if($error['time']==''): ?>
            <form action="" method="post" name="register-check">
            <input type="hidden" name="aciton" value="submit">
            <p class="register-check">予約日:
                <?php echo htmlspecialchars($_SESSION['register']['date'], ENT_QUOTES); ?>
            </p>
            <p class="register-check">予約時間:
                <?php echo htmlspecialchars($_SESSION['register']['time'], ENT_QUOTES); ?>
            限</p>
            <p class="register-check">バンド名:
                <?php echo htmlspecialchars($_SESSION['register']['band'], ENT_QUOTES); ?>
            </p>
            <div class="check-button">
            <a class="back-button" href="index.php">戻る</a>
            <p><input type="submit" value="登録する"class="register-button"></p>
            </div>
            </form>
            <?php endif; ?>
            <?php if($error['time']=='duplicate'): ?>
                <p class='register-check'>他のバンドが登録されています</p>
                <div class="check-button">
                <a class="back-button" href="index.php">戻る</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>