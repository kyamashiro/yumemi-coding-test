<?php

$file = new SplFileObject('input.csv');
$file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

$total_score_every_players = [];

// CSVからプレイヤーごとのトータルスコア＋プレイ回数を算出する
// プレイヤーの総数の最大は1万人
foreach ($file as $idx => $line) {
    print_r("index:{$idx}\n");
    // 先頭行はスキップ
    if ($idx === 0) {
        continue;
    }
    $player_id = $line[1];
    $score = $line[2];
    $count = $total_score_every_players[$player_id]['count'] ?? 0;
    $total_score = $total_score_every_players[$player_id]['total_score'] ?? 0;

    $total_score_every_players[$player_id] = ['count' => $count + 1, 'total_score' => $total_score + $score];
}

$player_rankings = [];
$min_ranking_score = 0;

// 上位10名のみ抽出する。スコアが同点の場合は同率とする。
foreach ($total_score_every_players as $player_id => $total_score_every_player) {
    $avg_score = round($total_score_every_player['total_score'] / $total_score_every_player['count']);

    // 最初の1件
    if (count($player_rankings) === 0) {
        $min_ranking_score = $avg_score;
        $player_rankings[$avg_score][] = $player_id;
        continue;
    }

    // 最初の10件までは追加
    if (count($player_rankings) < 10) {
        if ($avg_score < $min_ranking_score) {
            $min_ranking_score = $avg_score;
        }
        $player_rankings[$avg_score][] = $player_id;
        continue;
    }

    // トップ10の中で最も低いスコアより高いとき
    if ($min_ranking_score < $avg_score) {
        // トップ10に追加
        $player_rankings[$avg_score][] = $player_id;
        // 最も低いスコアを削除する
        unset($player_rankings[$min_ranking_score]);
        // 最も低いスコアを更新する
        $min_ranking_score = min(array_keys($player_rankings));
    } else if ($min_ranking_score === $avg_score) {
        // 最も低いスコアと同じ時スコアに追加する
        $player_rankings[$avg_score][] = $player_id;
    }
}

// スコアの降順でソートする
krsort($player_rankings);
// CSVを出力する
$file = new SplFileObject('./get_ranking/game_score_log.csv', 'w');
// ヘッダー行を書き出し
$file->fputcsv(['rank', 'player_id', 'mean_score']);

$rank = 1;
foreach ($player_rankings as $score => $player_ids) {
    foreach ($player_ids as $player_id) {
        $file->fputcsv([$rank, $player_id, $score]);
    }
    $rank++;
}