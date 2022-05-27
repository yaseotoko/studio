<?php
session_start();

require('dbconnect.php');

if(!empty($_POST)){
    mb_convert_kana($_POST['student_number'],'a');

    // 学籍番号が空白の場合
    if($_POST['student_number'] == ''){
        $error['student_number']='blank';

    // 学籍番号が正規表現に則っていない場合
    }else if(!preg_match('/^[e][1][a-z][0-9]{5}$/ ', $_POST['student_number'])){
        $error['student_number']='expression';
    }
    
    // 名前が空白の場合
    if($_POST['student_name']==''){
        $error['student_name']='blank';
    }

    // パスワードが4文字未満の場合
    if(strlen($_POST['password1'])<4){
        $error['password']='length';
    }

    // パスワードが空白の場合
    if($_POST['password1']==''){
        $error['password']='blank';
    }

    // パスワードが合致しない場合
    if($_POST['password1']!== $_POST['password2'] ){
        $error['password']='notequall';
    }

    if(empty($error)){
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE student_number=?');
        $member->execute(array($_POST['student_number'])); 
        $record = $member->fetch();
    
        // 既に学籍番号が登録されている場合
        if($record['cnt'] > 0){
            $error['student_number'] = 'duplicate';
        }
    }

    if(empty($error)){
        $_SESSION['signup'] = $_POST;
        header('Location: check.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>新規作成</title>
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
    
    <div class="signup-content wrapper">
        <h2 class="page-title">新規作成</h2>
        <div class="signup-form">
            <form action="" method="post" name="signup">
            <p class="paragraph">学籍番号:<input type="text" name="student_number" placeholder="e1x00000" class="signup-student_number"></p>
            <?php if ($error['student_number'] == 'blank'): ?>
                <p class="error">*学籍番号を入力してください</p>
            <?php endif; ?>
            <?php if ($error['student_number'] == 'duplicate'): ?>
                <p class="error">*既に登録されている学籍番号です</p>
            <?php endif; ?>
            <?php if ($error['student_number'] == 'expression'): ?>
                <p class="error">*正しい書式で入力してください</p>
            <?php endif; ?>
            <p class="paragraph">名前:<input type="text" name="student_name" placeholder="工大太郎" class="signup-student_name"></p>
            <?php if ($error['student_name'] == 'blank'): ?>
                <p class="error">*名前を入力してください</p>
            <?php endif; ?>
            <p class="paragraph">パスワード:<input type="password" name="password1" placeholder="4文字以上" class="signup-password"></p>
            <p class="paragraph">パスワード:<input type="password" name="password2" placeholder="再入力" class="signup-password"></p>
            <?php if ($error['password'] == 'blank'): ?>
                <p class="error">*パスワードを入力してください</p>
            <?php endif; ?>
            <?php if ($error['password'] == 'length'): ?>
                <p class="error">*パスワードは4文字以上です</p>
            <?php endif; ?>
            <?php if ($error['password'] == 'notequall'): ?>
                <p class="error">*パスワードが合致しません</p>
            <?php endif; ?>
            <p><input type="submit" value="登録する"class="signup-button"></p>
            </form>
        </div>
    </div>
</body>
</html>