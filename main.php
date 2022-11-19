<?php
function fgc($url, $prefix = '') {
    if (!is_dir('cache')) {
        mkdir('cache');
    }
    $cache_file = 'cache/' . md5($url) . $prefix;
    if (file_exists($cache_file)) {
        if(time() - filemtime($cache_file) > 3600) {
            $cache = @file_get_contents($url);
            file_put_contents($cache_file, $cache);
        } else {
            $cache = @file_get_contents($cache_file);
        }
    } else {
        $cache = @file_get_contents($url);
        file_put_contents($cache_file, $cache);
    }
    return $cache;
}

function searchImage($query, $numResults = 20) {
    $numResults = intval($numResults);
    $num = ceil(floatval($numResults / 20));
    $imglist = [];
    foreach (range(1, $num) as $n) {
        $query = urlencode($query);
        $start = ($n - 1) * 20;
        $url = 'https://www.google.com/search?source=lnms&sa=X&gbv=1&tbm=isch&q=' . $query . '&start=' . $start;
        $contents = @fgc($url, '.html');
        if ($contents == false) {
            //Err
        } else {
            $dom = new DOMDocument();
            $dom->loadHTML($contents);
            $dom->preserveWhiteSpace = false;
            $table = $dom->getElementsByTagName('table')[2];
            $imgs = $table->getElementsByTagName('img');
            foreach ($imgs as $img) {
                $url = $img->getAttribute('src');
                @fgc($url, '.jpg');
                array_push($imglist, 'cache/' . md5($url) . '.jpg');
            }
        }
    }
    return array_slice($imglist, 0, $numResults);
}
