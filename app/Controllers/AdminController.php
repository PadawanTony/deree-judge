<?php
namespace Judge\Controllers;
use Judge\Database\DB;
use Judge\Models\User;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

/**
 * Created by PhpStorm.
 * User: antony
 * Date: 8/3/16
 * Time: 7:21 PM
 */

class AdminController extends Controller
{
    protected $user;

    public function __construct($data = null)
    {
        parent::__construct($data);

        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Views/admin/');
        $this->twig = new Twig_Environment($loader, array(
            'debug' => true,
            // ...
        ));
        $this->twig->addExtension(new Twig_Extension_Debug());
        $this->twig->addGlobal("session", $_SESSION);

        if (isset($_SESSION['user']) && isset($_SESSION['password'])) {
            $this->user = new User($_SESSION['user'], $_SESSION['password']);
        }
    }

    public function dashboard()
    {
        if ($this->adminIsLoggedIn())
            echo $this->twig->render('dashboard.twig');
        else
            $this->login();
    }

    public function login($errorMessage = null)
    {
        if (isset($errorMessage))
            echo $this->twig->render('login.twig');
        else
            echo $this->twig->render('login.twig', array('errorMessage' => $errorMessage));
    }

    public function postLogin()
    {
        $userDB = new DB();

        $user = $userDB->getUser($_POST['userEmail'], $_POST['userPassword']);

        if (empty($user)) {
            $errorMessage = "Wrong Credentials.";
            $this->login($errorMessage);
        } else {
            $this->user = new User($_POST['userEmail'], $_POST['userPassword']); //find the user from db

            $errorMessage = $this->user->isAdmin(); //authenticate user

            if (empty($errorMessage)) { //if authentication successful

                $this->user->login(); //set Cookies and Session

                $this->dashboard(); //show dashboard
            } else {
                $this->login($errorMessage); //redirect to login page
            }
        }
    }

    public function logout()
    {
        if ($this->adminIsLoggedIn()) {
            $this->user->logout();
            $this->login();
        }
    }

    public function adminIsLoggedIn()
    {
        if (isset($this->user) && $this->user->isLoggedIn() && empty($this->user->isAdmin()))
            return true;
        else
            return false;
    }
}