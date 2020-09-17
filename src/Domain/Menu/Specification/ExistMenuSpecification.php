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
use Zentlix\MainBundle\Application\Query\NotFoundException;
use Zentlix\MenuBundle\Domain\Menu\Repository\MenuRepository;
use function is_null;

final class ExistMenuSpecification
{
    private MenuRepository $menuRepository;
    private TranslatorInterface $translator;

    public function __construct(MenuRepository $menuRepository, TranslatorInterface $translator)
    {
        $this->menuRepository = $menuRepository;
        $this->translator = $translator;
    }

    public function isExist(int $id): void
    {
        if(is_null($this->menuRepository->find($id))) {
            throw new NotFoundException(sprintf($this->translator->trans('zentlix_menu.not_exist'), $id));
        }
    }

    public function __invoke(int $id): void
    {
        $this->isExist($id);
    }
}