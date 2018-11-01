<?php
## ==== define db info
$db_host = 'localhost';
$db_user = 'root';
$db_passwd = '';
$db_name = '2018_docomo_hackathon';
$table = 'img_info';
## ====

function get_user_imagelist($user_id_arr){
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
  $sql = 'SELECT * FROM '.$db_name.'.'.$table.' WHERE ';
  for($i=0; $i<count($user_id_arr); $i++){
    if($i+1 < count($user_id_arr)){
      $sql .= 'subject="'.$user_id_arr[$i].'" or ';
    }else{
      $sql .= 'subject="'.$user_id_arr[$i].'";';
    }
  }
  #echo $sql.'<br/>';

  $res = mysqli_query($connect, $sql);
  if(!$res){
    die('Error: Run sql');
  }

  # close mysql
  mysqli_close($connect);

  # fetch result
  $img_list = array();
  while($row=mysqli_fetch_assoc($res)){
    array_push($img_list, $row['img_path']);
    #echo '<img src="'.$row['img_path'].'">';
    #echo '<br/>';
  }
  return $img_list;
}

function make_img_table($img_list, $col, $width_str, $height_str){
  $table = '<table align="center" class="table-striped">';
  for($i=0; $i < count($img_list); $i++){
    if(fmod($i+1, $col)==1){
      $table .= '<tr>';
    }
    $table .= '<td align="center">';
    $table .= '<img src="'.$img_list[$i].'" width="'.$width_str.'" height="'.$height_str.'">';
    $table .= '</td>';
    if(fmod($i+1, $col)==0){
      $table .= '</tr>';
    }
  }
  $table .= '</table>';
  return $table;
}


## main =======
#$user_id_arr = array('A', "A'");
$user_id_arr = array(htmlspecialchars($_POST['user_id']));
$col = htmlspecialchars($_POST['col']);;
$width_str = "80%";
$height_str = "80%";
$img_list = get_user_imagelist($user_id_arr);
$table = make_img_table($img_list, $col, $width_str, $height_str);
$tmp_str = "";
for($i=0; $i<count($user_id_arr); $i++){
  if($i+1 < count($user_id_arr)){
    $tmp_str .= $user_id_arr[$i]. ", ";
  }else{
    $tmp_str .= $user_id_arr[$i];
  }
}
echo '<center><h1 class="h1">['.$tmp_str.']さんの画像データ一覧</h1></center>';
echo $table;
?>