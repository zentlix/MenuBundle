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
use Zentlix\MainBundle\UI\Http\Web\Controller\Admin\ResourceController;
use Zentlix\MenuBundle\Application\Command\Menu\CreateCommand;
use Zentlix\MenuBundle\Application\Command\Menu\UpdateCommand;
use Zentlix\MenuBundle\Application\Command\Menu\DeleteCommand;
use Zentlix\MenuBundle\Application\Query\Menu\DataTableQuery;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\UI\Http\Web\DataTable\Menu\Table;
use Zentlix\MenuBundle\UI\Http\Web\Form\Menu\CreateForm;
use Zentlix\MenuBundle\UI\Http\Web\Form\Menu\UpdateForm;

class MenuController extends ResourceController
{
    public static $createSuccessMessage = 'zentlix_menu.menu.create.success';
    public static $updateSuccessMessage = 'zentlix_menu.menu.update.success';
    public static $deleteSuccessMessage = 'zentlix_menu.menu.delete.success';
    public static $redirectAfterAction  = 'admin.menu.list';

    public function index(): Response
    {
        return $this->listResource(new DataTableQuery(Table::class), '@MenuBundle/admin/menu/menu.html.twig');
    }

    public function create(): Response
    {
        return $this->createResource(new CreateCommand(), CreateForm::class, '@MenuBundle/admin/menu/create.html.twig');
    }

    public function update(Menu $menu): Response
    {
        return $this->updateResource(
            new UpdateCommand($menu), UpdateForm::class,'@MenuBundle/admin/menu/update.html.twig', ['menu' => $menu]
        );
    }

    public function delete(Menu $menu): Response
    {
        return $this->deleteResource(new DeleteCommand($menu));
    }
}