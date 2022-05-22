<?php
declare(strict_types=1);

namespace tobias14\clearlag;

use JetBrains\PhpStorm\ArrayShape;
use pocketmine\entity\Living;
use pocketmine\entity\object\ExperienceOrb;
use pocketmine\entity\object\ItemEntity;
use pocketmine\plugin\PluginBase;
use tobias14\clearlag\command\ClearLagCommand;
use tobias14\clearlag\preferences\Preferences;
use tobias14\clearlag\preferences\types\EntityPreferences;
use tobias14\clearlag\task\ClearTask;
use tobias14\clearlag\utils\EntityRemover;
use tobias14\clearlag\utils\Messages;

class ClearLag extends PluginBase
{

    /** @var self $instance */
    private static self $instance;

    public static function getInstance(): self
    {
        return self::$instance;
    }

    /** @var Preferences $preferences */
    private Preferences $preferences;

    protected function onEnable(): void
    {
        self::$instance = $this;

        $this->reloadConfig();
        $config = $this->getConfig();

        if(!version_compare($config->get('metadata/version', '1.0.0'), $this->getDescription()->getVersion(), '>=')) {
            $this->getLogger()->warning('Configuration file is not up to date! Delete the current config-file and restart the server to update it.');
        }

        $this->preferences = new Preferences($config);

        (new Messages($this->preferences));

        ClearTask::run($this->preferences);

        $cmdPreferences = $this->preferences->getCommandPreferences();
        if($cmdPreferences->isEnabled()) {
            $this->getServer()->getCommandMap()->register('ClearLag', new ClearLagCommand($cmdPreferences->getName(), $cmdPreferences->getDescription()));
        }
    }

    /**
     * @return int[]
     */
    #[ArrayShape(['item_count' => "int", 'entity_count' => "int"])]
    public function doClear(): array
    {
        $removalPreferences = $this->preferences->getRemovalPreferences();
        $entityPreferences = $removalPreferences->getEntityPreferences();

        if($entityPreferences->get(EntityPreferences::PREFERENCE_ITEMS) || $entityPreferences->get(EntityPreferences::PREFERENCE_ALL)) {
            $items = EntityRemover::removeEntities([ItemEntity::class], $removalPreferences->getBlacklist());
        }
        if($entityPreferences->get(EntityPreferences::PREFERENCE_ALL)) {
            $entities = EntityRemover::removeEntities(null, $removalPreferences->getBlacklist());
        } else {
            $entityTypes = $entityPreferences->get(EntityPreferences::PREFERENCE_LIVING) ? [Living::class] : ($entityPreferences->get(EntityPreferences::PREFERENCE_XPORBS) ? [ExperienceOrb::class] : []);
            $entityTypes = $entityPreferences->get(EntityPreferences::PREFERENCE_LIVING) && $entityPreferences->get(EntityPreferences::PREFERENCE_XPORBS) ? [Living::class, ExperienceOrb::class] : $entityTypes;
            $entities = EntityRemover::removeEntities($entityTypes, $removalPreferences->getBlacklist());
        }
        return ['item_count' => $items ?? 0, 'entity_count' => $entities];
    }

}
