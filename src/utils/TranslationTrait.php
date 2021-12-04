<?php
declare(strict_types=1);

namespace tobias14\clearlag\utils;

use pocketmine\lang\Translatable;
use tobias14\clearlag\ClearLag;

trait TranslationTrait
{

    /**
     * @param string $string
     * @param float[]|int[]|string[]|Translatable[] $params
     * @return string
     */
    public function translate(string $string, array $params = []): string
    {
        return ClearLag::getInstance()->getLanguage()->translateString($string, $params);
    }

}
