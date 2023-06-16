-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema gate
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema gate
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `gate`;
CREATE SCHEMA `gate` DEFAULT CHARACTER SET utf8 ;
USE `gate` ;

-- -----------------------------------------------------
-- Table `gate`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gate`.`usuario` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(80) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `cidade` VARCHAR(45) NOT NULL,
  `estado` VARCHAR(19) NOT NULL,
  `sexo` CHAR(1) NOT NULL,
  `perfil` CHAR(3) NOT NULL DEFAULT 'USU' COMMENT 'ADM=Administrador\nUSU=Usuario',
  `senha` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gate`.`conteudo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gate`.`conteudo` (
  `id_c` INT(11) NOT NULL AUTO_INCREMENT,
  `link` VARCHAR(255) NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `tipo` CHAR(5) NOT NULL  DEFAULT 'CONT' COMMENT 'CONT=Conteudo\nTEND=Tendencia\nLANC=Lancamento\nFS=FilmeSerie\nGAME=Game\nANIME=Anime' ,
  `texto` MEDIUMTEXT NOT NULL,
  `tipoImg` VARCHAR(255) NOT NULL,
  `usuario_id_user` INT(11) NOT NULL,
  PRIMARY KEY (`id_c`, `usuario_id_user`),
  INDEX `fk_conteudo_usuario1_idx` (`usuario_id_user` ASC),
  CONSTRAINT `fk_conteudo_usuario1`
    FOREIGN KEY (`usuario_id_user`)
    REFERENCES `gate`.`usuario` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gate`.`comentario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gate`.`comentario` (
  `id_comn` INT(11) NOT NULL,
  `texto` TINYTEXT NOT NULL,
  `usuario_id_user` INT(11) NOT NULL,
  `conteudo_id_c` INT(11) NOT NULL,
  PRIMARY KEY (`id_comn`, `usuario_id_user`, `conteudo_id_c`),
  INDEX `fk_comentario_usuario1_idx` (`usuario_id_user` ASC),
  INDEX `fk_comentario_conteudo1_idx` (`conteudo_id_c` ASC),
  CONSTRAINT `fk_comentario_conteudo1`
    FOREIGN KEY (`conteudo_id_c`)
    REFERENCES `gate`.`conteudo` (`id_c`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comentario_usuario1`
    FOREIGN KEY (`usuario_id_user`)
    REFERENCES `gate`.`usuario` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gate`.`curtir`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gate`.`curtir` (
  `id_ctr` INT(11) NOT NULL,
  `qntdd` INT(11) NOT NULL,
  `usuario_id_user` INT(11) NOT NULL,
  `conteudo_id_c` INT(11) NOT NULL,
  PRIMARY KEY (`id_ctr`, `usuario_id_user`, `conteudo_id_c`),
  INDEX `fk_curtir_usuario1_idx` (`usuario_id_user` ASC),
  INDEX `fk_curtir_conteudo1_idx` (`conteudo_id_c` ASC),
  CONSTRAINT `fk_curtir_conteudo1`
    FOREIGN KEY (`conteudo_id_c`)
    REFERENCES `gate`.`conteudo` (`id_c`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_curtir_usuario1`
    FOREIGN KEY (`usuario_id_user`)
    REFERENCES `gate`.`usuario` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

INSERT INTO gate.usuario (nome, email, cidade, estado, sexo, perfil, senha)
VALUES 	('Admin', 'adm@gmail.com', 'Brasília', 'DF', 'm', 'ADM', md5(123456));

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;