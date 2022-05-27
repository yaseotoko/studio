<?php
session_start();

require('dbconnect.php');

if(!isset($_SESSION['signup'])){
    header('Location: index.php');
exit();
}

if(!empty($_POST)){
    $statement = $db->prepare('INSERT INTO members SET student_number=?, 
    student_name=?, password=?, created=NOW()');
    echo $ret = $statement->execute(array(
        $_SESSION['signup']['student_number'],
        $_SESSION['signup']['student_name'],
        password_hash($_SESSION['signup']['password1'], PASSWORD_DEFAULT)
    ));
    unset($_SESSION['signup']);
    header('Location: thanks.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>会員登録確認</title>
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
        <h2 class="page-title">確認画面</h2>
        <div class="check-form">
            <form action="" method="post" name="check">
            <input type="hidden" name="action" value="submit">
            <p class="check_student_number">学籍番号は<span><?php echo htmlspecialchars($_SESSION['signup']['student_number'], ENT_QUOTES); ?></span>でよろしいですか？</p>
            <div class="check-button">
            <a class="back-button" href="signup.php">戻る</a>
            <p><input type="submit" value="登録する"class="signup-button"></p>
            </div>
            </form>
            </div>
        </div>
    </div>
</body>
</html>