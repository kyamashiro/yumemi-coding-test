# README

## 実行環境

PHP8.1  
MySQL8

1. 事前にMySQLにbatch_rankingsテーブルを作成しておくこと。nvb cgggggggggggggggggg \cvbbbbbbbbbbbbbbbbbbn
2. my.cnfに以下の設定を追加してLOAD DATA IN LOCAL FILEを有効にしておくこと。

```
local_infile=1
```

3. 以下のコマンドを実行すること

```sh
cd yumemi-coding-test
composer dump
cd src/ver1
php index.php input.csv get_ranking/game_score_log.csv
```
