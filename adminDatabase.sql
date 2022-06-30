-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 30 jun 2022 om 20:26
-- Serverversie: 10.4.24-MariaDB
-- PHP-versie: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testen`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `admin`
--

CREATE TABLE `admin` (
  `admin_ID` int(11) NOT NULL,
  `nameAdmin` text NOT NULL,
  `Password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `admin`
--

INSERT INTO `admin` (`admin_ID`, `nameAdmin`, `Password`) VALUES
(7, 'Bram', '$2y$10$y0k7Cvay6k3vcdDXhiiqKeIXRG67T3ym5S1ROHSPUpR7CrlIDO3p2'),
(8, 'root', '$2y$10$IhICarpzZvsACCrwfOmPf.kcDU1MYNZ/WZRaoylrAmc567tDoSDBa'),
(10, 'test', '$2y$10$YQVkSxI6oX2E979uuECJuO1BTdEod3JREGxgvpsqWh1g7p5RWNOMK');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sponsers`
--

CREATE TABLE `sponsers` (
  `sponser_ID` int(10) NOT NULL,
  `Sponser_Naam` text NOT NULL,
  `Sponser_Adres` varchar(20) NOT NULL,
  `Sponser_Locatie` varchar(20) NOT NULL,
  `Sponser_beschrijving` varchar(256) NOT NULL,
  `Sponser_tier` text NOT NULL,
  `SponserFoto` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `User_ID` int(10) NOT NULL,
  `naam` text NOT NULL,
  `password` text NOT NULL,
  `gamer_tag` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`User_ID`, `naam`, `password`, `gamer_tag`) VALUES
(1, 'Bram', '$2y$10$ASsjTaSYwuI0I5G4foriOutdxWeWKsAP6l.xsU84UavKVzyRAtP/W', 'DevilskeyOrgin');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_ID`);

--
-- Indexen voor tabel `sponsers`
--
ALTER TABLE `sponsers`
  ADD PRIMARY KEY (`sponser_ID`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `sponsers`
--
ALTER TABLE `sponsers`
  MODIFY `sponser_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
