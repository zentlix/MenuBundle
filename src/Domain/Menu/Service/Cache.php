<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Domain\Menu\Service;

use Zentlix\MainBundle\Domain\Cache\Service\Cache as BaseCache;

class Cache extends BaseCache
{
    public static function getMenuTree(string $code)
    {
        return self::get('zentlix_menu_' . $code);
    }

    public static function setMenuTree(string $code, $tree = null): void
    {
        self::set($tree, 'zentlix_menu_' . $code);
    }

    public static function clearMenuTree(string $code): void
    {
        self::clear('zentlix_menu_' . $code);
    }
}