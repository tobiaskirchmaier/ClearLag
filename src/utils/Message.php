<?php
declare(strict_types=1);

namespace tobias14\clearlag\utils;

use pocketmine\player\Player;
use pocketmine\Server;

final class Message
{

    public const TYPE_MESSAGE = 'message';
    public const TYPE_TITLE = 'title';
    public const TYPE_TIP = 'tip';
    public const TYPE_POPUP = 'popup';

    /**
     * @param string $type
     * @param string $text
     */
    public function __construct(public string $type, public string $text) {}

    /**
     * @param string[] $variables
     * @return void
     */
    public function replace(array $variables): void
    {
        foreach($variables as $search => $replace) {
            $this->text = str_replace($search, $replace, $this->text);
        }
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendTo(Player $player): void
    {
        match(strtolower($this->type)) {
            self::TYPE_TITLE => $player->sendTitle($this->text),
            self::TYPE_TIP => $player->sendTip($this->text),
            self::TYPE_POPUP => $player->sendPopup($this->text),
            default => $player->sendMessage($this->text)
        };
    }

    /**
     * @param bool $logToConsole
     * @return void
     */
    public function broadcast(bool $logToConsole = true): void
    {
        if($logToConsole) {
            Server::getInstance()->getLogger()->info($this->text);
        }
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            $this->sendTo($player);
        }
    }

}
