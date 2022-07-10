CREATE DATABASE test;

USE test;

CREATE TABLE batch_rankings
(
    create_timestamp timestamp    not null,
    player_id        varchar(255) not null,
    score            bigint       not null
)
    comment 'プレイログデータ';