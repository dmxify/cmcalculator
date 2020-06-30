<?php

function url_params()
{
    $url_params = "";
    $url_params .= "?v=" . get_version();
    if (is_no_cache()) {
        $url_params .= "&t=".time();
    }
    echo $url_params;
}
