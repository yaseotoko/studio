<?php try{
    $db = new PDO('mysql:dbname=kawasaki-kaito_studio;host=mysql57.kawasaki-kaito.sakura.ne.jp;charset=utf8','kawasaki-kaito','1111aaaa'); 
        }catch(PDOException $e){
            echo 'DB接続エラー:'. $e->getMessage();
        }
?>
    
