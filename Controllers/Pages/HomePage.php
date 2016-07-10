<?php
namespace Controllers\Pages;

use Twig;

class HomePage
{
    public static function getHomePageHtml()
    {
        $loader = new \Twig_Loader_Filesystem('views');
        $twig = new \Twig_Environment($loader);
        $data = ['title' => 'Homepage'];
        
        return $twig->render('Pages/HomePage.twig', $data);
    }
}