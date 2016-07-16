<?php
namespace Controllers\Pages;

use Controllers\Navigation;

class DefaultData
{
    public static function getDefaultData()
    {
        // Add all default data to same array
        return array_merge(
            Navigation\MainMenu::getMainMenuData()
        );
    }
}