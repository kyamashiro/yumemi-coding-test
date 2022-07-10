<?php

namespace Ver1;

use PDO;

class ScoreRankingBatch
{
    public function generate(string $input_csv_path): int
    {
        $pdo = new PDO('mysql:dbname=test;host=mysql', 'root', 'password', [PDO::MYSQL_ATTR_LOCAL_INFILE => true]);
        $this->import($pdo, $input_csv_path);

        // SQLでトップ10を集計する
        $result = $pdo->query("
SELECT rank_result, player_id, mean_score
FROM (SELECT player_id, ROUND(AVG(score)) as mean_score, DENSE_RANK() OVER (ORDER BY ROUND(AVG(score)) DESC) AS rank_result
      FROM batch_rankings
      GROUP BY player_id) AS s
WHERE rank_result <= 10
")->fetchAll(PDO::FETCH_ASSOC);

        print_r("SQL executed\n");
        $this->export('./get_ranking/game_score_log.csv', $result);

        return 0;
    }

    private function import(PDO $pdo, string $csv_file_path): void
    {
        // テーブルをtruncate
        $pdo->query('TRUNCATE batch_rankings;');
        print_r("LOAD DATA LOCAL IN FILE\n");
        // CSVファイルをテーブルに突っ込む
        $pdo->query("LOAD DATA LOCAL INFILE '{$csv_file_path}' INTO TABLE batch_rankings FIELDS TERMINATED BY ',';");
    }

    private function export(string $file_path, array $list): void
    {
        // CSVファイルを生成する
        $f = fopen($file_path, "w");
        // ヘッダー行
        fputcsv($f, ['rank', 'player_id', 'mean_score']);

        foreach ($list as $line) {
            fputcsv($f, $line);
        }
        fclose($f);
    }
}




