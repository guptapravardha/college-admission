--  College Admission Management System - Database Schema
CREATE DATABASE IF NOT EXISTS college_admission;
USE college_admission;

-- TABLE 1: students

CREATE TABLE students (
    student_id   INT AUTO_INCREMENT PRIMARY KEY,
    full_name    VARCHAR(100) NOT NULL,
    email        VARCHAR(100) NOT NULL UNIQUE,
    password     VARCHAR(255) NOT NULL,
    phone        VARCHAR(15),
    dob          DATE,
    gender       ENUM('Male','Female','Other'),
    address      TEXT,
    marks_10th   DECIMAL(5,2),               
    marks_12th   DECIMAL(5,2),              
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- TABLE 2: courses

CREATE TABLE courses (
    course_id    INT AUTO_INCREMENT PRIMARY KEY,
    course_name  VARCHAR(150) NOT NULL,
    course_code  VARCHAR(20)  NOT NULL UNIQUE,
    department   VARCHAR(100),
    duration     VARCHAR(20),
    total_seats  INT          DEFAULT 60,
    min_marks    DECIMAL(5,2) DEFAULT 50.00,
    fee_per_year DECIMAL(10,2)
);


INSERT INTO courses (course_name, course_code, department, duration, total_seats, min_marks, fee_per_year) VALUES
('B.Tech Computer Science & Information Technology', 'BTECH-CSIT',  'Engineering & Technology',      '4 Years', 60, 60.00, 185000.00),
('B.Tech Mechatronics Engineering',                  'BTECH-MECH',  'Engineering & Technology',      '4 Years', 60, 55.00, 180000.00),
('B.Tech Automobile Engineering',                    'BTECH-AUTO',  'Engineering & Technology',      '4 Years', 60, 55.00, 178000.00),
('BBA Banking, Financial Services & Insurance',      'BBA-BFSI',    'Management & Commerce',         '3 Years', 60, 50.00,  95000.00),
('BBA Retail Management',                            'BBA-RETAIL',  'Management & Commerce',         '3 Years', 60, 50.00,  92000.00),
('BBA Digital Marketing',                            'BBA-DM',      'Management & Commerce',         '3 Years', 60, 50.00,  92000.00),
('BBA Logistics & Supply Chain Management',          'BBA-LSCM',    'Management & Commerce',         '3 Years', 60, 50.00,  92000.00),
('BSc Data Science',                                 'BSC-DS',      'Science & Technology',          '3 Years', 40, 55.00, 110000.00),
('MBA Banking, Financial Services & Insurance',      'MBA-BFSI',    'Postgraduate Management',       '2 Years', 40, 50.00, 150000.00),
('MBA Marketing',                                    'MBA-MKT',     'Postgraduate Management',       '2 Years', 40, 50.00, 148000.00);

-- TABLE 3: applications

CREATE TABLE applications (
    application_id   INT AUTO_INCREMENT PRIMARY KEY,
    student_id       INT NOT NULL,
    course_id        INT NOT NULL,
    status           ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
    applied_on       DATETIME DEFAULT CURRENT_TIMESTAMP,
    remarks          TEXT,                           -- admin notes
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id)  REFERENCES courses(course_id)   ON DELETE CASCADE
);

-- TABLE 4: fees

CREATE TABLE fees (
    fee_id           INT AUTO_INCREMENT PRIMARY KEY,
    course_id        INT NOT NULL UNIQUE,
    annual_fee       DECIMAL(10,2) NOT NULL,
    admission_fee    DECIMAL(10,2) DEFAULT 5000.00,
    exam_fee         DECIMAL(10,2) DEFAULT 2000.00,
    hostel_fee       DECIMAL(10,2) DEFAULT 60000.00,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
);

INSERT INTO fees (course_id, annual_fee, admission_fee, exam_fee, hostel_fee)
SELECT course_id, fee_per_year, 5000.00, 2000.00, 60000.00 FROM courses;

-- TABLE 5: documents

CREATE TABLE documents (
    doc_id           INT AUTO_INCREMENT PRIMARY KEY,
    student_id       INT NOT NULL,
    doc_type         VARCHAR(50) NOT NULL,
    file_name        VARCHAR(200) NOT NULL, 
    uploaded_on      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);


-- TABLE 6: admins

CREATE TABLE admins (
    admin_id    INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    full_name   VARCHAR(100)
);

-- Default admin: username = admin, password = admin123
INSERT INTO admins (username, password, full_name)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator');

