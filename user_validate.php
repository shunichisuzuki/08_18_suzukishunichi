<?php
// 関数ファイルの読み込み
include('functions.php');

//1. GETデータ取得
$id   = $_GET['id'];

//2. DB接続します(エラー処理追加)
$pdo = db_conn();

//3．データ登録SQL作成
// $sql = 'DELETE FROM user_table where id=:id'; //★削除対象を選択する。
// $stmt = $pdo->prepare($sql);
// $stmt->bindValue(':id', $id, PDO::PARAM_INT);
// $status = $stmt->execute();

//life_flgをアクティブ:1から非アクティブ:0に。
$sql = 'UPDATE user_table SET life_flg=0 WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

//4．データ登録処理後
if ($status==false) {
    errorMsg($stmt);
} else {
    //select.phpへリダイレクト
    header('Location: user_select.php');
    exit;
}