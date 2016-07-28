<?php
namespace Judge\Controllers;

use Judge\Database\DB;
use Judge\Models\User;
use Judge\Services\InvertNamesToUrl;
use Judge\Services\ModifyProfessorUrlName;
use Judge\Transformers\JudgmentsTransformer;

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
        $myDB = new DB();
        $judgmentsTransformer = new JudgmentsTransformer();

        $professor = $myDB->getProfessorByUrlName($this->professor);

        $judgmentsResults = $myDB->getJudgmentsForProfessorByID($professor['id']);
        $judgments = $judgmentsTransformer->transformToArrayAndRemoveParentheses($judgmentsResults);

        $comments = $myDB->getCommentsForProfessorByID($professor['id']);

        echo $this->twig->render('professor.twig', array('professor'=>$professor, 'judgments'=>$judgments, 'comments'=>$comments));
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
//        $helper = new ModifyProfessorUrlName();

//        $helper->removeLastDash();

//        echo $this->twig->render('materialize_test.twig');
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
    
    public function selectProfessorToView()
    {
            $myDB = new DB();

            $majors = $myDB->getAllMajors();
            $professors = $myDB->getAllProfessors();
            
            echo $this->twig->render('selectProfessorToView.twig', array('majors'=>$majors, 'professors'=>$professors));
    }

    public function postSelectProfessor()
    {
        $professorID = $_POST['professor'];
        
        echo $this->twig->render('judge.twig', array('professorID'=>$professorID));
    }

    public function judge()
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

    public function viewProfessor()
    {
        $professorID = $_POST['professor'];

        $myDB = new DB();
        $professor = $myDB->getProfessorByID($professorID);
        $professorUrlName = $professor['urlName'];

        header("Location: /professor/$professorUrlName", true, 302);
        exit;
    }
}