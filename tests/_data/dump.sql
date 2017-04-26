-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Wersja serwera:               10.1.22-MariaDB-1~jessie - mariadb.org binary distribution
-- Serwer OS:                    debian-linux-gnu
-- HeidiSQL Wersja:              9.4.0.5144
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Zrzut struktury tabela strimoid.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_source` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `uv` int(10) unsigned NOT NULL DEFAULT '0',
  `dv` int(10) unsigned NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_group_id_foreign` (`group_id`),
  KEY `comments_content_id_foreign` (`content_id`),
  CONSTRAINT `comments_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`),
  CONSTRAINT `comments_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.comments: ~2 rows (około)
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT IGNORE INTO `comments` (`id`, `text`, `text_source`, `user_id`, `group_id`, `content_id`, `uv`, `dv`, `score`, `created_at`, `updated_at`) VALUES
	(1, '<p>Quae quasi quia non vero quia. Voluptatibus sequi aut quam et saepe voluptates. Dicta a est vel dolores. Incidunt mollitia expedita et eum asperiores consequuntur cupiditate ab.<br>\nVoluptas quaerat aut et esse quas magni non. Aliquam aliquid ut repellendus distinctio animi exercitationem. Deleniti in dolor inventore architecto sed sint enim. Est eligendi autem explicabo occaecati.</p>', 'Quae quasi quia non vero quia. Voluptatibus sequi aut quam et saepe voluptates. Dicta a est vel dolores. Incidunt mollitia expedita et eum asperiores consequuntur cupiditate ab.\nVoluptas quaerat aut et esse quas magni non. Aliquam aliquid ut repellendus distinctio animi exercitationem. Deleniti in dolor inventore architecto sed sint enim. Est eligendi autem explicabo occaecati.', 1, 1, 1, 0, 0, 0, '2016-11-01 08:30:06', '2017-04-26 22:01:09'),
	(2, '<p>Explicabo earum aperiam dolorum. Voluptatem placeat aut temporibus accusantium sequi unde voluptate. Qui incidunt nesciunt nisi hic qui consequuntur repellat.<br>\nEnim est sapiente accusantium minima fuga qui. Sed et deleniti consequatur quis ut.<br>\nNisi ut eligendi aut veritatis dolorem. Aut et magni dolor eaque. Fugiat nam qui odit velit natus eos. Cumque impedit dolore voluptatem tenetur error saepe. Dolor voluptatem cumque est aut similique.</p>', 'Explicabo earum aperiam dolorum. Voluptatem placeat aut temporibus accusantium sequi unde voluptate. Qui incidunt nesciunt nisi hic qui consequuntur repellat.\nEnim est sapiente accusantium minima fuga qui. Sed et deleniti consequatur quis ut.\nNisi ut eligendi aut veritatis dolorem. Aut et magni dolor eaque. Fugiat nam qui odit velit natus eos. Cumque impedit dolore voluptatem tenetur error saepe. Dolor voluptatem cumque est aut similique.', 1, 1, 1, 0, 0, 0, '2007-06-05 23:08:39', '2017-04-26 22:01:09'),
	(3, '<p>Ut nesciunt vero vero et fugit. Aperiam deleniti est sunt officia vel vel. Consequatur mollitia sed aut non.<br>\nEt occaecati consequatur error quisquam et. Ut nam dolores expedita. Animi incidunt quidem earum deserunt eius non et.<br>\nSint sapiente sint accusamus tenetur sed officiis possimus. Aspernatur rerum eaque cum sit ut officiis possimus ut. Aliquam illum ut quis aut. Fuga corrupti asperiores nam id nemo.</p>', 'Ut nesciunt vero vero et fugit. Aperiam deleniti est sunt officia vel vel. Consequatur mollitia sed aut non.\nEt occaecati consequatur error quisquam et. Ut nam dolores expedita. Animi incidunt quidem earum deserunt eius non et.\nSint sapiente sint accusamus tenetur sed officiis possimus. Aspernatur rerum eaque cum sit ut officiis possimus ut. Aliquam illum ut quis aut. Fuga corrupti asperiores nam id nemo.', 2, 3, 2, 0, 0, 0, '2016-04-22 14:50:05', '2017-04-26 22:01:11'),
	(4, '<p>Dolores similique quod iusto est vel. Blanditiis nesciunt aut optio tempora rem rerum ex. Autem qui et porro dolor eligendi perspiciatis.<br>\nAdipisci aspernatur deserunt dolore a iusto nam itaque. Natus amet provident molestiae laborum. Voluptas consequuntur necessitatibus perferendis et reprehenderit consequatur.<br>\nModi qui est fugiat. Voluptates illum corporis quis ut necessitatibus recusandae. Natus asperiores iure voluptas vitae explicabo. Ut quam laudantium nesciunt labore amet at sint.</p>', 'Dolores similique quod iusto est vel. Blanditiis nesciunt aut optio tempora rem rerum ex. Autem qui et porro dolor eligendi perspiciatis.\nAdipisci aspernatur deserunt dolore a iusto nam itaque. Natus amet provident molestiae laborum. Voluptas consequuntur necessitatibus perferendis et reprehenderit consequatur.\nModi qui est fugiat. Voluptates illum corporis quis ut necessitatibus recusandae. Natus asperiores iure voluptas vitae explicabo. Ut quam laudantium nesciunt labore amet at sint.', 2, 3, 2, 0, 0, 0, '2010-12-22 19:17:23', '2017-04-26 22:01:12'),
	(5, '<p>Fugiat aut quo consequatur et saepe odio ratione. Eum veniam mollitia voluptas molestiae.<br>\nQuisquam saepe nihil dolorum quo ut. Quam et facere atque doloremque tenetur veniam nemo ratione. Qui doloremque sint assumenda ullam officia. Autem consequatur molestiae minus veniam doloremque qui.<br>\nCorrupti et cum est qui facilis perspiciatis consequuntur odit. Voluptas a quod ipsa voluptate modi aut corrupti. Deleniti quas sint itaque architecto vel maxime est. Ut velit accusantium dolorem odit quidem.</p>', 'Fugiat aut quo consequatur et saepe odio ratione. Eum veniam mollitia voluptas molestiae.\nQuisquam saepe nihil dolorum quo ut. Quam et facere atque doloremque tenetur veniam nemo ratione. Qui doloremque sint assumenda ullam officia. Autem consequatur molestiae minus veniam doloremque qui.\nCorrupti et cum est qui facilis perspiciatis consequuntur odit. Voluptas a quod ipsa voluptate modi aut corrupti. Deleniti quas sint itaque architecto vel maxime est. Ut velit accusantium dolorem odit quidem.', 2, 3, 3, 0, 0, 0, '2011-08-28 14:17:12', '2017-04-26 22:01:13'),
	(6, '<p>Qui odit consequatur repellat quia eum culpa. Quod quia nobis reprehenderit ad. Non occaecati enim commodi nulla aut aut tenetur. Sapiente excepturi ea laboriosam.<br>\nIusto cumque magni dolores nihil quia quis. Earum neque voluptate blanditiis laudantium velit adipisci culpa. Voluptas quisquam eos sapiente laudantium delectus tenetur consequatur voluptas. Et neque nam et eveniet.</p>', 'Qui odit consequatur repellat quia eum culpa. Quod quia nobis reprehenderit ad. Non occaecati enim commodi nulla aut aut tenetur. Sapiente excepturi ea laboriosam.\nIusto cumque magni dolores nihil quia quis. Earum neque voluptate blanditiis laudantium velit adipisci culpa. Voluptas quisquam eos sapiente laudantium delectus tenetur consequatur voluptas. Et neque nam et eveniet.', 2, 3, 3, 0, 0, 0, '2007-07-05 17:18:53', '2017-04-26 22:01:14'),
	(7, '<p>Enim aut velit quaerat modi qui. Aliquid quia quia reiciendis unde. Et perspiciatis deleniti aspernatur quis blanditiis quae consequuntur.<br>\nA impedit alias qui sed nam rerum corrupti aut. Libero esse quam aut ducimus cum alias. Vitae tempore maxime qui nam repellendus magnam. Sequi iure voluptatem rerum et voluptatem alias neque.<br>\nDolorem dolore minima modi sequi. Sed consequuntur sunt doloremque eos consequuntur alias voluptas. Fuga fuga explicabo atque.</p>', 'Enim aut velit quaerat modi qui. Aliquid quia quia reiciendis unde. Et perspiciatis deleniti aspernatur quis blanditiis quae consequuntur.\nA impedit alias qui sed nam rerum corrupti aut. Libero esse quam aut ducimus cum alias. Vitae tempore maxime qui nam repellendus magnam. Sequi iure voluptatem rerum et voluptatem alias neque.\nDolorem dolore minima modi sequi. Sed consequuntur sunt doloremque eos consequuntur alias voluptas. Fuga fuga explicabo atque.', 1, 3, 4, 0, 0, 0, '2012-08-02 23:55:39', '2017-04-26 22:01:15'),
	(8, '<p>Aut et repudiandae est omnis voluptatem. Corporis consequatur officia eius quae doloribus voluptatem.<br>\nIusto iste voluptatem deserunt blanditiis. Enim debitis distinctio voluptatem et quia architecto in. Tenetur praesentium ratione aliquid vitae quisquam adipisci quae consectetur. Aut ea totam culpa assumenda nam veritatis explicabo. Voluptatem deleniti enim pariatur odio voluptatibus sunt.</p>', 'Aut et repudiandae est omnis voluptatem. Corporis consequatur officia eius quae doloribus voluptatem.\nIusto iste voluptatem deserunt blanditiis. Enim debitis distinctio voluptatem et quia architecto in. Tenetur praesentium ratione aliquid vitae quisquam adipisci quae consectetur. Aut ea totam culpa assumenda nam veritatis explicabo. Voluptatem deleniti enim pariatur odio voluptatibus sunt.', 1, 3, 4, 0, 0, 0, '2013-04-21 03:54:48', '2017-04-26 22:01:15'),
	(9, '<p>Ipsa et totam ut dolores labore est sed tempora. Placeat molestias et molestiae aut labore enim. A eos qui aliquid hic blanditiis et placeat.<br>\nRerum perspiciatis eius et. Iste et sint sed asperiores vel repudiandae libero. Dicta voluptate ut ut dignissimos atque quisquam maxime.<br>\nEt impedit nihil omnis. Sed natus sit voluptas laudantium explicabo culpa. Eos suscipit ipsam est ut consequatur et fugit. Iste optio beatae quia qui eaque corporis voluptatum beatae.</p>', 'Ipsa et totam ut dolores labore est sed tempora. Placeat molestias et molestiae aut labore enim. A eos qui aliquid hic blanditiis et placeat.\nRerum perspiciatis eius et. Iste et sint sed asperiores vel repudiandae libero. Dicta voluptate ut ut dignissimos atque quisquam maxime.\nEt impedit nihil omnis. Sed natus sit voluptas laudantium explicabo culpa. Eos suscipit ipsam est ut consequatur et fugit. Iste optio beatae quia qui eaque corporis voluptatum beatae.', 2, 3, 5, 0, 0, 0, '2012-08-12 05:48:35', '2017-04-26 22:01:16'),
	(10, '<p>Facilis aut cumque aliquid accusamus. Tempora quasi rerum itaque odio ut. Sint voluptatem ut est perferendis libero quaerat. Quas qui quia rerum illo atque.<br>\nHic in similique culpa quo vel est omnis. Maiores sunt non voluptatem animi doloribus mollitia et. Explicabo eos non aspernatur exercitationem.</p>', 'Facilis aut cumque aliquid accusamus. Tempora quasi rerum itaque odio ut. Sint voluptatem ut est perferendis libero quaerat. Quas qui quia rerum illo atque.\nHic in similique culpa quo vel est omnis. Maiores sunt non voluptatem animi doloribus mollitia et. Explicabo eos non aspernatur exercitationem.', 2, 3, 5, 0, 0, 0, '2016-11-07 18:33:48', '2017-04-26 22:01:16'),
	(11, '<p>Reiciendis expedita totam et omnis ut qui ab. Omnis in aut eveniet qui. Saepe tempora voluptatem asperiores dolores. Reprehenderit at a alias excepturi rem voluptas.<br>\nVoluptatibus veritatis dolores quod. Voluptas ratione sit at expedita labore. Animi illum aut excepturi officia maxime. Ut nihil aut ea laborum veritatis eligendi quo.<br>\nDeserunt aut reprehenderit rerum temporibus. Quia delectus aut et repellat repellat. Libero delectus nostrum voluptatem et delectus quos et assumenda.</p>', 'Reiciendis expedita totam et omnis ut qui ab. Omnis in aut eveniet qui. Saepe tempora voluptatem asperiores dolores. Reprehenderit at a alias excepturi rem voluptas.\nVoluptatibus veritatis dolores quod. Voluptas ratione sit at expedita labore. Animi illum aut excepturi officia maxime. Ut nihil aut ea laborum veritatis eligendi quo.\nDeserunt aut reprehenderit rerum temporibus. Quia delectus aut et repellat repellat. Libero delectus nostrum voluptatem et delectus quos et assumenda.', 1, 5, 8, 0, 0, 0, '2009-02-13 06:18:21', '2017-04-26 22:01:19'),
	(12, '<p>Sit reprehenderit autem eum officiis est. Et modi repudiandae soluta. Qui mollitia sed qui rerum ipsum quo.<br>\nOfficia amet dolores soluta. Velit porro modi voluptas in magnam. Qui dolor id illo aut.<br>\nVoluptatibus perferendis hic hic nostrum voluptate ipsa est culpa. Ducimus rerum est nulla autem amet. Possimus quisquam id possimus iure eius illum. Et numquam ab temporibus qui asperiores quaerat.</p>', 'Sit reprehenderit autem eum officiis est. Et modi repudiandae soluta. Qui mollitia sed qui rerum ipsum quo.\nOfficia amet dolores soluta. Velit porro modi voluptas in magnam. Qui dolor id illo aut.\nVoluptatibus perferendis hic hic nostrum voluptate ipsa est culpa. Ducimus rerum est nulla autem amet. Possimus quisquam id possimus iure eius illum. Et numquam ab temporibus qui asperiores quaerat.', 4, 5, 8, 0, 0, 0, '2015-03-09 01:16:33', '2017-04-26 22:01:20'),
	(13, '<p>Tempora vitae inventore dolor nemo et. Quisquam culpa voluptas sed labore.<br>\nLabore non ea aut est voluptate pariatur repellendus. Sint voluptatum non veniam voluptates qui id. Ipsam quaerat id consequatur molestiae enim. Sapiente labore et ullam neque enim consequatur.<br>\nCulpa eum et itaque omnis eos eum. Voluptatibus nisi dolores iusto aliquam minima sed porro. Doloremque ipsa culpa quas dignissimos. Libero eligendi eum et cum.</p>', 'Tempora vitae inventore dolor nemo et. Quisquam culpa voluptas sed labore.\nLabore non ea aut est voluptate pariatur repellendus. Sint voluptatum non veniam voluptates qui id. Ipsam quaerat id consequatur molestiae enim. Sapiente labore et ullam neque enim consequatur.\nCulpa eum et itaque omnis eos eum. Voluptatibus nisi dolores iusto aliquam minima sed porro. Doloremque ipsa culpa quas dignissimos. Libero eligendi eum et cum.', 2, 6, 9, 0, 0, 0, '2014-11-19 00:55:28', '2017-04-26 22:01:21'),
	(14, '<p>Sit qui placeat est voluptate molestiae doloremque. Aliquid harum ea et at. Ullam molestiae autem vel consequatur accusamus qui aut provident.<br>\nDoloremque ullam atque nostrum cum. Consequuntur accusamus et reprehenderit dicta a excepturi quas. Voluptatibus id repellat eum quo magni nihil. Et deleniti quod est dolores rem quasi.</p>', 'Sit qui placeat est voluptate molestiae doloremque. Aliquid harum ea et at. Ullam molestiae autem vel consequatur accusamus qui aut provident.\nDoloremque ullam atque nostrum cum. Consequuntur accusamus et reprehenderit dicta a excepturi quas. Voluptatibus id repellat eum quo magni nihil. Et deleniti quod est dolores rem quasi.', 4, 6, 9, 0, 0, 0, '2014-10-27 15:09:30', '2017-04-26 22:01:21'),
	(15, '<p>Molestiae praesentium quos sunt recusandae et voluptatem magni. Veritatis et ratione cum iure distinctio debitis dolorem. Itaque et quae alias alias consequatur.<br>\nSunt eum ipsam voluptatibus deleniti consequatur. Quia consectetur blanditiis doloremque maxime ut. Quas amet perferendis est vel.<br>\nQuis laboriosam porro architecto. Cum veniam veniam iusto velit odit. Error praesentium consequuntur dolorem officia assumenda ipsam.</p>', 'Molestiae praesentium quos sunt recusandae et voluptatem magni. Veritatis et ratione cum iure distinctio debitis dolorem. Itaque et quae alias alias consequatur.\nSunt eum ipsam voluptatibus deleniti consequatur. Quia consectetur blanditiis doloremque maxime ut. Quas amet perferendis est vel.\nQuis laboriosam porro architecto. Cum veniam veniam iusto velit odit. Error praesentium consequuntur dolorem officia assumenda ipsam.', 3, 6, 9, 0, 0, 0, '2012-02-24 11:05:56', '2017-04-26 22:01:21'),
	(16, '<p>Vero quibusdam qui placeat eos consequatur aliquid. Nesciunt cupiditate excepturi velit fugiat qui voluptates explicabo rerum. Incidunt et quisquam error quis unde exercitationem laborum. Nihil minus quis consectetur magni.<br>\nIllo laudantium aliquam a libero fugiat vel in. Aut quia dolor nobis incidunt debitis et voluptatibus quis. Amet recusandae earum quae perferendis. Sit qui odit odio quo ea ducimus.</p>', 'Vero quibusdam qui placeat eos consequatur aliquid. Nesciunt cupiditate excepturi velit fugiat qui voluptates explicabo rerum. Incidunt et quisquam error quis unde exercitationem laborum. Nihil minus quis consectetur magni.\nIllo laudantium aliquam a libero fugiat vel in. Aut quia dolor nobis incidunt debitis et voluptatibus quis. Amet recusandae earum quae perferendis. Sit qui odit odio quo ea ducimus.', 4, 6, 10, 0, 0, 0, '2010-03-08 23:11:44', '2017-04-26 22:01:22'),
	(17, '<p>Aut id fugiat neque suscipit enim. Est blanditiis ducimus sed maxime nemo consequatur. Vero laudantium sunt voluptas consequatur laudantium ut accusantium.<br>\nVero culpa nisi maxime sit ipsa dolorum. Nulla velit enim culpa similique facilis rerum. Fugit reiciendis quasi exercitationem ea. Error delectus architecto molestias et.<br>\nQuam et magni sapiente animi maxime. Omnis amet eos nobis autem. Qui quo et eum doloremque in illo in quidem. Delectus enim et ipsa recusandae ea. Sint quaerat laborum est accusantium.</p>', 'Aut id fugiat neque suscipit enim. Est blanditiis ducimus sed maxime nemo consequatur. Vero laudantium sunt voluptas consequatur laudantium ut accusantium.\nVero culpa nisi maxime sit ipsa dolorum. Nulla velit enim culpa similique facilis rerum. Fugit reiciendis quasi exercitationem ea. Error delectus architecto molestias et.\nQuam et magni sapiente animi maxime. Omnis amet eos nobis autem. Qui quo et eum doloremque in illo in quidem. Delectus enim et ipsa recusandae ea. Sint quaerat laborum est accusantium.', 2, 6, 12, 0, 0, 0, '2011-01-06 06:53:07', '2017-04-26 22:01:24'),
	(18, '<p>Dicta ut qui itaque aliquid suscipit. Earum perspiciatis sit maxime. Ducimus minus est perspiciatis maiores ullam aperiam necessitatibus. Nulla consequatur qui deleniti vel cumque amet.<br>\nCorrupti repellendus iusto quaerat quae rem dolorem minus at. Voluptate rem maxime ullam esse praesentium qui aspernatur error.<br>\nTenetur voluptate et vero tenetur reprehenderit deleniti aliquam nam. Eius laboriosam distinctio quod. Magni non molestiae totam dolores quasi voluptas.</p>', 'Dicta ut qui itaque aliquid suscipit. Earum perspiciatis sit maxime. Ducimus minus est perspiciatis maiores ullam aperiam necessitatibus. Nulla consequatur qui deleniti vel cumque amet.\nCorrupti repellendus iusto quaerat quae rem dolorem minus at. Voluptate rem maxime ullam esse praesentium qui aspernatur error.\nTenetur voluptate et vero tenetur reprehenderit deleniti aliquam nam. Eius laboriosam distinctio quod. Magni non molestiae totam dolores quasi voluptas.', 3, 6, 12, 0, 0, 0, '2007-07-09 01:05:15', '2017-04-26 22:01:24'),
	(19, '<p>Veniam sint dolores similique possimus aut. Magni praesentium tempore doloremque qui fugiat nobis possimus quisquam. Accusantium dolorum vel et. Fugiat repudiandae praesentium eligendi sed.<br>\nFugit qui facilis corrupti delectus dolorum. Incidunt adipisci sint nesciunt quo. Pariatur id velit et incidunt.<br>\nPorro aut consectetur molestiae hic. Fugit modi dolorem temporibus earum aperiam. Ab omnis consequatur est est. Cupiditate cum facilis officia quia.</p>', 'Veniam sint dolores similique possimus aut. Magni praesentium tempore doloremque qui fugiat nobis possimus quisquam. Accusantium dolorum vel et. Fugiat repudiandae praesentium eligendi sed.\nFugit qui facilis corrupti delectus dolorum. Incidunt adipisci sint nesciunt quo. Pariatur id velit et incidunt.\nPorro aut consectetur molestiae hic. Fugit modi dolorem temporibus earum aperiam. Ab omnis consequatur est est. Cupiditate cum facilis officia quia.', 5, 7, 13, 0, 0, 0, '2013-02-23 03:22:00', '2017-04-26 22:01:26'),
	(20, '<p>Dolores quas et sed tempore laboriosam et sequi. Ipsum est molestias quia voluptatem minus repellendus in. Non explicabo sed soluta sit iusto aut qui.<br>\nEum exercitationem repellendus consequatur voluptate nemo. Vel enim libero impedit fuga libero. Ea quos nemo non molestiae soluta quam. Saepe voluptas officiis recusandae qui minima. Mollitia asperiores quisquam unde hic.<br>\nId eum ut inventore voluptate sunt. Nisi eos sunt ipsa. Quasi nihil dolorum sit earum.</p>', 'Dolores quas et sed tempore laboriosam et sequi. Ipsum est molestias quia voluptatem minus repellendus in. Non explicabo sed soluta sit iusto aut qui.\nEum exercitationem repellendus consequatur voluptate nemo. Vel enim libero impedit fuga libero. Ea quos nemo non molestiae soluta quam. Saepe voluptas officiis recusandae qui minima. Mollitia asperiores quisquam unde hic.\nId eum ut inventore voluptate sunt. Nisi eos sunt ipsa. Quasi nihil dolorum sit earum.', 1, 7, 13, 0, 0, 0, '2009-07-23 15:24:30', '2017-04-26 22:01:26'),
	(21, '<p>Excepturi dicta nihil deleniti ut assumenda voluptate. Et perspiciatis ipsam dicta est quis placeat voluptas. Sit cupiditate voluptatibus deserunt voluptatum.<br>\nA deleniti voluptates eius facere vel. Dolore qui nihil eos quidem neque quia. Incidunt recusandae consectetur voluptatibus alias. Nesciunt mollitia accusantium beatae repudiandae perspiciatis aut nisi. Qui aut neque corporis a.<br>\nEt illo rem quas. Ad vero laudantium neque ut. Officiis modi quisquam voluptates dolores sed.</p>', 'Excepturi dicta nihil deleniti ut assumenda voluptate. Et perspiciatis ipsam dicta est quis placeat voluptas. Sit cupiditate voluptatibus deserunt voluptatum.\nA deleniti voluptates eius facere vel. Dolore qui nihil eos quidem neque quia. Incidunt recusandae consectetur voluptatibus alias. Nesciunt mollitia accusantium beatae repudiandae perspiciatis aut nisi. Qui aut neque corporis a.\nEt illo rem quas. Ad vero laudantium neque ut. Officiis modi quisquam voluptates dolores sed.', 1, 7, 14, 0, 0, 0, '2010-10-25 01:51:45', '2017-04-26 22:01:28'),
	(22, '<p>Est laboriosam non at non atque occaecati. Dolorem odio rerum at omnis. Dolorum reiciendis commodi minima adipisci rem tenetur excepturi.<br>\nFuga impedit quas ea aliquam. Perspiciatis odio facilis quae beatae magni consectetur beatae. Assumenda illum quia sed libero vitae et. Vel consequatur consequatur illum ullam molestiae.<br>\nVoluptas temporibus ut ullam cum non. Porro ratione et laboriosam eum necessitatibus. Vero eos nihil quasi fugit qui incidunt.</p>', 'Est laboriosam non at non atque occaecati. Dolorem odio rerum at omnis. Dolorum reiciendis commodi minima adipisci rem tenetur excepturi.\nFuga impedit quas ea aliquam. Perspiciatis odio facilis quae beatae magni consectetur beatae. Assumenda illum quia sed libero vitae et. Vel consequatur consequatur illum ullam molestiae.\nVoluptas temporibus ut ullam cum non. Porro ratione et laboriosam eum necessitatibus. Vero eos nihil quasi fugit qui incidunt.', 4, 7, 14, 0, 0, 0, '2010-06-26 07:41:39', '2017-04-26 22:01:28'),
	(23, '<p>Reprehenderit et delectus neque est omnis. Magni quia reiciendis voluptatum recusandae neque vel dolorem est. Saepe repellat ipsam nulla velit officia distinctio quaerat natus. Ut quam et enim doloribus sequi neque. Sed nisi consequatur dolore esse.<br>\nQui et consequatur neque. Nihil occaecati voluptas asperiores sit ipsa.<br>\nAsperiores vel nostrum alias omnis qui officiis ad. Laboriosam dolores reiciendis quaerat accusamus quia voluptatem. Cupiditate perspiciatis vero quos aut.</p>', 'Reprehenderit et delectus neque est omnis. Magni quia reiciendis voluptatum recusandae neque vel dolorem est. Saepe repellat ipsam nulla velit officia distinctio quaerat natus. Ut quam et enim doloribus sequi neque. Sed nisi consequatur dolore esse.\nQui et consequatur neque. Nihil occaecati voluptas asperiores sit ipsa.\nAsperiores vel nostrum alias omnis qui officiis ad. Laboriosam dolores reiciendis quaerat accusamus quia voluptatem. Cupiditate perspiciatis vero quos aut.', 1, 7, 14, 0, 0, 0, '2007-11-11 10:13:03', '2017-04-26 22:01:28'),
	(24, '<p>Eum alias ipsam aut aperiam. Similique id veritatis est. Officia ea voluptate dolores et doloribus repellendus velit.<br>\nPossimus aperiam voluptatum veritatis velit qui. Quae ipsa numquam blanditiis doloremque.<br>\nDelectus facere tenetur quod quaerat esse consequatur aut. Dicta dolores fuga voluptatem et amet est. Eius ut dignissimos velit accusamus. Reprehenderit quam sed sunt doloremque ipsa molestiae dolor.</p>', 'Eum alias ipsam aut aperiam. Similique id veritatis est. Officia ea voluptate dolores et doloribus repellendus velit.\nPossimus aperiam voluptatum veritatis velit qui. Quae ipsa numquam blanditiis doloremque.\nDelectus facere tenetur quod quaerat esse consequatur aut. Dicta dolores fuga voluptatem et amet est. Eius ut dignissimos velit accusamus. Reprehenderit quam sed sunt doloremque ipsa molestiae dolor.', 3, 7, 15, 0, 0, 0, '2009-07-28 02:59:18', '2017-04-26 22:01:30'),
	(25, '<p>Est veniam praesentium commodi asperiores consectetur quia vel dolorem. Ullam cupiditate dolorem laudantium alias nihil atque quod. Deleniti sapiente modi atque optio incidunt nesciunt aut. Asperiores non animi earum nobis.<br>\nSuscipit animi autem corrupti eum. Quia earum sed autem sit consectetur. In deserunt excepturi officiis et omnis.<br>\nIllo dolorem facilis perspiciatis sapiente et necessitatibus. Unde aperiam maiores consequatur atque. Et nostrum voluptatem asperiores autem.</p>', 'Est veniam praesentium commodi asperiores consectetur quia vel dolorem. Ullam cupiditate dolorem laudantium alias nihil atque quod. Deleniti sapiente modi atque optio incidunt nesciunt aut. Asperiores non animi earum nobis.\nSuscipit animi autem corrupti eum. Quia earum sed autem sit consectetur. In deserunt excepturi officiis et omnis.\nIllo dolorem facilis perspiciatis sapiente et necessitatibus. Unde aperiam maiores consequatur atque. Et nostrum voluptatem asperiores autem.', 4, 7, 16, 0, 0, 0, '2011-04-05 03:36:34', '2017-04-26 22:01:31'),
	(26, '<p>Quia eos dolorum eum ut officiis. Culpa cumque ipsa odio hic quia voluptatem optio. Excepturi maiores vel ab expedita. Praesentium quas non molestiae velit reprehenderit.<br>\nEt sit repudiandae consequuntur laborum enim. Occaecati labore saepe quia qui facere ut praesentium. Eos tenetur libero ut eum commodi omnis.<br>\nAb rem rerum ut consequatur ut ut. Sint sunt vel sunt rerum. Voluptatibus soluta cum assumenda qui. Neque velit corporis ab magni libero.</p>', 'Quia eos dolorum eum ut officiis. Culpa cumque ipsa odio hic quia voluptatem optio. Excepturi maiores vel ab expedita. Praesentium quas non molestiae velit reprehenderit.\nEt sit repudiandae consequuntur laborum enim. Occaecati labore saepe quia qui facere ut praesentium. Eos tenetur libero ut eum commodi omnis.\nAb rem rerum ut consequatur ut ut. Sint sunt vel sunt rerum. Voluptatibus soluta cum assumenda qui. Neque velit corporis ab magni libero.', 1, 7, 16, 0, 0, 0, '2014-01-17 15:04:05', '2017-04-26 22:01:31'),
	(27, '<p>Aut vero quia minus facere quo. Laboriosam dolores cupiditate libero. Unde quaerat blanditiis est at.<br>\nEa ut perferendis quis. Modi dolor facere accusantium nobis ullam. Rem doloribus quaerat sint reiciendis quos.<br>\nTemporibus illo cupiditate ab nam. Amet sequi illo assumenda culpa sed asperiores vero. Sint aliquid sit sapiente odit necessitatibus harum voluptates dignissimos.</p>', 'Aut vero quia minus facere quo. Laboriosam dolores cupiditate libero. Unde quaerat blanditiis est at.\nEa ut perferendis quis. Modi dolor facere accusantium nobis ullam. Rem doloribus quaerat sint reiciendis quos.\nTemporibus illo cupiditate ab nam. Amet sequi illo assumenda culpa sed asperiores vero. Sint aliquid sit sapiente odit necessitatibus harum voluptates dignissimos.', 5, 8, 17, 0, 0, 0, '2014-04-25 19:11:09', '2017-04-26 22:01:32'),
	(28, '<p>Et magnam veniam nobis debitis. Dolorum sapiente suscipit est eos sed quod unde.<br>\nUt illo sunt ut enim rem. Voluptatum et sit eaque sunt est quibusdam ea. Tempore et voluptatem soluta voluptate harum ullam placeat rerum. Natus qui dolor commodi repellendus minima.<br>\nQuia non soluta necessitatibus. Laudantium ut voluptatem aut odio eaque in accusamus. Qui cupiditate eveniet quia et esse. Rem hic alias ea accusamus.</p>', 'Et magnam veniam nobis debitis. Dolorum sapiente suscipit est eos sed quod unde.\nUt illo sunt ut enim rem. Voluptatum et sit eaque sunt est quibusdam ea. Tempore et voluptatem soluta voluptate harum ullam placeat rerum. Natus qui dolor commodi repellendus minima.\nQuia non soluta necessitatibus. Laudantium ut voluptatem aut odio eaque in accusamus. Qui cupiditate eveniet quia et esse. Rem hic alias ea accusamus.', 5, 8, 17, 0, 0, 0, '2016-09-09 19:43:34', '2017-04-26 22:01:32'),
	(29, '<p>Praesentium nemo occaecati fugit error minus dolorem. Iste in labore et voluptas asperiores.<br>\nPorro quia ipsum rerum molestiae fugit. Quod voluptate quo deserunt cupiditate autem.<br>\nNihil ut quia et quas aperiam enim. Totam tenetur est aut qui. Ab aut velit rem praesentium incidunt quia.<br>\nRerum amet sed quia mollitia. In id id officiis pariatur. Velit omnis et voluptates. Eum officiis aut quidem perspiciatis ratione deleniti ut.</p>', 'Praesentium nemo occaecati fugit error minus dolorem. Iste in labore et voluptas asperiores.\nPorro quia ipsum rerum molestiae fugit. Quod voluptate quo deserunt cupiditate autem.\nNihil ut quia et quas aperiam enim. Totam tenetur est aut qui. Ab aut velit rem praesentium incidunt quia.\nRerum amet sed quia mollitia. In id id officiis pariatur. Velit omnis et voluptates. Eum officiis aut quidem perspiciatis ratione deleniti ut.', 6, 8, 18, 0, 0, 0, '2015-09-13 08:41:01', '2017-04-26 22:01:34'),
	(30, '<p>Omnis provident aliquid architecto iste velit eum suscipit. Aliquid voluptas aut ut ratione ut. Alias ex officia velit qui est. Officia culpa excepturi dignissimos.<br>\nVoluptas sit adipisci aut excepturi fugit harum. Illo placeat accusantium praesentium neque. Doloribus quia tempore magni quae et.<br>\nQuo harum voluptates repudiandae qui sit est officia. Asperiores totam ab repellendus et eveniet dolores. Tenetur minima excepturi ducimus et. Iusto nisi fugiat et fugit.</p>', 'Omnis provident aliquid architecto iste velit eum suscipit. Aliquid voluptas aut ut ratione ut. Alias ex officia velit qui est. Officia culpa excepturi dignissimos.\nVoluptas sit adipisci aut excepturi fugit harum. Illo placeat accusantium praesentium neque. Doloribus quia tempore magni quae et.\nQuo harum voluptates repudiandae qui sit est officia. Asperiores totam ab repellendus et eveniet dolores. Tenetur minima excepturi ducimus et. Iusto nisi fugiat et fugit.', 6, 8, 18, 0, 0, 0, '2008-02-03 07:23:47', '2017-04-26 22:01:34'),
	(31, '<p>Vitae fugit ipsa vero nihil impedit dolores odio dolorem. Ipsa iure exercitationem qui animi nihil ipsam ut. Numquam non consequatur ducimus repellat nostrum quae excepturi. Eum vero autem molestiae fugit eius autem. Ut vero quia ipsa quia unde quaerat.<br>\nTemporibus qui sed sequi aperiam ut sed exercitationem. Eaque vero nesciunt quas itaque. Ut perferendis ducimus inventore nihil maiores quia. Ipsam asperiores rerum doloribus ratione.</p>', 'Vitae fugit ipsa vero nihil impedit dolores odio dolorem. Ipsa iure exercitationem qui animi nihil ipsam ut. Numquam non consequatur ducimus repellat nostrum quae excepturi. Eum vero autem molestiae fugit eius autem. Ut vero quia ipsa quia unde quaerat.\nTemporibus qui sed sequi aperiam ut sed exercitationem. Eaque vero nesciunt quas itaque. Ut perferendis ducimus inventore nihil maiores quia. Ipsam asperiores rerum doloribus ratione.', 3, 8, 19, 0, 0, 0, '2010-03-20 16:04:22', '2017-04-26 22:01:35'),
	(32, '<p>Culpa praesentium optio error earum quis. Aliquid voluptas vitae delectus minima et.<br>\nAssumenda maxime omnis accusamus autem quasi. Accusamus pariatur ea beatae exercitationem eum quaerat. Et culpa fugiat architecto qui qui non repellat.<br>\nQuia qui sed vel aut. Dolore magni saepe aut recusandae cupiditate non velit. Omnis vero eum dolorem ea laboriosam animi. Quo qui quia beatae ab praesentium eum earum.</p>', 'Culpa praesentium optio error earum quis. Aliquid voluptas vitae delectus minima et.\nAssumenda maxime omnis accusamus autem quasi. Accusamus pariatur ea beatae exercitationem eum quaerat. Et culpa fugiat architecto qui qui non repellat.\nQuia qui sed vel aut. Dolore magni saepe aut recusandae cupiditate non velit. Omnis vero eum dolorem ea laboriosam animi. Quo qui quia beatae ab praesentium eum earum.', 2, 8, 19, 0, 0, 0, '2012-02-29 00:23:33', '2017-04-26 22:01:35'),
	(33, '<p>Eum itaque aut veniam. Modi deleniti aperiam esse eaque sed ut et. Omnis ea dicta error et aut libero ullam.<br>\nNihil quas atque nisi eum. Quae neque et illum est eum consequatur repellendus explicabo. Ipsam iusto numquam aut aut sunt sequi. Neque nihil distinctio soluta magni aut sit dolor.<br>\nDicta eligendi rem aspernatur ex in officia quia. At aut officia minima culpa enim consequatur et sed. Expedita beatae molestias vel placeat rerum velit rerum. Aut quis tenetur est architecto voluptas.</p>', 'Eum itaque aut veniam. Modi deleniti aperiam esse eaque sed ut et. Omnis ea dicta error et aut libero ullam.\nNihil quas atque nisi eum. Quae neque et illum est eum consequatur repellendus explicabo. Ipsam iusto numquam aut aut sunt sequi. Neque nihil distinctio soluta magni aut sit dolor.\nDicta eligendi rem aspernatur ex in officia quia. At aut officia minima culpa enim consequatur et sed. Expedita beatae molestias vel placeat rerum velit rerum. Aut quis tenetur est architecto voluptas.', 4, 8, 19, 0, 0, 0, '2008-07-14 22:22:58', '2017-04-26 22:01:35'),
	(34, '<p>Architecto velit dolor possimus enim dolor ut. Rerum vel nam unde et minima suscipit labore. Qui tenetur officiis commodi commodi commodi dolorem.<br>\nDignissimos soluta aliquid commodi in et. Esse aut est enim blanditiis eum velit culpa ullam. Nihil consectetur voluptatem ex ab iure placeat illum dignissimos.</p>', 'Architecto velit dolor possimus enim dolor ut. Rerum vel nam unde et minima suscipit labore. Qui tenetur officiis commodi commodi commodi dolorem.\nDignissimos soluta aliquid commodi in et. Esse aut est enim blanditiis eum velit culpa ullam. Nihil consectetur voluptatem ex ab iure placeat illum dignissimos.', 4, 8, 20, 0, 0, 0, '2012-02-25 23:10:56', '2017-04-26 22:01:36'),
	(35, '<p>Non maxime quia omnis qui. Est quo qui sed voluptas et. Suscipit ipsa sint impedit sit fugit aut.<br>\nQuis quae corrupti totam nobis a possimus neque. Suscipit praesentium cum optio sapiente aliquam omnis. Veniam eos quidem labore illo earum nam laboriosam.<br>\nSunt eveniet ab sit. Officia et illo quo fuga vero dolores. Dolor ea eos sit nulla adipisci illo. Placeat inventore et quidem debitis saepe officia.<br>\nOfficiis at aut eos totam. Nemo at omnis rerum possimus. Illum aliquid et nesciunt.</p>', 'Non maxime quia omnis qui. Est quo qui sed voluptas et. Suscipit ipsa sint impedit sit fugit aut.\nQuis quae corrupti totam nobis a possimus neque. Suscipit praesentium cum optio sapiente aliquam omnis. Veniam eos quidem labore illo earum nam laboriosam.\nSunt eveniet ab sit. Officia et illo quo fuga vero dolores. Dolor ea eos sit nulla adipisci illo. Placeat inventore et quidem debitis saepe officia.\nOfficiis at aut eos totam. Nemo at omnis rerum possimus. Illum aliquid et nesciunt.', 1, 9, 21, 0, 0, 0, '2012-05-11 20:56:53', '2017-04-26 22:01:38'),
	(36, '<p>Esse laborum facere eveniet delectus iure. Possimus consequuntur rerum aliquam accusamus occaecati. Ut qui nostrum laboriosam eius dolores.<br>\nQuibusdam eum sapiente velit vel officia. Harum maiores autem laborum facere error sit voluptatem. Repellendus veritatis consequatur delectus praesentium eos. Nulla sunt et nostrum natus.<br>\nDolores totam autem saepe non ut vel et earum. Reiciendis minima velit dolores doloribus. Aspernatur neque ut quae ea quia.</p>', 'Esse laborum facere eveniet delectus iure. Possimus consequuntur rerum aliquam accusamus occaecati. Ut qui nostrum laboriosam eius dolores.\nQuibusdam eum sapiente velit vel officia. Harum maiores autem laborum facere error sit voluptatem. Repellendus veritatis consequatur delectus praesentium eos. Nulla sunt et nostrum natus.\nDolores totam autem saepe non ut vel et earum. Reiciendis minima velit dolores doloribus. Aspernatur neque ut quae ea quia.', 7, 9, 21, 0, 0, 0, '2014-07-20 03:13:25', '2017-04-26 22:01:39'),
	(37, '<p>Voluptatem et iure voluptas vitae officia exercitationem. Pariatur velit assumenda molestiae accusantium alias ipsum molestiae. Voluptatum nihil adipisci et. Recusandae consequatur natus aut corporis officiis. Cum quo nostrum cum voluptates non labore.<br>\nEt ratione harum incidunt fugiat sint quod. Quo sed et laborum omnis cupiditate. Est molestiae corporis aspernatur cumque.<br>\nVelit quia dolor voluptatibus deserunt qui vitae consequuntur. Est eveniet exercitationem minus vitae iste eos est.</p>', 'Voluptatem et iure voluptas vitae officia exercitationem. Pariatur velit assumenda molestiae accusantium alias ipsum molestiae. Voluptatum nihil adipisci et. Recusandae consequatur natus aut corporis officiis. Cum quo nostrum cum voluptates non labore.\nEt ratione harum incidunt fugiat sint quod. Quo sed et laborum omnis cupiditate. Est molestiae corporis aspernatur cumque.\nVelit quia dolor voluptatibus deserunt qui vitae consequuntur. Est eveniet exercitationem minus vitae iste eos est.', 6, 9, 21, 0, 0, 0, '2012-06-11 11:52:45', '2017-04-26 22:01:39'),
	(38, '<p>Ipsa nihil rerum et sint. Quam autem id alias velit quis non repellat. Sequi voluptatibus rerum deserunt sed omnis iusto consequatur. Voluptatem a dolores atque optio ducimus quo omnis.<br>\nEt rem ullam harum deleniti laboriosam. Aut esse voluptas neque. Et amet nam iure illo autem harum necessitatibus. Incidunt consequatur vel recusandae aut iusto.</p>', 'Ipsa nihil rerum et sint. Quam autem id alias velit quis non repellat. Sequi voluptatibus rerum deserunt sed omnis iusto consequatur. Voluptatem a dolores atque optio ducimus quo omnis.\nEt rem ullam harum deleniti laboriosam. Aut esse voluptas neque. Et amet nam iure illo autem harum necessitatibus. Incidunt consequatur vel recusandae aut iusto.', 5, 9, 23, 0, 0, 0, '2011-06-12 03:29:00', '2017-04-26 22:01:41'),
	(39, '<p>Sunt quis qui sed ad voluptates et. Ut doloribus voluptatem illo minima et natus. Fugit dolores ducimus sit iure.<br>\nRerum sapiente quia exercitationem sequi. Saepe libero soluta laboriosam pariatur quia quaerat sit. Maxime quia fugiat voluptas qui libero facilis dolores.<br>\nEsse autem adipisci ea explicabo. Dignissimos accusamus eius sint provident. Ipsa similique cumque omnis dicta quis. Velit accusamus officiis quo aliquam.</p>', 'Sunt quis qui sed ad voluptates et. Ut doloribus voluptatem illo minima et natus. Fugit dolores ducimus sit iure.\nRerum sapiente quia exercitationem sequi. Saepe libero soluta laboriosam pariatur quia quaerat sit. Maxime quia fugiat voluptas qui libero facilis dolores.\nEsse autem adipisci ea explicabo. Dignissimos accusamus eius sint provident. Ipsa similique cumque omnis dicta quis. Velit accusamus officiis quo aliquam.', 8, 9, 23, 0, 0, 0, '2010-01-25 20:11:52', '2017-04-26 22:01:41'),
	(40, '<p>Nam tempore laboriosam ab corrupti dignissimos quo quae quia. Maiores laborum blanditiis et dolorem.<br>\nQuis ipsa ipsam omnis voluptas sed laudantium quisquam. Quia dolore aut totam illum. Velit quis id nam eos ea iusto ipsam.<br>\nVoluptas est praesentium sint corporis. Fugiat perferendis beatae sit dolorem laudantium sapiente iure accusamus. Eligendi et velit itaque consequatur.</p>', 'Nam tempore laboriosam ab corrupti dignissimos quo quae quia. Maiores laborum blanditiis et dolorem.\nQuis ipsa ipsam omnis voluptas sed laudantium quisquam. Quia dolore aut totam illum. Velit quis id nam eos ea iusto ipsam.\nVoluptas est praesentium sint corporis. Fugiat perferendis beatae sit dolorem laudantium sapiente iure accusamus. Eligendi et velit itaque consequatur.', 1, 10, 25, 0, 0, 0, '2009-07-23 19:28:27', '2017-04-26 22:01:44'),
	(41, '<p>Ut deserunt eius vero perspiciatis nihil aut. Ea maiores veniam ipsam modi ea quo. Reiciendis aut vero maxime atque veniam earum.<br>\nVoluptate voluptas cupiditate accusamus perferendis qui iusto occaecati sed. Quia itaque enim voluptatem. Ut voluptatem magnam dignissimos vero.<br>\nVel tenetur molestias nobis autem corrupti nam voluptate labore. Fuga dicta et sint voluptatem perspiciatis vero non ipsa. Aut laudantium omnis dolorem consequatur deserunt. Consequatur magni itaque et et corrupti alias.</p>', 'Ut deserunt eius vero perspiciatis nihil aut. Ea maiores veniam ipsam modi ea quo. Reiciendis aut vero maxime atque veniam earum.\nVoluptate voluptas cupiditate accusamus perferendis qui iusto occaecati sed. Quia itaque enim voluptatem. Ut voluptatem magnam dignissimos vero.\nVel tenetur molestias nobis autem corrupti nam voluptate labore. Fuga dicta et sint voluptatem perspiciatis vero non ipsa. Aut laudantium omnis dolorem consequatur deserunt. Consequatur magni itaque et et corrupti alias.', 6, 10, 25, 0, 0, 0, '2016-10-09 05:37:22', '2017-04-26 22:01:44'),
	(42, '<p>Aut est ullam hic ut voluptatum. Expedita aspernatur odit quia est eligendi. Quo sunt qui eum enim porro laudantium quia.<br>\nEa incidunt quae saepe debitis voluptate sunt. Unde quia illum alias perspiciatis. Occaecati est in neque distinctio consequuntur. Vel omnis aut laborum quaerat et dolore doloribus.<br>\nNostrum totam et ut ipsa odio nesciunt. Sed et dolor magni voluptas aut laborum. Dolorem voluptatem nostrum amet velit.</p>', 'Aut est ullam hic ut voluptatum. Expedita aspernatur odit quia est eligendi. Quo sunt qui eum enim porro laudantium quia.\nEa incidunt quae saepe debitis voluptate sunt. Unde quia illum alias perspiciatis. Occaecati est in neque distinctio consequuntur. Vel omnis aut laborum quaerat et dolore doloribus.\nNostrum totam et ut ipsa odio nesciunt. Sed et dolor magni voluptas aut laborum. Dolorem voluptatem nostrum amet velit.', 3, 10, 26, 0, 0, 0, '2014-07-14 14:21:48', '2017-04-26 22:01:45'),
	(43, '<p>Est fugiat illo facilis quidem nostrum corrupti. Cum a sunt molestiae voluptatum.<br>\nMollitia vel optio ut nostrum et. Accusamus similique vitae in iure quibusdam aut. Doloremque vel ducimus molestiae.<br>\nCorrupti ipsam recusandae est voluptatum quia quo est. Iusto et praesentium dignissimos voluptatum rem nam. Quo voluptatem velit illum non minima enim. Nam laudantium doloremque est cupiditate laborum.</p>', 'Est fugiat illo facilis quidem nostrum corrupti. Cum a sunt molestiae voluptatum.\nMollitia vel optio ut nostrum et. Accusamus similique vitae in iure quibusdam aut. Doloremque vel ducimus molestiae.\nCorrupti ipsam recusandae est voluptatum quia quo est. Iusto et praesentium dignissimos voluptatum rem nam. Quo voluptatem velit illum non minima enim. Nam laudantium doloremque est cupiditate laborum.', 5, 10, 26, 0, 0, 0, '2008-08-24 23:11:21', '2017-04-26 22:01:45'),
	(44, '<p>Natus nihil numquam voluptates quidem aliquid ex. Voluptatum cupiditate dignissimos quam consequatur aut omnis. Voluptatem et labore sunt facilis. Eligendi totam culpa aut aut vero molestiae.<br>\nQuod distinctio ipsam doloribus soluta suscipit. Soluta reiciendis sapiente repellat. Eos sapiente ea autem recusandae incidunt.<br>\nAccusamus quisquam nisi et deleniti cumque incidunt. Ipsa et esse nihil cum magni. Eos magnam cumque nam qui. Odit libero similique sequi cum ut sapiente.</p>', 'Natus nihil numquam voluptates quidem aliquid ex. Voluptatum cupiditate dignissimos quam consequatur aut omnis. Voluptatem et labore sunt facilis. Eligendi totam culpa aut aut vero molestiae.\nQuod distinctio ipsam doloribus soluta suscipit. Soluta reiciendis sapiente repellat. Eos sapiente ea autem recusandae incidunt.\nAccusamus quisquam nisi et deleniti cumque incidunt. Ipsa et esse nihil cum magni. Eos magnam cumque nam qui. Odit libero similique sequi cum ut sapiente.', 7, 10, 27, 0, 0, 0, '2008-09-08 22:07:06', '2017-04-26 22:01:46'),
	(45, '<p>Suscipit ratione aut a asperiores. Et nemo inventore ut. Reiciendis vel accusantium culpa officia.<br>\nQui officiis fuga neque enim. Autem porro nisi tempore rerum ullam qui dolor. Cumque laudantium magni ab sunt aspernatur iste aut rerum. Cumque excepturi vel et reiciendis. Dolorem est illo occaecati eos.<br>\nIllum sit ipsa debitis atque iste. Deserunt porro enim neque ipsa corrupti dolorem. Vero voluptas dignissimos dolorem totam laborum ipsa.</p>', 'Suscipit ratione aut a asperiores. Et nemo inventore ut. Reiciendis vel accusantium culpa officia.\nQui officiis fuga neque enim. Autem porro nisi tempore rerum ullam qui dolor. Cumque laudantium magni ab sunt aspernatur iste aut rerum. Cumque excepturi vel et reiciendis. Dolorem est illo occaecati eos.\nIllum sit ipsa debitis atque iste. Deserunt porro enim neque ipsa corrupti dolorem. Vero voluptas dignissimos dolorem totam laborum ipsa.', 8, 12, 28, 0, 0, 0, '2015-10-15 18:51:01', '2017-04-26 22:01:47'),
	(46, '<p>Odio odit atque a soluta. Quas nam quis molestias quo molestiae id veritatis. Vero qui quis voluptatum adipisci officia maxime. Corporis laborum nemo aspernatur aut nulla voluptates consequatur.<br>\nIure qui nostrum cumque fuga. Ipsa cupiditate deserunt aspernatur quod atque consequatur cumque.<br>\nMolestias impedit et dolorum dolor aut consequuntur a. Quo ad facilis ratione natus quos consequatur. Omnis maxime totam est est non natus aspernatur voluptates.</p>', 'Odio odit atque a soluta. Quas nam quis molestias quo molestiae id veritatis. Vero qui quis voluptatum adipisci officia maxime. Corporis laborum nemo aspernatur aut nulla voluptates consequatur.\nIure qui nostrum cumque fuga. Ipsa cupiditate deserunt aspernatur quod atque consequatur cumque.\nMolestias impedit et dolorum dolor aut consequuntur a. Quo ad facilis ratione natus quos consequatur. Omnis maxime totam est est non natus aspernatur voluptates.', 3, 12, 28, 0, 0, 0, '2016-05-07 20:43:00', '2017-04-26 22:01:47'),
	(47, '<p>Quia voluptate rerum fugit laudantium dolorem veniam. Quas et reiciendis sint quia iusto temporibus molestias placeat.<br>\nRepudiandae dolorem labore rerum sit aut tenetur veniam. Accusantium quis ipsa saepe quia asperiores error. Perspiciatis eos eveniet est vero. Et eveniet deleniti sit a labore ut nihil maxime.<br>\nExercitationem rerum eaque qui eum nulla ad et. Quia qui labore similique quae in magnam accusantium. Adipisci culpa temporibus ad repellendus minus neque est.</p>', 'Quia voluptate rerum fugit laudantium dolorem veniam. Quas et reiciendis sint quia iusto temporibus molestias placeat.\nRepudiandae dolorem labore rerum sit aut tenetur veniam. Accusantium quis ipsa saepe quia asperiores error. Perspiciatis eos eveniet est vero. Et eveniet deleniti sit a labore ut nihil maxime.\nExercitationem rerum eaque qui eum nulla ad et. Quia qui labore similique quae in magnam accusantium. Adipisci culpa temporibus ad repellendus minus neque est.', 5, 12, 29, 0, 0, 0, '2014-03-12 15:57:57', '2017-04-26 22:01:48'),
	(48, '<p>Temporibus eos et ipsa qui fuga fugiat voluptatum. Labore nemo vel quas quos ullam sunt in. Velit voluptates et eum a totam consectetur consequatur. Quos error et iste dolorem magnam provident qui.<br>\nCorporis minus vel velit id porro ex neque. Ea voluptatibus sequi repellat ipsa.<br>\nOdit sequi recusandae ab qui omnis. Omnis autem labore corporis. Placeat voluptatem voluptas nihil.</p>', 'Temporibus eos et ipsa qui fuga fugiat voluptatum. Labore nemo vel quas quos ullam sunt in. Velit voluptates et eum a totam consectetur consequatur. Quos error et iste dolorem magnam provident qui.\nCorporis minus vel velit id porro ex neque. Ea voluptatibus sequi repellat ipsa.\nOdit sequi recusandae ab qui omnis. Omnis autem labore corporis. Placeat voluptatem voluptas nihil.', 1, 12, 29, 0, 0, 0, '2012-08-14 22:57:16', '2017-04-26 22:01:48');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.comment_replies
CREATE TABLE IF NOT EXISTS `comment_replies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_source` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `uv` int(10) unsigned NOT NULL DEFAULT '0',
  `dv` int(10) unsigned NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_replies_user_id_foreign` (`user_id`),
  KEY `comment_replies_group_id_foreign` (`group_id`),
  KEY `comment_replies_parent_id_foreign` (`parent_id`),
  CONSTRAINT `comment_replies_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `comment_replies_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`),
  CONSTRAINT `comment_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.comment_replies: ~4 rows (około)
/*!40000 ALTER TABLE `comment_replies` DISABLE KEYS */;
INSERT IGNORE INTO `comment_replies` (`id`, `text`, `text_source`, `user_id`, `group_id`, `parent_id`, `uv`, `dv`, `score`, `created_at`, `updated_at`) VALUES
	(1, '<p>Repudiandae nesciunt similique fugiat consequatur et. Quas temporibus facere commodi velit expedita. Tempora vero sit voluptatem unde modi dolore.<br>\nEos in adipisci earum aut soluta. Dolores ipsa nihil ut eos et totam qui facere. Eos vero impedit ut quo est expedita molestias. Aut deleniti sapiente voluptatum voluptas qui provident consequuntur. Explicabo non voluptates possimus voluptatibus quas repellendus aut nemo.<br>\nSit fugiat corrupti omnis aliquid debitis. Non non repudiandae earum. Ex odit rem quia.</p>', 'Repudiandae nesciunt similique fugiat consequatur et. Quas temporibus facere commodi velit expedita. Tempora vero sit voluptatem unde modi dolore.\nEos in adipisci earum aut soluta. Dolores ipsa nihil ut eos et totam qui facere. Eos vero impedit ut quo est expedita molestias. Aut deleniti sapiente voluptatum voluptas qui provident consequuntur. Explicabo non voluptates possimus voluptatibus quas repellendus aut nemo.\nSit fugiat corrupti omnis aliquid debitis. Non non repudiandae earum. Ex odit rem quia.', 1, 1, 1, 0, 0, 0, '2007-09-04 02:52:01', '2017-04-26 22:01:09'),
	(2, '<p>Nulla ducimus veniam quo ducimus. Possimus a accusamus et laudantium ut. Et doloribus est qui.<br>\nVoluptas quo voluptatibus repellendus perspiciatis harum. Possimus architecto adipisci sapiente. Repudiandae ut eligendi vitae quia est placeat ullam.<br>\nVoluptatem quas minima sint debitis vel beatae ea. Autem nulla sint tenetur tempore.<br>\nDucimus suscipit et aut ut. Quia labore vel hic reprehenderit id possimus rerum. Tempore est reiciendis sapiente nesciunt.</p>', 'Nulla ducimus veniam quo ducimus. Possimus a accusamus et laudantium ut. Et doloribus est qui.\nVoluptas quo voluptatibus repellendus perspiciatis harum. Possimus architecto adipisci sapiente. Repudiandae ut eligendi vitae quia est placeat ullam.\nVoluptatem quas minima sint debitis vel beatae ea. Autem nulla sint tenetur tempore.\nDucimus suscipit et aut ut. Quia labore vel hic reprehenderit id possimus rerum. Tempore est reiciendis sapiente nesciunt.', 1, 1, 1, 0, 0, 0, '2007-10-27 17:38:44', '2017-04-26 22:01:09'),
	(3, '<p>Est ullam eum odio autem. Autem ab et alias. Corrupti et et sit sint voluptas. Qui officia possimus voluptatibus impedit odio est accusamus dignissimos.<br>\nAccusantium earum corrupti commodi itaque esse odit omnis. Natus quia iste sit facilis et voluptas voluptatem. Nesciunt porro voluptas culpa in. Voluptas praesentium sequi consequatur laboriosam culpa aut.</p>', 'Est ullam eum odio autem. Autem ab et alias. Corrupti et et sit sint voluptas. Qui officia possimus voluptatibus impedit odio est accusamus dignissimos.\nAccusantium earum corrupti commodi itaque esse odit omnis. Natus quia iste sit facilis et voluptas voluptatem. Nesciunt porro voluptas culpa in. Voluptas praesentium sequi consequatur laboriosam culpa aut.', 1, 1, 2, 0, 0, 0, '2012-05-28 02:32:46', '2017-04-26 22:01:09'),
	(4, '<p>Dolorem consequatur id odit culpa. Saepe aliquam explicabo consequatur voluptas ut velit in. Qui ad quam itaque rerum quia rem quo. Accusantium exercitationem voluptatum in itaque repellendus numquam doloribus. Sit qui omnis commodi unde.<br>\nRerum quibusdam et suscipit non ut unde rerum accusamus. Iusto amet totam accusamus vel quaerat aut. Consectetur assumenda expedita velit quia.</p>', 'Dolorem consequatur id odit culpa. Saepe aliquam explicabo consequatur voluptas ut velit in. Qui ad quam itaque rerum quia rem quo. Accusantium exercitationem voluptatum in itaque repellendus numquam doloribus. Sit qui omnis commodi unde.\nRerum quibusdam et suscipit non ut unde rerum accusamus. Iusto amet totam accusamus vel quaerat aut. Consectetur assumenda expedita velit quia.', 1, 1, 2, 0, 0, 0, '2011-11-28 17:07:34', '2017-04-26 22:01:09'),
	(5, '<p>Eum rem omnis consequatur. Saepe soluta minus commodi modi repellendus et. Unde laudantium iure atque assumenda quidem voluptatem excepturi impedit. Vitae fugiat impedit sint ullam.<br>\nSoluta odit sed itaque velit asperiores. Illo officiis quod fuga impedit. Velit consequatur soluta illum nobis repellat cumque voluptatum voluptates. Inventore soluta dolorem quo fuga ut nam.</p>', 'Eum rem omnis consequatur. Saepe soluta minus commodi modi repellendus et. Unde laudantium iure atque assumenda quidem voluptatem excepturi impedit. Vitae fugiat impedit sint ullam.\nSoluta odit sed itaque velit asperiores. Illo officiis quod fuga impedit. Velit consequatur soluta illum nobis repellat cumque voluptatum voluptates. Inventore soluta dolorem quo fuga ut nam.', 3, 3, 3, 0, 0, 0, '2008-07-14 17:48:55', '2017-04-26 22:01:11'),
	(6, '<p>Maiores ipsum aut officia quia. Consequatur quia maxime distinctio deserunt ut. Et ea aperiam nisi necessitatibus qui aut minus.<br>\nEveniet magni et possimus ratione quia qui veniam. Mollitia quis qui provident. Cum et non est et non id perspiciatis beatae. Cum sit voluptas id vitae dignissimos. Ipsa aspernatur illum asperiores.<br>\nNihil repellendus labore aperiam nihil natus veritatis nihil architecto. Eum labore alias veniam enim labore. Culpa et repudiandae voluptate laboriosam fuga aut necessitatibus.</p>', 'Maiores ipsum aut officia quia. Consequatur quia maxime distinctio deserunt ut. Et ea aperiam nisi necessitatibus qui aut minus.\nEveniet magni et possimus ratione quia qui veniam. Mollitia quis qui provident. Cum et non est et non id perspiciatis beatae. Cum sit voluptas id vitae dignissimos. Ipsa aspernatur illum asperiores.\nNihil repellendus labore aperiam nihil natus veritatis nihil architecto. Eum labore alias veniam enim labore. Culpa et repudiandae voluptate laboriosam fuga aut necessitatibus.', 2, 3, 3, 0, 0, 0, '2010-05-24 12:02:03', '2017-04-26 22:01:12'),
	(7, '<p>Dolores ut quasi repellendus animi. Hic quia hic aut. Harum ut dolorum et repellat.<br>\nExcepturi molestiae laboriosam nesciunt consequatur alias doloremque non. Iste enim porro ut id.<br>\nRepudiandae dolorum non ut ut repellat. Ut eos et doloremque nulla. Vel quia voluptatem velit maxime libero explicabo.<br>\nAutem voluptatem nihil unde reiciendis qui. Excepturi nisi enim enim optio possimus consequatur autem. Pariatur minus facere cumque excepturi.</p>', 'Dolores ut quasi repellendus animi. Hic quia hic aut. Harum ut dolorum et repellat.\nExcepturi molestiae laboriosam nesciunt consequatur alias doloremque non. Iste enim porro ut id.\nRepudiandae dolorum non ut ut repellat. Ut eos et doloremque nulla. Vel quia voluptatem velit maxime libero explicabo.\nAutem voluptatem nihil unde reiciendis qui. Excepturi nisi enim enim optio possimus consequatur autem. Pariatur minus facere cumque excepturi.', 3, 3, 3, 0, 0, 0, '2017-02-04 20:25:34', '2017-04-26 22:01:12'),
	(8, '<p>Consectetur excepturi quaerat ut sed mollitia. Illum vel sit reiciendis deleniti tempore facere. Ut inventore non qui et a ex hic. Rem asperiores quaerat omnis eveniet. Itaque perferendis labore voluptate rerum ducimus magnam.<br>\nMollitia dicta alias excepturi deserunt natus. Quia exercitationem facere quod asperiores optio adipisci sed et. Est commodi quo qui accusamus error ex reprehenderit. Dolor rem aut et expedita autem cumque blanditiis.</p>', 'Consectetur excepturi quaerat ut sed mollitia. Illum vel sit reiciendis deleniti tempore facere. Ut inventore non qui et a ex hic. Rem asperiores quaerat omnis eveniet. Itaque perferendis labore voluptate rerum ducimus magnam.\nMollitia dicta alias excepturi deserunt natus. Quia exercitationem facere quod asperiores optio adipisci sed et. Est commodi quo qui accusamus error ex reprehenderit. Dolor rem aut et expedita autem cumque blanditiis.', 1, 3, 4, 0, 0, 0, '2015-10-20 21:07:47', '2017-04-26 22:01:12'),
	(9, '<p>Praesentium sit nihil fugit eius voluptas. Nemo in perspiciatis odio quasi ea distinctio voluptas. Quia laborum eaque similique id aut voluptatem. Quidem et consectetur dolor autem quia fugiat enim.<br>\nRepellat dolore ea natus voluptatibus qui. Et quo perferendis corporis minus magni. Quod mollitia fugiat eius vel repudiandae architecto. Dolores rerum et facere labore ea ipsum omnis dolores. Consectetur aperiam inventore eos illo.</p>', 'Praesentium sit nihil fugit eius voluptas. Nemo in perspiciatis odio quasi ea distinctio voluptas. Quia laborum eaque similique id aut voluptatem. Quidem et consectetur dolor autem quia fugiat enim.\nRepellat dolore ea natus voluptatibus qui. Et quo perferendis corporis minus magni. Quod mollitia fugiat eius vel repudiandae architecto. Dolores rerum et facere labore ea ipsum omnis dolores. Consectetur aperiam inventore eos illo.', 2, 3, 4, 0, 0, 0, '2010-02-25 21:16:47', '2017-04-26 22:01:12'),
	(10, '<p>Illum non et quos dignissimos. Quae dolorum fuga aut quidem eius. Nesciunt accusantium eveniet at. Libero perspiciatis dolor molestiae dicta architecto.<br>\nDignissimos atque impedit saepe molestiae vitae animi. Quas voluptates numquam asperiores quis corporis. Delectus similique deleniti dicta quisquam laborum placeat quia.</p>', 'Illum non et quos dignissimos. Quae dolorum fuga aut quidem eius. Nesciunt accusantium eveniet at. Libero perspiciatis dolor molestiae dicta architecto.\nDignissimos atque impedit saepe molestiae vitae animi. Quas voluptates numquam asperiores quis corporis. Delectus similique deleniti dicta quisquam laborum placeat quia.', 3, 3, 4, 0, 0, 0, '2012-04-19 23:32:52', '2017-04-26 22:01:12'),
	(11, '<p>Natus ut iure consectetur sit non sed. Dolorem rem quis enim eveniet optio. Voluptas qui voluptas fugiat voluptatum. Qui est doloremque qui ducimus aperiam aut modi.<br>\nNulla qui ut occaecati corporis magnam ut velit autem. Amet aliquid ex a officia maxime consequuntur consequatur. Fuga rem est rerum laborum dolore quibusdam veritatis.<br>\nIpsam reiciendis a et omnis et. Non minima nihil fugit quae autem qui. Sit cum quibusdam ad.</p>', 'Natus ut iure consectetur sit non sed. Dolorem rem quis enim eveniet optio. Voluptas qui voluptas fugiat voluptatum. Qui est doloremque qui ducimus aperiam aut modi.\nNulla qui ut occaecati corporis magnam ut velit autem. Amet aliquid ex a officia maxime consequuntur consequatur. Fuga rem est rerum laborum dolore quibusdam veritatis.\nIpsam reiciendis a et omnis et. Non minima nihil fugit quae autem qui. Sit cum quibusdam ad.', 1, 3, 5, 0, 0, 0, '2016-01-04 05:32:57', '2017-04-26 22:01:13'),
	(12, '<p>Aliquam sint suscipit placeat est similique rerum. Eveniet consequatur sunt voluptas et dolorem aut et. Consectetur ut et magnam harum velit unde. Recusandae consequatur qui et exercitationem est.<br>\nQuos in occaecati voluptate minima rem. Explicabo architecto sequi aut.<br>\nDicta dignissimos expedita ad aliquam atque sint reprehenderit odio. Voluptatum fugiat asperiores ut cupiditate harum doloribus aspernatur. Similique optio quia eum. Qui ea omnis illum dolores.</p>', 'Aliquam sint suscipit placeat est similique rerum. Eveniet consequatur sunt voluptas et dolorem aut et. Consectetur ut et magnam harum velit unde. Recusandae consequatur qui et exercitationem est.\nQuos in occaecati voluptate minima rem. Explicabo architecto sequi aut.\nDicta dignissimos expedita ad aliquam atque sint reprehenderit odio. Voluptatum fugiat asperiores ut cupiditate harum doloribus aspernatur. Similique optio quia eum. Qui ea omnis illum dolores.', 1, 3, 5, 0, 0, 0, '2009-02-16 07:26:36', '2017-04-26 22:01:14'),
	(13, '<p>Similique vitae laborum itaque fugiat assumenda eius. Temporibus maiores occaecati aut odit autem. Accusantium officia quibusdam consequatur perspiciatis molestiae dolor. Error nemo est nobis odio.<br>\nOdit quaerat recusandae molestias qui. Natus voluptatem libero ut eos repellendus. Dolore et labore eos reprehenderit dolore iure laboriosam.<br>\nQuia veritatis voluptatem nulla asperiores. Porro est aut corrupti officia et ut. Saepe voluptate fugit porro et. Alias rerum est sed tempora.</p>', 'Similique vitae laborum itaque fugiat assumenda eius. Temporibus maiores occaecati aut odit autem. Accusantium officia quibusdam consequatur perspiciatis molestiae dolor. Error nemo est nobis odio.\nOdit quaerat recusandae molestias qui. Natus voluptatem libero ut eos repellendus. Dolore et labore eos reprehenderit dolore iure laboriosam.\nQuia veritatis voluptatem nulla asperiores. Porro est aut corrupti officia et ut. Saepe voluptate fugit porro et. Alias rerum est sed tempora.', 2, 3, 6, 0, 0, 0, '2011-03-23 19:19:28', '2017-04-26 22:01:14'),
	(14, '<p>Provident nihil quisquam fugiat quia et voluptatibus autem. Vel assumenda et similique qui sapiente ratione. Quia repellat veniam sit asperiores et ea.<br>\nReprehenderit facilis dolores distinctio. Temporibus officiis id ullam sit. Aut itaque et id ipsa voluptas quisquam eos.<br>\nEum consequatur quis maiores repellat. Sunt et quam qui consequuntur et. Esse dolorum enim culpa minima. Aut officiis ea quos sed.</p>', 'Provident nihil quisquam fugiat quia et voluptatibus autem. Vel assumenda et similique qui sapiente ratione. Quia repellat veniam sit asperiores et ea.\nReprehenderit facilis dolores distinctio. Temporibus officiis id ullam sit. Aut itaque et id ipsa voluptas quisquam eos.\nEum consequatur quis maiores repellat. Sunt et quam qui consequuntur et. Esse dolorum enim culpa minima. Aut officiis ea quos sed.', 2, 3, 7, 0, 0, 0, '2007-05-21 19:53:14', '2017-04-26 22:01:15'),
	(15, '<p>Natus sint velit quidem corrupti officia eos. Deserunt dolores quam ab nihil. Illum illo molestiae sed laborum cupiditate ut.<br>\nNostrum facere a quasi ut excepturi veniam corrupti. Deleniti ut porro ut mollitia tempora et. At error neque voluptatibus ullam.<br>\nNon molestias doloribus dolor sed. Repudiandae laborum sequi ipsam aut. Inventore quo et non commodi.</p>', 'Natus sint velit quidem corrupti officia eos. Deserunt dolores quam ab nihil. Illum illo molestiae sed laborum cupiditate ut.\nNostrum facere a quasi ut excepturi veniam corrupti. Deleniti ut porro ut mollitia tempora et. At error neque voluptatibus ullam.\nNon molestias doloribus dolor sed. Repudiandae laborum sequi ipsam aut. Inventore quo et non commodi.', 2, 3, 7, 0, 0, 0, '2010-01-15 10:38:41', '2017-04-26 22:01:15'),
	(16, '<p>Et iusto aut tempore vel dicta quisquam dolor velit. Dolores quo veniam voluptatum impedit quisquam.<br>\nCommodi voluptatem culpa quo. Nihil iusto odio quasi. Reiciendis perferendis et ab laboriosam. Natus explicabo et dolorem ex fugit.<br>\nExercitationem beatae omnis ut illum qui molestiae corrupti et. Eaque nihil nam est expedita non. Minus incidunt velit vel voluptatibus ut praesentium nisi. Pariatur atque aut qui.</p>', 'Et iusto aut tempore vel dicta quisquam dolor velit. Dolores quo veniam voluptatum impedit quisquam.\nCommodi voluptatem culpa quo. Nihil iusto odio quasi. Reiciendis perferendis et ab laboriosam. Natus explicabo et dolorem ex fugit.\nExercitationem beatae omnis ut illum qui molestiae corrupti et. Eaque nihil nam est expedita non. Minus incidunt velit vel voluptatibus ut praesentium nisi. Pariatur atque aut qui.', 2, 3, 7, 0, 0, 0, '2013-10-27 05:58:18', '2017-04-26 22:01:15'),
	(17, '<p>Facere dolor et voluptatem quos autem. Rem commodi non quasi voluptatem nisi. Excepturi et incidunt officia nemo maxime. Excepturi dolore vero ad sint molestiae qui neque. Veniam eaque eius architecto sequi.<br>\nEum sed odio suscipit vel voluptatem placeat. Officiis velit exercitationem sed quaerat voluptas asperiores consequatur. Non et eum aut ut doloremque neque qui. Pariatur eligendi quia unde ut veritatis voluptatem placeat. Culpa odio omnis beatae sed.</p>', 'Facere dolor et voluptatem quos autem. Rem commodi non quasi voluptatem nisi. Excepturi et incidunt officia nemo maxime. Excepturi dolore vero ad sint molestiae qui neque. Veniam eaque eius architecto sequi.\nEum sed odio suscipit vel voluptatem placeat. Officiis velit exercitationem sed quaerat voluptas asperiores consequatur. Non et eum aut ut doloremque neque qui. Pariatur eligendi quia unde ut veritatis voluptatem placeat. Culpa odio omnis beatae sed.', 2, 3, 8, 0, 0, 0, '2008-07-28 18:57:01', '2017-04-26 22:01:16'),
	(18, '<p>Ut aut fugiat est in. Quidem consequuntur totam neque cum. Et qui delectus culpa reprehenderit at accusantium provident assumenda. Ad optio eius tempora repellat aliquid.<br>\nIpsum earum commodi corrupti nihil. Commodi accusantium voluptates modi et. Consequatur aperiam atque illum.<br>\nReiciendis numquam saepe nostrum sit voluptas. Consequatur recusandae nisi atque sed ipsa qui. Deserunt veritatis sunt nostrum est a rerum ab. Ipsum minima eligendi eum officiis.</p>', 'Ut aut fugiat est in. Quidem consequuntur totam neque cum. Et qui delectus culpa reprehenderit at accusantium provident assumenda. Ad optio eius tempora repellat aliquid.\nIpsum earum commodi corrupti nihil. Commodi accusantium voluptates modi et. Consequatur aperiam atque illum.\nReiciendis numquam saepe nostrum sit voluptas. Consequatur recusandae nisi atque sed ipsa qui. Deserunt veritatis sunt nostrum est a rerum ab. Ipsum minima eligendi eum officiis.', 1, 3, 8, 0, 0, 0, '2011-06-25 04:22:09', '2017-04-26 22:01:16'),
	(19, '<p>Dolorum placeat et reiciendis autem minus possimus. Dolorem voluptas laudantium distinctio asperiores esse. Ea neque et officia voluptatibus voluptatem sit blanditiis. Soluta voluptatem et ex dolore praesentium consequatur vero.<br>\nSoluta placeat et cupiditate molestiae quas. Eligendi quaerat fugit distinctio ut nihil necessitatibus assumenda reprehenderit. A et esse et dolorem perferendis. Accusamus non odit inventore aliquid autem vitae officia.</p>', 'Dolorum placeat et reiciendis autem minus possimus. Dolorem voluptas laudantium distinctio asperiores esse. Ea neque et officia voluptatibus voluptatem sit blanditiis. Soluta voluptatem et ex dolore praesentium consequatur vero.\nSoluta placeat et cupiditate molestiae quas. Eligendi quaerat fugit distinctio ut nihil necessitatibus assumenda reprehenderit. A et esse et dolorem perferendis. Accusamus non odit inventore aliquid autem vitae officia.', 1, 3, 8, 0, 0, 0, '2016-11-19 16:22:07', '2017-04-26 22:01:16'),
	(20, '<p>Expedita harum nobis cumque unde facilis voluptatum commodi. Numquam id et placeat quis. Soluta et voluptatem rerum qui iusto dolor. Aperiam minima cum et.<br>\nDoloremque est rerum animi beatae et. Molestias nisi iure odio maiores.<br>\nEarum sit accusantium quia. Qui eius cum sint aut dolorum esse. Nisi atque aut eaque.<br>\nLaudantium laborum modi nostrum quia itaque. Est voluptatem ea dolorem distinctio sint. Quis ipsum officia amet corrupti alias.</p>', 'Expedita harum nobis cumque unde facilis voluptatum commodi. Numquam id et placeat quis. Soluta et voluptatem rerum qui iusto dolor. Aperiam minima cum et.\nDoloremque est rerum animi beatae et. Molestias nisi iure odio maiores.\nEarum sit accusantium quia. Qui eius cum sint aut dolorum esse. Nisi atque aut eaque.\nLaudantium laborum modi nostrum quia itaque. Est voluptatem ea dolorem distinctio sint. Quis ipsum officia amet corrupti alias.', 3, 3, 9, 0, 0, 0, '2010-11-16 13:47:27', '2017-04-26 22:01:16'),
	(21, '<p>Officia ut quo explicabo eius eos. Provident aliquid quaerat mollitia non magnam. Minima ducimus facilis quia nulla sit.<br>\nNihil dolor placeat provident. Delectus labore sint libero aliquam distinctio nostrum omnis. Pariatur nam enim dolor quasi soluta ducimus et. Culpa qui laboriosam esse.<br>\nQuam maxime error quod in minima. Aut eum id expedita suscipit. Maiores quas aut ut quaerat laboriosam mollitia. Unde hic consequuntur qui fuga et aliquid blanditiis.</p>', 'Officia ut quo explicabo eius eos. Provident aliquid quaerat mollitia non magnam. Minima ducimus facilis quia nulla sit.\nNihil dolor placeat provident. Delectus labore sint libero aliquam distinctio nostrum omnis. Pariatur nam enim dolor quasi soluta ducimus et. Culpa qui laboriosam esse.\nQuam maxime error quod in minima. Aut eum id expedita suscipit. Maiores quas aut ut quaerat laboriosam mollitia. Unde hic consequuntur qui fuga et aliquid blanditiis.', 3, 3, 10, 0, 0, 0, '2012-01-15 17:17:37', '2017-04-26 22:01:16'),
	(22, '<p>Voluptatum officia explicabo corrupti dolorum. Praesentium maiores iure nihil in vel. Quam earum quis id cupiditate provident. Quis velit et quae quas voluptas.<br>\nSunt optio non velit nihil qui qui totam dolor. Natus fugiat voluptatibus repellendus incidunt explicabo sed. Et voluptatem veritatis consequuntur odio.<br>\nNumquam praesentium dolores ratione veritatis repellat. Aliquam repellat dolor quod rerum minima. Natus autem dolor ea et consequatur accusamus.</p>', 'Voluptatum officia explicabo corrupti dolorum. Praesentium maiores iure nihil in vel. Quam earum quis id cupiditate provident. Quis velit et quae quas voluptas.\nSunt optio non velit nihil qui qui totam dolor. Natus fugiat voluptatibus repellendus incidunt explicabo sed. Et voluptatem veritatis consequuntur odio.\nNumquam praesentium dolores ratione veritatis repellat. Aliquam repellat dolor quod rerum minima. Natus autem dolor ea et consequatur accusamus.', 1, 3, 10, 0, 0, 0, '2010-03-21 05:34:38', '2017-04-26 22:01:16'),
	(23, '<p>Labore consectetur eum est doloremque iusto quisquam earum sed. Qui nisi similique soluta at inventore ea. Laudantium voluptates iusto distinctio exercitationem animi at at.<br>\nDoloremque laboriosam sit recusandae rerum. Sed nihil ut voluptatem suscipit. Hic autem sint qui dolorem qui a dolorum. Est aspernatur aut sed reprehenderit id.<br>\nCupiditate excepturi consequatur recusandae qui. Placeat alias quis vel eos maiores. Eos accusantium distinctio eum qui explicabo laborum.</p>', 'Labore consectetur eum est doloremque iusto quisquam earum sed. Qui nisi similique soluta at inventore ea. Laudantium voluptates iusto distinctio exercitationem animi at at.\nDoloremque laboriosam sit recusandae rerum. Sed nihil ut voluptatem suscipit. Hic autem sint qui dolorem qui a dolorum. Est aspernatur aut sed reprehenderit id.\nCupiditate excepturi consequatur recusandae qui. Placeat alias quis vel eos maiores. Eos accusantium distinctio eum qui explicabo laborum.', 3, 3, 10, 0, 0, 0, '2012-10-03 17:22:21', '2017-04-26 22:01:16'),
	(24, '<p>Nesciunt accusantium et laborum labore. Fugit modi omnis voluptatum at necessitatibus. Ut molestias porro consequatur id voluptas eum quisquam ad. Doloremque doloribus eum voluptas expedita dicta voluptatibus.<br>\nUt cum sunt ullam corporis eius et. Dolore quasi eos accusamus quia maiores praesentium non. Et animi quod reiciendis voluptas a.<br>\nConsequuntur pariatur quisquam provident. Veritatis tenetur harum eum. Voluptatum et accusamus officiis nihil.</p>', 'Nesciunt accusantium et laborum labore. Fugit modi omnis voluptatum at necessitatibus. Ut molestias porro consequatur id voluptas eum quisquam ad. Doloremque doloribus eum voluptas expedita dicta voluptatibus.\nUt cum sunt ullam corporis eius et. Dolore quasi eos accusamus quia maiores praesentium non. Et animi quod reiciendis voluptas a.\nConsequuntur pariatur quisquam provident. Veritatis tenetur harum eum. Voluptatum et accusamus officiis nihil.', 2, 5, 11, 0, 0, 0, '2012-11-21 05:00:35', '2017-04-26 22:01:19'),
	(25, '<p>Consequuntur aspernatur perspiciatis culpa saepe dolorem. Nulla laudantium voluptas perferendis voluptatibus sint mollitia sed. Reiciendis voluptate ad nulla consequatur.<br>\nQui iure eveniet qui doloremque velit molestias. Dignissimos eveniet impedit nobis sapiente molestiae. Et velit rerum tempore.<br>\nSit fugiat quis ut. Quasi perspiciatis aut fugiat aut. Molestiae dolorem velit expedita porro repudiandae sunt quia. Quas suscipit voluptatem rem.</p>', 'Consequuntur aspernatur perspiciatis culpa saepe dolorem. Nulla laudantium voluptas perferendis voluptatibus sint mollitia sed. Reiciendis voluptate ad nulla consequatur.\nQui iure eveniet qui doloremque velit molestias. Dignissimos eveniet impedit nobis sapiente molestiae. Et velit rerum tempore.\nSit fugiat quis ut. Quasi perspiciatis aut fugiat aut. Molestiae dolorem velit expedita porro repudiandae sunt quia. Quas suscipit voluptatem rem.', 2, 5, 11, 0, 0, 0, '2012-08-08 11:38:42', '2017-04-26 22:01:19'),
	(26, '<p>Sint illo dolor ea impedit nesciunt hic laudantium. Expedita mollitia maiores qui porro incidunt et. Iusto rerum enim ut est. Cupiditate quas ab et id tenetur.<br>\nAliquid ab adipisci id velit eum. Consequatur placeat quasi aut magni autem. Consectetur magni enim eveniet corrupti amet eos. Autem porro nesciunt fugiat nihil id laborum possimus.<br>\nVelit ipsum laborum odit. Consequuntur aut a asperiores cum doloribus. Et non corrupti quia fugit.</p>', 'Sint illo dolor ea impedit nesciunt hic laudantium. Expedita mollitia maiores qui porro incidunt et. Iusto rerum enim ut est. Cupiditate quas ab et id tenetur.\nAliquid ab adipisci id velit eum. Consequatur placeat quasi aut magni autem. Consectetur magni enim eveniet corrupti amet eos. Autem porro nesciunt fugiat nihil id laborum possimus.\nVelit ipsum laborum odit. Consequuntur aut a asperiores cum doloribus. Et non corrupti quia fugit.', 3, 6, 13, 0, 0, 0, '2015-12-22 15:46:16', '2017-04-26 22:01:21'),
	(27, '<p>Saepe autem ea accusamus ad. Qui molestiae possimus quo ducimus adipisci. Dolor pariatur qui nobis molestias. Quibusdam autem exercitationem harum vel quidem consequatur rerum.<br>\nVoluptatem quas aut vitae consequatur magnam fuga ipsam. Sint rem soluta et sed quis excepturi. Neque illo sit quisquam error voluptatum debitis qui est. Non error numquam cum tempore ipsum molestias et.</p>', 'Saepe autem ea accusamus ad. Qui molestiae possimus quo ducimus adipisci. Dolor pariatur qui nobis molestias. Quibusdam autem exercitationem harum vel quidem consequatur rerum.\nVoluptatem quas aut vitae consequatur magnam fuga ipsam. Sint rem soluta et sed quis excepturi. Neque illo sit quisquam error voluptatum debitis qui est. Non error numquam cum tempore ipsum molestias et.', 2, 6, 13, 0, 0, 0, '2008-08-22 02:04:13', '2017-04-26 22:01:21'),
	(28, '<p>Distinctio minus praesentium veritatis quasi fugiat. Cupiditate veniam aliquid dolor necessitatibus in aut deserunt. Dolores quas fugit dignissimos odit ut et. Nihil magnam eos numquam facere nam adipisci maxime.<br>\nMaiores dolorem consequatur voluptatem ut vel ut ut. Quis officiis doloribus repellendus rerum culpa sed. Occaecati itaque quae quia eius cumque ab.<br>\nAut rerum quas molestias voluptate. Alias autem eos perferendis. Dolorum blanditiis magnam ipsa.</p>', 'Distinctio minus praesentium veritatis quasi fugiat. Cupiditate veniam aliquid dolor necessitatibus in aut deserunt. Dolores quas fugit dignissimos odit ut et. Nihil magnam eos numquam facere nam adipisci maxime.\nMaiores dolorem consequatur voluptatem ut vel ut ut. Quis officiis doloribus repellendus rerum culpa sed. Occaecati itaque quae quia eius cumque ab.\nAut rerum quas molestias voluptate. Alias autem eos perferendis. Dolorum blanditiis magnam ipsa.', 5, 6, 13, 0, 0, 0, '2008-10-12 02:50:32', '2017-04-26 22:01:21'),
	(29, '<p>Et distinctio quas quas ut earum cum. Vel tempora eos et ut quo officiis vitae quas. Eveniet at provident porro sint nemo autem ex ratione.<br>\nDelectus autem quaerat aperiam dolorum repudiandae nihil facere aliquam. Repellat est architecto qui.<br>\nDolores temporibus facilis ratione consequuntur sit dolorem consectetur. Vitae voluptatem quisquam qui eos omnis sapiente. Quibusdam molestiae repudiandae sit. Delectus officia asperiores dolorem perspiciatis voluptatum sequi expedita.</p>', 'Et distinctio quas quas ut earum cum. Vel tempora eos et ut quo officiis vitae quas. Eveniet at provident porro sint nemo autem ex ratione.\nDelectus autem quaerat aperiam dolorum repudiandae nihil facere aliquam. Repellat est architecto qui.\nDolores temporibus facilis ratione consequuntur sit dolorem consectetur. Vitae voluptatem quisquam qui eos omnis sapiente. Quibusdam molestiae repudiandae sit. Delectus officia asperiores dolorem perspiciatis voluptatum sequi expedita.', 1, 6, 14, 0, 0, 0, '2012-10-25 07:19:33', '2017-04-26 22:01:21'),
	(30, '<p>Quaerat quis optio doloribus inventore voluptate. Fugit facere exercitationem maxime alias. Et consectetur aut soluta quam sint eaque consequuntur consequuntur.<br>\nIllum perspiciatis fuga suscipit odio doloremque omnis et ut. Sed est hic aut et modi non autem qui. Tempore non tenetur cum dignissimos debitis quidem incidunt. Molestiae sunt enim non recusandae.</p>', 'Quaerat quis optio doloribus inventore voluptate. Fugit facere exercitationem maxime alias. Et consectetur aut soluta quam sint eaque consequuntur consequuntur.\nIllum perspiciatis fuga suscipit odio doloremque omnis et ut. Sed est hic aut et modi non autem qui. Tempore non tenetur cum dignissimos debitis quidem incidunt. Molestiae sunt enim non recusandae.', 4, 6, 14, 0, 0, 0, '2016-09-22 14:30:37', '2017-04-26 22:01:21'),
	(31, '<p>Et magnam consequatur rem. Tempore ipsum eos consectetur qui deserunt non aspernatur. Voluptatum esse non ut voluptas.<br>\nQuaerat qui natus qui quos qui et. Est commodi cum corporis enim id. Cum quo eligendi expedita consequatur alias dolores neque. Eum quidem ut sed voluptatem.<br>\nEum labore eum sunt iusto suscipit. Deleniti in laboriosam recusandae sint veritatis voluptates est.<br>\nNemo distinctio et nam. Similique ea ab et sapiente vel. Perspiciatis in non consectetur in. Officiis dicta et deleniti in.</p>', 'Et magnam consequatur rem. Tempore ipsum eos consectetur qui deserunt non aspernatur. Voluptatum esse non ut voluptas.\nQuaerat qui natus qui quos qui et. Est commodi cum corporis enim id. Cum quo eligendi expedita consequatur alias dolores neque. Eum quidem ut sed voluptatem.\nEum labore eum sunt iusto suscipit. Deleniti in laboriosam recusandae sint veritatis voluptates est.\nNemo distinctio et nam. Similique ea ab et sapiente vel. Perspiciatis in non consectetur in. Officiis dicta et deleniti in.', 5, 6, 15, 0, 0, 0, '2015-10-31 16:32:24', '2017-04-26 22:01:21'),
	(32, '<p>Id tempora itaque repellendus doloribus voluptatem quae possimus et. Et fugiat laboriosam sint alias facere rerum quo. Sit at voluptas et velit aut et. Voluptas eaque ut nihil aut.<br>\nOccaecati sapiente nemo voluptate sit quasi voluptas. Eveniet nam illo saepe itaque tempore et blanditiis. Quia minus sint rerum consequatur maiores dolores. Aut aspernatur alias tempore quibusdam libero impedit.<br>\nQuia aut laudantium incidunt numquam voluptate expedita. Qui corrupti ipsam rerum iste quidem.</p>', 'Id tempora itaque repellendus doloribus voluptatem quae possimus et. Et fugiat laboriosam sint alias facere rerum quo. Sit at voluptas et velit aut et. Voluptas eaque ut nihil aut.\nOccaecati sapiente nemo voluptate sit quasi voluptas. Eveniet nam illo saepe itaque tempore et blanditiis. Quia minus sint rerum consequatur maiores dolores. Aut aspernatur alias tempore quibusdam libero impedit.\nQuia aut laudantium incidunt numquam voluptate expedita. Qui corrupti ipsam rerum iste quidem.', 2, 6, 15, 0, 0, 0, '2015-03-23 07:16:42', '2017-04-26 22:01:21'),
	(33, '<p>Dignissimos reprehenderit et omnis maxime vero dicta modi. Id voluptatibus corrupti praesentium nobis.<br>\nCulpa dolor et sed accusantium quos aliquam. Occaecati non sunt occaecati dolor voluptate nihil. Rerum numquam velit ratione tempore error.<br>\nSequi voluptatem porro quidem qui sapiente. Omnis dolorem est voluptatibus. Soluta minima autem voluptatum et sequi.</p>', 'Dignissimos reprehenderit et omnis maxime vero dicta modi. Id voluptatibus corrupti praesentium nobis.\nCulpa dolor et sed accusantium quos aliquam. Occaecati non sunt occaecati dolor voluptate nihil. Rerum numquam velit ratione tempore error.\nSequi voluptatem porro quidem qui sapiente. Omnis dolorem est voluptatibus. Soluta minima autem voluptatum et sequi.', 4, 6, 16, 0, 0, 0, '2009-05-18 20:20:14', '2017-04-26 22:01:22'),
	(34, '<p>Labore et est odit. Quo alias voluptatem officia perspiciatis amet aliquam unde. In blanditiis accusantium soluta est unde sit dolor.<br>\nPorro ad in ipsa officiis et. Quia qui est placeat molestias aut et. At nam quod id repudiandae qui.<br>\nConsectetur est sunt qui deleniti debitis. Fugiat amet dolores error debitis. Ea exercitationem nihil ad neque. Voluptatibus vel consequatur magni aut alias.</p>', 'Labore et est odit. Quo alias voluptatem officia perspiciatis amet aliquam unde. In blanditiis accusantium soluta est unde sit dolor.\nPorro ad in ipsa officiis et. Quia qui est placeat molestias aut et. At nam quod id repudiandae qui.\nConsectetur est sunt qui deleniti debitis. Fugiat amet dolores error debitis. Ea exercitationem nihil ad neque. Voluptatibus vel consequatur magni aut alias.', 3, 6, 16, 0, 0, 0, '2012-10-15 00:16:28', '2017-04-26 22:01:22'),
	(35, '<p>Explicabo et animi occaecati est magnam. Nam quidem minima quia dolor molestiae non amet quia. Rerum amet quos debitis aliquid aut. Et ducimus qui repellendus rem delectus saepe.<br>\nQuisquam ducimus aut ipsam quibusdam qui sed tempore pariatur. Voluptate voluptas consectetur quia recusandae itaque molestias.<br>\nVeniam earum quas et facere dolor vitae. Totam provident quisquam esse error dolorem maiores. Sit repudiandae deleniti hic.</p>', 'Explicabo et animi occaecati est magnam. Nam quidem minima quia dolor molestiae non amet quia. Rerum amet quos debitis aliquid aut. Et ducimus qui repellendus rem delectus saepe.\nQuisquam ducimus aut ipsam quibusdam qui sed tempore pariatur. Voluptate voluptas consectetur quia recusandae itaque molestias.\nVeniam earum quas et facere dolor vitae. Totam provident quisquam esse error dolorem maiores. Sit repudiandae deleniti hic.', 3, 6, 16, 0, 0, 0, '2016-02-02 03:38:40', '2017-04-26 22:01:22'),
	(36, '<p>Reprehenderit assumenda soluta mollitia aperiam quas itaque facilis suscipit. Nisi itaque exercitationem vel rerum rerum dolorem molestiae. Quia aut magni ut doloribus culpa. Repudiandae autem voluptate suscipit et suscipit illo.<br>\nVel magnam nihil est. Ad voluptate adipisci quis facilis. Reprehenderit dolorem porro illo est.</p>', 'Reprehenderit assumenda soluta mollitia aperiam quas itaque facilis suscipit. Nisi itaque exercitationem vel rerum rerum dolorem molestiae. Quia aut magni ut doloribus culpa. Repudiandae autem voluptate suscipit et suscipit illo.\nVel magnam nihil est. Ad voluptate adipisci quis facilis. Reprehenderit dolorem porro illo est.', 5, 6, 17, 0, 0, 0, '2010-09-20 06:46:55', '2017-04-26 22:01:24'),
	(37, '<p>Aliquid dolor officiis non illum nobis enim voluptate. Non ipsa aut quis et ratione. Molestiae et quam eos itaque non hic quos. Itaque aspernatur autem impedit voluptatem eos.<br>\nAt vel laborum ad ea ut. Deserunt voluptas tempore illo soluta numquam vero sit. Quam maxime velit corporis sit.<br>\nPlaceat omnis incidunt aut. Enim blanditiis eum sunt vero veniam. Dolorem qui illo ipsum illum non. Nihil consequatur et harum tempore accusamus.</p>', 'Aliquid dolor officiis non illum nobis enim voluptate. Non ipsa aut quis et ratione. Molestiae et quam eos itaque non hic quos. Itaque aspernatur autem impedit voluptatem eos.\nAt vel laborum ad ea ut. Deserunt voluptas tempore illo soluta numquam vero sit. Quam maxime velit corporis sit.\nPlaceat omnis incidunt aut. Enim blanditiis eum sunt vero veniam. Dolorem qui illo ipsum illum non. Nihil consequatur et harum tempore accusamus.', 2, 6, 18, 0, 0, 0, '2013-01-05 00:45:34', '2017-04-26 22:01:24'),
	(38, '<p>Fuga qui veniam omnis corporis. Dolor minima perspiciatis maxime non dignissimos dolore. Aliquid consectetur cupiditate aut ut. Aut mollitia molestiae pariatur. Sapiente magnam et modi reprehenderit culpa enim.<br>\nDoloremque et consequatur quisquam voluptatem accusantium. Commodi ut id voluptate dolor sed. Reprehenderit labore nulla in accusantium.</p>', 'Fuga qui veniam omnis corporis. Dolor minima perspiciatis maxime non dignissimos dolore. Aliquid consectetur cupiditate aut ut. Aut mollitia molestiae pariatur. Sapiente magnam et modi reprehenderit culpa enim.\nDoloremque et consequatur quisquam voluptatem accusantium. Commodi ut id voluptate dolor sed. Reprehenderit labore nulla in accusantium.', 3, 7, 21, 0, 0, 0, '2015-10-28 13:16:02', '2017-04-26 22:01:28'),
	(39, '<p>Velit sint sunt voluptatem delectus excepturi quam. Voluptas deleniti doloremque velit optio ut. Velit consequuntur sequi suscipit aspernatur fugit sit laboriosam.<br>\nLaborum voluptatum facilis similique impedit aut eum dignissimos. Aut voluptas quas sint ex. Assumenda et ullam aut sint tempore sunt. Praesentium vero dolorum ex et.<br>\nDolore officiis rerum doloribus. Qui esse quaerat sed incidunt. Quo voluptatem consequatur ut aut excepturi voluptas. Rerum dolor eum similique non.</p>', 'Velit sint sunt voluptatem delectus excepturi quam. Voluptas deleniti doloremque velit optio ut. Velit consequuntur sequi suscipit aspernatur fugit sit laboriosam.\nLaborum voluptatum facilis similique impedit aut eum dignissimos. Aut voluptas quas sint ex. Assumenda et ullam aut sint tempore sunt. Praesentium vero dolorum ex et.\nDolore officiis rerum doloribus. Qui esse quaerat sed incidunt. Quo voluptatem consequatur ut aut excepturi voluptas. Rerum dolor eum similique non.', 6, 7, 21, 0, 0, 0, '2014-09-17 02:25:28', '2017-04-26 22:01:28'),
	(40, '<p>Maxime non fugit qui incidunt ut accusantium eaque. Ut voluptas fugit alias beatae dignissimos voluptas assumenda. Et magnam ad et dolorem sint deleniti eligendi.<br>\nTempore temporibus blanditiis totam ut porro quasi. Deleniti debitis omnis facilis est et laboriosam. Est sed deserunt aspernatur eos nihil in consequuntur. Et eligendi enim repudiandae ut aliquam consequuntur.</p>', 'Maxime non fugit qui incidunt ut accusantium eaque. Ut voluptas fugit alias beatae dignissimos voluptas assumenda. Et magnam ad et dolorem sint deleniti eligendi.\nTempore temporibus blanditiis totam ut porro quasi. Deleniti debitis omnis facilis est et laboriosam. Est sed deserunt aspernatur eos nihil in consequuntur. Et eligendi enim repudiandae ut aliquam consequuntur.', 6, 7, 22, 0, 0, 0, '2012-11-23 05:06:16', '2017-04-26 22:01:28'),
	(41, '<p>Ducimus necessitatibus vitae rerum rerum quaerat delectus provident. Numquam praesentium exercitationem dolorum quos dolorum necessitatibus consequuntur.<br>\nEa recusandae sit porro commodi deleniti quo. Ad repudiandae eos et quibusdam et dolor est. Quasi voluptatem totam ut suscipit.<br>\nTemporibus ipsa iste hic dolorum occaecati deserunt. Quod et at excepturi dolor eos et excepturi voluptate. Ut nam quaerat aperiam et.</p>', 'Ducimus necessitatibus vitae rerum rerum quaerat delectus provident. Numquam praesentium exercitationem dolorum quos dolorum necessitatibus consequuntur.\nEa recusandae sit porro commodi deleniti quo. Ad repudiandae eos et quibusdam et dolor est. Quasi voluptatem totam ut suscipit.\nTemporibus ipsa iste hic dolorum occaecati deserunt. Quod et at excepturi dolor eos et excepturi voluptate. Ut nam quaerat aperiam et.', 1, 7, 22, 0, 0, 0, '2011-03-18 02:04:17', '2017-04-26 22:01:28'),
	(42, '<p>Et sed neque alias ad quia praesentium. Explicabo consequatur qui est laboriosam. Aut nisi voluptas distinctio rerum adipisci iusto. Quia soluta voluptatibus voluptatibus neque quia.<br>\nVeniam quibusdam eum ratione aut atque unde veniam. Expedita voluptatem voluptas quo eos quia omnis. Sit et eius ea id corrupti at repellat et. Nam possimus incidunt iusto tenetur natus. Sed libero et aut rerum sint.</p>', 'Et sed neque alias ad quia praesentium. Explicabo consequatur qui est laboriosam. Aut nisi voluptas distinctio rerum adipisci iusto. Quia soluta voluptatibus voluptatibus neque quia.\nVeniam quibusdam eum ratione aut atque unde veniam. Expedita voluptatem voluptas quo eos quia omnis. Sit et eius ea id corrupti at repellat et. Nam possimus incidunt iusto tenetur natus. Sed libero et aut rerum sint.', 5, 7, 22, 0, 0, 0, '2007-12-26 07:36:32', '2017-04-26 22:01:28'),
	(43, '<p>Et ipsum est velit aut. Voluptatem fuga asperiores laboriosam totam amet est sint. Et et odit enim quibusdam.<br>\nDoloremque fugit officiis quia eum ipsa. Dicta illum unde in aperiam quia eum accusamus.<br>\nEt cum cumque eveniet quo. Quo qui excepturi at. Suscipit porro neque neque dolores similique aut doloribus. Suscipit quia est sapiente quae repellendus fuga vitae.<br>\nPariatur ipsum tempora voluptas tenetur. Architecto sed est inventore facere est quia. Doloremque quia eius totam.</p>', 'Et ipsum est velit aut. Voluptatem fuga asperiores laboriosam totam amet est sint. Et et odit enim quibusdam.\nDoloremque fugit officiis quia eum ipsa. Dicta illum unde in aperiam quia eum accusamus.\nEt cum cumque eveniet quo. Quo qui excepturi at. Suscipit porro neque neque dolores similique aut doloribus. Suscipit quia est sapiente quae repellendus fuga vitae.\nPariatur ipsum tempora voluptas tenetur. Architecto sed est inventore facere est quia. Doloremque quia eius totam.', 1, 7, 24, 0, 0, 0, '2009-01-31 22:16:32', '2017-04-26 22:01:30'),
	(44, '<p>Alias dolorem nesciunt qui vero. Qui nesciunt mollitia qui eum tempora. Unde consequuntur quos dolorem doloribus et dolorem. Qui voluptate harum facilis ut.<br>\nOmnis non doloremque non. Accusantium nam illum delectus cumque. Atque voluptatem pariatur magnam esse consequatur illum consectetur eaque.<br>\nAdipisci inventore ut velit ducimus molestias beatae laboriosam quia. Ipsum omnis et rerum. Laudantium minus qui autem eius possimus ut debitis impedit. Eligendi animi ducimus placeat.</p>', 'Alias dolorem nesciunt qui vero. Qui nesciunt mollitia qui eum tempora. Unde consequuntur quos dolorem doloribus et dolorem. Qui voluptate harum facilis ut.\nOmnis non doloremque non. Accusantium nam illum delectus cumque. Atque voluptatem pariatur magnam esse consequatur illum consectetur eaque.\nAdipisci inventore ut velit ducimus molestias beatae laboriosam quia. Ipsum omnis et rerum. Laudantium minus qui autem eius possimus ut debitis impedit. Eligendi animi ducimus placeat.', 4, 7, 24, 0, 0, 0, '2011-04-25 09:19:56', '2017-04-26 22:01:30'),
	(45, '<p>Quis voluptas ducimus sunt non. Et velit et consectetur voluptatum ea saepe dignissimos. Ducimus harum alias recusandae ea sed praesentium. Dolorum voluptas repellat ut rerum illum deserunt sed.<br>\nUt architecto quis id tempora tenetur. Autem vel eum ipsum mollitia est commodi et. Autem eum blanditiis ut perferendis laboriosam.<br>\nUt voluptates at soluta magni illum enim. Dolor exercitationem porro labore qui et.</p>', 'Quis voluptas ducimus sunt non. Et velit et consectetur voluptatum ea saepe dignissimos. Ducimus harum alias recusandae ea sed praesentium. Dolorum voluptas repellat ut rerum illum deserunt sed.\nUt architecto quis id tempora tenetur. Autem vel eum ipsum mollitia est commodi et. Autem eum blanditiis ut perferendis laboriosam.\nUt voluptates at soluta magni illum enim. Dolor exercitationem porro labore qui et.', 2, 7, 24, 0, 0, 0, '2013-03-02 16:16:43', '2017-04-26 22:01:30'),
	(46, '<p>Tempora est eos ipsam. Recusandae hic aut aut perspiciatis. Sed et corporis est qui quasi cupiditate. Animi velit qui optio dolorem officia. Itaque iste aut fuga iste distinctio iste.<br>\nDolor qui autem nihil vel. Id magni ut exercitationem quam quaerat vero inventore. Rem odit eos consequatur.<br>\nAperiam qui rerum unde quo voluptas. Ullam fugit optio beatae repudiandae odio placeat facilis. Et iusto qui qui et eum debitis. Doloremque et quas ut voluptatem.</p>', 'Tempora est eos ipsam. Recusandae hic aut aut perspiciatis. Sed et corporis est qui quasi cupiditate. Animi velit qui optio dolorem officia. Itaque iste aut fuga iste distinctio iste.\nDolor qui autem nihil vel. Id magni ut exercitationem quam quaerat vero inventore. Rem odit eos consequatur.\nAperiam qui rerum unde quo voluptas. Ullam fugit optio beatae repudiandae odio placeat facilis. Et iusto qui qui et eum debitis. Doloremque et quas ut voluptatem.', 3, 7, 25, 0, 0, 0, '2011-11-04 08:44:54', '2017-04-26 22:01:31'),
	(47, '<p>Quisquam aut consequuntur at est voluptatem. In dolores magni officia ipsam. Qui autem maxime nihil consequatur accusantium atque.<br>\nSuscipit nihil nihil qui quibusdam aut. Sit nulla nulla voluptatem id blanditiis et.<br>\nEt totam ducimus sint atque officiis harum. Aut culpa ex omnis rerum eaque rerum. Beatae eos quis molestias repudiandae. Magni et ea harum cupiditate omnis.<br>\nVelit sint fugit excepturi omnis velit ducimus quis. Doloribus inventore assumenda eaque aliquam unde at consequatur.</p>', 'Quisquam aut consequuntur at est voluptatem. In dolores magni officia ipsam. Qui autem maxime nihil consequatur accusantium atque.\nSuscipit nihil nihil qui quibusdam aut. Sit nulla nulla voluptatem id blanditiis et.\nEt totam ducimus sint atque officiis harum. Aut culpa ex omnis rerum eaque rerum. Beatae eos quis molestias repudiandae. Magni et ea harum cupiditate omnis.\nVelit sint fugit excepturi omnis velit ducimus quis. Doloribus inventore assumenda eaque aliquam unde at consequatur.', 3, 7, 26, 0, 0, 0, '2010-07-20 01:12:38', '2017-04-26 22:01:31'),
	(48, '<p>Accusantium cumque fuga ea consequuntur eaque nostrum aliquid et. Est est ut est similique perspiciatis. Quo eum et excepturi expedita iste ea repellendus. Quod assumenda voluptatem nulla quo sunt vero.<br>\nVoluptates aperiam ut est quasi qui eum. Et sed consequuntur asperiores sint itaque rerum. Nulla alias vero tempore rem. Quidem nihil fuga doloribus reiciendis dignissimos autem quos.<br>\nOdit ut rem vero quo. Vel asperiores consequatur quos aut officiis. Sunt rerum repudiandae non.</p>', 'Accusantium cumque fuga ea consequuntur eaque nostrum aliquid et. Est est ut est similique perspiciatis. Quo eum et excepturi expedita iste ea repellendus. Quod assumenda voluptatem nulla quo sunt vero.\nVoluptates aperiam ut est quasi qui eum. Et sed consequuntur asperiores sint itaque rerum. Nulla alias vero tempore rem. Quidem nihil fuga doloribus reiciendis dignissimos autem quos.\nOdit ut rem vero quo. Vel asperiores consequatur quos aut officiis. Sunt rerum repudiandae non.', 2, 7, 26, 0, 0, 0, '2013-09-19 14:19:12', '2017-04-26 22:01:31'),
	(49, '<p>Alias omnis eaque esse nemo tempora. Voluptas voluptatem doloremque optio nisi nihil.<br>\nQuas quis fugiat distinctio tempora. Sequi perferendis sequi saepe atque qui temporibus. Omnis assumenda nam eos deleniti nesciunt aliquam quae dolore. Dolorem cumque similique est aliquam perferendis nisi.<br>\nSed rerum aut dolorem quod blanditiis minima. Iure incidunt fugiat est ratione et necessitatibus non odio. Officia veritatis laborum tenetur assumenda blanditiis aspernatur praesentium minus.</p>', 'Alias omnis eaque esse nemo tempora. Voluptas voluptatem doloremque optio nisi nihil.\nQuas quis fugiat distinctio tempora. Sequi perferendis sequi saepe atque qui temporibus. Omnis assumenda nam eos deleniti nesciunt aliquam quae dolore. Dolorem cumque similique est aliquam perferendis nisi.\nSed rerum aut dolorem quod blanditiis minima. Iure incidunt fugiat est ratione et necessitatibus non odio. Officia veritatis laborum tenetur assumenda blanditiis aspernatur praesentium minus.', 1, 7, 26, 0, 0, 0, '2016-08-17 20:52:29', '2017-04-26 22:01:31'),
	(50, '<p>Optio voluptate ducimus id. Aliquid aut non iure ut sunt velit. Quos est voluptatum architecto voluptatem quas et. Temporibus accusantium velit dolorum totam dolores commodi dolor.<br>\nRatione voluptas est veritatis omnis earum voluptatum sed qui. Et minus aut ipsam. Excepturi dolor ut vero ut iure. Ratione rerum saepe quia voluptates mollitia.<br>\nSunt culpa saepe hic quam dolores porro ut. Delectus amet voluptate error laboriosam alias. In suscipit consectetur est unde quam iure. Et quo aut sapiente earum.</p>', 'Optio voluptate ducimus id. Aliquid aut non iure ut sunt velit. Quos est voluptatum architecto voluptatem quas et. Temporibus accusantium velit dolorum totam dolores commodi dolor.\nRatione voluptas est veritatis omnis earum voluptatum sed qui. Et minus aut ipsam. Excepturi dolor ut vero ut iure. Ratione rerum saepe quia voluptates mollitia.\nSunt culpa saepe hic quam dolores porro ut. Delectus amet voluptate error laboriosam alias. In suscipit consectetur est unde quam iure. Et quo aut sapiente earum.', 1, 8, 28, 0, 0, 0, '2012-01-31 03:03:53', '2017-04-26 22:01:33'),
	(51, '<p>Sint sit reprehenderit et veniam. Esse veritatis qui saepe cum et voluptas. Assumenda ab nemo laboriosam dolores exercitationem quia. Exercitationem facere dolorem illum consectetur fuga eaque.<br>\nFugit illo voluptatem velit omnis nemo rerum. Ut error suscipit culpa et. Hic vel sit qui aperiam dolor aut fugit nihil.<br>\nSoluta quis perferendis vel. Et deserunt culpa eos qui ratione. Quidem voluptatem fugit magnam dolorem.</p>', 'Sint sit reprehenderit et veniam. Esse veritatis qui saepe cum et voluptas. Assumenda ab nemo laboriosam dolores exercitationem quia. Exercitationem facere dolorem illum consectetur fuga eaque.\nFugit illo voluptatem velit omnis nemo rerum. Ut error suscipit culpa et. Hic vel sit qui aperiam dolor aut fugit nihil.\nSoluta quis perferendis vel. Et deserunt culpa eos qui ratione. Quidem voluptatem fugit magnam dolorem.', 4, 8, 29, 0, 0, 0, '2012-08-30 15:06:18', '2017-04-26 22:01:34'),
	(52, '<p>Beatae repellendus eum quibusdam labore rerum. Corrupti et minus nostrum veniam sit velit fugit. Sit aut perferendis nesciunt ut dolorum exercitationem et. Harum vel tenetur adipisci occaecati quae tempore qui.<br>\nEius est aperiam necessitatibus voluptate quis nisi aut. Voluptatum quod qui ad ullam nostrum. Molestiae dignissimos qui dolorem et. Veritatis porro mollitia sit aut id.</p>', 'Beatae repellendus eum quibusdam labore rerum. Corrupti et minus nostrum veniam sit velit fugit. Sit aut perferendis nesciunt ut dolorum exercitationem et. Harum vel tenetur adipisci occaecati quae tempore qui.\nEius est aperiam necessitatibus voluptate quis nisi aut. Voluptatum quod qui ad ullam nostrum. Molestiae dignissimos qui dolorem et. Veritatis porro mollitia sit aut id.', 2, 8, 29, 0, 0, 0, '2010-04-16 01:33:33', '2017-04-26 22:01:34'),
	(53, '<p>Dicta nemo animi voluptas laboriosam dolore harum voluptatem. Qui tempore hic hic id qui. Perferendis consequatur aliquam iure harum. Eos inventore harum non eius quas aliquid dolorum quas.<br>\nPerferendis ipsum et totam accusamus et mollitia. Fugit et nisi sunt et debitis accusamus. Quia natus ipsam amet sit. Atque dolor omnis qui quod quo.<br>\nDelectus illum iusto tempora tenetur. Fugiat at aut modi. Quo consequatur iusto provident ea.</p>', 'Dicta nemo animi voluptas laboriosam dolore harum voluptatem. Qui tempore hic hic id qui. Perferendis consequatur aliquam iure harum. Eos inventore harum non eius quas aliquid dolorum quas.\nPerferendis ipsum et totam accusamus et mollitia. Fugit et nisi sunt et debitis accusamus. Quia natus ipsam amet sit. Atque dolor omnis qui quod quo.\nDelectus illum iusto tempora tenetur. Fugiat at aut modi. Quo consequatur iusto provident ea.', 6, 8, 29, 0, 0, 0, '2009-10-26 13:03:58', '2017-04-26 22:01:34'),
	(54, '<p>Sed fugit neque est voluptas distinctio eum enim eaque. Quae deserunt cumque qui exercitationem alias sunt quia adipisci. Ipsam odio non enim alias aspernatur neque libero magni. Omnis quos corrupti qui id esse.<br>\nId non necessitatibus tempora fuga blanditiis. Culpa mollitia quos quos aut.<br>\nProvident reprehenderit et veritatis minima eos tempora aliquam. Aut perferendis dolores odit mollitia. Eum nulla et beatae.</p>', 'Sed fugit neque est voluptas distinctio eum enim eaque. Quae deserunt cumque qui exercitationem alias sunt quia adipisci. Ipsam odio non enim alias aspernatur neque libero magni. Omnis quos corrupti qui id esse.\nId non necessitatibus tempora fuga blanditiis. Culpa mollitia quos quos aut.\nProvident reprehenderit et veritatis minima eos tempora aliquam. Aut perferendis dolores odit mollitia. Eum nulla et beatae.', 1, 8, 30, 0, 0, 0, '2012-04-15 20:58:29', '2017-04-26 22:01:34'),
	(55, '<p>Incidunt ut in sunt suscipit sint. Quae nesciunt vero qui mollitia quo rerum. Amet fuga est enim nemo et. Ex maxime tempore in minima id hic.<br>\nVelit sit commodi officia in earum mollitia. Doloremque iusto laborum debitis. Quibusdam voluptas sit nihil nam quo. Voluptas veritatis minima placeat est labore recusandae voluptatem.<br>\nNihil totam est eos. Unde corporis ex illo totam adipisci tempore ratione. Omnis ratione eum quia dolorem deserunt non. Voluptas reiciendis sint excepturi deserunt rerum fugiat dolore.</p>', 'Incidunt ut in sunt suscipit sint. Quae nesciunt vero qui mollitia quo rerum. Amet fuga est enim nemo et. Ex maxime tempore in minima id hic.\nVelit sit commodi officia in earum mollitia. Doloremque iusto laborum debitis. Quibusdam voluptas sit nihil nam quo. Voluptas veritatis minima placeat est labore recusandae voluptatem.\nNihil totam est eos. Unde corporis ex illo totam adipisci tempore ratione. Omnis ratione eum quia dolorem deserunt non. Voluptas reiciendis sint excepturi deserunt rerum fugiat dolore.', 7, 8, 30, 0, 0, 0, '2011-11-26 13:31:54', '2017-04-26 22:01:34'),
	(56, '<p>Quis eveniet est molestiae praesentium. Deserunt numquam dolor saepe. Quia consequatur laborum repudiandae nihil iste. Autem hic impedit praesentium voluptatem aperiam accusantium.<br>\nUt asperiores ut illum et sed rerum ullam praesentium. Quo expedita quisquam facere. Beatae consequatur numquam cumque reprehenderit saepe.<br>\nDignissimos laborum consectetur odio sed. Nihil qui quisquam eius accusantium tempore. Est qui voluptatum corrupti unde animi dolores.</p>', 'Quis eveniet est molestiae praesentium. Deserunt numquam dolor saepe. Quia consequatur laborum repudiandae nihil iste. Autem hic impedit praesentium voluptatem aperiam accusantium.\nUt asperiores ut illum et sed rerum ullam praesentium. Quo expedita quisquam facere. Beatae consequatur numquam cumque reprehenderit saepe.\nDignissimos laborum consectetur odio sed. Nihil qui quisquam eius accusantium tempore. Est qui voluptatum corrupti unde animi dolores.', 3, 8, 30, 0, 0, 0, '2008-03-01 01:36:01', '2017-04-26 22:01:34'),
	(57, '<p>Quis veritatis eum omnis quis ut commodi. Nostrum vitae omnis consectetur. Doloribus et quo eligendi corrupti corrupti.<br>\nAccusamus eum aut qui nemo. Vitae animi nisi perspiciatis possimus et.<br>\nCommodi in voluptas soluta culpa consectetur. Hic nulla totam harum ea enim. Rerum iure et deleniti aut.<br>\nPorro harum nemo praesentium consectetur quia suscipit. Eos omnis odio quasi rerum. Voluptatem modi aut incidunt doloribus distinctio.</p>', 'Quis veritatis eum omnis quis ut commodi. Nostrum vitae omnis consectetur. Doloribus et quo eligendi corrupti corrupti.\nAccusamus eum aut qui nemo. Vitae animi nisi perspiciatis possimus et.\nCommodi in voluptas soluta culpa consectetur. Hic nulla totam harum ea enim. Rerum iure et deleniti aut.\nPorro harum nemo praesentium consectetur quia suscipit. Eos omnis odio quasi rerum. Voluptatem modi aut incidunt doloribus distinctio.', 3, 8, 31, 0, 0, 0, '2013-07-06 19:43:10', '2017-04-26 22:01:35'),
	(58, '<p>Animi in non vel sed voluptas in. Illum dolores non enim. Maxime dolor magnam perferendis excepturi voluptatibus. Perspiciatis sequi velit blanditiis et minima commodi.<br>\nNemo quas provident ut consequuntur voluptates rerum. Sapiente et quia quas corrupti nobis dolorem nisi.<br>\nTemporibus quidem ad est est ut. Expedita pariatur corrupti eaque maiores. Dignissimos et beatae ut voluptatum a. Consequatur id modi quis.</p>', 'Animi in non vel sed voluptas in. Illum dolores non enim. Maxime dolor magnam perferendis excepturi voluptatibus. Perspiciatis sequi velit blanditiis et minima commodi.\nNemo quas provident ut consequuntur voluptates rerum. Sapiente et quia quas corrupti nobis dolorem nisi.\nTemporibus quidem ad est est ut. Expedita pariatur corrupti eaque maiores. Dignissimos et beatae ut voluptatum a. Consequatur id modi quis.', 1, 8, 33, 0, 0, 0, '2009-05-20 00:06:35', '2017-04-26 22:01:35'),
	(59, '<p>Delectus cupiditate soluta numquam est fugiat. Nulla quidem vel qui corporis est dolorem veniam repudiandae. Neque vel corrupti aut ipsam voluptas. Quas aut officiis dolor dolor saepe sint maxime. Quo dolore neque eaque commodi provident officiis.<br>\nOfficiis impedit necessitatibus voluptatem numquam. Blanditiis ex ipsa reiciendis voluptatem quasi voluptatibus reprehenderit. Cum vitae cupiditate eos repellendus laboriosam esse et in.</p>', 'Delectus cupiditate soluta numquam est fugiat. Nulla quidem vel qui corporis est dolorem veniam repudiandae. Neque vel corrupti aut ipsam voluptas. Quas aut officiis dolor dolor saepe sint maxime. Quo dolore neque eaque commodi provident officiis.\nOfficiis impedit necessitatibus voluptatem numquam. Blanditiis ex ipsa reiciendis voluptatem quasi voluptatibus reprehenderit. Cum vitae cupiditate eos repellendus laboriosam esse et in.', 7, 8, 34, 0, 0, 0, '2017-02-13 05:00:23', '2017-04-26 22:01:36'),
	(60, '<p>Deleniti voluptatem qui aut perspiciatis laborum explicabo. Dolorem quia sit iure consequatur blanditiis. Id eveniet est exercitationem quis et aspernatur quis.<br>\nNatus voluptates nostrum ad earum. Provident nulla assumenda perferendis itaque totam eos esse. Et aspernatur asperiores iure esse.<br>\nRepellat voluptatem aut ut accusamus tenetur vero natus. Sint pariatur qui occaecati cum esse reprehenderit. Recusandae assumenda perspiciatis eum dolorum accusantium quod.</p>', 'Deleniti voluptatem qui aut perspiciatis laborum explicabo. Dolorem quia sit iure consequatur blanditiis. Id eveniet est exercitationem quis et aspernatur quis.\nNatus voluptates nostrum ad earum. Provident nulla assumenda perferendis itaque totam eos esse. Et aspernatur asperiores iure esse.\nRepellat voluptatem aut ut accusamus tenetur vero natus. Sint pariatur qui occaecati cum esse reprehenderit. Recusandae assumenda perspiciatis eum dolorum accusantium quod.', 1, 8, 34, 0, 0, 0, '2015-01-08 16:56:26', '2017-04-26 22:01:37'),
	(61, '<p>Natus ad explicabo voluptatibus eveniet aliquid laborum iusto. Provident iure delectus odit dolores dolor sunt. Voluptatem aut omnis quas eos.<br>\nAut alias soluta voluptatum ipsum maiores tenetur repellat odio. Dolores enim molestias possimus ducimus perspiciatis animi excepturi odit. Facilis est in voluptas alias maxime tenetur ab. Ut est sint magnam molestias aut modi.</p>', 'Natus ad explicabo voluptatibus eveniet aliquid laborum iusto. Provident iure delectus odit dolores dolor sunt. Voluptatem aut omnis quas eos.\nAut alias soluta voluptatum ipsum maiores tenetur repellat odio. Dolores enim molestias possimus ducimus perspiciatis animi excepturi odit. Facilis est in voluptas alias maxime tenetur ab. Ut est sint magnam molestias aut modi.', 3, 9, 35, 0, 0, 0, '2012-09-29 06:49:34', '2017-04-26 22:01:38'),
	(62, '<p>Aut ut alias omnis fuga harum. Tempore minus repudiandae ut repudiandae quae iste odit dolores. Quae corrupti qui ea autem commodi ratione.<br>\nNesciunt dolores rerum perspiciatis ut commodi. Ut beatae maiores dignissimos sunt ut maiores enim.<br>\nAperiam dolores et quo. Et quia quis similique distinctio et fugit. Assumenda numquam et non tempora sed sint.<br>\nEt et dolores est officia. Quidem sit et ut velit id. Consequatur molestiae et et ut. Recusandae perspiciatis totam asperiores dolorem ratione ab.</p>', 'Aut ut alias omnis fuga harum. Tempore minus repudiandae ut repudiandae quae iste odit dolores. Quae corrupti qui ea autem commodi ratione.\nNesciunt dolores rerum perspiciatis ut commodi. Ut beatae maiores dignissimos sunt ut maiores enim.\nAperiam dolores et quo. Et quia quis similique distinctio et fugit. Assumenda numquam et non tempora sed sint.\nEt et dolores est officia. Quidem sit et ut velit id. Consequatur molestiae et et ut. Recusandae perspiciatis totam asperiores dolorem ratione ab.', 3, 9, 35, 0, 0, 0, '2015-01-27 01:49:09', '2017-04-26 22:01:38'),
	(63, '<p>Est maxime perspiciatis culpa beatae. Rem suscipit quas dolores aut. A modi voluptatem qui.<br>\nSint in fugit ullam ut. Voluptatem qui laboriosam et dolores id corrupti placeat minus. Praesentium qui sit et doloremque vel voluptate et. Accusamus vitae modi aperiam esse repellat neque.<br>\nAtque non eaque doloremque veritatis voluptatum recusandae quia. Impedit quia omnis ad accusantium similique. Nam eveniet id excepturi voluptatum et.</p>', 'Est maxime perspiciatis culpa beatae. Rem suscipit quas dolores aut. A modi voluptatem qui.\nSint in fugit ullam ut. Voluptatem qui laboriosam et dolores id corrupti placeat minus. Praesentium qui sit et doloremque vel voluptate et. Accusamus vitae modi aperiam esse repellat neque.\nAtque non eaque doloremque veritatis voluptatum recusandae quia. Impedit quia omnis ad accusantium similique. Nam eveniet id excepturi voluptatum et.', 4, 9, 35, 0, 0, 0, '2015-07-04 06:23:53', '2017-04-26 22:01:38'),
	(64, '<p>Et ipsam iusto temporibus et. Error quia in quia occaecati. Ut est est in.<br>\nQuia consequatur quidem velit dignissimos officiis animi quis. Nam ab molestiae possimus dolores quaerat aut magni. Et ut vero cumque et suscipit eos maxime.<br>\nQui voluptas laboriosam eos sunt. Eos temporibus delectus in ratione placeat.<br>\nEt occaecati laboriosam eligendi et animi. Et et ipsum cupiditate vitae adipisci porro. Sed maiores ab alias quia qui.</p>', 'Et ipsam iusto temporibus et. Error quia in quia occaecati. Ut est est in.\nQuia consequatur quidem velit dignissimos officiis animi quis. Nam ab molestiae possimus dolores quaerat aut magni. Et ut vero cumque et suscipit eos maxime.\nQui voluptas laboriosam eos sunt. Eos temporibus delectus in ratione placeat.\nEt occaecati laboriosam eligendi et animi. Et et ipsum cupiditate vitae adipisci porro. Sed maiores ab alias quia qui.', 8, 9, 36, 0, 0, 0, '2017-04-25 05:44:22', '2017-04-26 22:01:39'),
	(65, '<p>Quo velit aut saepe odit dolorem necessitatibus. Et placeat corrupti ullam cupiditate eius possimus.<br>\nAssumenda omnis non quia autem id quia dolor natus. Amet debitis quis exercitationem aut.<br>\nQuas et et consequatur incidunt et. Similique dolor et dolore tempora aut aut id. Veritatis sit a aut beatae culpa velit. Nihil voluptas et sed rerum laboriosam.<br>\nVel modi possimus eaque est laudantium excepturi impedit. Aut aut dolores quia. Quaerat rerum veniam rem voluptatem ut.</p>', 'Quo velit aut saepe odit dolorem necessitatibus. Et placeat corrupti ullam cupiditate eius possimus.\nAssumenda omnis non quia autem id quia dolor natus. Amet debitis quis exercitationem aut.\nQuas et et consequatur incidunt et. Similique dolor et dolore tempora aut aut id. Veritatis sit a aut beatae culpa velit. Nihil voluptas et sed rerum laboriosam.\nVel modi possimus eaque est laudantium excepturi impedit. Aut aut dolores quia. Quaerat rerum veniam rem voluptatem ut.', 4, 9, 36, 0, 0, 0, '2014-09-16 20:25:29', '2017-04-26 22:01:39'),
	(66, '<p>Qui amet molestiae sit voluptas. Exercitationem dolore laboriosam quae incidunt. Soluta reprehenderit debitis non voluptatum quibusdam est corporis. Veritatis nostrum sed et nulla aut.<br>\nSed odio est nihil voluptate nisi incidunt autem quia. Ut debitis qui aliquam voluptatem dolor provident voluptatibus.<br>\nSoluta in est dolores molestias asperiores unde nemo. Ratione dolores dicta a quam mollitia. Autem repellat et molestiae modi ullam quaerat.</p>', 'Qui amet molestiae sit voluptas. Exercitationem dolore laboriosam quae incidunt. Soluta reprehenderit debitis non voluptatum quibusdam est corporis. Veritatis nostrum sed et nulla aut.\nSed odio est nihil voluptate nisi incidunt autem quia. Ut debitis qui aliquam voluptatem dolor provident voluptatibus.\nSoluta in est dolores molestias asperiores unde nemo. Ratione dolores dicta a quam mollitia. Autem repellat et molestiae modi ullam quaerat.', 4, 9, 37, 0, 0, 0, '2007-11-21 02:26:28', '2017-04-26 22:01:39'),
	(67, '<p>Voluptate rerum nihil aliquam dolore tenetur laborum. Quisquam quis doloremque et qui fugit.<br>\nVelit eum quos similique veritatis odio sint. Est et beatae ut ut recusandae consequatur. Et et et et at et tempore optio.<br>\nEnim est dignissimos veniam itaque quas eius ut voluptate. Odit aut corporis dolorum eaque illo est ut. Doloremque ad quasi vero iure voluptatem. Non commodi enim magnam delectus ducimus.</p>', 'Voluptate rerum nihil aliquam dolore tenetur laborum. Quisquam quis doloremque et qui fugit.\nVelit eum quos similique veritatis odio sint. Est et beatae ut ut recusandae consequatur. Et et et et at et tempore optio.\nEnim est dignissimos veniam itaque quas eius ut voluptate. Odit aut corporis dolorum eaque illo est ut. Doloremque ad quasi vero iure voluptatem. Non commodi enim magnam delectus ducimus.', 5, 9, 38, 0, 0, 0, '2013-12-25 12:20:39', '2017-04-26 22:01:41'),
	(68, '<p>Quisquam sint rem quis ipsum aut placeat. Et dolore porro totam et. Sunt alias nemo distinctio. Esse et similique ut aut assumenda.<br>\nDignissimos eveniet nostrum explicabo doloribus ipsam dolorum. Omnis eos non rem doloremque similique. Itaque animi est omnis corporis dolores omnis exercitationem aut.<br>\nQuo libero eaque itaque voluptas laborum vitae. Illo molestiae et rerum quia natus. Tenetur ipsa id repudiandae illo nostrum error possimus repellendus.</p>', 'Quisquam sint rem quis ipsum aut placeat. Et dolore porro totam et. Sunt alias nemo distinctio. Esse et similique ut aut assumenda.\nDignissimos eveniet nostrum explicabo doloribus ipsam dolorum. Omnis eos non rem doloremque similique. Itaque animi est omnis corporis dolores omnis exercitationem aut.\nQuo libero eaque itaque voluptas laborum vitae. Illo molestiae et rerum quia natus. Tenetur ipsa id repudiandae illo nostrum error possimus repellendus.', 2, 9, 38, 0, 0, 0, '2013-02-07 03:04:05', '2017-04-26 22:01:41'),
	(69, '<p>Itaque non libero minima ut sed eum. At earum non ipsam quasi sed molestiae. Earum repudiandae necessitatibus illo.<br>\nQuia quasi dolorum magnam quia ex. Ut commodi rerum fugit dolorem.<br>\nTemporibus quae voluptatum qui sint. Corrupti ut est alias aliquid sed non molestiae. Molestias placeat illum quos quisquam qui ab rerum. Sunt soluta rerum qui magni numquam et. Ullam et similique voluptatibus iste.</p>', 'Itaque non libero minima ut sed eum. At earum non ipsam quasi sed molestiae. Earum repudiandae necessitatibus illo.\nQuia quasi dolorum magnam quia ex. Ut commodi rerum fugit dolorem.\nTemporibus quae voluptatum qui sint. Corrupti ut est alias aliquid sed non molestiae. Molestias placeat illum quos quisquam qui ab rerum. Sunt soluta rerum qui magni numquam et. Ullam et similique voluptatibus iste.', 6, 9, 39, 0, 0, 0, '2007-05-01 02:54:35', '2017-04-26 22:01:41'),
	(70, '<p>Ex cum sint eligendi nihil culpa exercitationem. Commodi doloribus consectetur unde dignissimos ut. Corrupti non modi et qui dolores voluptatem.<br>\nNihil id iure quia occaecati delectus autem aut sit. Sint impedit non eligendi id. Quis porro quia dignissimos quidem necessitatibus expedita vitae.<br>\nConsequuntur deleniti consequuntur consequatur. Optio laudantium id voluptatem nisi repellat dolor quia. Nobis magnam et cumque dolor.</p>', 'Ex cum sint eligendi nihil culpa exercitationem. Commodi doloribus consectetur unde dignissimos ut. Corrupti non modi et qui dolores voluptatem.\nNihil id iure quia occaecati delectus autem aut sit. Sint impedit non eligendi id. Quis porro quia dignissimos quidem necessitatibus expedita vitae.\nConsequuntur deleniti consequuntur consequatur. Optio laudantium id voluptatem nisi repellat dolor quia. Nobis magnam et cumque dolor.', 2, 9, 39, 0, 0, 0, '2012-06-15 20:34:34', '2017-04-26 22:01:41'),
	(71, '<p>Occaecati doloribus ipsam vero reprehenderit nesciunt. Enim iste tenetur consequatur doloremque. Vero voluptas unde nisi quo sint. Sit dicta aut ad perspiciatis magni repellendus eos aliquam.<br>\nEos eum et nobis placeat tempore ut voluptate. Magnam quaerat qui voluptate sunt accusamus. Magni id qui voluptatibus magnam ut. Voluptatem ipsam aut repellat rerum.</p>', 'Occaecati doloribus ipsam vero reprehenderit nesciunt. Enim iste tenetur consequatur doloremque. Vero voluptas unde nisi quo sint. Sit dicta aut ad perspiciatis magni repellendus eos aliquam.\nEos eum et nobis placeat tempore ut voluptate. Magnam quaerat qui voluptate sunt accusamus. Magni id qui voluptatibus magnam ut. Voluptatem ipsam aut repellat rerum.', 2, 9, 39, 0, 0, 0, '2013-12-29 09:28:10', '2017-04-26 22:01:41'),
	(72, '<p>Voluptatibus aliquid ratione aut odio sunt excepturi cumque. Cupiditate quo consequatur beatae consequuntur.<br>\nConsequatur optio eos dolor et ut. Et blanditiis quaerat porro. Quaerat exercitationem nesciunt velit modi. Occaecati est quia eaque omnis eveniet dolorem.<br>\nTemporibus reiciendis voluptatibus qui sapiente. Unde voluptas nesciunt voluptatibus quis nihil. Aspernatur laborum asperiores iure assumenda rerum adipisci. Expedita inventore unde quae nam sunt architecto vero.</p>', 'Voluptatibus aliquid ratione aut odio sunt excepturi cumque. Cupiditate quo consequatur beatae consequuntur.\nConsequatur optio eos dolor et ut. Et blanditiis quaerat porro. Quaerat exercitationem nesciunt velit modi. Occaecati est quia eaque omnis eveniet dolorem.\nTemporibus reiciendis voluptatibus qui sapiente. Unde voluptas nesciunt voluptatibus quis nihil. Aspernatur laborum asperiores iure assumenda rerum adipisci. Expedita inventore unde quae nam sunt architecto vero.', 8, 10, 40, 0, 0, 0, '2013-08-30 20:23:26', '2017-04-26 22:01:44'),
	(73, '<p>Voluptas qui sapiente eaque. Asperiores dolorem id voluptas est culpa numquam tempora.<br>\nPossimus est omnis amet. Omnis ab nam sed rem ad. Ipsum qui quis veniam placeat minima officiis.<br>\nVoluptas consequatur et animi nam consequatur. Harum similique iusto voluptates laboriosam hic maiores magnam. Fuga officia eius cumque nostrum.</p>', 'Voluptas qui sapiente eaque. Asperiores dolorem id voluptas est culpa numquam tempora.\nPossimus est omnis amet. Omnis ab nam sed rem ad. Ipsum qui quis veniam placeat minima officiis.\nVoluptas consequatur et animi nam consequatur. Harum similique iusto voluptates laboriosam hic maiores magnam. Fuga officia eius cumque nostrum.', 6, 10, 41, 0, 0, 0, '2011-07-06 11:54:48', '2017-04-26 22:01:44'),
	(74, '<p>Nemo quo praesentium iure accusamus. Vitae ipsa debitis eveniet esse eligendi placeat. Fugit cumque eligendi et eum suscipit magni.<br>\nMaxime libero culpa et eos modi. Illo asperiores magni voluptatem voluptate nihil.<br>\nDebitis repellat excepturi laudantium consequatur. Rerum repellendus expedita aut. Voluptas ut cum itaque repellendus et temporibus.<br>\nMolestiae voluptatem dolor corporis eveniet et ut. Totam ducimus harum praesentium quidem voluptatum. Voluptatum optio voluptatem a cumque.</p>', 'Nemo quo praesentium iure accusamus. Vitae ipsa debitis eveniet esse eligendi placeat. Fugit cumque eligendi et eum suscipit magni.\nMaxime libero culpa et eos modi. Illo asperiores magni voluptatem voluptate nihil.\nDebitis repellat excepturi laudantium consequatur. Rerum repellendus expedita aut. Voluptas ut cum itaque repellendus et temporibus.\nMolestiae voluptatem dolor corporis eveniet et ut. Totam ducimus harum praesentium quidem voluptatum. Voluptatum optio voluptatem a cumque.', 2, 10, 41, 0, 0, 0, '2010-04-07 17:04:26', '2017-04-26 22:01:44'),
	(75, '<p>Dignissimos laboriosam deleniti explicabo similique velit praesentium et. Ex numquam culpa et autem autem. Soluta optio voluptatem nostrum eum.<br>\nDolores at accusantium qui dolores porro alias harum. Perspiciatis doloremque consequatur aut odit molestiae assumenda amet. Error omnis et sapiente cupiditate hic. Molestias magnam quia in assumenda est. Sed voluptas occaecati aliquam maxime.</p>', 'Dignissimos laboriosam deleniti explicabo similique velit praesentium et. Ex numquam culpa et autem autem. Soluta optio voluptatem nostrum eum.\nDolores at accusantium qui dolores porro alias harum. Perspiciatis doloremque consequatur aut odit molestiae assumenda amet. Error omnis et sapiente cupiditate hic. Molestias magnam quia in assumenda est. Sed voluptas occaecati aliquam maxime.', 1, 10, 42, 0, 0, 0, '2007-12-31 05:32:40', '2017-04-26 22:01:45'),
	(76, '<p>Consequatur fuga aliquid et hic. Qui non voluptatem doloribus qui excepturi impedit amet quia.<br>\nConsequuntur voluptatum eum molestiae et amet. Minus neque ut eum consectetur deleniti. Enim ad fugit dicta accusantium quo.<br>\nNatus ut dolor maiores quis distinctio ut sint. Voluptates eaque suscipit fuga dolore sit repellat esse. Iure nostrum vel asperiores. Quidem vero labore magni quibusdam quis delectus.</p>', 'Consequatur fuga aliquid et hic. Qui non voluptatem doloribus qui excepturi impedit amet quia.\nConsequuntur voluptatum eum molestiae et amet. Minus neque ut eum consectetur deleniti. Enim ad fugit dicta accusantium quo.\nNatus ut dolor maiores quis distinctio ut sint. Voluptates eaque suscipit fuga dolore sit repellat esse. Iure nostrum vel asperiores. Quidem vero labore magni quibusdam quis delectus.', 8, 10, 42, 0, 0, 0, '2009-02-25 11:59:02', '2017-04-26 22:01:45'),
	(77, '<p>Dicta similique iste nostrum dolores in sit autem in. Quia neque voluptatem et est aut sit. Rerum provident quia neque provident qui labore.<br>\nQui hic iusto doloribus molestias. Rem voluptatem esse id dolore non sit. Qui quae velit et nam.<br>\nRepellendus expedita maxime velit. Explicabo asperiores voluptas aut dolorem neque. Aut odit in est.</p>', 'Dicta similique iste nostrum dolores in sit autem in. Quia neque voluptatem et est aut sit. Rerum provident quia neque provident qui labore.\nQui hic iusto doloribus molestias. Rem voluptatem esse id dolore non sit. Qui quae velit et nam.\nRepellendus expedita maxime velit. Explicabo asperiores voluptas aut dolorem neque. Aut odit in est.', 2, 10, 42, 0, 0, 0, '2011-01-28 19:36:59', '2017-04-26 22:01:45'),
	(78, '<p>Nihil fugit consequatur harum repellendus accusamus in. Aspernatur laboriosam maxime magnam est. Beatae sunt consequatur amet facilis.<br>\nIn totam odit est ut at. Placeat similique corrupti cupiditate repellendus at repudiandae harum. Saepe dolores ut quod id quaerat nemo.<br>\nEst nisi rerum voluptatem aut iste quis. Quia aut similique harum.<br>\nEt cumque suscipit totam et. Repellat et nihil rerum dolores enim. Nisi fuga est vel cumque veniam sint laboriosam. Minima quae qui saepe odio.</p>', 'Nihil fugit consequatur harum repellendus accusamus in. Aspernatur laboriosam maxime magnam est. Beatae sunt consequatur amet facilis.\nIn totam odit est ut at. Placeat similique corrupti cupiditate repellendus at repudiandae harum. Saepe dolores ut quod id quaerat nemo.\nEst nisi rerum voluptatem aut iste quis. Quia aut similique harum.\nEt cumque suscipit totam et. Repellat et nihil rerum dolores enim. Nisi fuga est vel cumque veniam sint laboriosam. Minima quae qui saepe odio.', 5, 10, 43, 0, 0, 0, '2012-03-07 03:53:38', '2017-04-26 22:01:45'),
	(79, '<p>Provident voluptatem non quas laudantium nihil nisi hic. Enim magni dolorum voluptatem molestias sed. Quidem adipisci velit deleniti non nemo non.<br>\nFugit eum quo eveniet nihil esse molestias enim vel. Distinctio consectetur quas illum unde et. Vitae architecto fuga placeat distinctio aspernatur saepe reiciendis. Alias et autem cupiditate ad.<br>\nAliquid eveniet ullam modi vel. Aut non quasi dolorem.</p>', 'Provident voluptatem non quas laudantium nihil nisi hic. Enim magni dolorum voluptatem molestias sed. Quidem adipisci velit deleniti non nemo non.\nFugit eum quo eveniet nihil esse molestias enim vel. Distinctio consectetur quas illum unde et. Vitae architecto fuga placeat distinctio aspernatur saepe reiciendis. Alias et autem cupiditate ad.\nAliquid eveniet ullam modi vel. Aut non quasi dolorem.', 1, 10, 43, 0, 0, 0, '2012-03-02 22:10:37', '2017-04-26 22:01:45'),
	(80, '<p>Odit consequuntur rerum voluptas accusantium velit labore ipsa. Asperiores repudiandae sunt dolorem et nisi. Quia nostrum impedit deleniti qui. Nihil accusantium sed ad natus.<br>\nVeritatis aspernatur et dolor corporis exercitationem ut nemo. Ea eos qui est et nam suscipit ab. Sunt impedit beatae labore quae. Perferendis eum ut eveniet accusamus ut.</p>', 'Odit consequuntur rerum voluptas accusantium velit labore ipsa. Asperiores repudiandae sunt dolorem et nisi. Quia nostrum impedit deleniti qui. Nihil accusantium sed ad natus.\nVeritatis aspernatur et dolor corporis exercitationem ut nemo. Ea eos qui est et nam suscipit ab. Sunt impedit beatae labore quae. Perferendis eum ut eveniet accusamus ut.', 4, 10, 44, 0, 0, 0, '2013-01-01 18:05:27', '2017-04-26 22:01:46'),
	(81, '<p>Voluptas ratione laudantium aperiam enim tempore aut. Consequatur qui tempora repellendus. Id similique nisi quis tenetur.<br>\nEaque nam molestiae possimus sint aut illum. Unde ducimus voluptatem qui et qui sed provident. Officiis tenetur nulla molestiae facere nisi optio. Quam quae ab laborum voluptatibus temporibus voluptates est.<br>\nQuia consequuntur quisquam necessitatibus sequi esse recusandae. At officia nobis totam et ullam omnis vel dignissimos. Eligendi totam sint perferendis cum.</p>', 'Voluptas ratione laudantium aperiam enim tempore aut. Consequatur qui tempora repellendus. Id similique nisi quis tenetur.\nEaque nam molestiae possimus sint aut illum. Unde ducimus voluptatem qui et qui sed provident. Officiis tenetur nulla molestiae facere nisi optio. Quam quae ab laborum voluptatibus temporibus voluptates est.\nQuia consequuntur quisquam necessitatibus sequi esse recusandae. At officia nobis totam et ullam omnis vel dignissimos. Eligendi totam sint perferendis cum.', 9, 12, 45, 0, 0, 0, '2009-09-20 16:06:49', '2017-04-26 22:01:47'),
	(82, '<p>Repudiandae asperiores sed et vero aliquam. Quia qui eum maiores corporis sed. Est dolores omnis culpa. Qui sequi minima qui. Esse aut eos magnam totam id.<br>\nCorrupti eaque eum porro soluta sequi libero temporibus. Maxime hic aspernatur neque doloremque incidunt labore sint. Eius voluptatibus et doloribus totam nulla temporibus quam necessitatibus. Facilis autem quia velit.</p>', 'Repudiandae asperiores sed et vero aliquam. Quia qui eum maiores corporis sed. Est dolores omnis culpa. Qui sequi minima qui. Esse aut eos magnam totam id.\nCorrupti eaque eum porro soluta sequi libero temporibus. Maxime hic aspernatur neque doloremque incidunt labore sint. Eius voluptatibus et doloribus totam nulla temporibus quam necessitatibus. Facilis autem quia velit.', 6, 12, 45, 0, 0, 0, '2010-08-06 22:21:41', '2017-04-26 22:01:47'),
	(83, '<p>Perferendis est aut itaque ut natus dolor. Explicabo est quo labore distinctio.<br>\nDolore impedit quae illo ut. Aut aut cupiditate ullam aut earum voluptatem nesciunt doloremque.<br>\nLaudantium mollitia veritatis repellendus non consequatur. Illo voluptatibus nam quis debitis consequatur molestias. Aliquam aut praesentium ut deserunt sapiente ad.<br>\nVoluptatem inventore nihil temporibus. Rem quae esse laudantium ut ut minima culpa. Unde quam maxime id non soluta.</p>', 'Perferendis est aut itaque ut natus dolor. Explicabo est quo labore distinctio.\nDolore impedit quae illo ut. Aut aut cupiditate ullam aut earum voluptatem nesciunt doloremque.\nLaudantium mollitia veritatis repellendus non consequatur. Illo voluptatibus nam quis debitis consequatur molestias. Aliquam aut praesentium ut deserunt sapiente ad.\nVoluptatem inventore nihil temporibus. Rem quae esse laudantium ut ut minima culpa. Unde quam maxime id non soluta.', 4, 12, 48, 0, 0, 0, '2007-07-04 15:14:22', '2017-04-26 22:01:48'),
	(84, '<p>Et suscipit possimus eum dolore dolorum culpa. In impedit quidem consequatur voluptas optio rerum. Quos consequatur sunt voluptatem ipsam.<br>\nEt possimus incidunt porro et. Harum quod tenetur corporis et debitis iste. Voluptas autem aut odio rerum et sed.<br>\nNon quis perferendis quia saepe rerum. Minus consectetur et numquam et. Magnam blanditiis nostrum nulla voluptatum in itaque ea. Placeat tenetur excepturi natus sint vitae quam. Minima ut qui eum iure vero sit.</p>', 'Et suscipit possimus eum dolore dolorum culpa. In impedit quidem consequatur voluptas optio rerum. Quos consequatur sunt voluptatem ipsam.\nEt possimus incidunt porro et. Harum quod tenetur corporis et debitis iste. Voluptas autem aut odio rerum et sed.\nNon quis perferendis quia saepe rerum. Minus consectetur et numquam et. Magnam blanditiis nostrum nulla voluptatum in itaque ea. Placeat tenetur excepturi natus sint vitae quam. Minima ut qui eum iure vero sit.', 5, 12, 48, 0, 0, 0, '2010-11-18 18:05:46', '2017-04-26 22:01:48'),
	(85, '<p>Quas voluptates praesentium eum commodi minus deleniti eos. Iure itaque tempore sequi error. Quo et consequatur quia voluptatem eveniet recusandae et at.<br>\nDistinctio unde provident dignissimos libero autem. Et qui quia animi delectus neque saepe cumque. Modi qui iusto aspernatur voluptatum corporis qui. Quis cumque culpa voluptatem voluptatem sed distinctio.<br>\nAut et voluptates nihil repudiandae tempora dolores. Sed qui sunt nobis quo qui. Corporis sed molestiae sequi iure distinctio beatae.</p>', 'Quas voluptates praesentium eum commodi minus deleniti eos. Iure itaque tempore sequi error. Quo et consequatur quia voluptatem eveniet recusandae et at.\nDistinctio unde provident dignissimos libero autem. Et qui quia animi delectus neque saepe cumque. Modi qui iusto aspernatur voluptatum corporis qui. Quis cumque culpa voluptatem voluptatem sed distinctio.\nAut et voluptates nihil repudiandae tempora dolores. Sed qui sunt nobis quo qui. Corporis sed molestiae sequi iure distinctio beatae.', 9, 12, 48, 0, 0, 0, '2007-12-19 13:00:00', '2017-04-26 22:01:48');
/*!40000 ALTER TABLE `comment_replies` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.contents
CREATE TABLE IF NOT EXISTS `contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci,
  `text` text COLLATE utf8mb4_unicode_ci,
  `text_source` text COLLATE utf8mb4_unicode_ci,
  `comments_count` int(10) unsigned NOT NULL DEFAULT '0',
  `related_count` int(10) unsigned NOT NULL DEFAULT '0',
  `eng` tinyint(1) NOT NULL DEFAULT '0',
  `nsfw` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `uv` int(10) unsigned NOT NULL DEFAULT '0',
  `dv` int(10) unsigned NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `frontpage_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contents_user_id_foreign` (`user_id`),
  KEY `contents_group_id_foreign` (`group_id`),
  CONSTRAINT `contents_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `contents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.contents: ~1 rows (około)
/*!40000 ALTER TABLE `contents` DISABLE KEYS */;
INSERT IGNORE INTO `contents` (`id`, `title`, `description`, `domain`, `thumbnail`, `url`, `text`, `text_source`, `comments_count`, `related_count`, `eng`, `nsfw`, `user_id`, `group_id`, `uv`, `dv`, `score`, `frontpage_at`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
	(1, 'Nihil itaque quos qui rem error unde fuga recusandae.', 'Nulla aut facilis eius perspiciatis. Et inventore voluptatem hic quisquam explicabo beatae. Omnis nisi incidunt sint dolor.', 'gutmann.com', NULL, 'http://www.gutmann.com/sunt-eveniet-voluptas-asperiores-optio.html', NULL, NULL, 6, 0, 1, 0, 1, 1, 0, 0, 0, NULL, '2009-02-27 16:46:21', '2017-04-26 22:01:09', NULL, 0),
	(2, 'Earum ex placeat minima qui inventore ut dolor.', 'Quam qui nisi non. Aspernatur mollitia accusantium voluptatem est. Delectus et odio voluptatem aliquam.', 'goldner.com', NULL, 'http://goldner.com/', NULL, NULL, 8, 0, 1, 1, 3, 3, 0, 0, 0, NULL, '2016-01-05 08:59:19', '2017-04-26 22:01:12', NULL, 0),
	(3, 'Quae quod qui eos et rerum ut necessitatibus ab enim eveniet et facere et.', 'Aperiam odit beatae occaecati. Numquam laborum nihil voluptatum consectetur. Dicta fugiat molestias et vero aut libero.', 'bauch.biz', NULL, 'http://bauch.biz/neque-consequuntur-molestiae-non-accusamus', NULL, NULL, 5, 0, 0, 0, 1, 3, 0, 0, 0, NULL, '2012-03-29 12:31:30', '2017-04-26 22:01:14', NULL, 0),
	(4, 'Accusantium fuga cum officiis et sit minus.', 'Sapiente perspiciatis quis sunt ipsum quas ut ad quo. Ut temporibus omnis exercitationem. Sequi mollitia sunt iure.', 'damore.com', NULL, 'https://damore.com/rerum-odio-reprehenderit-aliquid-sapiente-harum-enim.html', NULL, NULL, 8, 0, 1, 0, 3, 3, 0, 0, 0, NULL, '2014-02-04 09:26:56', '2017-04-26 22:01:16', NULL, 0),
	(5, 'Sint qui amet praesentium sapiente ut beatae.', 'Sit autem doloremque placeat molestias. Facilis vero sapiente suscipit quidem ipsa. Cumque natus dolorem temporibus repellat. Veniam quisquam necessitatibus repudiandae et voluptas amet.', 'rowe.com', NULL, 'http://rowe.com/id-dolore-et-sit-aut-omnis-id', NULL, NULL, 6, 0, 1, 1, 1, 3, 0, 0, 0, NULL, '2010-10-06 01:30:14', '2017-04-26 22:01:16', NULL, 0),
	(6, 'Architecto nam sit animi deserunt natus nam voluptas sint.', 'Impedit ut reiciendis sequi fuga aut praesentium quod. Ad inventore qui voluptatem aut et tenetur. Facilis officiis sunt architecto. Iusto dolores rerum et.', 'braun.com', NULL, 'http://braun.com/delectus-rerum-totam-saepe-ut-fuga.html', NULL, NULL, 0, 0, 0, 0, 1, 3, 0, 0, 0, NULL, '2015-04-23 12:08:59', '2017-04-26 22:01:17', NULL, 0),
	(7, 'Aut quia voluptas tempora dolores ut consequatur qui inventore ea nam debitis eum qui.', 'Tempore modi quas natus accusamus odit labore consequuntur. Dolorum qui ipsam recusandae dolorem fuga sed.', 'fay.com', NULL, 'http://fay.com/quibusdam-laboriosam-quod-sit-porro', NULL, NULL, 0, 0, 0, 0, 3, 5, 0, 0, 0, NULL, '2011-11-24 23:48:03', '2017-04-26 22:01:19', NULL, 0),
	(8, 'Maiores in consectetur corporis delectus eaque cupiditate magnam.', 'Consequatur molestiae saepe dolores cum qui veritatis natus. Accusamus ut illum qui eos tempore occaecati. Eum harum qui sequi rerum ab odio alias. Quis voluptatem eum possimus nihil.', 'luettgen.info', NULL, 'http://luettgen.info/minus-at-dolore-sint-earum-voluptatem.html', NULL, NULL, 4, 0, 1, 1, 4, 5, 0, 0, 0, NULL, '2008-07-09 20:41:20', '2017-04-26 22:01:20', NULL, 0),
	(9, 'Qui quidem animi aliquid ad earum amet distinctio accusantium soluta voluptatem eos atque.', 'Fugit perferendis accusamus consequatur quod. Ad quibusdam dolorem nam corrupti itaque quia rerum. Ut quo earum quia totam omnis.', 'corkery.com', NULL, 'http://corkery.com/aliquid-optio-dolore-et-sunt-quis-repellendus.html', NULL, NULL, 10, 0, 0, 1, 2, 6, 0, 0, 0, NULL, '2011-06-24 00:53:39', '2017-04-26 22:01:21', NULL, 0),
	(10, 'In aliquid dolor ea cumque assumenda impedit qui.', 'Rem reprehenderit eligendi ducimus expedita. Accusamus perferendis sapiente doloremque quisquam iure. Aliquam odio necessitatibus quis qui soluta est.', 'reinger.com', NULL, 'http://www.reinger.com/aliquid-minus-nihil-et-quos-accusamus-possimus', NULL, NULL, 4, 0, 1, 1, 5, 6, 0, 0, 0, NULL, '2009-07-01 06:16:32', '2017-04-26 22:01:22', NULL, 0),
	(11, 'Ratione amet sit commodi error in qui dolores.', 'Ut accusamus sint et et. Voluptatem sapiente officia tenetur. Dolorem nesciunt et et voluptate labore itaque aspernatur.', 'langosh.com', NULL, 'http://www.langosh.com/', NULL, NULL, 0, 0, 0, 1, 4, 6, 0, 0, 0, NULL, '2017-03-29 12:08:15', '2017-04-26 22:01:23', NULL, 0),
	(12, 'Et enim et voluptatibus aut hic quas inventore.', 'Quod enim minus rerum voluptatem. Est odit dolor suscipit molestiae laboriosam. Quod itaque qui quia.', 'hoppe.com', NULL, 'http://www.hoppe.com/explicabo-dolor-aspernatur-eos-sunt-dolores', NULL, NULL, 4, 0, 1, 0, 2, 6, 0, 0, 0, NULL, '2012-11-12 09:36:31', '2017-04-26 22:01:25', NULL, 0),
	(13, 'Ab esse velit nihil non totam consequatur tenetur non.', 'Dolores impedit dolore ipsa voluptas quas. Velit dicta aliquid non molestias alias ex. Id velit quis nemo delectus distinctio officiis amet. Est est accusantium nesciunt fuga molestiae tempora qui.', 'deckow.net', NULL, 'http://www.deckow.net/adipisci-voluptatem-soluta-voluptatem-non-earum-distinctio-fugit-libero', NULL, NULL, 2, 0, 1, 0, 6, 7, 0, 0, 0, NULL, '2011-08-09 19:29:15', '2017-04-26 22:01:26', NULL, 0),
	(14, 'Debitis ut rem iure eveniet ea deleniti dolorem commodi sunt architecto reiciendis placeat maxime.', 'Maiores libero nesciunt et sint id expedita consectetur. Sed sint et accusantium unde tenetur in enim. Similique nostrum est esse qui facilis sit dolores sit.', 'swift.com', NULL, 'http://swift.com/', NULL, NULL, 8, 0, 0, 1, 6, 7, 0, 0, 0, NULL, '2012-04-13 04:28:55', '2017-04-26 22:01:28', NULL, 0),
	(15, 'Eligendi maiores quia est veritatis voluptas qui possimus optio autem incidunt.', 'Possimus illo reiciendis in maiores in fugiat. Sed rem velit minus assumenda. Ea et repellat soluta deserunt.', 'lesch.biz', NULL, 'http://www.lesch.biz/ipsa-dolorem-suscipit-inventore-minima-iure.html', NULL, NULL, 4, 0, 1, 0, 4, 7, 0, 0, 0, NULL, '2013-07-09 14:57:50', '2017-04-26 22:01:30', NULL, 0),
	(16, 'Ut atque officiis et accusantium ullam est.', 'Et nihil totam voluptates odit. Animi velit et laudantium voluptatem autem ut perferendis quo. Quia maiores voluptas consequatur a.', 'bosco.info', NULL, 'https://www.bosco.info/id-suscipit-quasi-omnis-officiis', NULL, NULL, 6, 0, 1, 1, 1, 7, 0, 0, 0, NULL, '2008-03-20 01:51:11', '2017-04-26 22:01:31', NULL, 0),
	(17, 'Neque quas corrupti doloremque autem numquam praesentium.', 'Neque sint fuga architecto ex. Eaque consequatur voluptatibus voluptas. Reiciendis molestiae et est occaecati et accusantium consequatur.', 'wisozk.com', NULL, 'http://wisozk.com/molestiae-corporis-itaque-et-cumque-perspiciatis-expedita', NULL, NULL, 3, 0, 1, 1, 1, 8, 0, 0, 0, NULL, '2017-01-12 04:19:46', '2017-04-26 22:01:33', NULL, 0),
	(18, 'Voluptates doloribus rerum qui consectetur suscipit cumque ratione rem cumque.', 'Rerum ipsam molestiae nulla possimus. Esse mollitia esse mollitia est neque. Qui est qui corporis voluptas. Pariatur aperiam eum autem repellendus nihil.', 'gibson.info', NULL, 'http://www.gibson.info/dolorem-assumenda-optio-iure-natus', NULL, NULL, 8, 0, 0, 1, 1, 8, 0, 0, 0, NULL, '2009-02-19 06:01:53', '2017-04-26 22:01:34', NULL, 0),
	(19, 'Qui corporis sapiente ut blanditiis dicta nisi.', 'Et quo quae provident aut ut animi perferendis hic. Et illo error rerum. Distinctio nostrum dolor sit.', 'boehm.info', NULL, 'http://www.boehm.info/vel-dolore-neque-ut-nesciunt-quae-vitae.html', NULL, NULL, 5, 0, 1, 1, 6, 8, 0, 0, 0, NULL, '2010-06-16 19:18:42', '2017-04-26 22:01:35', NULL, 0),
	(20, 'Alias tenetur fuga quam est et aut harum dolorem voluptatem unde.', 'Tempore voluptate ullam voluptatibus excepturi sed consectetur cumque velit. Dolorem quis accusantium doloremque. Ut sit iusto ad quia. Repellat incidunt nostrum sunt itaque.', 'bayer.com', NULL, 'http://bayer.com/dicta-nostrum-suscipit-odio-ducimus.html', NULL, NULL, 3, 0, 1, 0, 6, 8, 0, 0, 0, NULL, '2013-05-11 18:23:47', '2017-04-26 22:01:37', NULL, 0),
	(21, 'Occaecati culpa quo voluptate cum placeat quibusdam magni impedit possimus facilis.', 'Voluptatibus earum nostrum autem. Sapiente iste eos nemo reprehenderit porro. Sed labore magni sunt quia et officiis.', 'haley.com', NULL, 'http://haley.com/est-eos-enim-rerum-neque-porro-voluptate-numquam', NULL, NULL, 9, 0, 1, 0, 5, 9, 0, 0, 0, NULL, '2012-08-26 16:27:09', '2017-04-26 22:01:39', NULL, 0),
	(22, 'Esse ut iusto harum nostrum illo sed incidunt amet laudantium impedit modi illo.', 'Qui eveniet id nam voluptatem optio consequatur. Voluptatum commodi deserunt natus eos voluptatem. Possimus nobis architecto aut soluta. Illum doloribus ut aut vero distinctio quo assumenda.', 'donnelly.com', NULL, 'http://www.donnelly.com/tempora-laborum-et-dicta-et-in', NULL, NULL, 0, 0, 0, 0, 3, 9, 0, 0, 0, NULL, '2007-09-12 06:07:11', '2017-04-26 22:01:40', NULL, 0),
	(23, 'Vel aut cum cumque quam dolores atque omnis aut nostrum et voluptatem odit magnam.', 'Ea quia eligendi dolor perspiciatis dolores impedit dolores. Dolorem quis sapiente ut odio. Dolore mollitia id dignissimos fugit.', 'hammes.org', NULL, 'http://www.hammes.org/ipsam-unde-ut-molestias', NULL, NULL, 7, 0, 1, 1, 8, 9, 0, 0, 0, NULL, '2009-11-03 06:27:10', '2017-04-26 22:01:41', NULL, 0),
	(24, 'Officia quis commodi est necessitatibus minima libero consectetur quam quasi.', 'Sit autem debitis amet officiis asperiores magni impedit. Neque assumenda perferendis sed asperiores. Vel harum velit aut itaque.', 'kuhlman.biz', NULL, 'http://www.kuhlman.biz/tempore-itaque-ut-ea-commodi-repudiandae-cumque-ipsa-facilis.html', NULL, NULL, 0, 0, 0, 0, 1, 10, 0, 0, 0, NULL, '2017-04-12 07:04:49', '2017-04-26 22:01:42', NULL, 0),
	(25, 'Debitis qui earum mollitia ut ea alias atque provident magni voluptatum animi corrupti quae.', 'Magnam eos et temporibus consectetur velit labore. Illum porro autem natus dolorem. Vel quos rerum magni sint quidem distinctio sed.', 'willms.com', NULL, 'http://www.willms.com/nihil-deserunt-iure-inventore-dolor-vitae-ex-tenetur', NULL, NULL, 5, 0, 0, 0, 1, 10, 0, 0, 0, NULL, '2015-05-03 15:37:03', '2017-04-26 22:01:44', NULL, 0),
	(26, 'Nihil vel quidem recusandae dolorem est autem nesciunt voluptatum mollitia sed.', 'Soluta esse eos earum possimus animi. Harum qui aperiam quod. Ut quibusdam molestiae pariatur eveniet temporibus. Necessitatibus sunt molestiae ut aut.', 'schinner.com', NULL, 'http://schinner.com/vitae-ullam-vero-autem-enim', NULL, NULL, 7, 0, 0, 1, 4, 10, 0, 0, 0, NULL, '2010-04-24 10:01:36', '2017-04-26 22:01:45', NULL, 0),
	(27, 'Nulla est pariatur nulla ut quis hic inventore praesentium.', 'Aut assumenda consequuntur ut. Fugit in harum iusto necessitatibus qui explicabo. Quo adipisci ut reprehenderit dolorem modi nobis.', 'bins.com', NULL, 'http://bins.com/optio-dolore-quo-et-voluptate-itaque.html', NULL, NULL, 2, 0, 1, 1, 8, 10, 0, 0, 0, NULL, '2014-01-20 18:29:14', '2017-04-26 22:01:46', NULL, 0),
	(28, 'Nemo eligendi excepturi dolores qui suscipit deleniti ut.', 'Dicta quam quaerat rerum nihil deleniti. Odio voluptatem dignissimos maiores sed. Porro illo corrupti occaecati qui aliquam sunt laudantium. Et et laudantium laboriosam qui laborum voluptate.', 'ernser.info', NULL, 'http://www.ernser.info/veniam-cupiditate-nihil-vel-eveniet.html', NULL, NULL, 4, 0, 1, 1, 2, 12, 0, 0, 0, NULL, '2012-04-12 20:50:44', '2017-04-26 22:01:47', NULL, 0),
	(29, 'Possimus quidem eveniet maxime omnis repellendus voluptates quia.', 'Incidunt sed est maiores asperiores repellendus ut. Ratione corporis dolores similique nobis. Deleniti pariatur nostrum minima omnis esse aspernatur. Et molestiae aliquam officiis et atque.', 'deckow.com', NULL, 'http://www.deckow.com/velit-magnam-a-tenetur', NULL, NULL, 5, 0, 0, 0, 3, 12, 0, 0, 0, NULL, '2010-07-26 05:41:58', '2017-04-26 22:01:48', NULL, 0);
/*!40000 ALTER TABLE `contents` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.content_related
CREATE TABLE IF NOT EXISTS `content_related` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `eng` tinyint(1) NOT NULL DEFAULT '0',
  `nsfw` tinyint(1) NOT NULL DEFAULT '0',
  `uv` int(10) unsigned NOT NULL DEFAULT '0',
  `dv` int(10) unsigned NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_related_content_id_foreign` (`content_id`),
  KEY `content_related_group_id_foreign` (`group_id`),
  KEY `content_related_user_id_foreign` (`user_id`),
  CONSTRAINT `content_related_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_related_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_related_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.content_related: ~0 rows (około)
/*!40000 ALTER TABLE `content_related` DISABLE KEYS */;
/*!40000 ALTER TABLE `content_related` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.conversations
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.conversations: ~0 rows (około)
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.conversation_messages
CREATE TABLE IF NOT EXISTS `conversation_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_source` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conversation_messages_conversation_id_foreign` (`conversation_id`),
  KEY `conversation_messages_user_id_foreign` (`user_id`),
  CONSTRAINT `conversation_messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversation_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.conversation_messages: ~0 rows (około)
/*!40000 ALTER TABLE `conversation_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversation_messages` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.conversation_users
CREATE TABLE IF NOT EXISTS `conversation_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conversation_users_conversation_id_foreign` (`conversation_id`),
  KEY `conversation_users_user_id_foreign` (`user_id`),
  CONSTRAINT `conversation_users_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversation_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.conversation_users: ~0 rows (około)
/*!40000 ALTER TABLE `conversation_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversation_users` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.entries
CREATE TABLE IF NOT EXISTS `entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_source` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `replies_count` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `uv` int(10) unsigned NOT NULL DEFAULT '0',
  `dv` int(10) unsigned NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entries_user_id_foreign` (`user_id`),
  KEY `entries_group_id_foreign` (`group_id`),
  CONSTRAINT `entries_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `entries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.entries: ~1 rows (około)
/*!40000 ALTER TABLE `entries` DISABLE KEYS */;
INSERT IGNORE INTO `entries` (`id`, `text`, `text_source`, `replies_count`, `user_id`, `group_id`, `uv`, `dv`, `score`, `created_at`, `updated_at`) VALUES
	(1, '<p>Eius quibusdam repellendus ut itaque. Odio quo unde enim id et numquam. Odit ea odit in qui dolores.<br>\nMagnam occaecati non ipsa sapiente quibusdam. Eaque voluptatem quia nisi natus at quidem. Impedit accusantium ab rerum iusto maxime esse. Est eaque magni ex similique. Doloribus delectus dolores corporis dolores incidunt non voluptates.<br>\nProvident nihil amet aliquam dolor id. Omnis quos est esse minus. Quaerat dolor sed eum sed.</p>', 'Eius quibusdam repellendus ut itaque. Odio quo unde enim id et numquam. Odit ea odit in qui dolores.\nMagnam occaecati non ipsa sapiente quibusdam. Eaque voluptatem quia nisi natus at quidem. Impedit accusantium ab rerum iusto maxime esse. Est eaque magni ex similique. Doloribus delectus dolores corporis dolores incidunt non voluptates.\nProvident nihil amet aliquam dolor id. Omnis quos est esse minus. Quaerat dolor sed eum sed.', 2, 1, 1, 0, 0, 0, '2016-04-10 13:07:14', '2017-04-26 22:01:11'),
	(2, '<p>Nam ea vitae officia. Expedita delectus consequatur debitis. Numquam quam blanditiis eveniet ut iusto autem ut est. Nesciunt ratione facilis omnis pariatur qui dolorem fuga.<br>\nMinima molestiae quis nobis. Quis nam magni nam architecto eum incidunt delectus. Non recusandae rem ea.<br>\nLaborum deleniti eum quisquam cumque. Ipsam ut id dolorem totam molestias. Praesentium iste repellat adipisci temporibus autem est saepe.</p>', 'Nam ea vitae officia. Expedita delectus consequatur debitis. Numquam quam blanditiis eveniet ut iusto autem ut est. Nesciunt ratione facilis omnis pariatur qui dolorem fuga.\nMinima molestiae quis nobis. Quis nam magni nam architecto eum incidunt delectus. Non recusandae rem ea.\nLaborum deleniti eum quisquam cumque. Ipsam ut id dolorem totam molestias. Praesentium iste repellat adipisci temporibus autem est saepe.', 3, 3, 3, 0, 0, 0, '2015-11-02 17:30:52', '2017-04-26 22:01:13'),
	(3, '<p>Facere aliquid iusto temporibus maiores aut veniam dignissimos. Sapiente dolorum hic iure. Qui quod tempora et aspernatur beatae consectetur.<br>\nUllam consequuntur sit nobis iure eos et. Non distinctio quidem illum temporibus excepturi aut ut. Sint ab animi repellat ipsum. Est atque fugit laborum magnam inventore.<br>\nIllo sit itaque ut deleniti enim voluptatum. Omnis sit natus fugiat et dolorem eius. Rerum dolorem maiores aperiam earum nostrum fugiat fugit. Laboriosam et praesentium sequi magni in asperiores.</p>', 'Facere aliquid iusto temporibus maiores aut veniam dignissimos. Sapiente dolorum hic iure. Qui quod tempora et aspernatur beatae consectetur.\nUllam consequuntur sit nobis iure eos et. Non distinctio quidem illum temporibus excepturi aut ut. Sint ab animi repellat ipsum. Est atque fugit laborum magnam inventore.\nIllo sit itaque ut deleniti enim voluptatum. Omnis sit natus fugiat et dolorem eius. Rerum dolorem maiores aperiam earum nostrum fugiat fugit. Laboriosam et praesentium sequi magni in asperiores.', 3, 3, 3, 0, 0, 0, '2014-07-27 09:15:22', '2017-04-26 22:01:15'),
	(4, '<p>Nulla qui quia quisquam odit commodi labore. Perferendis ea voluptatem minima eos praesentium hic. Non eligendi porro libero ipsa quod consequatur. Similique voluptatem minima sint sint sint.<br>\nRecusandae recusandae aut in esse quos. Qui unde ut laborum dicta. Itaque ea qui aut neque architecto sed repellat. Fuga blanditiis aut praesentium optio.<br>\nVero dolor molestias aut ut minus asperiores similique. Esse dolore sint quibusdam in. Animi et rerum dolorum officiis.</p>', 'Nulla qui quia quisquam odit commodi labore. Perferendis ea voluptatem minima eos praesentium hic. Non eligendi porro libero ipsa quod consequatur. Similique voluptatem minima sint sint sint.\nRecusandae recusandae aut in esse quos. Qui unde ut laborum dicta. Itaque ea qui aut neque architecto sed repellat. Fuga blanditiis aut praesentium optio.\nVero dolor molestias aut ut minus asperiores similique. Esse dolore sint quibusdam in. Animi et rerum dolorum officiis.', 0, 2, 3, 0, 0, 0, '2013-12-02 06:46:32', '2017-04-26 22:01:16'),
	(5, '<p>Aut adipisci ea ratione. Ea corrupti aperiam velit et quo dignissimos. Qui minima consequatur itaque vel mollitia. Quia quidem natus neque rerum. Id nesciunt id praesentium est earum.<br>\nMollitia repellat temporibus architecto ut quod. Saepe omnis facilis quia quibusdam rerum deserunt. Veritatis non debitis aut maiores veniam eaque. Cupiditate est quia ea non repudiandae blanditiis.</p>', 'Aut adipisci ea ratione. Ea corrupti aperiam velit et quo dignissimos. Qui minima consequatur itaque vel mollitia. Quia quidem natus neque rerum. Id nesciunt id praesentium est earum.\nMollitia repellat temporibus architecto ut quod. Saepe omnis facilis quia quibusdam rerum deserunt. Veritatis non debitis aut maiores veniam eaque. Cupiditate est quia ea non repudiandae blanditiis.', 1, 2, 3, 0, 0, 0, '2008-08-19 00:08:02', '2017-04-26 22:01:17'),
	(6, '<p>Quia dolorum rerum odit illo molestias quidem. Perspiciatis expedita et est quia et incidunt itaque. Et voluptatem quia similique doloribus accusantium. Dolore excepturi sequi non.<br>\nPariatur quas sunt eaque non est ullam aut. Vel omnis doloremque eum omnis possimus natus repellat. Totam et repudiandae aspernatur quidem saepe repudiandae. Quia eos sed aspernatur iste.</p>', 'Quia dolorum rerum odit illo molestias quidem. Perspiciatis expedita et est quia et incidunt itaque. Et voluptatem quia similique doloribus accusantium. Dolore excepturi sequi non.\nPariatur quas sunt eaque non est ullam aut. Vel omnis doloremque eum omnis possimus natus repellat. Totam et repudiandae aspernatur quidem saepe repudiandae. Quia eos sed aspernatur iste.', 2, 2, 3, 0, 0, 0, '2010-11-13 19:19:51', '2017-04-26 22:01:18'),
	(7, '<p>Qui necessitatibus ad cupiditate totam quo itaque. Iste sint eum nam repudiandae optio aliquam. Illo voluptatibus qui dolorum voluptas porro veritatis.<br>\nNon aperiam ratione consequatur. Deserunt asperiores delectus ipsam sed odio. Dolor et quia illo facilis minima eaque et amet. Animi officia consectetur soluta aliquid consequuntur sint error.</p>', 'Qui necessitatibus ad cupiditate totam quo itaque. Iste sint eum nam repudiandae optio aliquam. Illo voluptatibus qui dolorum voluptas porro veritatis.\nNon aperiam ratione consequatur. Deserunt asperiores delectus ipsam sed odio. Dolor et quia illo facilis minima eaque et amet. Animi officia consectetur soluta aliquid consequuntur sint error.', 1, 3, 5, 0, 0, 0, '2014-02-07 00:51:12', '2017-04-26 22:01:19'),
	(8, '<p>Molestiae sequi facere ipsa accusamus maiores. Excepturi veniam occaecati qui dolorem labore. Dolor repudiandae error asperiores ut. Tempore dolorem sint dolores.<br>\nNeque aspernatur consectetur itaque cumque voluptas non. Sunt exercitationem voluptas occaecati explicabo. Animi sed iste ab repudiandae earum.<br>\nQui sint aspernatur laudantium quibusdam debitis. Sunt optio excepturi deserunt nostrum.</p>', 'Molestiae sequi facere ipsa accusamus maiores. Excepturi veniam occaecati qui dolorem labore. Dolor repudiandae error asperiores ut. Tempore dolorem sint dolores.\nNeque aspernatur consectetur itaque cumque voluptas non. Sunt exercitationem voluptas occaecati explicabo. Animi sed iste ab repudiandae earum.\nQui sint aspernatur laudantium quibusdam debitis. Sunt optio excepturi deserunt nostrum.', 2, 1, 5, 0, 0, 0, '2014-06-28 13:28:19', '2017-04-26 22:01:21'),
	(9, '<p>Eaque odio esse dolores culpa. Et laborum et aspernatur nemo necessitatibus deserunt ut. Eos fugiat reiciendis esse exercitationem. Aspernatur sed porro nemo deleniti.<br>\nNemo ea dolores similique dolorem voluptatem. Sunt non iste soluta nesciunt minus. Unde reiciendis provident neque voluptatem ex.<br>\nVelit cumque architecto nostrum autem eos. Debitis eaque quis voluptate atque assumenda. Adipisci aut dolores et in a excepturi soluta. Iusto natus deserunt et.</p>', 'Eaque odio esse dolores culpa. Et laborum et aspernatur nemo necessitatibus deserunt ut. Eos fugiat reiciendis esse exercitationem. Aspernatur sed porro nemo deleniti.\nNemo ea dolores similique dolorem voluptatem. Sunt non iste soluta nesciunt minus. Unde reiciendis provident neque voluptatem ex.\nVelit cumque architecto nostrum autem eos. Debitis eaque quis voluptate atque assumenda. Adipisci aut dolores et in a excepturi soluta. Iusto natus deserunt et.', 1, 4, 6, 0, 0, 0, '2014-03-04 07:13:35', '2017-04-26 22:01:22'),
	(10, '<p>Dignissimos est officiis veniam inventore ducimus placeat ut natus. Eos doloribus rem nihil corporis. A corporis delectus sit provident et voluptatem quaerat.<br>\nQui eaque aut aut et occaecati. Et tempore minus quasi enim quas tempora. Quos natus sunt officia ratione hic facilis fuga dolores. Omnis consectetur inventore explicabo autem accusamus non.</p>', 'Dignissimos est officiis veniam inventore ducimus placeat ut natus. Eos doloribus rem nihil corporis. A corporis delectus sit provident et voluptatem quaerat.\nQui eaque aut aut et occaecati. Et tempore minus quasi enim quas tempora. Quos natus sunt officia ratione hic facilis fuga dolores. Omnis consectetur inventore explicabo autem accusamus non.', 1, 1, 6, 0, 0, 0, '2016-06-10 08:48:30', '2017-04-26 22:01:23'),
	(11, '<p>Sint occaecati occaecati ullam pariatur placeat. Id qui corporis deserunt et laboriosam ratione ab. Aut quis rerum voluptatum. Vero enim qui necessitatibus odit inventore consequuntur voluptas cupiditate.<br>\nQui nobis dolores quibusdam perspiciatis commodi. Itaque id ullam veniam velit doloribus alias velit. Quibusdam voluptas officia qui harum nihil fugit debitis ut.</p>', 'Sint occaecati occaecati ullam pariatur placeat. Id qui corporis deserunt et laboriosam ratione ab. Aut quis rerum voluptatum. Vero enim qui necessitatibus odit inventore consequuntur voluptas cupiditate.\nQui nobis dolores quibusdam perspiciatis commodi. Itaque id ullam veniam velit doloribus alias velit. Quibusdam voluptas officia qui harum nihil fugit debitis ut.', 2, 2, 6, 0, 0, 0, '2014-02-26 21:28:14', '2017-04-26 22:01:24'),
	(12, '<p>Molestias neque nesciunt culpa asperiores odio. Ut ab distinctio dolorum quidem. Asperiores est voluptas quo qui nostrum accusamus quisquam. Voluptatem unde sequi aut velit sunt quia ipsa magnam.<br>\nVoluptates sed et et perspiciatis voluptatem. Laboriosam labore tenetur maxime in. Dicta aut facilis voluptas dolorum repudiandae. Sunt dolores inventore hic aspernatur sed delectus aut.</p>', 'Molestias neque nesciunt culpa asperiores odio. Ut ab distinctio dolorum quidem. Asperiores est voluptas quo qui nostrum accusamus quisquam. Voluptatem unde sequi aut velit sunt quia ipsa magnam.\nVoluptates sed et et perspiciatis voluptatem. Laboriosam labore tenetur maxime in. Dicta aut facilis voluptas dolorum repudiandae. Sunt dolores inventore hic aspernatur sed delectus aut.', 3, 2, 6, 0, 0, 0, '2014-10-01 20:16:32', '2017-04-26 22:01:26'),
	(13, '<p>Eveniet ab rem consequatur vitae qui. Aliquid eveniet eligendi ipsam quia cum. Cum eos enim quia est nostrum eveniet neque. Nemo exercitationem consectetur voluptas et enim labore.<br>\nSaepe corrupti dolorem eligendi eum quaerat et. Nam nobis consequatur optio. Quo molestiae voluptatibus maxime quae corrupti.<br>\nAccusamus harum illo quas voluptatem numquam recusandae. Eveniet facere sint aut dolore sint id. Dolor alias sed numquam aut. Id rem in enim suscipit sunt atque.</p>', 'Eveniet ab rem consequatur vitae qui. Aliquid eveniet eligendi ipsam quia cum. Cum eos enim quia est nostrum eveniet neque. Nemo exercitationem consectetur voluptas et enim labore.\nSaepe corrupti dolorem eligendi eum quaerat et. Nam nobis consequatur optio. Quo molestiae voluptatibus maxime quae corrupti.\nAccusamus harum illo quas voluptatem numquam recusandae. Eveniet facere sint aut dolore sint id. Dolor alias sed numquam aut. Id rem in enim suscipit sunt atque.', 3, 4, 7, 0, 0, 0, '2013-05-24 20:43:43', '2017-04-26 22:01:28'),
	(14, '<p>Nostrum atque at quia facere ut voluptatem. Ipsum recusandae expedita voluptatibus velit in. Repellat eos consequatur quod repellat id ducimus consequuntur. Iure placeat omnis et soluta inventore veritatis.<br>\nNisi beatae et id in nobis neque corporis. Sint soluta voluptas incidunt cum voluptatibus. Est earum officia sed laborum sit sint.<br>\nUllam porro dolores id perferendis consectetur. Quis sit ipsam omnis est. Nisi ea dolores corporis voluptatibus adipisci.</p>', 'Nostrum atque at quia facere ut voluptatem. Ipsum recusandae expedita voluptatibus velit in. Repellat eos consequatur quod repellat id ducimus consequuntur. Iure placeat omnis et soluta inventore veritatis.\nNisi beatae et id in nobis neque corporis. Sint soluta voluptas incidunt cum voluptatibus. Est earum officia sed laborum sit sint.\nUllam porro dolores id perferendis consectetur. Quis sit ipsam omnis est. Nisi ea dolores corporis voluptatibus adipisci.', 2, 1, 7, 0, 0, 0, '2010-09-12 00:34:38', '2017-04-26 22:01:30'),
	(15, '<p>Et tenetur voluptatem hic pariatur eos ipsa sit. Officiis harum voluptatibus aliquid ad accusamus ipsum voluptas. Et nulla enim fugit ipsa non expedita. Quidem illum repellendus culpa quia quia.<br>\nSed ut at velit doloribus velit odio. Blanditiis ipsa quia asperiores deleniti voluptatem aliquid. Est perspiciatis omnis doloribus distinctio expedita sapiente.<br>\nOfficia pariatur ipsa optio dignissimos asperiores nulla. Qui veritatis dicta autem. Et sapiente error odio veniam eveniet fuga ratione.</p>', 'Et tenetur voluptatem hic pariatur eos ipsa sit. Officiis harum voluptatibus aliquid ad accusamus ipsum voluptas. Et nulla enim fugit ipsa non expedita. Quidem illum repellendus culpa quia quia.\nSed ut at velit doloribus velit odio. Blanditiis ipsa quia asperiores deleniti voluptatem aliquid. Est perspiciatis omnis doloribus distinctio expedita sapiente.\nOfficia pariatur ipsa optio dignissimos asperiores nulla. Qui veritatis dicta autem. Et sapiente error odio veniam eveniet fuga ratione.', 2, 2, 7, 0, 0, 0, '2009-06-17 10:45:07', '2017-04-26 22:01:31'),
	(16, '<p>Maxime quia recusandae voluptates dolores a. Omnis sit et est aperiam autem delectus. Praesentium cumque est consectetur consequuntur quo. Aut aut ipsam et necessitatibus.<br>\nDeserunt velit facere ut aut. Praesentium ratione qui a quia consequatur enim amet. Est aut officiis et repudiandae id cupiditate. Laborum odit aut fugit et.</p>', 'Maxime quia recusandae voluptates dolores a. Omnis sit et est aperiam autem delectus. Praesentium cumque est consectetur consequuntur quo. Aut aut ipsam et necessitatibus.\nDeserunt velit facere ut aut. Praesentium ratione qui a quia consequatur enim amet. Est aut officiis et repudiandae id cupiditate. Laborum odit aut fugit et.', 1, 2, 7, 0, 0, 0, '2010-04-14 07:24:19', '2017-04-26 22:01:32'),
	(17, '<p>Quod voluptas perferendis et ad soluta sint in. Explicabo iure pariatur neque est cupiditate cumque. Voluptates soluta quia officiis natus.<br>\nError dolorem est ut voluptatem officia ipsam. Neque neque provident ex natus dolore eaque laboriosam. Atque maxime quidem praesentium rem commodi nam est.<br>\nSit assumenda totam iusto blanditiis ea illum. Eaque velit ut libero vel et est reprehenderit. Enim nisi asperiores in atque voluptas.</p>', 'Quod voluptas perferendis et ad soluta sint in. Explicabo iure pariatur neque est cupiditate cumque. Voluptates soluta quia officiis natus.\nError dolorem est ut voluptatem officia ipsam. Neque neque provident ex natus dolore eaque laboriosam. Atque maxime quidem praesentium rem commodi nam est.\nSit assumenda totam iusto blanditiis ea illum. Eaque velit ut libero vel et est reprehenderit. Enim nisi asperiores in atque voluptas.', 2, 4, 8, 0, 0, 0, '2011-05-31 03:00:09', '2017-04-26 22:01:34'),
	(18, '<p>Dolorem laboriosam enim voluptatem et quis dolorem quam est. In et quam ut quis.<br>\nUllam ea laudantium dolorem distinctio sunt. Omnis rerum delectus optio deleniti illum vitae. Alias esse soluta harum quis. Numquam quos non cupiditate.<br>\nModi optio beatae quasi consectetur et corporis. Est laudantium omnis qui explicabo. Velit nemo dolorum aliquam odio repudiandae enim.</p>', 'Dolorem laboriosam enim voluptatem et quis dolorem quam est. In et quam ut quis.\nUllam ea laudantium dolorem distinctio sunt. Omnis rerum delectus optio deleniti illum vitae. Alias esse soluta harum quis. Numquam quos non cupiditate.\nModi optio beatae quasi consectetur et corporis. Est laudantium omnis qui explicabo. Velit nemo dolorum aliquam odio repudiandae enim.', 0, 1, 8, 0, 0, 0, '2007-09-24 22:10:48', '2017-04-26 22:01:34'),
	(19, '<p>Veritatis soluta quo et asperiores. Quis officia quia et rerum ratione veritatis cum officiis. Ex ab quisquam fugiat dolore.<br>\nEt veniam eaque cupiditate voluptatum esse et. Autem ut totam maiores rerum. Quas iste enim iusto dolore dolore.<br>\nSoluta qui ipsam ut ut magni sapiente non. Molestiae perferendis eaque sed pariatur facere voluptas in amet. Aut dolores sed maxime ea officia. Earum et maxime in natus numquam quibusdam laudantium.</p>', 'Veritatis soluta quo et asperiores. Quis officia quia et rerum ratione veritatis cum officiis. Ex ab quisquam fugiat dolore.\nEt veniam eaque cupiditate voluptatum esse et. Autem ut totam maiores rerum. Quas iste enim iusto dolore dolore.\nSoluta qui ipsam ut ut magni sapiente non. Molestiae perferendis eaque sed pariatur facere voluptas in amet. Aut dolores sed maxime ea officia. Earum et maxime in natus numquam quibusdam laudantium.', 3, 2, 8, 0, 0, 0, '2007-11-06 01:57:23', '2017-04-26 22:01:36'),
	(20, '<p>Hic adipisci sunt omnis rem sed est officia. Id eaque totam debitis doloribus. Voluptatem at saepe earum esse aliquam velit iure quam. Nesciunt quae eligendi et ut placeat.<br>\nRepellendus dicta voluptatem fugit praesentium facilis. Totam aspernatur dolor qui sunt molestias culpa.<br>\nUt ut rem enim et sint sunt. Excepturi ipsam laborum molestias laborum quia. Totam tenetur porro et occaecati accusantium. Modi libero fuga dicta molestiae.</p>', 'Hic adipisci sunt omnis rem sed est officia. Id eaque totam debitis doloribus. Voluptatem at saepe earum esse aliquam velit iure quam. Nesciunt quae eligendi et ut placeat.\nRepellendus dicta voluptatem fugit praesentium facilis. Totam aspernatur dolor qui sunt molestias culpa.\nUt ut rem enim et sint sunt. Excepturi ipsam laborum molestias laborum quia. Totam tenetur porro et occaecati accusantium. Modi libero fuga dicta molestiae.', 3, 6, 8, 0, 0, 0, '2011-10-07 20:26:11', '2017-04-26 22:01:38'),
	(21, '<p>Ducimus corporis aperiam laborum quidem consequatur iure harum aut. Possimus veritatis ut asperiores dolorem. Ut blanditiis ullam aut iste aliquid soluta.<br>\nIure quo facilis sapiente minus nulla. Eligendi ut ratione qui sit harum esse eligendi. Tempore est numquam dolorem nihil. Ut est autem quia.<br>\nMinima voluptate consectetur esse tempore atque hic quos. Quo aut corporis nemo corporis. Eum dolor provident unde consequatur. Sit aut modi incidunt architecto aliquam dolore.</p>', 'Ducimus corporis aperiam laborum quidem consequatur iure harum aut. Possimus veritatis ut asperiores dolorem. Ut blanditiis ullam aut iste aliquid soluta.\nIure quo facilis sapiente minus nulla. Eligendi ut ratione qui sit harum esse eligendi. Tempore est numquam dolorem nihil. Ut est autem quia.\nMinima voluptate consectetur esse tempore atque hic quos. Quo aut corporis nemo corporis. Eum dolor provident unde consequatur. Sit aut modi incidunt architecto aliquam dolore.', 2, 3, 9, 0, 0, 0, '2008-08-19 22:27:44', '2017-04-26 22:01:40'),
	(22, '<p>Rerum exercitationem consequuntur voluptas aut eum occaecati. Ut laborum et laboriosam quia delectus ut soluta. Nam porro dolorem ipsum maxime maiores doloribus. Placeat itaque harum occaecati quisquam esse.<br>\nAtque atque et sed culpa incidunt. Harum quas voluptate ad illo quasi soluta nisi omnis. Porro et id sequi dolores at qui facilis est. Veniam quia sunt nobis adipisci modi enim.</p>', 'Rerum exercitationem consequuntur voluptas aut eum occaecati. Ut laborum et laboriosam quia delectus ut soluta. Nam porro dolorem ipsum maxime maiores doloribus. Placeat itaque harum occaecati quisquam esse.\nAtque atque et sed culpa incidunt. Harum quas voluptate ad illo quasi soluta nisi omnis. Porro et id sequi dolores at qui facilis est. Veniam quia sunt nobis adipisci modi enim.', 1, 5, 9, 0, 0, 0, '2008-01-27 21:30:15', '2017-04-26 22:01:41'),
	(23, '<p>Vero optio ullam quis cum. Repudiandae ut consequatur dolorum perspiciatis omnis voluptate similique. Maiores aliquid est quia quas dolor id debitis. Et non molestiae vero quis est.<br>\nSint ut quo reprehenderit ut. Ducimus eos maxime ipsa aperiam quisquam quo. Ut est alias qui quaerat nisi quod. Consequatur ducimus quasi rerum et. Vel omnis et autem voluptatum amet numquam.<br>\nTemporibus natus ut rerum numquam. Qui quas voluptatem sit mollitia voluptas totam.</p>', 'Vero optio ullam quis cum. Repudiandae ut consequatur dolorum perspiciatis omnis voluptate similique. Maiores aliquid est quia quas dolor id debitis. Et non molestiae vero quis est.\nSint ut quo reprehenderit ut. Ducimus eos maxime ipsa aperiam quisquam quo. Ut est alias qui quaerat nisi quod. Consequatur ducimus quasi rerum et. Vel omnis et autem voluptatum amet numquam.\nTemporibus natus ut rerum numquam. Qui quas voluptatem sit mollitia voluptas totam.', 2, 7, 9, 0, 0, 0, '2012-10-31 20:05:46', '2017-04-26 22:01:42'),
	(24, '<p>Quos cupiditate quos vel accusantium unde. In nam recusandae similique aliquam odit. Voluptatum culpa vero accusamus nemo beatae voluptatem.<br>\nDolores quo eos ea voluptatem quaerat quaerat. Numquam reiciendis tenetur veniam quos id facilis debitis. Mollitia et non at iusto quia.<br>\nVoluptas dolor ipsam voluptas natus. In explicabo provident aut minima. Earum et rerum est. Est similique quo optio.</p>', 'Quos cupiditate quos vel accusantium unde. In nam recusandae similique aliquam odit. Voluptatum culpa vero accusamus nemo beatae voluptatem.\nDolores quo eos ea voluptatem quaerat quaerat. Numquam reiciendis tenetur veniam quos id facilis debitis. Mollitia et non at iusto quia.\nVoluptas dolor ipsam voluptas natus. In explicabo provident aut minima. Earum et rerum est. Est similique quo optio.', 2, 5, 10, 0, 0, 0, '2010-08-25 05:38:09', '2017-04-26 22:01:44'),
	(25, '<p>Excepturi molestias eos a voluptatum. Vel sit voluptatum qui debitis voluptates. Est odio ea nesciunt culpa quo quod optio.<br>\nNihil animi expedita quis autem voluptas quasi ex. Temporibus sed consequatur est sed maiores voluptates. Ut qui nesciunt sequi explicabo.<br>\nUt at ut sit pariatur dolores cupiditate deserunt. Dolor omnis qui rerum aut fugit ad est. Debitis ab at numquam eos.<br>\nMinus a omnis beatae. Consequuntur non quia vel accusamus non. Dolorem iusto commodi voluptas molestias numquam autem.</p>', 'Excepturi molestias eos a voluptatum. Vel sit voluptatum qui debitis voluptates. Est odio ea nesciunt culpa quo quod optio.\nNihil animi expedita quis autem voluptas quasi ex. Temporibus sed consequatur est sed maiores voluptates. Ut qui nesciunt sequi explicabo.\nUt at ut sit pariatur dolores cupiditate deserunt. Dolor omnis qui rerum aut fugit ad est. Debitis ab at numquam eos.\nMinus a omnis beatae. Consequuntur non quia vel accusamus non. Dolorem iusto commodi voluptas molestias numquam autem.', 1, 3, 10, 0, 0, 0, '2013-05-30 18:23:04', '2017-04-26 22:01:45'),
	(26, '<p>Alias quam ut numquam velit expedita. Quibusdam error a molestiae deserunt aut non officiis. Ea rerum non praesentium distinctio maiores quaerat.<br>\nUt blanditiis dolorem sit quaerat minus fugit quo. Aliquid sed excepturi aut et velit. Laudantium quae sit dolorum quo itaque.<br>\nCorporis dolores sed dolores. Non odit earum debitis voluptas harum est. In quia vel itaque ut sed. Maxime aut labore sed provident laboriosam et.</p>', 'Alias quam ut numquam velit expedita. Quibusdam error a molestiae deserunt aut non officiis. Ea rerum non praesentium distinctio maiores quaerat.\nUt blanditiis dolorem sit quaerat minus fugit quo. Aliquid sed excepturi aut et velit. Laudantium quae sit dolorum quo itaque.\nCorporis dolores sed dolores. Non odit earum debitis voluptas harum est. In quia vel itaque ut sed. Maxime aut labore sed provident laboriosam et.', 1, 7, 10, 0, 0, 0, '2009-02-12 13:15:28', '2017-04-26 22:01:46'),
	(27, '<p>Quam nam autem aut atque voluptatem. Perferendis magnam minus sit sed ut esse. Illum cupiditate in porro nemo quasi ullam corporis. Libero maxime occaecati pariatur ut facere necessitatibus.<br>\nOdio consequatur ut aut a non velit omnis est. Vero aliquid placeat quis saepe modi. Quia consequatur vero voluptatem. Qui possimus cupiditate in rerum facilis incidunt. Aliquam autem dolores atque vero ratione fugit quia.</p>', 'Quam nam autem aut atque voluptatem. Perferendis magnam minus sit sed ut esse. Illum cupiditate in porro nemo quasi ullam corporis. Libero maxime occaecati pariatur ut facere necessitatibus.\nOdio consequatur ut aut a non velit omnis est. Vero aliquid placeat quis saepe modi. Quia consequatur vero voluptatem. Qui possimus cupiditate in rerum facilis incidunt. Aliquam autem dolores atque vero ratione fugit quia.', 0, 7, 10, 0, 0, 0, '2015-11-17 00:42:33', '2017-04-26 22:01:46'),
	(28, '<p>Maiores similique quos ex culpa eligendi cum. Nam quaerat iste aliquid itaque neque. Maiores occaecati laborum et doloremque. Corporis inventore similique in nam aut debitis ullam.<br>\nVoluptas aspernatur non doloribus quo incidunt rem nihil. Cupiditate quisquam fugit molestias id dolorem voluptatem ullam. Vitae saepe cumque dignissimos possimus autem eligendi. Iste commodi veniam distinctio dolorem est.</p>', 'Maiores similique quos ex culpa eligendi cum. Nam quaerat iste aliquid itaque neque. Maiores occaecati laborum et doloremque. Corporis inventore similique in nam aut debitis ullam.\nVoluptas aspernatur non doloribus quo incidunt rem nihil. Cupiditate quisquam fugit molestias id dolorem voluptatem ullam. Vitae saepe cumque dignissimos possimus autem eligendi. Iste commodi veniam distinctio dolorem est.', 1, 1, 12, 0, 0, 0, '2012-03-08 15:57:42', '2017-04-26 22:01:47'),
	(29, '<p>Qui voluptate et ab ab beatae. Qui nobis eveniet quibusdam aut. Voluptates debitis voluptatem necessitatibus dolore. Nihil eos perferendis quo dolore sequi magni.<br>\nEt quo sit fuga eius tenetur ut placeat. Quae debitis sequi non quia. Fuga excepturi vero voluptas fugit tempore impedit quis. Et sunt ut doloribus molestiae reiciendis. Ut ex sunt aut repellat non.</p>', 'Qui voluptate et ab ab beatae. Qui nobis eveniet quibusdam aut. Voluptates debitis voluptatem necessitatibus dolore. Nihil eos perferendis quo dolore sequi magni.\nEt quo sit fuga eius tenetur ut placeat. Quae debitis sequi non quia. Fuga excepturi vero voluptas fugit tempore impedit quis. Et sunt ut doloribus molestiae reiciendis. Ut ex sunt aut repellat non.', 3, 6, 12, 0, 0, 0, '2015-10-19 08:29:47', '2017-04-26 22:01:49');
/*!40000 ALTER TABLE `entries` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.entry_replies
CREATE TABLE IF NOT EXISTS `entry_replies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_source` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `replies_count` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `uv` int(10) unsigned NOT NULL DEFAULT '0',
  `dv` int(10) unsigned NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entry_replies_user_id_foreign` (`user_id`),
  KEY `entry_replies_group_id_foreign` (`group_id`),
  KEY `entry_replies_parent_id_foreign` (`parent_id`),
  CONSTRAINT `entry_replies_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `entry_replies_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `entries` (`id`),
  CONSTRAINT `entry_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.entry_replies: ~2 rows (około)
/*!40000 ALTER TABLE `entry_replies` DISABLE KEYS */;
INSERT IGNORE INTO `entry_replies` (`id`, `text`, `text_source`, `replies_count`, `user_id`, `group_id`, `parent_id`, `uv`, `dv`, `score`, `created_at`, `updated_at`) VALUES
	(1, '<p>Magnam porro quia repudiandae veritatis sapiente iusto autem. Architecto animi et animi aspernatur. Ad aliquam et velit sed sit ut tempore dolor.<br>\nVelit ut est deleniti. Ea consequatur placeat minima possimus vel eaque quo. Quos vel non consequatur quis delectus. Distinctio quia quia saepe voluptas dolorem exercitationem.<br>\nVeritatis sint et non aut possimus veritatis. Cum labore quaerat explicabo velit. Neque nihil rerum sit amet temporibus consequuntur rerum.</p>', 'Magnam porro quia repudiandae veritatis sapiente iusto autem. Architecto animi et animi aspernatur. Ad aliquam et velit sed sit ut tempore dolor.\nVelit ut est deleniti. Ea consequatur placeat minima possimus vel eaque quo. Quos vel non consequatur quis delectus. Distinctio quia quia saepe voluptas dolorem exercitationem.\nVeritatis sint et non aut possimus veritatis. Cum labore quaerat explicabo velit. Neque nihil rerum sit amet temporibus consequuntur rerum.', 0, 1, 1, 1, 0, 0, 0, '2010-10-05 21:20:06', '2017-04-26 22:01:10'),
	(2, '<p>Ut et impedit quisquam voluptatem. Ea libero et molestias ut. Ut dolorem in fuga velit sed quisquam qui.<br>\nAut necessitatibus delectus porro. Itaque libero aut eum consequatur et esse placeat. Distinctio et laboriosam voluptatibus voluptas sit officia.<br>\nEnim ex veniam facilis et ut earum. Sit qui numquam in veritatis sint voluptas et. Quo itaque alias numquam eos dolor qui.</p>', 'Ut et impedit quisquam voluptatem. Ea libero et molestias ut. Ut dolorem in fuga velit sed quisquam qui.\nAut necessitatibus delectus porro. Itaque libero aut eum consequatur et esse placeat. Distinctio et laboriosam voluptatibus voluptas sit officia.\nEnim ex veniam facilis et ut earum. Sit qui numquam in veritatis sint voluptas et. Quo itaque alias numquam eos dolor qui.', 0, 1, 1, 1, 0, 0, 0, '2013-04-15 17:16:51', '2017-04-26 22:01:11'),
	(3, '<p>Quis reiciendis architecto nulla sunt. Odit nihil veniam beatae nemo ipsa animi aperiam. Porro dolores natus et atque voluptas excepturi minima tempore.<br>\nRerum ea amet et omnis. Ducimus et tempora doloremque. Earum suscipit earum voluptatum voluptatem mollitia voluptatem earum. Dolorem ut rem rerum nemo voluptas.<br>\nEarum qui sit dignissimos dolorum animi nemo mollitia ut. Eius amet enim sunt et debitis eos eum mollitia.</p>', 'Quis reiciendis architecto nulla sunt. Odit nihil veniam beatae nemo ipsa animi aperiam. Porro dolores natus et atque voluptas excepturi minima tempore.\nRerum ea amet et omnis. Ducimus et tempora doloremque. Earum suscipit earum voluptatum voluptatem mollitia voluptatem earum. Dolorem ut rem rerum nemo voluptas.\nEarum qui sit dignissimos dolorum animi nemo mollitia ut. Eius amet enim sunt et debitis eos eum mollitia.', 0, 1, 3, 2, 0, 0, 0, '2016-11-17 07:44:58', '2017-04-26 22:01:12'),
	(4, '<p>Quis fuga laborum quam animi non architecto. Autem eaque voluptas qui quia. Debitis deserunt provident commodi nihil suscipit est qui.<br>\nVelit possimus incidunt minus quis nisi sunt dolores. Et vel laboriosam est provident neque. Ut necessitatibus in alias.<br>\nUt exercitationem dolorum saepe iste ratione et sed eius. Voluptas et enim dolorem in non et. Soluta impedit totam corrupti sapiente natus aut. Debitis est consectetur porro similique.</p>', 'Quis fuga laborum quam animi non architecto. Autem eaque voluptas qui quia. Debitis deserunt provident commodi nihil suscipit est qui.\nVelit possimus incidunt minus quis nisi sunt dolores. Et vel laboriosam est provident neque. Ut necessitatibus in alias.\nUt exercitationem dolorum saepe iste ratione et sed eius. Voluptas et enim dolorem in non et. Soluta impedit totam corrupti sapiente natus aut. Debitis est consectetur porro similique.', 0, 1, 3, 2, 0, 0, 0, '2012-05-24 16:30:07', '2017-04-26 22:01:13'),
	(5, '<p>Corrupti non eius voluptatibus culpa odio. Nam voluptas nisi voluptatem iusto consequatur. Itaque at iure perspiciatis suscipit natus maxime. Nisi voluptas facere sint consequatur labore.<br>\nNeque illo molestiae ad qui maxime. Dolor sed vel alias quo. Aut nihil ut sint corrupti optio.<br>\nAperiam nostrum modi culpa modi natus voluptatem fugiat deserunt. Dolores aspernatur nobis eligendi esse repellat inventore. Autem aliquid quasi nihil accusantium est. Id quidem quibusdam quo tempora amet ipsum.</p>', 'Corrupti non eius voluptatibus culpa odio. Nam voluptas nisi voluptatem iusto consequatur. Itaque at iure perspiciatis suscipit natus maxime. Nisi voluptas facere sint consequatur labore.\nNeque illo molestiae ad qui maxime. Dolor sed vel alias quo. Aut nihil ut sint corrupti optio.\nAperiam nostrum modi culpa modi natus voluptatem fugiat deserunt. Dolores aspernatur nobis eligendi esse repellat inventore. Autem aliquid quasi nihil accusantium est. Id quidem quibusdam quo tempora amet ipsum.', 0, 1, 3, 2, 0, 0, 0, '2015-02-08 11:01:13', '2017-04-26 22:01:13'),
	(6, '<p>Ut quaerat quis et. Quisquam veritatis impedit alias. Quia velit sint perferendis.<br>\nIste libero voluptas neque praesentium qui optio quasi. Qui autem dolorum id laudantium aliquid. Sunt ut eum voluptatibus voluptas. Eius reprehenderit quia exercitationem tempora.<br>\nAssumenda culpa pariatur sit recusandae ex. Maiores harum ea quia eius vel omnis quasi possimus. Praesentium ut voluptas ratione unde officia aspernatur deserunt.</p>', 'Ut quaerat quis et. Quisquam veritatis impedit alias. Quia velit sint perferendis.\nIste libero voluptas neque praesentium qui optio quasi. Qui autem dolorum id laudantium aliquid. Sunt ut eum voluptatibus voluptas. Eius reprehenderit quia exercitationem tempora.\nAssumenda culpa pariatur sit recusandae ex. Maiores harum ea quia eius vel omnis quasi possimus. Praesentium ut voluptas ratione unde officia aspernatur deserunt.', 0, 1, 3, 3, 0, 0, 0, '2012-08-31 02:46:40', '2017-04-26 22:01:14'),
	(7, '<p>In placeat optio quia ea. Deleniti et harum ad esse. Sunt sint ab omnis exercitationem. Doloribus voluptatem est nobis reiciendis dicta accusantium.<br>\nQuia qui iste quaerat deserunt inventore nulla sint. Quia maxime voluptas tempora sapiente tempore. Explicabo ut nostrum quae nostrum aspernatur sunt. Blanditiis laborum et quibusdam distinctio. Et sed voluptates voluptatem temporibus.<br>\nNobis non quaerat libero velit dolor quaerat. Vel sint itaque provident sit rerum.</p>', 'In placeat optio quia ea. Deleniti et harum ad esse. Sunt sint ab omnis exercitationem. Doloribus voluptatem est nobis reiciendis dicta accusantium.\nQuia qui iste quaerat deserunt inventore nulla sint. Quia maxime voluptas tempora sapiente tempore. Explicabo ut nostrum quae nostrum aspernatur sunt. Blanditiis laborum et quibusdam distinctio. Et sed voluptates voluptatem temporibus.\nNobis non quaerat libero velit dolor quaerat. Vel sint itaque provident sit rerum.', 0, 3, 3, 3, 0, 0, 0, '2014-08-09 22:22:06', '2017-04-26 22:01:14'),
	(8, '<p>Iure placeat vel ea nemo illum debitis aliquam. Quo ipsum sequi dolorem qui magnam repellendus est. Voluptas eos magnam qui delectus.<br>\nTempora at eos deserunt qui dolores. Odio eos ea praesentium perspiciatis iure non. Placeat est vitae veritatis placeat voluptatem recusandae autem ut. Id maiores similique tenetur et aut voluptas mollitia.<br>\nAccusamus sed commodi voluptates non dignissimos qui id. Ea velit cupiditate et mollitia aut. Ea non doloribus quod id deleniti dolor iusto qui.</p>', 'Iure placeat vel ea nemo illum debitis aliquam. Quo ipsum sequi dolorem qui magnam repellendus est. Voluptas eos magnam qui delectus.\nTempora at eos deserunt qui dolores. Odio eos ea praesentium perspiciatis iure non. Placeat est vitae veritatis placeat voluptatem recusandae autem ut. Id maiores similique tenetur et aut voluptas mollitia.\nAccusamus sed commodi voluptates non dignissimos qui id. Ea velit cupiditate et mollitia aut. Ea non doloribus quod id deleniti dolor iusto qui.', 0, 1, 3, 3, 0, 0, 0, '2012-04-13 23:58:46', '2017-04-26 22:01:15'),
	(9, '<p>Omnis aliquid odio aut quaerat. Deleniti quod at voluptas nam illum earum. Rem sunt eius molestias veritatis. Dolorem quidem totam architecto doloremque eos aut illo.<br>\nEst voluptatem adipisci dignissimos consequatur et corporis omnis. Non ullam omnis velit quisquam delectus. Error repudiandae sit optio voluptatem deserunt.<br>\nUllam dolorum est voluptas est maxime. Et quia natus autem veniam. Error ad cupiditate quis aut consequuntur non quasi.</p>', 'Omnis aliquid odio aut quaerat. Deleniti quod at voluptas nam illum earum. Rem sunt eius molestias veritatis. Dolorem quidem totam architecto doloremque eos aut illo.\nEst voluptatem adipisci dignissimos consequatur et corporis omnis. Non ullam omnis velit quisquam delectus. Error repudiandae sit optio voluptatem deserunt.\nUllam dolorum est voluptas est maxime. Et quia natus autem veniam. Error ad cupiditate quis aut consequuntur non quasi.', 0, 1, 3, 5, 0, 0, 0, '2011-08-25 03:38:30', '2017-04-26 22:01:17'),
	(10, '<p>Dolore error saepe laborum qui eaque cumque nostrum. Impedit sapiente similique laboriosam sed deleniti dolore amet quod. Nihil sed incidunt id perspiciatis veniam qui.<br>\nAperiam ea aliquid veritatis fugiat minima alias recusandae. Reiciendis tenetur voluptatem itaque aut amet.<br>\nAliquam dolor repellendus hic accusamus vero omnis exercitationem. Ullam optio officiis perferendis beatae atque in id. Deserunt et incidunt voluptatem fugiat voluptatem assumenda in dolor. Unde et quo quia expedita veritatis.</p>', 'Dolore error saepe laborum qui eaque cumque nostrum. Impedit sapiente similique laboriosam sed deleniti dolore amet quod. Nihil sed incidunt id perspiciatis veniam qui.\nAperiam ea aliquid veritatis fugiat minima alias recusandae. Reiciendis tenetur voluptatem itaque aut amet.\nAliquam dolor repellendus hic accusamus vero omnis exercitationem. Ullam optio officiis perferendis beatae atque in id. Deserunt et incidunt voluptatem fugiat voluptatem assumenda in dolor. Unde et quo quia expedita veritatis.', 0, 2, 3, 6, 0, 0, 0, '2013-10-08 08:44:51', '2017-04-26 22:01:18'),
	(11, '<p>Consequatur voluptatem laudantium eos et temporibus eaque ut. Rerum quia et eum fuga corrupti voluptatem rem.<br>\nDeleniti veniam labore facilis ex itaque. Pariatur sit non fuga pariatur vel labore similique. Sed at qui atque non omnis. Rerum id perferendis deleniti magni molestiae voluptatem possimus. Earum quo odit beatae qui ut.<br>\nNon ut est reprehenderit molestiae praesentium qui aspernatur. Iusto ex beatae laudantium dolorum odit qui. Porro ut quidem a at dignissimos. Ipsam recusandae voluptatem occaecati.</p>', 'Consequatur voluptatem laudantium eos et temporibus eaque ut. Rerum quia et eum fuga corrupti voluptatem rem.\nDeleniti veniam labore facilis ex itaque. Pariatur sit non fuga pariatur vel labore similique. Sed at qui atque non omnis. Rerum id perferendis deleniti magni molestiae voluptatem possimus. Earum quo odit beatae qui ut.\nNon ut est reprehenderit molestiae praesentium qui aspernatur. Iusto ex beatae laudantium dolorum odit qui. Porro ut quidem a at dignissimos. Ipsam recusandae voluptatem occaecati.', 0, 2, 3, 6, 0, 0, 0, '2011-07-27 08:52:40', '2017-04-26 22:01:18'),
	(12, '<p>Qui iste non laborum impedit sed. Perspiciatis maiores qui sunt consectetur eum quia. Consequatur provident accusamus mollitia ut laudantium enim.<br>\nId ea ratione rerum laborum molestias. Ut officiis ut autem impedit. Sequi et vel praesentium dolore. Ut voluptatem iure rerum maxime autem non exercitationem.<br>\nIncidunt id qui at totam. Excepturi omnis maiores soluta sit eligendi.</p>', 'Qui iste non laborum impedit sed. Perspiciatis maiores qui sunt consectetur eum quia. Consequatur provident accusamus mollitia ut laudantium enim.\nId ea ratione rerum laborum molestias. Ut officiis ut autem impedit. Sequi et vel praesentium dolore. Ut voluptatem iure rerum maxime autem non exercitationem.\nIncidunt id qui at totam. Excepturi omnis maiores soluta sit eligendi.', 0, 2, 5, 7, 0, 0, 0, '2009-04-19 22:00:16', '2017-04-26 22:01:19'),
	(13, '<p>Neque optio et corporis soluta enim officiis. Cum qui consectetur nemo rem earum aliquid. Voluptatibus laboriosam neque perferendis totam fugiat consequatur molestiae. Dolorem voluptatem pariatur sint quas a cum placeat.<br>\nReiciendis quam laborum et qui voluptatem. Ea soluta molestias est aliquid consequuntur at ducimus. Nihil ut cupiditate inventore quas.<br>\nEst sequi quibusdam aut dignissimos. Aspernatur voluptas aperiam magni.</p>', 'Neque optio et corporis soluta enim officiis. Cum qui consectetur nemo rem earum aliquid. Voluptatibus laboriosam neque perferendis totam fugiat consequatur molestiae. Dolorem voluptatem pariatur sint quas a cum placeat.\nReiciendis quam laborum et qui voluptatem. Ea soluta molestias est aliquid consequuntur at ducimus. Nihil ut cupiditate inventore quas.\nEst sequi quibusdam aut dignissimos. Aspernatur voluptas aperiam magni.', 0, 2, 5, 8, 0, 0, 0, '2012-05-29 10:15:28', '2017-04-26 22:01:20'),
	(14, '<p>Dolores aut qui ea necessitatibus a. Nemo et similique sit error eaque.<br>\nDelectus reprehenderit voluptatem accusantium consequatur dolores id. Labore qui qui perspiciatis quis. Nisi rerum omnis rem aut quod rerum vel. Distinctio doloribus voluptatum quasi repudiandae.<br>\nQuia quis omnis nihil consequatur. Placeat earum nam accusantium aut molestiae. Reiciendis rerum velit perferendis nesciunt aut.</p>', 'Dolores aut qui ea necessitatibus a. Nemo et similique sit error eaque.\nDelectus reprehenderit voluptatem accusantium consequatur dolores id. Labore qui qui perspiciatis quis. Nisi rerum omnis rem aut quod rerum vel. Distinctio doloribus voluptatum quasi repudiandae.\nQuia quis omnis nihil consequatur. Placeat earum nam accusantium aut molestiae. Reiciendis rerum velit perferendis nesciunt aut.', 0, 4, 5, 8, 0, 0, 0, '2011-05-31 15:59:33', '2017-04-26 22:01:20'),
	(15, '<p>Ducimus error fuga ut dolor id voluptatem. Aut ipsa rem qui ab ut vitae.<br>\nEst repudiandae dolorum rem aut et. Saepe similique incidunt aspernatur modi cum nihil.<br>\nQuo libero ab placeat placeat voluptas velit maiores. Ipsa rem ratione eligendi qui quas. Mollitia consequuntur ut atque tempore necessitatibus. Culpa consequatur molestiae dicta.</p>', 'Ducimus error fuga ut dolor id voluptatem. Aut ipsa rem qui ab ut vitae.\nEst repudiandae dolorum rem aut et. Saepe similique incidunt aspernatur modi cum nihil.\nQuo libero ab placeat placeat voluptas velit maiores. Ipsa rem ratione eligendi qui quas. Mollitia consequuntur ut atque tempore necessitatibus. Culpa consequatur molestiae dicta.', 0, 1, 6, 9, 0, 0, 0, '2012-07-04 03:30:44', '2017-04-26 22:01:22'),
	(16, '<p>Excepturi et aut et quo et laudantium dolor. Dolor sed voluptas ut explicabo molestias dicta.<br>\nEt assumenda rem quia nihil. Molestiae est at cumque eum. Aut enim corrupti amet labore dolorem culpa. Esse nesciunt voluptatum sit rem rem odit tempora omnis.<br>\nLaboriosam autem sapiente et corporis. Qui cumque ratione provident at dolorem facere optio. Est doloremque voluptas deleniti voluptas.</p>', 'Excepturi et aut et quo et laudantium dolor. Dolor sed voluptas ut explicabo molestias dicta.\nEt assumenda rem quia nihil. Molestiae est at cumque eum. Aut enim corrupti amet labore dolorem culpa. Esse nesciunt voluptatum sit rem rem odit tempora omnis.\nLaboriosam autem sapiente et corporis. Qui cumque ratione provident at dolorem facere optio. Est doloremque voluptas deleniti voluptas.', 0, 3, 6, 10, 0, 0, 0, '2010-01-01 16:00:34', '2017-04-26 22:01:23'),
	(17, '<p>Quos accusamus animi atque ut. Iste sint sunt rerum laboriosam non neque sed. In voluptatem nihil hic et et corrupti distinctio nihil.<br>\nAsperiores ab corporis enim ut. Voluptates minima quam eum cumque nostrum architecto. Sed architecto quia sed.<br>\nBeatae non illum qui cum quas. Laboriosam tempore repellat nihil et soluta vel. Consequuntur neque modi velit quod voluptatem corrupti rem.<br>\nPraesentium nulla et labore voluptates dolor consequatur quod. Odit nobis voluptatibus quia sed unde.</p>', 'Quos accusamus animi atque ut. Iste sint sunt rerum laboriosam non neque sed. In voluptatem nihil hic et et corrupti distinctio nihil.\nAsperiores ab corporis enim ut. Voluptates minima quam eum cumque nostrum architecto. Sed architecto quia sed.\nBeatae non illum qui cum quas. Laboriosam tempore repellat nihil et soluta vel. Consequuntur neque modi velit quod voluptatem corrupti rem.\nPraesentium nulla et labore voluptates dolor consequatur quod. Odit nobis voluptatibus quia sed unde.', 0, 1, 6, 11, 0, 0, 0, '2012-12-12 18:56:54', '2017-04-26 22:01:24'),
	(18, '<p>Laboriosam quasi dolores sed. Omnis recusandae veniam cumque deleniti aliquam officia. In alias quis totam sit accusantium. Eveniet provident ipsam quia autem vel veritatis impedit.<br>\nQuia inventore ut libero temporibus. Quis laboriosam et vel. Perspiciatis ullam doloribus atque dolorem sit sapiente. Provident aut sit ut.<br>\nA eveniet nihil velit alias nostrum quia. Odit enim perferendis aut voluptas. Quas omnis non ducimus perferendis possimus.</p>', 'Laboriosam quasi dolores sed. Omnis recusandae veniam cumque deleniti aliquam officia. In alias quis totam sit accusantium. Eveniet provident ipsam quia autem vel veritatis impedit.\nQuia inventore ut libero temporibus. Quis laboriosam et vel. Perspiciatis ullam doloribus atque dolorem sit sapiente. Provident aut sit ut.\nA eveniet nihil velit alias nostrum quia. Odit enim perferendis aut voluptas. Quas omnis non ducimus perferendis possimus.', 0, 4, 6, 11, 0, 0, 0, '2008-08-06 09:45:30', '2017-04-26 22:01:24'),
	(19, '<p>Voluptas id quo facere. Harum placeat architecto esse porro. Incidunt cumque culpa veritatis repellat. Nulla iusto maxime aliquid non.<br>\nNumquam et molestias minima voluptatem libero ratione. Repellendus iste nesciunt omnis impedit. Qui animi sunt aut eos illo est. Aut ut et qui modi.<br>\nError quam mollitia illum ab ipsum ut eligendi. Eum ut voluptatem ea debitis error numquam nam voluptatem.</p>', 'Voluptas id quo facere. Harum placeat architecto esse porro. Incidunt cumque culpa veritatis repellat. Nulla iusto maxime aliquid non.\nNumquam et molestias minima voluptatem libero ratione. Repellendus iste nesciunt omnis impedit. Qui animi sunt aut eos illo est. Aut ut et qui modi.\nError quam mollitia illum ab ipsum ut eligendi. Eum ut voluptatem ea debitis error numquam nam voluptatem.', 0, 3, 6, 12, 0, 0, 0, '2015-01-18 02:11:24', '2017-04-26 22:01:25'),
	(20, '<p>Autem beatae quam alias eos modi. Animi quibusdam et aut commodi iusto non voluptatem. Ut non recusandae aspernatur autem quas.<br>\nVoluptatem dolorem expedita consequuntur qui delectus ut aliquid quo. Cupiditate aut placeat natus. Perspiciatis itaque cum ut omnis quis molestiae facere.<br>\nQuod quo rerum qui. Atque accusamus modi natus blanditiis. Et dicta sit corporis culpa deleniti animi placeat quis.</p>', 'Autem beatae quam alias eos modi. Animi quibusdam et aut commodi iusto non voluptatem. Ut non recusandae aspernatur autem quas.\nVoluptatem dolorem expedita consequuntur qui delectus ut aliquid quo. Cupiditate aut placeat natus. Perspiciatis itaque cum ut omnis quis molestiae facere.\nQuod quo rerum qui. Atque accusamus modi natus blanditiis. Et dicta sit corporis culpa deleniti animi placeat quis.', 0, 2, 6, 12, 0, 0, 0, '2015-08-18 09:44:32', '2017-04-26 22:01:25'),
	(21, '<p>Sapiente nesciunt esse saepe quos quidem sit consequatur dolore. Modi voluptatibus aut veniam voluptas. Odit est et dicta ullam. Fuga ipsam quod facilis nisi quis.<br>\nDolorem laudantium maxime repudiandae commodi reiciendis. Praesentium nemo saepe molestias aut molestias reprehenderit. Accusamus voluptatum animi doloribus eum sit ipsa nesciunt totam. Tempora quis in mollitia id earum dolorem. Sapiente suscipit maiores aut sunt ut quas esse.</p>', 'Sapiente nesciunt esse saepe quos quidem sit consequatur dolore. Modi voluptatibus aut veniam voluptas. Odit est et dicta ullam. Fuga ipsam quod facilis nisi quis.\nDolorem laudantium maxime repudiandae commodi reiciendis. Praesentium nemo saepe molestias aut molestias reprehenderit. Accusamus voluptatum animi doloribus eum sit ipsa nesciunt totam. Tempora quis in mollitia id earum dolorem. Sapiente suscipit maiores aut sunt ut quas esse.', 0, 4, 6, 12, 0, 0, 0, '2009-03-22 05:17:18', '2017-04-26 22:01:26'),
	(22, '<p>Exercitationem et dolores et. Et labore et qui voluptate. Omnis ab ea eum.<br>\nAut accusantium distinctio expedita consequatur quidem et. Rerum voluptatem et et deleniti qui ut aperiam. Est doloremque totam necessitatibus enim aperiam laboriosam temporibus.<br>\nVoluptates possimus dolorum molestias illum. Eius distinctio fugit quis dolores consequuntur. Id est non reprehenderit est. Dignissimos voluptatibus ipsam quia ea.</p>', 'Exercitationem et dolores et. Et labore et qui voluptate. Omnis ab ea eum.\nAut accusantium distinctio expedita consequatur quidem et. Rerum voluptatem et et deleniti qui ut aperiam. Est doloremque totam necessitatibus enim aperiam laboriosam temporibus.\nVoluptates possimus dolorum molestias illum. Eius distinctio fugit quis dolores consequuntur. Id est non reprehenderit est. Dignissimos voluptatibus ipsam quia ea.', 0, 1, 7, 13, 0, 0, 0, '2008-02-21 04:35:33', '2017-04-26 22:01:27'),
	(23, '<p>Libero nostrum non eum molestias molestias. Vero et unde adipisci distinctio dolorum tempora magnam. Voluptate necessitatibus aspernatur architecto.<br>\nTempora accusantium ut dicta sint autem. Eos recusandae recusandae molestiae nemo. Corporis possimus cumque consequatur ad fugit iure reiciendis. Non aut ut consequatur recusandae beatae.<br>\nAtque magnam vitae praesentium saepe sed id qui. Enim nisi et veritatis pariatur voluptatibus et quae.</p>', 'Libero nostrum non eum molestias molestias. Vero et unde adipisci distinctio dolorum tempora magnam. Voluptate necessitatibus aspernatur architecto.\nTempora accusantium ut dicta sint autem. Eos recusandae recusandae molestiae nemo. Corporis possimus cumque consequatur ad fugit iure reiciendis. Non aut ut consequatur recusandae beatae.\nAtque magnam vitae praesentium saepe sed id qui. Enim nisi et veritatis pariatur voluptatibus et quae.', 0, 3, 7, 13, 0, 0, 0, '2011-01-12 04:49:36', '2017-04-26 22:01:27'),
	(24, '<p>Eum quam quasi eaque non. Ratione in reiciendis autem autem similique. Autem maxime temporibus veritatis architecto. Consequuntur pariatur omnis omnis autem.<br>\nDeserunt tempore molestiae facilis vel earum. Iste ipsa adipisci qui sunt est expedita. Et soluta minus repudiandae odio. Reiciendis quia aliquid quo quis natus animi velit est.<br>\nTempora et qui tempore iure aut dolorem. Eos voluptatem culpa est voluptate consequatur saepe. Veritatis modi rem quasi est cupiditate. Dolorem voluptatum qui aliquid tenetur.</p>', 'Eum quam quasi eaque non. Ratione in reiciendis autem autem similique. Autem maxime temporibus veritatis architecto. Consequuntur pariatur omnis omnis autem.\nDeserunt tempore molestiae facilis vel earum. Iste ipsa adipisci qui sunt est expedita. Et soluta minus repudiandae odio. Reiciendis quia aliquid quo quis natus animi velit est.\nTempora et qui tempore iure aut dolorem. Eos voluptatem culpa est voluptate consequatur saepe. Veritatis modi rem quasi est cupiditate. Dolorem voluptatum qui aliquid tenetur.', 0, 1, 7, 13, 0, 0, 0, '2008-12-25 15:22:53', '2017-04-26 22:01:28'),
	(25, '<p>Voluptatibus voluptas neque ab explicabo dolor quia. Omnis et id dolore vel. Qui maxime consequuntur ipsa omnis.<br>\nAliquid aut pariatur sunt similique neque sunt. Est consequuntur vero aut dicta aliquid fugiat. Accusantium iusto est odit fugiat voluptates dolore laboriosam perferendis. Recusandae non fugiat at velit doloremque molestiae harum.</p>', 'Voluptatibus voluptas neque ab explicabo dolor quia. Omnis et id dolore vel. Qui maxime consequuntur ipsa omnis.\nAliquid aut pariatur sunt similique neque sunt. Est consequuntur vero aut dicta aliquid fugiat. Accusantium iusto est odit fugiat voluptates dolore laboriosam perferendis. Recusandae non fugiat at velit doloremque molestiae harum.', 0, 6, 7, 14, 0, 0, 0, '2015-12-24 06:29:16', '2017-04-26 22:01:29'),
	(26, '<p>Illum voluptatem necessitatibus quidem quos. Inventore rem recusandae animi vitae occaecati et. Ipsam ut aut sit in voluptatem qui ullam. Non optio aut dolorem non qui.<br>\nVoluptas dolores qui optio nihil est praesentium. Excepturi adipisci sapiente aperiam maiores ut nostrum voluptas. Ratione et in ea maiores suscipit et. Fugit omnis culpa qui et rerum consequuntur vel minima.</p>', 'Illum voluptatem necessitatibus quidem quos. Inventore rem recusandae animi vitae occaecati et. Ipsam ut aut sit in voluptatem qui ullam. Non optio aut dolorem non qui.\nVoluptas dolores qui optio nihil est praesentium. Excepturi adipisci sapiente aperiam maiores ut nostrum voluptas. Ratione et in ea maiores suscipit et. Fugit omnis culpa qui et rerum consequuntur vel minima.', 0, 6, 7, 14, 0, 0, 0, '2007-05-26 09:19:37', '2017-04-26 22:01:29'),
	(27, '<p>Corporis voluptatem provident officia qui qui rerum. Soluta dolores quia qui necessitatibus et ut. Ea voluptate quisquam quia placeat rem.<br>\nEt repudiandae facere vel esse in ipsam recusandae. Nostrum fugit eos sit quae ipsa. Molestias ex omnis et facere. Sit non voluptas quis aliquid sit.<br>\nAut placeat in quia aut dolor. Soluta sint est quod enim beatae excepturi. Corrupti voluptatem ut quia consequatur aperiam molestiae aut. Perspiciatis incidunt repellendus autem sit exercitationem harum voluptatibus.</p>', 'Corporis voluptatem provident officia qui qui rerum. Soluta dolores quia qui necessitatibus et ut. Ea voluptate quisquam quia placeat rem.\nEt repudiandae facere vel esse in ipsam recusandae. Nostrum fugit eos sit quae ipsa. Molestias ex omnis et facere. Sit non voluptas quis aliquid sit.\nAut placeat in quia aut dolor. Soluta sint est quod enim beatae excepturi. Corrupti voluptatem ut quia consequatur aperiam molestiae aut. Perspiciatis incidunt repellendus autem sit exercitationem harum voluptatibus.', 0, 2, 7, 15, 0, 0, 0, '2010-08-14 03:55:02', '2017-04-26 22:01:30'),
	(28, '<p>Et qui perspiciatis eligendi ipsa nisi. Et numquam ut ipsa architecto possimus tempore. Earum perferendis deserunt voluptas nesciunt minus tenetur quia.<br>\nVoluptate aut sed in officia. Quia rerum aspernatur illo sunt. Molestiae iusto excepturi laboriosam dolorem.<br>\nQui aut illum esse voluptates recusandae. Doloremque deserunt quae rerum sed voluptas. Vero facilis vitae nam dicta dolorem vero quasi. Dolores enim dolorem eum amet quibusdam nobis quibusdam.</p>', 'Et qui perspiciatis eligendi ipsa nisi. Et numquam ut ipsa architecto possimus tempore. Earum perferendis deserunt voluptas nesciunt minus tenetur quia.\nVoluptate aut sed in officia. Quia rerum aspernatur illo sunt. Molestiae iusto excepturi laboriosam dolorem.\nQui aut illum esse voluptates recusandae. Doloremque deserunt quae rerum sed voluptas. Vero facilis vitae nam dicta dolorem vero quasi. Dolores enim dolorem eum amet quibusdam nobis quibusdam.', 0, 6, 7, 15, 0, 0, 0, '2009-07-26 08:33:45', '2017-04-26 22:01:31'),
	(29, '<p>Quo quia et earum tempora. Sed non officiis officiis et similique. Qui consequuntur impedit reprehenderit. Saepe fugit qui et magnam in id reprehenderit.<br>\nQuod praesentium et voluptas recusandae. Velit eum vel et eaque autem. Nostrum in odio velit pariatur iste praesentium velit. Repellat quia vitae molestiae aut amet laudantium sapiente.</p>', 'Quo quia et earum tempora. Sed non officiis officiis et similique. Qui consequuntur impedit reprehenderit. Saepe fugit qui et magnam in id reprehenderit.\nQuod praesentium et voluptas recusandae. Velit eum vel et eaque autem. Nostrum in odio velit pariatur iste praesentium velit. Repellat quia vitae molestiae aut amet laudantium sapiente.', 0, 2, 7, 16, 0, 0, 0, '2016-07-04 13:38:01', '2017-04-26 22:01:32'),
	(30, '<p>Odit qui necessitatibus quia laboriosam quis cum. Veritatis ipsam modi quibusdam quae aperiam quas. Debitis alias ut veritatis molestias. Aperiam et impedit sit rerum unde qui.<br>\nVoluptatem quis aut enim ea vel vel sint. Cumque quia sed eum fugit deserunt ut laboriosam aut. Necessitatibus rem et quas.<br>\nMollitia et minus natus voluptatem enim sed. Earum necessitatibus molestias fugit voluptates. Modi illo corrupti velit sapiente voluptatem accusamus. Quisquam aut quibusdam numquam rerum non.</p>', 'Odit qui necessitatibus quia laboriosam quis cum. Veritatis ipsam modi quibusdam quae aperiam quas. Debitis alias ut veritatis molestias. Aperiam et impedit sit rerum unde qui.\nVoluptatem quis aut enim ea vel vel sint. Cumque quia sed eum fugit deserunt ut laboriosam aut. Necessitatibus rem et quas.\nMollitia et minus natus voluptatem enim sed. Earum necessitatibus molestias fugit voluptates. Modi illo corrupti velit sapiente voluptatem accusamus. Quisquam aut quibusdam numquam rerum non.', 0, 1, 8, 17, 0, 0, 0, '2013-03-02 12:12:53', '2017-04-26 22:01:33'),
	(31, '<p>Excepturi dolores at molestiae nesciunt quia voluptatem. Assumenda maiores voluptatem rem nihil sed qui aut. Ut et sit et voluptatem recusandae.<br>\nEos assumenda et rerum. Consectetur ut rerum non.<br>\nOmnis eius eveniet delectus consequuntur tempore vel corporis. Voluptatem ut enim beatae dolorum in optio. Non sit cum enim nihil voluptatem.<br>\nUt consequatur deleniti distinctio laudantium. Qui alias ut necessitatibus voluptas animi. Aperiam voluptas et omnis voluptatum in. Dolor et reprehenderit dicta quo nam quia.</p>', 'Excepturi dolores at molestiae nesciunt quia voluptatem. Assumenda maiores voluptatem rem nihil sed qui aut. Ut et sit et voluptatem recusandae.\nEos assumenda et rerum. Consectetur ut rerum non.\nOmnis eius eveniet delectus consequuntur tempore vel corporis. Voluptatem ut enim beatae dolorum in optio. Non sit cum enim nihil voluptatem.\nUt consequatur deleniti distinctio laudantium. Qui alias ut necessitatibus voluptas animi. Aperiam voluptas et omnis voluptatum in. Dolor et reprehenderit dicta quo nam quia.', 0, 7, 8, 17, 0, 0, 0, '2016-07-07 21:08:02', '2017-04-26 22:01:33'),
	(32, '<p>Non reprehenderit qui enim quia. Et blanditiis iste quis omnis numquam suscipit nihil. Eaque debitis sed dolorem et quia earum.<br>\nRerum doloribus deserunt quis blanditiis laboriosam eius. Nisi placeat quasi et quos occaecati aut expedita. Repellendus eos fuga quasi numquam sunt autem.<br>\nPlaceat fuga dolorem odit consequatur non quis possimus. Ut praesentium sint repellat eos nam. Consectetur illo at vel deserunt.</p>', 'Non reprehenderit qui enim quia. Et blanditiis iste quis omnis numquam suscipit nihil. Eaque debitis sed dolorem et quia earum.\nRerum doloribus deserunt quis blanditiis laboriosam eius. Nisi placeat quasi et quos occaecati aut expedita. Repellendus eos fuga quasi numquam sunt autem.\nPlaceat fuga dolorem odit consequatur non quis possimus. Ut praesentium sint repellat eos nam. Consectetur illo at vel deserunt.', 0, 2, 8, 19, 0, 0, 0, '2008-02-13 11:15:11', '2017-04-26 22:01:35'),
	(33, '<p>Itaque consequuntur tempore aspernatur quos facere. Ut est repudiandae sequi enim. Voluptas provident corrupti quod autem aut.<br>\nVoluptates nisi sit beatae saepe quasi vel est. Aut enim possimus ex numquam aut est. Debitis ratione deserunt commodi facilis commodi natus sint.<br>\nRatione tempora fuga enim. Blanditiis velit et et in nihil sed. Voluptatem unde doloremque accusamus et ab velit.</p>', 'Itaque consequuntur tempore aspernatur quos facere. Ut est repudiandae sequi enim. Voluptas provident corrupti quod autem aut.\nVoluptates nisi sit beatae saepe quasi vel est. Aut enim possimus ex numquam aut est. Debitis ratione deserunt commodi facilis commodi natus sint.\nRatione tempora fuga enim. Blanditiis velit et et in nihil sed. Voluptatem unde doloremque accusamus et ab velit.', 0, 7, 8, 19, 0, 0, 0, '2012-12-27 23:46:32', '2017-04-26 22:01:36'),
	(34, '<p>Expedita quos vitae libero ratione omnis fugit fugiat. Minima vel in praesentium qui. Voluptatem magni dolor non dicta explicabo vero perferendis. Nihil et quia autem.<br>\nRatione a voluptate animi. Ipsum autem omnis quasi accusamus ex quos error ut. Illo blanditiis voluptatem dolor maiores minus adipisci ut. Ut odit sit vero tempora odit. Voluptas corporis temporibus aut sed.</p>', 'Expedita quos vitae libero ratione omnis fugit fugiat. Minima vel in praesentium qui. Voluptatem magni dolor non dicta explicabo vero perferendis. Nihil et quia autem.\nRatione a voluptate animi. Ipsum autem omnis quasi accusamus ex quos error ut. Illo blanditiis voluptatem dolor maiores minus adipisci ut. Ut odit sit vero tempora odit. Voluptas corporis temporibus aut sed.', 0, 2, 8, 19, 0, 0, 0, '2013-11-10 05:32:14', '2017-04-26 22:01:36'),
	(35, '<p>Non at qui nulla sint quisquam inventore ex error. Fugiat hic qui voluptatum distinctio eos illo magni. Dolore rem et natus animi velit. Porro consequatur cum ut consequuntur voluptas.<br>\nEt odio alias beatae quo. Voluptas voluptas aut esse vel. Ut officiis quisquam sint temporibus impedit quis.<br>\nEos facilis non asperiores eveniet. Quae et ratione qui quae autem. Nobis aut rerum omnis asperiores accusamus pariatur.<br>\nEt animi sapiente odio molestias aut velit beatae. Neque dolores omnis quis.</p>', 'Non at qui nulla sint quisquam inventore ex error. Fugiat hic qui voluptatum distinctio eos illo magni. Dolore rem et natus animi velit. Porro consequatur cum ut consequuntur voluptas.\nEt odio alias beatae quo. Voluptas voluptas aut esse vel. Ut officiis quisquam sint temporibus impedit quis.\nEos facilis non asperiores eveniet. Quae et ratione qui quae autem. Nobis aut rerum omnis asperiores accusamus pariatur.\nEt animi sapiente odio molestias aut velit beatae. Neque dolores omnis quis.', 0, 2, 8, 20, 0, 0, 0, '2011-08-24 15:40:37', '2017-04-26 22:01:37'),
	(36, '<p>Nemo maxime labore voluptates numquam sed ad explicabo. Ab nobis ad nostrum doloremque quis in. Quidem unde explicabo non error repellat quas possimus.<br>\nConsequatur consequatur quos consequatur beatae. Voluptate exercitationem fugit asperiores vitae. Id esse ea consequuntur rerum architecto omnis minus incidunt.</p>', 'Nemo maxime labore voluptates numquam sed ad explicabo. Ab nobis ad nostrum doloremque quis in. Quidem unde explicabo non error repellat quas possimus.\nConsequatur consequatur quos consequatur beatae. Voluptate exercitationem fugit asperiores vitae. Id esse ea consequuntur rerum architecto omnis minus incidunt.', 0, 1, 8, 20, 0, 0, 0, '2015-10-27 17:43:19', '2017-04-26 22:01:37'),
	(37, '<p>Ut dolores consequatur eligendi optio. Aut rem et quia perspiciatis velit veniam. Et itaque blanditiis optio quis aliquid.<br>\nDolor inventore qui sint molestiae enim cupiditate. Et molestias quis enim suscipit aut. Necessitatibus nulla perferendis vero. Aperiam rerum placeat a quis.<br>\nTempora explicabo sapiente optio non eum ducimus cumque tenetur. Nihil et non harum facere delectus et illum iusto. Architecto et ratione esse est. Beatae eaque totam qui ut quo aut.</p>', 'Ut dolores consequatur eligendi optio. Aut rem et quia perspiciatis velit veniam. Et itaque blanditiis optio quis aliquid.\nDolor inventore qui sint molestiae enim cupiditate. Et molestias quis enim suscipit aut. Necessitatibus nulla perferendis vero. Aperiam rerum placeat a quis.\nTempora explicabo sapiente optio non eum ducimus cumque tenetur. Nihil et non harum facere delectus et illum iusto. Architecto et ratione esse est. Beatae eaque totam qui ut quo aut.', 0, 1, 8, 20, 0, 0, 0, '2017-01-15 05:26:43', '2017-04-26 22:01:38'),
	(38, '<p>Nobis ut in optio molestiae. Voluptas non a quis sapiente animi enim. Molestiae et necessitatibus consectetur est. Aspernatur perferendis assumenda necessitatibus dolores. Sunt ex illum molestiae quo.<br>\nRerum repudiandae debitis unde. Quidem totam et laboriosam quis. Est alias fugit eos saepe vero voluptatem debitis. Et perspiciatis voluptas facilis facere nihil reiciendis.</p>', 'Nobis ut in optio molestiae. Voluptas non a quis sapiente animi enim. Molestiae et necessitatibus consectetur est. Aspernatur perferendis assumenda necessitatibus dolores. Sunt ex illum molestiae quo.\nRerum repudiandae debitis unde. Quidem totam et laboriosam quis. Est alias fugit eos saepe vero voluptatem debitis. Et perspiciatis voluptas facilis facere nihil reiciendis.', 0, 1, 9, 21, 0, 0, 0, '2015-07-21 05:54:36', '2017-04-26 22:01:39'),
	(39, '<p>Et corporis repellat ut et amet neque architecto. Et vitae laboriosam voluptate et tempore. Quae aspernatur voluptas dignissimos non expedita dolores dolorem et. Aut et doloribus iusto sit.<br>\nDolorem voluptatem enim blanditiis. Culpa repellendus qui sequi quae omnis qui libero. Rerum ducimus vel nam corporis. Recusandae nihil cumque inventore accusantium.</p>', 'Et corporis repellat ut et amet neque architecto. Et vitae laboriosam voluptate et tempore. Quae aspernatur voluptas dignissimos non expedita dolores dolorem et. Aut et doloribus iusto sit.\nDolorem voluptatem enim blanditiis. Culpa repellendus qui sequi quae omnis qui libero. Rerum ducimus vel nam corporis. Recusandae nihil cumque inventore accusantium.', 0, 6, 9, 21, 0, 0, 0, '2011-05-27 00:25:22', '2017-04-26 22:01:39'),
	(40, '<p>Aliquam quo sit rerum vel quibusdam laborum et. Blanditiis quia debitis aut quam. Fugit deleniti officia maxime.<br>\nPerferendis praesentium veniam expedita asperiores sunt illum amet. Sed voluptas omnis suscipit debitis. Cupiditate ut eius ea molestiae maiores neque quae.<br>\nQuis quae molestias quisquam autem quae voluptatibus. Occaecati enim autem quasi sint. Ducimus porro vel fuga accusantium doloremque ipsam. A repellat recusandae corrupti labore corporis quis.</p>', 'Aliquam quo sit rerum vel quibusdam laborum et. Blanditiis quia debitis aut quam. Fugit deleniti officia maxime.\nPerferendis praesentium veniam expedita asperiores sunt illum amet. Sed voluptas omnis suscipit debitis. Cupiditate ut eius ea molestiae maiores neque quae.\nQuis quae molestias quisquam autem quae voluptatibus. Occaecati enim autem quasi sint. Ducimus porro vel fuga accusantium doloremque ipsam. A repellat recusandae corrupti labore corporis quis.', 0, 8, 9, 22, 0, 0, 0, '2007-11-28 14:16:48', '2017-04-26 22:01:40'),
	(41, '<p>Ut corrupti illo dolorum. Amet molestiae aspernatur est ut asperiores dolores. Nam cum eum alias rem omnis.<br>\nEt dignissimos consequatur deserunt perspiciatis ducimus. Repellat veritatis id sint qui tempore asperiores quidem. Voluptate sit sunt in nobis.<br>\nVoluptatem sit beatae sequi fugiat cupiditate consequatur ullam deleniti. Quis consectetur incidunt suscipit. Aut dolorum commodi atque natus labore. Labore et aliquid qui enim accusamus.</p>', 'Ut corrupti illo dolorum. Amet molestiae aspernatur est ut asperiores dolores. Nam cum eum alias rem omnis.\nEt dignissimos consequatur deserunt perspiciatis ducimus. Repellat veritatis id sint qui tempore asperiores quidem. Voluptate sit sunt in nobis.\nVoluptatem sit beatae sequi fugiat cupiditate consequatur ullam deleniti. Quis consectetur incidunt suscipit. Aut dolorum commodi atque natus labore. Labore et aliquid qui enim accusamus.', 0, 6, 9, 23, 0, 0, 0, '2013-03-22 21:08:31', '2017-04-26 22:01:42'),
	(42, '<p>Quo assumenda consequatur voluptas ea sint. Id quas et sint a omnis aperiam iure. Sapiente voluptate nobis assumenda ipsa quae aliquam labore. Facere molestiae nemo quod quo.<br>\nEst accusamus voluptatem pariatur aperiam porro dolores praesentium. Autem tempora laboriosam minima et. Cumque consequatur sit ipsa non. Nostrum culpa ea est.<br>\nExcepturi eum suscipit aut voluptatum nobis. Enim hic aliquam quia ipsa accusantium consequatur. Veniam sunt sed ea ea. Sapiente nostrum et ea rerum quaerat non.</p>', 'Quo assumenda consequatur voluptas ea sint. Id quas et sint a omnis aperiam iure. Sapiente voluptate nobis assumenda ipsa quae aliquam labore. Facere molestiae nemo quod quo.\nEst accusamus voluptatem pariatur aperiam porro dolores praesentium. Autem tempora laboriosam minima et. Cumque consequatur sit ipsa non. Nostrum culpa ea est.\nExcepturi eum suscipit aut voluptatum nobis. Enim hic aliquam quia ipsa accusantium consequatur. Veniam sunt sed ea ea. Sapiente nostrum et ea rerum quaerat non.', 0, 2, 9, 23, 0, 0, 0, '2015-09-05 03:02:33', '2017-04-26 22:01:42'),
	(43, '<p>Rem cum sunt dolor itaque rem iure quidem. Id reprehenderit esse explicabo eligendi dolore. Id beatae qui delectus praesentium animi quidem perspiciatis. Est voluptate illum velit error.<br>\nPlaceat necessitatibus qui maxime aspernatur quas unde. Possimus hic earum eveniet consequuntur et sint ab.<br>\nExpedita nihil dolorum maiores est possimus quaerat dolores rem. Voluptas quae et sed in qui voluptatem. Est esse qui natus rerum. Accusamus aut error quam.</p>', 'Rem cum sunt dolor itaque rem iure quidem. Id reprehenderit esse explicabo eligendi dolore. Id beatae qui delectus praesentium animi quidem perspiciatis. Est voluptate illum velit error.\nPlaceat necessitatibus qui maxime aspernatur quas unde. Possimus hic earum eveniet consequuntur et sint ab.\nExpedita nihil dolorum maiores est possimus quaerat dolores rem. Voluptas quae et sed in qui voluptatem. Est esse qui natus rerum. Accusamus aut error quam.', 0, 4, 10, 24, 0, 0, 0, '2011-10-28 12:57:22', '2017-04-26 22:01:43'),
	(44, '<p>Nisi eius eius nihil a consectetur. Perferendis veniam cum molestiae voluptatem. Autem repellendus voluptatem qui quae suscipit ratione pariatur.<br>\nUt delectus atque earum sapiente beatae pariatur quam. Eius sunt sit amet. Quo officia delectus rerum nobis sapiente velit quae.<br>\nEt corporis delectus veritatis rerum. Praesentium facere pariatur pariatur omnis. Ut consequatur architecto sint sequi eligendi excepturi. Nesciunt quidem sint sunt nesciunt sed soluta minus.</p>', 'Nisi eius eius nihil a consectetur. Perferendis veniam cum molestiae voluptatem. Autem repellendus voluptatem qui quae suscipit ratione pariatur.\nUt delectus atque earum sapiente beatae pariatur quam. Eius sunt sit amet. Quo officia delectus rerum nobis sapiente velit quae.\nEt corporis delectus veritatis rerum. Praesentium facere pariatur pariatur omnis. Ut consequatur architecto sint sequi eligendi excepturi. Nesciunt quidem sint sunt nesciunt sed soluta minus.', 0, 8, 10, 24, 0, 0, 0, '2010-12-12 09:11:32', '2017-04-26 22:01:43'),
	(45, '<p>Expedita occaecati molestiae maxime consequuntur. Eum non eos modi velit. Dolorem ut consequatur mollitia pariatur.<br>\nConsequuntur id rem quod ad vel soluta commodi. Facere ipsa neque sapiente delectus fugit et ab. Maiores doloremque harum culpa velit repudiandae nobis. Quia quia corrupti est vel.</p>', 'Expedita occaecati molestiae maxime consequuntur. Eum non eos modi velit. Dolorem ut consequatur mollitia pariatur.\nConsequuntur id rem quod ad vel soluta commodi. Facere ipsa neque sapiente delectus fugit et ab. Maiores doloremque harum culpa velit repudiandae nobis. Quia quia corrupti est vel.', 0, 2, 10, 25, 0, 0, 0, '2008-04-24 20:39:22', '2017-04-26 22:01:44'),
	(46, '<p>Quod tempore est non molestias. Aut dolorem dicta est consequatur eius laudantium sapiente. Voluptas autem minima error voluptatem possimus distinctio sunt. Corrupti sed autem dolor doloribus sit sit.<br>\nOdit eum omnis fuga officiis expedita. Dolore veritatis aliquid ad rerum et. Eius aut provident perferendis nihil ratione eos quam. Nostrum aperiam natus praesentium.</p>', 'Quod tempore est non molestias. Aut dolorem dicta est consequatur eius laudantium sapiente. Voluptas autem minima error voluptatem possimus distinctio sunt. Corrupti sed autem dolor doloribus sit sit.\nOdit eum omnis fuga officiis expedita. Dolore veritatis aliquid ad rerum et. Eius aut provident perferendis nihil ratione eos quam. Nostrum aperiam natus praesentium.', 0, 1, 10, 26, 0, 0, 0, '2009-11-23 20:33:24', '2017-04-26 22:01:45'),
	(47, '<p>Cumque et voluptatum ad excepturi. Nihil cumque omnis incidunt at culpa voluptas. Nisi dolores quos dolorum qui delectus. Suscipit laboriosam voluptatum amet sed consequatur aperiam maxime.<br>\nEt et ut non molestias. Adipisci beatae ut minima autem deserunt quam. Unde odio et dolorem harum voluptatem recusandae velit eveniet. Et ipsum officia velit velit qui illo.</p>', 'Cumque et voluptatum ad excepturi. Nihil cumque omnis incidunt at culpa voluptas. Nisi dolores quos dolorum qui delectus. Suscipit laboriosam voluptatum amet sed consequatur aperiam maxime.\nEt et ut non molestias. Adipisci beatae ut minima autem deserunt quam. Unde odio et dolorem harum voluptatem recusandae velit eveniet. Et ipsum officia velit velit qui illo.', 0, 1, 12, 28, 0, 0, 0, '2009-07-28 23:33:53', '2017-04-26 22:01:47'),
	(48, '<p>Sit dolor soluta quo a quo id et. Vitae in iste aut asperiores blanditiis. Sint aut quia eum eaque nihil.<br>\nVel mollitia illo a sed. At ipsam atque et quaerat est.<br>\nEos tempore error aliquid pariatur. Dicta asperiores similique expedita vel perferendis nihil. Sed incidunt aut explicabo ab dignissimos inventore saepe. Qui ea adipisci architecto eaque vero voluptate. Quasi est iure quas nostrum eius voluptas enim et.</p>', 'Sit dolor soluta quo a quo id et. Vitae in iste aut asperiores blanditiis. Sint aut quia eum eaque nihil.\nVel mollitia illo a sed. At ipsam atque et quaerat est.\nEos tempore error aliquid pariatur. Dicta asperiores similique expedita vel perferendis nihil. Sed incidunt aut explicabo ab dignissimos inventore saepe. Qui ea adipisci architecto eaque vero voluptate. Quasi est iure quas nostrum eius voluptas enim et.', 0, 2, 12, 29, 0, 0, 0, '2015-12-07 16:14:24', '2017-04-26 22:01:48'),
	(49, '<p>Aut aut voluptas consectetur ut aut. Maxime officia non optio ea at. Ut error quibusdam porro consectetur doloribus reiciendis.<br>\nAut quo veritatis doloremque harum quibusdam fugiat. Minima iure et tempora cum. Voluptatem optio minus in repudiandae. Et et aliquam aut facere. Veniam necessitatibus sit earum enim velit.<br>\nIusto delectus et doloribus et quidem iusto magnam. Quia unde sunt consequatur enim eligendi.</p>', 'Aut aut voluptas consectetur ut aut. Maxime officia non optio ea at. Ut error quibusdam porro consectetur doloribus reiciendis.\nAut quo veritatis doloremque harum quibusdam fugiat. Minima iure et tempora cum. Voluptatem optio minus in repudiandae. Et et aliquam aut facere. Veniam necessitatibus sit earum enim velit.\nIusto delectus et doloribus et quidem iusto magnam. Quia unde sunt consequatur enim eligendi.', 0, 2, 12, 29, 0, 0, 0, '2014-08-14 22:50:06', '2017-04-26 22:01:49'),
	(50, '<p>Aut quis sed facere corrupti itaque eum esse. Praesentium odit deserunt omnis culpa. Molestiae odio quam quisquam. Corporis dolore officiis commodi.<br>\nAut quia beatae et vero odit expedita qui. Est aut quaerat voluptatem aut culpa aut quam. Quibusdam dolores rerum excepturi officiis ducimus non.</p>', 'Aut quis sed facere corrupti itaque eum esse. Praesentium odit deserunt omnis culpa. Molestiae odio quam quisquam. Corporis dolore officiis commodi.\nAut quia beatae et vero odit expedita qui. Est aut quaerat voluptatem aut culpa aut quam. Quibusdam dolores rerum excepturi officiis ducimus non.', 0, 4, 12, 29, 0, 0, 0, '2012-09-05 11:14:00', '2017-04-26 22:01:49');
/*!40000 ALTER TABLE `entry_replies` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.failed_jobs: ~0 rows (około)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.folders
CREATE TABLE IF NOT EXISTS `folders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `public` tinyint(1) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `folders_user_id_foreign` (`user_id`),
  CONSTRAINT `folders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.folders: ~0 rows (około)
/*!40000 ALTER TABLE `folders` DISABLE KEYS */;
/*!40000 ALTER TABLE `folders` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.folder_groups
CREATE TABLE IF NOT EXISTS `folder_groups` (
  `folder_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `folder_groups_folder_id_foreign` (`folder_id`),
  KEY `folder_groups_group_id_foreign` (`group_id`),
  CONSTRAINT `folder_groups_folder_id_foreign` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `folder_groups_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.folder_groups: ~0 rows (około)
/*!40000 ALTER TABLE `folder_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `folder_groups` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `avatar` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `style` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `urlname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('public','private') COLLATE utf8mb4_unicode_ci NOT NULL,
  `popular_threshold` int(11) NOT NULL,
  `sidebar` text COLLATE utf8mb4_unicode_ci,
  `sidebar_source` text COLLATE utf8mb4_unicode_ci,
  `creator_id` int(10) unsigned NOT NULL,
  `subscribers_count` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`),
  KEY `groups_creator_id_foreign` (`creator_id`),
  CONSTRAINT `groups_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.groups: ~1 rows (około)
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT IGNORE INTO `groups` (`id`, `avatar`, `description`, `name`, `style`, `urlname`, `type`, `popular_threshold`, `sidebar`, `sidebar_source`, `creator_id`, `subscribers_count`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, NULL, 'Enim iusto corrupti sequi. Eum harum dolore nam et pariatur et laborum sunt.', 'South Braulio', NULL, 'runolfsdottir', 'public', 0, '<p>Nemo placeat at sint deserunt recusandae a quo. Saepe hic quae inventore ipsum nisi molestias nesciunt. Vitae nihil maiores delectus. Et non quia dignissimos tempore sapiente.</p>', 'Nemo placeat at sint deserunt recusandae a quo. Saepe hic quae inventore ipsum nisi molestias nesciunt. Vitae nihil maiores delectus. Et non quia dignissimos tempore sapiente.', 1, 0, '2008-07-27 04:39:42', '2017-04-26 22:01:09', NULL),
	(2, NULL, 'Quam ducimus occaecati dolorem sit unde unde. Minima et possimus architecto eaque est et.', 'Lake Eldredfort', NULL, 'barrows', 'public', 0, '<p>Quas itaque adipisci reiciendis suscipit aspernatur. Similique eos aut illo tempore repudiandae. Nihil blanditiis at inventore perferendis maiores. Tenetur aut incidunt aut. Natus autem repellat a dolor.</p>', 'Quas itaque adipisci reiciendis suscipit aspernatur. Similique eos aut illo tempore repudiandae. Nihil blanditiis at inventore perferendis maiores. Tenetur aut incidunt aut. Natus autem repellat a dolor.', 2, 0, '2010-11-19 09:19:58', '2017-04-26 22:01:11', NULL),
	(3, NULL, 'Rerum adipisci unde consequuntur et sed. At quidem eveniet consequatur non fuga.', 'Anneport', NULL, 'mcglynn', 'public', 0, '<p>Amet ipsum modi distinctio porro vel totam voluptatem. Sequi molestias soluta odit aperiam consequatur non dolores tenetur. Hic quam distinctio velit exercitationem ut ab voluptatem quidem. Voluptate enim officia quia sunt qui totam voluptas.</p>', 'Amet ipsum modi distinctio porro vel totam voluptatem. Sequi molestias soluta odit aperiam consequatur non dolores tenetur. Hic quam distinctio velit exercitationem ut ab voluptatem quidem. Voluptate enim officia quia sunt qui totam voluptas.', 3, 0, '2011-12-17 07:44:51', '2017-04-26 22:01:11', NULL),
	(4, NULL, 'Impedit vitae autem qui. Et itaque et ex quia accusantium eum.', 'Port Deion', NULL, 'mante', 'public', 0, '<p>Delectus nostrum laborum eligendi vero. Maiores modi eos eum blanditiis neque debitis laudantium.</p>', 'Delectus nostrum laborum eligendi vero. Maiores modi eos eum blanditiis neque debitis laudantium.', 4, 0, '2013-10-05 08:07:12', '2017-04-26 22:01:18', NULL),
	(5, NULL, 'Commodi eligendi ducimus aut sed. Minima ad doloribus nobis quaerat.', 'Toyburgh', NULL, 'konopelski', 'public', 0, '<p>Neque et nulla eum vero. Repudiandae consequatur temporibus alias consectetur voluptate exercitationem. Voluptatem neque tempora laborum facilis. At quis rerum corporis sed architecto animi in.</p>', 'Neque et nulla eum vero. Repudiandae consequatur temporibus alias consectetur voluptate exercitationem. Voluptatem neque tempora laborum facilis. At quis rerum corporis sed architecto animi in.', 4, 0, '2016-11-17 08:42:16', '2017-04-26 22:01:19', NULL),
	(6, NULL, 'Omnis numquam qui sed aut nihil. Explicabo quas tempora sint qui itaque voluptatem.', 'Lake Isabell', NULL, 'bogisich', 'public', 0, '<p>Modi sit vel odio. Dolores quam sed sunt et quia. Enim quisquam nihil rerum. Neque itaque eius et quo accusantium non voluptatem aliquam.</p>', 'Modi sit vel odio. Dolores quam sed sunt et quia. Enim quisquam nihil rerum. Neque itaque eius et quo accusantium non voluptatem aliquam.', 5, 0, '2007-12-10 18:23:39', '2017-04-26 22:01:21', NULL),
	(7, NULL, 'Incidunt aliquid fugit aliquid in minima. Recusandae eum adipisci facere et quis.', 'Priscillatown', NULL, 'moore', 'public', 0, '<p>Et voluptas consequatur et impedit. Aut modi fugit rerum nemo. Ut omnis adipisci quaerat expedita excepturi nisi.</p>', 'Et voluptas consequatur et impedit. Aut modi fugit rerum nemo. Ut omnis adipisci quaerat expedita excepturi nisi.', 6, 0, '2008-10-26 19:32:26', '2017-04-26 22:01:26', NULL),
	(8, NULL, 'Suscipit aliquam ducimus soluta rerum omnis et quia. In vitae sequi repudiandae et expedita similique eaque.', 'Sierrafurt', NULL, 'pollich', 'public', 0, '<p>Vel adipisci accusantium officia aut magni itaque. Sunt aliquam explicabo molestiae numquam. Distinctio id atque et animi neque omnis optio. Sequi totam quisquam sequi consequatur. Qui at facilis illo est voluptate aut quibusdam.</p>', 'Vel adipisci accusantium officia aut magni itaque. Sunt aliquam explicabo molestiae numquam. Distinctio id atque et animi neque omnis optio. Sequi totam quisquam sequi consequatur. Qui at facilis illo est voluptate aut quibusdam.', 7, 0, '2011-11-04 05:20:58', '2017-04-26 22:01:32', NULL),
	(9, NULL, 'Architecto magnam voluptatem occaecati doloribus quae minus. Cum quas ea iusto sed.', 'North Clarkberg', NULL, 'nolan', 'public', 0, '<p>Soluta officiis quas minus delectus tempore tenetur. Dolores esse laboriosam dolores corporis. Odit odit repellendus nostrum qui rem. Accusamus dolor ut enim. Aspernatur at ea nesciunt ea quaerat qui.</p>', 'Soluta officiis quas minus delectus tempore tenetur. Dolores esse laboriosam dolores corporis. Odit odit repellendus nostrum qui rem. Accusamus dolor ut enim. Aspernatur at ea nesciunt ea quaerat qui.', 8, 0, '2012-06-06 01:23:48', '2017-04-26 22:01:38', NULL),
	(10, NULL, 'Qui repellendus ut sequi tempora ut voluptatum minima. Quaerat id qui quas a.', 'Ullrichside', NULL, 'mcdermott', 'public', 0, '<p>Rem sit doloremque totam quia aliquam. Possimus numquam iure a nihil. Tempore est minus tempora maiores enim.</p>', 'Rem sit doloremque totam quia aliquam. Possimus numquam iure a nihil. Tempore est minus tempora maiores enim.', 8, 0, '2008-01-10 08:26:21', '2017-04-26 22:01:42', NULL),
	(11, NULL, 'Dolorem earum ea deserunt sapiente doloribus accusamus. Accusantium voluptate soluta qui qui.', 'Klockofort', NULL, 'blanda', 'public', 0, '<p>Sit hic quos libero expedita amet quia. Et officiis eligendi porro esse non dicta debitis. Quia voluptates rerum ab. Adipisci et alias aliquam omnis asperiores labore.</p>', 'Sit hic quos libero expedita amet quia. Et officiis eligendi porro esse non dicta debitis. Quia voluptates rerum ab. Adipisci et alias aliquam omnis asperiores labore.', 9, 0, '2015-02-21 01:32:48', '2017-04-26 22:01:46', NULL),
	(12, NULL, 'Qui earum ut at magni occaecati. Et aut omnis quos.', 'Port Brandifort', NULL, 'stehr', 'public', 0, '<p>In ea distinctio voluptates omnis est ipsa. Eligendi qui amet dolore eveniet rerum ipsa est. Alias magnam magni numquam porro qui.</p>', 'In ea distinctio voluptates omnis est ipsa. Eligendi qui amet dolore eveniet rerum ipsa est. Alias magnam magni numquam porro qui.', 10, 0, '2012-10-12 10:59:40', '2017-04-26 22:01:47', NULL);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.group_bans
CREATE TABLE IF NOT EXISTS `group_bans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `moderator_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `group_bans_group_id_foreign` (`group_id`),
  KEY `group_bans_moderator_id_foreign` (`moderator_id`),
  KEY `group_bans_user_id_foreign` (`user_id`),
  CONSTRAINT `group_bans_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `group_bans_moderator_id_foreign` FOREIGN KEY (`moderator_id`) REFERENCES `users` (`id`),
  CONSTRAINT `group_bans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.group_bans: ~0 rows (około)
/*!40000 ALTER TABLE `group_bans` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_bans` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.group_moderators
CREATE TABLE IF NOT EXISTS `group_moderators` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `moderator_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `accepted` tinyint(1) NOT NULL,
  `type` enum('moderator','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_moderators_group_id_foreign` (`group_id`),
  KEY `group_moderators_moderator_id_foreign` (`moderator_id`),
  KEY `group_moderators_user_id_foreign` (`user_id`),
  CONSTRAINT `group_moderators_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `group_moderators_moderator_id_foreign` FOREIGN KEY (`moderator_id`) REFERENCES `users` (`id`),
  CONSTRAINT `group_moderators_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.group_moderators: ~0 rows (około)
/*!40000 ALTER TABLE `group_moderators` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_moderators` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.migrations: ~42 rows (około)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT IGNORE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_04_24_110151_create_oauth_scopes_table', 1),
	(2, '2014_04_24_110304_create_oauth_grants_table', 1),
	(3, '2014_04_24_110403_create_oauth_grant_scopes_table', 1),
	(4, '2014_04_24_110459_create_oauth_clients_table', 1),
	(5, '2014_04_24_110557_create_oauth_client_endpoints_table', 1),
	(6, '2014_04_24_110705_create_oauth_client_scopes_table', 1),
	(7, '2014_04_24_110817_create_oauth_client_grants_table', 1),
	(8, '2014_04_24_111002_create_oauth_sessions_table', 1),
	(9, '2014_04_24_111109_create_oauth_session_scopes_table', 1),
	(10, '2014_04_24_111254_create_oauth_auth_codes_table', 1),
	(11, '2014_04_24_111403_create_oauth_auth_code_scopes_table', 1),
	(12, '2014_04_24_111518_create_oauth_access_tokens_table', 1),
	(13, '2014_04_24_111657_create_oauth_access_token_scopes_table', 1),
	(14, '2014_04_24_111810_create_oauth_refresh_tokens_table', 1),
	(15, '2015_03_15_000000_create_users_table', 1),
	(16, '2015_03_15_000001_create_password_resets_table', 1),
	(17, '2015_03_15_000002_create_groups_table', 1),
	(18, '2015_03_15_000003_create_contents_table', 1),
	(19, '2015_03_15_000004_create_comments_table', 1),
	(20, '2015_03_15_000005_create_entries_table', 1),
	(21, '2015_03_16_030116_create_votes_table', 1),
	(22, '2015_03_16_030139_create_notifications_table', 1),
	(23, '2015_03_18_190557_create_notification_targets_table', 1),
	(24, '2015_03_19_010952_create_user_actions_table', 1),
	(25, '2015_03_19_203351_create_groups_bans_table', 1),
	(26, '2015_03_19_203943_create_groups_moderators_table', 1),
	(27, '2015_03_20_012950_create_content_related_table', 1),
	(28, '2015_03_20_144839_create_user_blocked_groups_table', 1),
	(29, '2015_03_20_144856_create_user_subscribed_groups_table', 1),
	(30, '2015_03_20_151518_create_user_blocked_users_table', 1),
	(31, '2015_03_20_151531_create_user_followed_users_table', 1),
	(32, '2015_03_20_155400_create_folders_table', 1),
	(33, '2015_03_20_155500_create_folder_groups_table', 1),
	(34, '2015_03_20_163311_create_saves_table', 1),
	(35, '2015_03_23_172524_create_conversations_table', 1),
	(36, '2015_03_23_205149_create_conversation_messages_table', 1),
	(37, '2015_03_23_205200_create_conversation_users_table', 1),
	(38, '2015_03_23_234643_create_user_settings_table', 1),
	(39, '2015_03_24_000834_create_user_blocked_domains_table', 1),
	(40, '2015_03_25_022259_create_comment_replies_table', 1),
	(41, '2015_03_25_022314_create_entry_replies_table', 1),
	(42, '2015_05_21_091754_create_failed_jobs_table', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `element_id` int(10) unsigned NOT NULL,
  `element_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=213 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.notifications: ~9 rows (około)
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT IGNORE INTO `notifications` (`id`, `title`, `element_id`, `element_type`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 'Quae quasi quia non vero quia. Voluptatibus sequi aut quam e...', 1, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:09', '2017-04-26 22:01:09'),
	(2, 'Repudiandae nesciunt similique fugiat consequatur et. Quas t...', 1, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:09', '2017-04-26 22:01:09'),
	(3, 'Nulla ducimus veniam quo ducimus. Possimus a accusamus et la...', 2, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:09', '2017-04-26 22:01:09'),
	(4, 'Explicabo earum aperiam dolorum. Voluptatem placeat aut temp...', 2, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:09', '2017-04-26 22:01:09'),
	(5, 'Est ullam eum odio autem. Autem ab et alias. Corrupti et et...', 3, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:09', '2017-04-26 22:01:09'),
	(6, 'Dolorem consequatur id odit culpa. Saepe aliquam explicabo c...', 4, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:09', '2017-04-26 22:01:09'),
	(7, 'Eius quibusdam repellendus ut itaque. Odio quo unde enim id...', 1, 'Strimoid\\Models\\Entry', 1, '2017-04-26 22:01:09', '2017-04-26 22:01:09'),
	(8, 'Magnam porro quia repudiandae veritatis sapiente iusto autem...', 1, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:10', '2017-04-26 22:01:10'),
	(9, 'Ut et impedit quisquam voluptatem. Ea libero et molestias ut...', 2, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:11', '2017-04-26 22:01:11'),
	(10, 'Ut nesciunt vero vero et fugit. Aperiam deleniti est sunt of...', 3, 'Strimoid\\Models\\Comment', 2, '2017-04-26 22:01:11', '2017-04-26 22:01:11'),
	(11, 'Eum rem omnis consequatur. Saepe soluta minus commodi modi r...', 5, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:12', '2017-04-26 22:01:12'),
	(12, 'Maiores ipsum aut officia quia. Consequatur quia maxime dist...', 6, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:12', '2017-04-26 22:01:12'),
	(13, 'Dolores ut quasi repellendus animi. Hic quia hic aut. Harum...', 7, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:12', '2017-04-26 22:01:12'),
	(14, 'Dolores similique quod iusto est vel. Blanditiis nesciunt au...', 4, 'Strimoid\\Models\\Comment', 2, '2017-04-26 22:01:12', '2017-04-26 22:01:12'),
	(15, 'Consectetur excepturi quaerat ut sed mollitia. Illum vel sit...', 8, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:12', '2017-04-26 22:01:12'),
	(16, 'Praesentium sit nihil fugit eius voluptas. Nemo in perspicia...', 9, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:12', '2017-04-26 22:01:12'),
	(17, 'Illum non et quos dignissimos. Quae dolorum fuga aut quidem...', 10, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:12', '2017-04-26 22:01:12'),
	(18, 'Nam ea vitae officia. Expedita delectus consequatur debitis....', 2, 'Strimoid\\Models\\Entry', 3, '2017-04-26 22:01:12', '2017-04-26 22:01:12'),
	(19, 'Quis reiciendis architecto nulla sunt. Odit nihil veniam bea...', 3, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:12', '2017-04-26 22:01:12'),
	(20, 'Quis fuga laborum quam animi non architecto. Autem eaque vol...', 4, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:13', '2017-04-26 22:01:13'),
	(21, 'Corrupti non eius voluptatibus culpa odio. Nam voluptas nisi...', 5, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:13', '2017-04-26 22:01:13'),
	(22, 'Fugiat aut quo consequatur et saepe odio ratione. Eum veniam...', 5, 'Strimoid\\Models\\Comment', 2, '2017-04-26 22:01:13', '2017-04-26 22:01:13'),
	(23, 'Natus ut iure consectetur sit non sed. Dolorem rem quis enim...', 11, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:13', '2017-04-26 22:01:13'),
	(24, 'Aliquam sint suscipit placeat est similique rerum. Eveniet c...', 12, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:14', '2017-04-26 22:01:14'),
	(25, 'Qui odit consequatur repellat quia eum culpa. Quod quia nobi...', 6, 'Strimoid\\Models\\Comment', 2, '2017-04-26 22:01:14', '2017-04-26 22:01:14'),
	(26, 'Similique vitae laborum itaque fugiat assumenda eius. Tempor...', 13, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:14', '2017-04-26 22:01:14'),
	(27, 'Facere aliquid iusto temporibus maiores aut veniam dignissim...', 3, 'Strimoid\\Models\\Entry', 3, '2017-04-26 22:01:14', '2017-04-26 22:01:14'),
	(28, 'Ut quaerat quis et. Quisquam veritatis impedit alias. Quia v...', 6, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:14', '2017-04-26 22:01:14'),
	(29, 'In placeat optio quia ea. Deleniti et harum ad esse. Sunt si...', 7, 'Strimoid\\Models\\EntryReply', 3, '2017-04-26 22:01:14', '2017-04-26 22:01:14'),
	(30, 'Iure placeat vel ea nemo illum debitis aliquam. Quo ipsum se...', 8, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:15', '2017-04-26 22:01:15'),
	(31, 'Enim aut velit quaerat modi qui. Aliquid quia quia reiciendi...', 7, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:15', '2017-04-26 22:01:15'),
	(32, 'Provident nihil quisquam fugiat quia et voluptatibus autem....', 14, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:15', '2017-04-26 22:01:15'),
	(33, 'Natus sint velit quidem corrupti officia eos. Deserunt dolor...', 15, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:15', '2017-04-26 22:01:15'),
	(34, 'Et iusto aut tempore vel dicta quisquam dolor velit. Dolores...', 16, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:15', '2017-04-26 22:01:15'),
	(35, 'Aut et repudiandae est omnis voluptatem. Corporis consequatu...', 8, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(36, 'Facere dolor et voluptatem quos autem. Rem commodi non quasi...', 17, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(37, 'Ut aut fugiat est in. Quidem consequuntur totam neque cum. E...', 18, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(38, 'Dolorum placeat et reiciendis autem minus possimus. Dolorem...', 19, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(39, 'Nulla qui quia quisquam odit commodi labore. Perferendis ea...', 4, 'Strimoid\\Models\\Entry', 2, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(40, 'Ipsa et totam ut dolores labore est sed tempora. Placeat mol...', 9, 'Strimoid\\Models\\Comment', 2, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(41, 'Expedita harum nobis cumque unde facilis voluptatum commodi....', 20, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(42, 'Facilis aut cumque aliquid accusamus. Tempora quasi rerum it...', 10, 'Strimoid\\Models\\Comment', 2, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(43, 'Officia ut quo explicabo eius eos. Provident aliquid quaerat...', 21, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(44, 'Voluptatum officia explicabo corrupti dolorum. Praesentium m...', 22, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(45, 'Labore consectetur eum est doloremque iusto quisquam earum s...', 23, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(46, 'Aut adipisci ea ratione. Ea corrupti aperiam velit et quo di...', 5, 'Strimoid\\Models\\Entry', 2, '2017-04-26 22:01:16', '2017-04-26 22:01:16'),
	(47, 'Omnis aliquid odio aut quaerat. Deleniti quod at voluptas na...', 9, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:17', '2017-04-26 22:01:17'),
	(48, 'Quia dolorum rerum odit illo molestias quidem. Perspiciatis...', 6, 'Strimoid\\Models\\Entry', 2, '2017-04-26 22:01:17', '2017-04-26 22:01:17'),
	(49, 'Dolore error saepe laborum qui eaque cumque nostrum. Impedit...', 10, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:18', '2017-04-26 22:01:18'),
	(50, 'Consequatur voluptatem laudantium eos et temporibus eaque ut...', 11, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:18', '2017-04-26 22:01:18'),
	(51, 'Qui necessitatibus ad cupiditate totam quo itaque. Iste sint...', 7, 'Strimoid\\Models\\Entry', 3, '2017-04-26 22:01:19', '2017-04-26 22:01:19'),
	(52, 'Qui iste non laborum impedit sed. Perspiciatis maiores qui s...', 12, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:19', '2017-04-26 22:01:19'),
	(53, 'Reiciendis expedita totam et omnis ut qui ab. Omnis in aut e...', 11, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:19', '2017-04-26 22:01:19'),
	(54, 'Nesciunt accusantium et laborum labore. Fugit modi omnis vol...', 24, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:19', '2017-04-26 22:01:19'),
	(55, 'Consequuntur aspernatur perspiciatis culpa saepe dolorem. Nu...', 25, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:19', '2017-04-26 22:01:19'),
	(56, 'Sit reprehenderit autem eum officiis est. Et modi repudianda...', 12, 'Strimoid\\Models\\Comment', 4, '2017-04-26 22:01:20', '2017-04-26 22:01:20'),
	(57, 'Molestiae sequi facere ipsa accusamus maiores. Excepturi ven...', 8, 'Strimoid\\Models\\Entry', 1, '2017-04-26 22:01:20', '2017-04-26 22:01:20'),
	(58, 'Neque optio et corporis soluta enim officiis. Cum qui consec...', 13, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:20', '2017-04-26 22:01:20'),
	(59, 'Dolores aut qui ea necessitatibus a. Nemo et similique sit e...', 14, 'Strimoid\\Models\\EntryReply', 4, '2017-04-26 22:01:20', '2017-04-26 22:01:20'),
	(60, 'Tempora vitae inventore dolor nemo et. Quisquam culpa volupt...', 13, 'Strimoid\\Models\\Comment', 2, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(61, 'Sint illo dolor ea impedit nesciunt hic laudantium. Expedita...', 26, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(62, 'Saepe autem ea accusamus ad. Qui molestiae possimus quo duci...', 27, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(63, 'Distinctio minus praesentium veritatis quasi fugiat. Cupidit...', 28, 'Strimoid\\Models\\CommentReply', 5, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(64, 'Sit qui placeat est voluptate molestiae doloremque. Aliquid...', 14, 'Strimoid\\Models\\Comment', 4, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(65, 'Et distinctio quas quas ut earum cum. Vel tempora eos et ut...', 29, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(66, 'Quaerat quis optio doloribus inventore voluptate. Fugit face...', 30, 'Strimoid\\Models\\CommentReply', 4, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(67, 'Molestiae praesentium quos sunt recusandae et voluptatem mag...', 15, 'Strimoid\\Models\\Comment', 3, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(68, 'Et magnam consequatur rem. Tempore ipsum eos consectetur qui...', 31, 'Strimoid\\Models\\CommentReply', 5, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(69, 'Id tempora itaque repellendus doloribus voluptatem quae poss...', 32, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(70, 'Eaque odio esse dolores culpa. Et laborum et aspernatur nemo...', 9, 'Strimoid\\Models\\Entry', 4, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(71, 'Ducimus error fuga ut dolor id voluptatem. Aut ipsa rem qui...', 15, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:22', '2017-04-26 22:01:22'),
	(72, 'Vero quibusdam qui placeat eos consequatur aliquid. Nesciunt...', 16, 'Strimoid\\Models\\Comment', 4, '2017-04-26 22:01:22', '2017-04-26 22:01:22'),
	(73, 'Dignissimos reprehenderit et omnis maxime vero dicta modi. I...', 33, 'Strimoid\\Models\\CommentReply', 4, '2017-04-26 22:01:22', '2017-04-26 22:01:22'),
	(74, 'Labore et est odit. Quo alias voluptatem officia perspiciati...', 34, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:22', '2017-04-26 22:01:22'),
	(75, 'Explicabo et animi occaecati est magnam. Nam quidem minima q...', 35, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:22', '2017-04-26 22:01:22'),
	(76, 'Dignissimos est officiis veniam inventore ducimus placeat ut...', 10, 'Strimoid\\Models\\Entry', 1, '2017-04-26 22:01:22', '2017-04-26 22:01:22'),
	(77, 'Excepturi et aut et quo et laudantium dolor. Dolor sed volup...', 16, 'Strimoid\\Models\\EntryReply', 3, '2017-04-26 22:01:23', '2017-04-26 22:01:23'),
	(78, 'Sint occaecati occaecati ullam pariatur placeat. Id qui corp...', 11, 'Strimoid\\Models\\Entry', 2, '2017-04-26 22:01:23', '2017-04-26 22:01:23'),
	(79, 'Quos accusamus animi atque ut. Iste sint sunt rerum laborios...', 17, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:24', '2017-04-26 22:01:24'),
	(80, 'Laboriosam quasi dolores sed. Omnis recusandae veniam cumque...', 18, 'Strimoid\\Models\\EntryReply', 4, '2017-04-26 22:01:24', '2017-04-26 22:01:24'),
	(81, 'Aut id fugiat neque suscipit enim. Est blanditiis ducimus se...', 17, 'Strimoid\\Models\\Comment', 2, '2017-04-26 22:01:24', '2017-04-26 22:01:24'),
	(82, 'Reprehenderit assumenda soluta mollitia aperiam quas itaque...', 36, 'Strimoid\\Models\\CommentReply', 5, '2017-04-26 22:01:24', '2017-04-26 22:01:24'),
	(83, 'Dicta ut qui itaque aliquid suscipit. Earum perspiciatis sit...', 18, 'Strimoid\\Models\\Comment', 3, '2017-04-26 22:01:24', '2017-04-26 22:01:24'),
	(84, 'Aliquid dolor officiis non illum nobis enim voluptate. Non i...', 37, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:25', '2017-04-26 22:01:25'),
	(85, 'Molestias neque nesciunt culpa asperiores odio. Ut ab distin...', 12, 'Strimoid\\Models\\Entry', 2, '2017-04-26 22:01:25', '2017-04-26 22:01:25'),
	(86, 'Voluptas id quo facere. Harum placeat architecto esse porro....', 19, 'Strimoid\\Models\\EntryReply', 3, '2017-04-26 22:01:25', '2017-04-26 22:01:25'),
	(87, 'Autem beatae quam alias eos modi. Animi quibusdam et aut com...', 20, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:25', '2017-04-26 22:01:25'),
	(88, 'Sapiente nesciunt esse saepe quos quidem sit consequatur dol...', 21, 'Strimoid\\Models\\EntryReply', 4, '2017-04-26 22:01:26', '2017-04-26 22:01:26'),
	(89, 'Veniam sint dolores similique possimus aut. Magni praesentiu...', 19, 'Strimoid\\Models\\Comment', 5, '2017-04-26 22:01:26', '2017-04-26 22:01:26'),
	(90, 'Dolores quas et sed tempore laboriosam et sequi. Ipsum est m...', 20, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:26', '2017-04-26 22:01:26'),
	(91, 'Eveniet ab rem consequatur vitae qui. Aliquid eveniet eligen...', 13, 'Strimoid\\Models\\Entry', 4, '2017-04-26 22:01:26', '2017-04-26 22:01:26'),
	(92, 'Exercitationem et dolores et. Et labore et qui voluptate. Om...', 22, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:27', '2017-04-26 22:01:27'),
	(93, 'Libero nostrum non eum molestias molestias. Vero et unde adi...', 23, 'Strimoid\\Models\\EntryReply', 3, '2017-04-26 22:01:27', '2017-04-26 22:01:27'),
	(94, 'Eum quam quasi eaque non. Ratione in reiciendis autem autem...', 24, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:28', '2017-04-26 22:01:28'),
	(95, 'Excepturi dicta nihil deleniti ut assumenda voluptate. Et pe...', 21, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:28', '2017-04-26 22:01:28'),
	(96, 'Fuga qui veniam omnis corporis. Dolor minima perspiciatis ma...', 38, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:28', '2017-04-26 22:01:28'),
	(97, 'Velit sint sunt voluptatem delectus excepturi quam. Voluptas...', 39, 'Strimoid\\Models\\CommentReply', 6, '2017-04-26 22:01:28', '2017-04-26 22:01:28'),
	(98, 'Est laboriosam non at non atque occaecati. Dolorem odio reru...', 22, 'Strimoid\\Models\\Comment', 4, '2017-04-26 22:01:28', '2017-04-26 22:01:28'),
	(99, 'Maxime non fugit qui incidunt ut accusantium eaque. Ut volup...', 40, 'Strimoid\\Models\\CommentReply', 6, '2017-04-26 22:01:28', '2017-04-26 22:01:28'),
	(100, 'Ducimus necessitatibus vitae rerum rerum quaerat delectus pr...', 41, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:28', '2017-04-26 22:01:28'),
	(101, 'Et sed neque alias ad quia praesentium. Explicabo consequatu...', 42, 'Strimoid\\Models\\CommentReply', 5, '2017-04-26 22:01:28', '2017-04-26 22:01:28'),
	(102, 'Reprehenderit et delectus neque est omnis. Magni quia reicie...', 23, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:28', '2017-04-26 22:01:28'),
	(103, 'Nostrum atque at quia facere ut voluptatem. Ipsum recusandae...', 14, 'Strimoid\\Models\\Entry', 1, '2017-04-26 22:01:28', '2017-04-26 22:01:28'),
	(104, 'Voluptatibus voluptas neque ab explicabo dolor quia. Omnis e...', 25, 'Strimoid\\Models\\EntryReply', 6, '2017-04-26 22:01:29', '2017-04-26 22:01:29'),
	(105, 'Illum voluptatem necessitatibus quidem quos. Inventore rem r...', 26, 'Strimoid\\Models\\EntryReply', 6, '2017-04-26 22:01:29', '2017-04-26 22:01:29'),
	(106, 'Eum alias ipsam aut aperiam. Similique id veritatis est. Off...', 24, 'Strimoid\\Models\\Comment', 3, '2017-04-26 22:01:30', '2017-04-26 22:01:30'),
	(107, 'Et ipsum est velit aut. Voluptatem fuga asperiores laboriosa...', 43, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:30', '2017-04-26 22:01:30'),
	(108, 'Alias dolorem nesciunt qui vero. Qui nesciunt mollitia qui e...', 44, 'Strimoid\\Models\\CommentReply', 4, '2017-04-26 22:01:30', '2017-04-26 22:01:30'),
	(109, 'Quis voluptas ducimus sunt non. Et velit et consectetur volu...', 45, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:30', '2017-04-26 22:01:30'),
	(110, 'Et tenetur voluptatem hic pariatur eos ipsa sit. Officiis ha...', 15, 'Strimoid\\Models\\Entry', 2, '2017-04-26 22:01:30', '2017-04-26 22:01:30'),
	(111, 'Corporis voluptatem provident officia qui qui rerum. Soluta...', 27, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:30', '2017-04-26 22:01:30'),
	(112, 'Et qui perspiciatis eligendi ipsa nisi. Et numquam ut ipsa a...', 28, 'Strimoid\\Models\\EntryReply', 6, '2017-04-26 22:01:31', '2017-04-26 22:01:31'),
	(113, 'Est veniam praesentium commodi asperiores consectetur quia v...', 25, 'Strimoid\\Models\\Comment', 4, '2017-04-26 22:01:31', '2017-04-26 22:01:31'),
	(114, 'Tempora est eos ipsam. Recusandae hic aut aut perspiciatis....', 46, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:31', '2017-04-26 22:01:31'),
	(115, 'Quia eos dolorum eum ut officiis. Culpa cumque ipsa odio hic...', 26, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:31', '2017-04-26 22:01:31'),
	(116, 'Quisquam aut consequuntur at est voluptatem. In dolores magn...', 47, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:31', '2017-04-26 22:01:31'),
	(117, 'Accusantium cumque fuga ea consequuntur eaque nostrum aliqui...', 48, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:31', '2017-04-26 22:01:31'),
	(118, 'Alias omnis eaque esse nemo tempora. Voluptas voluptatem dol...', 49, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:31', '2017-04-26 22:01:31'),
	(119, 'Maxime quia recusandae voluptates dolores a. Omnis sit et es...', 16, 'Strimoid\\Models\\Entry', 2, '2017-04-26 22:01:31', '2017-04-26 22:01:31'),
	(120, 'Quo quia et earum tempora. Sed non officiis officiis et simi...', 29, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:32', '2017-04-26 22:01:32'),
	(121, 'Aut vero quia minus facere quo. Laboriosam dolores cupiditat...', 27, 'Strimoid\\Models\\Comment', 5, '2017-04-26 22:01:32', '2017-04-26 22:01:32'),
	(122, 'Et magnam veniam nobis debitis. Dolorum sapiente suscipit es...', 28, 'Strimoid\\Models\\Comment', 5, '2017-04-26 22:01:32', '2017-04-26 22:01:32'),
	(123, 'Optio voluptate ducimus id. Aliquid aut non iure ut sunt vel...', 50, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:33', '2017-04-26 22:01:33'),
	(124, 'Quod voluptas perferendis et ad soluta sint in. Explicabo iu...', 17, 'Strimoid\\Models\\Entry', 4, '2017-04-26 22:01:33', '2017-04-26 22:01:33'),
	(125, 'Odit qui necessitatibus quia laboriosam quis cum. Veritatis...', 30, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:33', '2017-04-26 22:01:33'),
	(126, 'Excepturi dolores at molestiae nesciunt quia voluptatem. Ass...', 31, 'Strimoid\\Models\\EntryReply', 7, '2017-04-26 22:01:33', '2017-04-26 22:01:33'),
	(127, 'Praesentium nemo occaecati fugit error minus dolorem. Iste i...', 29, 'Strimoid\\Models\\Comment', 6, '2017-04-26 22:01:34', '2017-04-26 22:01:34'),
	(128, 'Sint sit reprehenderit et veniam. Esse veritatis qui saepe c...', 51, 'Strimoid\\Models\\CommentReply', 4, '2017-04-26 22:01:34', '2017-04-26 22:01:34'),
	(129, 'Beatae repellendus eum quibusdam labore rerum. Corrupti et m...', 52, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:34', '2017-04-26 22:01:34'),
	(130, 'Dicta nemo animi voluptas laboriosam dolore harum voluptatem...', 53, 'Strimoid\\Models\\CommentReply', 6, '2017-04-26 22:01:34', '2017-04-26 22:01:34'),
	(131, 'Omnis provident aliquid architecto iste velit eum suscipit....', 30, 'Strimoid\\Models\\Comment', 6, '2017-04-26 22:01:34', '2017-04-26 22:01:34'),
	(132, 'Sed fugit neque est voluptas distinctio eum enim eaque. Quae...', 54, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:34', '2017-04-26 22:01:34'),
	(133, 'Incidunt ut in sunt suscipit sint. Quae nesciunt vero qui mo...', 55, 'Strimoid\\Models\\CommentReply', 7, '2017-04-26 22:01:34', '2017-04-26 22:01:34'),
	(134, 'Quis eveniet est molestiae praesentium. Deserunt numquam dol...', 56, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:34', '2017-04-26 22:01:34'),
	(135, 'Dolorem laboriosam enim voluptatem et quis dolorem quam est....', 18, 'Strimoid\\Models\\Entry', 1, '2017-04-26 22:01:34', '2017-04-26 22:01:34'),
	(136, 'Vitae fugit ipsa vero nihil impedit dolores odio dolorem. Ip...', 31, 'Strimoid\\Models\\Comment', 3, '2017-04-26 22:01:35', '2017-04-26 22:01:35'),
	(137, 'Quis veritatis eum omnis quis ut commodi. Nostrum vitae omni...', 57, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:35', '2017-04-26 22:01:35'),
	(138, 'Culpa praesentium optio error earum quis. Aliquid voluptas v...', 32, 'Strimoid\\Models\\Comment', 2, '2017-04-26 22:01:35', '2017-04-26 22:01:35'),
	(139, 'Eum itaque aut veniam. Modi deleniti aperiam esse eaque sed...', 33, 'Strimoid\\Models\\Comment', 4, '2017-04-26 22:01:35', '2017-04-26 22:01:35'),
	(140, 'Animi in non vel sed voluptas in. Illum dolores non enim. Ma...', 58, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:35', '2017-04-26 22:01:35'),
	(141, 'Veritatis soluta quo et asperiores. Quis officia quia et rer...', 19, 'Strimoid\\Models\\Entry', 2, '2017-04-26 22:01:35', '2017-04-26 22:01:35'),
	(142, 'Non reprehenderit qui enim quia. Et blanditiis iste quis omn...', 32, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:35', '2017-04-26 22:01:35'),
	(143, 'Itaque consequuntur tempore aspernatur quos facere. Ut est r...', 33, 'Strimoid\\Models\\EntryReply', 7, '2017-04-26 22:01:36', '2017-04-26 22:01:36'),
	(144, 'Expedita quos vitae libero ratione omnis fugit fugiat. Minim...', 34, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:36', '2017-04-26 22:01:36'),
	(145, 'Architecto velit dolor possimus enim dolor ut. Rerum vel nam...', 34, 'Strimoid\\Models\\Comment', 4, '2017-04-26 22:01:36', '2017-04-26 22:01:36'),
	(146, 'Delectus cupiditate soluta numquam est fugiat. Nulla quidem...', 59, 'Strimoid\\Models\\CommentReply', 7, '2017-04-26 22:01:36', '2017-04-26 22:01:36'),
	(147, 'Deleniti voluptatem qui aut perspiciatis laborum explicabo....', 60, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:37', '2017-04-26 22:01:37'),
	(148, 'Hic adipisci sunt omnis rem sed est officia. Id eaque totam...', 20, 'Strimoid\\Models\\Entry', 6, '2017-04-26 22:01:37', '2017-04-26 22:01:37'),
	(149, 'Non at qui nulla sint quisquam inventore ex error. Fugiat hi...', 35, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:37', '2017-04-26 22:01:37'),
	(150, 'Nemo maxime labore voluptates numquam sed ad explicabo. Ab n...', 36, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:37', '2017-04-26 22:01:37'),
	(151, 'Ut dolores consequatur eligendi optio. Aut rem et quia persp...', 37, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(152, 'Non maxime quia omnis qui. Est quo qui sed voluptas et. Susc...', 35, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(153, 'Natus ad explicabo voluptatibus eveniet aliquid laborum iust...', 61, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(154, 'Aut ut alias omnis fuga harum. Tempore minus repudiandae ut...', 62, 'Strimoid\\Models\\CommentReply', 3, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(155, 'Est maxime perspiciatis culpa beatae. Rem suscipit quas dolo...', 63, 'Strimoid\\Models\\CommentReply', 4, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(156, 'Esse laborum facere eveniet delectus iure. Possimus consequu...', 36, 'Strimoid\\Models\\Comment', 7, '2017-04-26 22:01:39', '2017-04-26 22:01:39'),
	(157, 'Et ipsam iusto temporibus et. Error quia in quia occaecati....', 64, 'Strimoid\\Models\\CommentReply', 8, '2017-04-26 22:01:39', '2017-04-26 22:01:39'),
	(158, 'Quo velit aut saepe odit dolorem necessitatibus. Et placeat...', 65, 'Strimoid\\Models\\CommentReply', 4, '2017-04-26 22:01:39', '2017-04-26 22:01:39'),
	(159, 'Voluptatem et iure voluptas vitae officia exercitationem. Pa...', 37, 'Strimoid\\Models\\Comment', 6, '2017-04-26 22:01:39', '2017-04-26 22:01:39'),
	(160, 'Qui amet molestiae sit voluptas. Exercitationem dolore labor...', 66, 'Strimoid\\Models\\CommentReply', 4, '2017-04-26 22:01:39', '2017-04-26 22:01:39'),
	(161, 'Ducimus corporis aperiam laborum quidem consequatur iure har...', 21, 'Strimoid\\Models\\Entry', 3, '2017-04-26 22:01:39', '2017-04-26 22:01:39'),
	(162, 'Nobis ut in optio molestiae. Voluptas non a quis sapiente an...', 38, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:39', '2017-04-26 22:01:39'),
	(163, 'Et corporis repellat ut et amet neque architecto. Et vitae l...', 39, 'Strimoid\\Models\\EntryReply', 6, '2017-04-26 22:01:40', '2017-04-26 22:01:40'),
	(164, 'Rerum exercitationem consequuntur voluptas aut eum occaecati...', 22, 'Strimoid\\Models\\Entry', 5, '2017-04-26 22:01:40', '2017-04-26 22:01:40'),
	(165, 'Aliquam quo sit rerum vel quibusdam laborum et. Blanditiis q...', 40, 'Strimoid\\Models\\EntryReply', 8, '2017-04-26 22:01:40', '2017-04-26 22:01:40'),
	(166, 'Ipsa nihil rerum et sint. Quam autem id alias velit quis non...', 38, 'Strimoid\\Models\\Comment', 5, '2017-04-26 22:01:41', '2017-04-26 22:01:41'),
	(167, 'Voluptate rerum nihil aliquam dolore tenetur laborum. Quisqu...', 67, 'Strimoid\\Models\\CommentReply', 5, '2017-04-26 22:01:41', '2017-04-26 22:01:41'),
	(168, 'Quisquam sint rem quis ipsum aut placeat. Et dolore porro to...', 68, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:41', '2017-04-26 22:01:41'),
	(169, 'Sunt quis qui sed ad voluptates et. Ut doloribus voluptatem...', 39, 'Strimoid\\Models\\Comment', 8, '2017-04-26 22:01:41', '2017-04-26 22:01:41'),
	(170, 'Itaque non libero minima ut sed eum. At earum non ipsam quas...', 69, 'Strimoid\\Models\\CommentReply', 6, '2017-04-26 22:01:41', '2017-04-26 22:01:41'),
	(171, 'Ex cum sint eligendi nihil culpa exercitationem. Commodi dol...', 70, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:41', '2017-04-26 22:01:41'),
	(172, 'Occaecati doloribus ipsam vero reprehenderit nesciunt. Enim...', 71, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:41', '2017-04-26 22:01:41'),
	(173, 'Vero optio ullam quis cum. Repudiandae ut consequatur doloru...', 23, 'Strimoid\\Models\\Entry', 7, '2017-04-26 22:01:41', '2017-04-26 22:01:41'),
	(174, 'Ut corrupti illo dolorum. Amet molestiae aspernatur est ut a...', 41, 'Strimoid\\Models\\EntryReply', 6, '2017-04-26 22:01:42', '2017-04-26 22:01:42'),
	(175, 'Quo assumenda consequatur voluptas ea sint. Id quas et sint...', 42, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:42', '2017-04-26 22:01:42'),
	(176, 'Quos cupiditate quos vel accusantium unde. In nam recusandae...', 24, 'Strimoid\\Models\\Entry', 5, '2017-04-26 22:01:42', '2017-04-26 22:01:42'),
	(177, 'Rem cum sunt dolor itaque rem iure quidem. Id reprehenderit...', 43, 'Strimoid\\Models\\EntryReply', 4, '2017-04-26 22:01:43', '2017-04-26 22:01:43'),
	(178, 'Nisi eius eius nihil a consectetur. Perferendis veniam cum m...', 44, 'Strimoid\\Models\\EntryReply', 8, '2017-04-26 22:01:43', '2017-04-26 22:01:43'),
	(179, 'Nam tempore laboriosam ab corrupti dignissimos quo quae quia...', 40, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:44', '2017-04-26 22:01:44'),
	(180, 'Voluptatibus aliquid ratione aut odio sunt excepturi cumque....', 72, 'Strimoid\\Models\\CommentReply', 8, '2017-04-26 22:01:44', '2017-04-26 22:01:44'),
	(181, 'Ut deserunt eius vero perspiciatis nihil aut. Ea maiores ven...', 41, 'Strimoid\\Models\\Comment', 6, '2017-04-26 22:01:44', '2017-04-26 22:01:44'),
	(182, 'Voluptas qui sapiente eaque. Asperiores dolorem id voluptas...', 73, 'Strimoid\\Models\\CommentReply', 6, '2017-04-26 22:01:44', '2017-04-26 22:01:44'),
	(183, 'Nemo quo praesentium iure accusamus. Vitae ipsa debitis even...', 74, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:44', '2017-04-26 22:01:44'),
	(184, 'Excepturi molestias eos a voluptatum. Vel sit voluptatum qui...', 25, 'Strimoid\\Models\\Entry', 3, '2017-04-26 22:01:44', '2017-04-26 22:01:44'),
	(185, 'Expedita occaecati molestiae maxime consequuntur. Eum non eo...', 45, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:44', '2017-04-26 22:01:44'),
	(186, 'Aut est ullam hic ut voluptatum. Expedita aspernatur odit qu...', 42, 'Strimoid\\Models\\Comment', 3, '2017-04-26 22:01:45', '2017-04-26 22:01:45'),
	(187, 'Dignissimos laboriosam deleniti explicabo similique velit pr...', 75, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:45', '2017-04-26 22:01:45'),
	(188, 'Consequatur fuga aliquid et hic. Qui non voluptatem doloribu...', 76, 'Strimoid\\Models\\CommentReply', 8, '2017-04-26 22:01:45', '2017-04-26 22:01:45'),
	(189, 'Dicta similique iste nostrum dolores in sit autem in. Quia n...', 77, 'Strimoid\\Models\\CommentReply', 2, '2017-04-26 22:01:45', '2017-04-26 22:01:45'),
	(190, 'Est fugiat illo facilis quidem nostrum corrupti. Cum a sunt...', 43, 'Strimoid\\Models\\Comment', 5, '2017-04-26 22:01:45', '2017-04-26 22:01:45'),
	(191, 'Nihil fugit consequatur harum repellendus accusamus in. Aspe...', 78, 'Strimoid\\Models\\CommentReply', 5, '2017-04-26 22:01:45', '2017-04-26 22:01:45'),
	(192, 'Provident voluptatem non quas laudantium nihil nisi hic. Eni...', 79, 'Strimoid\\Models\\CommentReply', 1, '2017-04-26 22:01:45', '2017-04-26 22:01:45'),
	(193, 'Alias quam ut numquam velit expedita. Quibusdam error a mole...', 26, 'Strimoid\\Models\\Entry', 7, '2017-04-26 22:01:45', '2017-04-26 22:01:45'),
	(194, 'Quod tempore est non molestias. Aut dolorem dicta est conseq...', 46, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:45', '2017-04-26 22:01:45'),
	(195, 'Natus nihil numquam voluptates quidem aliquid ex. Voluptatum...', 44, 'Strimoid\\Models\\Comment', 7, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(196, 'Odit consequuntur rerum voluptas accusantium velit labore ip...', 80, 'Strimoid\\Models\\CommentReply', 4, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(197, 'Quam nam autem aut atque voluptatem. Perferendis magnam minu...', 27, 'Strimoid\\Models\\Entry', 7, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(198, 'Suscipit ratione aut a asperiores. Et nemo inventore ut. Rei...', 45, 'Strimoid\\Models\\Comment', 8, '2017-04-26 22:01:47', '2017-04-26 22:01:47'),
	(199, 'Voluptas ratione laudantium aperiam enim tempore aut. Conseq...', 81, 'Strimoid\\Models\\CommentReply', 9, '2017-04-26 22:01:47', '2017-04-26 22:01:47'),
	(200, 'Repudiandae asperiores sed et vero aliquam. Quia qui eum mai...', 82, 'Strimoid\\Models\\CommentReply', 6, '2017-04-26 22:01:47', '2017-04-26 22:01:47'),
	(201, 'Odio odit atque a soluta. Quas nam quis molestias quo molest...', 46, 'Strimoid\\Models\\Comment', 3, '2017-04-26 22:01:47', '2017-04-26 22:01:47'),
	(202, 'Maiores similique quos ex culpa eligendi cum. Nam quaerat is...', 28, 'Strimoid\\Models\\Entry', 1, '2017-04-26 22:01:47', '2017-04-26 22:01:47'),
	(203, 'Cumque et voluptatum ad excepturi. Nihil cumque omnis incidu...', 47, 'Strimoid\\Models\\EntryReply', 1, '2017-04-26 22:01:47', '2017-04-26 22:01:47'),
	(204, 'Quia voluptate rerum fugit laudantium dolorem veniam. Quas e...', 47, 'Strimoid\\Models\\Comment', 5, '2017-04-26 22:01:48', '2017-04-26 22:01:48'),
	(205, 'Temporibus eos et ipsa qui fuga fugiat voluptatum. Labore ne...', 48, 'Strimoid\\Models\\Comment', 1, '2017-04-26 22:01:48', '2017-04-26 22:01:48'),
	(206, 'Perferendis est aut itaque ut natus dolor. Explicabo est quo...', 83, 'Strimoid\\Models\\CommentReply', 4, '2017-04-26 22:01:48', '2017-04-26 22:01:48'),
	(207, 'Et suscipit possimus eum dolore dolorum culpa. In impedit qu...', 84, 'Strimoid\\Models\\CommentReply', 5, '2017-04-26 22:01:48', '2017-04-26 22:01:48'),
	(208, 'Quas voluptates praesentium eum commodi minus deleniti eos....', 85, 'Strimoid\\Models\\CommentReply', 9, '2017-04-26 22:01:48', '2017-04-26 22:01:48'),
	(209, 'Qui voluptate et ab ab beatae. Qui nobis eveniet quibusdam a...', 29, 'Strimoid\\Models\\Entry', 6, '2017-04-26 22:01:48', '2017-04-26 22:01:48'),
	(210, 'Sit dolor soluta quo a quo id et. Vitae in iste aut asperior...', 48, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:48', '2017-04-26 22:01:48'),
	(211, 'Aut aut voluptas consectetur ut aut. Maxime officia non opti...', 49, 'Strimoid\\Models\\EntryReply', 2, '2017-04-26 22:01:49', '2017-04-26 22:01:49'),
	(212, 'Aut quis sed facere corrupti itaque eum esse. Praesentium od...', 50, 'Strimoid\\Models\\EntryReply', 4, '2017-04-26 22:01:49', '2017-04-26 22:01:49');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.notification_targets
CREATE TABLE IF NOT EXISTS `notification_targets` (
  `notification_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `read` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `notification_targets_notification_id_foreign` (`notification_id`),
  KEY `notification_targets_user_id_foreign` (`user_id`),
  CONSTRAINT `notification_targets_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notification_targets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.notification_targets: ~0 rows (około)
/*!40000 ALTER TABLE `notification_targets` DISABLE KEYS */;
/*!40000 ALTER TABLE `notification_targets` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_access_tokens
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_id` int(10) unsigned NOT NULL,
  `expire_time` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth_access_tokens_id_session_id_unique` (`id`,`session_id`),
  KEY `oauth_access_tokens_session_id_index` (`session_id`),
  CONSTRAINT `oauth_access_tokens_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `oauth_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_access_tokens: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_access_token_scopes
CREATE TABLE IF NOT EXISTS `oauth_access_token_scopes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `access_token_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_token_scopes_access_token_id_index` (`access_token_id`),
  KEY `oauth_access_token_scopes_scope_id_index` (`scope_id`),
  CONSTRAINT `oauth_access_token_scopes_access_token_id_foreign` FOREIGN KEY (`access_token_id`) REFERENCES `oauth_access_tokens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `oauth_access_token_scopes_scope_id_foreign` FOREIGN KEY (`scope_id`) REFERENCES `oauth_scopes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_access_token_scopes: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_access_token_scopes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_access_token_scopes` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_auth_codes
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_id` int(10) unsigned NOT NULL,
  `redirect_uri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire_time` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_session_id_index` (`session_id`),
  CONSTRAINT `oauth_auth_codes_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `oauth_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_auth_codes: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_auth_code_scopes
CREATE TABLE IF NOT EXISTS `oauth_auth_code_scopes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auth_code_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_code_scopes_auth_code_id_index` (`auth_code_id`),
  KEY `oauth_auth_code_scopes_scope_id_index` (`scope_id`),
  CONSTRAINT `oauth_auth_code_scopes_auth_code_id_foreign` FOREIGN KEY (`auth_code_id`) REFERENCES `oauth_auth_codes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `oauth_auth_code_scopes_scope_id_foreign` FOREIGN KEY (`scope_id`) REFERENCES `oauth_scopes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_auth_code_scopes: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_auth_code_scopes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_code_scopes` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_clients
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth_clients_id_secret_unique` (`id`,`secret`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_clients: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_client_endpoints
CREATE TABLE IF NOT EXISTS `oauth_client_endpoints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_uri` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth_client_endpoints_client_id_redirect_uri_unique` (`client_id`,`redirect_uri`),
  CONSTRAINT `oauth_client_endpoints_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_client_endpoints: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_client_endpoints` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_client_endpoints` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_client_grants
CREATE TABLE IF NOT EXISTS `oauth_client_grants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grant_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_client_grants_client_id_index` (`client_id`),
  KEY `oauth_client_grants_grant_id_index` (`grant_id`),
  CONSTRAINT `oauth_client_grants_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `oauth_client_grants_grant_id_foreign` FOREIGN KEY (`grant_id`) REFERENCES `oauth_grants` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_client_grants: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_client_grants` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_client_grants` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_client_scopes
CREATE TABLE IF NOT EXISTS `oauth_client_scopes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_client_scopes_client_id_index` (`client_id`),
  KEY `oauth_client_scopes_scope_id_index` (`scope_id`),
  CONSTRAINT `oauth_client_scopes_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `oauth_client_scopes_scope_id_foreign` FOREIGN KEY (`scope_id`) REFERENCES `oauth_scopes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_client_scopes: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_client_scopes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_client_scopes` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_grants
CREATE TABLE IF NOT EXISTS `oauth_grants` (
  `id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_grants: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_grants` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_grants` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_grant_scopes
CREATE TABLE IF NOT EXISTS `oauth_grant_scopes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grant_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_grant_scopes_grant_id_index` (`grant_id`),
  KEY `oauth_grant_scopes_scope_id_index` (`scope_id`),
  CONSTRAINT `oauth_grant_scopes_grant_id_foreign` FOREIGN KEY (`grant_id`) REFERENCES `oauth_grants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `oauth_grant_scopes_scope_id_foreign` FOREIGN KEY (`scope_id`) REFERENCES `oauth_scopes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_grant_scopes: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_grant_scopes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_grant_scopes` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_refresh_tokens
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire_time` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`access_token_id`),
  UNIQUE KEY `oauth_refresh_tokens_id_unique` (`id`),
  CONSTRAINT `oauth_refresh_tokens_access_token_id_foreign` FOREIGN KEY (`access_token_id`) REFERENCES `oauth_access_tokens` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_refresh_tokens: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_scopes
CREATE TABLE IF NOT EXISTS `oauth_scopes` (
  `id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_scopes: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_scopes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_scopes` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_sessions
CREATE TABLE IF NOT EXISTS `oauth_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_type` enum('client','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `owner_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_redirect_uri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_sessions_client_id_owner_type_owner_id_index` (`client_id`,`owner_type`,`owner_id`(191)),
  CONSTRAINT `oauth_sessions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_sessions: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_sessions` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.oauth_session_scopes
CREATE TABLE IF NOT EXISTS `oauth_session_scopes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` int(10) unsigned NOT NULL,
  `scope_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_session_scopes_session_id_index` (`session_id`),
  KEY `oauth_session_scopes_scope_id_index` (`scope_id`),
  CONSTRAINT `oauth_session_scopes_scope_id_foreign` FOREIGN KEY (`scope_id`) REFERENCES `oauth_scopes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `oauth_session_scopes_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `oauth_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.oauth_session_scopes: ~0 rows (około)
/*!40000 ALTER TABLE `oauth_session_scopes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_session_scopes` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`(191)),
  KEY `password_resets_token_index` (`token`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.password_resets: ~0 rows (około)
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.saves
CREATE TABLE IF NOT EXISTS `saves` (
  `element_id` int(10) unsigned NOT NULL,
  `element_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `saves_user_id_foreign` (`user_id`),
  CONSTRAINT `saves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.saves: ~0 rows (około)
/*!40000 ALTER TABLE `saves` DISABLE KEYS */;
/*!40000 ALTER TABLE `saves` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('user','banned','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_token` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_points` int(11) NOT NULL DEFAULT '0',
  `avatar` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` smallint(5) unsigned NOT NULL,
  `sex` enum('unknown','male','female') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_name_unique` (`name`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.users: ~1 rows (około)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `type`, `last_ip`, `is_activated`, `activation_token`, `total_points`, `avatar`, `age`, `sex`, `location`, `description`, `created_at`, `updated_at`, `deleted_at`, `last_login`) VALUES
	(1, 'lina.hahn', 'raleigh50@gmail.com', '$2y$10$G08uKMY6LccSDTTKvXX7VOObncSNPs7J6md6wlLeynkQ1kgEVNfNK', NULL, 'user', '', 1, '', 0, NULL, 0, 'unknown', NULL, NULL, '2013-10-27 08:12:27', '2017-04-26 22:01:09', NULL, '0000-00-00 00:00:00'),
	(2, 'maurine13', 'bnienow@swift.com', '$2y$10$FXQZfE2f9hye5D956.IRTu8pgCLsuqiPPW4lazHNk.cHi0pMDKWlS', NULL, 'user', '', 1, '', 0, NULL, 0, 'unknown', NULL, NULL, '2014-06-25 08:27:04', '2017-04-26 22:01:11', NULL, '0000-00-00 00:00:00'),
	(3, 'xbeier', 'mina33@hotmail.com', '$2y$10$/3kIMJJ6RMd1jS0HQykNaeM8ZMZ1EgzZrihzygDsqnZIO.TuNElLe', NULL, 'user', '', 1, '', 0, NULL, 0, 'unknown', NULL, NULL, '2010-10-09 23:01:15', '2017-04-26 22:01:11', NULL, '0000-00-00 00:00:00'),
	(4, 'lilla.goyette', 'shaniya48@gmail.com', '$2y$10$8N0iLGntvA5sGn2xrSyxJu2rsn0EwF4oVpwmly1TT4Q55ca3ozufm', NULL, 'user', '', 1, '', 0, NULL, 0, 'unknown', NULL, NULL, '2013-07-27 06:36:26', '2017-04-26 22:01:18', NULL, '0000-00-00 00:00:00'),
	(5, 'jayden.mueller', 'leannon.bret@grant.net', '$2y$10$N0uKutp50vIhcKUtRUdRS.A.G43wL8DPWRQFNZiJE6ytYGsiiw40S', NULL, 'user', '', 1, '', 0, NULL, 0, 'unknown', NULL, NULL, '2016-04-21 11:01:39', '2017-04-26 22:01:21', NULL, '0000-00-00 00:00:00'),
	(6, 'jaunita.jerde', 'kraig.reichert@hartmann.com', '$2y$10$LrmeUTdZSr74sIcofnAPTuqkt2950fbPqROHHUGiK6P5QB4MKFRiC', NULL, 'user', '', 1, '', 0, NULL, 0, 'unknown', NULL, NULL, '2015-12-03 08:01:27', '2017-04-26 22:01:26', NULL, '0000-00-00 00:00:00'),
	(7, 'julian85', 'aroberts@spinka.com', '$2y$10$8pylS0BAqq2Hv03vIZOSnOzdtMCzVszbcA4Dql.f6JvODFqQ/pitq', NULL, 'user', '', 1, '', 0, NULL, 0, 'unknown', NULL, NULL, '2016-11-25 19:57:56', '2017-04-26 22:01:32', NULL, '0000-00-00 00:00:00'),
	(8, 'abernathy.zoie', 'quitzon.lexie@yundt.info', '$2y$10$ed6n1j2XAhY7A5l1Xbirvus01O.gBkgd2TLqKTOb8LpFN0zApc5ji', NULL, 'user', '', 1, '', 0, NULL, 0, 'unknown', NULL, NULL, '2009-12-01 06:07:36', '2017-04-26 22:01:38', NULL, '0000-00-00 00:00:00'),
	(9, 'tatyana.simonis', 'keely.schoen@hotmail.com', '$2y$10$SIXZOtw.bWH4MZxBwwLeuuiW.JA1i3GmnWv0pxoqE32Onhus2.tZa', NULL, 'user', '', 1, '', 0, NULL, 0, 'unknown', NULL, NULL, '2009-09-16 22:02:31', '2017-04-26 22:01:46', NULL, '0000-00-00 00:00:00'),
	(10, 'handerson', 'louvenia.herman@gmail.com', '$2y$10$U57Ui1W3/wv1H5O2jZKCWezXZe8/N0sqXkVNY1.vjagozWNN68Lcu', NULL, 'user', '', 1, '', 0, NULL, 0, 'unknown', NULL, NULL, '2008-11-07 03:16:41', '2017-04-26 22:01:47', NULL, '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.user_actions
CREATE TABLE IF NOT EXISTS `user_actions` (
  `user_id` int(10) unsigned NOT NULL,
  `element_id` int(10) unsigned NOT NULL,
  `element_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `user_actions_user_id_foreign` (`user_id`),
  CONSTRAINT `user_actions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.user_actions: ~10 rows (około)
/*!40000 ALTER TABLE `user_actions` DISABLE KEYS */;
INSERT IGNORE INTO `user_actions` (`user_id`, `element_id`, `element_type`, `created_at`) VALUES
	(1, 1, 'Strimoid\\Models\\Content', '2017-04-26 22:01:09'),
	(1, 1, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:09'),
	(1, 1, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:09'),
	(1, 2, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:09'),
	(1, 2, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:09'),
	(1, 3, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:09'),
	(1, 4, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:09'),
	(1, 1, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:09'),
	(1, 1, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:10'),
	(1, 2, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:11'),
	(3, 2, 'Strimoid\\Models\\Content', '2017-04-26 22:01:11'),
	(2, 3, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:11'),
	(3, 5, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:11'),
	(2, 6, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:12'),
	(3, 7, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:12'),
	(2, 4, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:12'),
	(1, 8, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:12'),
	(2, 9, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:12'),
	(3, 10, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:12'),
	(3, 2, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:12'),
	(1, 3, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:12'),
	(1, 4, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:13'),
	(1, 5, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:13'),
	(1, 3, 'Strimoid\\Models\\Content', '2017-04-26 22:01:13'),
	(2, 5, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:13'),
	(1, 11, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:13'),
	(1, 12, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:14'),
	(2, 6, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:14'),
	(2, 13, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:14'),
	(3, 3, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:14'),
	(1, 6, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:14'),
	(3, 7, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:14'),
	(1, 8, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:15'),
	(3, 4, 'Strimoid\\Models\\Content', '2017-04-26 22:01:15'),
	(1, 7, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:15'),
	(2, 14, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:15'),
	(2, 15, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:15'),
	(2, 16, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:15'),
	(1, 8, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:16'),
	(2, 17, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:16'),
	(1, 18, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:16'),
	(1, 19, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:16'),
	(2, 4, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:16'),
	(1, 5, 'Strimoid\\Models\\Content', '2017-04-26 22:01:16'),
	(2, 9, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:16'),
	(3, 20, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:16'),
	(2, 10, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:16'),
	(3, 21, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:16'),
	(1, 22, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:16'),
	(3, 23, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:16'),
	(2, 5, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:16'),
	(1, 9, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:17'),
	(1, 6, 'Strimoid\\Models\\Content', '2017-04-26 22:01:17'),
	(2, 6, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:17'),
	(2, 10, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:18'),
	(2, 11, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:18'),
	(3, 7, 'Strimoid\\Models\\Content', '2017-04-26 22:01:19'),
	(3, 7, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:19'),
	(2, 12, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:19'),
	(4, 8, 'Strimoid\\Models\\Content', '2017-04-26 22:01:19'),
	(1, 11, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:19'),
	(2, 24, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:19'),
	(2, 25, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:19'),
	(4, 12, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:20'),
	(1, 8, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:20'),
	(2, 13, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:20'),
	(4, 14, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:20'),
	(2, 9, 'Strimoid\\Models\\Content', '2017-04-26 22:01:21'),
	(2, 13, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:21'),
	(3, 26, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:21'),
	(2, 27, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:21'),
	(5, 28, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:21'),
	(4, 14, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:21'),
	(1, 29, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:21'),
	(4, 30, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:21'),
	(3, 15, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:21'),
	(5, 31, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:21'),
	(2, 32, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:21'),
	(4, 9, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:21'),
	(1, 15, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:22'),
	(5, 10, 'Strimoid\\Models\\Content', '2017-04-26 22:01:22'),
	(4, 16, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:22'),
	(4, 33, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:22'),
	(3, 34, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:22'),
	(3, 35, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:22'),
	(1, 10, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:22'),
	(3, 16, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:23'),
	(4, 11, 'Strimoid\\Models\\Content', '2017-04-26 22:01:23'),
	(2, 11, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:23'),
	(1, 17, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:24'),
	(4, 18, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:24'),
	(2, 12, 'Strimoid\\Models\\Content', '2017-04-26 22:01:24'),
	(2, 17, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:24'),
	(5, 36, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:24'),
	(3, 18, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:24'),
	(2, 37, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:25'),
	(2, 12, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:25'),
	(3, 19, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:25'),
	(2, 20, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:25'),
	(4, 21, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:26'),
	(6, 13, 'Strimoid\\Models\\Content', '2017-04-26 22:01:26'),
	(5, 19, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:26'),
	(1, 20, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:26'),
	(4, 13, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:26'),
	(1, 22, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:27'),
	(3, 23, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:27'),
	(1, 24, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:28'),
	(6, 14, 'Strimoid\\Models\\Content', '2017-04-26 22:01:28'),
	(1, 21, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:28'),
	(3, 38, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:28'),
	(6, 39, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:28'),
	(4, 22, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:28'),
	(6, 40, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:28'),
	(1, 41, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:28'),
	(5, 42, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:28'),
	(1, 23, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:28'),
	(1, 14, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:28'),
	(6, 25, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:29'),
	(6, 26, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:29'),
	(4, 15, 'Strimoid\\Models\\Content', '2017-04-26 22:01:30'),
	(3, 24, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:30'),
	(1, 43, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:30'),
	(4, 44, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:30'),
	(2, 45, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:30'),
	(2, 15, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:30'),
	(2, 27, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:30'),
	(6, 28, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:31'),
	(1, 16, 'Strimoid\\Models\\Content', '2017-04-26 22:01:31'),
	(4, 25, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:31'),
	(3, 46, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:31'),
	(1, 26, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:31'),
	(3, 47, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:31'),
	(2, 48, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:31'),
	(1, 49, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:31'),
	(2, 16, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:31'),
	(2, 29, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:32'),
	(1, 17, 'Strimoid\\Models\\Content', '2017-04-26 22:01:32'),
	(5, 27, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:32'),
	(5, 28, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:32'),
	(1, 50, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:33'),
	(4, 17, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:33'),
	(1, 30, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:33'),
	(7, 31, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:33'),
	(1, 18, 'Strimoid\\Models\\Content', '2017-04-26 22:01:34'),
	(6, 29, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:34'),
	(4, 51, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:34'),
	(2, 52, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:34'),
	(6, 53, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:34'),
	(6, 30, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:34'),
	(1, 54, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:34'),
	(7, 55, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:34'),
	(3, 56, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:34'),
	(1, 18, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:34'),
	(6, 19, 'Strimoid\\Models\\Content', '2017-04-26 22:01:35'),
	(3, 31, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:35'),
	(3, 57, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:35'),
	(2, 32, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:35'),
	(4, 33, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:35'),
	(1, 58, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:35'),
	(2, 19, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:35'),
	(2, 32, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:35'),
	(7, 33, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:36'),
	(2, 34, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:36'),
	(6, 20, 'Strimoid\\Models\\Content', '2017-04-26 22:01:36'),
	(4, 34, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:36'),
	(7, 59, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:36'),
	(1, 60, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:37'),
	(6, 20, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:37'),
	(2, 35, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:37'),
	(1, 36, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:37'),
	(1, 37, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:38'),
	(5, 21, 'Strimoid\\Models\\Content', '2017-04-26 22:01:38'),
	(1, 35, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:38'),
	(3, 61, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:38'),
	(3, 62, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:38'),
	(4, 63, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:38'),
	(7, 36, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:39'),
	(8, 64, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:39'),
	(4, 65, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:39'),
	(6, 37, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:39'),
	(4, 66, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:39'),
	(3, 21, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:39'),
	(1, 38, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:39'),
	(6, 39, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:40'),
	(3, 22, 'Strimoid\\Models\\Content', '2017-04-26 22:01:40'),
	(5, 22, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:40'),
	(8, 40, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:40'),
	(8, 23, 'Strimoid\\Models\\Content', '2017-04-26 22:01:41'),
	(5, 38, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:41'),
	(5, 67, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:41'),
	(2, 68, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:41'),
	(8, 39, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:41'),
	(6, 69, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:41'),
	(2, 70, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:41'),
	(2, 71, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:41'),
	(7, 23, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:41'),
	(6, 41, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:42'),
	(2, 42, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:42'),
	(1, 24, 'Strimoid\\Models\\Content', '2017-04-26 22:01:42'),
	(5, 24, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:42'),
	(4, 43, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:43'),
	(8, 44, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:43'),
	(1, 25, 'Strimoid\\Models\\Content', '2017-04-26 22:01:44'),
	(1, 40, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:44'),
	(8, 72, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:44'),
	(6, 41, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:44'),
	(6, 73, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:44'),
	(2, 74, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:44'),
	(3, 25, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:44'),
	(2, 45, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:44'),
	(4, 26, 'Strimoid\\Models\\Content', '2017-04-26 22:01:45'),
	(3, 42, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:45'),
	(1, 75, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:45'),
	(8, 76, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:45'),
	(2, 77, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:45'),
	(5, 43, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:45'),
	(5, 78, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:45'),
	(1, 79, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:45'),
	(7, 26, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:45'),
	(1, 46, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:45'),
	(8, 27, 'Strimoid\\Models\\Content', '2017-04-26 22:01:46'),
	(7, 44, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:46'),
	(4, 80, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:46'),
	(7, 27, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:46'),
	(2, 28, 'Strimoid\\Models\\Content', '2017-04-26 22:01:47'),
	(8, 45, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:47'),
	(9, 81, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:47'),
	(6, 82, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:47'),
	(3, 46, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:47'),
	(1, 28, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:47'),
	(1, 47, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:47'),
	(3, 29, 'Strimoid\\Models\\Content', '2017-04-26 22:01:48'),
	(5, 47, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:48'),
	(1, 48, 'Strimoid\\Models\\Comment', '2017-04-26 22:01:48'),
	(4, 83, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:48'),
	(5, 84, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:48'),
	(9, 85, 'Strimoid\\Models\\CommentReply', '2017-04-26 22:01:48'),
	(6, 29, 'Strimoid\\Models\\Entry', '2017-04-26 22:01:48'),
	(2, 48, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:48'),
	(2, 49, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:49'),
	(4, 50, 'Strimoid\\Models\\EntryReply', '2017-04-26 22:01:49');
/*!40000 ALTER TABLE `user_actions` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.user_blocked_domains
CREATE TABLE IF NOT EXISTS `user_blocked_domains` (
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `user_blocked_domains_user_id_foreign` (`user_id`),
  CONSTRAINT `user_blocked_domains_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.user_blocked_domains: ~0 rows (około)
/*!40000 ALTER TABLE `user_blocked_domains` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_blocked_domains` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.user_blocked_groups
CREATE TABLE IF NOT EXISTS `user_blocked_groups` (
  `group_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `user_blocked_groups_group_id_foreign` (`group_id`),
  KEY `user_blocked_groups_user_id_foreign` (`user_id`),
  CONSTRAINT `user_blocked_groups_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_blocked_groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.user_blocked_groups: ~0 rows (około)
/*!40000 ALTER TABLE `user_blocked_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_blocked_groups` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.user_blocked_users
CREATE TABLE IF NOT EXISTS `user_blocked_users` (
  `source_id` int(10) unsigned NOT NULL,
  `target_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `user_blocked_users_source_id_foreign` (`source_id`),
  KEY `user_blocked_users_target_id_foreign` (`target_id`),
  CONSTRAINT `user_blocked_users_source_id_foreign` FOREIGN KEY (`source_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_blocked_users_target_id_foreign` FOREIGN KEY (`target_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.user_blocked_users: ~0 rows (około)
/*!40000 ALTER TABLE `user_blocked_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_blocked_users` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.user_followed_users
CREATE TABLE IF NOT EXISTS `user_followed_users` (
  `source_id` int(10) unsigned NOT NULL,
  `target_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `user_followed_users_source_id_foreign` (`source_id`),
  KEY `user_followed_users_target_id_foreign` (`target_id`),
  CONSTRAINT `user_followed_users_source_id_foreign` FOREIGN KEY (`source_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_followed_users_target_id_foreign` FOREIGN KEY (`target_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.user_followed_users: ~0 rows (około)
/*!40000 ALTER TABLE `user_followed_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_followed_users` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.user_settings
CREATE TABLE IF NOT EXISTS `user_settings` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `value` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `user_settings_user_id_foreign` (`user_id`),
  CONSTRAINT `user_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.user_settings: ~0 rows (około)
/*!40000 ALTER TABLE `user_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_settings` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.user_subscribed_groups
CREATE TABLE IF NOT EXISTS `user_subscribed_groups` (
  `group_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `user_subscribed_groups_group_id_foreign` (`group_id`),
  KEY `user_subscribed_groups_user_id_foreign` (`user_id`),
  CONSTRAINT `user_subscribed_groups_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_subscribed_groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.user_subscribed_groups: ~0 rows (około)
/*!40000 ALTER TABLE `user_subscribed_groups` DISABLE KEYS */;
INSERT IGNORE INTO `user_subscribed_groups` (`group_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(2, 1, '2017-04-26 22:01:11', '2017-04-26 22:01:11'),
	(4, 1, '2017-04-26 22:01:18', '2017-04-26 22:01:18'),
	(4, 2, '2017-04-26 22:01:19', '2017-04-26 22:01:19'),
	(5, 1, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(5, 2, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(5, 3, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(5, 4, '2017-04-26 22:01:21', '2017-04-26 22:01:21'),
	(6, 1, '2017-04-26 22:01:26', '2017-04-26 22:01:26'),
	(6, 2, '2017-04-26 22:01:26', '2017-04-26 22:01:26'),
	(6, 3, '2017-04-26 22:01:26', '2017-04-26 22:01:26'),
	(6, 4, '2017-04-26 22:01:26', '2017-04-26 22:01:26'),
	(6, 5, '2017-04-26 22:01:26', '2017-04-26 22:01:26'),
	(7, 1, '2017-04-26 22:01:32', '2017-04-26 22:01:32'),
	(7, 2, '2017-04-26 22:01:32', '2017-04-26 22:01:32'),
	(7, 3, '2017-04-26 22:01:32', '2017-04-26 22:01:32'),
	(8, 1, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(8, 2, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(8, 3, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(8, 4, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(8, 5, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(8, 6, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(8, 7, '2017-04-26 22:01:38', '2017-04-26 22:01:38'),
	(9, 1, '2017-04-26 22:01:42', '2017-04-26 22:01:42'),
	(9, 2, '2017-04-26 22:01:42', '2017-04-26 22:01:42'),
	(9, 3, '2017-04-26 22:01:42', '2017-04-26 22:01:42'),
	(10, 1, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(10, 2, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(10, 3, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(10, 4, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(10, 5, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(10, 6, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(11, 1, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(11, 2, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(11, 3, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(11, 4, '2017-04-26 22:01:46', '2017-04-26 22:01:46'),
	(12, 1, '2017-04-26 22:01:49', '2017-04-26 22:01:49'),
	(12, 2, '2017-04-26 22:01:49', '2017-04-26 22:01:49'),
	(12, 3, '2017-04-26 22:01:49', '2017-04-26 22:01:49'),
	(12, 4, '2017-04-26 22:01:49', '2017-04-26 22:01:49'),
	(12, 5, '2017-04-26 22:01:49', '2017-04-26 22:01:49'),
	(12, 6, '2017-04-26 22:01:49', '2017-04-26 22:01:49'),
	(12, 7, '2017-04-26 22:01:49', '2017-04-26 22:01:49'),
	(12, 8, '2017-04-26 22:01:49', '2017-04-26 22:01:49'),
	(12, 9, '2017-04-26 22:01:49', '2017-04-26 22:01:49');
/*!40000 ALTER TABLE `user_subscribed_groups` ENABLE KEYS */;

-- Zrzut struktury tabela strimoid.votes
CREATE TABLE IF NOT EXISTS `votes` (
  `up` tinyint(1) NOT NULL,
  `element_id` int(10) unsigned NOT NULL,
  `element_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `votes_user_id_foreign` (`user_id`),
  CONSTRAINT `votes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Zrzucanie danych dla tabeli strimoid.votes: ~0 rows (około)
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
