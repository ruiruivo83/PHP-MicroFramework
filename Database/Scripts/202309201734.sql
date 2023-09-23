CREATE TABLE chapters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    section_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    VideoURL VARCHAR(512),  -- Added VideoURL column
    FileURL VARCHAR(512),   -- Added FileURL column
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
);

