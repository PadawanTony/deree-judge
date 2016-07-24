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

    public function error404()
    {
        echo $this->twig->render('error404.twig');
    }

}