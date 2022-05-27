<?php
session_start();

require('dbconnect.php');

if(!empty($_GET)){
    $posts = $db->prepare('SELECT * FROM posts INNER JOIN members ON posts.student_number =
     members.student_number WHERE band_name=? AND date=? AND timetable=? ');   
    $ret = $posts->execute(array(
        $_GET['band_name'],
        $_GET['date'],
        $_GET['time']
    ));
    $post = $posts->fetch();
}

if(!empty($_POST)){
    $delete = $db->prepare('DELETE FROM posts where band_name=? AND
     date=? AND timetable=?');   
    echo $ret = $delete->execute(array(
        $post['band_name'],
        $post['date'],
        $post['timetable']
    ));   
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>バンド確認</title>
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

    <div class="check-content wrapper">
        <h2 class="page-title">バンド確認</h2>
        <div class="band-form">
            <p class="band-name">バンド名:<?php echo $post['band_name'] ?></p>
            <p>登録者:<?php echo $post['student_name'] ?></p>

            <div class="check-button">
            <a class="back-button" href="index.php">戻る</a>
            <?php if($_SESSION['student_number'] == $post['student_number']): ?>
                <p>
                    <form action="" method="post" name="delete">
                        <input type="hidden" name="action" value="<?php $post['band_name'] ?>">
                        <input class="delete-button" type="submit" value="削除する" >
                    </form>
                </p>
            <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>