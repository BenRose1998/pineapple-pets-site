DROP DATABASE IF EXISTS pets;
CREATE DATABASE IF NOT EXISTS pets;
USE pets;

-- Tables
CREATE TABLE IF NOT EXISTS user(
  user_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  user_first_name VARCHAR(20) NOT NULL,
  user_last_name VARCHAR(20) NOT NULL,
  user_email VARCHAR(40) NOT NULL,
  user_password VARCHAR(255) NOT NULL,
  user_created DATETIME NOT NULL
);
CREATE TABLE IF NOT EXISTS staff(
  staff_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  user_id INT(64) NOT NULL,
  staff_email_notification BOOLEAN DEFAULT false,
  CONSTRAINT FK_staff_user_id FOREIGN KEY (user_id) REFERENCES user(user_id)
);
CREATE TABLE IF NOT EXISTS pet_type(
  pet_type_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  pet_type_name VARCHAR(20) NOT NULL
);
CREATE TABLE IF NOT EXISTS pet_breed(
  pet_breed_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  pet_breed_name VARCHAR(20) NOT NULL
);
CREATE TABLE IF NOT EXISTS pet(
  pet_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  pet_type_id INT(64) NOT NULL,
  pet_breed_id INT(64) NOT NULL,
  pet_name VARCHAR(20) NOT NULL,
  pet_age INT(2) NOT NULL,
  pet_gender INT(1) NOT NULL,
  pet_image VARCHAR(100) NOT NULL,
  pet_description TEXT(1000),
  pet_active BOOLEAN DEFAULT true,
  CONSTRAINT FK_pet_pet_type_id FOREIGN KEY (pet_type_id) REFERENCES pet_type(pet_type_id),
  CONSTRAINT FK_pet_pet_breed_id FOREIGN KEY (pet_breed_id) REFERENCES pet_breed(pet_breed_id),
  CONSTRAINT check_pet_gender CHECK(pet_gender IN (0, 1))
);
CREATE TABLE IF NOT EXISTS form(
  form_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  user_id INT(64) NOT NULL,
  pet_id INT(64) NOT NULL,
  form_status VARCHAR(64) DEFAULT "Initiated",
  form_created DATETIME NOT NULL,
  CONSTRAINT FK_form_user_id FOREIGN KEY (user_id) REFERENCES user(user_id),
  CONSTRAINT FK_form_pet_id FOREIGN KEY (pet_id) REFERENCES pet(pet_id),
  CONSTRAINT check_form_status CHECK(form_status IN ("Initiated", "Approved", "Rejected", "Finalised"))
);
CREATE TABLE IF NOT EXISTS category(
  category_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  category_name VARCHAR(64) NOT NULL
);
CREATE TABLE IF NOT EXISTS question(
  question_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  category_id INT(64) NOT NULL,
  question_text TEXT(200) NOT NULL,
  question_type VARCHAR(10) NOT NULL,
  CONSTRAINT FK_question_category_id FOREIGN KEY (category_id) REFERENCES category(category_id)
);
CREATE TABLE IF NOT EXISTS possible_answer(
  possible_answer_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  question_id INT(64) NOT NULL,
  possible_answer_value VARCHAR(100) NOT NULL,
  CONSTRAINT FK_possible_answer_question_id FOREIGN KEY (question_id) REFERENCES question(question_id)
);
CREATE TABLE IF NOT EXISTS answer(
  answer_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  form_id INT(64) NOT NULL,
  question_id INT(64) NOT NULL,
  possible_answer_id INT(64),
  answer_value TEXT(1000),
  CONSTRAINT FK_answer_form_id FOREIGN KEY (form_id) REFERENCES form(form_id),
  CONSTRAINT FK_answer_question_id FOREIGN KEY (question_id) REFERENCES question(question_id),
  CONSTRAINT FK_answer_possible_answer_id FOREIGN KEY (possible_answer_id) REFERENCES possible_answer(possible_answer_id)
);

-- Indexes
CREATE INDEX index_user ON user(user_email);
CREATE INDEX index_form ON form(form_status);

INSERT INTO user
VALUES
  (
    1,
    'Ben',
    'Rose',
    'benrose11@hotmail.co.uk',
    '$2y$10$07I8wgv643Ol/lTtguU0QerEBi23ht3cue.50GBLV0cWvyFG0MWsq',
    '2020-01-31 17:46:56'
  );
