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

//
// Class ADOdb
//
//

// --------------------------------------------------------------------------------- //

define('ADODB_ASSOC_CASE', 2);
require_once('ADOdb/adodb/adodb.inc.php');
include_once('ADOdb/adodb/adodb-error.inc.php');
include_once('ADOdb/adodb/lang/adodb-de.inc.php');

class ADOdb extends TICModule
{
    function ADOdb()
    {
	parent::__construct(array
	(new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net"),
	new Author("AlbertLast","AlbertLast","#tic-progger@quakenet")),
	"10",
	"ADOdb",
	"ADOdb backend",
	array(
            "Core" => "10",
	     "Design"=> "10"
        ));
    }

    public function createMenuEntries($menuroot)
    {
        $admin = $menuroot->getChildByName('Admin');
        $admin->addChild(new MenuEntry("Datenbankfehler", 10, $this->getName(), "ADOdb"));
    }

    public function get($module, $key, $value = false)
    {
        global $tic;
        $qry = "SELECT value FROM config WHERE module = %s AND _key_ = %s;";
        $rs = $tic->db->Execute($this->getName(), $qry, array($module, $key));
        if ($rs->EOF)
            return $value;
        else
            return $rs->fields[0];
    }

    public function set($module, $key, $value)
    {
        global $tic;
        $qry = "SELECT value FROM config WHERE module = %s AND _key_ = %s;";
        $rs = $tic->db->Execute($this->getName(), $qry, array($module, $key));
        if ($rs->EOF)
            $qry = "INSERT INTO config (value, module, _key_) VALUES (%s, %s, %s);";
        else
            $qry = "UPDATE config SET value = %s WHERE module = %s AND _key_ = %s;";
        $tic->db->Execute($this->getName(), $qry, array($value, $module, $key));
    }

    public function onLoad()
    {
        global $tic;

        if ($tic->db) {
		return;
	}

        $dsn = $tic->mod['Core']->get($this->getName(), 'dsn');
        if ($dsn) {
            $db = NewADOConnection($dsn);
        } else {
            $type = $tic->mod['Core']->get($this->getName(), 'type');
            $host = $tic->mod['Core']->get($this->getName(), 'host');
            $user = $tic->mod['Core']->get($this->getName(), 'user');
            $pw = $tic->mod['Core']->get($this->getName(), 'pw');
            $name = $tic->mod['Core']->get($this->getName(), 'name');
            $db = NewADOConnection($type);
            $db->PConnect($host, $user, $pw, $name);
        }
        if ($db && $db->IsConnected()){
            $tic->db = new ADOWrapper($db);
	} else
            die('could not connect to database');
    }

    public function getDBType()
    {
        global $tic;
        $type = $tic->mod['Core']->get($this->getName(), 'type');
        if ($type)
            return $type;

        $dsn = $tic->mod['Core']->get($this->getName(), 'dsn');
        $tmp = explode('://', $dsn);
        return $tmp[0];
    }

    public function onExecute($menuentry)
    {
        global $tic;

        $qry = "SELECT module, sql_exec, sql_orig, errnum, time FROM sql_error ORDER BY time DESC";
        $rs = $tic->db->SelectLimit($this->getName(), $qry, 20, 0);
        $errors = $rs->getArray();
        foreach ($errors as $i => $row) {
            $errors[$i]['msg'] = $tic->db->ado->MetaErrorMsg($row['errnum']);
        }

        if ($tic->mod['Right']->isAllowed(ADODB_ACCESS_FAILED_SQL, null))
            $this->setVar('errors', $errors);
        else 
            $this->setVar('errors', array());
        $this->setTemplate('ADOdb.tpl');
    }

    public function getInstallQueriesMySQL()
    {
        return array(
                'DROP TABLE IF EXISTS sql_error',
                //FIXME scheinbar andere timestamp einstellungen mit mysql > 4.1 noetig :(
                'CREATE TABLE sql_error (
                    module VARCHAR(20) NOT NULL ,
                    sql_exec TEXT NOT NULL ,
                    sql_orig TEXT NOT NULL ,
                    errnum INT NOT NULL ,
                    time TIMESTAMP
                ) TYPE = INNODB',
                'DROP TABLE IF EXISTS config',
                'CREATE TABLE config (
                    module VARCHAR (20) NOT NULL,
                    _key_ VARCHAR (20) NOT NULL,
                    value VARCHAR (50) NULL,
                    PRIMARY KEY (module, _key_)
                ) TYPE = INNODB'
            );
    }

    public function getInstallQueriesPostgreSQL()
	{
	    return array(
                'DROP TABLE sql_error;',
                'CREATE TABLE sql_error (
                    module varchar(20) NOT NULL,
                    sql_exec varchar NOT NULL,
                    sql_orig varchar NOT NULL,
                    errnum int NOT NULL,
                    time timestamp(0) NOT NULL DEFAULT now()
                );',
                'DROP TABLE config;',
                'CREATE TABLE config (
                    module varchar(20) NOT NULL,
                    _key_ varchar(20) NOT NULL,
                    value varchar(50),
                    UNIQUE(module, _key_)
                );'
            );
    }

	public function execInstallDBStart($installdata)
	{
        global $tic;
        if (isset($installdata['dsn']))
            $tic->mod['Core']->set($this->getName(), 'dsn', $installdata['dsn']);
        else {
            $tic->mod['Core']->set($this->getName(), 'type', $installdata['type']);
            $tic->mod['Core']->set($this->getName(), 'host', $installdata['host']);
            $tic->mod['Core']->set($this->getName(), 'user', $installdata['user']);
            $tic->mod['Core']->set($this->getName(), 'pw', $installdata['pw']);
            $tic->mod['Core']->set($this->getName(), 'name', $installdata['name']);
        }
        $this->onLoad();
	}
}

