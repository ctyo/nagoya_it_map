<?php
$url = "https://docs.google.com/spreadsheets/d/17JDu2NPfzJ6rvtnH9lJTnGwm97qzmYRQFukJJ4UIaNA/gviz/tq?tqx=out:csv&sheet=1";
//$csv = array_map(function ($row) {return str_replace('"', '', explode(',', $row));}, explode("\n", file_get_contents($url)));
$csv = csv_parse_re(file_get_contents($url));
array_shift($csv);

$geojson = [
   'type'      => 'FeatureCollection',
   'features'  => []
];
foreach ($csv as $row) {
    $feature = [
//        'id' => $row['partnership_id'],
        'type' => 'Feature', 
        'geometry' => ['type' => 'Point','coordinates' => array(
            explode(',', $row[2])[1],
            explode(',', $row[2])[0],
         )],
        'properties' => [
            'title' => $row[0],
            'color' => $row[4],
        ]
    ];
    $geojson['features'][] = $feature;
}

echo json_encode($geojson, JSON_PRETTY_PRINT);


function csv_parse_re($str) {
    $re = '/"(?:[^"]|"")*"|"(?:[^"]|"")*$|[^,\r\n]+|,+|\r?\n|\r/m';
    $r = array(array(""));
    if (!$str) {return array();}
    $offset = 0;
    while(preg_match($re, $str, $m, 0, $offset)) {
        $offset += strlen($m[0]);
        if (($c = substr($m[0], 0, 1)) === ',') {
            for($c = strlen($m[0]); $c > 0; $c--) {$r[count($r)-1][]='';}
            continue;
        }
        if ($c === "\n" || $c === "\r\n" || $c === "\r") {$r[] = array('');continue;}
        $r[$n=count($r)-1][count($r[$n])-1] = $c === '"' ? substr(strtr($m[0],array('""'=>'"')),1,-1) : $m[0];
    }
    return $r;
}