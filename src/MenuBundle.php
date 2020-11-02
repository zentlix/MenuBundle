<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Zentlix\MainBundle\ZentlixBundleInterface;
use Zentlix\MainBundle\ZentlixBundleTrait;
use Zentlix\MenuBundle\Application\Command;
use Zentlix\MenuBundle\Application\Query;

class MenuBundle extends Bundle implements ZentlixBundleInterface
{
    use ZentlixBundleTrait;

    public function getTitle(): string
    {
        return 'zentlix_menu.menu.menu';
    }

    public function getVersion(): string
    {
        return '1.0.1';
    }

    public function getDeveloper(): array
    {
        return ['name' => 'Zentlix', 'url' => 'https://zentlix.io'];
    }

    public function getDescription(): string
    {
        return 'zentlix_menu.bundle_description';
    }

    public function configureRights(): array
    {
        return [
            Query\Menu\DataTableQuery::class  => 'zentlix_menu.view',
            Command\Menu\CreateCommand::class => 'zentlix_menu.menu.create.process',
            Command\Menu\UpdateCommand::class => 'zentlix_menu.menu.update.process',
            Command\Menu\DeleteCommand::class => 'zentlix_menu.menu.delete.process',
        ];
    }
}