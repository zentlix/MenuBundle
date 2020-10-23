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

use Zentlix\MenuBundle\Infrastructure\Menu\Service\ProviderInterface;

class Providers
{
    private array $providers = [];

    public function __construct(iterable $providers)
    {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    public function addProvider(ProviderInterface $provider)
    {
        if(isset($this->providers[$provider->getType()])) {
            throw new \DomainException(sprintf('Menu provider with type %s already exist.', $provider->getType()));
        }

        $this->providers[$provider->getType()] = $provider;
    }

    public function getDefaultProvider(): ProviderInterface
    {
        return $this->getProviderByType('url');
    }

    public function getProviderByType(string $type): ProviderInterface
    {
        return $this->providers[$type];
    }

    public function getProviders(): array
    {
        return $this->providers;
    }
}