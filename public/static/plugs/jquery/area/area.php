<?php

$data = [];
$list = json_decode(file_get_contents(__DIR__ . '/area.json'), true);
foreach ($list as $citys) {
    $line = $citys['n'];
    foreach ($citys['c'] as $area) $line = $line . '$' . $area['n'] . ',' . join(',', $area['a']);
    $data[] = $line;
}
echo join('#', $data);