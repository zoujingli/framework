<?php

$data = [];
$list = json_decode(file_get_contents(__DIR__ . '/area.json'), true);
foreach ($list as $citys) {
    $lines = [];
    foreach ($citys['list'] as $area) {
        $lines[] = $area['name'] . ',' . join(',', $area['list']);
    }
    $data[] = $citys['name'] . '$' . join('|',$lines);
}
echo join('#', $data);