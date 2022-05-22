<?php
declare(strict_types=1);

namespace tobias14\clearlag\preferences\types;

final class Translations
{

    /** @var Translation[] $translations */
    private array $translations = [];

    /**
     * @param Translation $translation
     * @return void
     */
    public function register(Translation $translation): void
    {
        $this->translations[$translation->getId()] = $translation;
    }

    /**
     * @param string $index
     * @return Translation|null
     */
    public function get(string $index): ?Translation
    {
        return $this->translations[$index] ?? null;
    }

}
