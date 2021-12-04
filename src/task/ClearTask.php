<?php
declare(strict_types=1);

namespace tobias14\clearlag\task;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use tobias14\clearlag\ClearEntitiesTrait;
use tobias14\clearlag\ClearLag;
use tobias14\clearlag\utils\TimeParserTrait;
use tobias14\clearlag\utils\TranslationTrait;

final class ClearTask extends Task
{

    use TranslationTrait;
    use ClearEntitiesTrait;
    use TimeParserTrait;

    /** @var int $timer */
    private int $timer;

    /** @var int[] $alertTimes */
    private array $alertTimes;

    /**
     * @param string[] $exceptions
     */
    public static function run(int $delay, array $exceptions): void
    {
        ClearLag::getInstance()->getScheduler()->scheduleRepeatingTask(new self($delay, $exceptions), 20);
    }

    /**
     * @param string[] $exceptions
     */
    public function __construct(private int $delay, private array $exceptions)
    {
        // Do not start immediately after the server start (add 30)
        // Do not wait too long, because of impatience (set to 930)
        $this->timer = $this->delay > 930 ? 930 : $this->delay + 30;

        $alertTimes = ['15m', '10m', '5m', '4m', '3m', '2m', '1m', '30s', '20s', '10s', '5s', '4s', '3s', '2s', '1s'];
        $this->alertTimes = $this->parseTimeStrings($alertTimes);
    }

    public function onRun(): void
    {
        $this->timer--;

        if(in_array($this->timer, $this->alertTimes)) {
            $unit = $this->translate($this->findHPU($this->timer));
            $time = $this->toHPU($this->timer);
            $this->broadcast($this->translate('clearlag.warning', [$time, $unit]));
        }

        if($this->timer === 0) {
            $this->doClear();
        }
    }

    private function doClear(): void
    {
        // Add 5 as a short delay between clear-lags
        $this->timer = $this->delay + 5;

        $items = $this->clearItems($this->exceptions);
        $entities = $this->clearEntities($this->exceptions);

        $this->broadcast($this->translate('clearlag.cleared', [$items, $entities]));
    }

    private function broadcast(string $message): void
    {
        $server = ClearLag::getInstance()->getServer();
        $server->broadcastMessage(trim(ClearLag::getInstance()->getMessagePrefix()) . ' ' . TextFormat::colorize($message));
    }

}
