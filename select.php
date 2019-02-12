<?php
//1. DB接続
// $dbn ='mysql:dbname=gs_f02_db18;charset=utf8;port=3306;host=localhost';
// $user = 'root';
// $pwd = '';
// try {
//     $pdo = new PDO($dbn, $user, $pwd);
// } catch (PDOException $e) {
//     exit('dbError:'.$e->getMessage());
// }
include('functions.php');
$pdo = db_conn();

//SQL文におけるバッファを有効にする。
//gs_bm_table表の行数を数えるための処理のために必要。
$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

//2. データ表示SQL作成
$sql = 'SELECT * from gs_bm_table;';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

//gs_bm_table表の行数を数えるための処理。
$result = $pdo->query($sql);
$result->execute();
$row_cnt = $result->rowCount();

//3. データ表示
$view='';
if ($status==false) {
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit('sqlError:'.$error[2]);
} else {
    //Selectデータの数だけ自動でループしてくれる
    //http://php.net/manual/ja/pdostatement.fetch.php
    // while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //     $view .= '<li class="list-group-item">';
    //     $view .= '<p>'.$result['name'].'___'.'<a href="'.$result['url'].'" target="_blank">'.$result['name'].'</a>'.'___'.$result['comment'].'</p>';
    //     $view .= '</li>';
    // }

    //2件以上登録されている場合に全削除ボタンを表示
    if ($row_cnt>1) {
        $view .= '<a href="javascript:void(0);" onclick="var ok=confirm(\'本当にすべてを削除しますか？\'); if (ok) location.href=\'alldelete.php\'; return false;" class="badge badge-danger">ALL Delete</a>';
    }

    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $view .= '<li class="list-group-item">';
        $view .= '<p>'.$result['name'].'___'.'<a href="'.$result['url'].'" target="_blank">'.$result['name'].'</a>'.'___'.$result['comment'].'</p>';

        $view .= '<a href="detail.php?id='.$result['id'].'" class="badge badge-primary">Edit</a>';
        $view .= '<a href="javascript:void(0);" onclick="var ok=confirm(\'本当に削除しますか？\'); if (ok) location.href=\'delete.php?id='.$result['id'].'\'; return false;" class="badge badge-danger">Delete</a>';

        $view .= '</li>';
    }

    //2件以上登録されている場合に全削除ボタンを表示
    if ($row_cnt>1) {
        $view .= '<a href="javascript:void(0);" onclick="var ok=confirm(\'本当にすべてを削除しますか？\'); if (ok) location.href=\'alldelete.php\'; return false;" class="badge badge-danger">ALL Delete</a>';
    }
}
?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>書籍ブックマークと一覧表示</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <style>
        div {
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">お気に入り書籍一覧</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">書籍ブックマーク</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div>
        <ul class="list-group">
            <!-- ここにDBから取得したデータを表示しよう -->
            <?=$view?>
        </ul>
    </div>

</body>

</html>