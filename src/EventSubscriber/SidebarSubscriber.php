<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zentlix\MainBundle\Domain\AdminSidebar\Event\BeforeBuild;

class SidebarSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeBuild::class => 'onBeforeBuild',
        ];
    }

    public function onBeforeBuild(BeforeBuild $beforeBuild): void
    {
        $sidebar = $beforeBuild->getSidebar();

        $settings = $sidebar->getMenuItem('zentlix_main.settings');

        $settings
            ->addChildren('zentlix_menu.menu.menu')
            ->generateUrl('admin.menu.list')
            ->sort(110);
    }
}