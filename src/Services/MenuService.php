<?php

namespace Shandialamp\Foodin\Services;

use Shandialamp\Foodin\Models\Menu;
use Shandialamp\Foodin\Models\User;

class MenuService
{
    public function getAllMenusByUser(User $user)
    {
        $menus = Menu::get();
        $menus = $this->listToTree($menus, null);
        return $menus;
    }

    protected function listToTree($items, $parentId, $idKey = 'id', $parentIdKey = 'parent_id')
    {
        $branch = [];

        foreach ($items as $item) {
            if ($item->$parentIdKey === $parentId) {
                $children = $this->listToTree($items, $item[$idKey]);
                if ($children) {
                    $item->children = $children;
                }
                $branch[] = $item;
            }
        }

        return $branch;
    }

    public function loadMenus($data)
    {
        return Menu::insert($data);
    }
}
