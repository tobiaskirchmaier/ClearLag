<?php
declare(strict_types=1);

namespace tobias14\clearlag\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use tobias14\clearlag\ClearLag;
use tobias14\clearlag\utils\Messages;

final class ClearLagCommand extends Command implements PluginOwned
{

    private const ACTION_ARGUMENT = 'clear';

    /**
     * @param string $name
     * @param string $description
     */
    public function __construct(string $name, string $description)
    {
        parent::__construct($name, $description, '/' . $name . ' ' . self::ACTION_ARGUMENT);

        $this->setPermission('clearlag.command');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($this->getOwningPlugin()->isDisabled()) {
            $sender->sendMessage(TextFormat::RED . 'This plugin is disabled!');
            return;
        }
        if(!$this->testPermission($sender)) {
            return;
        }
        if(!($sender instanceof Player)) {
            $sender->sendMessage(TextFormat::RED . 'Please use this command ingame!');
            return;
        }
        if(count($args) < 1 || $args[0] !== self::ACTION_ARGUMENT) {
            throw new InvalidCommandSyntaxException();
        }
        $result = ClearLag::getInstance()->doClear();
        $message = Messages::SUCCESS_MESSAGE();
        $message->replace(['{ENTITY_COUNT}' => (string) $result['entity_count'], '{ITEM_COUNT}' => (string) $result['item_count']]);
        $message->sendTo($sender);
    }

    public function getOwningPlugin(): ClearLag
    {
        return ClearLag::getInstance();
    }

}
