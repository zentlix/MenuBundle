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

class UrlMenuProvider implements ProviderInterface
{
    public function getTitle(): string
    {
        return 'zentlix_menu.item.url_type';
    }

    public function getType(): string
    {
        return 'url';
    }

    public function isNeedUrl(): bool
    {
        return true;
    }

    public function getUrl(array $item): string
    {
        return $item['url'] ?: '';
    }
}