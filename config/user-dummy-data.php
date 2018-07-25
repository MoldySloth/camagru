<?php
include 'database.php';
// DUMMY DATA FOR: users

//  prime dummy data into users table
try {
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT); # Create PDO based off of created DB
	$createsql = "INSERT INTO ".$DB_NAME." . `users` (`id`, `username`, `password`, `salt`, `name`, `email`, `notify_me`, `profile_img`, `active`, `joined`, `group`)
					VALUES ('1','priscilla30','6baaf015834e7d65d95df052cf06556c6f6cfdba8562ff6637e11d67f29615fd','afbb5df0-f28f-30bb-8e51-61c3462e','Celia Graham','zoe67@example.org','1','../resources/icons/DOMO_profile_default.jpg','1','2018-09-09 14:39:28',1),
				('2','bbergnaum','c801d14c84050bd8323f3a42fcc67368007b5865d02a7dc2710caac073b0c3e4','aeef8d17-61c1-32ac-bf65-532ad7a4','Mr. Cletus Becker DDS','dominic04@example.org','1','../resources/icons/DOMO_profile_default.jpg','1','2018-01-07 02:57:10',1),
				('3','cassin.rhea','d7316694e220746a2c3d4599879427ff261c4f908bdde646ff7309d0048bb406','db89b205-5797-3e9a-8a2e-358c3487','Marley Goodwin','flossie48@example.com','1','../resources/icons/DOMO_profile_default.jpg','1','2018-04-10 19:40:33',1),
				('4','julien86','5325322c74896b62dd87ea57d36ce1fe9c9ee6d4feddb7f2b4005f742acd8127','c41e11b0-ec65-30e6-bd22-1efbb4a5','Lafayette Carroll','agustina07@example.com','1','../resources/icons/DOMO_profile_default.jpg','1','2018-12-09 00:36:37',1),
				('5','kamron.dubuque','9edf6597780019ace2bd23875c9d9765ca84312cc30f1db311385bfb3d448c87','0564ecc4-e229-3927-9331-dcd104f1','Ms. Mossie Towne I','considine.darius@example.com','1','../resources/icons/DOMO_profile_default.jpg','1','2018-01-08 03:22:25',1),
				('6','marlon.greenfelder','598bd9818d3f0564940c582dbc07b4bb157c2497a2c1433376ab00b0a5e7f1ef','82dd1d8c-404f-3c7d-bf0b-3ba9b915','Dr. Renee Lesch IV','jaskolski.kira@example.org','1','../resources/icons/DOMO_profile_default.jpg','1','2018-05-22 20:31:18',1),
				('7','collin.johns','340216ba91f12bfcc4ef095b6d614b7919fb16e6a4d65ad979a10168e1e4be7c','1da9e5cf-9a40-38bd-a105-403759f1','Prof. Noemi Smith PhD','bpfeffer@example.net','1','../resources/icons/DOMO_profile_default.jpg','1','2018-09-08 00:02:04',1),
				('8','simonis.elza','fc9f4a042547e0bacd52cbb439a728241e84649e5e3866de783503e6f9264ec7','449deb32-1eb3-35e1-bb37-6cd2be92','Mr. Conrad Hermann V','broderick21@example.net','1','../resources/icons/DOMO_profile_default.jpg','1','2018-10-13 18:53:19',1),
				('9','glenna.kuhic','912d086988b1bf0079acc651820caa16ef78e080ca7c15accf024638df87be4d','5155db10-96f7-3868-898a-da68ab3a','Ike Emmerich','igreenfelder@example.net','1','../resources/icons/DOMO_profile_default.jpg','1','2018-09-03 10:47:26',1),
				('10','gkulas','fc32b2551fe1f02a9b5b05bbf1ca9e8ed63234b2882eed51c808a1eb4534b9c4','4855b922-50ca-3ccf-87fa-b3758ee1','Marta Douglas MD','reyna38@example.org','1','../resources/icons/DOMO_profile_default.jpg','1','2018-04-25 22:21:38',1),
				(11, 'zan', 'bdfaeeb77fd9b81cb0b4144416d579903c6f7feb7bbcd48c8658048f6aa8d635', 'än«3kÄ¾ÔúÿkçÛÆ:íô\fdUn2', 'Rozanne de Jager', 'rozanne.dejager@gmail.com', 0x31, '../resources/icons/DOMO_profile_default.jpg', 0x31, '2018-05-24 21:21:21',1);";
	$pdo->exec($createsql); # EXEC create
	echo "SUCCESS: User has been added to table.\n";
} catch (PDOException $e) {
	echo "FAILURE: Could not add User Table with error: \n".$e->getMessage();
}

