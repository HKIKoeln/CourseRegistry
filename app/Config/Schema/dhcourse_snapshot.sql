-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server Version:               5.5.38-0ubuntu0.12.04.1 - (Ubuntu)
-- Server Betriebssystem:        debian-linux-gnu
-- HeidiSQL Version:             8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Exportiere Struktur von Tabelle dhcourse-stage.cities
DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `FK_cities_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.cities: ~42 rows (ungefähr)
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` (`id`, `country_id`, `name`) VALUES
	(1, 1, 'Rotterdam'),
	(2, 2, 'Leuven'),
	(3, 1, 'Nijmegen'),
	(4, 1, 'Groningen'),
	(5, 1, 'Tilburg'),
	(6, 2, 'Gent'),
	(7, 1, 'Maastricht'),
	(8, 1, 'Utrecht'),
	(9, 1, 'Amsterdam'),
	(10, 1, 'Leiden'),
	(11, 2, 'Brussel'),
	(12, 2, 'Antwerpen'),
	(14, 3, 'Bamberg'),
	(15, 3, 'Bielefeld'),
	(16, 3, 'Darmstadt'),
	(17, 3, 'Erlangen'),
	(18, 3, 'Giessen'),
	(19, 3, 'Hamburg'),
	(20, 3, 'Cologne'),
	(21, 3, 'Lüneburg'),
	(22, 3, 'Saarbrücken'),
	(23, 3, 'Würzburg'),
	(24, 3, 'Potsdam'),
	(25, 3, 'Trier'),
	(27, 4, 'Paris'),
	(28, 4, 'Caen'),
	(29, 4, 'Grenoble'),
	(30, 4, 'Lille'),
	(31, 4, 'Tours'),
	(32, 5, 'Turku'),
	(33, 6, 'Pisa'),
	(34, 6, 'Rome'),
	(37, 6, 'Siena'),
	(38, 7, 'Dublin'),
	(39, 7, 'Maynooth'),
	(40, 7, 'Cork'),
	(42, 1, 'Bergen'),
	(43, 9, 'Graz'),
	(44, 10, 'Coimbra'),
	(45, 11, 'London'),
	(46, 11, 'Glasgow'),
	(48, 3, 'Passau');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.countries
DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.countries: ~11 rows (ungefähr)
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` (`id`, `name`) VALUES
	(1, 'Netherlands'),
	(2, 'Belgium'),
	(3, 'Germany'),
	(4, 'France'),
	(5, 'Finland'),
	(6, 'Italy'),
	(7, 'Ireland'),
	(8, 'Norway'),
	(9, 'Austria'),
	(10, 'Portugal'),
	(11, 'United Kingdom');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.courses
DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `university_id` int(11) DEFAULT NULL,
  `department` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_type_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `access_requirements` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enrollment_period` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'possibly to be dropped',
  `start_date` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recurring` tinyint(1) NOT NULL DEFAULT '1',
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `guide_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ects` float DEFAULT NULL,
  `contact_mail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'as opposed to the former ''contact name'' colums, the lecturer properties are supposed to contain only a single contact',
  `contact_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'enter a single email address only',
  `lon` decimal(10,6) DEFAULT NULL,
  `lat` decimal(10,6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `university_id` (`university_id`),
  KEY `active` (`active`),
  KEY `FK_courses_parent_types` (`parent_type_id`),
  KEY `FK_courses_languages` (`language_id`),
  KEY `country_id` (`country_id`),
  KEY `city_id` (`city_id`),
  KEY `type_id` (`type_id`),
  KEY `lon` (`lon`),
  KEY `lat` (`lat`),
  KEY `FK_courses_users` (`user_id`),
  CONSTRAINT `FK_courses_cities` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `FK_courses_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `FK_courses_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  CONSTRAINT `FK_courses_parent_types` FOREIGN KEY (`parent_type_id`) REFERENCES `parent_types` (`id`),
  CONSTRAINT `FK_courses_types` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`),
  CONSTRAINT `FK_courses_universities` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`),
  CONSTRAINT `FK_courses_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.courses: ~119 rows (ungefähr)
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` (`id`, `user_id`, `active`, `created`, `updated`, `name`, `country_id`, `city_id`, `university_id`, `department`, `parent_type_id`, `type_id`, `language_id`, `access_requirements`, `enrollment_period`, `start_date`, `recurring`, `url`, `guide_url`, `ects`, `contact_mail`, `contact_name`, `lon`, `lat`) VALUES
	(1, 1, 1, '2014-06-03 17:16:10', '2013-06-04 02:31:17', 'Digital research methods', 1, 1, 1, 'Media and communication', 2, 6, 2, 'none', '', '2014-09-01', 1, 'http://eshcc.sin-online.nl/studiegids/?action2=show_course&course=CM4104', '', 5, 'menchentrevino@eshcc.eur.nl', 'dr. E.Menchen-Trevino,; dr. T Timan', 4.526174, 51.917975),
	(2, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Digital culture', 1, 1, 1, 'Erasmus School of History, Culture and Communication (ESHCC)', 2, 5, 2, 'english proficiency', '', NULL, 1, 'http://minordigitalculture.wordpress.com/', NULL, 15, 'Scagliola@eshcc.eur.nl', 'dr.Stef Scagliola, Marian van der Beek, dr. Tjerk Timan', 4.526174, 51.917975),
	(3, NULL, 0, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Not yet available: has to be evaluated by the NVAO', 2, 2, 2, '', 1, NULL, NULL, '', '', NULL, 1, 'not available yet', NULL, 0, 'danny.deschreye@cs.kuleuven.be', 'Danny de Schreye, Frank van Eynde', 4.700306, 50.878150),
	(4, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'E-Humanities', 1, 3, 3, 'Humanities-Dutch language and culture', 2, 5, 1, 'taken the course Inleiding Informatie- en Communicatietechnologie', '2014', '2014-09-01', 1, 'http://www.ru.nl/nederlands/bachelor_nl/minoren/minor-humanities/', NULL, 15, 'm.rem@let.ru.nl', 'Dr. Margit Rem', 5.865339, 51.818912),
	(5, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'New media en digital culture', 1, 3, 3, 'Humanities- Communication and Information Studies, Cultural Studies, Philosophical Ethics', 2, 5, 1, 'Taken course \'\'Inleiding Informatie- en Communicatietechnologie\'\'', '2013-2014', NULL, 1, 'http://www.studiegids.science.ru.nl/2013/arts/prospectus/minorgids/contents/info/35375/', NULL, 15, 'm.becker@ftr.ru.nl', 'dr. M. Becker', 5.865339, 51.818912),
	(6, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Technology, Information en Communication (TIC) ', 1, 3, 3, 'Humanities-Linguistics', 2, 5, 1, 'It is possible that a minor includes courses for which entry requirements are set.', '2014', NULL, 1, 'http://www.studiegids.science.ru.nl/2013/arts/prospectus/minorgids/contents/info/35053/', NULL, 20, 'j.klatter@let.ru.nl', 'dr. J. Klatter', 5.865339, 51.818912),
	(7, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Information science', 1, 4, 4, 'Arts', 1, 2, 2, 'A Bachelor diploma in the field of: Communication- and Information Science, Humanities Computing/Information Science', '2013-2014', NULL, 1, 'http://www.rug.nl/masters/information-science/', NULL, 60, 'j.nerbonne@rug.nl', 'John Nerbonne', 6.561975, 53.219099),
	(8, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Digitaal erfgoed', 1, 5, 5, 'Humanities', 2, 6, 1, 'none', '2014-2015', '2014-09-01', 1, 'http://mystudy.uvt.nl/it10.vakzicht?taal=n&pfac=FLW&vakcode=826020', NULL, 6, 'eric.postma@gmail.com', 'prof. dr. E. Postma and dr. J.J. Paijmans', 5.040711, 51.563119),
	(9, NULL, 0, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Still under discussion (this will change)', 1, 5, 5, 'Culture Studies (within Tilburg School of Humanities)', 1, 1, 1, '', '2014', NULL, 1, 'http://www.tilburguniversity.edu/nl/over-tilburg-university/schools/geesteswetenschappen/dcu/', NULL, 0, 'a.s.dogruoz@gmail.com', 'Seza Dogruoz', 5.040711, 51.563119),
	(10, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Statistiek', 2, 6, 6, 'Political and Social Sciences / introduction for the Arts', 2, 6, 1, 'none', '2014', NULL, 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A000352.pdf', NULL, 5, 'John.Lievens@UGent.be', 'John Lievens', 3.727373, 51.047060),
	(11, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Geografie for the arts', 2, 6, 6, 'Science ', 2, 5, 1, 'none', '2014', NULL, 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/C001475.pdf', NULL, 4, 'nico.vandeweghe@ugent.be', 'Tijs Neutens, Van de Weghe, Nico', 3.727373, 51.047060),
	(12, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Databeheer en statistiek voor historici', 2, 6, 6, 'Arts', 2, 6, 1, 'none', '2013-2014', '2013-09-01', 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A002871.pdf', 'http://studiegids.ugent.be/2013/NL/studiefiches/A002871.pdf', 5, 'Christa.Matthys@UGent.be', 'Christa.Matthys', 3.727207, 51.047725),
	(13, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Historische methoden, Module Historische cartografie en GIS', 2, 6, 6, 'Arts', 2, 6, 1, 'none', '2013-2014', NULL, 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A002872.pdf', 'http://studiegids.ugent.be/2013/NL/studiefiches/A002872.pdf', 2.5, 'Gita.Deneckere@UGent.be', 'Gita Deneckere', 3.727207, 51.047725),
	(14, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Historische methoden, Module Prosopografie en sociale netwerkanalyse', 2, 6, 6, 'Arts', 2, 6, 1, 'none', '2013-2014', NULL, 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A002872.pdf', 'http://studiegids.ugent.be/2013/NL/studiefiches/A002872.pdf', 3, 'Gita.Deneckere@UGent.be', 'Gita Deneckere', 3.727207, 51.047725),
	(15, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Laguage technology', 2, 6, 6, 'Arts ', 2, 6, 1, 'Bachelor', '2013-2014', NULL, 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A701096.pdf', NULL, 4, 'philippe.demaeyer@ugent.be', 'Philippe de Maeyer', 3.727207, 51.047725),
	(16, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Terminologie en vertaaltechnologie', 2, 6, 6, 'Arts', 2, 6, 1, 'Bachelor', '2013-2014', NULL, 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A701005.pdf', NULL, 3, 'joost.buysschaert@ugent.be', 'Buysschaert, Joost; Carine de Groote', 3.727207, 51.047725),
	(17, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Taaltechnologie', 2, 6, 6, 'Arts', 2, 6, 1, 'Bachelor', '2013-2014', NULL, 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A701096.pdf', NULL, 3, 'thomas.crombez@uantwerpen.be', 'Hoste, Veronique, Els Lefever', 3.727207, 51.047725),
	(18, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Lokalisatie', 2, 6, 6, 'Arts', 2, 6, 1, 'Bachelor', '2013-2014', NULL, 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A701099.pdf', NULL, 3, 'lieve.macken@ugent.be', 'Lieve Macken', 3.727207, 51.047725),
	(19, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'hedendaags documentbeheer', 2, 6, 6, 'Arts ', 2, 6, 1, 'master', '2010-2011', NULL, 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A000309.pdf', 'http://studiegids.ugent.be/2013/NL/studiefiches/A000309.pdf', 5, 'frank.scheelings@vub.ac.be', 'Frank Scheelings', 3.727207, 51.047725),
	(20, 3, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Business informatics', 2, 6, 6, 'Arts', 2, 6, 1, 'master', '2009-2010', '2013-09-01', 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A002084.pdf', 'http://studiegids.ugent.be/2013/NL/studiefiches/A002084.pdf', 4, 'fien.danniau@ugent.be', 'E.van Dijck', 3.727207, 51.047725),
	(21, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Corpus Linguistics', 2, 6, 6, 'Arts', 2, 6, 2, 'master', '2004-2005', '2013-09-01', 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A002088.pdf', 'http://studiegids.ugent.be/2013/NL/studiefiches/A002088.pdf', 6, 'fien.danniau@ugent.be', 'Fien Danniau', 3.727207, 51.047725),
	(22, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Language Data and Linguistic Objects', 2, 6, 6, 'Arts', 2, 6, 2, 'master', '2012-2013', NULL, 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A000582.pdf', NULL, 6, 'Mieke.VanHerreweghe@UGent.be', 'Van Herreweghe, Mieke (responsible teacher); Van Peteghem, Marleen ', 3.727207, 51.047725),
	(23, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Communicatiewetenschappelijk onderzoek I ', 2, 6, 6, 'Social and political Sciences', 2, 6, 1, 'none', '2013-2014', NULL, 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/K000120.pdf', NULL, 6, 'gino.verleye@ugent.be', 'Verleye, Gino', 3.727207, 51.047725),
	(24, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Informatica en statistiek toegepast op de archeologie', 2, 6, 6, 'Arts', 2, 6, 1, 'none', '2011-2012', '2013-09-01', 1, 'http://studiegids.ugent.be/2013/NL/studiefiches/A002563.pdf', 'http://studiegids.ugent.be/2013/NL/studiefiches/A002563.pdf', 2.5, 'Devi.Taelman@UGent.be', 'Taelman, Devi (responsible teacher); Vandenabeele, Peter', 3.727207, 51.047725),
	(25, NULL, 1, '2014-06-03 17:16:10', '2014-06-04 02:31:17', 'Media culture', 1, 7, 7, 'Arts and Social Sciences, Technology & Society Studies', 1, 2, 2, 'a bachelor\'s degree in a relevant academic field (media or communications studies, film or television studies, history, social science, humanities orliberal arts) and a certified list of grades', '2014-2015', NULL, 1, 'http://www.maastrichtuniversity.nl/web/Faculties/FASoS/TargetGroups/ProspectiveStudents/MastersProgrammes/MediaCulture2.htm', NULL, 60, 'k.wenz@maastrichtuniversity.nl ', 'Karin Wenz', 5.686173, 50.847096),
	(26, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Introductie in de Digital Humanities', 1, 8, 8, 'Humanities', 2, 6, 2, 'Bachelor; in order to follow the courses, students must enroll in all four courses of the minor', '2013-2014', '2013-09-01', 1, 'https://www.osiris.universiteitutrecht.nl/osistu_ospr/OnderwijsCatalogusSelect.do?selectie=cursus&collegejaar=2013&cursus=TL2V13001', NULL, 30, 'E.Stronks@uu.nl', 'E.Stronks', 5.123130, 52.090698),
	(27, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Summer school state of the art, digital humanities', 1, 8, 8, 'Humanities', 2, 7, 2, 'Bachelor', '', '2013-07-08', 1, 'http://www.summerschoolsineurope.eu/course/1516/digital-humanities-state-of-the-art', NULL, 1.5, 'E.A.J.M.Kas@uu.nl', 'E.A.J.M.Kas', 5.123130, 52.090698),
	(28, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Innovative digital production tools', 1, 8, 8, 'Arts', 2, 7, 2, 'BA and MA students from all discipline backgrounds who are open to the use of technologies in creative production. No previous experience with the use of technologies is required. Students that subscribe for this course are asked to come up with a concret', '9/1/2014', NULL, 1, 'http://www.utrechtsummerschool.nl/courses/art-design/innovative-digital-productiontools-#', NULL, 1.5, 'Joris@protospace.nl', 'Joris van Tubergen', 5.123130, 52.090698),
	(29, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Artifical intelligence', 1, 8, 8, 'Information science', 1, 2, 2, 'Possess knowlegde of logic as used in artificial intelligence, propositional and predicate logic and modal logic (modal reasoning);mathematics as used in artificial intelligence, including set theory, statistics (Bayesian networks), analysis (convergence)', '2013-2014', NULL, 1, 'http://www.uu.nl/university/international-students/EN/artificialintelligence/Pages/default.aspx?refer=/university/international-students/EN/programmes/mastersprogrammes/artificialintelligence/Pages/default.aspx', NULL, 120, 'h.prakken@uu.nl', 'Prof. Dr. Henry Prakken', 5.123130, 52.090698),
	(30, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Game studies', 1, 8, 8, 'Arts', 2, 5, 1, 'For students within and beyond arts', '2013-2014', NULL, 1, 'http://www.uu.nl/university/minors/NL/GameStudies/Pages/default.aspx', NULL, 30, 'I.O.devries@uu.nl', 'I.O. de Vries', 5.123130, 52.090698),
	(31, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Kunstmatige intelligentie', 1, 8, 8, 'Information science', 1, 1, 1, '', '2014-2015', NULL, 1, 'http://www.uu.nl/faculty/humanities/NL/Onderwijs/bacheloropleidingen/kunstmatigeintelligentie/studieprogramma/Pages/default.aspx', NULL, 180, 'Janneke.vanLith@phil.uu.nl', 'dr. Janneke van Lith', 5.123130, 52.090698),
	(32, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Game and media technology', 1, 8, 8, 'Information science', 1, 2, 2, 'There is no entrance exam but admission is dependent on the content of the Bachelor’s programme taken by the student. There is no set limit on to the number of students accepted. The programme allows using up to 15 ECTS (2 courses) to remedy deficiencies ', '2013-2014', NULL, 1, 'http://www.uu.nl/university/international-students/EN/gamemedia/Pages/default.aspx?refer=/university/internationalstudents/en/gamemedia', NULL, 180, 'H.Philippi@uu.nl', 'Dhrs. Hans Philippi', 5.123130, 52.090698),
	(33, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Neuroscience and cognition', 1, 8, 8, 'Information science', 1, 2, 2, 'Applicants must hold a BSc in Biology, Physics, Psychology, Artificial Intelligence, Pharmacology, Linguistics, Biomedical Science, Medicine or Veterinary Medicine.', '2014-2015', NULL, 1, 'http://www.uu.nl/university/masters/nl/nc/Pages/default.aspx?refer=/university/masters/nl/nc/Pages/study.aspx', NULL, 120, 'p.n.e.degraan@umcutrecht.nl', 'Dr. Pierre de Graan, Prof. Albert Postma', 5.123130, 52.090698),
	(34, 21, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'New media and digital culture', 1, 8, 8, 'Humanities', 1, 2, 2, 'Bachelor', '2013-2014', '2014-09-01', 1, 'http://www.uu.nl/faculty/humanities/EN/education/mastersprogrammes/newmediadigitalculture/Pages/default.aspx', NULL, 60, 'A.S.Lehmann@uu.nl', 'A.S.Lehmann', 5.123130, 52.090698),
	(35, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Media and performace studies', 1, 8, 8, 'Humanities', 1, 2, 2, 'Bachelor', '2013-2014', NULL, 1, 'http://www.uu.nl/faculty/humanities/EN/education/mastersprogrammes/mediaandperformancestudies/studyprogramme/courses/Pages/default.aspx', NULL, 120, 'M.A.Bleeker@uu.nl', 'Prof. dr. M.A. (Maaike) Bleeker', 5.123130, 52.090698),
	(36, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Communicatie- en informatiewetenschappen', 1, 8, 8, 'Humanities', 1, 1, 1, 'none', '2013-2014', NULL, 1, 'http://www.uu.nl/faculty/humanities/nl/Onderwijs/bacheloropleidingen/communicatieeninformatiewetenschappen/Pages/default.aspx', NULL, 180, 'StudieadviesMCW.GW@uu.nl', 'Anneriek van Bommel / Stefan Vuurens', 5.123130, 52.090698),
	(37, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Theatre, Film and Television science', 1, 8, 8, 'Humanities', 1, 1, 1, 'none', '2013-2014', NULL, 1, 'http://www.uu.nl/faculty/humanities/NL/Onderwijs/bacheloropleidingen/theaterfilmtelevisiewetenschap/studieprogramma/Pages/default.aspx', NULL, 180, 'StudieadviesMCW.GW@uu.nl', 'Drs. Anneriek van Bommel', 5.123130, 52.090698),
	(38, 18, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Utrecht data school', 1, 8, 8, 'Humanities', 2, 6, 1, '', '2014', NULL, 1, 'http://www.dataschool.nl', NULL, 37.5, 'm.t.schaefer@uu.nl', 'Mirko Tobias Schaefer', 5.123130, 52.090698),
	(39, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'New media and digital cultures', 1, 9, 9, 'Humanities-media studies', 1, 2, 2, 'being admitted to the MA Media Studies: New Media and Digital Culture', '2014', NULL, 1, 'http://gsh.uva.nl/ma-programmes/programmes/content21/new-media-and-digital-culture.html', NULL, 60, 'Rogers@uva.nl;E.Rutten1@uva.nl', 'mw. dr. Y van Dijk, prof. dr. R.A. Rogers (coordinator)', 4.893700, 52.368000),
	(40, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Digital Humanities', 1, 9, 9, 'Humanities', 1, 1, 1, 'being admitted to the honours program humanities', '2014', NULL, 1, 'http://studiegids.uva.nl/web/uva/2011_2012/nl/c/12131.html', NULL, 12, 'L.W.M.Bod@uva.nl', 'prof.dr. L.W.M. Bod', 4.893700, 52.368000),
	(42, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Computational Literary studies', 1, 9, 9, 'Humanities', 2, 6, 2, 'being admitted to a research master or PhD', '02 2014', NULL, 1, 'http://www.oslit.nl/course-computational-literary-studies/', NULL, 4, 'osl-fgw@uva.nl', 'Katarina van Dalen Oskam', 4.893700, 52.368000),
	(43, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Digital Humanities', 1, 9, 9, 'Humanities', 1, 3, 2, 'The student has been admitted to a research master', '2014', NULL, 1, 'http://studiegids.uva.nl/web/uva/2011_2012/nl/c/12442.html', NULL, 10, 'L.W.M.Bod@uva.nl', 'prof.dr. L.W.M. Bod', 4.893700, 52.368000),
	(44, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Digital Humanities', 1, 9, 10, 'Humanities', 1, 1, 2, 'No entry requirements. The courses are open to all students. They are specially recommended for students of History, Literature, Arts, Cultural Studies and Cultural Anthropology', '2013-2014', NULL, 1, 'http://www.let.vu.nl/nl/studenten/studiegids/2012-2013/exchange-courses/index.asp?view=module&id=50599492', NULL, 6, 'i.b.leemans@vu.nl', 'prof. dr. Inger Leemans & dr. E. Jorink (Huygens Institute/ING)', 4.893700, 52.368000),
	(45, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'New Media Research Practices', 1, 9, 9, 'Media studies', 1, 2, 2, 'being admitted to one of the MA\'s of the Faculty of Humanities', '2013-2014', NULL, 1, 'http://studiegids.uva.nl/web/uva/sgs/nl/c/13086.html"",""http://studiegids.uva.nl/web/uva/sgs/nl/c/13086.html""', NULL, 60, 'Rogers@uva.nl;E.Rutten1@uva.nl', 'mw. dr. Y van Dijk, prof. dr. R.A. Rogers (coordinator)', 4.893700, 52.368000),
	(46, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Digital Humanities', 1, 9, 9, 'Humanities', 1, 1, 1, 'being admitted to the honours program humanities', '2014', NULL, 1, 'http://studiegids.uva.nl/web/uva/2011_2012/nl/c/12131.html', NULL, 30, 'L.W.M.Bod@uva.nl ', 'L.W.M.Bod', 4.893700, 52.368000),
	(47, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Digital Humanities', 1, 9, 9, 'Philsophy', 2, 6, 2, 'The student has been admitted to a research master', '2014', NULL, 1, 'http://studiegids.uva.nl/web/uva/2011_2012/nl/c/12442.html', NULL, 10, 'L.W.M.Bod@uva.nl ', 'L.W.M.Bod', 4.893700, 52.368000),
	(48, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Book and Digital Media Studies', 1, 10, 11, 'Humanities', 1, 2, 2, 'No formal requirements', '09 2014', NULL, 1, 'https://studiegids.leidenuniv.nl/studies/show/2636/media-studies-book-and-digital-media-studiesl ', NULL, 60, 'p.a.f.verhaar@hum.leidenuniv.nl', 'Peter Verhaar or Fleur Praal,', 4.486760, 52.156700),
	(50, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Digital Humanities', 1, 9, 10, 'Arts', 2, 6, 2, 'No entry requirements. The courses are open to all students. They are specially recommended for students of History, Literature, Arts, Cultural Studies and Cultural Anthropology', '2014', NULL, 1, 'http://www.let.vu.nl/nl/opleidingen/minoren/early-modern-culture/course-descriptions/index.asp', NULL, 6, 'i.b.leemans@vu.nl', 'prof. dr. Inger Leemans & dr. E. Jorink (Huygens Institute/ING)', 4.893700, 52.368000),
	(51, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Digital Humanities', 2, 12, 13, 'Faculty of Arts and Philosophy', 2, 6, 1, 'none', '2014', NULL, 1, 'http://www.ua.ac.be/main.aspx?c=.OOD2012&n=105258&ct=105258&e=291065&detail=1016FLWTLA', NULL, 4, 'thomas.crombez@uantwerpen.be', 'Thomas Crombez', 4.413980, 51.223900),
	(52, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Ontwikkeling, gebruik en beleid van nieuwe media', 2, 11, 12, 'Faculty of Economic, Political and Social Sciences and Solvay Business School - Department of Communication Studies', 2, 5, 1, 'IS&G', '2014', NULL, 1, 'https://caliweb.cumulus.vub.ac.be/caliweb/?page=course-offer&id=000384&anchor=1&target=pr&language=nl&output=html', NULL, 6, 'jo.pierson@vub.ac.be', 'Jo Pierson', 4.394140, 50.819698),
	(53, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Advanced theoretical debates: digital media marketing', 2, 11, 12, 'Faculty of Economic, Political and Social Sciences and Solvay Business School - Department of Communication Studies', 2, 6, 2, 'MA profile SBM', '2014', NULL, 1, 'https://caliweb.cumulus.vub.ac.be/caliweb/?page=course-offer&id=007443&anchor=1&target=pr&language=nl&output=html', NULL, 6, 'jo.pierson@vub.ac.be', 'Jo Pierson', 4.394140, 50.819698),
	(54, NULL, 1, '2014-06-03 17:16:10', '2014-06-03 17:16:29', 'Users and innovation in new media', 2, 11, 12, 'Faculty of Economic, Political and Social Sciences and Solvay Business School - Department of Communication Studies', 2, 6, 2, '', '2014', NULL, 1, 'https://caliweb.cumulus.vub.ac.be/caliweb/?page=course-offer&anchor=1&id=006852&target=pr&language=nl&output=html', NULL, 6, 'jo.pierson@vub.ac.be', 'Jo Pierson', 4.394140, 50.819698),
	(100, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Angewandte Informatik (Medien - Kultur  - Interaktion)', 3, 14, 14, 'Fakultät Wirtschafts-informatik/Angewandte Informatik (WIAI)', 1, 1, 3, '', NULL, '', 1, 'http://www.uni-bamberg.de/ba-ai/', '', 210, 'andreas.henrich@wiai.uni-bamberg.de', 'Prof. Dr. Andreas Henrich', 10.886021, 49.893758),
	(101, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Angewandte Informatik', 3, 14, 14, 'WIAI', 1, 2, 3, 'BA in AI oder eng verwandtem Fach', NULL, '', 1, 'http://www.uni-bamberg.de/ma-ai/', '', NULL, 'andreas.henrich@wiai.uni-bamberg.de', 'Prof. Dr. Andreas Henrich', 10.886021, 49.893758),
	(102, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Computing in the Humanities', 3, 14, 14, 'WIAI', 1, 2, 3, 'BA in Geistes-, Kultur- oder Humanwiss.', NULL, '', 1, 'http://www.uni-bamberg.de/ma-cith/', '', NULL, 'christoph.schlieder@uni-bamberg.de', 'Prof. Dr. Christoph Schlieder', 10.886021, 49.893758),
	(103, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Texttechnologie und Computerlinguistik (Nebenfach)', 3, 15, 15, 'Fakultät für Linguistik und Lit.wiss.', 1, 1, 3, '', NULL, '', 1, 'http://ekvv.uni-bielefeld.de/sinfo/publ/bachelor/texttechnologie;jsessionid=53EC3E73BE27CFAA999214EC80E1EA56.publ_ekvvb', 'http://ekvv.uni-bielefeld.de/sinfo/publ/bachelor/texttechnologie;jsessionid=0A33A429BD4D842F667ACAA9E2F2FD65.publ_ekvvb?m', NULL, '', '', 8.496345, 52.035418),
	(104, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Linguistik: Kommunikation, Kognition und Sprachtechnologie', 3, 15, 15, 'Fakultät für Linguistik und Lit.wiss.', 1, 2, 3, '6-semestriges Bachelorstudium mit deutlichem sprachwiss. Anteil', NULL, '', 1, 'http://www.uni-bielefeld.de/lili/studium/faecher/linguistik/master-ab-ws-10-11/index.htm', 'http://www.uni-bielefeld.de/lili/studium/faecher/linguistik/master-ab-ws-10-11/module.htm', NULL, '', '', 8.496345, 52.035418),
	(105, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Interdisziplinäre Medienwissenschaft', 3, 15, 15, 'Fakultät für Linguistik und Lit.wiss.', 1, 2, 3, 'kompliziert, siehe http://www.uni-bielefeld.de/medienwissenschaft/downloads/fsb.pdf', NULL, '', 1, 'http://www.uni-bielefeld.de/medienwissenschaft/', 'http://ekvv.uni-bielefeld.de/kvv_publ/publ/Studiengang_Vorlesungsverzeichnis_.jsp;jsessionid=66EC4ED6CE53E77F5E69630CEE51559A.publ_ekvva', NULL, '', '', 8.496345, 52.035418),
	(106, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Joint Bachelor of Arts (viele Studiengänge möglich)', 3, 16, 16, 'Institut für Sprach- und Literaturwissenschaft', 1, 1, 3, '', NULL, '', 1, 'http://www.ifs.tu-darmstadt.de/index.php?id=2853', '', NULL, '', '', 8.656518, 49.874948),
	(107, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Master of Arts Linguistics and Literary Computing', 3, 16, 16, 'Institut für Sprach- und Literaturwissenschaft', 1, 2, 3, '(Joint-) B.A. in Germanistik, Anglistik oder einer anderen Philologie oder BSC in Informatik mit mindestens der Note 2,0', NULL, '', 1, 'http://www.linglit.tu-darmstadt.de/index.php?id=ma-llc', '', NULL, '', '', 8.656518, 49.874948),
	(108, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Internet- und Web-basierte Systeme', 3, 16, 16, 'Fachbereich Informatik', 1, 2, 3, 'BSc im Studiengang Informatik der TU Darmstadt oder ein gleichwertiger Abschluss', NULL, '', 1, 'https://www.informatik.tu-darmstadt.de/de/studierende/studiengaenge/masterstudiengaenge/spezialisierte-masterstudiengaenge/internet-und-web-basierte-systeme/', '', NULL, '', '', 8.656518, 49.874948),
	(109, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Zwei-Fach-Bachelor (of Arts) mit Erstfach Informatik', 3, 17, 17, 'Philosophische Fakultät', 1, 1, 3, '', NULL, '', 1, 'http://www.informatik.studium.uni-erlangen.de/studieninteressierte/zweifachba.shtml', '', NULL, '', '', 11.004551, 49.597880),
	(110, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Linguistische Informatik', 3, 17, 17, 'Professur für Computerlinguistik', 1, 1, 3, '', NULL, '', 1, 'http://www.uni-erlangen.de/studium/studienangebot/uebersicht/docs/Linguistische_Informatik_BA.pdf und http://www.linguistik.uni-erlangen.de/studium-lehre/aktuelle-lehrveranstaltungen.shtml', 'http://www.linguistik.uni-erlangen.de/studium-lehre/module.shtml', NULL, '', '', 11.004551, 49.597880),
	(111, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Linguistische Informatik', 3, 17, 17, 'Professur für Computerlinguistik', 1, 2, 3, '', NULL, '', 1, '', '', NULL, '', '', 11.004551, 49.597880),
	(112, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Computerlinguistik und Texttechnologie', 3, 18, 18, 'Institut Für Germanistik', 1, 2, 3, 'BA mit sprachwiss. Schwerpunkt', NULL, '', 1, 'http://www.uni-giessen.de/cms/fbz/fb05/germanistik/studium/studiengaenge/master/cltt', '', NULL, '', '', 9.984620, 53.566564),
	(113, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Sprachtechnologie und Fremdsprachendidaktik', 3, 18, 18, 'Institut Für Germanistik', 1, 2, 3, 'BA mit  Schwerpunkt Fremdsprachenphilologie (einschließlich DaF) oder Computerlinguistik/ Sprachtechnologie', NULL, '', 1, 'http://www.uni-giessen.de/cms/studium/studienangebot/master/msf/index_html/view?set_language=de', '', NULL, '', '', 9.984620, 53.566564),
	(114, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Mensch-Computer-Interaktion', 3, 19, 19, 'Fachbereich Informatik', 1, 1, 3, '', NULL, '', 1, 'http://www.informatik.uni-hamburg.de/Info/Studium/MCI', 'http://www.informatik.uni-hamburg.de/Info/Studium/BSc/MCI/', NULL, '', '', 9.984620, 53.566564),
	(115, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Deutsche Sprache und Literatur - DH-Anteile -> "Embedded DH"', 3, 19, 19, 'Fachbereich Sprache, Literatur, Medien', 1, 2, 3, 'Hochschulabschluss Germanistik o.ä.', NULL, '', 1, 'http://www.verwaltung.uni-hamburg.de/campuscenter/studienfaecher/Bachelor/studiengang.html?1028294069 (BA) und http://www.slm.uni-hamburg.de/masterstudium (MA)', '', NULL, '', '', 9.984620, 53.566564),
	(116, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Informationsverarbeitung', 3, 20, 20, 'Philosophische Fakultät', 1, 1, 3, '', NULL, '', 1, 'http://phil-fak.uni-koeln.de/ba_informationsverarbeitung.html', '', NULL, '', '', 6.927178, 50.928162),
	(117, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Informationsverarbeitung', 3, 20, 20, 'Philosophische Fakultät', 1, 2, 3, 'B.A. mit computerling./ sprachtechn. und/oder geisteswiss. Komponente; Kenntnisse in Programmierung in einer objektorientierten Programmiersprache ', NULL, '', 1, 'http://phil-fak.uni-koeln.de/4644.html', '', NULL, '', '', 6.927178, 50.928162),
	(118, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Medienwissenschaft (bzw. Medieninformatik als Teilfach)', 3, 20, 20, 'Philosophische Fakultät', 1, 1, 3, '', NULL, '', 1, 'http://phil-fak.uni-koeln.de/ba_mewi.html', '', NULL, '', '', 6.927178, 50.928162),
	(119, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Medienwissenschaft (bzw. Medieninformatik als Teilfach)', 3, 20, 20, 'Philosophische Fakultät', 1, 2, 3, 'BA in Medienwiss. oder in einem Studiengang mit vergleichbarem Curriculum', NULL, '', 1, 'http://phil-fak.uni-koeln.de/4650.html', '', NULL, '', '', 6.927178, 50.928162),
	(120, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'IT-Zertifikat', 3, 20, 20, 'Philosophische Fakultät', 2, 6, 3, 'Angebot richtet sich an alle Studierenden der Phil. Fak. der Uni Köln', NULL, '', 1, 'http://www.hki.uni-koeln.de/ITZert', '', NULL, '', '', 6.927178, 50.928162),
	(121, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digitale Medien / Kulturinformatik', 3, 21, 21, 'Institut für Kultur und Ästhetik Digitaler Medien', 1, 1, 3, '', NULL, '', 1, 'http://www.leuphana.de/college/bachelor/studiengang-minor/digitale-medien-kulturinformatik-dmk.html', '', NULL, 'grossmann@uni-lueneburg.de', 'Rolf Grossmann', 10.401220, 53.228844),
	(122, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Educational Technology', 3, 22, 22, 'Fakultät für Empirische Humanwissenschaften- Bildungstechnologie', 1, 2, 3, 'Hochschulabschluss in Informatik, Pädagogik oder Psychologie', NULL, '', 1, 'http://www.uni-saarland.de/campus/studium/studienangebot/az/edutech.html; http://edutech.uni-saarland.de/', 'http://edutech.uni-saarland.de/uploads/Modulhandbuch%20Master%20Edutech.pdf', NULL, '', '', 7.040975, 49.255028),
	(123, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Educational Technology', 3, 22, 22, 'Department of Educational Technology', 1, 2, 3, '', NULL, '', 1, 'http://edutech.uni-saarland.de/1', 'http://edutech.uni-saarland.de/9', NULL, 'v.gehlen-baum@mx.uni-saarland.de', 'Vera Gehlen-Baum', 7.040975, 49.255028),
	(124, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Humanities', 3, 23, 23, 'Lehrstuhl für Neuere Literatur und Computerphilologie (Phil. Fak.)', 1, 1, 3, '', NULL, '', 1, 'http://www.uni-wuerzburg.de/fuer/studierende/angebot/faecher/digihum', 'http://www.germanistik.uni-wuerzburg.de/lehrstuehle/computerphilologie/studium/bachelor/', NULL, 'sm@germanistik.uni-wuerzburg.de', 'Stephan Moser', 9.970846, 49.783008),
	(125, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Humanities', 3, 23, 23, 'Lehrstuhl für Neuere Literatur und Computerphilologie (Phil. Fak.)', 1, 2, 3, '', NULL, '', 1, 'http://www.uni-wuerzburg.de/fuer/studierende/angebot/faecher/digihum', 'http://www.germanistik.uni-wuerzburg.de/lehrstuehle/computerphilologie/studium/master/', NULL, 'sm@germanistik.uni-wuerzburg.de', 'Stephan Moser', 9.970846, 49.783008),
	(126, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Computerlinguistik', 3, 24, 24, 'Department Linguistik', 1, 1, 3, '', NULL, '', 1, 'http://uni-potsdam.de/studienmglk1/faecher/computerlinguistik.html', '', NULL, '', '', 13.099410, 52.407445),
	(127, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Computerlinguistik und Digital Humanities', 3, 25, 25, 'Fachbereich II: Sprach-, Literatur- und Medienwissenschaften', 1, 1, 3, '', NULL, '', 1, 'http://www.uni-trier.de/index.php?id=722&tx_urtdbstudienangebotverwaltung_pi1[Details]=1&tx_urtdbstudienangebotverwaltung_pi1[dbidfach]=110&tx_urtdbstudienangebotverwaltung_pi1[dbidabschluss]=28', '', NULL, 'koehler@uni-trier.de', 'Reinhard Köhler', 6.688470, 49.745760),
	(128, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Computerlinguistik und Digital Humanities', 3, 25, 25, 'Fachbereich II: Sprach-, Literatur- und Medienwissenschaften', 1, 2, 3, '', NULL, '', 1, 'http://www.uni-trier.de/index.php?id=722&tx_urtdbstudienangebotverwaltung_pi1[Details]=1&tx_urtdbstudienangebotverwaltung_pi1[dbidfach]=110&tx_urtdbstudienangebotverwaltung_pi1[dbidabschluss]=29', '', NULL, '', '', 6.688470, 49.745760),
	(129, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Médiation culturelle, patrimoine et numérique', 4, 27, 44, 'Sciences Humaines et Sociales', 1, 2, 4, 'Titulaires d\'un diplôme de licence ou diplôme de niveau équivalent ou supérieur, en histoire de l\'art, en archéologie et/ou en sciences de l\'information et de la communication.', NULL, '', 1, 'http://www.u-paris10.fr/7777777/0/fiche___formation/&RH=for_dipg%E9n', 'http://www.u-paris10.fr/formation/master-sciences-humaines-et-sociales-br-mention-histoire-de-l-art-archeologie-representations-et-societes-br-specialite-mediation-culturelle-patrimoine-et-numerique--416442.kjsp?STNAV=&RUBNAV=&RH=for_dipg%E9n', NULL, '', '', 2.213282, 48.904221),
	(130, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Document Numérique en Réseau / Parcours Ingénierie de l\'Internet', 4, 28, 43, 'Département Informatique', 1, 2, 4, '', NULL, '', 1, 'https://www.info.unicaen.fr/DNR2I/presentation/index.php', '', NULL, '', '', -0.363888, 49.188971),
	(131, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Spécialité Double Compétence : Informatique et Sciences Sociales (DCISS)', 4, 29, 42, 'Sciences sociales et humaines', 1, 2, 4, 'des étudiants titulaires d’une Licence ou d’un M1 (ou d’une maîtrise) scientifique (sauf informatique) ou non scientifique (Sciences Sociales, Sciences Humaines, Linguistique, etc.)', NULL, '', 1, 'http://imss-www.upmf-grenoble.fr/master-ic2a/specialite-double-competence-informatique-et-sciences-sociales-dciss', 'http://web.ujf-grenoble.fr/ujf/conseils/demandeHabilitation07-10/pdf/ICA_7juin.pdf', NULL, '', '', 5.727910, 45.165465),
	(132, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Information, Communication, Culture et Documentation', 4, 30, 41, 'Sciences Humaines et Sociales', 1, 2, 4, '', NULL, '', 1, 'http://www.univ-lille3.fr/etudes/formations/cursus-master/masters-sciences-humaines-sociales/master-sciences-information-document/', '', NULL, '', '', 3.143432, 50.610137),
	(133, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Technologies numériques appliquées à l’histoire', 4, 27, 40, 'L\'École nationale des chartes', 1, 2, 4, 'connaissances générales en histoire ou en lettres', NULL, '', 1, 'http://www.enc.sorbonne.fr/node/24', '', NULL, '', '', 2.343277, 48.848456),
	(134, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Patrimoine écrit et édition numérique', 4, 31, 39, 'Arts, Lettres, Langues', 1, 2, 4, 'Titulaire d\'une licence en Arts, Lettres, Langues, Sciences humaines.', NULL, '', 1, 'http://www.univ-tours.fr/medias/fichier/master-patrimoine-ecrit-et-edition-numerique_1331718824770.pdf', '', NULL, '', '', 0.681452, 47.396337),
	(135, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Histoire et informatique ', 4, 27, 38, 'Laboratoire de Médiévistique Occidentale de Paris', 1, 2, 4, '', NULL, '', 1, 'http://pireh.univ-paris1.fr/pirh/index.php/enseignement/master.html + http://lamop.univ-paris1.fr/spip.php?rubrique126', '', NULL, '', '', 2.344360, 48.846970),
	(136, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'European Heritage, Digital Media and the Information Society (EuroMACHS)', 5, 32, 37, 'Faculty of Humanities', 1, 2, 2, '', NULL, '', 1, 'http://www.europeanheritage.utu.fi/', '', NULL, '', '', 22.284580, 60.454773),
	(137, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Informatica Umanistica - Humanities Computing', 6, 33, 36, '', 1, 2, 5, '', NULL, '', 1, 'http://infouma.di.unipi.it/specialistica/index.asp', '', NULL, '', '', 10.413138, 43.711856),
	(138, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Informatica Umanistica - Humanities Computing', 6, 33, 36, 'Facoltà: Lettere e Filosofia, Scienze Matematiche, Fisiche e Naturali', 1, 1, 5, '', NULL, '', 1, 'http://infouma.di.unipi.it/laurea/index.asp oder (offizielle Uniseite) http://www.unipi.it/corsilaurea/corsi/dett_corso_180.html', '', NULL, '', '', 10.413138, 43.711856),
	(139, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Informatica del Testo ed Edizione Elettronica', 6, 37, 35, 'Facoltà di Lettere e Filosofia di Arezzo e Facoltà di Ingegneria', 1, 2, 5, '', NULL, '', 1, 'http://www.infotext.unisi.it/', '', NULL, 'stella@unisi.it', 'Francesco Stella', 11.332752, 43.319181),
	(140, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digilab', 6, 34, 34, 'Facoltà di Filosofia, Lettere, Scienze umanistiche e Studi orientali', 2, 8, 5, '', NULL, '', 1, '', '', NULL, '', '', 12.512569, 41.901422),
	(141, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Humanities and Culture', 7, 38, 33, 'School of English', 1, 2, 2, 'honors degree (at least an upper second, or a GPA of at least 3.3 in any of the disciplines of the humanities). A critical writing sample is also required (3,000-5,000 words).', NULL, '', 1, 'http://www.tcd.ie/English/postgraduate/digital-humanities/', 'http://www.tcd.ie/English/postgraduate/digital-humanities/digital-hum-options.php', NULL, '', '', -6.254572, 53.343793),
	(142, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Humanities Computing', 7, 39, 32, 'An Foras Feasa Institute', 2, 5, 2, '', NULL, '', 1, 'http://www.learndigitalhumanities.ie/courses/undergrad/humanities-computing', '', 5, '', '', -6.601476, 53.384671),
	(143, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Humanities', 7, 39, 32, 'An Foras Feasa Institute', 1, 2, 2, '', NULL, '', 1, 'http://www.learndigitalhumanities.ie/courses/postgrad/ma-digital-humanities', '', NULL, '', '', -6.601476, 53.384671),
	(144, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Arts and Humanities', 7, 39, 32, 'An Foras Feasa Institute', 1, 4, 2, '', NULL, '', 1, 'http://www.learndigitalhumanities.ie/dah', '', NULL, '', '', -6.601476, 53.384671),
	(145, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Arts and Humanities', 7, 40, 31, 'College of Arts, Celtic Studies and Social Sciences', 1, 2, 2, '', NULL, '', 1, 'http://www.ucc.ie/en/study/postgrad/what/acsss/masters/digital/', 'http://www.ucc.ie/en/study/postgrad/what/acsss/masters/digital/', NULL, '', '', -8.491198, 51.893609),
	(147, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Masterprogram i digital kultur', 8, 42, 30, 'Humanistiske fakultet', 1, 2, 6, '', NULL, '', 1, 'http://www.uib.no/studieprogram/MAHF-DIKUL', '', NULL, '', '', 5.321755, 60.387859),
	(148, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'European Heritage, Digital Media and the Information Society (EuroMACHS)', 9, 43, 29, '', 1, 2, 3, 'Abschluss eines geistes- oder kulturwiss. Bachelor-(oder gleichwertigen) Studiums', NULL, '', 1, 'http://www.uni-graz.at/euromachs/', '', NULL, 'walter.scholger@uni-graz.at', 'Walter Scholger', 15.450611, 47.078832),
	(149, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Informationsmodellierung in den Geisteswissenschaften', 9, 43, 29, 'Geisteswiss. Fakultät', 2, 6, 3, 'alle Studierenden können dieses Modul!!! im Rahmen der freien Wahlfächer absolvieren', NULL, '', 1, 'http://www.uni-graz.at/inig1www/inig1www_lehre/content.inig1www-module', '', NULL, 'walter.scholger@uni-graz.at', 'Walter Scholger', 15.450611, 47.078832),
	(150, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'European Heritage, Digital Media and the Information Society (EuroMACHS)', 10, 44, 28, 'Faculdade de Letras', 1, 2, 7, '', NULL, '', 1, 'http://www.uc.pt/en/fluc/euromachs', '', NULL, '', '', -8.424151, 40.208907),
	(151, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Humanities ', 11, 45, 27, 'School of Arts & Humanities / Digital Humanities', 1, 2, 2, 'Minimum 2:1 first degree or overseas equivalent in any arts or humanities subject; familiarity with basic computer use, including email and word processing.  ', NULL, '', 1, 'http://www.kcl.ac.uk/artshums/depts/ddh/study/pgt/madh/index.aspx', '', NULL, 'elena.pierazzo@kcl.ac.uk', 'Elena Pierazzo', -0.037892, 51.450649),
	(152, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Culture and Society', 11, 45, 27, 'School of Arts & Humanities / Digital Humanities', 1, 2, 2, 'Minimum 2:1 first degree or overseas equivalent in any discipline.', NULL, '', 1, 'http://www.kcl.ac.uk/artshums/depts/ddh/study/pgt/madcs/index.aspx', '', NULL, '', '', -0.037892, 51.450649),
	(153, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Asset Management', 11, 45, 27, 'School of Arts & Humanities / Digital Humanities', 1, 2, 2, 'Minimum 2:1 first degree or overseas equivalent in any discipline and/ or substantial work experience involving the creation and/or management of digital resources', NULL, '', 1, 'http://www.kcl.ac.uk/artshums/depts/ddh/study/pgt/madam/index.aspx', '', NULL, '', '', -0.037892, 51.450649),
	(154, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Humanities', 11, 45, 27, 'Centre for Digital Humanities', 1, 2, 2, 'a first or upper second-class Honours degree in a relevant Humanities or Computing discipline from a UK university, or an overseas qualification of an equivalent standard', NULL, '', 1, 'http://www.ucl.ac.uk/dh/courses/mamsc ', '', NULL, '', '', -0.037892, 51.450649),
	(155, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Media & Information Studies', 11, 46, 26, 'Arts, Science and Social Sciences', 1, 1, 2, '', NULL, '', 1, 'http://www.gla.ac.uk/undergraduate/degrees/digitalmedia/', '', NULL, '', '', -4.288201, 55.872121),
	(156, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Digital Humanities', 11, 45, 27, 'School of Arts & Humanities / Digital Humanities', 1, 4, 2, '', NULL, '', 1, 'http://www.kcl.ac.uk/artshums/depts/ddh/study/pgr/index.aspx', '', NULL, '', '', -0.037892, 51.450649),
	(157, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Information Management & Preservation (Digital)/(Archives & Records Management)', 11, 46, 26, 'Arts, Science and Social Sciences', 1, 2, 2, '', NULL, '', 1, 'http://www.gla.ac.uk/postgraduate/taught/informationmanagementpreservationdigitalarchivesrecordsmanagement/', '', NULL, '', '', -4.288201, 55.872121),
	(158, NULL, 1, '2014-08-11 12:00:00', '2014-08-11 12:00:00', 'Médiation culturelle, patrimoine et numérique', 4, 27, 45, 'Sciences Humaines et Sociales', 1, 2, 4, 'Titulaires d\'un diplôme de licence ou diplôme de niveau équivalent ou supérieur, en histoire de l\'art, en archéologie et/ou en sciences de l\'information et de la communication.', NULL, '', 1, 'http://www.u-paris10.fr/7777777/0/fiche___formation/&RH=for_dipg%E9n', 'http://www.u-paris10.fr/formation/master-sciences-humaines-et-sociales-br-mention-histoire-de-l-art-archeologie-representations-et-societes-br-specialite-mediation-culturelle-patrimoine-et-numerique--416442.kjsp?STNAV=&RUBNAV=&RH=for_dipg%E9n', NULL, '', '', 2.560769, 48.976192),
	(163, 26, 1, '2014-08-27 12:15:03', '2014-08-27 12:15:03', 'Zertifikat "Digital Humanities"', 3, 48, 46, 'Philosophische Fakultät', 2, 6, 3, 'being student of a valid program', NULL, '2014-10-01;2015-04-01', 1, 'http://www.phil.uni-passau.de/die-fakultaet/lehrstuehle-professuren/rehbein/studium-dh/zertifikat-dh.html', '', 35, 'malte.rehbein@uni-passau.de', 'Malte Rehbein', 13.451720, 48.567256),
	(164, NULL, 1, NULL, NULL, 'Digital Humanities', 1, 9, 10, 'Faculty of Humanities', 2, 5, 2, 'none', '2014-2015', '2014-09-01', 1, 'http://www.let.vu.nl/nl/opleidingen/minoren/digital-humanities/index.asp', 'file:///C:/Users/Filip/Downloads/vu_amsterdam_printscreen.pdf', 30, 'f.g.t.maas@vu.nl', 'Fernie Maas', 4.893700, 52.368000);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.courses_tadirah_activities
DROP TABLE IF EXISTS `courses_tadirah_activities`;
CREATE TABLE IF NOT EXISTS `courses_tadirah_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `tadirah_activity_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tadirah_activity_id` (`tadirah_activity_id`),
  KEY `FK_courses_tadirah_activities_courses` (`course_id`),
  CONSTRAINT `FK_courses_tadirah_activities_courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_courses_tadirah_activities_tadirah_activities` FOREIGN KEY (`tadirah_activity_id`) REFERENCES `tadirah_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.courses_tadirah_activities: ~85 rows (ungefähr)
/*!40000 ALTER TABLE `courses_tadirah_activities` DISABLE KEYS */;
INSERT INTO `courses_tadirah_activities` (`id`, `course_id`, `tadirah_activity_id`) VALUES
	(7, 53, 58),
	(9, 29, 4),
	(10, 29, 44),
	(11, 29, 23),
	(13, 31, 41),
	(14, 31, 10),
	(16, 36, 4),
	(17, 36, 50),
	(18, 36, 39),
	(25, 42, 10),
	(26, 42, 4),
	(33, 21, 4),
	(34, 21, 26),
	(35, 21, 10),
	(36, 12, 10),
	(37, 12, 47),
	(38, 8, 10),
	(39, 8, 47),
	(42, 2, 32),
	(44, 26, 10),
	(45, 26, 4),
	(47, 26, 37),
	(49, 40, 4),
	(50, 40, 10),
	(51, 43, 4),
	(52, 43, 37),
	(53, 44, 5),
	(54, 44, 4),
	(55, 46, 4),
	(56, 46, 37),
	(57, 47, 4),
	(58, 50, 4),
	(59, 51, 4),
	(60, 51, 27),
	(61, 51, 30),
	(63, 1, 4),
	(64, 1, 12),
	(65, 4, 4),
	(66, 32, 2),
	(67, 32, 13),
	(68, 30, 13),
	(69, 30, 23),
	(71, 11, 41),
	(72, 13, 41),
	(73, 14, 4),
	(74, 14, 37),
	(77, 24, 23),
	(78, 23, 4),
	(79, 28, 10),
	(80, 22, 10),
	(81, 48, 51),
	(82, 48, 10),
	(85, 15, 43),
	(91, 16, 24),
	(92, 7, 10),
	(93, 7, 23),
	(94, 7, 25),
	(95, 19, 47),
	(96, 19, 12),
	(98, 20, 47),
	(99, 17, 24),
	(100, 18, 24),
	(101, 35, 4),
	(102, 25, 25),
	(103, 34, 10),
	(105, 34, 26),
	(108, 39, 26),
	(110, 45, 4),
	(112, 5, 4),
	(113, 52, 25),
	(114, 10, 10),
	(116, 27, 10),
	(117, 6, 10),
	(118, 37, 39),
	(121, 54, 10),
	(122, 38, 10),
	(123, 10, 10),
	(124, 20, 4),
	(125, 28, 10),
	(126, 33, 4),
	(127, 163, 1),
	(128, 163, 4),
	(129, 163, 41),
	(130, 163, 50),
	(131, 164, 10);
/*!40000 ALTER TABLE `courses_tadirah_activities` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.courses_tadirah_objects
DROP TABLE IF EXISTS `courses_tadirah_objects`;
CREATE TABLE IF NOT EXISTS `courses_tadirah_objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `tadirah_object_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tadirah_object_id` (`tadirah_object_id`),
  KEY `FK__courses` (`course_id`),
  CONSTRAINT `FK__courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__tadirah_objects` FOREIGN KEY (`tadirah_object_id`) REFERENCES `tadirah_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.courses_tadirah_objects: ~106 rows (ungefähr)
/*!40000 ALTER TABLE `courses_tadirah_objects` DISABLE KEYS */;
INSERT INTO `courses_tadirah_objects` (`id`, `course_id`, `tadirah_object_id`) VALUES
	(5, 53, 6),
	(6, 29, 32),
	(7, 29, 3),
	(8, 29, 34),
	(9, 31, 3),
	(10, 31, 6),
	(11, 36, 32),
	(16, 42, 6),
	(17, 42, 23),
	(18, 21, 3),
	(19, 21, 30),
	(20, 12, 6),
	(21, 8, 5),
	(22, 8, 6),
	(24, 2, 5),
	(25, 2, 32),
	(30, 26, 32),
	(31, 40, 5),
	(34, 40, 5),
	(36, 26, 5),
	(37, 43, 5),
	(39, 43, 32),
	(40, 43, 6),
	(41, 44, 5),
	(43, 44, 6),
	(44, 44, 32),
	(45, 46, 5),
	(46, 46, 6),
	(47, 46, 32),
	(48, 47, 5),
	(49, 47, 23),
	(50, 50, 5),
	(52, 50, 22),
	(53, 50, 6),
	(54, 50, 27),
	(55, 51, 5),
	(56, 51, 6),
	(57, 1, 5),
	(58, 1, 23),
	(59, 1, 3),
	(60, 4, 5),
	(61, 4, 32),
	(63, 32, 23),
	(64, 32, 32),
	(65, 30, 3),
	(66, 11, 6),
	(67, 11, 3),
	(68, 13, 6),
	(69, 14, 23),
	(71, 24, 3),
	(73, 23, 23),
	(74, 28, 5),
	(75, 28, 32),
	(76, 22, 6),
	(77, 48, 5),
	(78, 48, 6),
	(81, 15, 32),
	(82, 16, 30),
	(83, 16, 12),
	(84, 16, 32),
	(85, 7, 3),
	(86, 7, 6),
	(87, 19, 6),
	(88, 20, 3),
	(89, 17, 12),
	(90, 17, 30),
	(91, 18, 12),
	(92, 18, 30),
	(93, 35, 23),
	(94, 25, 23),
	(95, 25, 23),
	(96, 33, 6),
	(97, 33, 23),
	(98, 33, 22),
	(99, 34, 6),
	(100, 34, 5),
	(101, 39, 6),
	(102, 39, 5),
	(103, 45, 32),
	(104, 45, 23),
	(105, 5, 32),
	(106, 5, 5),
	(107, 5, 23),
	(108, 52, 32),
	(109, 10, 6),
	(111, 27, 5),
	(112, 27, 23),
	(113, 27, 6),
	(114, 27, 22),
	(115, 6, 6),
	(116, 6, 32),
	(117, 37, 8),
	(118, 54, 32),
	(119, 54, 6),
	(120, 54, 23),
	(121, 38, 6),
	(122, 37, 33),
	(123, 163, 3),
	(124, 163, 6),
	(125, 163, 7),
	(126, 163, 11),
	(127, 163, 17),
	(128, 163, 18),
	(129, 163, 27),
	(130, 163, 29),
	(131, 164, 5);
/*!40000 ALTER TABLE `courses_tadirah_objects` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.courses_tadirah_techniques
DROP TABLE IF EXISTS `courses_tadirah_techniques`;
CREATE TABLE IF NOT EXISTS `courses_tadirah_techniques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `tadirah_technique_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tadirah_technique_id` (`tadirah_technique_id`),
  KEY `FK_courses_tadirah_techniques_courses` (`course_id`),
  CONSTRAINT `FK_courses_tadirah_techniques_courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_courses_tadirah_techniques_tadirah_techniques` FOREIGN KEY (`tadirah_technique_id`) REFERENCES `tadirah_techniques` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.courses_tadirah_techniques: ~61 rows (ungefähr)
