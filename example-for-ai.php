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
            try {
                $dom = new DOMDocument();
                @$dom->loadHTML($contents);
                $dom->preserveWhiteSpace = false;
		try {
	            $table = $dom->getElementsByTagName('table')[2];
		} catch (Exception $e) {}
                $imgs = $table->getElementsByTagName('img');
                foreach ($imgs as $img) {
                    $url = $img->getAttribute('src');
                    @fgc($url, '.jpg');
                    array_push($imglist, 'cache/' . md5($url) . '.jpg');
                }
            } catch (Exception $e) {
                echo 'Error, but not too bad: ' . $e->getMessage() . '<br>';
            }
        }
    }
    return array_slice($imglist, 0, $numResults);
}
function rstr($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

//header('Content-type:text/plain');
$n = 1;
$objects = explode("\n", trim(file_get_contents('objects.txt')));
foreach ($objects as $obj) {
    $obj = trim($obj);
    if (!is_dir('obj')) {
        mkdir('obj');
    }
    if (!is_dir("obj/$obj")) {
        mkdir("obj/$obj");
        foreach(searchImage($obj, 50) as $img) {
            copy($img, "obj/$obj/" . rstr(5) . '.jpg');
            echo "Copied file for type: '$obj'!\n";
            $n++;
        }
    } else {
        echo 'Dir already exists!' . "\n";
    }
}
