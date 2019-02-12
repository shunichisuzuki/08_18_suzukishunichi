<?php

// 入力チェック
if(
    !isset($_POST['bookname']) || $_POST['bookname']==''
){
    exit('ParamError');
}

//POSTデータ取得
$bookname = $_POST['bookname'];

$data = "https://www.googleapis.com/books/v1/volumes?q=".$bookname;
// var_dump($data);
$json = file_get_contents($data);
// var_dump($json);
// $json_decode = json_decode($json);
$json_decode = json_decode($json,true);
// print_r($json_decode);
    // echo "<pre>";
    // foreach($json_decode as $v) {
    //   var_dump($v["items"]);
    // }
    // echo "</pre>";
// var_dump($json_decode["items"][0]["volumeInfo"]["infoLink"]);
$ports = $json_decode["items"][0]["volumeInfo"]["infoLink"];
// var_dump($ports);

$comment = $_POST['comment'];

//DB接続
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

//データ登録SQL作成
$sql ='INSERT INTO gs_bm_table values (null,:a1,:a2,:a3,sysdate())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':a1', $bookname, PDO::PARAM_STR);    //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a2', $ports, PDO::PARAM_STR);   //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a3', $comment, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//４．データ登録処理後
if ($status==false) {
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit('sqlError:'.$error[2]);
} else {
    //５．index.phpへリダイレクト
    header('Location: index.php');
}
