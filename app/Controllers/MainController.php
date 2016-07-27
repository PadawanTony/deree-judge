<?php
namespace Judge\Controllers;

use Judge\Database\DB;
use Judge\Models\User;

class MainController extends Controller
{
    protected $user;

    public function __construct($data=null)
    {
        parent::__construct($data);

        if (isset($_SESSION['user']) && isset($_SESSION['password'])) {
            $this->user = new User($_SESSION['user'], $_SESSION['password']);
        }
    }

    public function index($message=null, $success=null)
    {
        if ($message == null) {
            echo $this->twig->render('index.twig');
        } else {
            echo $this->twig->render('index.twig', array('errorMessage'=>$message, 'success'=>$success));
        }
    }

    public function professor()
    {
        echo $this->twig->render('professor.twig');
    }

    public function login($errorMessage = null)
    {
        if (isset($errorMessage))
            echo $this->twig->render('login.twig', array('errorMessage' => $errorMessage));
        else
            echo $this->twig->render('login.twig');
    }

    public function postLogin()
    {
        $myDB = new DB();

        $user = $myDB->getUser($_POST['email'], $_POST['password']);

        if (empty($user)) {
            $errorMessage = "Wrong Credentials.";

            $this->login($errorMessage);
        } else {
            $this->user = new User($_POST['email'], $_POST['password']); //find the user from db

            $this->user->login(); //set Cookies and Session

            $this->selectProfessor(); //show selectProfessor page
        }
    }

    public function logout()
    {
        if (isset($this->user) && $this->user->IsLoggedIn()) {
            $this->user->logout();
            $errorMessage = "You have been logged out.";
            $this->login($errorMessage);
        }
    }

    public function error404()
    {
        echo $this->twig->render('error404.twig');
    }

    public function test()
    {
        echo $this->twig->render('materialize_test.twig');
    }

    public function selectProfessor()
    {
        if (isset($this->user) && $this->user->isLoggedIn() ) {

            $myDB = new DB();

            $majors = $myDB->getAllMajors();
            $professors = $myDB->getAllProfessors();
            
            echo $this->twig->render('selectProfessor.twig', array('majors'=>$majors, 'professors'=>$professors));
        }
        else {
            $errorMessage = "You need to login to continue";
            $this->login($errorMessage);
        }

    }

    public function postSelectProfessor()
    {
        echo $this->twig->render('judge.twig');
    }

    public function postJudge()
    {
        $myDB = new DB();

        $success = false;
        $message = $myDB->passJudgment($_POST);

        if (isset($message) && empty($message)) {
            $message = "Thank you for passing your judgment.";
            $success = true;
        }

        $this->index($message, $success);
    }

}