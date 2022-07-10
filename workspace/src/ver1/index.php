<?php

use Ver1\ScoreRankingBatch;

require '../../vendor/autoload.php';

// CLIアプリケーションは1つの引数を受け取ること

if (!isset($_SERVER['argv'][1])) {
    echo("読み込むCSVファイルパスを入力してください\n");
    echo("php index.php {input_csv_file_path} {output_csv_file_path}\n");
    return 1;
}

$input_csv_file_path = $_SERVER['argv'][1];
$output_csv_file_path = $_SERVER['argv'][2];
(new ScoreRankingBatch())->generate($input_csv_file_path);
