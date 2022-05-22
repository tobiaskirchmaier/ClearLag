<?php
declare(strict_types=1);

namespace tobias14\clearlag\preferences\types;

final class CommandPreferences
{

    /**
     * @param bool $enabled
     * @param string $name
     * @param string $description
     */
    public function __construct(private bool $enabled, private string $name, private string $description) {}

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

}
