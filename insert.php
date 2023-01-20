<?php

    $query = $_GET['query'];

    // Google Books APIを使用して、検索したワードに関連する書籍のタイトル、著者、出版日を取得
    $url = 'https://www.googleapis.com/books/v1/volumes?q='. $query .'&maxResults=10';
    $json = file_get_contents($url);
    $data = json_decode($json,true);

    //2. DB接続します
    // try {
    //     //ID:'root', Password: xamppは 空白 ''
    //     $pdo = new PDO('mysql:dbname=books_db;charset=utf8;host=localhost', 'root', '');
    // } catch (PDOException $e) {
    //     exit('DBConnectError:'.$e->getMessage());
    // }

    require_once('funcs.php');
    $pdo = db_conn();

    //３．データ登録SQL作成

    // 1. SQL文を用意
    $stmt = $pdo->prepare("INSERT INTO
                    googlebooks_table(No, query, title, authors, publishedDate, link, thumbnail, previewLink, date)
                    VALUES(NULL, :query, :title, :authors, :publishedDate, :link, :thumbnail, :previewLink, sysdate() )");


    // 取得したデータをMySQLに保存
    foreach($data['items'] as $item){
        
        // bookのタイトル
        $title = $item['volumeInfo']['title'];
        // 著者
        $authors = implode(",", $item['volumeInfo']['authors']);
        // 出版日
        $publishedDate = $item['volumeInfo']['publishedDate'];
        // リンク
        $link = $item['volumeInfo']['infoLink'];
        // 画像
        $thumbnail = $item['volumeInfo']['imageLinks']['thumbnail'];
        // プレビュー
        $previewLink = $item['volumeInfo']['previewLink'];

        //  2. バインド変数を用意
        // Integer 数値の場合 PDO::PARAM_INT
        // String文字列の場合 PDO::PARAM_STR
        $stmt->bindValue(':query', $query, PDO::PARAM_STR);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':authors', $authors, PDO::PARAM_STR);
        $stmt->bindValue(':publishedDate', $publishedDate, PDO::PARAM_STR);
        $stmt->bindValue(':link', $link, PDO::PARAM_STR);
        $stmt->bindValue(':thumbnail', $thumbnail, PDO::PARAM_STR);
        $stmt->bindValue(':previewLink', $previewLink, PDO::PARAM_STR);

        //  3. 実行
        $status = $stmt->execute();
        
        //４．データ登録処理後
        if ($status === false) {
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            $error = $stmt->errorInfo();
            exit('ErrorMessage:'.$error[2]);
        } else {
            //５．index.phpへリダイレクト
            header('Location: index.php');
        }
    }