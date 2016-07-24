<?php
//디비에서 위치값 가져오기위한 ajax용 php
	require_once("dbconfig.php"); //mysql 접속
	date_default_timezone_set('Asia/Seoul'); //아시아 서울 기준 시간 
	
	header("Content-Type:application/json");

	$Pid = $_POST["route"]; // mlocation.php 에서 버스 소분류 받아옴
	$sql = "select TB.lat, TB.lng,TB.deviceID, TB.time, TB.full
			from businfo TA, location TB
			where TA.deviceID = TB.deviceID 
           	and TB.time = (select max(time) from location TC where TC.deviceID = TA.deviceID)
			and TA.bustype = '".$Pid."'";
			/* Tip 
			 - 2*2  , 3*3  쪼인하면 row 수는 곱하기가 되고 컬럼은 더하기가 된다 */
	//날라온 버스타입 값과 businfo 버스타입이 같은 디바이스 id 값들을 location 에서  각 값에 최신값 만 뽑아냄 
		 
	
	$result = $db->query($sql);
	$num = mysqli_num_rows($result); // 총 대수
	$i=0;
	$list;
	$success = false;
		while($row = $result->fetch_assoc()){
			
		//	echo $row['lat'];
		//	echo $row['lng'];
		//	echo $row['time'];
			$arr = array('deviceID'=>$row['deviceID'],'lat'=>$row['lat'],'lng'=>$row['lng'],'full'=>$row['full']);
//			$arr = array('deviceID'=>"test",'lat'=>"test",'lng'=>"test",'full'=>"test");
			$list[$i] = $arr; // 전체 한 줄의 배열을 한번 더 배열 형태로 묶음 = > JSON 형태로 배열을 보내기 위함 
			$i++;
			$success=true;
		}

	if($success==true){
		 echo json_encode($list);
	}
	else{
		echo "null";
	}
?>		