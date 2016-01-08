-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 11, 2013 at 08:16 AM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET time_zone = "+00:00";

--
-- Database: `EclipsePhaseData`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai`
--

DROP TABLE IF EXISTS `ai`;
CREATE TABLE `ai` (
  `name` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `cost` smallint(6) NOT NULL,
  `unique` varchar(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `AiAptitude`
--

DROP TABLE IF EXISTS `AiAptitude`;
CREATE TABLE `AiAptitude` (
  `ai` varchar(100) NOT NULL,
  `aptitude` varchar(100) NOT NULL,
  `value` smallint(6) NOT NULL,
  PRIMARY KEY (`ai`,`aptitude`)
);

-- --------------------------------------------------------

--
-- Table structure for table `AiSkill`
--

DROP TABLE IF EXISTS `AiSkill`;
CREATE TABLE `AiSkill` (
  `ai` varchar(100) NOT NULL,
  `skillName` varchar(100) NOT NULL,
  `skillPrefix` varchar(100) NOT NULL DEFAULT '',
  `value` smallint(6) NOT NULL,
  PRIMARY KEY (`ai`,`skillName`,`skillPrefix`)
);

-- --------------------------------------------------------

--
-- Table structure for table `AiStat`
--

DROP TABLE IF EXISTS `AiStat`;
CREATE TABLE `AiStat` (
  `ai` varchar(100) NOT NULL,
  `stat` varchar(100) NOT NULL,
  `value` smallint(6) NOT NULL,
  PRIMARY KEY (`ai`,`stat`)
);

-- --------------------------------------------------------

--
-- Table structure for table `aptitude`
--

DROP TABLE IF EXISTS `aptitude`;
CREATE TABLE `aptitude` (
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `abbreviation` varchar(3) NOT NULL,
  PRIMARY KEY (`name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `AtomBook`
--

