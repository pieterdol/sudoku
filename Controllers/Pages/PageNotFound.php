<?php
namespace Controllers\Pages;

class PageNotFound {
    function getPageNotFoundHtml() {
        $loader = new Twig_Loader_Filesystem('views');
        $twig = new Twig_Environment($loader);
        return $twig->render('Pages/PageNotFound.twig');
    }
}