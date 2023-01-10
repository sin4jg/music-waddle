<?php

    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest3"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date DATETIME,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    
    //頻度高め
    $name=filter_input(INPUT_POST,'name');
    $str = filter_input(INPUT_POST, 'str');
    $date = date("Y/n/j G:i:s");
    
    //書き込み処理
    $pass_add=filter_input(INPUT_POST,'pass_add');
    $edit_n=filter_input(INPUT_POST,'edit_n');
    
    if (!empty($name)&&!empty($str)&&empty($edit_n)&&!empty($pass_add)) {
        
    $sql = $pdo -> prepare("INSERT INTO tbtest3 (name, comment,date,pass) VALUES (:name, :comment,:date,:pass)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $str, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass_add, PDO::PARAM_STR);
    $sql -> execute();
  
    }
    
    //削除処理
    $del=filter_input(INPUT_POST,'del');
    $pass_del=filter_input(INPUT_POST,'pass_del');
    
    if(!empty($del)&&!empty($pass_del)){
        $sql = 'SELECT * FROM tbtest3';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
    foreach ($results as $row){
        if($pass_del==$row['pass']){
    $sql = 'delete from tbtest3 where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $del, PDO::PARAM_INT);
    $stmt->execute();
    }
    }
    }
    
    
    //編集番号指定
    $pass_edit=filter_input(INPUT_POST,'pass_edit');
    $edit=filter_input(INPUT_POST,'number');
    
    if(!empty($edit)&&!empty($pass_edit)){
        $sql = 'SELECT * FROM tbtest3';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
    foreach ($results as $row){
        if($pass_edit==$row['pass']){
    $newnum=$row['id'];
    $newname=$row['name'];
    $newstr=$row['comment'];
    }
    }
    }
    
    //編集処理
    if(!empty($name) && !empty($str)&&!empty($edit_n)&&!empty($pass_add)){
    
    $sql = 'UPDATE tbtest3 SET name=:name,comment=:comment,pass=:pass WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $str, PDO::PARAM_STR);
    $stmt ->bindParam(':pass', $pass_add, PDO::PARAM_STR);
    $stmt->bindParam(':id', $edit_n, PDO::PARAM_INT);
    $stmt->execute();

    
    }
    
?>
    
    <!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>mission_5-1</title>
</head>
<h1> ポッキー派？それともトッポ派？ </h1>
<body>
    <form action=""method="post">
        【投稿】<br>
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($newname)){echo $newname;}?>" >
        <br>
        <input type="text" name="str" placeholder="コメント" value="<?php if(!empty($newstr)){ echo $newstr;}?>">
        <br>
        <input type="text" name="pass_add" placeholder="パスワード">
        <input type="submit" name="submit">
        <br>
        <input type="hidden" name="edit_n" value="<?php if(!empty($newnum)){echo $newnum;}?>">
        <br>【削除】<br>
        <input type="text"name="del" placeholder="削除対象番号">
        <br>
        <input type="text" name="pass_del" placeholder="パスワード">
        <input type="submit"value="削除">
        <br> 【編集】<br>
        <input type="text" name="number" placeholder="編集対象番号">
        <br>
        <input type="text" name="pass_edit" placeholder="パスワード">
        <input type="submit" name="edit" value="編集">
        <br>
</body>
</html>

<?php
    
    //表示
    $sql = 'SELECT * FROM tbtest3';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
    echo "<hr>";
    }

?>