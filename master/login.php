<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="./style.css" >
        <title>ログイン</title>
    </head>
    <header>
      <h1><div id="title" align="center"><font color="#ffffff">PicCom<br>
        PictureCommunication</font></div></h1>
    </header>
    <body>


<?php
## ==== define db info
$db_host = 'localhost';
$db_user = 'root';
$db_passwd = '';
$db_name = '2018_docomo_hackathon';
$table = 'user_img_info';
## ====


function check_user($id, $passwd){
  # define db info
  global $db_host;
  global $db_user;
  global $db_passwd;
  global $db_name;
  global $table;

  # connect mysql
  $connect = mysqli_connect($db_host, $db_user, $db_passwd);
  if(!$connect){
    die('Error: Mysql connect');
  }

  # connect DB
  $db_connect = mysqli_select_db($connect, $db_name);
  if(!$db_connect){
    die('Error: DB connect');
  }

  # run SQL
  $sql = 'SELECT * FROM '.$db_name.'.'.$table.' WHERE name="'.$id.'" and password="'.$passwd.'";';
  #echo $sql.'<br/>';

  # check isuser
  $res = mysqli_query($connect, $sql);
  if(!$res){
    die('Error: Run sql');
  }

  # close mysql
  mysqli_close($connect);

  # fetch result
  $img_list = array();
  $row = mysqli_fetch_assoc($res);
  if(empty($row)){
    return 0;
  }else{
    return 1;
  }
}

### main ========
$id = htmlspecialchars($_POST['user_id']);
$passwd = htmlspecialchars($_POST['passwd']);
$isuser = check_user($id, $passwd);
if(!$isuser){
  echo "Success: login";
}

# ===
$url = 'http://localhost/master/get_img.php';
$data = array(
    'user_id' => $id,
    'col' => 3,
);
$data = http_build_query($data, "", "&");
$header = array(
"Content-Type: application/x-www-form-urlencoded",
"Content-Length: ".strlen($data)
);
$options = array(
  'http' => array(
    'method' => 'POST',
    'header' => implode("\r\n", $header),
    'content' => $data,
  )
);
$options = stream_context_create($options);
$contents = file_get_contents($url, false, $options);
echo $contents;

?>

  <div align="center">
      <table border="0">
          <form>
            <input type="button" onClick="location.href='./start.html'" class="square_btn" value="スタート画面">
            <input type="button" onClick="location.href='./login.html'" class="square_btn" value="ログイン">
            <input type="button" onClick="location.href='./upload_img.php'" class="square_btn" value="画像アップロード">
          </form>
      </table>
  </div>

    </body>
</html>