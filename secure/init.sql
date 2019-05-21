BEGIN TRANSACTION;
--images for furniture
CREATE TABLE `images`(
`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
`file_name` TEXT UNIQUE NOT NULL,
`file_ext` TEXT NOT NULL,
`name` TEXT NOT NULL,
'price' INTEGER NOT NULL,
'description' TEXT
);

CREATE TABLE `electonic_images`(
`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
`file_name` TEXT UNIQUE NOT NULL,
`file_ext` TEXT NOT NULL,
`name` TEXT NOT NULL,
'price' INTEGER NOT NULL,
'description' TEXT
);


---
-- first images of funiture or electronics
-- all images were taken by Nigel (client) and belong to him
---

INSERT INTO electonic_images(id,file_name,file_ext,name,price,description ) VALUES (1, 'Music Player','jpg','Music Player',80,"This is a music player");

INSERT INTO electonic_images(id,file_name,file_ext,name,price,description ) VALUES (2, 'Macbook Computer','jpg','Macbook Computer',200,"Refurbished computer in good condition");

INSERT INTO electonic_images(id,file_name,file_ext,name,price,description ) VALUES (3, 'Polaroid Camera','jpg','Polaroid Camera',30,"Used polaroid camera in great condition");

--for furniture
CREATE TABLE 'types' (
'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
'type' TEXT UNIQUE NOT NULL
);

CREATE TABLE 'types_for_electronics' (
'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
'type' TEXT UNIQUE NOT NULL
);
INSERT INTO types_for_electronics(id, type) VALUES (1, 'Music');
INSERT INTO types_for_electronics(id, type) VALUES (2, 'Computers');
INSERT INTO types_for_electronics(id, type) VALUES (3, 'Cameras');

-- Joined table of furniture_images and types(useful for album creation)
CREATE TABLE `furniture_types`(
`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
`image_id` INTEGER NOT NULL,
'type_id' INTEGER NOT NULL
);

CREATE TABLE `electronic_types`(
`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
`elec_image_id` INTEGER NOT NULL,
'elec_type_id' INTEGER NOT NULL
);
INSERT INTO electronic_types(id, elec_image_id,elec_type_id) VALUES (1,1,1);
INSERT INTO electronic_types(id, elec_image_id,elec_type_id) VALUES (2,2,2);
INSERT INTO electronic_types(id, elec_image_id,elec_type_id) VALUES (3,3,3);

--Create User Table

CREATE TABLE users (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    user_name TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);

--User Seed Data

INSERT INTO users (id, user_name, password) VALUES
(1, 'admin', '$2y$10$WTAMKcgGiRmjgtoDjAKG6Otd8iUQWBJN0ZDxq/olW59fpRWHXS2zq'); --password: Nigel

--Create Sessions table (for the users)

CREATE TABLE `sessions`(
`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
`user_id` INTEGER NOT NULL,
`session` TEXT NOT NULL UNIQUE
);


CREATE TABLE `reviews`(
`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
`reviewer` TEXT NOT NULL,
`comment` TEXT NOT NULL
);

CREATE TABLE `contact`(
`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
`viewer_name` TEXT NOT NULL,
`viewer_email` TEXT NOT NULL,
`viewer_question` TEXT NOT NULL
);
CREATE TABLE `price_range`(
`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
`price_range` TEXT NOT NULL
);

CREATE TABLE `furniture_price_range`(
`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
`image_id` INTEGER NOT NULL,
`price_range_id` INTEGER NOT NULL
);

CREATE TABLE `electronic_price_range`(
`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
`elec_image_id` INTEGER NOT NULL,
`price_range_id` INTEGER NOT NULL
);
INSERT INTO electronic_price_range(id,elec_image_id,price_range_id) VALUES(1,1,2);
INSERT INTO electronic_price_range(id,elec_image_id,price_range_id) VALUES(2,2,1);
INSERT INTO electronic_price_range(id,elec_image_id,price_range_id) VALUES(3,3,4);

