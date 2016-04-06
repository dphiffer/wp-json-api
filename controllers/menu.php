<?php

/*
Controller name: Menu
Controller description: Returns the manu named 'navigation' from WordPress
*/

class JSON_API_Menu_Controller
{
    public function get_menu()
    {
        global $json_api;
        nocache_headers();
        $menu = wp_get_nav_menu_items('navigation');
        if (!$menu) {
            $json_api->error('The menu is empty or does not exist');
        }
        return array(
            'menuItems' => $menu
        );
    }
}