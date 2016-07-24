<?php


$db = new mysqli('localhost','yiubus','yiubus','yiubus');

if($db->connect_error){
   die('데이터베이스 연결에 문제가 있습니다. 관리자에게 문의 바랍니다.');

}

   $db->set_charset('utf8');
?>
<?php

   $sql = "SELECT Secondplace FROM Place";
   $result = $db->query($sql); 
   $num = mysqli_num_rows($result); // 총 대수
   $i=0;
   $list;
      while($row = $result->fetch_assoc()){
      
         $arr = array('Secondplace'=>$row['Secondplace']);
         $list[$i] = $arr; // 전체 한 줄의 배열을 한번 더 배열 형태로 묶음 = > JSON 형태로 배열을 보내기 위함 
         $i++; 
      }
   echo json_encode($list,JSON_UNESCAPED_UNICODE);   
   
   

   ?>