DROP TABLE IF EXISTS `AtomBook`;
CREATE TABLE `AtomBook` (
  `name` varchar(100) NOT NULL,
  `book` varchar(100) NOT NULL,
  PRIMARY KEY (`name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `AtomPage`
--

DROP TABLE IF EXISTS `AtomPage`;
CREATE TABLE `AtomPage` (
  `name` varchar(100) NOT NULL,
  `page` varchar(100) NOT NULL,
  PRIMARY KEY (`name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `background`
--

DROP TABLE IF EXISTS `background`;
CREATE TABLE `background` (
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(3) NOT NULL,
  PRIMARY KEY (`name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `BackgroundBonusMalus`
--

DROP TABLE IF EXISTS `BackgroundBonusMalus`;
CREATE TABLE `BackgroundBonusMalus` (
  `background` varchar(100) NOT NULL,
  `bonusMalus` varchar(100) NOT NULL,
  `occurrence` smallint(6) NOT NULL,
  PRIMARY KEY (`background`,`bonusMalus`)
);

-- --------------------------------------------------------

--
-- Table structure for table `BackgroundLimitation`
--

DROP TABLE IF EXISTS `BackgroundLimitation`;
CREATE TABLE `BackgroundLimitation` (
  `background` varchar(100) NOT NULL,
  `limitationGroup` varchar(100) NOT NULL,
  PRIMARY KEY (`background`,`limitationGroup`)
);

-- --------------------------------------------------------

--
-- Table structure for table `BackgroundObligation`
--

DROP TABLE IF EXISTS `BackgroundObligation`;
CREATE TABLE `BackgroundObligation` (
  `background` varchar(100) NOT NULL,
  `obligationGroup` varchar(100) NOT NULL,
  PRIMARY KEY (`background`,`obligationGroup`)
);

-- --------------------------------------------------------

--
-- Table structure for table `BackgroundTrait`
--

DROP TABLE IF EXISTS `BackgroundTrait`;
CREATE TABLE `BackgroundTrait` (
  `background` varchar(100) NOT NULL,
  `trait` varchar(100) NOT NULL,
  PRIMARY KEY (`background`,`trait`)
);

-- --------------------------------------------------------

--
-- Table structure for table `bonusMalus`
--

DROP TABLE IF EXISTS `bonusMalus`;
CREATE TABLE `bonusMalus` (
  `name` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `type` varchar(3) NOT NULL,
  `target` varchar(60) NOT NULL,
  `value` float NOT NULL,
  `tragetForCh` varchar(20) NOT NULL,
  `typeTarget` varchar(20) NOT NULL,
  `onCost` varchar(20) NOT NULL,
  `multiOccur` varchar(20) NOT NULL,
  PRIMARY KEY (`name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `BonusMalusTypes`
--

DROP TABLE IF EXISTS `BonusMalusTypes`;
CREATE TABLE `BonusMalusTypes` (
  `bmNameMain` varchar(60) NOT NULL,
  `bmChoices` varchar(60) NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `Gear`
--

DROP TABLE IF EXISTS `Gear`;
CREATE TABLE `Gear` (
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(3) NOT NULL,
  `cost` smallint(6) NOT NULL,
  `armorKinetic` smallint(6) NOT NULL,
  `armorEnergy` smallint(6) NOT NULL,
  `degat` varchar(30) NOT NULL,
  `armorPene` smallint(6) NOT NULL,
  `JustFor` varchar(100) NOT NULL,
  `unique` varchar(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `GearBonusMalus`
--

DROP TABLE IF EXISTS `GearBonusMalus`;
CREATE TABLE `GearBonusMalus` (
  `gear` varchar(100) NOT NULL,
  `bonusMalus` varchar(100) NOT NULL,
  `occur` smallint(6) NOT NULL,
  PRIMARY KEY (`gear`,`bonusMalus`)
);

-- --------------------------------------------------------

--
-- Table structure for table `GroupName`
--

DROP TABLE IF EXISTS `GroupName`;
CREATE TABLE `GroupName` (
  `groupName` varchar(100) NOT NULL,
  `targetName` varchar(100) NOT NULL,
  PRIMARY KEY (`groupName`,`targetName`)
);

-- --------------------------------------------------------

--
-- Table structure for table `infos`
--

DROP TABLE IF EXISTS `infos`;
CREATE TABLE `infos` (
  `id` varchar(100) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `morph`
--

DROP TABLE IF EXISTS `morph`;
CREATE TABLE `morph` (
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `age` smallint(6) NOT NULL,
  `maxApptitude` smallint(6) NOT NULL,
  `durablility` smallint(6) NOT NULL,
  `cpCost` smallint(6) NOT NULL,
  `creditCost` varchar(100) NOT NULL,
  PRIMARY KEY (`name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `MorphBonusMalus`
--

DROP TABLE IF EXISTS `MorphBonusMalus`;
CREATE TABLE `MorphBonusMalus` (
  `morph` varchar(100) NOT NULL,
  `bonusMalus` varchar(100) NOT NULL,
  `occur` smallint(6) NOT NULL,
  PRIMARY KEY (`morph`,`bonusMalus`)
);

-- --------------------------------------------------------

--
-- Table structure for table `MorphGears`
--

DROP TABLE IF EXISTS `MorphGears`;
CREATE TABLE `MorphGears` (
  `morph` varchar(100) NOT NULL,
  `gear` varchar(100) NOT NULL,
  `occur` smallint(6) NOT NULL,
  PRIMARY KEY (`morph`,`gear`)
);

-- --------------------------------------------------------

--
-- Table structure for table `MorphTrait`
--

DROP TABLE IF EXISTS `MorphTrait`;
CREATE TABLE `MorphTrait` (
  `morph` varchar(100) NOT NULL,
  `trait` varchar(100) NOT NULL,
  PRIMARY KEY (`morph`,`trait`)
);

-- --------------------------------------------------------

--
-- Table structure for table `psySleight`
--

DROP TABLE IF EXISTS `psySleight`;
CREATE TABLE `psySleight` (
  `name` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `range` varchar(50) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `level` varchar(3) NOT NULL,
  `strainMod` varchar(100) NOT NULL,
  `skillNeeded` varchar(100) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `PsySleightBonusMalus`
--

DROP TABLE IF EXISTS `PsySleightBonusMalus`;
CREATE TABLE `PsySleightBonusMalus` (
  `psy` varchar(100) NOT NULL,
  `bonusmalus` varchar(100) NOT NULL,
  `occur` smallint(6) NOT NULL,
  PRIMARY KEY (`psy`,`bonusmalus`)
);

-- --------------------------------------------------------

--
-- Table structure for table `reputation`
--

DROP TABLE IF EXISTS `reputation`;
CREATE TABLE `reputation` (
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `skillPrefixes`
--

DROP TABLE IF EXISTS `skillPrefixes`;
CREATE TABLE `skillPrefixes` (
  `prefix` varchar(100) NOT NULL,
  `linkedApt` varchar(3) NOT NULL,
  `skillType` varchar(3) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`prefix`)
);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE `skills` (
  `name` varchar(60) NOT NULL,
  `desc` text,
  `linkedApt` varchar(3) NOT NULL,
  `prefix` varchar(60) NOT NULL DEFAULT '',
  `skillType` varchar(3) NOT NULL,
  `defaultable` varchar(1) NOT NULL,
  PRIMARY KEY (`name`,`prefix`)
);

-- --------------------------------------------------------

--
-- Table structure for table `stat`
--

DROP TABLE IF EXISTS `stat`;
CREATE TABLE `stat` (
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `abbreviation` varchar(3) NOT NULL,
  PRIMARY KEY (`name`)
);

-- --------------------------------------------------------

--
-- Table structure for table `TraitBonusMalus`
--

DROP TABLE IF EXISTS `TraitBonusMalus`;
CREATE TABLE `TraitBonusMalus` (
  `traitName` varchar(60) NOT NULL,
  `bonusMalusName` varchar(100) NOT NULL,
  `occur` smallint(6) NOT NULL,
  PRIMARY KEY (`traitName`,`bonusMalusName`)
);

-- --------------------------------------------------------

--
-- Table structure for table `traits`
--

DROP TABLE IF EXISTS `traits`;
CREATE TABLE `traits` (
  `name` varchar(60) NOT NULL,
  `desc` text NOT NULL,
  `side` varchar(3) DEFAULT NULL,
  `onwhat` varchar(3) DEFAULT NULL,
  `cpCost` smallint(6) DEFAULT NULL,
  `level` smallint(6) NOT NULL,
  `JustFor` varchar(30) NOT NULL DEFAULT 'EVERY',
  PRIMARY KEY (`name`)
);