/*!40000 ALTER TABLE `courses_tadirah_techniques` DISABLE KEYS */;
INSERT INTO `courses_tadirah_techniques` (`id`, `course_id`, `tadirah_technique_id`) VALUES
	(1, 53, 15),
	(3, 53, 16),
	(6, 29, 32),
	(7, 29, 3),
	(11, 31, 24),
	(13, 36, 18),
	(14, 32, 13),
	(15, 30, 13),
	(18, 42, 15),
	(19, 21, 18),
	(21, 12, 16),
	(22, 8, 27),
	(25, 2, 3),
	(26, 2, 32),
	(28, 26, 15),
	(31, 40, 15),
	(32, 40, 16),
	(33, 43, 15),
	(34, 44, 15),
	(35, 46, 15),
	(36, 47, 15),
	(37, 50, 15),
	(39, 51, 15),
	(41, 1, 10),
	(42, 1, 38),
	(43, 4, 15),
	(44, 11, 14),
	(45, 13, 14),
	(47, 14, 25),
	(48, 24, 23),
	(49, 23, 3),
	(50, 23, 38),
	(51, 28, 15),
	(52, 28, 16),
	(53, 22, 16),
	(54, 48, 38),
	(56, 15, 14),
	(58, 16, 35),
	(59, 48, 10),
	(61, 7, 32),
	(62, 7, 38),
	(63, 19, 15),
	(64, 20, 15),
	(66, 35, 10),
	(67, 25, 10),
	(68, 34, 10),
	(69, 39, 10),
	(70, 45, 10),
	(71, 5, 10),
	(72, 52, 10),
	(73, 10, 16),
	(74, 27, 15),
	(75, 6, 35),
	(76, 37, 25),
	(77, 54, 10),
	(78, 38, 16),
	(79, 163, 2),
	(80, 163, 6),
	(81, 163, 31),
	(82, 163, 36),
	(83, 164, 16);
