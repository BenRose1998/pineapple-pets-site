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
  CONSTRAINT check_form_status CHECK(
    form_status IN ("Initiated", "Approved", "Rejected", "Finalised")
  )
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
  CONSTRAINT FK_question_category_id FOREIGN KEY (category_id) REFERENCES category(category_id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS possible_answer(
  possible_answer_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  question_id INT(64) NOT NULL,
  possible_answer_value VARCHAR(100) NOT NULL,
  CONSTRAINT FK_possible_answer_question_id FOREIGN KEY (question_id) REFERENCES question(question_id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS answer(
  answer_id INT(64) PRIMARY KEY AUTO_INCREMENT,
  form_id INT(64) NOT NULL,
  question_id INT(64) NOT NULL,
  possible_answer_id INT(64) NULL,
  answer_value TEXT(1000),
  CONSTRAINT FK_answer_form_id FOREIGN KEY (form_id) REFERENCES form(form_id),
  CONSTRAINT FK_answer_question_id FOREIGN KEY (question_id) REFERENCES question(question_id) ON DELETE CASCADE,
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
    'Admin',
    'User',
    'admin@admin.com',
    '$2y$10$07I8wgv643Ol/lTtguU0QerEBi23ht3cue.50GBLV0cWvyFG0MWsq',
    '2020-01-31 17:46:56'
  );

INSERT INTO user
VALUES
  (
    3,
    'Test',
    '1',
    'test1@test.com',
    '$2y$10$07I8wgv643Ol/lTtguU0QerEBi23ht3cue.50GBLV0cWvyFG0MWsq',
    '2020-01-31 17:46:56'
  );
INSERT INTO user
VALUES
  (
    4,
    'Test',
    '2',
    'test2@test.com',
    '$2y$10$07I8wgv643Ol/lTtguU0QerEBi23ht3cue.50GBLV0cWvyFG0MWsq',
    '2020-01-31 17:46:56'
  );
INSERT INTO user
VALUES
  (
    5,
    'Test',
    '3',
    'test3@test.com',
    '$2y$10$07I8wgv643Ol/lTtguU0QerEBi23ht3cue.50GBLV0cWvyFG0MWsq',
    '2020-01-31 17:46:56'
  );
INSERT INTO user
VALUES
  (
    6,
    'Test',
    '4',
    'test4@test.com',
    '$2y$10$07I8wgv643Ol/lTtguU0QerEBi23ht3cue.50GBLV0cWvyFG0MWsq',
    '2020-01-31 17:46:56'
  );
INSERT INTO user
VALUES
  (
    7,
    'Test',
    '5',
    'test5@test.com',
    '$2y$10$07I8wgv643Ol/lTtguU0QerEBi23ht3cue.50GBLV0cWvyFG0MWsq',
    '2020-01-31 17:46:56'
  );
INSERT INTO user
VALUES
  (
    8,
    'Test',
    '6',
    'test6@test.com',
    '$2y$10$07I8wgv643Ol/lTtguU0QerEBi23ht3cue.50GBLV0cWvyFG0MWsq',
    '2020-01-31 17:46:56'
  );
INSERT INTO user
VALUES
  (
    9,
    'Test',
    '7',
    'test7@test.com',
    '$2y$10$07I8wgv643Ol/lTtguU0QerEBi23ht3cue.50GBLV0cWvyFG0MWsq',
    '2020-01-31 17:46:56'
  );
INSERT INTO user
VALUES
  (
    10,
    'Test',
    '8',
    'test8@test.com',
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
INSERT INTO pet_breed
VALUES
  (3, 'European shorthair');
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
INSERT INTO pet
VALUES
  (
    3,
    2,
    3,
    'Molly',
    2,
    1,
    'cat.jpg',
    'Test description',
    true
  );
INSERT INTO pet
VALUES
  (
    4,
    2,
    3,
    'Holly',
    3,
    1,
    'cat.jpg',
    'Test description',
    true
  );
INSERT INTO staff
VALUES
  (1, 1, true);
INSERT INTO staff
VALUES
  (2, 2, true);
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
-- Form 1
INSERT INTO form VALUES (1, 2, 2, 'Initiated', '2020-03-23 18:09:11');
INSERT INTO answer (
    answer_id,
    form_id,
    question_id,
    possible_answer_id,
    answer_value
  )
VALUES
  (1, 1, 1, NULL, '66'),
  (2, 1, 2, NULL, 'Roebuck Ridge'),
  (3, 1, 3, NULL, 'Barnsley'),
  (4, 1, 4, NULL, 'South Yorkshire'),
  (5, 1, 5, NULL, 'S74 0LJ'),
  (6, 1, 6, NULL, 'test@test.com'),
  (7, 1, 7, NULL, '07747 085603'),
  (8, 1, 8, NULL, '07747 085603'),
  (9, 1, 9, 1, NULL),
  (10, 1, 10, 3, NULL),
  (11, 1, 11, 11, NULL),
  (12, 1, 12, 13, NULL),
  (13, 1, 13, 18, NULL),
  (14, 1, 14, 28, NULL),
  (15, 1, 15, 31, NULL),
  (16, 1, 16, 41, NULL),
  (17, 1, 17, NULL, 'N/A');
-- Form 2
INSERT INTO form VALUES (2, 3, 3, 'Initiated', '2020-03-23 18:09:11');
INSERT INTO answer (
  answer_id,
  form_id,
  question_id,
  possible_answer_id,
  answer_value
)
VALUES
(18, 2, 1, NULL, '66'),
(19, 2, 2, NULL, 'Roebuck Ridge'),
(20, 2, 3, NULL, 'Barnsley'),
(21, 2, 4, NULL, 'South Yorkshire'),
(22, 2, 5, NULL, 'S74 0LJ'),
(23, 2, 6, NULL, 'test@test.com'),
(24, 2, 7, NULL, '07747 085603'),
(25, 2, 8, NULL, '07747 085603'),
(26, 2, 9, 1, NULL),
(27, 2, 10, 3, NULL),
(28, 2, 11, 11, NULL),
(29, 2, 12, 13, NULL),
(30, 2, 13, 18, NULL),
(31, 2, 14, 28, NULL),
(32, 2, 15, 31, NULL),
(33, 2, 16, 41, NULL),
(34, 2, 17, NULL, 'N/A');