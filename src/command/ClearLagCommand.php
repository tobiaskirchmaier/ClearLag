<?php
declare(strict_types=1);

namespace tobias14\clearlag\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use tobias14\clearlag\ClearEntitiesTrait;
use tobias14\clearlag\ClearLag;
use tobias14\clearlag\utils\TranslationTrait;

class ClearLagCommand extends Command implements PluginOwned
{

    use TranslationTrait;
    use ClearEntitiesTrait;

    /**
     * @param string[] $exceptions
     */
    public function __construct(protected ClearLag $plugin, protected array $exceptions)
    {
        parent::__construct($this->translate('clearlag.command.name'), $this->translate('clearlag.command.description'));

        $this->setUsage($this->translate('clearlag.command.usage'));
        $this->setPermission('clearlag.command');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $prefix = trim($this->plugin->getMessagePrefix()) . ' ';
        if($this->plugin->isDisabled()) {
            $sender->sendMessage($prefix . TextFormat::RED . $this->translate('plugin.disabled'));
            return;
        }
        if(!$this->testPermissionSilent($sender)) {
            $sender->sendMessage($prefix . TextFormat::RED . $this->translate('clearlag.command.noperms'));
            return;
        }
        if(count($args) < 1) {
            /** @var string $usage */
            $usage = $this->getUsage();
            $sender->sendMessage($prefix . TextFormat::colorize($usage));
            return;
        }
        switch($args[0]) {
            case 'clear':
                $items = $this->clearItems($this->exceptions);
                $entities = $this->clearEntities($this->exceptions);
                $sender->sendMessage($prefix . TextFormat::colorize($this->translate('clearlag.cleared', [$items, $entities])));
                break;
            default:
                $sender->sendMessage($prefix . TextFormat::RED . $this->translate('clearlag.command.invalidArgument', [$args[0]]));
        }
    }

    public function getOwningPlugin(): ClearLag
    {
        return $this->plugin;
    }

}
