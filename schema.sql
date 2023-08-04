CREATE DATABASE IF NOT EXISTS surway;
USE surway;


CREATE TABLE IF NOT EXISTS surway.users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL
);


CREATE TABLE IF NOT EXISTS surway.status (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_value VARCHAR(20) NOT NULL
);


CREATE TABLE IF NOT EXISTS surway.survey (
    survey_id INT PRIMARY KEY AUTO_INCREMENT,
    survey_title VARCHAR(100),
    survey_desc TEXT,
    start_date DATETIME NOT NULL,
    end_date DATETIME,
    min_responses INT,
    max_responses INT,
    is_age_restricted BOOL NOT NULL,
    creator_id INT,
    FOREIGN KEY (creator_id) REFERENCES surway.users (user_id) ON UPDATE CASCADE ON DELETE RESTRICT
);


CREATE TABLE IF NOT EXISTS surway.surveystatus (
    survey_status_id INT PRIMARY KEY AUTO_INCREMENT,
    survey_id INT NOT NULL,
    status_id INT NOT NULL,
    updated_on datetime DEFAULT now(),
    FOREIGN KEY (survey_id) REFERENCES surway.survey (survey_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (status_id) REFERENCES surway.status (status_id) ON UPDATE CASCADE ON DELETE RESTRICT
);


CREATE TABLE IF NOT EXISTS surway.questiontype (
    question_type_id INT PRIMARY KEY AUTO_INCREMENT,
    question_type VARCHAR(100) NOT NULL,
    question_type_desc TEXT NOT NULL
);


CREATE TABLE IF NOT EXISTS surway.questions (
    question_id INT PRIMARY KEY AUTO_INCREMENT,
    question TEXT NOT NULL,
    is_mandatory BOOL DEFAULT false,
    question_type_id INT NOT NULL,
    survey_id INT NOT NULL,
    FOREIGN KEY (question_type_id) REFERENCES surway.questiontype (question_type_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (survey_id) REFERENCES surway.survey (survey_id) ON UPDATE CASCADE ON DELETE RESTRICT
);


CREATE TABLE IF NOT EXISTS surway.questionoptions (
    question_option_id INT PRIMARY KEY AUTO_INCREMENT,
    question_option TEXT NOT NULL,
    is_correct BOOL NOT NULL,
    question_id INT NOT NULL,
    FOREIGN KEY (question_id) REFERENCES surway.questions (question_id) ON UPDATE CASCADE ON DELETE RESTRICT
);


CREATE TABLE IF NOT EXISTS surway.respondants (
    respondant_id INT PRIMARY KEY AUTO_INCREMENT,
    responded_on DATETIME DEFAULT now(),
    user_id INT NOT NULL,
    survey_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES surway.users (user_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (survey_id) REFERENCES surway.survey (survey_id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS surway.responses (
    response_id INT PRIMARY KEY AUTO_INCREMENT,
    question_id INT NOT NULL,
    answer_option INT,
    answer TEXT,
    respondant_id INT NOT NULL,
    FOREIGN KEY (question_id) REFERENCES surway.questions (question_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (respondant_id) REFERENCES surway.respondants (respondant_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_answer CHECK ((answer_option IS NULL AND answer IS NOT NULL) OR (answer_option IS NOT NULL AND answer IS NULL))
);
