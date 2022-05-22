<?php
declare(strict_types=1);

namespace tobias14\clearlag\preferences\types;

final class RemovalPreferences
{

    /**
     * @param int $delay Time span between each entity removal.
     * @param bool $consoleLogging
     * @param EntityPreferences $entityPreferences
     * @param string[] $blacklist List of entity-/item-names that should not be removed.
     */
    public function __construct(private int $delay, private bool $consoleLogging, private EntityPreferences $entityPreferences, private array $blacklist) {}

    /**
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }

    /**
     * @return bool
     */
    public function getConsoleLogging(): bool
    {
        return $this->consoleLogging;
    }

    /**
     * @return EntityPreferences
     */
    public function getEntityPreferences(): EntityPreferences
    {
        return $this->entityPreferences;
    }

    /**
     * @return string[]
     */
    public function getBlacklist(): array
    {
        return $this->blacklist;
    }

}