INSERT INTO reviews (id, reviewer, comment) VALUES (1, 'Anonymous', 'As a customer, I can say that Nu2u Furniture Of Ithaca is a great business to work with! I have been impressed with the timely and professional manner or responses, as well as the flexibility of pickup and delivery times. The business owner and employees will go above and beyond to make sure that his customers find the right piece to meet their aesthetic preferences, their functional needs, and their budgets. With many years of experience, the business owner has an eye for quality and takes genuine satisfaction in matching customers to the right pieces;his enthusiasm for the work is evident. Although I initially arrived at the warehouse to pick up a small bookcase, I ended up acquiring a lovely vintage bureau as wel as some stunning floor-to-ceiling bookcases, which now grace my living room and guest bedroom. While I have enjoyed all of the pieces that have been reccomended to me, I can say I- as a self proclaimed bibliophile- have particularly enjoyed floor-to-ceiling bookcases, which were repurposed from Buffalo Street Books. Now, each time I walk into my living room, I am reminded that I own a small piece of Ithaca culture!');
INSERT INTO reviews (id, reviewer, comment) VALUES (2, 'Anonymous', 'My experience as a satisfied customer happily segued into a position as part-time employee at Nu2u Furniture Of Ithaca. This was more by happenstance than design, but it turned out that I had skills to offer and the business needed some extra help. So it was that I started working on furniture restoration for Nu2u Furniture in my spare time. The work has been challenging, yet enjoyable! I have valued the opportunity to put my skills (and my tools) to work, all while earning some extra income on the side. I can say that the wages available from Nu2u Furniture Of Ithaca - both hourly and commission - are fair and just, exceeding the local living wage. I have also been very grateful for the flexibility offered by Nu2u Furniture Of Ithaca, as I also have a full-time job and can only work evenings and weekends. If you have time and skills in woodworking, upholstery, and/or cleaning, I highly recommend stopping by Nu2u Furniture Of Ithaca to discuss employment opportunities.');


INSERT INTO contact (id, viewer_name, viewer_email,viewer_question) VALUES (1, 'Shangzhen', 'sw925@cornell.edu','How much is it?');

INSERT INTO types(id, type) VALUES (1,'Chairs');
INSERT INTO types(id, type) VALUES (2,'Couches');
INSERT INTO types(id, type) VALUES (3, 'Tables');
INSERT INTO types(id, type) VALUES (4, 'Desks');




--Price Range table
INSERT INTO price_range(id, price_range) VALUES (1,'0-50');
INSERT INTO price_range(id, price_range) VALUES (2,'50-100');
INSERT INTO price_range(id, price_range) VALUES (3,'100-150');
INSERT INTO price_range(id, price_range) VALUES (4,'150-200');

-- TODO: create tables

-- TODO: initial seed data


---
-- first images of funiture
-- all images were taken by Nigel (client) and belong to him
---

