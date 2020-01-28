<?php
    //Simple page redirect
function redirect($page){
    header('location: ' . URLROOT . '/' . $page);
}

function extractUserId($url){
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $queryStr = explode('&', $url);
    $queryStr = $queryStr[count($queryStr) - 1]; 
    $infoArray = explode('?', $queryStr);
    $userIdArr = explode('=', $infoArray[0]);
    $userId = (int) $userIdArr[count($userIdArr) - 1];
    return $userId;
}

function extractPageNum($url){
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $queryStr = explode('&', $url);
    $queryStr = $queryStr[count($queryStr) - 1]; 
    $infoArray = explode('?', $queryStr);
    $pagedArr = explode('=', $infoArray[1]);
    $pageNum = (int) $pagedArr[1];
    return $pageNum;
}