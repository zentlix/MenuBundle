<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Infrastructure\Item\Bus;

use Zentlix\MenuBundle\Domain\Menu\Entity\Item;

interface CreateCommandInterface
{
    public function getTitle(): string;
    public function getUrl(): ?string;
    public function getSort(): int;
    public function isCategory(): bool;
    public function isBlank(): bool;
    public function getDepth(): int;
    public function getEntityId();
    /** @return Item|int|null */
    public function getParent();
    public function getProvider(): string;
}