<?php
$url = "https://docs.google.com/spreadsheets/d/17JDu2NPfzJ6rvtnH9lJTnGwm97qzmYRQFukJJ4UIaNA/gviz/tq?tqx=out:csv&sheet=1";
$csv = array_map(function ($row) {return str_replace('"', '', explode(',', $row));}, explode("\n", file_get_contents($url)));
array_shift($csv);

$geojson = [
   'type'      => 'FeatureCollection',
   'features'  => []
];
foreach ($csv as $row) {
    $feature = [
//        'id' => $row['partnership_id'],
        'type' => 'Feature', 
        'geometry' => ['type' => 'Point','coordinates' => array($row[3], $row[2])],
        'properties' => [
            'name' => $row[0],
        ]
    ];
    $geojson['features'][] = $feature;
}

echo json_encode($geojson, JSON_PRETTY_PRINT);
