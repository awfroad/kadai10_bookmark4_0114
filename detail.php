<?php

session_start();
require_once('funcs.php');
require_once('head_parts.php');
loginCheck();

$No = $_GET['id'];
$_SESSION['id'] = $_GET['id'];
$view1 = "";
$view2 = "";

require_once('funcs.php');
$pdo = db_conn();

$stmt = $pdo->prepare('SELECT * FROM googlebooks_table WHERE No = :No;');
$stmt->bindValue(':No', $No, PDO::PARAM_INT); //PARAM_INTなので注意
$status = $stmt->execute();

if ($status === false) {
    //*** function化する！******\
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} else {
    $result = $stmt->fetch();
}

    $query = $result['query'];
    $title = h($result['title']);
    $authors = h($result['authors']);
    $publishedDate = h($result['publishedDate']);
    $link = $result['link'];
    $thumbnail = $result['thumbnail'];
    $previewLink = $result['previewLink'];
    $date = $result['date'];

    $view1 .= '<tr>';
    $view1 .= '<td>'.$No.'</td>';
    $view1 .= '<td>'.$query.'</td>';
    $view1 .= '<td>'.$date.'</td>';
    $view1 .= '<td><a target="_blank" href="' . $previewLink . '"><img src="'. $thumbnail .'"></a></td>';
    $view1 .= '</tr>';
    $view2 .= '<tr>';
    $view2 .= '<td>' . $authors . '</td>';
    $view2 .= '<td>' . $publishedDate . '</td>';
    $view2 .= '<td><a href="delete.php?id=' . $No . '">削除</a></td>';
    $view2 .= '<td><a target="_blank" href="' . $link . '">' . $title . '</a></td>';
    $view2 .= '</tr>';

    // $title = '';
    // $content = '';

    // if (isset($_SESSION['confirm']['title'])) {
    //     $title = $_SESSION['comfirm']['title'];
    // }

    // if (isset($_SESSION['confirm']['content'])) {
    //     $content = $_SESSION['confirm']['content'];
    // }

    if (isset($_SESSION['confirm']['image_data'])) {
        $image_data = $_SESSION['confirm']['image_data'];
    }

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <?= head_parts('詳細画面','detail') ?>
</head>

<body id="main">
    
    <div class="wrapper">

        <!-- Head[Start] -->
        <header>
            <div class="navbar">
                <a class="navbar_btn" href="index.php">検索</a>
                <a class="navbar_btn" href="select.php">一覧</a>
                <a class="navbar_btn" href="favorite_act.php">お気に入り</a>
                <a class="navbar_btn" href="logout.php">LOGOUT</a>
            </div>
        </header>
        <!-- Head[End] -->
        
        <div class="container">

            <table>
                <tr>
                    <th>No</th>
                    <th>検索ワード</th>
                    <th>登録日</th>
                    <th>画像<br>※クリック→レビュー</th>
                </tr>
                    <?= $view1 ?>
                <tr>
                    <th>著者</th>
                    <th>出版日</th>
                    <th>削除</th>
                    <th>タイトル<br>※クリック→Google Books</th>
                </tr>
                <?= $view2 ?>
                
                <tr>
                    <th colspan="2">メモ</th>
                    <th colspan="1">画像アップロード</th>
                    <th colspan="1">お気に入り</th>
                </tr>
                <tr>
                    <form method="POST" action="confirm.php" enctype="multipart/form-data">
                        <td colspan="2">
                            <!-- <form method="POST" action="update.php"> -->
                            <div class="">
                                <fieldset>
                                    <label><textarea name="memo" rows="5" cols="40"><?= h($result['memo'])?></textarea></label><br>
                                    <input type="hidden" name="No" value = "<?= h($No)?>">
                                </fieldset>
                            </div>
                            <!-- </form> -->
                        </td>

                        <td colspan="1" class="area_img">
                            <!-- 画像を挿入 -->
                            <div>
                                <?php if (isset($image_data)): ?>
                                    <img class="detail_img" src="image.php">
                                <?php endif;?>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">画像</label>
                                <input type="file" name="img">
                            </div>
                        </td>
                            
                        <td colspan="1" class="area_favorite">
                            <input class="btn" type="submit" value="保存">
                            <!-- <a href="confirm.php" class="btn btn-tag btn-tag--ver"><i class="fas fa-star"></i>お気に入りに追加</a> -->
                        </td>
                    </form>
                </tr>

            </table>
        </div>

    </div>

</body>

</html>

