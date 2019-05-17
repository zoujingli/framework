<?php

$data = [];
$list = json_decode(file_get_contents(__DIR__ . '/area.json'), true);
foreach ($list as $citys) {
    $line = $citys['name'];
    foreach ($citys['list'] as $area) $line = $line . '$' . $area['name'] . ',' . join(',', $area['list']);
    $data[] = $line;
}
echo join('#', $data);