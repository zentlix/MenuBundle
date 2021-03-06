<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Application\Command\Item\Url;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandHandlerInterface;
use Zentlix\MenuBundle\Domain\Menu\Event\Item\BeforeUpdate;
use Zentlix\MenuBundle\Domain\Menu\Event\Item\AfterUpdate;
use Zentlix\MenuBundle\Domain\Menu\Repository\ItemRepository;
use Zentlix\MenuBundle\Domain\Menu\Service\Cache;
use Zentlix\MenuBundle\Domain\Menu\Specification\ExistItemSpecification;

class UpdateHandler implements CommandHandlerInterface
{
    private ExistItemSpecification $existItemSpecification;
    private ItemRepository $itemRepository;
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                ExistItemSpecification $existItemSpecification,
                                ItemRepository $itemRepository)
    {
        $this->existItemSpecification = $existItemSpecification;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->itemRepository = $itemRepository;
    }

    public function __invoke(UpdateCommand $command): void
    {
        $item = $command->getEntity();

        $this->existItemSpecification->isExist((int) $command->parent);
        $command->parent = $this->itemRepository->get((int) $command->parent);

        $this->eventDispatcher->dispatch(new BeforeUpdate($command));

        $item->update($command);
        $this->entityManager->flush();

        $this->itemRepository->reorder($item->getRoot(), 'sort');
        $this->entityManager->flush();

        Cache::clearMenuTree($item->getMenu()->getCode());

        $this->eventDispatcher->dispatch(new AfterUpdate($item, $command));
    }
}