-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  sam. 13 nov. 2021 à 21:28
-- Version du serveur :  10.4.8-MariaDB
-- Version de PHP :  7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `gestion_conges`
--

-- --------------------------------------------------------

--
-- Structure de la table `conge`
--

CREATE TABLE `conge` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `contenu` text NOT NULL,
  `date_depart` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `valide` int(11) NOT NULL,
  `motif` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `conge`
--

INSERT INTO `conge` (`id`, `user_id`, `titre`, `contenu`, `date_depart`, `date_fin`, `valide`, `motif`, `created_at`, `updated_at`) VALUES
(1, 6, 'gggggg', 'gggggggggg', '2021-11-18 00:00:00', '2021-11-18 00:00:00', 2, 'yep', '2021-11-13 11:27:50', '2021-11-13 20:22:28'),
(7, 17, 'xxxxxxxxx', 'xxxxxxxxxxx', '2021-12-31 00:00:00', '2021-12-31 00:00:00', 1, 'merci', '2021-11-13 16:15:49', '2021-11-13 20:17:49'),
(8, 3, 'tttttttt', 'uuuuuuuuuuuuu', '2021-12-31 00:00:00', '2021-12-31 00:00:00', 1, 'chehe', '2021-11-13 20:56:12', '2021-11-13 21:02:13'),
(9, 6, 'ddddddddd', 'ddddddddd', '2021-12-31 00:00:00', '2021-12-11 00:00:00', 0, '', '2021-11-13 21:07:21', '2021-11-13 21:07:21'),
(10, 6, 'yyyyyyyy', 'ffffffffff', '2021-12-31 00:00:00', '2021-12-11 00:00:00', 0, '', '2021-11-13 21:08:34', '2021-11-13 21:08:34'),
(11, 6, 'fffffffffff', 'ffffffffffffff', '2021-12-31 00:00:00', '2021-12-11 00:00:00', 0, '', '2021-11-13 21:11:21', '2021-11-13 21:11:21'),
(12, 6, 'tttt', 'ffffffff', '2021-12-30 00:00:00', '2021-12-31 00:00:00', 1, '', '2021-11-13 21:14:41', '2021-11-13 21:17:09');

-- --------------------------------------------------------

--
-- Structure de la table `gestionnaire`
--

CREATE TABLE `gestionnaire` (
  `id` int(11) NOT NULL,
  `responsable` int(11) NOT NULL,
  `effectif` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `gestionnaire`
--

INSERT INTO `gestionnaire` (`id`, `responsable`, `effectif`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(2, 1, 4, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(3, 1, 5, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(4, 3, 6, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(5, 3, 7, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(6, 3, 8, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(7, 3, 9, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(8, 4, 10, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(9, 4, 11, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(10, 4, 12, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(11, 4, 13, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(12, 5, 14, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(13, 5, 15, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(14, 5, 16, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(15, 5, 17, '2021-11-13 00:00:00', '2021-11-13 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `role` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `role`, `created_at`, `updated_at`) VALUES
(1, 'responsable', '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(2, 'manager', '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(3, 'salarié', '2021-11-10 10:00:00', '2021-11-10 10:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `prenom` varchar(150) NOT NULL,
  `num_id` varchar(5) NOT NULL,
  `email` varchar(150) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`, `num_id`, `email`, `adresse`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 'Zuckerberg', 'Mark', '12345', 'test@test.te', '367 Addison Avenue à Palo Alto', 1, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(3, 'Jobs', 'Steve', '00045', 'test@test.te', '367 Addison Avenue à Palo Alto', 2, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(4, 'Gates', 'Bill', '00045', 'test@test.te', '367 Addison Avenue à Palo Alto', 2, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(5, 'Clinton', 'Bill', '00045', 'test@test.te', '367 Addison Avenue à Palo Alto', 2, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(6, 'Stallone', 'Sylvester', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(7, 'Van damme', 'Jean Claude', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(8, 'Turing', 'Alan', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(9, 'Trump', 'Donald', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(10, 'Schwarzenegger', 'Arnold', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(11, 'Monro', 'Maryline', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(12, 'Andérson', 'Pamela', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-10 10:00:00', '2021-11-10 10:00:00'),
(13, 'Craig', 'Daniel', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(14, 'Jordan', 'Michael', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(15, 'Carey', 'Mariah', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(16, 'Smith', 'Will', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-13 00:00:00', '2021-11-13 00:00:00'),
(17, 'Willis', 'Bruce', '12300', 'test@test.te', '367 Addison Avenue à Palo Alto', 3, '2021-11-13 00:00:00', '2021-11-13 00:00:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `conge`
--
ALTER TABLE `conge`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gestionnaire`
--
ALTER TABLE `gestionnaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `conge`
--
ALTER TABLE `conge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `gestionnaire`
--
ALTER TABLE `gestionnaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
