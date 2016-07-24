<?php
/*

Plugin Name: YIUBUS

Plugin URI: feelsite.kro.kr

Description: 각 학교의 스쿨버스 위치를 확인할 수 있도록 만든 워드프레스 플러그인

Version: YIUBUS 1.0

Author: 전필원,이도영

Author URI: feelsite.kro.kr

License: Yongin Univ.

*/

function get_js_css(){
	wp_register_style( 'jsstyle', plugins_url( '/YIUBUS.css', __FILE__ ), false );
	wp_enqueue_style( 'jsstyle' );

	wp_register_script( 'jsfunction', plugins_url( '/YIUBUS.js', __FILE__ ),array('jquery') );
	wp_enqueue_script( 'jsfunction' );

}

function get_N_api(){
		echo '<script type="text/javascript" src=" http://openapi.map.naver.com/openapi/v2/maps.js?clientId=yZLkZBpGBUq9DPcRUD8i"></script>';
}


function get_map_html(){
	?>
	<ul id="navi">
	
	﻿<ul>
	<li class="group">	<div class="title"> 용인시내</div>
		<ul class="sub">
			<li onclick="fn_send('큰버스');">큰버스</li>
			<li onclick="fn_send('작은버스');">작은버스
		</ul>
	</li>
	<li class="group">	<div class="title"> 서울지역</div>
		<ul class="sub">
			<li onclick="fn_send('강남');">강남</li>
			<li onclick="fn_send('잠실');">잠실
			<li onclick="fn_send('영등포');">영등포
		</ul>
	</li>
	<li class="group">	<div class="title"> 인천지역</div>
		<ul class="sub">
			<li onclick="fn_send('주안초등학교');">주안초등학교</li>
		</ul>
	</li>
	<li class="group">	<div class="title"> 경기지역</div>
		<ul class="sub">
			<li onclick="fn_send('안양');">안양</li>
			<li onclick="fn_send('성남/분당');">성남/분당
			<li onclick="fn_send('일산');">일산
		</ul>
	</li>
</ul>
	<div id="map_area" >
	<div id="progress"></div>
	<div class="view_map" id="map"></div>
	</div>

<script>
	var finish_ajax = 0;
	var before_marker = []; //이전마커를 담고 새로운 마커생성되면 이전마커를 삭제하기 위함
	var before_label = []; //이전라벨을 담고 새로운 라벨생성되면 이전마커를 삭제하기 위함
	var selection_route;
	var locations = [];
	var oPoint = new nhn.api.map.LatLng(37.2270152,127.1678561);
	var oMap = new nhn.api.map.Map("map", {
 	point : oPoint, // 지도 중심점의 좌표
 	zoom : 12, // 지도의 축척 레벨
 	//boundary : Array // 지도 생성 시 주어진 array 에 있는 점이 모두 보일 수 있도록 지도를 초기화한다.
 	//boundaryOffset : Number // boundary로 지도를 초기화 할 때 지도 전체에서 제외되는 영역의 크기.
 	enableWheelZoom : true, // 마우스 휠 동작으로 지도를 확대/축소할지 여부
 	enableDragPan : true, // 마우스로 끌어서 지도를 이동할지 여부
 	enableDblClickZoom : true, // 더블클릭으로 지도를 확대할지 여부
 	mapMode : 0, // 지도 모드(0 : 일반 지도, 1 : 겹침 지도, 2 : 위성 지도)
 	//activateTrafficMap : true, // 실시간 교통 활성화 여부
 	//activateBicycleMap : true, // 자전거 지도 활성화 여부
 	minMaxLevel : [1, 14], // 지도의 최소/최대 축척 레벨
 	size : new nhn.api.map.Size(600, 600) // 지도의 크기
 	//detectCoveredMarker : Boolean // 겹쳐 있는 마커를 클릭했을 때 겹친 마커 목록 표시 여부
	});

	
	
	var mapZoom = new nhn.api.map.ZoomControl(); // - 줌 컨트롤 선언
	mapZoom.setPosition({left:10, top:10});
	oMap.addControl(mapZoom);

	var oTraffic = new nhn.api.map.TrafficMapBtn(); // - 실시간 교통 지도 버튼 선언
    oTraffic.setPosition({top:10, right:110});
	oMap.addControl(oTraffic);

	var oTrafficGuide = new nhn.api.map.TrafficGuide(); // - 교통 범례 선언
    oTrafficGuide.setPosition({
    	bottom : 30,
    	left : 10
    });  // - 교통 범례 위치 지정.
    oMap.addControl(oTrafficGuide);

	
	
    mapTypeChangeButton = new nhn.api.map.MapTypeBtn(); //지도 타입 버튼 선언
    mapTypeChangeButton.setPosition({top:10, left:50});
    oMap.addControl(mapTypeChangeButton);

	jQuery("#map").hide();

	jQuery(window).resize(function(){
		var minHeight = 300;
		var resizeWidth = jQuery("#map_area").width();
		var resizeHeight = resizeWidth / 2;
		resizeHeight = (resizeHeight < minHeight ? minHeight : resizeHeight);
		oMap.setSize(new nhn.api.map.Size(resizeWidth, resizeHeight));
	});

    jQuery(document).ready(function(){
        
        //모든 서브 메뉴 감추기
        jQuery(".sub").css({display:"none"}); 
        //$(".sub").hide(); //위코드와 동일 

        jQuery(".title").click(function(){
            //일단 서브메뉴 다 가립니다.
            //$(".sub").css({display:"none"});
            
            //열린 서브메뉴에 대해서만 가립니다.
            jQuery(".sub").each(function(){
                //console.log(jQuery(this).css("display"));
                if(jQuery(this).css("display")=="block") {
                    //$(".sub").css({display:"none"});
                    //$(this).hide();
                    jQuery(this).slideUp("fast");
                }
            });

            //현재 요소의 다음 요소를 보이게 합니다.
            //$(this).next("ul").css({display:"block"});
            //$(this).next("ul").show();
            jQuery(this).next("ul").slideDown("fast");


        })
    });

</script>
	<?php
}

add_action( 'wp_enqueue_scripts', 'get_js_css' );
add_action( 'wp_head', 'get_N_api' ); //contain the Naver_openapi
add_shortcode( 'YIUBUS', 'get_map_html' );
