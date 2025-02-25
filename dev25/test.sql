CREATE TABLE scores (
    id INT(11) NOT NULL AUTO_INCREMENT,
    score1_1 INT(11) DEFAULT NULL,
    score1_2 INT(11) DEFAULT NULL,
    score1_3 INT(11) DEFAULT NULL,
    score2 INT(11) DEFAULT NULL,
    score3 INT(11) DEFAULT NULL,
    score4 INT(11) DEFAULT NULL,
    selected_options1_1 TEXT COLLATE utf8mb4_general_ci DEFAULT NULL,
    selected_options1_2 TEXT COLLATE utf8mb4_general_ci DEFAULT NULL,
    selected_options1_3 TEXT COLLATE utf8mb4_general_ci DEFAULT NULL,
    selected_options2 TEXT COLLATE utf8mb4_general_ci DEFAULT NULL,
    selected_options3 TEXT COLLATE utf8mb4_general_ci DEFAULT NULL,
    selected_options4 TEXT COLLATE utf8mb4_general_ci DEFAULT NULL,
    totalScore INT(11) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
