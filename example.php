<?php
function searchImage($query) {
    $query = urlencode($query);
    $contents = @file_get_contents('https://www.google.com/search?source=lnms&sa=X&gbv=1&tbm=isch&q=' . $query);
    if ($contents == false) {
        echo 'Error, likely 404.';
        echo "\n";
    } else {
        $dom = new DOMDocument();
        $dom->loadHTML($contents);
        $dom->preserveWhiteSpace = false;
        $table = $dom->getElementsByTagName('table')[2];
        $imgs = $table->getElementsByTagName('img');
        $imglist = [];
        foreach ($imgs as $img) {
            array_push($imglist, $img->getAttribute('src'));
        }
        return $imglist;
    }
}
// Show 20 results
foreach(searchImage('Test') as $img) {
    echo '<img src="' . htmlspecialchars($img) . '">';
}
// Show a variable number of results, e.g. 24
foreach(searchImage('Test', 24) as $img) {
    echo '<img src="' . htmlspecialchars($img) . '">';
}
