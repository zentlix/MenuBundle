<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\UI\Http\Web\Widget;

use Twig\Extension\AbstractExtension;
use Twig\Environment;
use Twig\TwigFunction;
use Zentlix\MainBundle\Domain\Site\Service\Sites;
use Zentlix\MenuBundle\Domain\Menu\Service\Cache;
use Zentlix\MenuBundle\Domain\Menu\Service\Menu as MenuService;

class Menu extends AbstractExtension
{
    private MenuService $menu;
    private Sites $sites;
    private string $defaultTemplate;

    public function __construct(MenuService $menu, Sites $sites, string $template)
    {
        $this->menu = $menu;
        $this->defaultTemplate = $template;
        $this->sites = $sites;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('menuWidget', [$this, 'getMenu'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function getMenu(Environment $twig, string $code, ?string $template = null): ?string
    {
        $tree = Cache::getMenuTree($code);

        if(is_null($tree)) {
            $tree = $this->menu->getMenuTree($code);
        }
        Cache::setMenuTree($code, $tree);

        if(is_null($tree)) {
            return null;
        }

        $siteTemplate = $this->sites->getCurrentSite()->getTemplate();
        $templateFile = $siteTemplate->getConfigParam(sprintf('menu.%s', $template));
        if($templateFile) {
            $templateFile = $siteTemplate->getFolder() . '/' . $templateFile;
        }

        return $twig->render($templateFile ?: $this->defaultTemplate, [
            'menu' => $tree
        ]);
    }
}