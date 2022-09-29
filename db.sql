CREATE TABLE `fit2104_a2`.`students` 
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

CREATE TABLE `fit2104_a2`.`tailored_class`
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

ALTER TABLE `tailored_class` ADD FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

