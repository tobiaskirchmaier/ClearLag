<?php
declare(strict_types=1);

namespace tobias14\clearlag\preferences\types;

final class Translation
{

    /**
     * @param string $id
     * @param string $type
     * @param string $text
     */
    public function __construct(private string $id, private string $type, private string $text) {}

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

}
