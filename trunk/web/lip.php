<?php
$ip = ($_SERVER['REMOTE_ADDR']??"");
if( isset($_SERVER['HTTP_X_FORWARDED_FOR'] )&&!empty( trim( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ){
    $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $tmp_ip=explode(',',$REMOTE_ADDR);
    $ip =(htmlentities($tmp_ip[0],ENT_QUOTES,"UTF-8"));
} else if(isset($_SERVER['HTTP_X_REAL_IP'])&& !empty( trim( $_SERVER['HTTP_X_REAL_IP'] ) ) ){
    $REMOTE_ADDR = $_SERVER['HTTP_X_REAL_IP'];
    $tmp_ip=explode(',',$REMOTE_ADDR);
    $ip =(htmlentities($tmp_ip[0],ENT_QUOTES,"UTF-8"));
}
header("location:http://your.public.domain.com/session.php?lip=".ip2long($ip) );
