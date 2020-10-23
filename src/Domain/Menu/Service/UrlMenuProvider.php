<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Domain\Menu\Service;

use Symfony\Component\Form\FormFactoryInterface;
use Twig\Environment;
use Zentlix\MenuBundle\Application\Command\Item\Url\CreateCommand;
use Zentlix\MenuBundle\Application\Command\Item\Url\UpdateCommand;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Infrastructure\Menu\Service\ProviderInterface;
use Zentlix\MenuBundle\UI\Http\Web\Form\Item\Url\CreateForm;
use Zentlix\MenuBundle\UI\Http\Web\Form\Item\Url\UpdateForm;

class UrlMenuProvider implements ProviderInterface
{
    private FormFactoryInterface $formFactory;
    private Environment $twig;

    public function __construct(FormFactoryInterface $formFactory, Environment $twig)
    {
        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }

    public function getTitle(): string
    {
        return 'zentlix_menu.item.url_type';
    }

    public static function getType(): string
    {
        return 'url';
    }

    public function getUrl(array $item): string
    {
        return $item['url'] ?: '';
    }

    public function getCreateForm(Menu $menu): string
    {
        return $this->twig->render('@MenuBundle/admin/items/url/create.html.twig', [
            'form' => $this->formFactory->create(CreateForm::class, new CreateCommand($menu))->createView(),
            'menu' => $menu
        ]);
    }

    public function getUpdateForm(Item $item): string
    {
        return $this->twig->render('@MenuBundle/admin/items/url/update.html.twig', [
            'form' => $this->formFactory->create(UpdateForm::class, new UpdateCommand($item))->createView(),
            'item' => $item
        ]);
    }
}