INSERT INTO images (id, file_name, file_ext,name,price,description) VALUES (1, 'bluecouch1','png','La-Z-Boy Signature II Sleeper Sofa With Throw Pillows',160,'Solid and sturdy shape with little wear

Pullout bed mechanism is sound and works nicely

Comes with 2 pairs of armrest covers and one headrest cover

Dimensions are 78.75 inches wide, 34.75 inches deep, and 36 inches high

With the bed out the sofa is 88 inches deep');
INSERT INTO images (id, file_name, file_ext,name,price,description) VALUES (2, 'bluecouch2','png','La-Z-Boy Signature II Sleeper Sofa With Throw Pillows',160,'Solid and sturdy shape with little wear

Pullout bed mechanism is sound and works nicely

Comes with 2 pairs of armrest covers and one headrest cover

Dimensions are 78.75 inches wide, 34.75 inches deep, and 36 inches high

With the bed out the sofa is 88 inches deep');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (1,1, 2);
INSERT INTO furniture_types(id,image_id, type_id) VALUES (2,2, 2);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(1,1,3);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(2,2,3);


INSERT INTO images (id, file_name, file_ext,name,price,description) VALUES (3, 'leathercouch1', 'png','Leather Frame Sofa With Throw Pillows',200,'Dimensions are 87.75 inches wide, 40 inches deep, and 37.5 inches high');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (3,3, 2);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(3,3,3);


INSERT INTO images (id, file_name, file_ext,name, price, description) VALUES (4, 'metaldesk1', 'png','Metal HON Desk With Wood Top', 60,'Can provide matching HON desk chair
Can provide other desk chairs
Dimensions are 48.25 inches wide, 30 inches deep, and 29.5 inches high');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (4,4, 4);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(4,4,2);


INSERT INTO images (id, file_name, file_ext,name, price, description) VALUES (5, 'corduroychair', 'png','Vintage 1960s Cream Corduroy Armchair', 45,'Solid and sturdy shape

A few tiny areas of expected wear due to age

Dimensions are 31.5 inches wide, 33 inches deep, and 31.75 inches high
');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (5,5, 1);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(5,5,1);


INSERT INTO images (id, file_name, file_ext,name, price, description) VALUES (6, 'brownrocker', 'png','Vintage 1960s Recliner Rocker',65,'Reclining and rocking mechanisms are perfectly sound and work nicely

Dimensions are 34 inches wide, 34.75 inches deep, and 41.75 inches high

Chair is 57.5 inches deep fully reclined');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (6,6, 1);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(6,6,2);


INSERT INTO images (id, file_name, file_ext,name, price, description) VALUES (7, 'wickerrocker', 'png','Vintage Solid Wood Rocking Chair With Wicker Seat',40,'Nice as is or perfect for restoration

Dimensions are 28.25 inches wide, 31 inches deep. and 43.25 inches high
');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (7,7, 1);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(7,7,1);


INSERT INTO images (id, file_name, file_ext,name, price, description) VALUES (8, 'vanillatable', 'png','Vanilla Single Drawer Nightstand/End Table',25,'Dimensions are 19.25 inches wide, 16.5 inches deep, and 20.75 inches high');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (8,8, 3);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(8,8,1);


INSERT INTO images (id, file_name, file_ext,name, price, description) VALUES (9, 'oaktable', 'png','Solid Oak Table',120,'Dimensions are 60 inches wide, 36 inches deep, and 29.75 inches high');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (9,9, 3);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(9,9,3);


INSERT INTO images (id, file_name, file_ext,name, price, description) VALUES (10, 'woodarmchair', 'png','Solid Wood Armchair With Wicker Back & Padded Seat',35,'Solid Wood Armchair With Wicker Back & Padded Seat

Padded seat was vacuumed and power washed

Dimensions are 22.5 inches wide, 24 inches deep, and 38.75 inches high');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (10,10, 1);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(10,10,1);


INSERT INTO images (id, file_name, file_ext,name, price, description) VALUES (11, 'swivelchair', 'png','Rolling Swivel Metal Frame Armchair',40,'Rolling Swivel Metal Frame Armchair

Made by Cal-Style Furniture Manufacturing Company of Compton, California--makers of quality furniture since 1951');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (11,11, 1);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(11,11,1);


INSERT INTO images (id, file_name, file_ext,name, price, description) VALUES (12, 'smallstudentdesk', 'png','Small Student Desk',35,'Dimensions are 34.75 inches wide, 18.5 inches deep, and 30.25 inches high');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (12,12, 4);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(12,12,1);


INSERT INTO images (id, file_name, file_ext,name, price, description) VALUES (13, 'rainbowloveseat', 'png','Pink/Rainbow Loveseat',35,'Dimensions are 34.75 inches wide, 18.5 inches deep, and 30.25 inches high');
INSERT INTO furniture_types(id,image_id, type_id) VALUES (13,13, 2);
INSERT INTO furniture_price_range(id,image_id,price_range_id) VALUES(13,13,1);

--all images on the content pages were taken by the client nigel and belong to him
-- TODO: FOR HASHED PASSWORDS, LEAVE A COMMENT WITH THE PLAIN TEXT PASSWORD!

--username: admin
--password: Nigel

COMMIT;
