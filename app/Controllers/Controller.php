<?php
namespace Judge\Controllers;

use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

class Controller
{
    protected $twig;
    protected $data;
    protected $sector;
    protected $professor;

    public function __construct( $data=null )
    {
        //Environment Variable
        //define('CSS_PATH', 'http://fab.app/public/css/');

        $this->data = $data;
        $this->sector = $data[1];
        isset($data[2]) ? $this->professor=$data[2] : $this->professor=null;
        
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Views/');
        $this->twig = new Twig_Environment($loader, array(
            'debug' => true,
            // ...
        ));
        $this->twig->addExtension(new Twig_Extension_Debug());
        $this->twig->addGlobal("session", $_SESSION);
    }
}