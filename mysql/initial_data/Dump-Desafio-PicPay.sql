-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema desafio-picpay
-- -----------------------------------------------------
-- Desafio Backend

-- -----------------------------------------------------
-- Schema desafio-picpay
--
-- Desafio Backend
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `desafio-picpay` DEFAULT CHARACTER SET utf8 ;
USE `desafio-picpay` ;

-- -----------------------------------------------------
-- Table `desafio-picpay`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `desafio-picpay`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(60) NOT NULL,
  `cpf` VARCHAR(11) NOT NULL,
  `phone_number` VARCHAR(15) NOT NULL,
  `email` VARCHAR(60) NOT NULL,
  `account_type` VARCHAR(10) NULL DEFAULT 'CONSUMER',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `cpf_UNIQUE` (`cpf` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `desafio-picpay`.`seller`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `desafio-picpay`.`seller` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `social_name` VARCHAR(60) NOT NULL,
  `fantasy_name` VARCHAR(60) NOT NULL,
  `cnpj` VARCHAR(25) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  `users_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_sellers_users_idx` (`users_id` ASC),
  UNIQUE INDEX `cnpj_UNIQUE` (`cnpj` ASC),
  CONSTRAINT `fk_sellers_users`
    FOREIGN KEY (`users_id`)
    REFERENCES `desafio-picpay`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `desafio-picpay`.`transactions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `desafio-picpay`.`transactions` (
  `id` INT NOT NULL,
  `transaction_date` DATETIME NOT NULL,
  `value` FLOAT NOT NULL,
  `users_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_transactions_users1_idx` (`users_id` ASC),
  CONSTRAINT `fk_transactions_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `desafio-picpay`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `desafio-picpay`.`authentication`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `desafio-picpay`.`authentication` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(30) NOT NULL,
  `password` VARCHAR(60) NOT NULL,
  `active` VARCHAR(10) NOT NULL DEFAULT 'ENABLE',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  `users_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_authentication_users1_idx` (`users_id` ASC),
  CONSTRAINT `fk_authentication_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `desafio-picpay`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
