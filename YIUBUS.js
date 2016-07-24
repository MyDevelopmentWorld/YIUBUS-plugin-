function fn_send(route){
	finish_ajax = 0;
	selection_route = route;
	var lat,lng,level;
	
	if(route == "큰버스"){
		jQuery("#map").show();
		lat="37.232045";
		lng="127.189971";
		level="7";
		var bus_stations = [
      		{lat:"37.2280616",lng:"127.1712030",'station_name':"1번정류장"},
      		{lat:"37.2375990",lng:"127.1794920",'station_name':"2번정류장"},
      		{lat:"37.2359850",lng:"127.1894860",'station_name':"3번정류장"},
      		{lat:"37.2347640",lng:"127.1983800",'station_name':"4번정류장"},
      		{lat:"37.2352650",lng:"127.2057250",'station_name':"5번정류장"},
      		{lat:"37.2334310",lng:"127.2089470",'station_name':"6번정류장"},
      		{lat:"37.2364319",lng:"127.1884552",'station_name':"7번정류장"},
      		{lat:"37.2389490",lng:"127.1781080",'station_name':"8번정류장"},
      		{lat:"37.2381310",lng:"127.1781290",'station_name':"9번정류장"}
   		];
		var sPoint = [];
		var sMarker = []; //정류장 위치,마커
				
		 

				
		for(var i=0; i<bus_stations.length; i++){
			
			var sSize = new nhn.api.map.Size(30, 30);
 			var sOffset = new nhn.api.map.Size(14, 37);
 			var sIcon = new nhn.api.map.Icon('/wp-content/plugins/YIUBUS/images/bus_station.png', sSize, sOffset);
 			sMarker[i] = new nhn.api.map.Marker(sIcon, { title : '' });
 			sPoint[i] = new nhn.api.map.LatLng(Number(bus_stations[i].lat), Number(bus_stations[i].lng));
			sMarker[i].setPoint(sPoint[i]);
			oMap.addOverlay(sMarker[i]);
 		}
		
		
		
	}
	else if(route == "작은버스"){
		jQuery("#map").show();
		lat="37.4145972";
		lng="126.8546778";
		level="2";
	}
	else if(route == "강남"){
		jQuery("#map").show();
		lat="37.5145972";
		lng="126.7546778";
		level="3";
	}
	else if(route == "잠실"){
		jQuery("#map").show();
		lat="37.5145972";
		lng="126.7546778";
		level="4";
	}
	else if(route == "영등포"){
		jQuery("#map").show();
		lat="37.5145972";
		lng="126.7546778";
		level="5";
	}
	else if(route == "주안초등학교"){
		jQuery("#map").show();
		lat="37.5145972";
		lng="126.7546778";
		level="6";
	}
	else if(route == "안양"){
		jQuery("#map").show();
		lat="37.5145972";
		lng="126.7546778";
		level="7";
	}
	else if(route == "성남/분당"){
		jQuery("#map").show();
		lat="37.5145972";
		lng="126.7546778";
		level="8";
	}
	else if(route == "일산"){
		jQuery("#map").show();
		lat="37.5145972";
		lng="126.7546778";
		level="9";
	}
	else{
		alert("다시 선택해주세요");
	}
		var oPoint2 = new nhn.api.map.LatLng(lat,lng);
		oMap.setLevel(level);
		oMap.setCenter(oPoint2);

		initialize();
}

function initialize(){

 ajax_markers();
 //프로그래스바 이미지가 맵에 띄어지도록 작성
 //ajax_markers()의 sucess에서 가져온 값들을 이용하여 marker들을 모두 만들도록 작성
 //ajax_markers()의 sucess부분에 프로그래스바 이미지가 있다면 이미지 제거 없다면 무시  
 var interval = setInterval( function(){
  
  ajax_markers();
 	//alert("test");

 }, 4000 );

	setTimeout(function(){
		clearInterval(interval);
	},1000000);
 
};


function ajax_markers(){
	var oMarker = [];
	var oLabel = [];
    var oPoint3 = []; //같은 경로의 버스들의 위치가 들어갈 배열
    var bus_img;
jQuery.ajax({
	type:"POST",
	url:"/wp-content/plugins/YIUBUS/new_YIUBUS/process.php",
	data : {
		"route" : selection_route // 받아온 파라메터를 process 로 날린다.
	},
	dataType : "json",
	beforeSend : function(){
		if(finish_ajax == 0){ //ajax가 처음 실행 될때만 로딩화면 보여줌
			jQuery("#progress").html("<img src='/wp-content/plugins/YIUBUS/images/progress.gif'/>");
		}
	},
	success: function(res){

	if(res == null){
		alert("현재 운행중인 버스가 없습니다.");
		location.reload();
	}else{

		if (before_marker != null && before_label != null) { //이전 마커와 라벨이 있으면 삭제한다
			for(var i=0; i<before_marker.length; i++){
				oMap.removeOverlay(before_marker[i]);
				oMap.removeOverlay(before_label[i]);
			
			}
			
		}
	
	for(var i=0; i<res.length; i++){

    if(res[i].full=='true'){ //full일경우 빨간 버스아이콘 보여줌
    	bus_img = '/wp-content/plugins/YIUBUS/images/bus_full.png';
    }
    else{
    	bus_img = '/wp-content/plugins/YIUBUS/images/bus.png';
    }
 	var oSize = new nhn.api.map.Size(40, 50);
 	var oOffset = new nhn.api.map.Size(14, 37);
 	var oIcon = new nhn.api.map.Icon(bus_img, oSize, oOffset);
 	oMarker[i] = new nhn.api.map.Marker(oIcon, { title : '' });
 	oLabel[i] = new nhn.api.map.MarkerLabel();

    oPoint3[i] = new nhn.api.map.LatLng(Number(res[i].lat),Number(res[i].lng));
	oMarker[i].setPoint(oPoint3[i]);
	oMarker[i].setTitle(selection_route);
 	oMap.addOverlay(oMarker[i]);
 	oMap.addOverlay(oLabel[i]);
 	oLabel[i].setVisible(true, oMarker[i]);

   }
   before_marker = oMarker; //이전 마커를 지우기 위해
   before_label = oLabel;
}
    
	finish_ajax = 1;
	jQuery("#progress").empty();
	
  },
  error : function(xhr,error,status){
   console.log(xhr+"|"+error+"|"+status);
  }
 });
}


function del_markers(){
 for(var i=0; i<markers.length; i++){
  markers[i].setMap(null);
 }
}
