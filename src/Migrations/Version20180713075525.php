<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180713075525 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS `movie_category`;
                            DROP TABLE IF EXISTS `profile`;
                            DROP TABLE IF EXISTS `watchlist_movie`;
                            DROP TABLE IF EXISTS `watchlist`;
                            DROP TABLE IF EXISTS `user`;
                            DROP TABLE IF EXISTS `movie`;
                            DROP TABLE IF EXISTS `history`');

        $this->addSql('DROP TABLE IF EXISTS `category`;
                            CREATE TABLE IF NOT EXISTS `category` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $this->addSql("INSERT INTO `category` (`id`, `libelle`) VALUES
                            (1, 'Comedy'),
                            (2, 'Horror'),
                            (3, 'Fantasy'),
                            (4, 'Superhero'),
                            (5, 'Animation'),
                            (6, 'Action'),
                            (7, 'Romance'),
                            (8, 'Science-Fiction'),
                            (9, 'Adventure');");

        $this->addSql('CREATE TABLE IF NOT EXISTS `history` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `date` datetime NOT NULL,
                              `note` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $this->addSql('CREATE TABLE IF NOT EXISTS `movie` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `director` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `release_date` datetime NOT NULL,
                              `duration` int(11) NOT NULL,
                              `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `synopsis` varchar(455) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $this->addSql("INSERT INTO `movie` (`id`, `title`, `director`, `release_date`, `duration`, `picture`, `synopsis`) VALUES
                            (1, 'Paranoïa', 'Steven Soderbergh', '2018-03-21 00:00:00', 120, 'f557038cefea7fad86429795d40d5eb9.jpeg', 'Paranoïa est un film d\'horreur américain réalisé par Steven Soderbergh, sorti en 2018. En 2018, Il est présenté hors-compétition au festival international du film de Berlin.'),
                            (2, 'Jurassic World', 'Juan Antonio Bayona', '2018-06-06 00:00:00', 150, '544cb3858c4485aaceab0500541e48fa.jpeg', 'Jurassic World: Fallen Kingdom ou Monde jurassique : Le royaume déchu au Québec est un film de science-fiction américain'),
                            (3, 'Inception', 'Christopher Nolan', '2018-07-19 00:00:00', 85, '1616339a14e47c1979d8ad19890dfe35.jpeg', 'Dom Cobb est un voleur expérimenté dans l\'art périlleux de `l\'extraction\' : sa spécialité consiste à s\'approprier les secrets les plus précieux d\'un individu, enfouis au plus profond de son subconscient, pendant qu\'il rêve et que son esprit est particulièrement vulnérable.'),
                            (4, 'Interstellar', 'Christopher Nolan', '2018-07-28 00:00:00', 70, 'f48e01a461f242ee4a80019176684d98.jpeg', 'Dans un futur proche, la Terre est de moins en moins accueillante pour l\'humanité qui connaît une grave crise alimentaire.'),
                            (5, 'The Dark Knight Rises', 'Christopher Nolan', '2018-07-19 00:00:00', 90, '4477ef1cb6d665de0caf394731285aae.jpeg', 'Il y a huit ans, Batman a disparu dans la nuit : lui qui était un héros est alors devenu un fugitif. L\'arrivée d\'une féline et fourbe cambrioleuse'),
                            (6, 'Avatar', 'James Cameron', '2018-07-13 00:00:00', 90, 'cb14613912e9a8354d957cc02debe5ec.jpeg', 'Malgré sa paralysie, Jake Sully, un ancien marine immobilisé dans un fauteuil roulant, est resté un combattant au plus profond de son être.'),
                            (7, 'Alien', 'Ridley Scott', '2018-08-16 00:00:00', 80, '25f7bb84b144acb511db52caa90ba1cb.jpeg', 'Durant le voyage de retour d un immense cargo spatial en mission commerciale de routine, ses passagers, cinq hommes et deux femmes plongés en hibernation, sont tirés de leur léthargie dix mois plus tôt que prévu par Mother, l ordinateur de bord.'),
                            (8, 'Fight Club', 'David Fincher', '2018-07-09 00:00:00', 110, 'd5a2a93161236a1f05b517e8842b2d7a.jpeg', 'Jack est un jeune expert en assurance insomniaque, désillusionné par sa vie personnelle et professionnelle. Lorsque son médecin lui conseille de suivre une thérapie afin de relativiser son mal-être, il rencontre dans un groupe d\'entraide Marla avec qui il parvient à trouver un équilibre.'),
                            (9, 'Inglorious Basterds', 'Quentin Tarantino', '2018-07-22 00:00:00', 80, 'cb44d682df156b62dbb036004b10a068.jpeg', 'Dans la France occupée de 1940, Shosanna Dreyfus assiste à l\'exécution de sa famille tombée entre les mains du colonel nazi Hans Landa. Shosanna s\'échappe de justesse et s\'enfuit à Paris où elle se construit une nouvelle identité en devenant exploitante d\'une salle de cinéma.'),
                            (10, 'Le bon, la Brute et le Truand', 'Sergio Leone', '2018-07-16 00:00:00', 90, '07501d0079fce6bf83012ad4a511b873.jpeg', 'Alors que la guerre de Sécession fait rage aux Etats-Unis, trois bandits n\'ont qu\'une préoccupation : l\'argent. Joe livre régulièrement à la justice son copain Tuco, dont la tête est mise à prix, puis empoche la prime et délivre son complice. Sentenza abat, avec un égal sang-froid, l\'homme qu\'il devait tuer moyennant récompense, et celui qui l\'avait mandaté pour cette exécution.'),
                            (11, 'Scarface', 'Brian de palma', '2018-07-19 00:00:00', 90, '4e7695a76bbf8b4ec20a78eb9d20af35.jpeg', 'En mai 1980, Fidel Castro autorise les opposants qui le souhaitent à quitter Cuba. Il en profite pour envoyer vers les États-Unis les malfrats devenus indésirables dans l\'île. C\'est ainsi que Tony Montana, un tueur mégalomane, se met à vivre son rêve américain. En deux temps, trois mouvements, il devient le bras droit de Frank Lopez, un magnat de la drogue, qu\'il ne tarde pas à éliminer.'),
                            (12, 'Shutter Island', 'Martin Scorsese', '2018-07-10 00:00:00', 95, '885e6b4cc2e093e040f479bfcb22b361.jpeg', 'L\'invraisemblable évasion d\'une meurtrière rusée amène le marshal Teddy Daniels et son nouveau partenaire à l\'hôpital Ashecliffe, un asile psychiatrique situé sur une île lointaine et balayée par les vents. Il apparaît que la femme a disparu d\'une chambre fermée à clef et il y a des indices d\'actes effroyables commis au sein de l\'hôpital'),
                            (13, 'Orange mécanique', 'Stanley Kubrick', '2018-07-12 00:00:00', 80, '3c28cbd7a805bf34798ae3d6cb13a878.jpeg', 'Dans une Angleterre futuriste et inhumaine, un groupe d\'adolescents se déchaînent chaque nuit, frappant et violant d\'innocentes victimes. Alex, le leader du gang est arrêté et condamné à 14 ans de prison. Il accepte de se soumettre à une thérapie de choc destinée à faire reculer la criminalité.'),
                            (14, 'Pulp Fiction', 'Quentin Tarantino', '2018-07-07 00:00:00', 65, 'af1c3c350eff745e1eff766a75119bb5.jpeg', 'L\'odyssée sanglante et burlesque de petits malfrats dans la jungle de Hollywood à travers trois histoires qui s\'entremêlent.'),
                            (15, 'Matrix', 'Lana Wachowski', '2018-07-30 00:00:00', 110, '337e5af2d21196310518322bdd3d78b0.jpeg', 'Programmeur anonyme dans un service administratif le jour, Thomas Anderson devient Neo la nuit venue. Sous ce pseudonyme, il est l\'un des pirates les plus recherchés du cyber-espace. A cheval entre deux mondes, Neo est assailli par d\'étranges songes et des messages cryptés provenant d\'un certain Morpheus.'),
                            (16, 'La ligne verte', 'Frank Darabont', '2018-07-12 00:00:00', 110, '7395f849a0754a676006f35ec52eb458.jpeg', 'Paul Edgecomb, pensionnaire centenaire d\'une maison de retraite, est hanté par ses souvenirs. Gardien-chef du pénitencier de Cold Mountain, en 1935, en Louisiane, il était chargé de veiller au bon déroulement des exécutions capitales au bloc E (la ligne verte) en s\'efforçant d\'adoucir les derniers moments des condamnés.'),
                            (17, 'Forrest Gump', 'Robert Zemeckis', '2018-07-18 00:00:00', 120, 'c34f0c01e8da8d73f03b10cac512d4b5.jpeg', 'Au fil des différents interlocuteurs qui viennent s\'asseoir tour à tour à côté de lui sur un banc, Forrest Gump raconte la fabuleuse histoire de sa vie. Sa vie est à l\'image d\'une plume qui se laisse porter par le vent, tout comme Forrest se laisse porter par les événements qu\'il traverse dans l\'Amérique de la seconde moitié du 20e siècle.'),
                            (18, 'La liste de Schindler', 'Steven Spilberg', '2018-07-09 00:00:00', 80, '214a93369d46931ead811793bfa4304f.jpeg', 'Les Allemands, victorieux de la Pologne, regroupent les Juifs dans des ghettos dans le but de s\'en servir comme main d\'oeuvre bon marché. Oskar Schindler, industriel et bon vivant, rachète pour une bouchée de pain une fabrique d\'ustensiles de cuisine.'),
                            (19, 'Oblivion', 'Joseph Kosinki', '2018-07-31 00:00:00', 75, 'a967ec262517fe999ec4cbf67ba621be.jpeg', 'En 2077, Jack Harper fait partie d\'une opération d\'extraction des dernières ressources de la terre. Sa mission touche à sa fin. Dans à peine deux semaines, il rejoindra le reste des survivants dans une colonie spatiale à des milliers de kilomètres de cette planète dévastée qu\'il considère néanmoins comme son chez-lui.'),
                            (20, 'Le Seigneur des anneaux: La Communauté de l\'anneau', 'Peter Jackson', '2018-07-13 00:00:00', 150, 'd673cdd82aee6d995b82acb1cf5ae2df.jpeg', 'Un jeune et timide `Hobbit\', Frodon Sacquet, hérite d\'un anneau magique. Bien loin d\'être une simple babiole, il s\'agit d\'un instrument de pouvoir absolu qui permettrait à Sauron, le `Seigneur des ténèbres\', de régner sur la `Terre du Milieu\' et de réduire en esclavage ses peuples. Frodon doit parvenir jusqu\'à la `Crevasse du Destin\' pour détruire l\'anneau.'),
                            (21, 'Harry Potter à l\'ecole des sorciers', 'Chris Columbus', '2018-07-16 00:00:00', 90, '85cf983b9e241414b9ee5fd40bc1f82f.jpeg', 'Orphelin, le jeune Harry Potter peut enfin quitter ses tyranniques oncle et tante Dursley lorsqu\'un curieux messager lui révèle qu\'il est un sorcier. À 11 ans, Harry va enfin pouvoir intégrer la légendaire école de sorcellerie de Poudlard, y trouver une famille digne de ce nom et des amis, développer ses dons, et préparer son glorieux avenir.'),
                            (22, 'Ça', 'Andrés Muschietti', '2018-07-03 00:00:00', 80, 'fc26b8b3a9645fe2efd1f82e106ce136.jpeg', 'À Derry, dans le Maine, sept gamins ayant du mal à s\'intégrer se sont regroupés au sein du Club des Ratés. Rejetés par leurs camarades, ils sont les cibles favorites des gros durs de l\'école. Ils ont aussi en commun d\'avoir éprouvé leur plus grande terreur face à un terrible prédateur métamorphe qu\'ils appellent Ça.'),
                            (23, 'Deadpool', 'Tim Miller', '2018-07-17 00:00:00', 100, '9dc7e43d32b05e47ff8fc21232826b41.jpeg', 'Wade Wilson, un ancien militaire des forces spéciales, est devenu mercenaire. Après avoir subi une expérimentation hors-norme qui va accélérer ses pouvoirs de guérison, il va devenir Deadpool. Armé de ses nouvelles capacités et d\'un humour noir survolté, il va traquer l\'homme qui a bien failli anéantir sa vie.'),
                            (24, 'Get Out', 'Jordan Peele', '2018-07-03 00:00:00', 110, '1e2a338aedc5328d0153882774f6cd4a.jpeg', 'Maintenant que Chris et sa copine Rose vont rencontrer leurs parents respectifs, elle l\'invite dans la résidence secondaire de sa famille pour un week-end. D\'abord Chris comprend que le comportement un peu étrange de la famille de Rose est lié au fait qu\'il est noir et qu\'elle est blanche. Cependant, il découvre que la vérité est bien plus dérangeante.'),
                            (25, 'Cinquante nuances de Grey', 'Sam Taylor-Johnson', '2018-07-05 00:00:00', 130, '5768606ac157ed14d7b0d62c69ec3b56.jpeg', 'Étudiante en littérature anglaise, Anastasia Steele se rend à Seattle dans les bureaux de Christian Grey, jeune homme d\'affaires déjà à la tête d\'un empire de télécom. Elle remplace sa colocataire malade, apprentie journaliste qui devait l\'interviewer pour le magazine de l\'université. Grey tombe sous le charme de cette jeune femme réservée. Au jeu de la séduction, les forces semblent bien distribuées.'),
                            (26, 'Dunkerque', 'Christopher Nolan', '2018-07-10 00:00:00', 120, '94cb370735fc1765935aecfefec8db97.jpeg', 'Au début de la Seconde Guerre mondiale, en mai 1940, des troupes alliées se retrouvent encerclées par les troupes allemandes à Dunkerque, en France. L\'Opération Dynamo est mise en place pour évacuer le Corps expéditionnaire britannique (CEB) vers l\'Angleterre.'),
                            (27, 'Le dernier Samouraï', 'Edward Zwick', '2018-07-01 00:00:00', 110, '836d7fb72e591f97662674a00ad7becb.jpeg', 'En 1876, Nathan Algren, un vétéran de l\'armée américaine, part pour le Japon afin d\'assister l\'armée impériale qui cherche à écraser une révolte de Samouraï, en guerre contre l\'occidentalisation. Capturé par les rebelles impressionnés par son courage, Nathan change de camp et décide de rejoindre leur combat.'),
                            (29, 'Gladiator', 'Riddley Scott', '2017-05-06 00:00:00', 110, 'e5742fed7e09b14a04470c37b1343728.jpeg', 'Le général romain Maximus est le plus fidèle soutien de l\'empereur Marc Aurèle, qu\'il a conduit de victoire en victoire. Jaloux du prestige de Maximus, et plus encore de l\'amour que lui voue l\'empereur, le fils de Marc Aurèle, Commode, s\'arroge brutalement le pouvoir, puis ordonne l\'arrestation du général et son exécution.');
                            ");

        $this->addSql('DROP TABLE IF EXISTS `movie_category`;
                            CREATE TABLE IF NOT EXISTS `movie_category` (
                              `movie_id` int(11) NOT NULL,
                              `category_id` int(11) NOT NULL,
                              PRIMARY KEY (`movie_id`,`category_id`),
                              KEY `IDX_DABA824C8F93B6FC` (`movie_id`),
                              KEY `IDX_DABA824C12469DE2` (`category_id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $this->addSql('INSERT INTO `movie_category` (`movie_id`, `category_id`) VALUES
                            (2, 6),
                            (3, 8),
                            (4, 8),
                            (5, 4),
                            (6, 3),
                            (6, 8),
                            (7, 2),
                            (7, 8),
                            (9, 6),
                            (10, 9),
                            (11, 9),
                            (12, 9),
                            (13, 9),
                            (14, 9),
                            (15, 8),
                            (16, 9),
                            (17, 9),
                            (18, 9),
                            (19, 8),
                            (20, 3),
                            (21, 3),
                            (23, 4),
                            (24, 2),
                            (25, 7),
                            (26, 6),
                            (27, 9);');

        $this->addSql('DROP TABLE IF EXISTS `profile`;
                            CREATE TABLE IF NOT EXISTS `profile` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `firstname` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `lastname` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `picture` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $this->addSql('DROP TABLE IF EXISTS `profile`;
                            CREATE TABLE IF NOT EXISTS `profile` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `firstname` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `lastname` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `picture` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $this->addSql("DROP TABLE IF EXISTS `user`;
                            CREATE TABLE IF NOT EXISTS `user` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `profile_id` int(11) DEFAULT NULL,
                              `username` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `password` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `roles` json DEFAULT NULL COMMENT '(DC2Type:json_array)',
                              PRIMARY KEY (`id`),
                              UNIQUE KEY `UNIQ_8D93D649CCFA12B8` (`profile_id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                            
                            --
                            -- Déchargement des données de la table `user`
                            --
                            
                            INSERT INTO `user` (`id`, `profile_id`, `username`, `email`, `password`, `roles`) VALUES
                            (1, 2, 'alex', 'alex@gmail.com', '$2y$13$qlckaO8KH1LWnNhPDwq6BukBJms3HqRcfYwegpTejB8fKDd06smoS', '[\\ROLE_ADMIN\\']'');                            
                            
                            --
                            -- Contraintes pour la table `user`
                            --
                            ALTER TABLE `user`
                              ADD CONSTRAINT `FK_8D93D649CCFA12B8` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`)");

        $this->addSql('ALTER TABLE `movie_category`
                              ADD CONSTRAINT `FK_DABA824C12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
                              ADD CONSTRAINT `FK_DABA824C8F93B6FC` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`) ON DELETE CASCADE;
                            ALTER TABLE `user`
                              ADD CONSTRAINT `FK_8D93D6491E058452` FOREIGN KEY (`history_id`) REFERENCES `history` (`id`),
                              ADD CONSTRAINT `FK_8D93D649CCFA12B8` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`);
                            COMMIT;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
