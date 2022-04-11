<?php
namespace App\Http\Menus;

use App\MenuBuilder\MenuBuilder;
use App\Models\Role;
use App\Models\Menus;
use App\MenuBuilder\RenderFromDatabaseData;

class GetSidebarMenu
{

    public function getMenuFromDB($menuName, $role)
    {
        $menutab = Menus::join('menu_role', 'menus.id', '=', 'menu_role.menus_id')
            ->join('menulist', 'menulist.id', '=', 'menus.menu_id')
            ->select('menus.*')
            ->where('menulist.name', '=', $menuName)
            ->where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhere('user_id', null);
            })
            ->orderBy('menus.sequence', 'asc')->get();

        $rfd = new RenderFromDatabaseData;
        return $rfd->render($menutab);
    }
}