/*!40000 ALTER TABLE `courses_tadirah_techniques` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.languages
DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.languages: ~7 rows (ungefähr)
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` (`id`, `name`) VALUES
	(1, 'Dutch'),
	(2, 'English'),
	(3, 'German'),
	(4, 'French'),
	(5, 'Italian'),
	(6, 'Norwegian'),
	(7, 'Portugese');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.parent_types
DROP TABLE IF EXISTS `parent_types`;
CREATE TABLE IF NOT EXISTS `parent_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'a short name',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.parent_types: ~2 rows (ungefähr)
/*!40000 ALTER TABLE `parent_types` DISABLE KEYS */;
INSERT INTO `parent_types` (`id`, `name`) VALUES
	(1, 'Degree'),
	(2, 'Credits');
/*!40000 ALTER TABLE `parent_types` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.tadirah_activities
DROP TABLE IF EXISTS `tadirah_activities`;
CREATE TABLE IF NOT EXISTS `tadirah_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `FK_tadirah_activities_tadirah_activities` FOREIGN KEY (`parent_id`) REFERENCES `tadirah_activities` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.tadirah_activities: ~67 rows (ungefähr)
/*!40000 ALTER TABLE `tadirah_activities` DISABLE KEYS */;
INSERT INTO `tadirah_activities` (`id`, `parent_id`, `name`, `description`) VALUES
	(1, 59, 'Capture', 'Capture generally refers to the activity of creating digital surrogates of existing cultural artefacts, or expressing existing artifacts in a digital representation (digitization). This could be a manual process (as in "transcribing") or an automated procedure (as in "imaging" or "data_recognition"). Such capture precedes "enrichment" and "analysis", at least from a systematic point of view, if not in practice. '),
	(2, 59, 'Creation', 'Creating things generally refers to the activity of producing born-digital digital objects, rather than creating digital objects by capturing and digitizing existing analog objects. Creating can involve writing natural language text (cf. "writing") or, understood more broadly as the creation of a string of discrete symbols, it could also concern other forms of expression, such as creating executable code (cf. "programming"), composing a musical score, or creating an image.'),
	(3, 59, 'Enrichment', 'Enrichment refers to the activity of adding information to an object of enquiry, by making its origin, nature, structure, meaning, or elements explicit. This activity typically follows the capture of the object. '),
	(4, 59, 'Analysis', 'This general research goal refers to the activity of extracting any kind of information from open or closed, structured or unstructured collections of data, of discovering recurring phenomena, units, elements, patterns, groupings, and the like. This can refer to structural, formal or semantic aspects of data. Analysis also includes methods used to visualize results. Methods and techniques related to this goal may be considered to follow "capture" and "enrichment"; however, "enrichment" depends upon assumptions, research questions and results related to "analysis". '),
	(5, 59, 'Interpretation', 'Interpretation is the activity of ascribing meaning to phenomena observed in "analysis". Therefore, interpretation usually follows analysis, although it could also be considered that interpretation defines the hermeneutic perspective of any method of analysis. '),
	(6, 59, 'Storage', 'Storing refers to the activity of making digital copies of objects of inquiry, results of research, or software and services and of keeping them accessible, without necessarily making them available to the public. '),
	(7, 59, 'Dissemination', 'Dissemination refers to the activity of making objects of inquiry, results of research, or software and services available to fellow researchers or the wider public in a variety of more or less formal ways. It builds on or requires storing and can include releasing and sharing of data using a variety of methods and techniques including the application of linked open data.'),
	(9, 1, 'conversion', 'Conversion refers to changing the file format of an object (e.g. converting a .wmv video to a .mov file as well as converting VHS into a digital format) without fundamentally changing the content or nature of the object. When conversion concerns metadata, it involves mapping one metadata schema to another. More fundamental “conversions”, such as converting a scanned page image to an editable text document, are better referred to using “DataRecognition”.'),
	(10, 1, 'DataRecognition', 'Data Recognition, for example OCR, refers to the process of treating the immediate products of digital data capture (recording or imaging), such as digital facsimiles of texts or of sheet music, in a way to extract discrete, machine-readable units from them, such as plain text words, musical notes, or still or moving image elements (including, for example, face recognition).'),
	(11, 1, 'Discovering', 'Discovering is the activity of seeking out objects of research, research results, or other information which is useful in a given search perspective. Discovery includes very directed techniques such as advanced querying of databases, less directed techniques such as simple searching, and more serendipitous ones as browsing, which would include faceted browsing. (It is different from Information Retrieval, which is a structured way of extracting some piece of information or some specific subset of objects from a resource.)'),
	(12, 1, 'Gathering', 'Gathering refers to aggregating discovered resources, usually in some structured way (e.g. bringing together all papers that address a certain topic, as part of a literature review, or pulling all works by a particular author out of a digital collection for further analysis). Related techniques include web crawling and scraping.'),
	(13, 1, 'Imaging', 'Imaging refers to the capture of texts, images, artefacts or spatial formations using optical means of capture. Imaging can be made in 2D or 3D, using various means (light, laser, infrared, ultrasound). Imaging usually does not lead to the identification of discrete semantic or structural units in the data, such as words or musical notes, which is something DataRecognition accomplishes. Imaging also includes scanning and digital photography.'),
	(14, 1, 'Recording', 'Capturing audio and/or video; the result is a digital audio (e.g. WAV, MP3, etc.) or video (e.g. MP4, Quicktime, etc.) file that can be manipulated, analyzed, and/or stored.'),
	(21, 1, 'Transcription', 'Transcription is the activity of creating a representation of a manuscript (often in combination with Enrichment) or of audio or video recordings. The representation is (also) generally textual for the verbal aspects of recordings and structured for example by speech turns, but can also contain multimodal information like gestures or events and multimedia information like time synchronization and relation to media files. Transcription that is partial, selective, and/or inherently linked to the source document may be better categorized as Annotation.'),
	(22, 2, 'Designing', 'The development of a user interface, with which the user is able to interact to perform various tasks and conduct activities. Also included here is the development of the user experience, where a person’s perceptions of the practical aspects such as utility, ease of use, and efficiency of the system are taken into consideration. Does not include the implementation of the design (see “Programming” or “Web development”). Database design is to be categorized using “Modeling”.'),
	(23, 2, 'Programming', 'Creation of code executable by a computer, that is creation of scripts or software. (This includes “Prototyping”, the creation of such code for testing or modeling purposes.) It is also closely related to the more broader activity of tool development. Programming is separate from Encoding (enriching a document by making structural, layout-related, semantic, or other information about a specific part of a document explicit by adding markup to its transcription).'),
	(24, 2, 'Translation', 'Translation involves creating a new linguistic object based on a source document but written in a different language than the source. This applies to both natural languages and machine-readable programming languages'),
	(25, 2, 'Web development', 'Creation of websites, by building on a platform (e.g. content management systems such as Drupal, WordPress and Omeka) or writing HTML/CSS. Writing a module/plugin for a platform, or programming web-based applications, should use the “Programming” method.'),
	(26, 2, 'Writing', 'Writing designates the activity of creating new texts (instead of capturing existing text). In our context, this would primarily concern research papers and reports, but may include other textually-oriented objects.'),
	(27, 3, 'Annotating', 'Annotating refers to the activity of making information about a digital object explicit by adding, e.g., comments, metadata or keywords to a digitized representation or to an annotation file associated with it. This can be in the form of annotations that comment on or contextualize a passage (explanatory annotations) in order to make structural or linguistic information explicit (structural/linguistic annotation), as linked open data making the relationships between objects machine-readable, or, in the case of general metadata, adding information about the object as a whole. Encoding is a technique associated with annotating, as are POS-Tagging, Tree-Tagging, and Georeferencing.'),
	(28, 3, 'Clean up', 'Data cleanup involves improving the quality of an existing digital object. This could include such things as correcting errors in a written text, errors in OCR results, debugging code, improving the quality of video, audio, or image file'),
	(29, 3, 'Editing', 'Editing refers to making structural, layout-related, semantic, or other information about a specific part of a document explicit by adding (inline or stand-off) markup to its transcription. This is typically part of the larger activity of scholarly editing of textual, musical, or other sources. It is based on a transcription of the document (the result of data recognition) and guided by a model of the document (the result of modeling).'),
	(30, 4, 'Content Analysis', 'Content Analysis is a method which aims to analyse aspects of digital objects relating to their meaning, such as identifying concepts or meaningful units. Relevant techniques include Topic Modeling, Sentiment Analysis, Information Retrieval, Discourse Analysis, but also Named Entity Recognition.'),
	(31, 4, 'Network Analysis', 'Network Analysis is a method to study the relations of (real or fictional) actors or other entities in a mediated network, which can take the form of a social or academic online network, a set of correspondence, or a work of literature; the resulting network is usually made up of nodes (entities) and edges (relations). Relevant techniques include Named Entity Recognition. When the artefacts themselves (texts, images, etc.) and their relations are concerned, the corresponding research activity would be Relational Analysis.'),
	(32, 4, 'Relational Analysis', 'Relational Analysis refers to computational techniques serving to discover specific relations between several objects of study. In textual studies, this could mean discovering overlap between several different texts (study of text reuse / plagiarism), or textual variations between several versions of one text (collation), or assessing the similarity of texts in terms of stylistic features (stylometry). By analogy, such methods can also be applied to other cultural artefacts, such as music, film or painting. Relevant techniques include Sequence Alignment, Collation, and techniques associated with Stylistic Analysis.'),
	(33, 4, 'Spatial Analysis', 'Relational Analysis refers to computational techniques serving to discover specific relations between several objects of study. In textual studies, this could mean discovering overlap between several different texts (study of text reuse / plagiarism), or textual variations between several versions of one text (collation), or assessing the similarity of texts in terms of stylistic features (stylometry). By analogy, such methods can also be applied to other cultural artefacts, such as music, film or painting. Relevant techniques include Sequence Alignment, Collation, and techniques associated with Stylistic Analysis.'),
	(37, 4, 'Structural Analysis', 'Structural Analysis involves analysis of objects on the level of the relations between structural elements of a cultural artefact (level of morphology or syntax in linguistics). Relevant techniques include: POS-Tagging, Tree-Tagging, Collocation Analysis, Concordancing.'),
	(38, 4, 'Stylistic Analysis', 'Stylistic Analysis consists of identifying stylistic or formal features of digital objects. Although computational stylistics is in many cases applied to texts and based on linguistic features, it can also be applied to other media such as physical artifacts, painting, music or movies. Relevant techniques include: Stylometry, Principal Component Analysis, Cluster Analysis, Paleographic Analysis.'),
	(39, 4, 'Visualization', 'Visualization refers to activities which serve to summarise and present in a graphical form, and to use such graphical forms analytically, that is to detect patterns, structures, or points of interest in the underlying data. Virtually any kind of data can be visualized, and the forms of visualizations can be images, maps, timelines, graphs, or tables, and the like. Relevant techniques include plotting and mapping.'),
	(40, 5, 'Contextualizing', 'Contextualization is the activity of creating associations between an object of investigation and other, more established or better-understood objects in a relation of geographical, temporal, or thematic proximity to the object of investigation, with the aim of ascribing meaning to that object. Such contextualizing may build on existing annotations and/or metadata.'),
	(41, 5, 'Modeling', 'Modeling is the activity of creating an abstract representation of a complex phenomenon, usually in a machine-readable way, possibly in an interactive way (i.e. it includes “simulation”). Models become machine-readable when modelling produces a schema that describes the elements and the structure of an object of inquiry in an explicit way. Modeling can also refer to the activity of transforming or manipulating a digital object in such a way as to make it compatible with a previously constructed model or schema. Mapping, for instance, is an example of a spatial model. Workflow design is included as part of Modeling, using an object such as Process.'),
	(42, 5, 'Theorizing', 'Theorizing is a method which aims to relate a number of elements or ideas into a coherent system based on some general principles and capable of explaining relevant phenomena or observations. Theorizing relies on techniques such as reasoning, abstract thinking, conceptualizing and defining. A theory may be implemented in the form of a model, or a model may give rise to formulating a theory.'),
	(43, 6, 'Archiving', 'Archiving includes the process of moving data and other resources to a separate space for retention. If long-term archiving is involved, activities related to data preservation may also be involved.'),
	(44, 6, 'Identifying', 'Identifying refers to the activity of naming and/or assigning (possibly unique and/or persistent) identifiers to objects of enquiry or to any kind of digital object. Adding a metadata description of the object is part of Annotation'),
	(45, 6, 'Organizing', 'Organizing refers to the arrangement of objects (research materials, data sets, images, etc.) in a way that facilitates other research activities. May also include activities that support discovery such as metadata creation and enhancement.'),
	(47, 6, 'Preservation', 'The application of specific strategies, activities and technologies for the purpose of ensuring an accurate rendering of digital content over time. It facilitates the reuse of research data, objects, and related resources and may include activities related to sustainability and interoperability. Related techniques include but are not limited to: Bit Stream Preservation, Durable Persistent Media, Emulation, Metadata Attachment, Migration, Replication, Technology Preservation, Versioning, the use of Open Archival Information Systems and standards that support interoperability.'),
	(48, 7, 'Collaboration', 'Collaboration is involved in any research activity being done jointly by several researchers, possibly in different places and at different times. Research-oriented collaboration is enabled, particularly, through comprehensive Digital Research Environments, but can also happen around more specific activities, such as communication or sharing of resources.'),
	(49, 7, 'Commenting', 'Commenting is the activity of adding information to a piece of data, usually in a way that separates the data to which the comment is attached and the comment. It usually serves to express some opinion, to add contextual information, or to engage in communication or collaboration with others about the object commented on. This is different from Annotating (as defined here) which refers to adding descriptive or explanatory information to sections of an object with the aim of making inherent qualities, structures, or meanings of that section explicit.'),
	(50, 7, 'Communicating', 'Communicating refers to the activity of exchanging ideas with other people, primarily, but not exclusively, using linguistic means. Relevant techniques include Email, Chat, Audio-Conferencing'),
	(51, 7, 'Crowdsourcing', 'Crowdsourcing refers to the paradigm of user-generated content in a web 2.0 context, applied here to the domain of digital humanities research. Crowdsourcing may include gamification, which may be understood as one form of creating motivation in crowdsourcing endeavors.'),
	(52, 7, 'Publishing', 'Publishing refers to the activity of making any kind of object formally available to the wider public. This can involve objects of research, research data, research results, or tools and services. Publishing can be closed or open access / open source, and research results can be published in print or digital formats.'),
	(53, 7, 'Sharing', 'Sharing refers to the activity of making objects publically available through informal channels such as blogs, code sharing sites such as GitHUB, or other social media sites.'),
	(54, 60, 'Assessing', 'Assessing refers to the activity of verifying the existence of certain properties, usually indicative of some desirable quality in some outcome of an activity. This may refer to reviewing research papers or conference proposals, to evaluating the coherence of the annotation of audio-visual materials, or to an assessment of the usefulness of the Digital Humanities.'),
	(55, 60, 'Community Building', 'Community building is the activity of creating or enhancing a community with a common interest. It may include dissemination, teaching as well as advocating for specific activities, practices, or values.'),
	(56, 60, 'Give Overview', 'GiveOverview refers to the activity of providing information which is relatively general or provides a historical or systematic overview of a given topic. Nevertheless, it can be aimed at experts or beginners in a field, subfield or specialty.'),
	(57, 60, 'Project Management', 'Project management involves activities such as developing a strategy and assessing risk for conducting a project, as well as task management activities, such as keeping a record of tasks, due dates, and other relevant information. It may include activities such as planning, documenting, getting funding, but also sending reminders and status reports. Project Management is related to Collaboration.'),
	(58, 60, 'Teaching/Learning', 'Teaching and Learning involves one group of people interactively helping another group of people acquire and/or develop skills, competencies, and knowledge that lets them solve problems in a specific area of research.'),
	(59, NULL, 'Research Activities', 'Research activities are usually applied to one or several research objects. An article about modeling of manuscript properties would therefore be tagged with the tags “modeling” and “manuscript”. A plain text editor would be tagged with the tags "writing" and "code" and "text". '),
	(60, NULL, 'Meta Activities', 'Meta Activities are activities which, unlike regular research activities, do not apply directly to a research object, but rather to a combination of a research activity with a research object. A case in point would be a tutorial "teaching" the digital "encoding" of "music", or a report "introducing" readers to "pattern_recognition" in "images". Meta-Activity tags can be added to provide additional context to a typical activity+object pair of tags. In some cases, however, meta-activities may also apply to objects, for example in the case of objects like “infrastructure” or “digital_humanities”. '),
	(164, NULL, '0', NULL);
