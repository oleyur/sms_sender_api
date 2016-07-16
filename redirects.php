<?php

// only domnain
$GLOBALS["redirects"] = array(
	//'mydomain.dev' => "my.newdomain.dev",
);

// domain and REQUEST_URI
$GLOBALS["redirects_by_regex"] = array(
    //"#\.my\.com\/it$#si"		=> "www.my.com",
);


if(isset($_SERVER["HTTP_HOST"]) && isset($GLOBALS["redirects"][$_SERVER["HTTP_HOST"]])){
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: http://".$GLOBALS["redirects"][$_SERVER["HTTP_HOST"]].$_SERVER["REQUEST_URI"]);
	exit;
}

foreach ((array)$GLOBALS["redirects_by_regex"] as $regex => $redirect){
	$is_need_redirect = preg_match($regex, $_SERVER["HTTP_HOST"].rtrim($_SERVER['REQUEST_URI'],"/"), $matches);
	if($is_need_redirect){
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://".$redirect);
		exit;
	}
}
