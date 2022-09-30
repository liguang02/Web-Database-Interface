CREATE TABLE `fit2104_a2`.`STUDENTS`
(
    `id` INT NOT NULL AUTO_INCREMENT, 
    `firstName` VARCHAR(64) NOT NULL, 
    `surname` VARCHAR(64) NOT NULL, 
    `address` VARCHAR(255) NOT NULL, 
    `phone` VARCHAR(12) NOT NULL, 
    `dob` DATE NOT NULL, 
    `email` VARCHAR(255) NOT NULL, 
    `subscribe` BOOLEAN NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `fit2104_a2`.`TAILORED_CLASS`
(
    `id` INT NOT NULL AUTO_INCREMENT,
    `summary` VARCHAR(255) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `quote` VARCHAR(255) NOT NULL,
    `otherInfo` VARCHAR(255) NOT NULL,
    `student_id` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `TAILORED_CLASS` ADD FOREIGN KEY (`student_id`) REFERENCES `STUDENTS`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

CREATE TABLE `fit2104_a2`.`COURSE`
(
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL,
    `price` INT NOT NULL,
    `category_id` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `fit2104_a2`.`COURSE_IMAGE`
(
    `id` INT NOT NULL AUTO_INCREMENT,
    `course_id` INT NOT NULL,
    `filePath` VARCHAR(64) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `fit2104_a2`.`CATEGORY`
(
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `COURSE` ADD FOREIGN KEY (`category_id`) REFERENCES `CATEGORY`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `COURSE_IMAGE` ADD FOREIGN KEY (`course_id`) REFERENCES `COURSE`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

CREATE TABLE `fit2104_a2`.`USERS`
(
    `id` INT NOT NULL AUTO_INCREMENT,
    `firstName` VARCHAR(64) NOT NULL,
    `surname` VARCHAR(64) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `username` VARCHAR(64) NOT NULL,
    `password` VARCHAR(64) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `USERS`
    ADD UNIQUE KEY `email` (`email`),
    ADD UNIQUE KEY `username` (`username`);

INSERT INTO `USERS` (`firstName`, `surname`, `email`, `username`, `password`) VALUES
('Dane', 'Oldman', 'dane.oldman@gmail.com', 'daneo', SHA2('daneo', 256));