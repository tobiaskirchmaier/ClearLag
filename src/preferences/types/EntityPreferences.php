<?php
declare(strict_types=1);

namespace tobias14\clearlag\preferences\types;

final class EntityPreferences
{

    public const PREFERENCE_ITEMS = 0x1;
    public const PREFERENCE_LIVING = 0x2;
    public const PREFERENCE_XPORBS = 0x3;
    public const PREFERENCE_ALL = 0x4;

    /** @var bool[] $preferences */
    private array $preferences = [];

    /**
     * @param int $preference
     * @param bool $value
     * @return void
     */
    public function set(int $preference, bool $value): void
    {
        $this->preferences[$preference] = $value;
    }

    /**
     * @param int $preference
     * @return bool|null
     */
    public function get(int $preference): ?bool
    {
        return $this->preferences[$preference] ?? null;
    }

}
