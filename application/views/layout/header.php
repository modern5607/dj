<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta http-equiv="imagetoolbar" content="no">
    
	<title><?php echo $siteTitle?></title>
	<!--link rel="stylesheet" href="<?php echo base_url('/_static/css/bootstrap.css?ver=20200725'); ?>"-->
	<link rel="stylesheet" href="<?php echo base_url('/_static/css/default_cj.css?ver=20200725'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('/_static/css/form.css?ver=20200725'); ?>">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<script src="<?php echo base_url('/_static/js/jquery-1.12.4.min.js'); ?>"></script>
	<script src="<?php echo base_url('/_static/js/common.js'); ?>"></script>
	


</head>
<body>
<?php date_default_timezone_set('Asia/Seoul'); ?>
<div id="smart_container">

	<div class="menu_Content">
        <div class="scroll">
            <div class="mControl">
                        <span class="mhide"><i class="material-icons">close</i></span>
                    </div>
                  
			<div id="" class="mcont_bh">
                <a href="<?php echo base_url('')?>"><img src="<?php echo base_url("_static/img/dj_logo.png");?>" width="140"></a>
                <div class="login_b">

                        <?php
				if(!empty($this->session->userdata('user_id'))){
				?>
                        <a href="<?php echo base_url('register/logout')?>" class="l_btn">로그아웃</a>
                        <?php
				}else{	
				?>
                        <a href="<?php echo base_url('register/login')?>" class="l_btn">로그인</a>
                        <?php
				}	
				?>
                    </div>
			</div>
            
            <div class="mcont_bd">
            
                <ul id="menuContent">
                
                    <li class="menu01_li">
                        <a href="<?php echo base_url('MDM')?>" class="menu_a <?php echo ($this->data['pos'] == "MDM")?"on":"";?>">기준정보</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "MDM")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('MDM')?>" class="<?php echo ($this->data['subpos'] == NULL || $this->data['subpos'] == 'index')?"on":"";?>"><i class="material-icons">build</i>공통코드관리</a></li>
                            <li><a href="<?php echo base_url('MDM/component')?>" class="<?php echo ($this->data['subpos'] == "component")?"on":"";?>"><i class="material-icons">wysiwyg</i>자재관리</a></li>
							<li><a href="<?php echo base_url('MDM/series')?>" class="<?php echo ($this->data['subpos'] == "series")?"on":"";?>"><i class="material-icons">dvr</i>시리즈관리</a></li>
							<li><a href="<?php echo base_url('MDM/items')?>" class="<?php echo ($this->data['subpos'] == "items")?"on":"";?>"><i class="material-icons">dvr</i>품목관리</a></li>
							<li><a href="<?php echo base_url('MDM/color')?>" class="<?php echo ($this->data['subpos'] == "color")?"on":"";?>"><i class="material-icons">dvr</i>품목색상관리</a></li>
                            <li><a href="<?php echo base_url('MDM/biz')?>" class="<?php echo ($this->data['subpos'] == "biz")?"on":"";?>"><i class="material-icons">build_circle</i>업체관리</a></li>
							<li><a href="<?php echo base_url('MDM/infolist')?>" class="<?php echo ($this->data['subpos'] == "infolist")?"on":"";?>"><i class="material-icons">build_circle</i>배송지관리</a></li>
                            <li><a href="<?php echo base_url('MDM/infoform')?>" class="<?php echo ($this->data['subpos'] == "infoform")?"on":"";?>"><i class="material-icons">build_circle</i>인사정보관리</a></li>
							
                        </ul>
                    </li>
					<li class="menu01_li">
                        <a href="<?php echo base_url('PO/p1')?>" class="menu_a <?php echo ($this->data['pos'] == "PO")?"on":"";?>" >구매관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "PO")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('PO/p1')?>" class="<?php echo ($this->data['subpos'] == 'p1')?"on":"";?>"><i class="material-icons">layers</i>자재입고</a></li>
							<li><a href="<?php echo base_url('PO/p2')?>" class="<?php echo ($this->data['subpos'] == 'p2')?"on":"";?>"><i class="material-icons">layers</i>자재입고현황</a></li>
							<li><a href="<?php echo base_url('PO/p3')?>" class="<?php echo ($this->data['subpos'] == 'p3')?"on":"";?>"><i class="material-icons">layers</i>자재출고현황</a></li>
                            
                        </ul>
                    </li>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('PLN')?>" class="menu_a <?php echo ($this->data['pos'] == "PLN")?"on":"";?>">주문/계획</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "PLN")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('PLN')?>" class="<?php echo ($this->data['subpos'] == NULL || $this->data['subpos'] == 'index')?"on":"";?>"><i class="material-icons">dvr</i>수주관리</a></li>
                            <!-- <li><a href="<?php echo base_url('PLN/p1')?>" class="<?php echo ($this->data['subpos'] == "p1")?"on":"";?>"><i class="material-icons">dvr</i>주문대비진행현황</a></li> -->
                            <li><a href="<?php echo base_url('PLN/p2')?>" class="<?php echo ($this->data['subpos'] == "p2")?"on":"";?>"><i class="material-icons">dvr</i>생산계획등록</a></li>
                            <li><a href="<?php echo base_url('PLN/p3')?>" class="<?php echo ($this->data['subpos'] == "p3")?"on":"";?>"><i class="material-icons">dvr</i>생산계획조회</a></li>
                        </ul>
                    </li>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('ORD/o1')?>" class="menu_a <?php echo ($this->data['pos'] == "ORD")?"on":"";?>">공정별작업지시</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "ORD")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('ORD/o1')?>" class="<?php echo ($this->data['subpos'] == "o1")?"on":"";?>"><i class="material-icons">dvr</i>성형작업지시</a></li>
                            <li><a href="<?php echo base_url('ORD/o2')?>" class="<?php echo ($this->data['subpos'] == "o2")?"on":"";?>"><i class="material-icons">dvr</i>정형작업지시</a></li>
                            <li><a href="<?php echo base_url('ORD/o3')?>" class="<?php echo ($this->data['subpos'] == "o3")?"on":"";?>"><i class="material-icons">dvr</i>시유작업지시</a></li>
                        </ul>
                    </li>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('ACT/a2')?>" class="menu_a <?php echo ($this->data['pos'] == "ACT")?"on":"";?>" >생산관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "ACT")?"style='display:block'":"";?>>
                            
							<!-- <li><a href="<?php echo base_url('ACT')?>" class="<?php echo ($this->data['subpos'] == NULL)?"on":"";?>"><i class="material-icons">memory</i>작업지시등록</a></li> -->
                            <!-- <li><a href="<?php echo base_url('ACT/a1')?>" class="<?php echo ($this->data['subpos'] == "a1")?"on":"";?>"><i class="material-icons">memory</i>공정별작업지시</a></li> -->
                            <li><a href="<?php echo base_url('ACT/a2')?>" class="<?php echo ($this->data['subpos'] == "a2")?"on":"";?>"><i class="material-icons">memory</i>건조(재고)현황</a></li>
                            <!-- <li><a href="<?php echo base_url('ACT/a3')?>" class="<?php echo ($this->data['subpos'] == "a3")?"on":"";?>"><i class="material-icons">memory</i>공정별 작업보고관리</a></li> -->
							
							
							<!-- <li><a href="<?php echo base_url('ACT/an2')?>" class="<?php echo ($this->data['subpos'] == "an2")?"on":"";?>"><i class="material-icons">memory</i>불량보고관리</a></li> -->
							<li><a href="<?php echo base_url('ACT/an3')?>" class="<?php echo ($this->data['subpos'] == "an3")?"on":"";?>"><i class="material-icons">memory</i>선별작업일지</a></li>
							
							<li><a href="<?php echo base_url('ACT/a4')?>" class="<?php echo ($this->data['subpos'] == "a4")?"on":"";?>"><i class="material-icons">memory</i>공정별진행내역</a></li>

							<li><a href="<?php echo base_url('ACT/an4')?>" class="<?php echo ($this->data['subpos'] == "an4")?"on":"";?>"><i class="material-icons">memory</i>생산현황판-사무동</a></li>
							<li><a href="<?php echo base_url('ACT/an5')?>" class="<?php echo ($this->data['subpos'] == "an5")?"on":"";?>"><i class="material-icons">memory</i>생산현황판-공장동</a></li>

							<li><a href="<?php echo base_url('ACT/a5')?>" class="<?php echo ($this->data['subpos'] == "a5")?"on":"";?>"><i class="material-icons">memory</i>기간별생산실적</a></li>
							<li><a href="<?php echo base_url('ACT/a6')?>" class="<?php echo ($this->data['subpos'] == "a6")?"on":"";?>"><i class="material-icons">memory</i>공정별수율관리</a></li>
							<li><a href="<?php echo base_url('ACT/a7')?>" class="<?php echo ($this->data['subpos'] == "a7")?"on":"";?>"><i class="material-icons">memory</i>공정별불량관리</a></li>
							<li><a href="<?php echo base_url('ACT/an1')?>" class="<?php echo ($this->data['subpos'] == "an1")?"on":"";?>"><i class="material-icons">memory</i>수주대비 출고내역</a></li>

						</ul>
					</li>

					<li class="menu01_li">
                        <a href="<?php echo base_url('ACT2/a8')?>" class="menu_a <?php echo ($this->data['pos'] == "ACT2")?"on":"";?>" >실적관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "ACT2")?"style='display:block'":"";?>>

							<li><a href="<?php echo base_url('ACT2/a8')?>" class="<?php echo ($this->data['subpos'] == "a8")?"on":"";?>"><i class="material-icons">memory</i>성형실적</a></li>
							<li><a href="<?php echo base_url('ACT2/a8_1')?>" class="<?php echo ($this->data['subpos'] == "a8_1")?"on":"";?>"><i class="material-icons">memory</i>성형실적현황</a></li>
							<li><a href="<?php echo base_url('ACT2/a9')?>" class="<?php echo ($this->data['subpos'] == "a9")?"on":"";?>"><i class="material-icons">memory</i>정형실적</a></li>
							<li><a href="<?php echo base_url('ACT2/a9_1')?>" class="<?php echo ($this->data['subpos'] == "a9_1")?"on":"";?>"><i class="material-icons">memory</i>정형BK</a></li>
							<li><a href="<?php echo base_url('ACT2/a9_2')?>" class="<?php echo ($this->data['subpos'] == "a9_2")?"on":"";?>"><i class="material-icons">memory</i>정형실적현황</a></li>
							<li><a href="<?php echo base_url('ACT2/a10')?>" class="<?php echo ($this->data['subpos'] == "a10" || $this->data['subpos'] == "a10_1")?"on":"";?>"><i class="material-icons">memory</i>시유실적</a></li>
							<li><a href="<?php echo base_url('ACT2/a10_2')?>" class="<?php echo ($this->data['subpos'] == "a10_2")?"on":"";?>"><i class="material-icons">memory</i>시유실적현황</a></li>
							<li><a href="<?php echo base_url('ACT2/a11')?>" class="<?php echo ($this->data['subpos'] == "a11")?"on":"";?>"><i class="material-icons">memory</i>선별작업실적</a></li>
							<li><a href="<?php echo base_url('ACT2/a11_1')?>" class="<?php echo ($this->data['subpos'] == "a11_1")?"on":"";?>"><i class="material-icons">memory</i>선별작업실적2</a></li>
							<li><a href="<?php echo base_url('ACT2/a11_2')?>" class="<?php echo ($this->data['subpos'] == "a11_2")?"on":"";?>"><i class="material-icons">memory</i>선별실적현황</a></li>
							<li><a href="<?php echo base_url('ACT2/a12')?>" class="<?php echo ($this->data['subpos'] == "a12")?"on":"";?>"><i class="material-icons">memory</i>후처리</a></li>
							<li><a href="<?php echo base_url('ACT2/a12_1')?>" class="<?php echo ($this->data['subpos'] == "a12_1")?"on":"";?>"><i class="material-icons">memory</i>후처리현황</a></li>
                        </ul>
                    </li>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('AMT/am1')?>" class="menu_a <?php echo ($this->data['pos'] == "AMT")?"on":"";?>" >재고/수불관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "AMT")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('AMT/am1')?>" class="<?php echo ($this->data['subpos'] == "am1")?"on":"";?>"><i class="material-icons">memory</i>완제품 재고내역</a></li>
                            <li><a href="<?php echo base_url('AMT/am1_1')?>" class="<?php echo ($this->data['subpos'] == "am1_1")?"on":"";?>"><i class="material-icons">memory</i>성형/정형 재고내역</a></li>
                            <li><a href="<?php echo base_url('AMT/am1_2')?>" class="<?php echo ($this->data['subpos'] == "am1_2")?"on":"";?>"><i class="material-icons">memory</i>시유 재고내역</a></li>
							<!-- <li><a href="<?php echo base_url('AMT/am2')?>" class="<?php echo ($this->data['subpos'] == "am2")?"on":"";?>"><i class="material-icons">memory</i>재생 재고내역</a></li> -->
							<li><a href="<?php echo base_url('AMT/am3')?>" class="<?php echo ($this->data['subpos'] == "am3")?"on":"";?>"><i class="material-icons">memory</i>재고조정</a></li>
							<li><a href="<?php echo base_url('AMT/am3_1')?>" class="<?php echo ($this->data['subpos'] == "am3_1")?"on":"";?>"><i class="material-icons">memory</i>재고조정현황</a></li>
							<li><a href="<?php echo base_url('AMT/am4')?>" class="<?php echo ($this->data['subpos'] == "am4")?"on":"";?>"><i class="material-icons">memory</i>출고등록</a></li>
							<li><a href="<?php echo base_url('AMT/am5')?>" class="<?php echo ($this->data['subpos'] == "am5")?"on":"";?>"><i class="material-icons">memory</i>기간별/업체별 출고내역</a></li>
							<li><a href="<?php echo base_url('AMT/am6')?>" class="<?php echo ($this->data['subpos'] == "am6")?"on":"";?>"><i class="material-icons">memory</i>클레임등록</a></li>
                        </ul>
                    </li>
                    <!--li class="menu01_li">
                        <a href="<?php echo base_url('ass/s1')?>" class="menu_a <?php echo ($this->data['pos'] == "ass")?"on":"";?>">
                        
                        조립생산관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "ass")?"style='display:block'":"";?>>
							<li><a href="<?php echo base_url('ass/s1')?>" class="<?php echo ($this->data['subpos'] == 's1')?"on":"";?>">생산계획 등록</a></li>
                            <li><a href="<?php echo base_url('ass/s2')?>" class="<?php echo ($this->data['subpos'] == 's2')?"on":"";?>">생산계획 조회</a></li>
                            <li><a href="<?php echo base_url('ass/s3')?>" class="<?php echo ($this->data['subpos'] == 's3')?"on":"";?>">계획대비실적현황</a></li>
                            <li><a href="<?php echo base_url('ass/s4')?>" class="<?php echo ($this->data['subpos'] == 's4')?"on":"";?>">작업지시등록</a></li>
                            <li><a href="<?php echo base_url('ass/barcode')?>" class="<?php echo ($this->data['subpos'] == 'barcode')?"on":"";?>">선별라벨발행</a></li>
                            <li><a href="<?php echo base_url('ass/asslist1')?>" class="<?php echo ($this->data['subpos'] == 'asslist1')?"on":"";?>">제작완료실적수신</a></li>
                            <li><a href="<?php echo base_url('ass/asslist2')?>" class="<?php echo ($this->data['subpos'] == 'asslist2')?"on":"";?>">검사정보실적수신</a></li>
                            <li><a href="<?php echo base_url('ass/s5')?>" class="<?php echo ($this->data['subpos'] == 's5')?"on":"";?>">작업일보</a></li>
                            <li><a href="<?php echo base_url('ass/s6')?>" class="<?php echo ($this->data['subpos'] == 's6')?"on":"";?>">생산진행현황</a></li>
                            <li><a href="<?php echo base_url('ass/asslist3')?>" class="<?php echo ($this->data['subpos'] == 'asslist3')?"on":"";?>">솔더실적관리</a></li>
                        </ul>
                    </li-->
                    <!--li class="menu01_li">
                        <a href="<?php echo base_url('rel/r1')?>" class="menu_a <?php echo ($this->data['pos'] == "rel")?"on":"";?>">
                        
                        재고/수불관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "rel")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('rel/r1')?>" class="<?php echo ($this->data['subpos'] == 'r1')?"on":"";?>">출고등록</a></li>
                            <li><a href="<?php echo base_url('rel/r2')?>" class="<?php echo ($this->data['subpos'] == 'r2')?"on":"";?>">기간별/업체별 출고내역</a></li>
                            <li><a href="<?php echo base_url('rel/r3')?>" class="<?php echo ($this->data['subpos'] == 'r3')?"on":"";?>">재공품내역</a></li>
                            <li><a href="<?php echo base_url('rel/r4')?>" class="<?php echo ($this->data['subpos'] == 'r4')?"on":"";?>">클래임 등록</a></li>
                            <li><a href="<?php echo base_url('rel/r5')?>" class="<?php echo ($this->data['subpos'] == 'r5')?"on":"";?>">클래임 내역조회</a></li>
                            <li><a href="<?php echo base_url('rel/rview')?>" class="<?php echo ($this->data['subpos'] == 'rview')?"on":"";?>">생산현황판</a></li>
                        </ul>
                    </li-->
                    <!--li class="menu01_li">
                        <a href="<?php echo base_url('mat/matform')?>" class="menu_a <?php echo ($this->data['pos'] == "mat")?"on":"";?>">
                        
                        자재관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "mat")?"style='display:block'":"";?>>
                            <li><a href="<?php echo base_url('mat/matform')?>" class="<?php echo ($this->data['subpos'] == 'matform')?"on":"";?>">자재입고등록</a></li>
                            <li><a href="<?php echo base_url('mat/materials')?>" class="<?php echo ($this->data['subpos'] == 'materials')?"on":"";?>">재고실사관리</a></li>
                            <li><a href="<?php echo base_url('mat/stocklist')?>" class="<?php echo ($this->data['subpos'] == 'stocklist')?"on":"";?>">재고현황</a></li>
                            <li><a href="<?php echo base_url('mat/m1')?>" class="<?php echo ($this->data['subpos'] == 'm1')?"on":"";?>">안전재고등록</a></li>
                            <li><a href="<?php echo base_url('mat/m2')?>" class="<?php echo ($this->data['subpos'] == 'm2')?"on":"";?>">안전재고현황</a></li>
                        </ul>
                    </li-->
                    <li class="menu01_li">
                        <a href="<?php echo base_url('kpi/equip1')?>" class="menu_a <?php echo ($this->data['pos'] == "kpi")?"on":"";?>">KPI</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "kpi")?"style='display:block'":"";?>>
                            <!--li><a href="">메뉴등록</a></li-->
                            <li><a href="<?php echo base_url('kpi/equip1')?>" class="<?php echo ($this->data['subpos'] == 'equip1')?"on":"";?>">반품감소율 차트</a></li>
                            <li><a href="<?php echo base_url('kpi/fair1')?>" class="<?php echo ($this->data['subpos'] == 'fair1')?"on":"";?>">전기에너지 절감율 차트</a></li>
                            <li><a href="<?php echo base_url('kpi/short1')?>" class="<?php echo ($this->data['subpos'] == 'short1')?"on":"";?>">납기단축 차트</a></li>
                            <li><a href="<?php echo base_url('kpi/equip2')?>" class="<?php echo ($this->data['subpos'] == 'equip2')?"on":"";?>">반품감소율 리스트</a></li>
                            <li><a href="<?php echo base_url('kpi/fair2')?>" class="<?php echo ($this->data['subpos'] == 'fair2')?"on":"";?>">전기에너지 절감율 리스트</a></li>
                            <li><a href="<?php echo base_url('kpi/short2')?>" class="<?php echo ($this->data['subpos'] == 'short2')?"on":"";?>">납기단축 리스트</a></li>
                        </ul>
                    </li>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('SYS/menu')?>" class="menu_a <?php echo ($this->data['pos'] == "SYS")?"on":"";?>">시스템관리</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "SYS")?"style='display:block'":"";?>>
							<li><a href="<?php echo base_url('SYS/menu')?>" class="<?php echo ($this->data['subpos'] == 'menu')?"on":"";?>"><i class="material-icons">inbox</i>메뉴권한관리</a></li>
                            <li><a href="<?php echo base_url('SYS/user')?>" class="<?php echo ($this->data['subpos'] == 'user')?"on":"";?>"><i class="material-icons">layers</i>사용자 관리</a></li>
                            <li><a href="<?php echo base_url('SYS/level')?>" class="<?php echo ($this->data['subpos'] == 'level')?"on":"";?>"><i class="material-icons">engineering</i>사용자 권한관리</a></li>
                            <li><a href="<?php echo base_url('SYS/version')?>" class="<?php echo ($this->data['subpos'] == 'version')?"on":"";?>"><i class="material-icons">memory</i>버전관리</a></li>
                            <li><a href="<?php echo base_url('SYS/userlog')?>" class="<?php echo ($this->data['subpos'] == 'userlog')?"on":"";?>"><i class="material-icons">preview</i>사용자 접속기록</a></li>
                        </ul>
                    </li>
                    <li class="menu01_li">
                        <a href="<?php echo base_url('Monitor/env')?>" class="menu_a <?php echo ($this->data['pos'] == "Monitor")?"on":"";?>">모니터링</a>
                        <ul class="menu02" <?php echo ($this->data['pos'] == "Monitor")?"style='display:block'":"";?>>
							<li><a href="<?php echo base_url('Monitor/env')?>" class="<?php echo ($this->data['subpos'] == 'env')?"on":"";?>"><i class="material-icons">inbox</i>온습도 모니터링</a></li>
                        </ul>
                    </li>
                </ul>
            
                
            </div>
        </div>
	</div>

    <div class="body_">
		<div id="" class="body_Header">
            
			<div class="bh_title">
			<?php
				if(!empty($this->session->userdata('user_id'))){
			?>
				<?php echo $member_name."님이 로그인중 입니다."; ?>
				
			<?php
			}else{	
			?>
				로그인이 필요합니다.
				<?php if($subpos != "login"){ ?>
				<?php } ?>
				
			<?php
			}	
			?>
			
			</div>
			<h2><?php echo (!empty($siteTitle))?$siteTitle:"NONE TITLE"; ?></h2>
        </div>
        <div class="mControl_show">
            <span class="mshow"><i class="material-icons">menu</i></span>
        </div>
		<div class="body_Content">
			
			

	
	
