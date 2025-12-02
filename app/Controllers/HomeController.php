<?php
namespace Prin0u\DevoirAppMvcPhp\Controllers;

use Prin0u\DevoirAppMvcPhp\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->render('home/index');
    }
}