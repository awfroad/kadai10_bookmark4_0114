<?php

session_start();
require_once('funcs.php');
require_once('head_parts.php');
loginCheck();

$pdo = db_conn();

//２．データ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM googlebooks_table");
$status = $stmt->execute();

//３．データ表示
$view="";
if ($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    // $view .= '<p>' . $result['No'] . ' / ' . h($result['title']) . ' / ' . h($result['comment']) . ' / ' . h($result['book_url']) . '</p>' ;
    $No = $result['No'];
    $query = $result['query'];
    $title = h($result['title']);
    $authors = h($result['authors']);
    $publishedDate = h($result['publishedDate']);
    $link = $result['link'];
    $thumbnail = $result['thumbnail'];
    $previewLink = $result['previewLink'];
    $date = $result['date'];

    $view .= '<tr>';
    $view .= '<td>'.$No.'</td>';
    $view .= '<td>'.$query.'</td>';
    $view .= '<td>'.$date.'</td>';
    $view .= '<td><a target="_blank" href="' . $previewLink . '"><img src="'. $thumbnail .'"></a></td>';
    $view .= '<td><a target="_blank" href="' . $link . '">' . $title . '</a></td>';
    $view .= '<td>' . $authors . '</td>';
    $view .= '<td>' . $publishedDate . '</td>';
    $view .= '<td><a class="select_btn" href="detail.php?id=' . $No . '">詳細</a></td>';
    $view .= '<td><a class="select_btn" href="delete.php?id=' . $No . '">削除</a></td>';
    $view .= '</tr>';
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

  <head>
    <?= head_parts('ブックマーク表示','select') ?>
  </head>

  <body id="main">
    <div class="wrapper">

      <!-- Head[Start] -->
      <header>
        <div class="navbar">
              <a class="navbar_btn" href="index.php">検索</a>
              <a class="navbar_btn" href="favorite.php">お気に入り</a>
              <a class="navbar_btn" href="logout.php">LOGOUT</a>
        </div>
      </header>
      <!-- Head[End] -->

      <!-- Main[Start] -->
        <table class="table">
          <tr>
            <th>No</th>
            <th>検索ワード</th>
            <th>登録日</th>
            <th>画像<br>※クリック→プレビュー</th>
            <th>タイトル<br>※クリック→Google Books</th>
            <th>著者</th>
            <th>出版日</th>
            <th>詳細</th>
            <th>削除</th>
          </tr>
          <?= $view ?>
        </table>
      <!-- Main[End] -->
    </div>

  </body>
</html>
