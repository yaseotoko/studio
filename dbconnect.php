<?php try{
    $db = new PDO('mysql:dbname=kawasaki-kaito_studio;host=mysql57.kawasaki-kaito.sakura.ne.jp;charset=utf8','xxxxxxxx','xxxxxxxx'); 
        }catch(PDOException $e){
            echo 'DB接続エラー:'. $e->getMessage();
        }
?>
    
