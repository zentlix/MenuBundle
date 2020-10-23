<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Infrastructure\Menu\Service;

use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;

interface ProviderInterface
{
    public function getTitle(): string;
    public static function getType(): string;
    public function getUrl(array $item): string;
    public function getCreateForm(Menu $menu): string;
    public function getUpdateForm(Item $item): string;
}