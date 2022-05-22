<?php
declare(strict_types=1);

namespace tobias14\clearlag\task;

use pocketmine\scheduler\Task;
use tobias14\clearlag\ClearLag;
use tobias14\clearlag\preferences\Preferences;
use tobias14\clearlag\utils\Messages;
use tobias14\clearlag\utils\TimeUtils;

final class ClearTask extends Task
{

    private const HARDCODED_ALERT_TIMES = [0x384, 0x258, 0x12C, 0xF0, 0xB4, 0x78, 0x3C, 0x1E, 0x14, 0xA, 0x5, 0x4, 0x3, 0x2, 0x1];

    /**
     * @param Preferences $preferences
     */
    public static function run(Preferences $preferences): void
    {
        ClearLag::getInstance()->getScheduler()->scheduleRepeatingTask(new self($preferences), 20);
    }

    /** @var int $timer */
    private int $timer;

    /**
     * @param Preferences $preferences
     */
    public function __construct(private Preferences $preferences)
    {
        $this->timer = min(0x3A2, $this->preferences->getRemovalPreferences()->getDelay()); // Do not wait too long after server start. (Set to <= 930)
    }

    public function onRun(): void
    {
        if(in_array($this->timer, self::HARDCODED_ALERT_TIMES)) {
            $message = Messages::ALERT_MESSAGE();
            $unit = $this->preferences->getTranslations()->get(TimeUtils::getHighestUnit($this->timer))?->getText();
            $message->replace(['{TIME}' => (string) TimeUtils::toHighestUnit($this->timer), '{UNIT}' => $unit]);
            $message->broadcast($this->preferences->getRemovalPreferences()->getConsoleLogging());
        }

        if($this->timer === 0) {
            $result = ClearLag::getInstance()->doClear();
            $message = Messages::SUCCESS_MESSAGE();
            $message->replace(['{ENTITY_COUNT}' => (string) $result['entity_count'], '{ITEM_COUNT}' => (string) $result['item_count']]);
            $message->broadcast($this->preferences->getRemovalPreferences()->getConsoleLogging());

            $delay = $this->preferences->getRemovalPreferences()->getDelay();
            $this->timer = in_array($delay, self::HARDCODED_ALERT_TIMES) ? $delay + 0x5 : $delay;
        }

        $this->timer--;
    }

}
