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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandBus;
use Zentlix\MenuBundle\Application\Command\Item\Url\CreateCommand;
use Zentlix\MenuBundle\Application\Command\Menu\UpdateCommand;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Event\Menu\AfterCreate;
use Zentlix\MenuBundle\Domain\Menu\Service\UrlMenuProvider;

class MenuSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private CommandBus $commandBus;

    public function __construct(EntityManagerInterface $entityManager, CommandBus $commandBus)
    {
        $this->entityManager = $entityManager;
        $this->commandBus = $commandBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterCreate::class => 'onAfterCreateMenu'
        ];
    }

    public function onAfterCreateMenu(AfterCreate $afterCreate): void
    {
        /** @var Menu $menu */
        $menu = $afterCreate->getEntity();

        $command = new CreateCommand($menu);
        $command->title = 'zentlix_menu.root_level';
        $command->provider = UrlMenuProvider::getType();
        $command->sort = 0;
        $item = new Item($command);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        $menu->setRootItem($item);
        $this->commandBus->handle(new UpdateCommand($menu));
    }
}