//  prime dummy data into gallery table
try {
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT); # Create PDO based off of created DB
	$createsql = "INSERT INTO ".$DB_NAME." . `gallery` (`user_id`, `image_url`, `image_overlay`, `tags`, `created`)
					VALUES ('2','https://picsum.photos/442/442/?image=1067','../resources/overlay_images/doodle_frames_05.png','','2016-09-03 17:47:12'),
						('9','https://picsum.photos/442/442/?image=106','../resources/overlay_images/doodles_love_03.png','','1983-12-29 11:04:43'),
						('4','https://picsum.photos/442/442/?image=1056','../resources/overlay_images/doodles_fun_03.png','','1986-02-09 15:28:43'),
						('4','https://picsum.photos/442/442/?image=985','../resources/overlay_images/doodles_love_01.png','','1977-08-06 16:17:33'),
						('8','https://picsum.photos/442/442/?image=785','../resources/overlay_images/doodles_love_05.png','','2018-03-22 21:25:49'),
						('9','https://picsum.photos/442/442/?image=125','../resources/overlay_images/doodles_fun_02.png','','1990-01-12 23:23:11'),
						('6','https://picsum.photos/442/442/?image=165','../resources/overlay_images/doodle_frames_07.png','','1998-01-02 15:52:09'),
						('9','https://picsum.photos/442/442/?image=159','../resources/overlay_images/doodles_love_06.png','','2000-02-22 13:09:42'),
						('9','https://picsum.photos/442/442/?image=369','../resources/overlay_images/doodle_frames_05.png','','1983-06-11 02:43:23'),
						('7','https://picsum.photos/442/442/?image=257','../resources/overlay_images/doodles_love_01.png','','2010-01-05 05:42:03'),
						('2','https://picsum.photos/442/442/?image=1015','../resources/overlay_images/doodle_frames_03.png','','1977-12-10 04:34:25'),
						('8','https://picsum.photos/442/442/?image=837','../resources/overlay_images/doodles_love_05.png','','2012-07-28 14:11:20'),
						('9','https://picsum.photos/442/442/?image=863','../resources/overlay_images/doodles_fun_05.png','','2000-12-12 09:11:24'),
						('9','https://picsum.photos/442/442/?image=852','../resources/overlay_images/doodle_frames_07.png','','1987-11-14 12:21:44'),
						('2','https://picsum.photos/442/442/?image=456','../resources/overlay_images/doodles_fun_02.png','','1993-04-21 10:54:55'),
						('9','https://picsum.photos/442/442/?image=368','../resources/overlay_images/doodles_love_06.png','','2016-04-27 16:08:56'),
						('4','https://picsum.photos/442/442/?image=951','../resources/overlay_images/doodles_love_01.png','','2006-11-26 22:43:33'),
						('10','https://picsum.photos/442/442/?image=987','../resources/overlay_images/doodle_frames_07.png','','1986-07-19 02:59:36'),
						('6','https://picsum.photos/442/442/?image=365','../resources/overlay_images/doodles_fun_07.png','','1982-11-10 15:16:39'),
						('5','https://picsum.photos/442/442/?image=425','../resources/overlay_images/doodle_frames_01.png','','1981-11-28 11:30:17'),
						('2','https://picsum.photos/442/442/?image=485','../resources/overlay_images/doodles_love_01.png','','1983-07-02 03:30:52'),
						('7','https://picsum.photos/442/442/?image=842','../resources/overlay_images/doodle_frames_07.png','','1983-07-06 17:36:52'),
						('6','https://picsum.photos/442/442/?image=862','../resources/overlay_images/doodles_fun_02.png','','1994-03-10 19:04:50'),
						('9','https://picsum.photos/442/442/?image=940','../resources/overlay_images/doodles_fun_04.png','','1997-02-25 17:32:17'),
						('9','https://picsum.photos/442/442/?image=883','../resources/overlay_images/doodle_frames_08.png','','1994-03-16 21:40:40'),
						('4','https://picsum.photos/442/442/?image=641','../resources/overlay_images/doodle_frames_05.png','','1973-06-26 20:43:00'),
						('8','https://picsum.photos/442/442/?image=821','../resources/overlay_images/doodles_love_05.png','','2004-11-02 04:39:49'),
						('8','https://picsum.photos/442/442/?image=862','../resources/overlay_images/doodles_fun_05.png','','1976-11-29 17:29:49'),
						('2','https://picsum.photos/442/442/?image=384','../resources/overlay_images/doodles_fun_07.png','','2006-10-28 15:40:51'),
						('11','../resources/uploads/11/5b0e6b744679d.png','../resources/overlay_images/doodles_fun_02.png','','1993-04-21 10:54:55'),
						('11','../resources/uploads/11/5b0c551407839.png','../resources/overlay_images/doodles_love_06.png','','2016-04-27 16:08:56'),
						('11','../resources/uploads/11/5b0e6b86870d7.png','../resources/overlay_images/doodles_love_01.png','','2006-11-26 22:43:33'),
						('11','../resources/uploads/11/5b0c55253ce4f.png','../resources/overlay_images/doodle_frames_07.png','','1986-07-19 02:59:36'),
						('11','../resources/uploads/11/5b0e89408ddcb.png','../resources/overlay_images/doodle_frames_05.png','','1986-07-19 02:59:36');";
	$pdo->exec($createsql); # EXEC create
	echo "SUCCESS: Images have been added to gallery table.\n";
} catch (PDOException $e) {
	echo "FAILURE: Could not add images to gallery table with error: \n".$e->getMessage();
}
