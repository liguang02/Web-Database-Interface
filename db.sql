DROP TABLE `STUDENTS`, `TAILORED_CLASS`, `COURSE`, `COURSE_IMAGE`, `CATEGORY`, `USERS`, `ENROLMENT`;

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
    `quote` INT NOT NULL,
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
    `parent_id` INT,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `COURSE` ADD FOREIGN KEY (`category_id`) REFERENCES `CATEGORY`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `COURSE_IMAGE` ADD FOREIGN KEY (`course_id`) REFERENCES `COURSE`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `CATEGORY` ADD FOREIGN KEY (`parent_id`) REFERENCES `CATEGORY`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

INSERT INTO `CATEGORY` (`name`, `parent_id`) VALUES
    ("Piano", NULL),
    ("Guitar", NULL),
    ("Violin", NULL),
    ("Percussion", NULL),
    ("Cello", NULL),
    ("Viola", NULL),
    ("Beginner", 1),
    ("Intermediate", 1),
    ("Advanced", 1),
    ("Acoustic", 2),
    ("Classical", 2),
    ("Electric", 2),
    ("Beginner", 3),
    ("Intermediate", 3),
    ("Advanced", 3),
    ("Drums", 4),
    ("Marimba", 4),
    ("Xylophone", 4),
    ("Beginner", 5),
    ("Intermediate", 5),
    ("Beginner", 6),
    ("Intermediate", 6);

INSERT INTO `COURSE` (`name`, `price`, `category_id`) VALUES
    ("Piano Beginner",110,7),
    ("Piano Intermediate",140,8),
    ("Piano Advanced",170,9),
    ("Acoustic Guitar",150,10),
    ("Classical Guitar",150,11),
    ("Electric Guitar",150,12),
    ("Violin Beginner",110,13),
    ("Violin Intermediate",140,14),
    ("Violin Advanced",170,15),
    ("Drums",160,16),
    ("Marimba",140,17),
    ("Xylophone",120,18),
    ("Cello Beginner",110,19),
    ("Cello Intermediate",140,20),
    ("Viola Beginner",110,21),
    ("Viola Intermediate",140,22);

INSERT INTO TAILORED_CLASS (`summary`,`start_date`,`end_date`,`quote`,`otherInfo`,`student_id`) VALUES
    ("Extra theory", "2022-10-07", "2022-10-13", 100, "Extra class to explain guitar theories", 1),
    ("Violin exam practice", "2022-11-07", "2022-11-20", 120, "Extra violin practice sessions to prepare for exam", 2),
    ("Guitar Stringing", "2022-09-07", "2022-09-10", 70, "Tutorial for restringing guitars", 3),
    ("Guitar exam practice", "2022-08-07", "2022-08-20", 150, "Extra violin practice sessions to prepare for exam", 3),
    ("Violin Stringing", "2022-11-17", "2022-11-23", 100, "Tutorial for restringing violins", 4),
    ("Toddler music class", "2022-12-18", "2022-12-19", 60, "Allow toddler to try out instruments", 5),
    ("Advanced Theory", "2022-12-19", "2022-12-30", 200, "Extra class to explain advanced theories", 6),
    ("Music Theory Revision", "2022-12-19", "2022-12-21", 100, "Extra class to revise beginner theories", 7),
    ("Final Exam Revision", "2022-12-24", "2022-12-25", 50, "Extra class to revise syllabus for final exam", 7),
    ("Piano exam practice", "2022-12-20", "2022-12-25", 150, "Extra class to practice for piano exam", 8),
    ("Drum exam practice", "2022-12-21", "2022-12-23", 120, "Extra class to practice for drum exam", 9),
    ("Drum extra class", "2022-12-22", "2022-12-23", 30, "Extra class to practice for drum instrument", 10),
    ("Classical Guitar practice", "2022-12-23", "2022-12-24", 50, "Extra class to practice for classical guitar instrument", 11),
    ("Advanced Classical Guitar practice", "2022-12-23", "2022-12-24", 50, "Extra class to practice for advanced classical guitar instrument", 12),
    ("Intermediate Classical Guitar practice", "2022-12-24", "2022-12-26", 50, "Extra class to practice for intermediate classical guitar instrument", 13),
    ("Intermediate Piano practice", "2022-12-26", "2022-12-27", 50, "Extra class to practice for Advanced piano instrument", 14);

INSERT INTO `ENROLMENT` (`course_id`,`student_id`) VALUES
    (1,1),
    (4,1),
    (11,1),
    (3,2),
    (6,2),
    (15,3),
    (2,4),
    (9,4),
    (10,5),
    (11,5),
    (16,6),
    (9,6),
    (5,7),
    (5,8),
    (6,8),
    (3,9);

INSERT INTO `COURSE_IMAGE` (`course_id`, `filePath`) VALUES
    (1, "course_1_6342b07d4db632.89476702.jpg"),
    (2, "course_2_6342b1b0216d89.34612897.jpg"),
    (3, "course_3_6342b1f3ce92b2.97419952.jpg"),
    (4, "course_4_6342b21da10d57.04035109.jpg"),
    (5, "course_5_6342b265df3183.04233647.jpg"),
    (6, "course_6_6342b2708d0ea2.04883307.jpg"),
    (7, "course_7_6342b90ae076a1.58071153.jpg"),
    (8, "course_8_6342b4deaac383.97296383.jpg"),
    (9, "course_9_6342b4febbaa54.18737296.jpg"),
    (10, "course_10_6342b5f013dcb1.58871381.jpg"),
    (11, "course_11_6342b61e8be434.19634655.jpg"),
    (12, "course_12_6342b640934264.30677624.jpg"),
    (13, "course_13_6342b7457347d6.21645195.jpg"),
    (14, "course_14_6342b74b68aad9.89220624.jpg"),
    (15, "course_15_6342b77fcdd600.15096298.jpg"),
    (16, "course_16_6342b785c572d9.34977735.jpg");