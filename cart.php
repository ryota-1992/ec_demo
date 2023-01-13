<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>カート</title>
  <link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>
  <body>
    <h1>カートに入っている商品</h1>
    <?php
    $productName=array();
    $productImagePath=array();
    $introduce=array();
    $price=array();
    $total=0;
    session_start();
    if(!empty($_SESSION['cart'])){
      $count=count($_SESSION['cart']);
    }else{
      $count=0;
    }

        $hostname = "localhost";    //ホスト名
        $userid = "root";    //データベースユーザ名
        $passwd = "";    //接続パスワード
        $dbname = "yq";    //データベース名
        $con=mysqli_connect($hostname,$userid,$passwd,$dbname);   //db接続に必要な情報を変数に入れる

                // 接続状況をチェックします
        if (mysqli_connect_errno()) {
            die("データベースに接続できません:" . mysqli_connect_error() . "\n");
        }

        $con->set_charset('utf8');
  if($count!=0){
        for($i=0; $i<$count; $i++){
          /* プリペアドステートメントを作成します */
          if ($stmt = mysqli_prepare($con, "SELECT productName,productImagePath,introduce,price FROM product WHERE id = ?")) {

              /* マーカにパラメータをバインドします */
              mysqli_stmt_bind_param($stmt, "s", $_SESSION['cart'][$i]);

              /* クエリを実行します */
              mysqli_stmt_execute($stmt);

              /* 結果変数をバインドします */
              mysqli_stmt_bind_result($stmt, $productName[$i],$productImagePath[$i],$introduce[$i],$price[$i]);


              /* 値を取得します */
              mysqli_stmt_fetch($stmt);

              /* ステートメントを閉じます */
              mysqli_stmt_close($stmt);
          }
    }
  }
        // 接続を閉じます
        mysqli_close($con);

     ?>

<script>
/**
 * 確認ダイアログの返り値によりフォーム送信
*/
function logoutChk () {
    /* 確認ダイアログ表示 */
    var flag = confirm ( "本当にログアウトしますか？\n\n");
    /* send_flg が TRUEなら送信、FALSEなら送信しない */
    return flag;
}

</script>


<!– 左サイドメニュー –>
<div class="leftmenu">
<div class="subinfo"> <?php

          if(isset($_SESSION['name'])){
            echo "ようこそ、".$_SESSION['name']."さん！";
          }else{
            header('Location:login.php');
            exit;

          }
 ?>

 </div>

<!– メニュー –>
<div class="subinfo">
  <form  action="cart.php" method="post">
    <input type="submit" name="toCart" value="カートの中身を確認する">
  </form>
  <form  method="post" action="product_list.php" onsubmit="return logoutChk()">
    <input type="submit" name="logout" value="ログアウト">
  </form>
  <form  method="post" action="product_list.php" >
    <input type="submit" name="toList" value="商品一覧に戻る">
  </form>
</div>

</div>


<div class="center">

  <div class="container">

<table width="100%">
  <?php
  if($count==0){
    echo "カートに何も入っていません。";
  }else{
    for($j=0; $j<$count; $j++){
  ?>


    <div class="item">
      <tr>

    <td width="20%"><img src="<?=$productImagePath[$j]; ?>" width="160" height="100"></td>
    <td width="10%"><?php echo htmlspecialchars($productName[$j],ENT_QUOTES,'UTF-8');?></td>
    <td width="40%"><?php echo htmlspecialchars($introduce[$j],ENT_QUOTES,'UTF-8'); ?></td>
    <td width="10%"><?php echo $_SESSION['quantity'][$j]."個";?>
    <td width="20%"><?php echo "￥".$price[$j]*$_SESSION['quantity'][$j]; ?></td>

    </td>
    </tr>
    </div>




  <?php
    $total+=$_SESSION['quantity'][$j]*$price[$j];
    }

  }
  ?>
  </div>
  </table>
  <div class="total">
    合計金額: <?=$total?>
  </div>
</br>
  <input type="submit" name="purchase" value="購入する">
</div>

  </body>
</html>