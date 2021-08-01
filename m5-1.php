<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>

    <?php
            //新規投稿機能

            //もしフォームに名前とコメントが投稿されたら
            if (!empty($_POST["name"]) && !empty($_POST["comment"] && !empty($_POST["pass"]))) {
                //定義
                $namea = $_POST["name"];
                $commenta=$_POST["comment"];
                $passa=$_POST["pass"];
                //編集番号がない場合、新規投稿は行わない
                if(empty($_POST["editnum"])){
                    
                $date=date("Y/m/d H:i:s");
                
                
                    //データベースに接続
                    $dsn = 'データベース名';
                    $user = 'ユーザー名';
                    $password = 'パスワード';
                    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                    //名前とコメントをインサートで追記
                    $sql = $pdo -> prepare("INSERT INTO tbtest_2 (name, comment, created_at, pass) VALUES (:name, :comment, :created_at, :pass)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':created_at', $created_at, PDO::PARAM_STR);
                    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                    $name = $namea;
                    $comment = $commenta; 
                    $created_at=$date;
                    $pass=$passa;
                    $sql -> execute();
                    
                    
                    
                }

            }
            
            
            
            
            //編集実行機能
            //編集選択により、下記の三項目が入力されたとき、
            if(!empty($_POST["name"]) && !empty($_POST["comment"]) &&!empty($_POST["editnum"])){
                
                //定義
                //編集番号を変数に入れる 
                $editnum = $_POST["editnum"];
                $date=date("Y/m/d H:i:s");

                
                $dsn = 'データベース名';
                $user = 'ユーザー名';
                $password = 'パスワード';
                $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                
                $id = $editnum; //変更する投稿番号
                $name = $_POST["name"];
                $comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
                $created_at = $date;                 //↓ここで一区切り
                $sql = 'UPDATE tbtest_2 SET name=:name, comment=:comment, created_at=:created_at WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute(); 
        } 
            
            
            //削除機能 
        //削除番号を受信した場合、
        if(!empty($_POST["delete"]) && !empty($_POST["deletepass"])){ 
             
            //定義
            //受信内容を変数に入れる
            $delete = $_POST["delete"]; 
            $deletepass = $_POST["deletepass"];
            
            //パスワードが一致した場合
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            $sql = 'SELECT * FROM tbtest_2';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                $row['id'];
                $row['name'];
                $row['comment'];
                $row['created_at'];
                $row['pass'];
            }
                
            if($deletepass == $row['pass']){
             
                $dsn = 'データベース名';
                $user = 'ユーザー名';
                $password = 'パスワード';
                $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                
                $id = $delete;
                $sql = 'delete from tbtest_2 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            
        } 
        
        //編集選択機能
        //編集番号を受信したとき
        if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
            
            //定義
            //受信したものを変数に入れる
            $edit=$_POST["edit"];
            $editpass=$_POST["editpass"];
            
            //$row['pass']を取得するために
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            $sql = 'SELECT * FROM tbtest_2';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                $row['id'];
                $row['name'];
                $row['comment'];
                $row['created_at'];
                $row['pass'];
            }
            
            //パスワードが一致したら
            if($editpass == $row['pass']){
            
                $dsn = 'データベース名';
                $user = 'ユーザー名';
                $password = 'パスワード';
                $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                
                $id=$edit;
                $sql = 'SELECT * FROM tbtest_2 WHERE id=:id ';
                $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                $stmt->execute();                             // ←SQLを実行する。
                $results = $stmt->fetchAll(); 
                    foreach ($results as $row){
                        //$rowの中にはテーブルのカラム名が入る
                        $row['id'];
                        $row['name'];
                        $row['comment'];
                        $row['pass'];
                    }
            }
                
                
                
        }
        
            //編集するものをフォームに自動で入れておく
            //valueを使い、フォーム内容の初期値をいれる
    ?>
            <form action="" method="post">
                
                <p>名前：<input type="text" name="name" 
                                value="<?php if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
                                                if($editpass == $row['pass']){
                                                    $dsn = 'データベース名';
                                                    $user = 'ユーザー名';
                                                    $password = 'パスワード';
                                                    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                                                    
                                                    $id=$edit;
                                                    $sql = 'SELECT * FROM tbtest_2 WHERE id=:id ';
                                                    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                                                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                                                    $stmt->execute();                             // ←SQLを実行する。
                                                    $results = $stmt->fetchAll(); 
                                                        foreach ($results as $row){
                                                    echo $row['name'];
                                                        }
                                                }
                                            }
                                        ?>"></p>
                        
                <p>コメント：<input type=textarea name="comment" 
                                    value="<?php if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
                                                    if($editpass == $row['pass']){
                                                    $dsn = 'データベース名';
                                                    $user = 'ユーザー名';
                                                    $password = 'パスワード';
                                                    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                                                    
                                                    $id=$edit;
                                                    $sql = 'SELECT * FROM tbtest_2 WHERE id=:id ';
                                                    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                                                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                                                    $stmt->execute();                             // ←SQLを実行する。
                                                    $results = $stmt->fetchAll(); 
                                                        foreach ($results as $row){
                                                    echo $row['comment'];
                                                        }
                                                    }
                                                }
                                            ?>"></p>
                
                <p>パスワード：<input type=text name="pass"
                                      value="<?php if(!empty($_POST["edit"]) && !empty($_POST['editpass'])){
                                                      if($editpass == $row['pass']){
                                                        $dsn = 'データベース名';
                                                        $user = 'ユーザー名';
                                                        $password = 'パスワード';
                                                        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                                                        
                                                        $id=$edit;
                                                        $sql = 'SELECT * FROM tbtest_2 WHERE id=:id ';
                                                        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                                                        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                                                        $stmt->execute();                             // ←SQLを実行する。
                                                        $results = $stmt->fetchAll(); 
                                                            foreach ($results as $row){
                                                        echo $row['pass'];
                                                            }
                                                      }
                                                  }
                                             ?>"></p>
                
                <input type=hidden name="editnum"
                                    value="<?php if(!empty($_POST["edit"])){
                                                    if($editpass == $row['pass']){
                                                        $dsn = 'データベース名';
                                                        $user = 'ユーザー名';
                                                        $password = 'パスワード';
                                                        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                                                        
                                                        $id=$edit;
                                                        $sql = 'SELECT * FROM tbtest_2 WHERE id=:id ';
                                                        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                                                        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                                                        $stmt->execute();                             // ←SQLを実行する。
                                                        $results = $stmt->fetchAll(); 
                                                            foreach ($results as $row){
                                                        echo $row['id'];
                                                            }
                                                      }
                                                }
                                            ?>">
                        
                <input type="submit" name="submit">
                
                <p>削除対象番号：<input type=number name="delete"></p>
                <p>パスワード：<input type=text name="deletepass"></p>
                <input type="submit" value="削除">
                
                <p>編集番号選択：<input type=number name="edit"></p>
                <p>パスワード：<input type=text name="editpass"></p>
                <input type="submit" value="編集">
            </form>
            
    <?php    
        
        //ブラウザに表示
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            $sql = 'SELECT * FROM tbtest_2';
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
    ?>
</body>
</html>