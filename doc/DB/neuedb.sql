SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `ticngtest` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `ticngtest`;

-- -----------------------------------------------------
-- Table `meta`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `meta` (
  `meta` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(60) NOT NULL ,
  `tag` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`meta`) ,
  UNIQUE INDEX `name` (`name` ASC) ,
  UNIQUE INDEX `tag` (`tag` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `allianz`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `allianz` (
  `allianz` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(60) NOT NULL ,
  `tag` VARCHAR(20) NOT NULL ,
  `meta` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`allianz`) ,
  UNIQUE INDEX `name` (`name` ASC) ,
  UNIQUE INDEX `tag` (`tag` ASC) ,
  INDEX `fk_Allianz_Meta` (`meta` ASC) ,
  CONSTRAINT `fk_Allianz_Meta`
    FOREIGN KEY (`meta` )
    REFERENCES `meta` (`meta` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `allianz_bnd`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `allianz_bnd` (
  `a` INT(11) NOT NULL ,
  `b` INT(11) NOT NULL ,
  INDEX `fk_AllianzBnd_Allianz` (`a` ASC) ,
  INDEX `fk_AllianzBnd_Allianz1` (`b` ASC) ,
  CONSTRAINT `fk_AllianzBnd_Allianz`
    FOREIGN KEY (`a` )
    REFERENCES `allianz` (`allianz` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_AllianzBnd_Allianz1`
    FOREIGN KEY (`b` )
    REFERENCES `allianz` (`allianz` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `galaxie`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `galaxie` (
  `gala` INT(11) NOT NULL ,
  `allianz` INT(11) NULL DEFAULT NULL ,
  UNIQUE INDEX `gala` (`gala` ASC, `allianz` ASC) ,
  PRIMARY KEY (`gala`) ,
  INDEX `fk_Galaxie_Allianz` (`allianz` ASC) ,
  CONSTRAINT `fk_Galaxie_Allianz`
    FOREIGN KEY (`allianz` )
    REFERENCES `allianz` (`allianz` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `gnplayer`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gnplayer` (
  `gala` INT(11) NOT NULL ,
  `planet` INT(11) NOT NULL ,
  `nick` VARCHAR(50) NULL ,
  UNIQUE INDEX `nick` (`nick` ASC) ,
  PRIMARY KEY (`planet`, `gala`) ,
  INDEX `fk_GNPlayer_Galaxie` (`gala` ASC) ,
  CONSTRAINT `fk_GNPlayer_Galaxie`
    FOREIGN KEY (`gala` )
    REFERENCES `galaxie` (`gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `role` (
  `role` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) NOT NULL ,
  `israng` TINYINT(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`role`) ,
  UNIQUE INDEX `name` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `tic_user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tic_user` (
  `gala` INT(11) NOT NULL ,
  `planet` INT(11) NOT NULL ,
  `role` INT(11) NOT NULL ,
  `pw_hash` CHAR(32) NOT NULL ,
  `pw_aendern` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'geändernt von tinyint auf boolean' ,
  `is_bot` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'geändernt von tinyint auf boolean' ,
  `gn_rang` INT(11) NOT NULL DEFAULT '0' ,
  `last_active` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP ,
  `failed_logins` INT(4) NOT NULL DEFAULT '0' ,
  `banned` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'geändernt von tinyint auf boolean' ,
  `timeformat` INT(11) NOT NULL DEFAULT '0' ,
  `telnr_visibility` TINYINT(1) NOT NULL DEFAULT '0' ,
  `authnick` VARCHAR(15) NULL DEFAULT NULL ,
  `salt` VARCHAR(24) NULL DEFAULT NULL ,
  `highlight` VARCHAR(50) NULL DEFAULT NULL ,
  `scantyp` INT(11) NULL DEFAULT NULL ,
  `svs` INT(11) NULL DEFAULT NULL ,
  `elokas` INT(11) NULL DEFAULT NULL ,
  `telnr` VARCHAR(20) NULL DEFAULT NULL ,
  `telnr_comment` VARCHAR(255) NULL DEFAULT NULL ,
  `icq` VARCHAR(13) NULL DEFAULT NULL ,
  `jabber` VARCHAR(200) NULL DEFAULT NULL ,
  PRIMARY KEY (`planet`, `gala`) ,
  INDEX `fk_TICUser_GNPlayer` (`planet` ASC, `gala` ASC) ,
  INDEX `fk_TICUser_role` (`role` ASC) ,
  CONSTRAINT `fk_TICUser_GNPlayer`
    FOREIGN KEY (`planet` , `gala` )
    REFERENCES `gnplayer` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TICUser_role`
    FOREIGN KEY (`role` )
    REFERENCES `role` (`role` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `attplaner_ma`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `attplaner_ma` (
  `ma` INT(11) NOT NULL AUTO_INCREMENT ,
  `planer_gala` INT(11) NOT NULL ,
  `planer_planet` INT(11) NOT NULL ,
  `att_typ` INT(1) NOT NULL DEFAULT '-1' ,
  `auswahl` INT(1) NOT NULL DEFAULT '-1' ,
  PRIMARY KEY (`ma`) ,
  INDEX `fk_Attplaner_ma_TICUser` (`planer_planet` ASC, `planer_gala` ASC) ,
  CONSTRAINT `fk_Attplaner_ma_TICUser`
    FOREIGN KEY (`planer_planet` , `planer_gala` )
    REFERENCES `tic_user` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `attplaner_ziele`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `attplaner_ziele` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `ma` INT(11) NOT NULL ,
  `ziel_gala` INT(11) NOT NULL ,
  `ziel_planet` INT(11) NOT NULL ,
  `freigabe` INT(10) NOT NULL DEFAULT '-1' ,
  `abflug` INT(10) NOT NULL DEFAULT '-1' ,
  `text` VARCHAR(150) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Attplaner_ziele_GNPlayer` (`ziel_planet` ASC, `ziel_gala` ASC) ,
  INDEX `fk_Attplaner_ziele_Attplaner_ma` (`ma` ASC) ,
  CONSTRAINT `fk_Attplaner_ziele_GNPlayer`
    FOREIGN KEY (`ziel_planet` , `ziel_gala` )
    REFERENCES `gnplayer` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Attplaner_ziele_Attplaner_ma`
    FOREIGN KEY (`ma` )
    REFERENCES `attplaner_ma` (`ma` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `attplaner_flotten`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `attplaner_flotten` (
  `flotte` INT(1) NOT NULL ,
  `planet` INT(11) NOT NULL ,
  `gala` INT(11) NOT NULL ,
  `ziel_id` INT(11) NOT NULL ,
  UNIQUE INDEX `ziel_id` (`flotte` ASC, `planet` ASC, `gala` ASC, `ziel_id` ASC) ,
  PRIMARY KEY (`planet`, `gala`, `ziel_id`) ,
  INDEX `fk_Attplaner_flotten_TICUser` (`planet` ASC, `gala` ASC) ,
  INDEX `fk_Attplaner_flotten_Attplaner_ziele` (`ziel_id` ASC) ,
  CONSTRAINT `fk_Attplaner_flotten_TICUser`
    FOREIGN KEY (`planet` , `gala` )
    REFERENCES `tic_user` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Attplaner_flotten_Attplaner_ziele`
    FOREIGN KEY (`ziel_id` )
    REFERENCES `attplaner_ziele` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `config`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `config` (
  `module` VARCHAR(20) NOT NULL ,
  `_key_` VARCHAR(20) NOT NULL ,
  `value` VARCHAR(50) NULL DEFAULT NULL ,
  PRIMARY KEY (`module`, `_key_`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `flotten`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flotten` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `start_gala` INT(11) NOT NULL ,
  `start_planet` INT(11) NOT NULL ,
  `flotte` TINYINT(4) NOT NULL ,
  `ziel_gala` INT(11) NOT NULL ,
  `ziel_planet` INT(11) NOT NULL ,
  `angriff` TINYINT(1) NOT NULL ,
  `rueckflug` TINYINT(1) NOT NULL ,
  `flugdauer` INT(11) NOT NULL ,
  `bleibedauer` INT(11) NOT NULL ,
  `eta` INT(11) NOT NULL ,
  `safe` TINYINT(1) NOT NULL ,
  `user_gala` INT(11) NULL DEFAULT NULL ,
  `user_planet` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Flotten_GNPlayer` (`start_planet` ASC, `start_gala` ASC) ,
  INDEX `fk_Flotten_GNPlayer1` (`ziel_gala` ASC, `ziel_planet` ASC) ,
  INDEX `fk_flotten_tic_user` (`user_planet` ASC, `user_gala` ASC) ,
  CONSTRAINT `fk_Flotten_GNPlayer`
    FOREIGN KEY (`start_planet` , `start_gala` )
    REFERENCES `gnplayer` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Flotten_GNPlayer1`
    FOREIGN KEY (`ziel_gala` , `ziel_planet` )
    REFERENCES `gnplayer` (`gala` , `planet` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_flotten_tic_user`
    FOREIGN KEY (`user_planet` , `user_gala` )
    REFERENCES `tic_user` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `gala` INT(11) NOT NULL ,
  `planet` INT(11) NOT NULL ,
  `action` INT(11) NOT NULL ,
  `object` VARCHAR(100) NOT NULL ,
  `param` VARCHAR(200) NULL DEFAULT NULL ,
  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_log_TICUser` (`planet` ASC, `gala` ASC) ,
  CONSTRAINT `fk_log_TICUser`
    FOREIGN KEY (`planet` , `gala` )
    REFERENCES `tic_user` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 49
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci
COMMENT = 'ticuser gegn planet und gala ausgetauscht';


-- -----------------------------------------------------
-- Table `news`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `news` (
  `news` INT(11) NOT NULL AUTO_INCREMENT ,
  `sender_gala` INT(11) NOT NULL ,
  `sender_planet` INT(11) NOT NULL ,
  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP ,
  `subject` VARCHAR(256) NOT NULL ,
  `text` TEXT NOT NULL ,
  `audience` INT(11) NOT NULL ,
  `audience_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`news`) ,
  INDEX `fk_news_tic_user` (`sender_planet` ASC, `sender_gala` ASC) ,
  CONSTRAINT `fk_news_tic_user`
    FOREIGN KEY (`sender_planet` , `sender_gala` )
    REFERENCES `tic_user` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `news_read`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `news_read` (
  `news` INT(11) NOT NULL ,
  `gala` INT(11) NOT NULL ,
  `planet` INT(11) NOT NULL ,
  PRIMARY KEY (`planet`, `gala`, `news`) ,
  INDEX `fk_news_read_TICUser` (`planet` ASC, `gala` ASC) ,
  INDEX `fk_news_read_news` (`news` ASC) ,
  CONSTRAINT `fk_news_read_TICUser`
    FOREIGN KEY (`planet` , `gala` )
    REFERENCES `tic_user` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_news_read_news`
    FOREIGN KEY (`news` )
    REFERENCES `news` (`news` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `role_capability`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `role_capability` (
  `role` INT(11) NOT NULL ,
  `capability` INT(11) NOT NULL ,
  `allowed` TINYINT(1) NOT NULL DEFAULT '0' ,
  UNIQUE INDEX `role` (`role` ASC, `capability` ASC) ,
  INDEX `fk_role_capability_role` (`role` ASC) ,
  CONSTRAINT `fk_role_capability_role`
    FOREIGN KEY (`role` )
    REFERENCES `role` (`role` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `scan_block`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `scan_block` (
  `user_planet` INT(11) NOT NULL ,
  `user_gala` INT(11) NOT NULL ,
  `zeit` INT(10) NOT NULL ,
  `svs` INT(10) NOT NULL ,
  `scantyp` INT(1) NULL DEFAULT NULL ,
  `planet` INT(11) NOT NULL ,
  `gala` INT(11) NOT NULL ,
  UNIQUE INDEX `scantyp` (`scantyp` ASC) ,
  INDEX `fk_scan_block_TICUser` (`user_planet` ASC, `user_gala` ASC) ,
  INDEX `fk_scan_block_GNPlayer` (`planet` ASC, `gala` ASC) ,
  CONSTRAINT `fk_scan_block_TICUser`
    FOREIGN KEY (`user_planet` , `user_gala` )
    REFERENCES `tic_user` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_scan_block_GNPlayer`
    FOREIGN KEY (`planet` , `gala` )
    REFERENCES `gnplayer` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `scan_header`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `scan_header` (
  `scan` INT(11) NOT NULL AUTO_INCREMENT ,
  `time` INT(10) NOT NULL DEFAULT '-1' ,
  `percent` INT(3) NOT NULL DEFAULT '-1' ,
  `birth` INT(1) NOT NULL DEFAULT '-1' ,
  `type` INT(1) NOT NULL DEFAULT '-1' ,
  `ziel_planet` INT(11) NOT NULL ,
  `ziel_gala` INT(11) NOT NULL ,
  `scanner_gala` INT(11) NULL DEFAULT NULL ,
  `scanner_planet` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`scan`) ,
  INDEX `fk_scan_header_TICUser1` (`scanner_planet` ASC, `scanner_gala` ASC) ,
  INDEX `fk_scan_header_gnplayer` (`ziel_planet` ASC, `ziel_gala` ASC) ,
  CONSTRAINT `fk_scan_header_TICUser1`
    FOREIGN KEY (`scanner_planet` , `scanner_gala` )
    REFERENCES `tic_user` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_scan_header_gnplayer`
    FOREIGN KEY (`ziel_planet` , `ziel_gala` )
    REFERENCES `gnplayer` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `scan_gesch`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `scan_gesch` (
  `scan` INT(11) NOT NULL ,
  `lo` INT(11) NOT NULL DEFAULT '-1' ,
  `lr` INT(11) NOT NULL DEFAULT '-1' ,
  `mr` INT(11) NOT NULL DEFAULT '-1' ,
  `sr` INT(11) NOT NULL DEFAULT '-1' ,
  `aj` INT(11) NOT NULL DEFAULT '-1' ,
  PRIMARY KEY (`scan`) ,
  INDEX `fk_scan_gesch_scan_header` (`scan` ASC) ,
  CONSTRAINT `fk_scan_gesch_scan_header`
    FOREIGN KEY (`scan` )
    REFERENCES `scan_header` (`scan` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `scan_mili`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `scan_mili` (
  `scan` INT(11) NOT NULL ,
  `jaeger0` INT(11) NOT NULL DEFAULT '-1' ,
  `bomber0` INT(11) NOT NULL DEFAULT '-1' ,
  `freggs0` INT(11) NOT NULL DEFAULT '-1' ,
  `zerris0` INT(11) NOT NULL DEFAULT '-1' ,
  `kreuzer0` INT(11) NOT NULL DEFAULT '-1' ,
  `schlachter0` INT(11) NOT NULL DEFAULT '-1' ,
  `traeger0` INT(11) NOT NULL DEFAULT '-1' ,
  `kaper0` INT(11) NOT NULL DEFAULT '-1' ,
  `cancs0` INT(11) NOT NULL DEFAULT '-1' ,
  `jaeger1` INT(11) NOT NULL DEFAULT '-1' ,
  `bomber1` INT(11) NOT NULL DEFAULT '-1' ,
  `freggs1` INT(11) NOT NULL DEFAULT '-1' ,
  `zerris1` INT(11) NOT NULL DEFAULT '-1' ,
  `kreuzer1` INT(11) NOT NULL DEFAULT '-1' ,
  `schlachter1` INT(11) NOT NULL DEFAULT '-1' ,
  `traeger1` INT(11) NOT NULL DEFAULT '-1' ,
  `kaper1` INT(11) NOT NULL DEFAULT '-1' ,
  `cancs1` INT(11) NOT NULL DEFAULT '-1' ,
  `jaeger2` INT(11) NOT NULL DEFAULT '-1' ,
  `bomber2` INT(11) NOT NULL DEFAULT '-1' ,
  `freggs2` INT(11) NOT NULL DEFAULT '-1' ,
  `zerris2` INT(11) NOT NULL DEFAULT '-1' ,
  `kreuzer2` INT(11) NOT NULL DEFAULT '-1' ,
  `schlachter2` INT(11) NOT NULL DEFAULT '-1' ,
  `traeger2` INT(11) NOT NULL DEFAULT '-1' ,
  `kaper2` INT(11) NOT NULL DEFAULT '-1' ,
  `cancs2` INT(11) NOT NULL DEFAULT '-1' ,
  `flotte1_status` INT(11) NOT NULL DEFAULT '-1' ,
  `flotte1_ziel` VARCHAR(50) CHARACTER SET 'utf8' NOT NULL ,
  `flotte2_status` INT(11) NOT NULL DEFAULT '-1' ,
  `flotte2_ziel` VARCHAR(50) CHARACTER SET 'utf8' NOT NULL ,
  PRIMARY KEY (`scan`) ,
  INDEX `fk_scan_mili_scan_header` (`scan` ASC) ,
  CONSTRAINT `fk_scan_mili_scan_header`
    FOREIGN KEY (`scan` )
    REFERENCES `scan_header` (`scan` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `scan_news`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `scan_news` (
  `scan` INT(11) NOT NULL ,
  `type` INT(11) NOT NULL DEFAULT '-1' ,
  `gala` INT(11) NOT NULL ,
  `planet` INT(11) NOT NULL ,
  `time` INT(10) NOT NULL DEFAULT '-1' ,
  `fleet` INT(11) NOT NULL DEFAULT '-1' ,
  `eta` INT(11) NOT NULL DEFAULT '-1' ,
  PRIMARY KEY (`scan`) ,
  INDEX `fk_scan_news_scan_header` (`scan` ASC) ,
  INDEX `fk_scan_news_GNPlayer` (`planet` ASC, `gala` ASC) ,
  CONSTRAINT `fk_scan_news_scan_header`
    FOREIGN KEY (`scan` )
    REFERENCES `scan_header` (`scan` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_scan_news_GNPlayer`
    FOREIGN KEY (`planet` , `gala` )
    REFERENCES `gnplayer` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `scan_sek`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `scan_sek` (
  `scan` INT(11) NOT NULL ,
  `punkte` INT(11) NOT NULL DEFAULT '-1' ,
  `schiffe` INT(11) NOT NULL DEFAULT '-1' ,
  `deff` INT(11) NOT NULL DEFAULT '-1' ,
  `me` INT(11) NOT NULL DEFAULT '-1' ,
  `ke` INT(11) NOT NULL DEFAULT '-1' ,
  `ast` INT(11) NOT NULL DEFAULT '-1' ,
  PRIMARY KEY (`scan`) ,
  INDEX `fk_scan_sek_scan_header` (`scan` ASC) ,
  CONSTRAINT `fk_scan_sek_scan_header`
    FOREIGN KEY (`scan` )
    REFERENCES `scan_header` (`scan` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `scan_unit`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `scan_unit` (
  `scan` INT(11) NOT NULL ,
  `jaeger` INT(11) NOT NULL DEFAULT '-1' ,
  `bomber` INT(11) NOT NULL DEFAULT '-1' ,
  `freggs` INT(11) NOT NULL DEFAULT '-1' ,
  `zerris` INT(11) NOT NULL DEFAULT '-1' ,
  `kreuzer` INT(11) NOT NULL DEFAULT '-1' ,
  `schlachter` INT(11) NOT NULL DEFAULT '-1' ,
  `traeger` INT(11) NOT NULL DEFAULT '-1' ,
  `kaper` INT(11) NOT NULL DEFAULT '-1' ,
  `cancs` INT(11) NOT NULL DEFAULT '-1' ,
  PRIMARY KEY (`scan`) ,
  INDEX `fk_scan_unit_scan_header` (`scan` ASC) ,
  CONSTRAINT `fk_scan_unit_scan_header`
    FOREIGN KEY (`scan` )
    REFERENCES `scan_header` (`scan` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `sql_error`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `sql_error` (
  `module` VARCHAR(20) NOT NULL ,
  `sql_exec` TEXT NOT NULL ,
  `sql_orig` TEXT NOT NULL ,
  `errnum` INT(11) NOT NULL ,
  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;


-- -----------------------------------------------------
-- Table `taktik_update`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `taktik_update` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_gala` INT(11) NULL ,
  `user_planet` INT(11) NULL ,
  `galaxie` INT(11) NOT NULL ,
  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_TaktikUpdate_Galaxie` (`galaxie` ASC) ,
  INDEX `fk_TaktikUpdate_TICUser` (`user_planet` ASC, `user_gala` ASC) ,
  CONSTRAINT `fk_TaktikUpdate_Galaxie`
    FOREIGN KEY (`galaxie` )
    REFERENCES `galaxie` (`gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TaktikUpdate_TICUser`
    FOREIGN KEY (`user_planet` , `user_gala` )
    REFERENCES `tic_user` (`planet` , `gala` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_german1_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
