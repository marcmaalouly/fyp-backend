<?php
/*
*   07.11.2019
*   MenusMenu.php
*/

namespace App\Http\Menus;

use App\MenuBuilder\MenuBuilder;

// TODO: Fix the missing MenuInterface
//class Menus implements MenuInterface

class Menus
{

    private $mb; //menu builder

    public function __construct()
    {
        $this->mb = new MenuBuilder();
    }


    public function get($roles)
    {
        return $this->mb->getResult();
    }
}
