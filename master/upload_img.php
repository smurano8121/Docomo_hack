<?php

## ==== define db info
$db_host = 'localhost';
$db_user = 'root';
$db_passwd = '';
$db_name = '2018_docomo_hackathon';
$table = 'img_info';
## ====

function insert_img_info($subject_arr, $photographer, $img_path, $photogprapher){
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
  for($i=0; $i<count($subject_arr); $i++){
    $sql = 'INSERT INTO 2018_docomo_hackathon.img_info (img_path, photographer, subject) values ("'.$img_path.'", "'.$photographer.'", '.'"'.$subject_arr[$i].'");';
    #echo $sql."<br/>";
    $res = mysqli_query($connect, $sql);
    if(!$res){
        die('Error: Run sql');
    }
  }
  
  # close mysql
  mysqli_close($connect);
}

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
      #echo "success: ".$file;
      #echo '<p><img src="', $file, '"></p>';
    } else {
      #echo "error: ";
    }
  } else {
    #echo 'not select';
  }
  return $save_dir.$img_name;

}


## main ======
# “ü—Íî•ñ‚Ìæ“¾
#$subject_arr = explode(',', htmlspecialchars($_POST['subject']));
$photographer = htmlspecialchars($_POST['photographer']);
if($photographer==null){
  $photographer = "anonymous";
}
# ‰æ‘œî•ñ‚Ìæ“¾
$img = $_FILES["upfile"];

# save img
#print_r($img);
$save_dir = './by_upload/';
$img_path = save_img($img, $save_dir, $photographer);

#insert_img_info($subject_arr, $photographer, $img_path, $photographer);
$cmd = 'activate py36 && python sqlinsert.py '.$photographer.' '.$img_path;
exec($cmd);

$url = "http://localhost/master/upload_img.html";
echo '<meta http-equiv="refresh" content="0;'.$url.'">';
?>