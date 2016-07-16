<?php
namespace Controllers\Navigation;

class MainMenu
{
    public static function getMainMenuData()
    {
        $class_key = "menu_item_class";
        $title_key = "menu_item_title";
        $href_key  = "menu_item_href";
        return [
            'main_menu_links' => [
                [
                    $class_key      => "active",
                    $title_key      => "Home",
                    $href_key       => APP_PATH,
                ],
                [
                    $class_key      => "",
                    $title_key      => "Sudoku's",
                    $href_key       => APP_PATH . "sudokus/",
                ],
            ]
        ];
    }
}