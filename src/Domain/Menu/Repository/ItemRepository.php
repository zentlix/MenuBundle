<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Domain\Menu\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MainBundle\Domain\Shared\Repository\AbstractTreeRepository;
use Zentlix\MainBundle\Domain\Shared\Repository\GetTrait;
use Zentlix\MainBundle\Domain\Shared\Repository\MaxSortTrait;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends AbstractTreeRepository
{
    use GetTrait, MaxSortTrait;

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(Item::class));
    }

    public function assocByMenuId(int $menuId): array
    {
        return array_column(
            $this->createQueryBuilder('a')
                ->select('a.id', 'a.title')
                ->andWhere('a.menu = :menuId')
                ->setParameter(':menuId', $menuId)
                ->orderBy('a.sort')
                ->getQuery()
                ->execute(), 'id', 'title'
        );
    }

    public function getMenuTree(int $menuId)
    {
        return $this->createQueryBuilder('item', 'item.id')
            ->andWhere('item.menu = :menuId')
            ->setParameter('menuId', $menuId)
            ->addOrderBy('item.root, item.lft', 'asc')
            ->getQuery()
            ->getArrayResult();
    }
}