<?php
require_once("dbconfig.php");
date_default_timezone_set('Asia/Seoul');

$busInfo = $_POST;

$date = date("Y-m-d H:i:s");
$sql = "INSERT INTO location(lat,lng,time,deviceID,full) VALUES ('".$busInfo['Vlat']."','".$busInfo['Vlng']."','".$date."','".$busInfo['VdeviceID']."','".$busInfo['Vfull']."')";// 따옴표 문자열로 변환
$db->query($sql);

$sql2 = "UPDATE businfo SET bustype='".$busInfo['Vroute']."' WHERE deviceID='".$busInfo['VdeviceID']."'";
$db->query($sql2); //businfo테이블에 경로를 업데이트 한다.

mysqli_close($db);

//http://feelsite.kro.kr/wp-content/plugins/YIUBUS/new_YIUBUS/GET_latlng.php?VdeviceID=1&Vroute=%EC%9D%BC%EC%82%B0&Vlat=37.4641088&Vlng=126.8291256&Vfull=y
?>