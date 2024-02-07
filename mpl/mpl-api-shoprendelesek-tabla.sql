-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3306
-- Létrehozás ideje: 2022. Okt 01. 07:10
-- Kiszolgáló verziója: 10.3.36-MariaDB-log-cll-lve
-- PHP verzió: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `isdopdan_pekar`
--

--
-- Tábla szerkezet ehhez a táblához `pek_shop_rendelesek`
--

CREATE TABLE `pek_shop_rendelesek` (
  `m_id` int(20) NOT NULL,
  `paymentazon` text NOT NULL,
  `rendelesazon` text NOT NULL,
  `megrendelonev` text NOT NULL,
  `megrendeloemail` text NOT NULL,
  `megrendelotelszam` text NOT NULL,
  `megrendeloszallcim` text NOT NULL,
  `megrendeloszlanev` text NOT NULL,
  `megrendeloszlaado` text NOT NULL,
  `megrendeloszlairszam` text NOT NULL,
  `megrendeloszlavaros` text NOT NULL,
  `megrendeloszlacim` text NOT NULL,
  `szallitasimod` text NOT NULL,
  `fizetesimod` text NOT NULL,
  `fizetendo` int(10) NOT NULL,
  `kupon` text NOT NULL,
  `szallitasft` int(10) NOT NULL,
  `termekek` text NOT NULL,
  `datum` date DEFAULT NULL,
  `duma` text NOT NULL,
  `fizetve` varchar(20) NOT NULL DEFAULT '0',
  `ragszam` text NOT NULL,
  `ragpdf` longtext NOT NULL,
  `logzar` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `pek_shop_rendelesek`
--

INSERT INTO `pek_shop_rendelesek` (`m_id`, `paymentazon`, `rendelesazon`, `megrendelonev`, `megrendeloemail`, `megrendelotelszam`, `megrendeloszallcim`, `megrendeloszlanev`, `megrendeloszlaado`, `megrendeloszlairszam`, `megrendeloszlavaros`, `megrendeloszlacim`, `szallitasimod`, `fizetesimod`, `fizetendo`, `kupon`, `szallitasft`, `termekek`, `datum`, `duma`, `fizetve`, `ragszam`, `ragpdf`, `logzar`) VALUES
(1, 'EV9Um6fHKl43HW', 'T0913095218', 'Monostori Hortenzia', 'zius73@gmail.com', '0620-464-0080', '5000. Szolnok, SzivÃ¡rvÃ¡ny utca 39.', '', '', '5000.', 'Szolnok,', 'SzivÃ¡rvÃ¡ny utca 39.  ', 'SzemÃ©lyes Ã¡tvÃ©tel Ã¼zletben', 'utÃ¡nvÃ©t', 20508, '', 0, '/1 db|B-CALM KORREKCIÃ“S HIDRATÃLÃ“ KRÃ‰M (50ml)|20.508 Ft', '2022-09-13', '', '1', '', '', '');

-- --------------------------------------------------------

--
-- A tábla indexei `pek_shop_rendelesek`
--
ALTER TABLE `pek_shop_rendelesek`
  ADD PRIMARY KEY (`m_id`);
  
  --
-- AUTO_INCREMENT a táblához `pek_shop_rendelesek`
--
ALTER TABLE `pek_shop_rendelesek`
  MODIFY `m_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