/*!40000 ALTER TABLE `tadirah_activities` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.tadirah_activities_tadirah_techniques
DROP TABLE IF EXISTS `tadirah_activities_tadirah_techniques`;
CREATE TABLE IF NOT EXISTS `tadirah_activities_tadirah_techniques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tadirah_activity_id` int(11) NOT NULL DEFAULT '0',
  `tadirah_technique_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tadirah_activities` (`tadirah_activity_id`),
  KEY `tadirah_techniques` (`tadirah_technique_id`),
  CONSTRAINT `FK_tadirah_activities_tadirah_techniques_tadirah_activities` FOREIGN KEY (`tadirah_activity_id`) REFERENCES `tadirah_activities` (`id`),
  CONSTRAINT `FK_tadirah_activities_tadirah_techniques_tadirah_techniques` FOREIGN KEY (`tadirah_technique_id`) REFERENCES `tadirah_techniques` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.tadirah_activities_tadirah_techniques: ~30 rows (ungefähr)
/*!40000 ALTER TABLE `tadirah_activities_tadirah_techniques` DISABLE KEYS */;
INSERT INTO `tadirah_activities_tadirah_techniques` (`id`, `tadirah_activity_id`, `tadirah_technique_id`) VALUES
	(1, 47, 10),
	(6, 47, 11),
	(7, 51, 13),
	(8, 27, 14),
	(9, 30, 15),
	(10, 27, 16),
	(12, 52, 16),
	(13, 37, 18),
	(14, 38, 18),
	(15, 30, 18),
	(16, 47, 21),
	(17, 27, 22),
	(18, 30, 22),
	(19, 47, 23),
	(20, 32, 24),
	(21, 37, 26),
	(22, 47, 27),
	(24, 47, 30),
	(25, 30, 33),
	(26, 32, 34),
	(27, 47, 35),
	(28, 30, 36),
	(29, 47, 37),
	(30, 12, 38),
	(31, 38, 28),
	(32, 38, 9),
	(33, 37, 7),
	(34, 37, 5),
	(36, 37, 4),
	(37, 47, 1);
