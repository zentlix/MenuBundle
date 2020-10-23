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

use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MenuBundle\Domain\Menu\Repository\MenuRepository;

final class UniqueCodeSpecification
{
    private MenuRepository $menuRepository;
    private TranslatorInterface $translator;

    public function __construct(MenuRepository $menuRepository, TranslatorInterface $translator)
    {
        $this->menuRepository = $menuRepository;
        $this->translator = $translator;
    }

    public function isUnique(string $code): void
    {
        if($this->menuRepository->hasByCode($code)) {
            throw new NonUniqueResultException(sprintf($this->translator->trans('zentlix_menu.already_exist'), $code));
        }
    }

    public function __invoke(string $code): void
    {
        $this->isUnique($code);
    }
}