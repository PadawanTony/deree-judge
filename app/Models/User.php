<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 7/7/16
 * Time: 1:17 PM
 */
namespace Judge\Models;

use Judge\Database\DB;

class User
{
    protected $myDB;

    protected $email;
    protected $password;
    protected $isAdmin;
    protected $id;

    public function __construct($email, $password)
    {
        $this->myDB = new DB();

        $this->setClassVariables($email, $password);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function setClassVariables($email, $password)
    {
        $user = $this->myDB->getUser($email, $password);
        
        $this->id = $user['userID'];
        $this->email = $user['userEmail'];
        $this->password = $user['userPassword'];
        $this->isAdmin = $user['admin'];
    }

    public function isAdmin()
    {
        if ($this->getIsAdmin() === '1') {
            return "";
        } elseif (is_null($this->isAdmin)) {
            return "The credentials you entered are wrong";
        } elseif ($this->isAdmin === '0') {
            return "You are a user but not an admin..";
        } else {
            return "If you forgot your credentials contact support";
        }
    }

    public function isLoggedIn()
    {
        if (isset($_COOKIE['active'])) {
            return true;
        }

        if (isset($_SESSION['user']) && $_SESSION['user'] == $this->getEmail()) {
            return true;
        } else {
            return false;
        }
    }

    public function login()
    {
        //Start $_SESSION
        $status = session_status();
        if ($status == PHP_SESSION_NONE) {
            //There is no active session
            session_start();
        } elseif ($status == PHP_SESSION_DISABLED) {
            //Sessions are not available
        } elseif ($status == PHP_SESSION_ACTIVE) {
            //Destroy current and start new one
            session_destroy();
            session_start();
        }

        //Set $_SESSION variables
        $_SESSION['user'] = $this->getEmail();
        $_SESSION['userID'] = $this->getId();
        $_SESSION['password'] = $this->getPassword();
        $_SESSION['isAdmin'] = $this->getIsAdmin();

        //Set $_COOKIE
        if (isset($_POST['remember'])) {
            setcookie("active", $_SESSION['user'], time() + (3600 * 24 * 365));
        }
    }

    public function logout()
    {
        $status = session_status();
        if ($status == PHP_SESSION_NONE) {
            //There is no active session
            session_start();
        } elseif ($status == PHP_SESSION_DISABLED) {
            //Sessions are not available
        } elseif ($status == PHP_SESSION_ACTIVE) {
            //Destroy current and start new one
            session_destroy();
            session_start();
        }

        //Unset $_SESSION variables
        unset($_SESSION["user"]);
        unset($_SESSION["password"]);
        unset($_SESSION["isAdmin"]);

        //Unset $_COOKIE variables
        unset($_COOKIE['active']);
        setcookie('active', '', time() - 3600);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

}