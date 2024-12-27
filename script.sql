DROP DATABASE IF EXISTS joueurs;
CREATE DATABASE joueurs;
USE joueurs;

CREATE TABLE players (
    player_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    position ENUM('GK','LB','CBL','CBR','RB','CML','DMF','CMR','LW','ST','RW') NOT NULL,
    club VARCHAR(255),
    nationality VARCHAR(255),
    rating INT NOT NULL,
    PRIMARY KEY (player_id)
   );
