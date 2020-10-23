<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Application\Command\Menu;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandHandlerInterface;
use Zentlix\MenuBundle\Domain\Menu\Event\Menu\BeforeUpdate;
use Zentlix\MenuBundle\Domain\Menu\Event\Menu\AfterUpdate;
use Zentlix\MenuBundle\Domain\Menu\Service\Cache;
use Zentlix\MenuBundle\Domain\Menu\Specification\UniqueCodeSpecification;

class UpdateHandler implements CommandHandlerInterface
{
    private UniqueCodeSpecification $uniqueCodeSpecification;
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                UniqueCodeSpecification $uniqueCodeSpecification)
    {
        $this->uniqueCodeSpecification = $uniqueCodeSpecification;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(UpdateCommand $command): void
    {
        $menu = $command->getEntity();

        if(!$menu->isCodeEqual($command->code)) {
            $this->uniqueCodeSpecification->isUnique($command->code);
        }

        $this->eventDispatcher->dispatch(new BeforeUpdate($command));

        $menu->update($command);
        $this->entityManager->flush();

        Cache::clearMenu($menu->getCode());

        $this->eventDispatcher->dispatch(new AfterUpdate($menu, $command));
    }
}