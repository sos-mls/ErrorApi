-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema ErrorAPIDB
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `ErrorAPIDB` ;

-- -----------------------------------------------------
-- Schema ErrorAPIDB
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ErrorAPIDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;
USE `ErrorAPIDB` ;

-- -----------------------------------------------------
-- Table `ErrorAPIDB`.`tbl_error`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ErrorAPIDB`.`tbl_error` ;

CREATE TABLE IF NOT EXISTS `ErrorAPIDB`.`tbl_error` (
  `error_id` INT NOT NULL AUTO_INCREMENT,
  `error_hash_id` VARCHAR(256) NOT NULL,
  `information` TEXT(65535) NOT NULL,
  `is_solved` TINYINT(1) NOT NULL DEFAULT 0,
  `error_count` INT NULL DEFAULT 0,
  `user_count` INT NULL DEFAULT 0,
  `last_occurrance_at` DATETIME NULL,
  `last_email_at` DATETIME NULL,
  `created_at` DATETIME NULL,
  PRIMARY KEY (`error_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ErrorAPIDB`.`tbl_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ErrorAPIDB`.`tbl_user` ;

CREATE TABLE IF NOT EXISTS `ErrorAPIDB`.`tbl_user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `user_hash_id` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`user_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ErrorAPIDB`.`tbl_user_has_error`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ErrorAPIDB`.`tbl_user_has_error` ;

CREATE TABLE IF NOT EXISTS `ErrorAPIDB`.`tbl_user_has_error` (
  `user_has_error_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `error_id` INT NOT NULL,
  PRIMARY KEY (`user_has_error_id`),
  INDEX `fk_tbl_user_has_error_tbl_user_idx` (`user_id` ASC),
  INDEX `fk_tbl_user_has_error_tbl_error1_idx` (`error_id` ASC),
  CONSTRAINT `fk_tbl_user_has_error_tbl_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `ErrorAPIDB`.`tbl_user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_user_has_error_tbl_error1`
    FOREIGN KEY (`error_id`)
    REFERENCES `ErrorAPIDB`.`tbl_error` (`error_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
