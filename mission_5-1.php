<?php
   $dsn = 'データベース名';
   $user = 'ユーザー名';
   $password = 'パスワード';
   $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
   $sql = "CREATE TABLE IF NOT EXISTS formbox"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."created_at DATETIME"
    .");";
   $stmt = $pdo->query($sql);
 
   $sql = 'SHOW TABLES';
   $result = $pdo -> query($sql);
   foreach ($result as $row){
       echo $row[0];
       echo '<br>';
   }
   echo "<hr>";

   $sql = 'SHOW CREATE TABLE formbox';
   $result = $pdo -> query($sql);
   foreach ($result as $row){
       echo $row[1];
   }
   echo "<hr>";

 //編集機能(3-4-1～3-4-5)
 if(!empty($_POST["showeditNumber"]) && !empty($_POST["password3"]) && isset($_POST["edit"])) {
   $showeditNumber = $_POST["showeditNumber"];
   $pass3 = $_POST["password3"];
   $sql = 'SELECT * FROM formbox';
   $stmt = $pdo->query($sql);
   $results = $stmt->fetchAll();
   foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';	
	echo $row['comment'].',';
        echo $row['created_at'].'<br>';
	echo "<hr>";
   if ($row['id'] == $showeditNumber) {
       $editName = $row['name'];
       $editComment = $row['comment'];
   }
   }
 }
?>

<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>投稿フォーム</title>
 </head>
 <body>
  <form action="mission_5-1.php" method="POST">
   <p>名前：</p>
    <input type="text" placeholder="名前" name="name" value=
     <?php 
       if(!empty($editName)) {
        echo $editName;
       }
     ?>
    ><br>
   <p>コメント：</p>
    <input type="text" placeholder="コメント" name="comment" value=
     <?php 
       if(!empty($editComment)) {
        echo $editComment;
       }
     ?>
    ><br>
    <input type="hidden" name="editNumber" value=
      <?php
        if(!empty($showeditNumber)) {
         echo $showeditNumber;
        }
      ?>
    ><br>
   <p>パスワード:</p>
    <input type="text" placeholder="パスワード" name="password1"><br>
    <input type="submit" name="send" value="送信"><br>
    
   <p>削除番号指定用フォーム</p>
    <input type="text" placeholder="削除番号" name="deleteNumber"><br>
   <p>パスワード:</p>
    <input type="text" placeholder="パスワード" name="password2"><br>
    <input type="submit" name="delete" value="削除"><br>
  
   <p>編集番号指定用フォーム</p>
    <input type="text" placeholder="編集対象番号" name="showeditNumber"><br>
   <p>パスワード:</p>
    <input type="text" placeholder="パスワード" name="password3"><br>
    <input type="submit" name="edit" value="編集">
  </form>
 </body>
</html>

<?php
 //新規投稿
 if (!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["editNumber"]) && !empty($_POST["password1"]) && isset($_POST["send"])){
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $pass1 = $_POST["password1"];
    $DATETIME = new DateTime();
    $DATETIME = $DATETIME->format('Y-m-d H:i:s');
    $sql = $pdo -> prepare("INSERT INTO formbox (name, comment,created_at) VALUES (:name, :comment, :created_at)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':created_at', $DATETIME, PDO::PARAM_STR);
    $sql -> execute();
    $sql = 'SELECT * FROM formbox';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
        echo $row['created_at'].'<br>';
    echo "<hr>";
    }
 }

 //削除機能
 if (!empty($_POST["deleteNumber"]) && !empty($_POST["password2"]) && isset($_POST["delete"])) {
    $deleteNumber = $_POST["deleteNumber"];
    $pass2 = $_POST["password2"];
    $id = $deleteNumber;
    $sql = 'delete from formbox where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $sql = 'SELECT * FROM formbox';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
        echo $row['created_at'].'<br>';
    echo "<hr>";
    }
 }

 //編集機能続き
 if(!empty($_POST["editNumber"]) && !empty($_POST["password1"]) && isset($_POST["send"])){
    $editNumber = $_POST["editNumber"];
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $pass1 = $_POST["password1"];
    $DATETIME = new DateTime();
    $DATETIME = $DATETIME->format('Y-m-d H:i:s');
    $id = $editNumber; 
    $sql = 'update formbox set name=:name,comment=:comment,created_at=:created_at where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt -> bindParam(':created_at', $DATETIME, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $sql = 'SELECT * FROM formbox';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
        echo $row['created_at'].'<br>';
        echo "<hr>";
    }
 }
?>
