<?php
namespace Controllers\Pages;

use Controllers\Navigation;

class DefaultData
{
    public static function getDefaultData()
    {
        $data = [];
        
        // Add data
        $data = array_merge($data, Navigation\MainMenu::getMainMenuData());
        
        return $data;
    }
}