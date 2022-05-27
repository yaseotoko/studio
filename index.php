<?php
session_start();

if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) {
    if($_REQUEST['page'] >=5 || $_REQUEST['page'] <=0){
        $page = 1;
    }else{
        $page = $_REQUEST['page'];
    }
} else {
    $page = 1;
}

require('dbconnect.php');

if (isset($_SESSION['student_number']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE student_number=?');
    $members->execute(array($_SESSION['student_number']));
    $member = $members->fetch();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>スタジオ表</title>
    <meta name="description" content="大阪工業大学のスタジオ表">
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
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
    <div class="home-content wrapper">
        <h2 class="page-title">スタジオ表</h2>

        <!-- ページ移動のボタン -->
        <div class="pagenav">
            <?php if ($page >= 2) : ?>
                <a class="nav-back" href="index.php?page=<?php print($page - 1); ?>">
                <img src="images/back.jpg" class="nav" alt="前週">
                </a>
            <?php endif; ?>
            <?php if ($page <= 3) : ?>
                <a class="nav-next" href="index.php?page=<?php print($page + 1); ?>">
                <img src="images/next.jpg" class="nav" alt="次週"></a>
            <?php endif; ?>
        </div>

        <!-- 表部分 -->
        <div class="studio-table">
            <?php
            $week_name = ['日', '月', '火', '水', '木', '金', '土'];

            // iのループは日にちを増やす
            for ($i = ($page-1)*7; $i <= ($page-1)*7+6; $i++) {
                print('<table class="studio-date">');
                print("<tr>");
                print('<th colspan="2">');
                print(date('n月/j日', strtotime("+$i day")) . "(" . $week_name[date('w', strtotime("+$i day"))] . ")");
                print("</th>");
                print("</tr>");

                // jのループはtimetableを増やす
                for ($j = 1; $j <= 7; $j++) {
                    print("<tr>");
                    print("<td class='time'>" . $j . "限</td>");
                    $posts = $db->prepare('SELECT band_name FROM posts WHERE date=? AND timetable=?');
                    $posts->execute(array(
                        date('Y-m-d', strtotime("+$i day")),
                        $j
                    ));
                    $post = $posts->fetch();
                    if (isset($post['band_name'])) {
                        if(strlen($post['band_name'])>= 10){
                            $scband = mb_substr($post['band_name'],0,9,"UTF-8")."...";
                        }else{
                            $scband = $post['band_name'];
                        }
                        print("<td class='band'><a href='band.php?band_name=".$post['band_name'].
                         "&date=". date('Y-m-d',strtotime("+$i day")) ."&time=".$j. 
                        "'>". htmlspecialchars($scband, ENT_QUOTES) . "<a></td>");                     
                    } else {
                        print("<td class='band'><a href='register.php?date=". date('Y-m-d',strtotime("+$i day")).
                        "&time=$j'>―</a></td>");
                    }
                    print('</td>');
                    print('</tr>');
                }
                print('</tabele>');
            }
            ?>
        </div>
    </div>
</body>
</html>