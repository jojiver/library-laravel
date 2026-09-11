-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: library
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isbn` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `published_year` int NOT NULL,
  `quantity` int NOT NULL,
  `available_quantity` int NOT NULL,
  `status` enum('available','unavailable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `description` text COLLATE utf8mb4_unicode_ci,
  `book_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `books_isbn_unique` (`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES ('01a08dc8-64b0-737b-9954-25d7327eb58a','The Power of Now','Eckhart Tolle','9782090099836','Self Help',1994,5,5,'available','It\'s no wonder that The Power of Now has sold over 2 million copies worldwide and has been translated into over 30 foreign languages. Much more than simple principles and platitudes, the book takes readers on an inspiring spiritual journey to find their true and deepest self and reach the ultimate in personal growth and spirituality: the discovery of truth and light. In the first chapter, Tolle introduces readers to enlightenment and its natural enemy, the mind. He awakens readers to their role as a creator of pain and shows them how to have a pain-free identity by living fully in the present. The journey is thrilling, and along the way, the author shows how to connect to the indestructible essence of our Being, \"the eternal, ever-present One Life beyond the myriad forms of life that are subject to birth and death.\" Featuring a new preface by the author, this paperback shows that only after regaining awareness of Being, liberated from Mind and intensely in the Now, is there Enlightenment.','books/aujQxMm0izyoglfcJ2T4LQFtERM01XBKibIAGfLY.jpg','2026-09-11 00:05:15','2026-09-11 14:40:24'),('01a08dc8-650a-7120-b9c7-3b7c9039486b','The art of computer programming','Donald Knuth','9793329859985','Programming',1973,5,5,'available','The Art of Computer Programming by Donald Knuth is a comprehensive series that explores fundamental algorithms and computer programming techniques. It covers topics such as data structures, sorting, searching, mathematical algorithms, and the analysis of algorithms. The books are widely regarded as an important reference for students, programmers, and computer science professionals.','books/E5khLAgXnZdt3mBj2ZRbhq8sfY1KUMwwz3PFjgb7.jpg','2026-09-11 00:05:15','2026-09-11 14:24:41'),('01a08dc8-6512-71a7-9aeb-7d1fe9c8f21a','Deep Work','Cal Newport','9798148953104','Self Help',2016,5,5,'available','One of the most valuable skills in our economy is becoming increasingly rare. If you master this skill, you\'ll achieve extraordinary results.\r\n\r\nDeep work is the ability to focus without distraction on a cognitively demanding task. It\'s a skill that allows you to quickly master complicated information and produce better results in less time. Deep work will make you better at what you do and provide the sense of true fulfillment that comes from craftsmanship. In short, deep work is like a super power in our increasingly competitive twenty-first century economy. And yet, most people have lost the ability to go deep-spending their days instead in a frantic blur of e-mail and social media, not even realizing there\'s a better way.\r\n\r\nIn DEEP WORK, author and professor Cal Newport flips the narrative on impact in a connected age. Instead of arguing distraction is bad, he instead celebrates the power of its opposite. Dividing this book into two parts, he first makes the case that in almost any profession, cultivating a deep work ethic will produce massive benefits. He then presents a rigorous training regimen, presented as a series of four \"rules,\" for transforming your mind and habits to support this skill.\r\n\r\nA mix of cultural criticism and actionable advice, DEEP WORK takes the reader on a journey through memorable stories -- from Carl Jung building a stone tower in the woods to focus his mind, to a social media pioneer buying a round-trip business class ticket to Tokyo to write a book free from distraction in the air -- and no-nonsense advice, such as the claim that most serious professionals should quit social media and that you should practice being bored. DEEP WORK is an indispensable guide to anyone seeking focused success in a distracted world.','books/xtv3lNssaS6ADnWMUeUvyVsnoiQvhxChRdT0f9qn.jpg','2026-09-11 00:05:15','2026-09-11 14:40:32'),('01a08dc8-6517-73f3-be75-d9d2f82dee84','Machine learning','Kevin P. Murphy','9785805609931','programming & hardware',2012,5,5,'available','\"This textbook offers a comprehensive and self-contained introduction to the field of machine learning, based on a unified, probabilistic approach. The coverage combines breadth and depth, offering necessary background material on such topics as probability, optimization, and linear algebra as well as discussion of recent developments in the field, including conditional random fields, L1 regularization, and deep learning. The book is written in an informal, accessible style, complete with pseudo-code for the most important algorithms. All topics are copiously illustrated with color images and worked examples drawn from such application domains as biology, text processing, computer vision, and robotics. Rather than providing a cookbook of different heuristic methods, the book stresses a principled model-based approach, often using the language of graphical models to specify models in a concise and intuitive way. Almost all the models described have been implemented in a MATLAB software package--PMTK (probabilistic modeling toolkit)--that is freely available online\"--Back cover.','books/hi8e1fFMmMkAiZZNiYLz5zATJYGkgwneXL4oYtBT.jpg','2026-09-11 00:05:15','2026-09-11 14:30:15'),('01a08dc8-651e-72d4-b734-bcb551bcadea','Earth Science','Edward J. Tarbuck and Frederick K. Lutgens','9786688016335','Science',1974,5,5,'available','Earth Science by Edward J. Tarbuck and Frederick K. Lutgens is a textbook that introduces the study of Earth and its physical processes. It covers topics such as geology, rocks and minerals, earthquakes, volcanoes, weather, climate, oceans, and Earth\'s history. It is designed to help students understand how Earth\'s systems work and how they shape the planet.','books/9ZzktznuX9r9LX1GtJ6p4Ixo32FX6xGXvO52rNWg.jpg','2026-09-11 00:05:15','2026-09-11 14:27:18'),('01a08dc8-6524-71a5-b475-c39946338811','No Longer Human','Osamu Dazai','9792082290813','Novel',1948,5,5,'available','The story follows Oba Yozo, a man who feels \"disqualified as a human being\" due to an inability to connect with others.  To mask his deep-seated alienation and fear, he adopts a clownish persona, which leads to a downward spiral of alcoholism, drug addiction, and failed suicide attempts. The narrative explores intense themes of social isolation, mental illness, and the struggle for identity in a modernizing society','books/rekLUcR40NfzUw3LqCZc7eWwKh3qa3Jzme0QogxN.jpg','2026-09-11 00:05:15','2026-09-11 14:19:34'),('01a08dc8-652d-7231-8a44-09fd31f29a43','How To Win Friends And Influence People','Dale Carnegie','9794621534037','Self Help',1936,5,5,'available','Available for the first time ever in trade paperback, Dale Carnegie\'s enduring classic, the inspirational personal development guide that shows how to achieve lifelong success. One of the top-selling books of all time, \"How to Win Friends & Influence People\" has sold more than 15 million copies in all its editions.','books/Nbm6I9P9uCSMg6duVbgpDC3XdCIdYGKy3bYZXObH.jpg','2026-09-11 00:05:15','2026-09-11 14:40:39'),('01a08dc8-6534-71fe-9e83-af6363fe6950','The 7 Habits of Highly Effective People','Stephen R. Covey and Sean Covey','9791029186233','Self Help',1989,5,5,'available','A step-by-step pathway to the principles of fairness, integrity, and human dignity that defines a way of life and leads to success in business.','books/gQc4qMUWsmmpGZoiUdRtOZ4fTBWdCL2QvaGDaqST.jpg','2026-09-11 00:05:15','2026-09-11 14:40:47'),('01a08dc8-6539-72e1-a717-69c4b99d6750','Chemistry','Theodore L. Brown, H. Eugene Lemay,','9789347618437','Science',2017,5,5,'available','The book provides the basis of modern chemistry that every student needs for their professional development and as preparation for more complex chemistry courses. It also offers features that facilitate learning and serve as a guide for students to acquire a conceptual understanding and the skills needed to solve problems. The first five chapters offer a microscopic and phenomenological view of chemistry, while the latter review the chemistry of nonmetals, metals, organic chemistry and biochemistry.','books/KzearCZhj3FvboUQRrHrLa8vGjpuv0EKeuOiOCeU.jpg','2026-09-11 00:05:15','2026-09-11 14:13:34'),('01a08dc8-6540-7164-899d-440445e91507','Atomic Habits','James Clear','9798581955345','Self Help',2018,5,5,'available','No matter your goals, Atomic Habits offers a proven framework for improving every day. James Clear, one of the world\'s leading experts on habit formation, reveals practical strategies that will teach you exactly how to form good habits, break bad ones, and master the tiny behaviors that lead to remarkable results.','books/DzSRYUUBaKkJsQ53n0BRYIvsjVTWlr2Osc2c6hBn.jpg','2026-09-11 00:05:15','2026-09-11 14:40:55'),('01a08dc8-6548-727a-aedd-921d9e071abd','Feel-Good Productivity','Ali Abdaal','9786792204802','Productivity',2023,5,5,'available','Choose to Enjoy Your Life\r\nThe Feel-Good Method for a Carefree, Happy Life\r\n\r\nDo you find yourself drowning in work, while procrastination is still your constant companion? Do you feel exhausted and unable to focus? Would you like to reduce your stress levels, have more time for friends and family, and still be successful? Then Ali Abdaal’s Feel-Good Method will change your life. The productivity expert demonstrates impressively and with scientific evidence that success comes above all when we feel good—and the best part is: Suddenly, we have more time and energy for the things that truly fulfill us, instead of rushing toward burnout.\r\n\r\nFeel-Good Productivity is a simple method, but it changes everything. It shows us that when we are in over our heads, we do not have to limit ourselves to merely keeping our heads above water. We can learn to swim.\r\n\r\n54 Practical Strategies for a Fulfilling, Happy Life\r\n\r\nAli Abdaal Reveals the Secret of Productivity\r\n\r\n“Ali is a master of productivity without sacrificing his happiness in life. This is the book we have all been waiting for.”\r\n\r\nDr. Julia Smith, bestselling author of “Get Up or Stay in Bed”\r\n\r\n“Ali’s approach to productivity is extraordinary and life-changing. A must-read if you want to experience the power of productivity in a completely new way.”\r\n\r\nProfessor Will Macaskill, University of Oxford','books/MFcob6JM61dGU7lVXOufUuMb8CCqxMZsWSvwrZ6V.jpg','2026-09-11 00:05:15','2026-09-11 14:11:02'),('01a08dc8-6555-73a9-afb0-0cc9fdbe3bf6','The C Programming Language','Brian W. Kernighan and Dennis MacAlistair Ritchie','9787872640008','Programming',1978,5,5,'available','Very well known, classic introduction to the C Programming Language. Both a text for learning, a reference, and, to some, the definition of proper C language features and use.','books/vnIPvXAKMBNTncFokEY2AxL0p9qUILvaqxzxBYmO.jpg','2026-09-11 00:05:15','2026-09-11 14:09:07'),('01a08f4f-fb04-73ef-a6fc-8753384f0af5','Do It Today','Darius Foroux','9790622349892','Productivity',2020,5,5,'available','Do it Today is a practical guide to breaking the cycle of procrastination and building a life of consistent, meaningful action. Drawing on psychology, personal experience, and proven productivity strategies, Darius Foroux explains why we delay important work and how small, intentional habits can transform the way we use our time. Through clear lessons and actionable exercises, the book helps readers focus on what truly matters, eliminate distractions, strengthen discipline, and create momentum toward long-term goals. Straightforward and motivating, it\'s a handbook for anyone who wants to stop waiting for \"someday\" and start making progress today.','books/JhnUxFIGVJc0I1SnTvDfum4dzFEEnD45DXirarj1.jpg','2026-09-11 07:12:58','2026-09-11 14:05:03'),('01a08f4f-fb32-7035-a354-fe8e573ad105','Object-oriented Programming with C++','E. Balagurusamy','9799848764830','Programming',2001,5,5,'available','This book provides a comprehensive introduction to object-oriented programming using C++. It covers fundamental programming concepts such as classes, objects, inheritance, polymorphism, encapsulation, and other OOP principles. It is designed to help students and beginners understand how to develop structured and reusable programs using C++.','books/rVllgrHE1jb3az774W0WaAbLsYOJYgnCKF2vY3gI.jpg','2026-09-11 07:12:58','2026-09-11 10:00:04'),('01a08f4f-fb36-72cc-bdcb-df3dd839ef6c','Microprocessors and interfacing','Douglas V. Hall and Andrew L. Rood','9787897696387','programming & hardware',1986,5,5,'available','This text focuses on the Intel 8086 family that are used in the IBM PC\'s and teaches students the programming, system connections, and interfacing of microprocessors and their peripheral devices in detail. Students begin with a brief introduction to computer hardware which leads to an in-depth look at how microprocessor-based computers are programmed to do real tasks. They also cover assembly language programming of 8086-based systems. Throughout the text, the emphasis is on writing assembly language programs in a top-down, structured manner. Included are comparisons between CISC and RISC microcomputer architectures and their trade-offs.','books/Vjcl8a8floyCtWvdm1zR57jQ0Yk5RmjwTdGeC1MV.jpg','2026-09-11 07:12:58','2026-09-11 14:34:13'),('01a08f4f-fb3b-72d4-866d-f90813b5e2a0','Your Limitation It\'s Only Your Imagination','self self help','9789076718422','Self Help',2020,5,5,'available','A motivational book that encourages readers to believe in their potential, overcome limitations, and use their imagination to achieve personal growth and success.','books/0qcTYjpfbpHil8VZVbdHPDne1Qp2wxUUDUP0v2wP.jpg','2026-09-11 07:12:58','2026-09-11 09:48:13'),('01a08f4f-fb40-738a-9448-a731e3d1146b','13 Steps to Mentalism','Tony Corinda','9786256526655','Self Help',1968,5,5,'available','A practical book that introduces techniques and ideas related to mentalism, magic tricks, memory, and psychological performance. It is useful for readers interested in learning entertaining techniques and developing their skills.','books/fbC89nVOnOwzMptyhNf5GKFsPNeJJk6d7BVb0f3U.jpg','2026-09-11 07:12:58','2026-09-11 09:50:09'),('01a08f4f-fb44-73fe-bf33-f8d0f52c7dfa','Three Act Tragedy','Agatha Christie','9794178859812','Tragedy',2005,5,5,'available','Sir Charles Cartwright should have known better than to allow thirteen guests to sit down for dinner. For at the end of the evening one of them is dead—choked by a cocktail that contained no trace of poison.\r\n\r\nPredictable, says Hercule Poirot, the great detective. But entirely unpredictable is that he can find absolutely no motive for murder.…','books/9Bg1YyoWcGOxPQ1reGfsNQ6JkypKgQFdPjcDHkkj.jpg','2026-09-11 07:12:58','2026-09-11 13:55:57'),('01a08f4f-fb49-71d5-9bc3-b475aa799a82','Shakespeare Hamlet','William Shakespeare','9792903947766','Tragedy',1857,5,5,'available','Debitis sint expedita animi sapiente. Quo rerum autem nostrum et illum cumque rem.\n\nSapiente dignissimos alias omnis doloribus molestiae saepe cupiditate. Ut aperiam molestias soluta exercitationem consequatur accusantium id culpa. Aliquam enim consectetur distinctio omnis esse ipsum dolor.','books/KSsK0Y7BQljvXZLhvZsoCuT1bPH9KH3HoSQ1zlGb.jpg','2026-09-11 07:12:58','2026-09-11 07:57:26'),('01a08f4f-fb56-7199-a29c-5764dedf8f37','Modern Full-Stack Development','Frank Zammetti','9789052668550','Programming',2023,5,5,'available','A practical guide to modern full-stack web development that covers technologies such as TypeScript, React, Node.js, Webpack, Python, Django, and Docker. It provides readers with concepts and techniques for building modern web applications using different frontend, backend, and development tools.','books/tQjIDH7HNeQF88Avr8WkzsDya1DnwSyeyNBKe5Jr.jpg','2026-09-11 07:12:58','2026-09-11 14:00:28'),('01a08f4f-fb60-70d8-a3b0-2ec328038dba','Chemistry Central Science Annotated','y Theodore L. Brown, H. Eugene Lemay','9799607739512','Science',1994,5,5,'available','The book provides the basis of modern chemistry that every student needs for their professional development and as preparation for more complex chemistry courses. It also offers features that facilitate learning and serve as a guide for students to acquire a conceptual understanding and the skills needed to solve problems. The first five chapters offer a microscopic and phenomenological view of chemistry, while the latter review the chemistry of nonmetals, metals, organic chemistry and biochemistry.','books/bWFhXGvUeh8v5TKhoaT3bIIo8rvTgRZKfynZ8mjO.jpg','2026-09-11 07:12:58','2026-09-11 14:03:36'),('01a08f4f-fb65-71d1-ab59-e2033883937c','Programming Python','Mark Lutz','9790846777693','Programming',2001,5,5,'available','Accompanying CD-ROM has examples from the book, Python 2.0 interpreter and standard documentation manuals, Python-related software packages, and the full Python 2.0 source code for PC, Macintosh, and Unix platforms.','books/6Wl6tlWqT4mAf0LfAqg8Uv7G6bPHxWTvrMiUxCbt.jpg','2026-09-11 07:12:58','2026-09-11 13:57:44');
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `borrowings`
--

DROP TABLE IF EXISTS `borrowings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `borrowings` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `borrower_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `borrower_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `borrowed_at` timestamp NOT NULL,
  `returned_at` timestamp NULL DEFAULT NULL,
  `status` enum('borrowed','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrowed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrowings_book_id_foreign` (`book_id`),
  CONSTRAINT `borrowings_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borrowings`
--

LOCK TABLES `borrowings` WRITE;
/*!40000 ALTER TABLE `borrowings` DISABLE KEYS */;
INSERT INTO `borrowings` VALUES ('01a08fcf-f864-7285-b64c-fd740bd652a9','01a08f4f-fb32-7035-a354-fe8e573ad105','Joniver','Sumalinog@gmail.com','2026-09-11 09:32:46','2026-09-11 10:00:04','returned','2026-09-11 09:32:46','2026-09-11 10:00:04');
/*!40000 ALTER TABLE `borrowings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_09_10_000001_create_books_table',1),(5,'2026_09_10_000002_create_borrowings_table',1),(6,'2026_09_10_064406_create_personal_access_tokens_table',1),(7,'2026_09_11_000001_add_description_to_books_table',1),(8,'2026_09_11_000002_add_book_image_to_books_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',1,'admin-token','0ebd1f08a76789272c702230dd4b6645f3c59b937ff1a58e48e4488748118320','[\"*\"]',NULL,NULL,'2026-09-11 00:15:46','2026-09-11 00:15:46'),(2,'App\\Models\\User',1,'admin-token','30576805b02ce7ffce3d82cd20176577e981a7e63ac7f35768cdf30b30581f64','[\"*\"]',NULL,NULL,'2026-09-11 00:20:11','2026-09-11 00:20:11'),(3,'App\\Models\\User',1,'admin-token','a97dd44f783f0b4c850a663af3009a76331ed47909666cc329b4f037909f3e8d','[\"*\"]',NULL,NULL,'2026-09-11 00:20:39','2026-09-11 00:20:39'),(4,'App\\Models\\User',1,'admin-token','7e7b649f0e2ee3b6b5fe0557e72473a94da1fd1571faad568af370ae1d5cbd68','[\"*\"]',NULL,NULL,'2026-09-11 00:21:08','2026-09-11 00:21:08'),(5,'App\\Models\\User',1,'admin-token','627bfc857682c0ce29ada109bd38d09b06c49eaa02dfe6bde175f9768604be8c','[\"*\"]',NULL,NULL,'2026-09-11 00:21:49','2026-09-11 00:21:49'),(6,'App\\Models\\User',1,'admin-token','be1a30d8d0c804a04fb87a82ebcbd9e0c5f5b3fecb1da0e4517ef6b38c634617','[\"*\"]',NULL,NULL,'2026-09-11 00:22:26','2026-09-11 00:22:26'),(7,'App\\Models\\User',1,'admin-token','a606d3913ebcd7fe0f5da5701339c8b815c4dfc8a124e3bb6c8ea78ee8943f9c','[\"*\"]',NULL,NULL,'2026-09-11 00:22:47','2026-09-11 00:22:47'),(8,'App\\Models\\User',1,'admin-token','22c9af48fe93bd1399e04a771f4e1ae4476cc7affb379c1a590f496676ed91e0','[\"*\"]',NULL,NULL,'2026-09-11 00:23:19','2026-09-11 00:23:19'),(9,'App\\Models\\User',1,'admin-token','49125fb93bfac8122f8b273feac5482b7a2fd9bcc01b73a8e1342f3a59c1e792','[\"*\"]',NULL,NULL,'2026-09-11 00:23:29','2026-09-11 00:23:29'),(10,'App\\Models\\User',1,'admin-token','3260d50be4b3635f8322f9d9d8954d4d6dca50379a87a1d2b0fb4ef589b5ab84','[\"*\"]',NULL,NULL,'2026-09-11 07:09:57','2026-09-11 07:09:57'),(11,'App\\Models\\User',2,'admin-token','ce28083792c5ad51f3dd852de01ba8bfba5208d4fa9f8b01a0e8af784abe0143','[\"*\"]',NULL,NULL,'2026-09-11 07:13:17','2026-09-11 07:13:17'),(12,'App\\Models\\User',1,'admin-token','eea8531f868808a25b90d99bfd4d99ac9d16daf9c067149a97641e815bdf753b','[\"*\"]',NULL,NULL,'2026-09-11 07:55:09','2026-09-11 07:55:09'),(13,'App\\Models\\User',1,'admin-token','fe9cbea6e1c58775a79a27896aa5094112b3f22d91faa961fabdea87c6e7f2c2','[\"*\"]',NULL,NULL,'2026-09-11 07:59:17','2026-09-11 07:59:17'),(14,'App\\Models\\User',1,'admin-token','4c9ec288deb6c772fa6a8c072e490ff8072203de0610801472d9d0dc8a9962a4','[\"*\"]',NULL,NULL,'2026-09-11 08:05:36','2026-09-11 08:05:36'),(15,'App\\Models\\User',1,'admin-token','040852eaa6301d6d1d8288604d4a2cc72d8ab32bc386b75dc2da926c17be8441','[\"*\"]',NULL,NULL,'2026-09-11 08:50:13','2026-09-11 08:50:13'),(16,'App\\Models\\User',1,'admin-token','2e2a3c6958cebe331f8c2c9c55bc23fa0aeeb9fcf741510dace71e8fc07ab8d0','[\"*\"]',NULL,NULL,'2026-09-11 08:55:05','2026-09-11 08:55:05'),(17,'App\\Models\\User',1,'admin-token','73f6380dc3cb3fed19400bd79fb03f4412bed6c6e4345d31665c5c4985385524','[\"*\"]',NULL,NULL,'2026-09-11 09:08:26','2026-09-11 09:08:26'),(18,'App\\Models\\User',2,'admin-token','c344eb0bfccdf490829a82a525c1879c85e81d2460ba3d0f6282085234eadb11','[\"*\"]',NULL,NULL,'2026-09-11 09:14:13','2026-09-11 09:14:13'),(19,'App\\Models\\User',2,'admin-token','aeffbfd140864afbe9c0d6b48348058ef39f1163d7ac77b6feb8adab175ddcbb','[\"*\"]',NULL,NULL,'2026-09-11 09:14:48','2026-09-11 09:14:48'),(20,'App\\Models\\User',2,'admin-token','5e1bd01c5b5bf7c9c249a68e6135da8e2b59802116990a25432be3ced5456782','[\"*\"]',NULL,NULL,'2026-09-11 09:31:21','2026-09-11 09:31:21'),(21,'App\\Models\\User',2,'admin-token','2f40e3a40aaefe86bc73b8663f598a37daa554c78d42b0bcd583ff2f37a758de','[\"*\"]',NULL,NULL,'2026-09-11 09:33:05','2026-09-11 09:33:05'),(22,'App\\Models\\User',1,'admin-token','1c54810ce41f4d8dc458b54b36e9f71d45fc228e3aaa28d8d3f27d4f1eafefc6','[\"*\"]',NULL,NULL,'2026-09-11 09:33:37','2026-09-11 09:33:37'),(23,'App\\Models\\User',1,'admin-token','090df114d8b4989fbd4cd626021903b7e41b046a27b3303f49b962910aea52c3','[\"*\"]',NULL,NULL,'2026-09-11 09:36:16','2026-09-11 09:36:16'),(24,'App\\Models\\User',1,'admin-token','25e2a2e2f3b2f59ded87e2174f323e1e5d3489c5c8e0813cc721ce2e37ada179','[\"*\"]',NULL,NULL,'2026-09-11 09:44:26','2026-09-11 09:44:26'),(25,'App\\Models\\User',1,'admin-token','635d611e113c8439a392e2f09fa3830baeb840fe32762e1042bd2533c14433eb','[\"*\"]',NULL,NULL,'2026-09-11 10:02:10','2026-09-11 10:02:10');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Library Admin','admin@library.com',NULL,'$2y$12$K9VsXkQI.q1RBgLugavoSefys2.k9K6ARM5nY7afNeZQX2VF9mR.u',NULL,'2026-09-11 00:05:13','2026-09-11 07:12:56'),(2,'Student Borrower','student@example.com',NULL,'$2y$12$JrGsUh8N8iEPZ2hUC3/i3.QJCdfwZJZVOc/NubuCx3nRDpgsamYLm',NULL,'2026-09-11 07:12:56','2026-09-11 07:12:56');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-11 14:44:49
