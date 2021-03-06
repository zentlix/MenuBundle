<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Domain\Menu\Specification;

use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\NotFoundException;
use Zentlix\MenuBundle\Domain\Menu\Repository\ItemRepository;
use function is_null;

final class ExistItemSpecification
{
    private ItemRepository $itemRepository;
    private TranslatorInterface $translator;

    public function __construct(ItemRepository $itemRepository, TranslatorInterface $translator)
    {
        $this->itemRepository = $itemRepository;
        $this->translator = $translator;
    }

    public function isExist(int $id): void
    {
        if(is_null($this->itemRepository->find($id))) {
            throw new NotFoundException(sprintf($this->translator->trans('zentlix_menu.item.not_exist'), $id));
        }
    }

    public function __invoke(int $id): void
    {
        $this->isExist($id);
    }
}