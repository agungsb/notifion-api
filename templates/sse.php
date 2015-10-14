<?php

function getSse($token) {
    global $app;

    $app->response->headers->set("Content-Type", "text/event-stream; charset=UTF-8");
//    $app->response->headers->set("Content-Type", "application/json");
    $app->response->headers->set("Connection", "keep-alive");
    $app->response->headers->set("Cache-Control", "no-cache");

//    $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhY2NvdW50IjoiaGFtaWRpbGxhaF9hamllIiwibmFtYSI6IkhhbWlkaWxsYWggQWppZSIsImlkX2phYmF0YW4iOiIwMDMwMDAwMDEiLCJpZF9pbnN0aXR1c2kiOiIwMDMwMDAiLCJuYW1hX2luc3RpdHVzaSI6IlBVU1RJS09NIiwidmFsaWQiOnRydWV9.I1ziqxbGr3H7UMnaYvmMeqrPyJ0nbUnQ7FfzA5pRHNo";

    $time = date('r');
//    echo 'data: '.$time . "\n\n\n";
    $output = array("time"=>$time, "unread"=>countUnreads($token), "favorites"=>  countFavorites($token), "unsigned"=>  countUnsigned($token));
    echo "data: ".json_encode($output) . "\n\n\n";
//    echo json_encode($output);
//    flush();
}
