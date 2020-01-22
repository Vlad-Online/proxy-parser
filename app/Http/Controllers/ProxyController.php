<?php

namespace App\Http\Controllers;

use App\Parser\Proxy;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Parser\Proxy  $proxy
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Proxy $proxy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Parser\Proxy  $proxy
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Proxy $proxy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Parser\Proxy  $proxy
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proxy $proxy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Parser\Proxy  $proxy
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proxy $proxy)
    {
        //
    }

    public function ping()
    {
        ob_start();
        echo "check this string in proxy response content\r\n";
        if (!empty($_GET['q']) && ('query' == $_GET['q'])) {
            echo 'allow_get';
        }

        if (!empty($_POST['r']) && ('request' == $_POST['r'])) {
            echo 'allow_post';
        }

        if (!empty($_COOKIE['c']) && ('cookie' == $_COOKIE['c'])) {
            echo 'allow_cookie';
        }

        if (!empty($_SERVER['HTTP_REFERER']) && ('http://www.google.com' == $_SERVER['HTTP_REFERER'])) {
            echo 'allow_referer';
        }

        if (!empty($_SERVER['HTTP_USER_AGENT']) && ('Mozila/4.0' == $_SERVER['HTTP_USER_AGENT'])) {
            echo 'allow_user_agent';
        }

        //proxy levels
        //Level 3 Elite Proxy, connection looks like a regular client
        //Level 2 Anonymous Proxy, no ip is forworded but target site could still tell it's a proxy
        //Level 1 Transparent Proxy, ip is forworded and target site would be able to tell it's a proxy
        if (!isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !isset($_SERVER['HTTP_VIA']) && !isset($_SERVER['HTTP_PROXY_CONNECTION'])) {
            echo 'proxylevel_elite';
        } elseif (!$_SERVER['HTTP_X_FORWARDED_FOR']) {
            echo 'proxylevel_anonymous';
        } else {
            echo 'proxylevel_transparent';
        }
        $content = ob_get_clean();

        return response($content, 200)->header('Content-Type', 'text/plain');;
    }
}
