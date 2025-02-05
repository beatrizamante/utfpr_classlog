SET foreign_key_checks = 0;

DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS schedules;
DROP TABLE IF EXISTS user_subjects;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS classrooms;
DROP TABLE IF EXISTS blocks;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS profile_images;
DROP TABLE IF EXISTS schedule_exceptions;

CREATE TABLE `roles` (
                       `id` INT AUTO_INCREMENT PRIMARY KEY,
                       `name` VARCHAR(255) NOT NULL
);

CREATE TABLE `blocks` (
                        `id` INT AUTO_INCREMENT PRIMARY KEY,
                        `name` VARCHAR(255) NOT NULL
);

CREATE TABLE `users` (
                       `id` INT AUTO_INCREMENT PRIMARY KEY,
                       `name` VARCHAR(255) NOT NULL,
                       `university_registry` VARCHAR(255) UNIQUE NOT NULL,
                       `encrypted_password` VARCHAR(255) NOT NULL,
                       `role_id` INT NOT NULL,
                       CONSTRAINT fk_role FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
);

CREATE TABLE `profile_images` (
                                `id` INT AUTO_INCREMENT PRIMARY KEY,
                                `url` VARCHAR(255) NOT NULL DEFAULT './assets/image/avatar.jpg',
                                `user_id` INT NOT NULL,
                                CONSTRAINT unique_user_image UNIQUE(user_id),
                                CONSTRAINT fk_user FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

CREATE TABLE `classrooms` (
                            `id` INT AUTO_INCREMENT PRIMARY KEY,
                            `block_id` INT NOT NULL,
                            `name` VARCHAR(255) NOT NULL,
                            CONSTRAINT fk_block FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`)
);

CREATE TABLE `subjects` (
                          `id` INT AUTO_INCREMENT PRIMARY KEY,
                          `name` VARCHAR(255) NOT NULL,
                          `semester` VARCHAR(255)
);



CREATE TABLE `user_subjects` (
                               `id` INT AUTO_INCREMENT PRIMARY KEY,
                               `subject_id` INT NOT NULL,
                               `user_id` INT NOT NULL,
                               CONSTRAINT fk_user_subjects_user_id FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
                               CONSTRAINT fk_subject FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`)
);

CREATE TABLE `schedules` (
                           `id` INT AUTO_INCREMENT PRIMARY KEY,
                           `start_time` TIME NOT NULL,
                           `end_time` TIME NOT NULL,
                           `day_of_week` INT NOT NULL,
                           `default_day` BOOLEAN NULL,
                           `exceptional_day` BOOLEAN NULL,
                           `date` DATE NULL,
                           `is_canceled` BOOLEAN NOT NULL,
                           `user_subject_id` INT NOT NULL,
                           `classroom_id` INT NOT NULL,
                           CONSTRAINT fk_user_subjects FOREIGN KEY (`user_subject_id`) REFERENCES `user_subjects` (`id`),
                           CONSTRAINT fk_classroom_schedules FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`)

);

CREATE TABLE `reservations` (
                              `id` INT AUTO_INCREMENT PRIMARY KEY,
                              `schedule_id` INT NOT NULL,
                              `subjects_id` INT NOT NULL,
                              `classroom_id` INT NOT NULL,
                              CONSTRAINT fk_schedule FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`),
                              CONSTRAINT fk_subjects FOREIGN KEY (`subjects_id`) REFERENCES `subjects` (`id`),
                              CONSTRAINT fk_classroom FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`)
);


SET foreign_key_checks = 1;
