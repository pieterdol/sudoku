<?php
namespace Controllers\Pages;

use Controllers\Pages\DefaultData;

class HomePage
{
    public static function getHomePageHtml()
    {
        $loader = new \Twig_Loader_Filesystem('views');
        $twig = new \Twig_Environment($loader);
        
        $template_data = DefaultData::getDefaultData();
        
        $template_data['title'] = "Homepage";
        
        return $twig->render('Pages/HomePage.twig', $template_data);
    }
}