<?php
namespace Judge\Controllers;

class MainController extends Controller
{

    public function __construct($data=null)
    {
        parent::__construct($data);
    }

    public function index()
    {
        echo $this->twig->render('index.twig');
    }

    public function professor()
    {
        echo $this->twig->render('professor.twig');
    }

    public function carousel()
    {
        echo $this->twig->render('carousel.twig');
    }

    public function error404()
    {
        echo $this->twig->render('error404.twig');
    }

    public function test()
    {
        echo $this->twig->render('test.twig');
    }

    
}