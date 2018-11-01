<?php
## ==== define db info
$db_host = 'localhost';
$db_user = 'root';
$db_passwd = '';
$db_name = '2018_docomo_hackathon';
$table = 'user_img_info';
## ====

function save_img($img, $save_dir, $photographer){
  $img_tmp_name = htmlspecialchars($img['tmp_name']);
  $kakutyoushi = substr(htmlspecialchars($img['name']), mb_strpos(htmlspecialchars($img['name']), '.', 0));  
 
  date_default_timezone_set('Asia/Tokyo');
  $date = date("YmdHis");
  $img_name = $photographer."_".$date.$kakutyoushi;
  if(is_uploaded_file($img_tmp_name)){
    /*
    if(!file_exists($save_dir)){
      mkdir('upload');
    }
    */
    $file = $save_dir.basename($img_name);
    if(move_uploaded_file($img_tmp_name, $file )) {
      echo '<p><img src="', $img_name, '"></p>';
    } else {
      #echo "error: ";
    }
  } else {
    #echo 'not select';
  }
  return $save_dir.$img_name;
}

function insert_user_info($user, $password, $face_img_path){
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
  $sql = 'INSERT INTO '.$db_name.'.'.$table.' (name, password, img_path) VALUES ("'.$user.'", "'.$password.'", "'.$face_img_path.'");';
  #echo $sql."<br/>";
  $res = mysqli_query($connect, $sql);
  if(!$res){
      die('Error: Run sql');
  }  
  # close mysql
  mysqli_close($connect);
}

## main ======
# “ü—Íî•ñ‚ÌŽæ“¾
$name = htmlspecialchars($_POST['user_id']);
$password = htmlspecialchars($_POST['password']);
# ‰æ‘œî•ñ‚ÌŽæ“¾
$img = $_FILES["upfile"];

# save img
#print_r($img);
$save_dir = './user_data/';
$photographer = "";
$img_path = save_img($img, $save_dir, $photographer);

insert_user_info($name, $password, $img_path);

#==
$url = 'http://localhost/master/get_img.php';
$data = array(
    'user_id' => $name,
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
#echo '<meta http-equiv="refresh" content="0;'.$url.'">';
?>


