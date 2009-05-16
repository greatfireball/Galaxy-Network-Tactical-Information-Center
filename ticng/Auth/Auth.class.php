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

class Auth extends TICModule
{
    public $DISABLE_AUTH = false;
    private $activeUser = null;
    
    function Auth()
    {
	parent::__construct(
	array(new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net")),
	"2",
	"Auth",
	"Authentication backend",
	array(
            "Core" => "4",
            "Design" => "2",
            "ADOdb" => "5",
            "UserMan" => "1"
        ));
    
    }

    public function execInstall($installdata)
    {
        //auth nicht abfragen da wir grade installieren und noch keine user angelegt sind
        $this->DISABLE_AUTH=true;
        return;
    }

    public function createMenuEntries($menuroot)
    {
        $logout = new MenuEntry("Logout", 20, $this->getName(), 'logout');
        $admin = $menuroot->getChildByName('Admin');
        $admin->addChild($logout);
    }

    public function onExecute($menuentry)
    {
        global $tic;
        switch ($menuentry) {
        case 'logout':
            $this->killSession();
            $this->redirectToLogin();
            $tic->accessDenied = true;
            break;
        case 'access_denied':
            if (isset($_SESSION['change_password']) && $_SESSION['change_password']) {
                $this->setTemplate('auth_change_password.tpl');
            } else {
                $this->setVar('failed', true);
                $this->setTemplate('auth_login.tpl');
            }
            break;
        }
    }

    public function onPostLoad()
    {
        global $tic;

        if ($this->DISABLE_AUTH)
            return;

        session_start();

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $this->verifyLogin($_POST['username'], $_POST['password']);
        } else if (isset($_SESSION['username'])) {
            $user = $tic->mod['UserMan']->getUserByNick($_SESSION['username']);
            if ($user) {
                $this->loginOk($user);
            } else {
                $this->killSession();
                $this->redirectToLogin();
            } 
        } else if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) { 
            $this->verifyLogin($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        } else {
            $this->redirectToLogin();
        }

        if (isset($_SESSION['change_password']) && $_SESSION['change_password']) {
            $tic->accessDenied = true;
            if (isset($_POST['password1']) && isset($_POST['password2']) && $_POST['password1'] == $_POST['password2']) {
                $user = $this->getActiveUser();
                $user->setPassword($_POST['password1']);
                $_SESSION['change_password'] = false;
                $tic->accessDenied = false;
            }
        }
    }

    private function loginFailed($user)
    {
        global $tic;
        $this->killSession();
        $tic->accessDenied = true;
        unset($_SESSION['username']);
        unset($_SESSION['change_password']);
        //echo 'Login oder Passwort falsch';
        if ($user)
            $user->incrementFailedLogins();
    }

    private function loginOk($user)
    {
        if (!$user)
            die("Aiiii, this shoudn't happen");
        $this->activeUser = $user;
        $_SESSION['username'] = $user->getNick();
        $user->setLastActive();
    }

    private function killSession()
    {
        unset($_SESSION['username']);
        unset($_SESSION['change_password']);
        setcookie(session_name(), session_id(), 3600*24, '/');
    }

    private function redirectToLogin()
    {
        global $tic;
        if (isset($_GET['extern'])) { die('einloggen'); }
        $this->accessDenied = true;
        $pos = strrpos($_SERVER['SCRIPT_NAME'], '/');
        $webpath = substr($_SERVER['SCRIPT_NAME'], 0, $pos+1);
        header('Location: http://'.$_SERVER['HTTP_HOST'].$webpath);
    }

    private function requestHttpAuth()
    {
        //FIXME support for php5 digest auth
        header('WWW-Authenticate: Basic realm="'.$this->getRealm().'"');
        header('HTTP/1.0 401 Unauthorized');
    }

    private function getRealm()
    {
        global $tic;
        $realm = $tic->db->get($this->getName(), 'realm');
        if (!$realm) {
            //$realm = $this->generateRandomRealm();
            $realm = 'ticng_'.$_SERVER['HTTP_HOST'];
            $tic->db->set($this->getName(), 'realm', $realm);
        }
        return $realm;
    }

    private function verifyLogin($username, $pw)
    {
        global $tic;
        
        unset($_SESSION['change_password']);
        unset($_SESSION['username']);

        $qry = "SELECT salt, pw_hash, pw_aendern FROM GNPlayer INNER JOIN TICUser ".
            "USING (planet, gala) WHERE lower(nick) = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array(strtolower($username)), false);
        if ($rs->EOF) {
            $this->loginFailed(false);
            return false;
        }
        
        $user = $tic->mod['UserMan']->getUserByNick($username);
        
        $salt = $rs->fields['salt'];
        $hash = $this->hashPassword($pw, $salt);
        if ($hash == $rs->fields['pw_hash']) {
            $this->loginOk($user);
            if ($rs->fields['pw_aendern']) {
                $_SESSION['change_password'] = true;
                $tic->accessDenied = true;
            }
            return true;
        } else {
            $this->loginFailed($user);
            return false;
        }
    }

    /* generate a random 24 Byte base64 encoded salt with 128 Bit entropy */
    public function generateSalt()
    {
        $salt = '';
        for ($i = 0; $i < 16; $i++) {
            $salt .= pack('C', mt_rand(0,0xFF));
        }
        return base64_encode($salt);
    }

    public function generateRandomPassword($len = 10, $min_num = 1, $max_num = 2, $caps = true)
    {
        $alpha_small = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $alpha_cap = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $nums = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        $alpha = array_merge($alpha_small, $alpha_cap);

        $num_count = mt_rand($min_num, $max_num);
        shuffle($alpha_small);
        shuffle($alpha_cap);
        shuffle($alpha);
        shuffle($nums);

        $pwa = array();
        for ($i = 0; $i < $len; $i++) {
            if ($i < $num_count)
                array_push($pwa, $nums[mt_rand(0, count($nums)-1)]);
            else
                if ($caps)
                    array_push($pwa, $alpha[mt_rand(0, count($alpha)-1)]);
                else
                    array_push($pwa, $alpha_small[mt_rand(0, count($alpha_small)-1)]);
        }
        shuffle($pwa);
        $pw = '';
        foreach($pwa as $val)
            $pw .= $val;
        return $pw;
    }

    public function hashPassword($pw, $salt)
    {
        return md5($salt.$pw);
    }

    private function generateRandomRealm()
    {
        global $tic;

        $rand = '';
        for ($i = 0; $i < 16; $i++) {
            $rand .= pack('C', mt_rand(0,0xFF));
        }
        $realm = 'ticng_'.md5($rand);
        $tic->db->set($this->getName(), 'realm', $realm);
        return $realm;
    }
    
    public function getInstallQueriesMySQL() { return array(); }
    public function getInstallQueriesPostgreSQL() { return array(); }

    public function getActiveUser()
    {
        return $this->activeUser;
    }
}

?>
