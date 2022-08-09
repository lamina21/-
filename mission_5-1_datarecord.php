<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5</title>
</head>
<body>
    <h3>データレコード</h3>
    <?php
        //データベース接続
        $dsn="mysql:dbname='データベース名';host=localhost";
        $user="ユーザー名";
        $password="パスワード";
        $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

        //データレコード表示
        $tablename="keijiban";
        $sql="SELECT * FROM $tablename";
        $stmt=$pdo->query($sql);
        $lines=$stmt->fetchAll();
        foreach($lines as $line){
            echo $line["id"].",";
            echo $line["name"].",";
            echo $line["comment"].",";
            echo $line["postdate"].",";
            echo $line["password"],"<br>";
            echo "<hr>";
        }
    ?>
</body>
</html>

