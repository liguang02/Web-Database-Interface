DROP TABLE `STUDENTS`, `TAILORED_CLASS`, `COURSE`, `COURSE_IMAGE`, `CATEGORY`, `USERS`;

CREATE TABLE `fit2104_a2`.`STUDENTS`
(
    `id` INT NOT NULL AUTO_INCREMENT, 
    `firstName` VARCHAR(64) NOT NULL, 
    `surname` VARCHAR(64) NOT NULL, 
    `address` VARCHAR(255) NOT NULL, 
    `phone` VARCHAR(16) NOT NULL,
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

ALTER TABLE `TAILORED_CLASS` ADD FOREIGN KEY (`student_id`) REFERENCES `STUDENTS`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

ALTER TABLE `COURSE` ADD FOREIGN KEY (`category_id`) REFERENCES `CATEGORY`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `COURSE_IMAGE` ADD FOREIGN KEY (`course_id`) REFERENCES `COURSE`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

CREATE TABLE `fit2104_a2`.`ENROLMENT`
(
    `id` INT NOT NULL AUTO_INCREMENT,
    `course_id` INT NOT NULL,
    `student_id` INT NOT NULL,
    `date` DATE NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `ENROLMENT` ADD FOREIGN KEY (`course_id`) REFERENCES `COURSE`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `ENROLMENT` ADD FOREIGN KEY (`student_id`) REFERENCES `STUDENTS`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `USERS` (`firstName`, `surname`, `email`, `username`, `password`) VALUES
    ('Dane', 'Oldman', 'dane.oldman@gmail.com', 'daneo', SHA2('daneo', 256));

INSERT INTO `STUDENTS` (`firstName`,`surname`,`address`,`phone`,`dob`,`email`,`subscribe`)
VALUES
    ("Priscilla","Davidson","2130 Sodales. Av.","(01) 6624 6189","2015-09-01","dictum.eleifend@hotmail.ca",true),
    ("Hashim","Cohen","Ap #350-2184 Est, St.","(08) 9615 5718","2007-09-26","ipsum.sodales@outlook.ca",false),
    ("Shaeleigh","Alvarado","P.O. Box 105, 9992 Neque Street","(08) 6711 1659","2013-10-17","ligula.donec.luctus@aol.ca",true),
    ("Justin","Garcia","671-7669 Sit St.","(07) 8283 2701","2015-03-08","lacus.ut@icloud.net",true),
    ("Kai","Gardner","502-2957 Nec Av.","(04) 2528 6400","2007-01-03","molestie.dapibus@aol.net",false),
    ("Zoe","Ellis","Ap #627-9211 Magna. Rd.","(08) 2532 6827","2011-06-25","dictum@icloud.couk",false),
    ("Dillon","Solomon","Ap #391-540 Neque Av.","(07) 9650 1432","2004-07-11","ut@google.org",true),
    ("Jerome","Duffy","Ap #711-1549 Ac St.","(02) 1485 4282","2005-05-20","sociis.natoque@aol.ca",true),
    ("Nicole","Snider","Ap #283-3455 Enim. Rd.","(04) 5863 4575","2004-06-07","lacus@hotmail.net",true),
    ("Cathleen","Puckett","Ap #283-7460 Quam St.","(08) 2252 4066","2007-12-20","non.sollicitudin@protonmail.ca",false),
    ("John","Gonzales","1339 Duis St.","(04) 8616 5649","2014-05-09","auctor@google.couk",false),
    ("Kyla","Crosby","Ap #937-7954 Massa. Avenue","(09) 8377 3674","2006-04-04","velit.aliquam.nisl@protonmail.net",false),
    ("Meredith","Wall","Ap #521-3042 Quis St.","(01) 8701 5738","2004-09-17","ante.ipsum@protonmail.couk",true),
    ("Talon","Mitchell","Ap #865-3970 Semper Rd.","(08) 4645 4714","2008-12-17","imperdiet.nec@icloud.ca",false),
    ("Emerald","Lindsey","Ap #194-3018 Sed St.","(02) 7670 8267","2010-08-18","accumsan.sed@protonmail.com",false),
    ("Geoffrey","Barlow","658-6012 In Road","(05) 5952 3268","2010-09-19","leo@aol.net",true),
    ("Malik","Ramirez","130-373 Aliquam, Av.","(08) 0128 8041","2014-12-08","fermentum.fermentum.arcu@yahoo.ca",false),
    ("Larissa","Atkinson","P.O. Box 995, 885 Quis St.","(02) 3883 1746","2016-06-01","nulla.magna@protonmail.net",false),
    ("Macy","Alston","Ap #656-2359 Adipiscing. St.","(03) 9273 8983","2009-03-16","scelerisque.mollis.phasellus@google.ca",true),
    ("Sonya","Nicholson","Ap #848-9143 Sem Road","(08) 7684 3651","2008-09-18","amet@protonmail.net",true),
    ("Aspen","Molina","Ap #637-2754 Ut Rd.","(07) 1815 8781","2013-10-07","sed@outlook.com",true),
    ("Charde","Huffman","463-4693 Et Road","(05) 3125 3614","2016-11-14","tincidunt.nibh@google.ca",false),
    ("Sydney","Gallegos","P.O. Box 965, 4254 Sit Avenue","(06) 8101 6531","2004-09-03","fusce.mollis@protonmail.edu",false),
    ("Boris","Cotton","350-8502 Justo. St.","(03) 1283 6214","2016-07-09","ipsum.suspendisse@outlook.com",false),
    ("Penelope","Alvarez","P.O. Box 882, 300 Egestas Street","(06) 4575 4802","2007-06-17","magna.phasellus.dolor@icloud.ca",false);

INSERT INTO `CATEGORY` (`name`) VALUES
    ("Beginner"),
    ("Intermediate");

INSERT INTO `COURSE` (`name`, `price`, `category_id`) VALUES
    ("Guitar",120,1),
    ("Violin",110,2);

INSERT INTO `TAILORED_CLASS` (`summary`,`start_date`,`end_date`,`quote`,`otherInfo`,`student_id`) VALUES
    ("Extra class ", "2022-10-7", "2022-10-8", "100", "Extra class to explain the guitar theories", "3"),
    ("Practice class", "2022-11-7", "2022-11-8", "120", "Violin practice", "2");