/*!40000 ALTER TABLE `tadirah_activities_tadirah_techniques` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.tadirah_objects
DROP TABLE IF EXISTS `tadirah_objects`;
CREATE TABLE IF NOT EXISTS `tadirah_objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.tadirah_objects: ~35 rows (ungefähr)
/*!40000 ALTER TABLE `tadirah_objects` DISABLE KEYS */;
INSERT INTO `tadirah_objects` (`id`, `name`, `description`) VALUES
	(1, 'Artifacts', ''),
	(2, 'BibliographicListings', ''),
	(3, 'Computers', ''),
	(4, 'Curricula', ''),
	(5, 'DigitalHumanities', NULL),
	(6, 'Data', NULL),
	(7, 'File', NULL),
	(8, 'Images', NULL),
	(9, 'Images(3D)', NULL),
	(10, 'Infrastructure', NULL),
	(11, 'Interaction', NULL),
	(12, 'Language', NULL),
	(13, 'Link', NULL),
	(14, 'Literature', NULL),
	(15, 'Manuscript', NULL),
	(16, 'Map', NULL),
	(17, 'Metadata', NULL),
	(18, 'Multimedia', NULL),
	(19, 'Multimodal', NULL),
	(20, 'NamedEntities', NULL),
	(21, 'Persons', NULL),
	(22, 'Projects', NULL),
	(23, 'Research', NULL),
	(24, 'ResearchProcess', NULL),
	(25, 'ResearchResults', NULL),
	(26, 'SheetMusic', NULL),
	(27, 'Software', NULL),
	(28, 'Sound', NULL),
	(29, 'Standards', NULL),
	(30, 'Text', NULL),
	(31, 'TextBearingObjects', NULL),
	(32, 'Tools', NULL),
	(33, 'Video', NULL),
	(34, 'Visualization', NULL),
	(35, 'VREs', NULL);
