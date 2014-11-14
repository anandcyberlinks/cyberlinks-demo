<?php

define('BASEURL', 'http://182.18.165.252/multitvfinal/web/');
define('CSS_PATH', BASEURL . 'assets/css/');
define('JS_PATH', BASEURL . 'assets/js/');
define('IMG_PATH', BASEURL . 'assets/images/');
define('TOKEN', '545a1e3363dd2');
define('APPDETAIL_URL', sprintf('http://182.18.165.252/multitvfinal/apis/users/appdetail/at/%s', TOKEN));
define('VIDEO_LIST', sprintf('http://182.18.165.252/multitvfinal/apis/content/getlist/type/video/at/%s', TOKEN));
define('FEATURED_LIST', sprintf('http://182.18.165.252/multitvfinal/apis/content/featured/at/%s/st/0/lt/9', TOKEN));
define('VIDEO_SEARCH', sprintf('http://182.18.165.252/multitvfinal/apis/content/search/at/'.TOKEN));
define('DATE_FORMAT', 'M,d Y');


function appdetail() {
    $result = getdata(APPDETAIL_URL);
    if (count($result) > 0) {
        return $result->result[0];
    } else {
        return false;
    }
}

function videoList($st = 0,$lt = 10) {
    $url = sprintf('%s/st/%d/lt/%d',VIDEO_LIST,$st,$lt);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function searchVideo($st = 0,$lt = 10, $title, $ob= null){
    $url = sprintf('%s/st/%d/lt/%d/k/title/val/%s/ob/%s',VIDEO_SEARCH,$st,$lt, $title, $ob);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function videoDetail($id){
    $url = sprintf('%s/k/id/val/%d',VIDEO_SEARCH,$id);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function recentVideo($st = 0,$lt = 10){
    $url = sprintf('%s/st/%d/lt/%d/k/recent',VIDEO_SEARCH,$st,$lt);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function viewedVideo($st = 0,$lt = 18){
    $url = sprintf('%s/st/%d/lt/%d/k/popular',VIDEO_SEARCH,$st,$lt);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function likedVideo($st = 0,$lt = 18){
    $url = sprintf('%s/st/%d/lt/%d/k/liked',VIDEO_SEARCH,$st,$lt);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function featuredList() {
    //echo FEATURED_LIST; exit;
    $result = getdata(FEATURED_LIST);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function getdata($url) {  
    $data = file_get_contents($url);
    return json_decode($data);
}

function dateFormat($date){
    $temp = strtotime($date);
    if($temp > 0){
        return date(DATE_FORMAT, $temp);
    }else{
        return false;
    }
    
}
?>