<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
        //データベース接続
        $dsn="mysql:dbname='データベース名';host=localhost";
        $user="ユーザー名";
        $password="パスワード";
        $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        /*echo "Ok1";*/

        //テーブル作成
        $tablename="keijiban";
        $sql="CREATE TABLE IF NOT EXISTS $tablename"
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."postdate DATETIME,"
        ."password char(32)"
        .");";
        $stmt=$pdo->query($sql);
        /*echo "OK2";*/

        //POST受信→変数
        $name=filter_input(INPUT_POST, "name");
        $comment=filter_input(INPUT_POST, "comment");
        $pass=filter_input(INPUT_POST, "pass");
        $delnum=filter_input(INPUT_POST, "delnum");
        $delpass=filter_input(INPUT_POST, "delpass");
        $ednum=filter_input(INPUT_POST, "ednum");
        $edpass=filter_input(INPUT_POST, "edpass");
        $edNUM=filter_input(INPUT_POST, "edNUM");

        //データ入力updateとinsertで分岐
        if(!empty($edNUM && $name && $comment && $pass)){
            $sql="UPDATE $tablename SET name=:name, comment=:comment, postdate=now(), password=:password WHERE id=:id";
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
            $stmt->bindParam(":password", $pass, PDO::PARAM_STR);
            $stmt->bindParam(":id", $edNUM, PDO::PARAM_INT);
            $stmt->execute();
            /*echo "Ok3";*/

        }elseif(!empty($name && $comment && $pass)){
            $sql="INSERT INTO $tablename(name, comment, postdate, password) VALUES(:name, :comment, now(), :password)";
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
            $stmt->bindParam(":password", $pass, PDO::PARAM_STR);
            $stmt->execute();
            /*echo "OK4";*/
        }
        
        //データ削除delete
        if(isset($delnum) && !empty($delpass)){
            $sql="DELETE FROM $tablename WHERE id=:id";
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(":id", $delnum, PDO::PARAM_INT);
            $stmt->execute();
            /*echo "OK5";*/
        }

        //編集番号の取得
        if(isset($ednum) && !empty($edpass)){
            $sql="SELECT * FROM $tablename WHERE id=:id";
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(":id", $ednum, PDO::PARAM_INT);
            $stmt->execute();
            $results=$stmt->fetchAll();
            foreach($results as $row){
                /*var_dump($row["id"],$row["name"],$row["comment"],$row["password"]);*/
                $edid=$row["id"];
                $edname=$row["name"];
                $edcomment=$row["comment"];
                $edpassword=$row["password"];
            }
            /*echo "OK6";*/
        }
    ?>

    <h2>掲示板</h2>
    <p>
        <label for="toukou">投稿フォーム</label>
        <form action="" method="post">
            <input id="toukou" type="hidden" name="edNUM" value=<?php echo $edid ?? ""; ?>>
            <input id="toukou" type="text" name="name" placeholder="名前" value=<?php echo $edname ?? ""; ?>><br>
            <input id="toukou" type="text" name="comment" placeholder="コメント" value=<?php echo $edcomment ?? ""; ?>><br>
            <input id="toukou" type="password" name="pass" placeholder="パスワード" value=<?php echo $edpassword ?? "";?>>
            <input id="toukou" type="submit" name="submit" value="投稿">
        </form>
    </p>

    <p>
        <label for="sakujo">削除番号指定フォーム</label>
        <form action="" method="post">
            <input id="sakujo" type="number" name="delnum" placeholder="削除番号"><br>
            <input id="sakujo" type="password" name="delpass" placeholder="パスワード">
            <input id="sakujo" type="submit" name="submit" value="削除">
        </form>
    </p>

    <p>
        <label for="hensyu">編集番号指定フォーム</label>
        <form action="" method="post">
            <input id="hensyu" type="number" name="ednum" placeholder="編集番号"><br>
            <input id="hensyu" type="password" name="edpass" placeholder="パスワード">
            <input id="hensyu" type="submit" name="submit" value="編集">
        </form>
    </p>

    <?php
        //データ表示
        $sql="SELECT * FROM $tablename";
        $stmt=$pdo->query($sql);
        $results=$stmt->fetchAll();
        foreach($results as $row){
            echo $row["id"].",";
            echo $row["name"]." ";
            echo $row["comment"]." ";
            echo $row["postdate"]."<br>";
        }
    ?>
</body>
</html>