/*!40000 ALTER TABLE `tadirah_objects` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.tadirah_techniques
DROP TABLE IF EXISTS `tadirah_techniques`;
CREATE TABLE IF NOT EXISTS `tadirah_techniques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.tadirah_techniques: ~35 rows (ungefähr)
/*!40000 ALTER TABLE `tadirah_techniques` DISABLE KEYS */;
INSERT INTO `tadirah_techniques` (`id`, `name`, `description`) VALUES
	(1, 'Bit Stream Preservation ', NULL),
	(2, 'Brainstorming', NULL),
	(3, 'Browsing', NULL),
	(4, 'Cluster Analysis ', NULL),
	(5, 'Collocation Analysis ', NULL),
	(6, 'Commenting', NULL),
	(7, 'Concordancing ', NULL),
	(8, 'Debugging', NULL),
	(9, 'Distance Measures', NULL),
	(10, 'Durable Persistent Media ', NULL),
	(11, 'Emulation ', NULL),
	(12, 'Encoding', NULL),
	(13, 'Gamification', NULL),
	(14, 'Georeferencing ', NULL),
	(15, 'Information Retrieval', NULL),
	(16, 'Linked open data ', NULL),
	(18, 'Machine Learning', NULL),
	(19, 'Mapping', NULL),
	(21, 'Migration ', NULL),
	(22, 'Named Entity Recognition ', NULL),
	(23, 'Open Archival Information Systems', NULL),
	(24, 'Pattern Recognition', NULL),
	(25, 'Photography', NULL),
	(26, 'POS-Tagging', NULL),
	(27, 'Preservation Metadata ', NULL),
	(28, 'Principal Component Analysis', NULL),
	(30, 'Replication ', NULL),
	(31, 'Scanning', NULL),
	(32, 'Searching', NULL),
	(33, 'Sentiment Analysis ', NULL),
	(34, 'Sequence Alignment', NULL),
	(35, 'Technology Preservation', NULL),
	(36, 'Topic Modeling', NULL),
	(37, 'Versioning', NULL),
	(38, 'Web Crawling ', NULL);
