CREATE DATABASE IF NOT EXISTS transcript;
USE transcript;


CREATE TABLE IF NOT EXISTS transcript.user_types (
	user_type_id VARCHAR(3) PRIMARY KEY, -- FAC, STU, ADM
    user_type VARCHAR(50) NOT NULL -- FACULTY, STUDENT, ADMIN
);


CREATE TABLE IF NOT EXISTS transcript.users (
    user_email VARCHAR(100) PRIMARY KEY,
    user_fname VARCHAR(50) NOT NULL,
    user_lname VARCHAR(50) NOT NULL,
    user_password VARCHAR(100) NOT NULL,
    user_type VARCHAR(3) NOT NULL,
    FOREIGN KEY (user_type) REFERENCES transcript.user_types (user_type_id) ON UPDATE CASCADE ON DELETE RESTRICT
);


CREATE TABLE IF NOT EXISTS transcript.courses (
    course_id VARCHAR(8) PRIMARY KEY,
    course_title VARCHAR(100) NOT NULL,
    course_desc TEXT NOT NULL
);


CREATE TABLE IF NOT EXISTS transcript.course_faculty (
    course_id VARCHAR(8) PRIMARY KEY,
    fac_email VARCHAR(100) NOT NULL,
    assigned_on DATE DEFAULT NOW(),
    FOREIGN KEY (course_id) REFERENCES transcript.courses (course_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (fac_email) REFERENCES transcript.users (user_email) ON UPDATE CASCADE ON DELETE RESTRICT
);


CREATE TABLE IF NOT EXISTS transcript.course_assessments (
    assessment_id VARCHAR(10) NOT NULL, -- Assignments: A1, A2; Exams: MT (mid-term), FE (final-exam); Project: IP (individual project), GP (group project)
    assessment VARCHAR(100) NOT NULL,
    max_marks DECIMAL(5, 2) NOT NULL,
    course_id VARCHAR(8) PRIMARY KEY,
    CONSTRAINT course_assessments_pk PRIMARY KEY (course_id, assessment_id),
    FOREIGN KEY (course_id) REFERENCES transcript.courses (course_id) ON UPDATE CASCADE ON DELETE RESTRICT
);


CREATE TABLE IF NOT EXISTS transcript.grades (
    assessment_id VARCHAR(10) NOT NULL,
    marks_obtained DECIMAL(5,2),
    stu_email VARCHAR(100) NOT NULL,
    course_id VARCHAR(8) PRIMARY KEY,
    FOREIGN KEY (stu_email) REFERENCES transcript.users (user_email) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (course_id) REFERENCES transcript.courses (course_id) ON UPDATE CASCADE ON DELETE RESTRICT
);