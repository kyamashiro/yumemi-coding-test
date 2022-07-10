<?php

// 行数
$line = $_SERVER['argv'][1];

$f = fopen("./input.csv", "w");
// ヘッダー行
fputcsv($f, ['create_timestamp', 'player_id', 'score']);

for ($i = 0; $i < $line; $i++) {
    fputcsv($f, [date("Y-m-d H:i:s"), 'player_' . rand(1, 10000), rand(1, 10000)]);
}

fclose($f);