<?php
$url = "https://docs.google.com/spreadsheets/d/17JDu2NPfzJ6rvtnH9lJTnGwm97qzmYRQFukJJ4UIaNA/gviz/tq?tqx=out:csv&sheet=1";
$csv = array_map(function ($row) {return str_replace('"', '', explode(',', $row));}, explode("\n", file_get_contents($url)));
array_shift($csv);
foreach($csv as $place) {
    $name = $place[0];

    // 緯度経度習得
    $search_word = "名古屋 $name";
    $xml = simplexml_load_file("https://www.geocoding.jp/api/?q=" . urlencode($search_word));
    $lat = (Float) $xml->coordinate->lat;
    $lon = (Float) $xml->coordinate->lng;

    // イメージ色習得
    if ($lat === "") {
        echo "\n";
    }
    echo $lat . "," . $lon . "\n";
    
    sleep(5);
}



