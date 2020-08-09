<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\UI\Http\Web\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Zentlix\MainBundle\UI\Http\Web\Controller\Admin\ResourceController;
use Zentlix\MenuBundle\Application\Command\Item\CreateCommand;
use Zentlix\MenuBundle\Application\Command\Item\DeleteCommand;
use Zentlix\MenuBundle\Application\Command\Item\UpdateCommand;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\Domain\Menu\Service\Providers;
use Zentlix\MenuBundle\UI\Http\Web\Form\Item\CreateForm;
use Zentlix\MenuBundle\UI\Http\Web\Form\Item\UpdateForm;

class ItemController extends ResourceController
{
    static $updateSuccessMessage = 'zentlix_menu.item.update.success';
    static $deleteSuccessMessage = 'zentlix_menu.item.delete.success';

    public function create(Menu $menu, Request $request, Providers $providers): Response
    {
        static::$redirectAfterCreate = ['admin.menu.update', ['id' => $menu->getId()]];

        return $this->createResource(new CreateCommand($menu, $providers, $request), CreateForm::class, $request);
    }

    public function update(Item $item, Request $request, Providers $providers): Response
    {
        static::$redirectAfterUpdate = ['admin.menu.update', ['id' => $item->getMenu()->getId()]];

        return $this->updateResource(new UpdateCommand($item, $providers, $request), UpdateForm::class, $request);
    }

    public function delete(Item $item): Response
    {
        static::$redirectAfterDelete = ['admin.menu.update', ['id' => $item->getMenu()->getId()]];

        return $this->deleteResource(new DeleteCommand($item));
    }
}