INSERT INTO user
VALUES
  (
    2,
    'David',
    'Rose',
    'david@david.com',
    '$2y$10$07I8wgv643Ol/lTtguU0QerEBi23ht3cue.50GBLV0cWvyFG0MWsq',
    '2020-01-31 17:46:56'
  );
INSERT INTO user
VALUES
  (
    3,
    'Admin',
    'User',
    'admin@admin.com',
    '$2y$10$07I8wgv643Ol/lTtguU0QerEBi23ht3cue.50GBLV0cWvyFG0MWsq',
    '2020-01-31 17:46:56'
  );
INSERT INTO pet_type
VALUES
  (1, 'Dog');
INSERT INTO pet_type
VALUES
  (2, 'Cat');
INSERT INTO pet_breed
VALUES
  (1, 'Labrador');
INSERT INTO pet_breed
VALUES
  (2, 'Border Collie');
INSERT INTO pet
VALUES
  (
    1,
    1,
    1,
    'Jack',
    4,
    0,
    'nancy.jpg',
    'Test description',
    true
  );
INSERT INTO pet
VALUES
  (
    2,
    1,
    1,
    'Charlie',
    6,
    0,
    'puppy.jpg',
    'Test description',
    true
  );
INSERT INTO staff
VALUES
  (1, 1, true);
INSERT INTO staff
VALUES
  (2, 3, true);
INSERT INTO category (category_name)
VALUES
  ('Address');
INSERT INTO category (category_name)
VALUES
  ('Property');
INSERT INTO category (category_name)
VALUES
  ('Pets');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (1, 'House number/name', 'input');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (1, 'Street', 'input');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (1, 'Town', 'input');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (1, 'County', 'input');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (1, 'Postcode', 'input');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (1, 'Email address', 'input');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (1, 'Landline number', 'input');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (1, 'Mobile number', 'input');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (
    2,
    'Do you Own or Rent your property?',
    'dropdown'
  );
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (2, 'Type of property', 'dropdown');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (2, 'Do you have a partner?', 'dropdown');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (2, 'Children and their ages', 'dropdown');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (2, 'Employment', 'dropdown');
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (
    3,
    'Do you have any pets living with you?',
    'dropdown'
  );
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (
    3,
    'If yes, please tick boxes that apply',
    'check'
  );
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (
    3,
    'Can you provide a vet reference? (if requested)',
    'dropdown'
  );
INSERT INTO question (category_id, question_text, question_type)
VALUES
  (
    3,
    'Any other information you would like to share with us. Hobbies, interests, suitability for being an adopter/foster',
    'text'
  );
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (9, 'Own');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (9, 'Rent');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (10, 'House with Garden');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (10, 'House without Garden');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (10, 'Flat/Apartment with Garden/Communal area');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (10, 'Flat/Apartment without Garden');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (10, 'Boat');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (10, 'Caravan');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (10, 'Other');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (11, 'Yes');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (11, 'No');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (11, 'Yes, but we do not live together');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (12, 'No children');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (12, 'Yes, under 16 years old');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (12, 'Yes, over 16 years old');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (12, 'Visiting child/children under 16 years old');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (12, 'Visiting child/children over 16 years old');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (13, 'Full time (Over 30 hours per week)');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (13, 'Part time (Under 30 hours per week)');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (13, 'Unemployed');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (13, 'Retired');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (13, 'Carer');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (13, 'Self-employed');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (13, 'Working from home');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (13, 'Sick/disabled');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (13, 'Partner works full or part time');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (13, 'Stay at home parent');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (14, 'Yes');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (14, 'No');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (14, 'Sometimes');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (15, 'Dog(s) neutered/spayed/vaccinated');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (15, 'Dog(s) unneutered/unsprayed/unvaccinated');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (15, 'Cat(s) neutered/sprayed');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (15, 'Cat(s) unneutered/unsprayed');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (15, 'Rabbit(s) neutered/spayed/vaccinated');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (15, 'Rabbit(s) unneutered/unspayed/unvaccinated');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (15, 'Bird(s)');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (15, 'Fish');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (15, 'Reptiles');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (15, 'Other');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (16, 'Yes');
INSERT INTO possible_answer (question_id, possible_answer_value)
VALUES
  (16, 'No');