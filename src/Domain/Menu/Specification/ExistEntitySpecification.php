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

use Doctrine\Persistence\ObjectRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MainBundle\Domain\Shared\Specification\AbstractSpecification;
use Zentlix\MainBundle\Application\Query\NotFoundException;

final class ExistEntitySpecification extends AbstractSpecification
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function isExist(ObjectRepository $repository, int $id): bool
    {
        return $this->isSatisfiedBy(['repository' => $repository, 'id' => $id]);
    }

    public function isSatisfiedBy($value): bool
    {
        $repository = $value['repository'];

        /** @var ObjectRepository $repository */
        if($repository->find($value['id']) === null) {
            throw new NotFoundException(sprintf($this->translator->trans('zentlix_menu.entity_not_found'), $value['id']));
        }

        return true;
    }

    public function __invoke(ObjectRepository $repository, int $id)
    {
        return $this->isExist($repository, $id);
    }
}