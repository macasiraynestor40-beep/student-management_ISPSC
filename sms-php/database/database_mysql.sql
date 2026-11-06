-- Student Management System (MySQL / MariaDB, e.g. XAMPP)
-- Import in phpMyAdmin or run: mysql -u root < database_mysql.sql

CREATE DATABASE IF NOT EXISTS sms;
USE sms;

DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id        INT AUTO_INCREMENT PRIMARY KEY,
    full_name      VARCHAR(100) NOT NULL,
    email          VARCHAR(100) NOT NULL UNIQUE,
    password_hash  VARCHAR(255) NOT NULL,
    created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE students (
    student_id        INT AUTO_INCREMENT PRIMARY KEY,
    student_no        VARCHAR(20)  NOT NULL UNIQUE,
    first_name        VARCHAR(50)  NOT NULL,
    middle_name       VARCHAR(50),
    last_name         VARCHAR(50)  NOT NULL,
    gender            VARCHAR(20)  CHECK (gender IN ('Male','Female')),
    email             VARCHAR(100) UNIQUE,
    contact_no        VARCHAR(20),
    program           VARCHAR(100) NOT NULL,
    year_level        VARCHAR(20)  NOT NULL,
    section           VARCHAR(20),
    enrollment_status VARCHAR(30)  NOT NULL CHECK (enrollment_status IN ('Regular','Irregular','Transferee')),
    date_enrolled     DATE,
    status            VARCHAR(20)  NOT NULL DEFAULT 'Active' CHECK (status IN ('Active','Inactive'))
);

INSERT INTO students
    (student_no, first_name, middle_name, last_name, gender, email, contact_no,
     program, year_level, section, enrollment_status, date_enrolled, status)
VALUES
    ('2023-0001', 'Maria',  'Lourdes',  'Santos',    'Female', 'maria.santos@ispsc.edu.ph',     '09171234501', 'BS Information Technology', '2nd Year', '2-A', 'Regular',    '2023-08-14', 'Active'),
    ('2023-0002', 'Juan',   'Dela',     'Reyes',     'Male',   'juan.reyes@ispsc.edu.ph',       '09171234502', 'BS Information Technology', '2nd Year', '2-A', 'Regular',    '2023-08-14', 'Active'),
    ('2023-0003', 'Ana',    'Marie',    'Cruz',      'Female', 'ana.cruz@ispsc.edu.ph',         '09171234503', 'BS Information Technology', '2nd Year', '2-B', 'Irregular',  '2023-08-14', 'Active'),
    ('2022-0004', 'Mark',   'Anthony',  'Garcia',    'Male',   'mark.garcia@ispsc.edu.ph',      '09171234504', 'BS Information Technology', '3rd Year', '3-A', 'Regular',    '2022-08-15', 'Active'),
    ('2025-0005', 'Elena',  'Patricia', 'Dela Cruz', 'Female', 'elena.delacruz@ispsc.edu.ph',   '09171234505', 'BS Computer Science',       '2nd Year', '2-A', 'Transferee', '2025-08-11', 'Active'),
    ('2022-0006', 'Carlo',  'Jose',     'Ramos',     'Male',   'carlo.ramos@ispsc.edu.ph',      '09171234506', 'BS Computer Science',       '3rd Year', '3-B', 'Irregular',  '2022-08-15', 'Inactive');

SELECT * FROM students ORDER BY student_id;
