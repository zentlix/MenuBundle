<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\UI\Http\Web\Widget\Admin;

use Twig\Extension\AbstractExtension;
use Twig\Environment;
use Twig\TwigFunction;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Service\Providers as ProvidersService;

class Providers extends AbstractExtension
{
    private ProvidersService $providers;

    public function __construct(ProvidersService $providers)
    {
        $this->providers = $providers;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('admin_menu_providers', [$this, 'getProviders'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function getProviders(Environment $twig, Menu $menu): ?string
    {
        return $twig->render('@MenuBundle/admin/widgets/providers.html.twig', [
            'providers' => $this->providers->getProviders(),
            'menu'      => $menu
        ]);
    }
}