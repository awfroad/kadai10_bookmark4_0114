<?php

session_start();
require_once('funcs.php');
require_once('head_parts.php');
loginCheck();

$No = $_SESSION['id'];
$_GET['id'] = $No;
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

// 画像の処理

// post受け取る
// $title = $_POST['title'];
// $content = $_POST['content'];
// $_SESSION['confirm']['title'] = $_POST['title'];
// $_SESSION['confirm']['content'] = $_POST['content'];

// formで送られてきたら
if ($_FILES['img']['name'] !== '') {
    $file_name = $_SESSION['confirm']['file_name'] = $_FILES['img']['name'];
    $image_data = $_SESSION['confirm']['image_data'] = file_get_contents($_FILES['img']['tmp_name']);
    $image_type = $_SESSION['confirm']['image_type'] = exif_imagetype($_FILES['img']['tmp_name']);

// ファイルで送らないけどセッションの中にデータがあれば
} elseif($_FILES['img']['name'] === '' && $_SESSION['confirm']['image_data'] !== '') {
    $file_name = $_SESSION['confirm']['file_name'];
    $image_data = $_SESSION['confirm']['image_data'];
    $image_type = $_SESSION['confirm']['image_type'];

// formにも、セッションにも何もデータがなければ。
} else {
    $file_name = $_SESSION['confirm']['file_name'] = '';
    $image_data = $_SESSION['confirm']['image_data'] = '';
    $image_type = $_SESSION['confirm']['image_type'] = '';
}

// 簡単なバリデーション処理。
if (trim($title) === '' || trim($content) === '') {
    redirect('confirm.php?error');
}

// imgある場合
// 添付ファイルの拡張子を確認する。
if (!empty($file_name)) {
    $extension = substr($file_name, -3);
    if ($extension != 'jpg' && $extension != 'gif' && $extension != 'png') {
        redirect('confirm.php?error=1');
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?= head_parts('確認画面','detail') ?>
</head>

<body id="main">
    
    <div class="wrapper">

        <!-- Head[Start] -->
        <header>
            <div class="navbar">
                <a class="navbar_btn" href="index.php">検索</a>
                <a class="navbar_btn" href="select.php">一覧</a>
                <a class="navbar_btn" href="favorite_act.php">お気に入り</a>
                <a class="navbar_btn" href="detail.php?id=<?= $No ?>">戻る</a>
                <!-- <a class="navbar_btn" href="detail.php?re-register=true">戻る</a> -->
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
                    <td colspan="2">
                        <form method="POST" action="update.php">
                            <div class="">
                                <fieldset>
                                    <label><textarea name="memo" rows="5" cols="40"><?= h($result['memo'])?></textarea></label><br>
                                    <input type="hidden" name="No" value = "<?= h($No)?>">
                                    <input class="btn" type="submit" value="保存">
                                </fieldset>
                            </div>
                        </form>
                    </td>

                    <td colspan="1" class="area_img">
                        <!-- 画像を挿入 -->
                        <?php if ($image_data) :?>
                            <div class="mb-3">
                                <img src="image.php">
                            </div>
                        <?php endif; ?>
                    </td>

                    <td colspan="1" class="area_favorite">
                        <a href="confirm.php" class="btn btn-tag btn-tag--ver"><i class="fas fa-star"></i>お気に入りに追加</a>
                    </td>
                </tr>

            </table>
        </div>

    </div>

</body>

</html>