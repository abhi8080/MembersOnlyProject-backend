<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CookieController extends Controller
{
    public function setCookie(Request $request) {
        $name = (string) $request->query('name');
    
        $minutes = 300;
        $response = new Response('Hello World');
        $token = "helloeijehdfddbdbddhdd";
        $response = $response->withCookie(cookie('name', $token, $minutes));
        return $response;
    }
    
    
    
    
    

    public function getCookie(Request $request) {
        $value = $request->cookie('name');
        echo $value;
    }
}
