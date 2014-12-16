<?php
session_start();
//ini_set('display_errors', 1);
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = 'at/ad8b0280827';
}
if (isset($_POST['login'])) {
    $data = array();
    $data['username'] = $_POST['username'];
    $data['password'] = $_POST['password'];
    $user = apicall('http://182.18.165.43/multitvfinal/apis/users/login/' . $_SESSION['token'], $data);
    $user = json_decode($user);
    if (isset($user->result->token)) {
        $_SESSION['ut'] = $user->result->token;
        $_SESSION['user_token'] = $user->result->token;
        $_SESSION['token'] = 'ut/' . $user->result->token;
        $_SESSION['userDetail'] = userDetail($_SESSION['ut']);
        $_SESSION['user'] = $_SESSION['app']->result[0]->username;
    } else {
        $msg = 'Invalid Login Detail, Try Again';
    }
}
define('APPDETAIL_URL', sprintf('http://182.18.165.43/multitvfinal/apis/users/appdetail/%s', $_SESSION['token']));
define('VIDEO_LIST', sprintf('http://182.18.165.43/multitvfinal/apis/content/getlist/type/video/%s', $_SESSION['token']));
define('FEATURED_LIST', sprintf('http://182.18.165.43/multitvfinal/apis/content/featured/%s/st/0/lt/9', $_SESSION['token']));
define('VIDEO_SEARCH', sprintf('http://182.18.165.43/multitvfinal/apis/content/search/%s', $_SESSION['token']));
define('CAT_LIST', sprintf('http://182.18.165.43/multitvfinal/apis/category/getlist/%s', $_SESSION['token']));
define('DATE_FORMAT', 'l,d,M');

function apicall($url, $post_fields = array()) {
    $post = http_build_query($post_fields);
    $ch = curl_init();                    // Initiate cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Define what you want to post
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the output in string format
    $output = curl_exec($ch); // Execute
    curl_close($ch); // Close cURL handle
    return $output;
}

function subList($cid) {
    $url = sprintf('http://182.18.165.43/multitvfinal/api/subscription/list/content_id/%d/token/%s', $cid, $_SESSION['user_token']);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function catcontent($id, $st, $lt) {
    $url = sprintf('http://182.18.165.43/multitvfinal/apis/content/search/k/category/val/%s/%s/st/%s/lt/%s/', $id, $_SESSION['token'], $st, $lt);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function getComment($id) {
    $url = sprintf('http://182.18.165.43/multitvfinal/apis/content/search/k/comments/val/%s/%s', $id, $_SESSION['token']);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function userDetail($ut) {
    $url = 'http://182.18.165.43/multitvfinal/apis/users/profile/ut/' . $ut;
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function appdetail() {
    $_SESSION['app'] = $result = getdata(APPDETAIL_URL);
    if (count($result) > 0) {
        return $result->result[0];
    } else {
        return false;
    }
}

function catList() {
    $result = getdata(CAT_LIST);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function videoList($st = 0, $lt = 10) {
    $url = sprintf('%s/st/%d/lt/%d', VIDEO_LIST, $st, $lt);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function searchVideo($st = 0, $lt = 10, $title, $ob = null, $k) {
    $url = sprintf('%s/st/%d/lt/%d/k/%s/val/%s/ob/%s', VIDEO_SEARCH, $st, $lt, $k, $title, $ob);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function videoDetail($id) {
    $url = sprintf('%s/k/id/val/%d', VIDEO_SEARCH, $id);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function recentVideo($st = 0, $lt = 3) {
    $url = sprintf('%s/st/%d/lt/%d/k/recent', VIDEO_SEARCH, $st, $lt);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function viewedVideo($st = 0, $lt = 18) {
    $url = sprintf('%s/st/%d/lt/%d/k/popular', VIDEO_SEARCH, $st, $lt);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function svideo($val, $st, $lt) {
    $url = sprintf('http://182.18.165.43/multitvfinal/apis/content/search/k/%s/%s/st/%s/lt/%s/', $val, $_SESSION['token'], $st, $lt);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function liked($st, $lt) {
    $url = sprintf('http://182.18.165.43/multitvfinal/apis/content/like/%s/st/%s/lt/%s', $_SESSION['token'], $st, $lt);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function fav($st, $lt) {
    $url = sprintf('http://182.18.165.43/multitvfinal/apis/content/favorite/%s/st/%s/lt/%s', $_SESSION['token'], $st, $lt);
    $result = getdata($url);
    if (count($result) > 0) {
        return $result;
    } else {
        return false;
    }
}

function likedVideo($st = 0, $lt = 18) {
    $url = sprintf('%s/st/%d/lt/%d/k/liked', VIDEO_SEARCH, $st, $lt);
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

function dateFormat($date) {
    $temp = strtotime($date);
    if ($temp > 0) {
        return date(DATE_FORMAT, $temp);
    } else {
        return false;
    }
}

function getLink($cid, $price) {
    
    return 'detail.php?v=' . $cid;
    
    /*
    if ($price == 'Free') {
        return 'detail.php?v=' . $cid;
    } else {
        if (isset($_SESSION['ut'])) {
            if ($price == 'Paid') {
                return 'subscription.php?v=' . $cid;
            }
        } else {
            return "javascript:animatedcollapse.toggle('login-box');window.scrollTo(0,0);";
        }
    }
    */
}

$file_path = $_SERVER['PHP_SELF'];
$file_path = explode('/', $file_path);
$file_name = array_pop($file_path);
//vd($file_name);

$isHomeActive = ($file_name == 'index.php') ? 'current-menu-item' : '';
$ispopulerActive = ($file_name == 'populer.php') ? 'current-menu-item' : '';
$iscatActive = ($file_name == 'category.php') ? 'current-menu-item' : '';
//$isProductActive = ($file_name == 'show_all_products.php')?'active' :'' ;