/*!40000 ALTER TABLE `tadirah_techniques` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.types
DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_type_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_type_id` (`parent_type_id`),
  CONSTRAINT `FK_types_parent_types` FOREIGN KEY (`parent_type_id`) REFERENCES `parent_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='The course types. Parent types are "degrees awarded" or "credits awarded".';

-- Exportiere Daten aus Tabelle dhcourse-stage.types: ~8 rows (ungefähr)
/*!40000 ALTER TABLE `types` DISABLE KEYS */;
INSERT INTO `types` (`id`, `parent_type_id`, `name`) VALUES
	(1, 1, 'Bachelor'),
	(2, 1, 'Master'),
	(3, 1, 'Research Master'),
	(4, 1, 'PhD'),
	(5, 2, 'Minor'),
	(6, 2, 'Course'),
	(7, 2, 'Summer School'),
	(8, 2, 'Continuing Education');
/*!40000 ALTER TABLE `types` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.universities
DROP TABLE IF EXISTS `universities`;
CREATE TABLE IF NOT EXISTS `universities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `country_id` int(11) DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_universities_cities` (`city_id`),
  KEY `FK_universities_countries` (`country_id`),
  CONSTRAINT `FK_universities_cities` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `FK_universities_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.universities: ~46 rows (ungefähr)
/*!40000 ALTER TABLE `universities` DISABLE KEYS */;
INSERT INTO `universities` (`id`, `city_id`, `country_id`, `name`) VALUES
	(1, 1, 1, 'Erasmus Universiteit Rotterdam'),
	(2, 2, 2, 'Katholieke Universiteit Leuven'),
	(3, 3, 1, 'Radboud Universiteit Nijmegen'),
	(4, 4, 1, 'Rijks Universiteit Groningen'),
	(5, 5, 1, 'Tilburg Universiteit'),
	(6, 6, 2, 'Universiteit Gent'),
	(7, 7, 1, 'Universiteit Maastricht'),
	(8, 8, 1, 'Universiteit Utrecht'),
	(9, 9, 1, 'Universiteit van Amsterdam'),
	(10, 9, 1, 'Vrije Universiteit Amsterdam'),
	(11, 10, 1, 'Universiteit van Leiden'),
	(12, 11, 2, 'Vrije Universiteit Brussel'),
	(13, 12, 2, 'Universiteit Antwerpen'),
	(14, 14, 3, 'Otto-Friedrich-Universität Bamberg'),
	(15, 15, 3, 'Universität Bielefeld'),
	(16, 16, 3, 'Technische Universität Darmstadt'),
	(17, 17, 3, 'Friedrich-Alexander-Universität Erlangen-Nürnberg'),
	(18, 18, 3, 'Justus-Liebig-Universität Gießen'),
	(19, 19, 3, 'Universität Hamburg'),
	(20, 20, 3, 'Universität zu Köln'),
	(21, 21, 3, 'Leuphana Universität Lüneburg'),
	(22, 22, 3, 'Universität des Saarlandes'),
	(23, 23, 3, 'Julius-Maximilians-Universität Würzburg'),
	(24, 24, 3, 'Universität Potsdam'),
	(25, 25, 3, 'Universität Trier'),
	(26, 46, 11, 'University of Glasgow'),
	(27, 45, 11, 'King’s College London'),
	(28, 44, 10, 'Universidade de Coimbra'),
	(29, 43, 9, 'Karl-Franzens-Universität Graz'),
	(30, 42, 8, 'Universitetet i Bergen'),
	(31, 40, 7, 'University College Cork'),
	(32, 39, 7, 'National University of Ireland, Maynooth'),
	(33, 38, 7, 'Trinity College Dublin'),
	(34, 34, 6, 'Università di Roma'),
	(35, 37, 6, 'Università degli Studi di Siena'),
	(36, 33, 6, 'Università degli Studi di Pisa'),
	(37, 32, 5, 'University of Turku'),
	(38, 27, 4, 'Université Paris 1 Panthéon-Sorbonne'),
	(39, 31, 4, 'Université Francois Rabelais'),
	(40, 27, 4, 'Université Paris-Sorbonne'),
	(41, 30, 4, 'Université Lille'),
	(42, 29, 4, 'Université Pierre-Mendès-France'),
	(43, 28, 4, 'Université de Caen Basse Normandie'),
	(44, 27, 4, 'Université Paris Ouest Nanterre La Défense'),
	(45, 27, 4, 'Universität Paris 8 Vincennes-Saint-Denis'),
	(46, 48, 3, 'Universität Passau');
/*!40000 ALTER TABLE `universities` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle dhcourse-stage.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `university_id` int(11) DEFAULT NULL,
  `university` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'temporary value provided during registration, if the university is not in the list yet',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `user_admin` tinyint(1) NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `password_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approval_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_token_expires` datetime DEFAULT NULL,
  `email_token_expires` datetime DEFAULT NULL,
  `approval_token_expires` datetime DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `academic_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authority` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  KEY `email` (`email`),
  KEY `email_reset_token` (`email_token`),
  KEY `password_reset_token` (`password_token`),
  KEY `FK_users_universities` (`university_id`),
  CONSTRAINT `FK_users_universities` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Exportiere Daten aus Tabelle dhcourse-stage.users: ~12 rows (ungefähr)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `university_id`, `university`, `email`, `password`, `email_verified`, `active`, `approved`, `is_admin`, `user_admin`, `last_login`, `password_token`, `email_token`, `approval_token`, `new_email`, `password_token_expires`, `email_token_expires`, `approval_token_expires`, `last_name`, `first_name`, `academic_title`, `telephone`, `authority`, `created`, `modified`) VALUES
	(1, 8, NULL, 'b.safradin@gmail.com', '8a8b603b0be1321a5bfe79e04e191b7e8baf1b3b', 1, 1, 1, 1, 1, '2014-08-22 17:59:41', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Safradin', 'Barbara', '', '0031652394270', NULL, '2014-07-24 12:49:03', '2014-08-22 17:59:41'),
	(2, 20, NULL, 'hendrik.schmeer@yahoo.de', 'd703561752326f8d191f045b3e4f33555a273f5a', 1, 1, 1, 1, 1, '2014-09-01 14:59:56', NULL, '187500tmjr7qlnqx', NULL, 'hschmeer@smail.uni-koeln.de', NULL, '2014-08-17 13:11:40', NULL, 'Schmeer', 'Hendrik', '', '004915774090678', NULL, NULL, '2014-09-01 14:59:56'),
	(3, NULL, NULL, 'fien.danniau@ugent.be', NULL, 1, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Diannau', 'Fien', NULL, NULL, NULL, NULL, NULL),
	(15, 1, NULL, 'scagliola@eshcc.eur.nl', NULL, 1, 1, 1, 1, 0, NULL, '037029yt4yyrg2uw', NULL, NULL, NULL, '2014-08-15 19:23:49', NULL, NULL, 'Scagliola', 'Stef', 'Dr. ', '', '', '2014-08-14 19:23:49', '2014-08-14 19:23:49'),
	(16, 20, NULL, 'zoe.schubert@uni-koeln.de', '92faa4a37b26619870c6138de487021c6782e868', 1, 1, 1, 1, 0, '2014-09-02 14:59:38', '', NULL, NULL, NULL, NULL, NULL, NULL, 'Schubert', 'Zoe', '', '', '', '2014-08-14 19:24:30', '2014-09-02 14:59:38'),
	(18, 8, NULL, 'J.deKruif@uu.nl', NULL, 1, 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'de Kruif', 'José', 'Dr.', '0031302537867', NULL, NULL, NULL),
	(20, 1, NULL, 'menchentrevino@eshcc.eur.nl', NULL, 1, 1, 1, 0, 0, NULL, '267123bf2ws99ee1', NULL, NULL, NULL, '2014-08-18 11:18:43', NULL, NULL, 'Menchen Trevino', 'Ericka', 'Dr.', '+31 408 8627', '* for the course Digital research methods', '2014-08-17 11:18:43', '2014-08-17 11:20:59'),
	(21, 8, NULL, 'A.S.Lehmann@uu.nl', NULL, 1, 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lehmann', 'Ann-Sophie', 'Dr.', NULL, NULL, NULL, NULL),
	(23, 20, NULL, 'manfred.thaller@uni-koeln.de', '47e4f42701b485530ea29bd5cc1cad204a356d9b', 1, 1, 1, 1, 0, '2014-08-26 10:25:45', '', NULL, NULL, NULL, NULL, NULL, NULL, 'Thaller', 'Manfred', 'Professor', '00492214707736', 'Project Coordinator', '2014-08-20 12:09:05', '2014-08-26 10:25:45'),
	(25, 4, NULL, 'malvina.nissim@unibo.it', NULL, 1, 1, 1, 0, 0, NULL, '653803jnywaiq9ks', NULL, NULL, NULL, '2014-08-22 22:43:23', NULL, NULL, 'Nissim', 'Malvina', '', '', '', '2014-08-21 22:43:23', '2014-08-21 22:43:23'),
	(26, 46, NULL, 'malte.rehbein@uni-passau.de', NULL, 1, 1, 1, 0, 0, NULL, '1328483v0v3avsn5', NULL, NULL, NULL, '2014-08-28 11:47:28', NULL, NULL, 'Rehbein', 'Malte', 'Professor Dr.', '', '', '2014-08-27 11:47:28', '2014-08-27 11:47:28'),
	(27, 8, NULL, 'A.J.vanHessen@utwente.nl', 'fbd6fb437ccde394e054e306791c428825cd0816', 1, 1, 1, 1, 0, '2014-08-29 15:50:02', '', NULL, NULL, NULL, NULL, NULL, NULL, 'van Hessen', 'Arjan', '', '', '', '2014-08-29 15:46:15', '2014-08-29 15:50:02');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
