<?php
////////////////////////////Common head
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
$view_title = "Welcome To Online Judge";
$result = false;
if ( isset( $OJ_ON_SITE_CONTEST_ID ) ) {
	header( "location:contest.php?cid=" . $OJ_ON_SITE_CONTEST_ID );
	exit();
}
///////////////////////////MAIN	

//NOIP赛制比赛时，暂时屏蔽本月之星
 $now =  date('Y-m-d H:i', time());
if(isset($OJ_NOIP_KEYWORD)&&$OJ_NOIP_KEYWORD){	                    
     $sql="select count(contest_id) from contest where start_time<'$now' and end_time>'$now' and ( title like '%$OJ_NOIP_KEYWORD%' or (contest_type & 20)>0 )  ";
	 $row=pdo_query($sql);
	 if(!empty($row)) $NOIP_flag=$row[0];
	 else $NOIP_flag=false;

}

$view_news = "";
$sql = "select * "
. "FROM `news` "
. "WHERE `defunct`!='Y' AND `title`!='faqs.$OJ_LANG'"
. "ORDER BY `importance` ASC,`time` DESC "
. "LIMIT 50";
$view_news .= "<div class='panel panel-default' style='width:80%;margin:0 auto;'>";
	$view_news .= "<div class='panel-heading'><h3>" . $MSG_NEWS . "<h3></div>";
	$view_news .= "<div class='panel-body'>";
	
$result = mysql_query_cache( $sql ); //mysql_escape_string($sql));
if ( !$result ) {
	$view_news.= "";
} else {
	foreach ( $result as $row ) {
		$view_news .= "<div class='panel panel-default'>";
		$view_news .= "<div class='panel-heading'><big>" . $row[ 'title' ] . "</big>-<small>" . $row[ 'user_id' ] . "</small></div>";
		$view_news .= "<div class='panel-body'>" . bbcode_to_html($row[ 'content' ]) . "</div>";
		$view_news .= "</div>";
	}
	
}
$view_news .= "</div>";
$view_news .= "<div class='panel-footer'></div>";
$view_news .= "</div>";

$view_apc_info = "";
$last_1000_id=0;
//$last_1000_id=pdo_query("select min(solution_id) from solution where in_date >= NOW() - INTERVAL 8 DAY ");
$last_1000_id=pdo_query("select max(solution_id-1000) from solution");
if(!empty($last_1000_id))  $last_1000_id=$last_1000_id[0][0];
if ($last_1000_id==NULL) $last_1000_id=0;


$sql = "SELECT date(in_date) md,count(1) c FROM (select * from solution where solution_id > $last_1000_id and result<13 and problem_id>0 and  result>=4 ) solution group by md order by md desc ";
$result = mysql_query_cache( $sql ); //mysql_escape_string($sql));
$chart_data_all = array();
//echo $sql;
if(!empty($result))
foreach ( $result as $row ) {
        array_push( $chart_data_all, array( $row[ 'md' ], $row[ 'c' ] ) );
}

$sql = "SELECT date(in_date) md,count(1) c FROM  (select * from solution where solution_id > $last_1000_id and result=4 and problem_id>0) solution group by md order by md desc ";
$result2 = mysql_query_cache( $sql ); //mysql_escape_string($sql));
$ac=array();
foreach ( $result2 as $row ) {
	$ac[$row['md']]=$row['c'];
}
$chart_data_ac = array();
//echo $sql;
if(!empty($result))
foreach ( $result as $row ) {
	if(isset($ac[$row['md']])) 
        	array_push( $chart_data_ac, array( $row[ 'md' ], $ac[$row['md']] ) );
        else
		array_push( $chart_data_ac, array( $row[ 'md' ], 0 ) );
}
$speed=0;
if ( isset( $_SESSION[ $OJ_NAME . '_' . 'administrator' ] ) ) {
        $sql = "select avg(sp) sp from (select  avg(1) sp,judgetime DIV 3600 from solution where result>3 and solution_id >$last_1000_id  group by (judgetime DIV 3600) order by sp) tt;";
        $result = mysql_query_cache( $sql );
        $speed = ( $result[ 0 ][ 0 ] ? $result[ 0 ][ 0 ] : 0 ) . '/min';
} else {
        if(isset($chart_data_all[ 0 ])) $speed = ( isset($chart_data_all[ 0 ][ 1 ]) ? $chart_data_all[ 0 ][ 1 ] : 0 ) . '/day';
}

function formatTimeLength($length) {
  $hour = 0;
  $minute = 0;
  $second = 0;
  $result = '';

  global $MSG_SECONDS, $MSG_MINUTES, $MSG_HOURS, $MSG_DAYS;

  if ($length>=60) {
    $second = $length%60;
    
    if ($second>0 && $second<10) {
    	$result = '0'.$second.' '.$MSG_SECONDS;}
    else if ($second>0) {
    	$result = $second.' '.$MSG_SECONDS;
    }

    $length = floor($length/60);
    if ($length >= 60) {
      $minute = $length%60;
      
      if ($minute==0) {
      	if ($result != '') {
      		$result = '00'.' '.$MSG_MINUTES.' '.$result;
      	}
      }
      else if ($minute>0 && $minute<10) {
      	if ($result != '') {
      		$result = '0'.$minute.' '.$MSG_MINUTES.' '.$result;}
				}
				else {
					$result = $minute.' '.$MSG_MINUTES.' '.$result;
				}
				
				$length = floor($length/60);

				if ($length >= 24) {
					$hour = $length%24;

				if ($hour==0) {
					if ($result != '') {
						$result = '00'.' '.$MSG_HOURS.' '.$result;
					}
				}
				else if ($hour>0 && $hour<10) {
					if($result != '') {
						$result = '0'.$hour.' '.$MSG_HOURS.' '.$result;
					}
				}
				else {
					$result = $hour.' '.$MSG_HOURS.' '.$result;
				}

				$length = floor($length / 24);
				$result = $length .$MSG_DAYS.' '.$result;
			}
			else {
				$result = $length.' '.$MSG_HOURS.' '.$result;
			}
		}
		else {
			$result = $length.' '.$MSG_MINUTES.' '.$result;
		}
	}
	else {
		$result = $length.' '.$MSG_SECONDS;
	}
	return $result;
}

/////////////////////////Template
require( "template/" . $OJ_TEMPLATE . "/index.php" );
if( isset($OJ_LONG_LOGIN)
    && $OJ_LONG_LOGIN 
    &&(!isset($_SESSION[$OJ_NAME.'_user_id']))
    &&isset($_COOKIE[$OJ_NAME."_user"])
    &&isset($_COOKIE[$OJ_NAME."_check"])){
          echo"<script>let xhr=new XMLHttpRequest();xhr.open('GET','login.php',true);xhr.send();setTimeout('location.reload()',800);</script>";
}

/////////////////////////Common foot
if ( file_exists( './include/cache_end.php' ) )
	require_once( './include/cache_end.php' );
?>