class ADOWrapper
{
    public $ado = null;
    private $queryCount = 0;
    private $queryFailedCount = 0;
    private $lastQueryFailed = false;
    public $LOG_ALL_QUERIES = false;
    public $DISABLE_TRANSLATION = false;
    public $DISABLE_LOGGING = false;

    public function ADOWrapper($obj)
    {
        $this->ado = $obj;
        $this->ado->SetFetchMode(ADODB_FETCH_BOTH);
    }

    public function Execute($module, $sql, $inputarr = array(), $magicquotes = 'auto')
    {
        if ($magicquotes = 'auto')
            $magicquotes = get_magic_quotes_gpc();
        $sql2 = $this->preExec($module, $sql, $inputarr, $magicquotes);
        $rs = $this->ado->Execute($sql2);
        $this->postExec($module, $sql, $sql2, $inputarr, $rs);
        return $rs;
    }

    public function SelectLimit($module, $sql, $numrows=-1, $offset=-1, $inputarr=array(), $magicquotes='auto')
    {
        if ($magicquotes = 'auto')
            $magicquotes = get_magic_quotes_gpc();
        $sql2 = $this->preExec($module, $sql, $inputarr, $magicquotes);
        $rs = $this->ado->SelectLimit($sql2, $numrows, $offset);
        $this->postExec($module, $sql, $sql2, $inputarr, $rs);
        return $rs;
    }

    public function Insert_ID()
    {
        global $tic;
        if ($this->lastQueryFailed) //FIXME log something or assert(false) or ...?
            return null;
        if ($tic->mod['ADOdb']->getDBType() == 'postgres') {
            $rs = $this->ado->Execute("SELECT lastval()");
            assert(!$rs->EOF);
            assert(is_numeric($rs->fields[0]));
            return $rs->fields[0];
        } else {
            return $this->ado->Insert_ID();
        }
    }

    public function StartTrans()
    {
        return $this->ado->StartTrans();
    }

    public function FailTrans()
    {
        return $this->ado->FailTrans();
    }

    public function HasFailedTrans()
    {
        return $this->ado->HasFailedTrans();
    }

    public function CompleteTrans()
    {
        return $this->ado->CompleteTrans();
    }

    public function getQueryCounter()
    {
        return $this->queryCount;
    }

    public function getQueryFailedCounter()
    {
        return $this->queryFailedCount;
    }

    /* alias */
    public function get($module, $key, $value = false)
    {
        global $tic;
        return $tic->mod['ADOdb']->get($module, $key, $value);
    }

    /* alias */
    public function set($module, $key, $value)
    {
        global $tic;
        return $tic->mod['ADOdb']->set($module, $key, $value);
    }

    private function preExec($module, $sql, $inputarr, $magicquotes)
    {
        $this->queryCount++;

        $inputarr = $this->escapeArray($inputarr, $magicquotes);

        $sql = $this->translate($module, $sql);
        if (count($inputarr) > 0)
            return vsprintf($sql, $inputarr);
        else
            return $sql;
    }

    private function postExec($module, $sql_orig, $sql, $inputarr, $rs)
    {
        if (!$rs) {
            $this->queryFailedCount++;
            $this->lastQueryFailed = true;
        } else {
            $this->lastQueryFailed = false;
        }
        if ((!$rs || $this->LOG_ALL_QUERIES) && !$this->DISABLE_LOGGING)
            $this->logError($module, $sql_orig, $sql);
    }

    /* escape all strings in array for DB */
    private function escapeArray($arr, $magicquotes)
    {
        foreach($arr as $key => $val) {
            if (is_null($val))
                $arr[$key] = "NULL";
            else if ($val === false)
                $arr[$key] = "'0'";
            else if ($val === true)
                $arr[$key] = "'1'";
            else
                $arr[$key] = $this->ado->qstr($val, $magicquotes);
        }
        return $arr;
    }

    private function logError($module, $sql_orig, $sql)
    {
        global $tic;
        $module = $tic->db->ado->qstr($module);
        $sql_orig = $tic->db->ado->qstr($sql_orig);
        $sql = $tic->db->ado->qstr($sql);
        $errnum = $tic->db->ado->MetaError();
        $qry = "INSERT INTO sql_error (module, sql_exec, sql_orig, errnum) VALUES ($module, $sql, $sql_orig, $errnum);";
        $rs = $this->ado->Execute($qry);
        if (!$rs)
            echo("An Error occured in the SQL Error handling routine: $qry<br>");
    }

    private function translate($module, $sql)
    {
        global $tic;

        if ($this->DISABLE_TRANSLATION)
            return $sql;

        $file = "$module/sqltrans.php";
        if (!is_file($file))
            return $sql;

        $type = $tic->mod['ADOdb']->getDBType();
        $arr = include($file);
        if (!$arr)
            return $sql;
        else
            if (array_key_exists($type, $arr) && array_key_exists($sql, $arr[$type]))
                return $arr[$type][$sql];
            else
                return $sql;
    }
}

?>
