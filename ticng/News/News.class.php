<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006  Andreas Hemel  <dai.shan@gmx.net>                            *
 *                                                                                   *
 *  This program is free software; you can redistribute it and/or                    *
 *  modify it under the terms of the GNU General Public License                      *
 *  as published by the Free Software Foundation; either version 2                   *
 *  of the License, or (at your option) any later version.                           *
 *                                                                                   *
 *  This program is distributed in the hope that it will be useful,                  *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of                   *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                    *
 *  GNU General Public License for more details.                                     *
 *                                                                                   *
 *  You should have received a copy of the GNU General Public License                *
 *  along with this program; if not, write to the Free Software                      *
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.  *
 *                                                                                   *
 *************************************************************************************/


require_once('NewsItem.class.php');

class News extends TICModule
{
    var $_audience = array('Galaxie' => 0,
                     'Allianz' => 1,
                     'Meta'    => 2,
                     'Alle'    => 3);
    function News()
    {
	parent::__construct(
	array(new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net")),
	"2",
	"News",
	"Nachrichten System",
	array(
            "Core" => "4",
            "Design" => "2",
            "ADOdb" => "5",
	    "Right" => "3"
        ));
    
    }

    function createMenuEntries($menuroot)
    {
        $info = $menuroot->getChildByName('TIC Info');
        $info->addChild(new MenuEntry("Nachrichten", -1, $this->getName(), 'read'));
        $admin = $menuroot->getChildByName('Admin');
        $admin->addChild(new MenuEntry("Nachricht schreiben", 1, $this->getName(), 'write'));
    }

    function onExecute($menuentry)
    {
        switch ($menuentry) {
        default:
        case 'read':
            if (isset($_POST['news_delete']))
                $this->_processDeletePost();
            $this->_showReadNews();
            break;
        case 'write':
            if (!isset($_POST['news_write_post'])) {
                $this->_showWriteNews();
            } else {
                $this->_processWritePost();
                $this->_showReadNews();
            }
            break;
        }
    }

    function getInstallQueriesMySQL()
    {
    	global $tic;
    	
        return array_merge($tic->mod['UserMan']->getInstallQueriesMySQL(),
        array(
            'DROP TABLE IF EXISTS news',
            'DROP TABLE IF EXISTS news_read',
            "CREATE  TABLE IF NOT EXISTS `news` (
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
            ",
            "CREATE  TABLE IF NOT EXISTS `news_read` (
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
			COLLATE = latin1_german1_ci;"
        ));
    }

	function getInstallQueriesPostgreSQL()
	{
        return array(
            'DROP TABLE news CASCADE',
            'DROP TABLE news_read CASCADE',
            'CREATE TABLE news (
                news serial NOT NULL PRIMARY KEY,
                sender int NOT NULL REFERENCES TICUser(ticuser),
                time timestamp(0) NOT NULL DEFAULT now(),
                subject varchar(256) NOT NULL,
                text text NOT NULL,
                audience int NOT NULL,
                audience_id int
            );',
            'CREATE TABLE news_read (
                news int NOT NULL REFERENCES news(news),
                ticuser int NOT NULL REFERENCES TICUser(ticuser),
                UNIQUE(news, ticuser)
            );'  
        );
    }
   
    function _showReadNews()
    {
        global $tic;

        $newsItems = array();
        $user = $tic->mod['Auth']->getActiveUser();
        $qry = "SELECT sender_gala, sender_planet , subject, text, audience, audience_id, time, news FROM news ORDER BY time DESC"; // WHERE clause, die die visibilty direkt ueberprueft??
        $rs = $tic->db->Execute($this->getName(), $qry);
        for (; !$rs->EOF; $rs->MoveNext()) {
            $item = new NewsItem(array($rs->fields[0], $rs->fields[1]), $rs->fields[2], $rs->fields[3], $rs->fields[4], $rs->fields[5], $rs->fields[6], $rs->fields[7]);
            if ($item->isVisible()) {
                array_push($newsItems, $item);
                $item->markAsRead();
            }
        }
        $this->setVar('news_items', $newsItems);
        $this->setTemplate('news_read.tpl');
    }

    function _showWriteNews()
    {
        //FIXME
        global $tic;
        $user = $tic->mod['Auth']->getActiveUser();
        $this->setVar('gala', true);
        if ($user->getAllianz())
            $this->setVar('alli', true);
        else 
            $this->setVar('alli', false);
        if ($user->getMeta())
            $this->setVar('meta', true);
        else
            $this->setVar('meta', false);
        $this->setVar('alle', true);
        $this->setTemplate('news_write.tpl');
    }

    function _processDeletePost()
    {
        global $tic;
        $item_id = $_POST['news_delete'];
        if (!is_numeric($item_id))
            return false;
        $item = new NewsItem();
        if (!$item->load($item_id))
            return false;
        return $item->delete();
    }

    function _processWritePost()
    {
        global $tic;
        //FIXME if post is incomplete or wrong call _showWriteNews() again and display incomplete/wrong values again
        if (!isset($_POST['subject']) || !isset($_POST['text']) || !isset($_POST['audience']))
            return false;

        $item = new NewsItem($tic->mod['Auth']->getActiveUser(), $_POST['subject'], $_POST['text'], $_POST['audience']);
        return $item->create();
    }
}

?>
