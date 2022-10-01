<?php
declare(strict_types=1);

namespace tobias14\clearlag\preferences;

use Exception;
use pocketmine\utils\Config;
use tobias14\clearlag\preferences\types\CommandPreferences;
use tobias14\clearlag\preferences\types\EntityPreferences;
use tobias14\clearlag\preferences\types\RemovalPreferences;
use tobias14\clearlag\preferences\types\Translation;
use tobias14\clearlag\preferences\types\Translations;
use tobias14\clearlag\utils\Message;
use tobias14\clearlag\utils\TimeUtils;

final class Preferences
{

    /** @var Translations $translations */
    private Translations $translations;

    /** @var CommandPreferences $commandPreferences */
    private CommandPreferences $commandPreferences;

    /** @var RemovalPreferences $removalPreferences */
    private RemovalPreferences $removalPreferences;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->translations = new Translations();
        $this->translations->register(new Translation(
            'alert-message',
            $config->getNested('alert-message.type', Message::TYPE_MESSAGE),
            $config->getNested('alert-message.text', '&7[&cClearLag&7] &fAll entities will be removed in &c{TIME} {UNIT}&f!')
        ));
        $this->translations->register(new Translation(
            'success-message',
            $config->getNested('success-message.type', Message::TYPE_MESSAGE),
            $config->getNested('success-message.text', '&7[&cClearLag&7] &c{ITEM_COUNT} items &fand &c{ENTITY_COUNT} entities &fwere removed.')
        ));
        $this->translations->register(new Translation(
            TimeUtils::UNIT_HOURS,
            'unknown',
            $config->getNested('time/unit.unit/hours', TimeUtils::UNIT_HOURS)
        ));
        $this->translations->register(new Translation(
            TimeUtils::UNIT_HOUR,
            'unknown',
            $config->getNested('time/unit.unit/hour', TimeUtils::UNIT_HOUR)
        ));
        $this->translations->register(new Translation(
            TimeUtils::UNIT_MINUTES,
            'unknown',
            $config->getNested('time/unit.unit/minutes', TimeUtils::UNIT_MINUTES)
        ));
        $this->translations->register(new Translation(
            TimeUtils::UNIT_MINUTE,
            'unknown',
            $config->getNested('time/unit.unit/minute', TimeUtils::UNIT_MINUTE)
        ));
        $this->translations->register(new Translation(
            TimeUtils::UNIT_SECONDS,
            'unknown',
            $config->getNested('time/unit.unit/seconds', TimeUtils::UNIT_SECONDS)
        ));
        $this->translations->register(new Translation(
            TimeUtils::UNIT_SECOND,
            'unknown',
            $config->getNested('time/unit.unit/second', TimeUtils::UNIT_SECOND)
        ));

        $this->commandPreferences = new CommandPreferences(
            $config->getNested('command/clearlag.enabled', true),
            $config->getNested('command/clearlag.name', 'clearlag'),
            $config->getNested('command/clearlag.description', 'Removes all entities')
        );

        $entityPreferences = new EntityPreferences();
        $entityPreferences->set(EntityPreferences::PREFERENCE_ITEMS, (bool) $config->getNested('clearlag/preferences.entities.entity/item', true));
        $entityPreferences->set(EntityPreferences::PREFERENCE_LIVING, (bool) $config->getNested('clearlag/preferences.entities.entity/living', true));
        $entityPreferences->set(EntityPreferences::PREFERENCE_XPORBS, (bool) $config->getNested('clearlag/preferences.entities.entity/experience-orb', true));
        $entityPreferences->set(EntityPreferences::PREFERENCE_ALL, (bool) $config->getNested('clearlag/preferences.entities.entity/*', false));

        try {
            $clearDelay = TimeUtils::parseTimeString($config->getNested('clearlag/preferences.clear-delay', '15m'));
        } catch(Exception) {
            $clearDelay = 0x384; // 15 minutes
        }

        try {
            $alertTimes = array_map(function(string $timeString): int {
                return TimeUtils::parseTimeString($timeString);
            }, $config->getNested('clearlag/preferences.logging/alert-times', []));
        } catch(Exception) {
            $alertTimes = [];
        }

        $this->removalPreferences = new RemovalPreferences(
            $clearDelay,
            $config->getNested('clearlag/preferences.logging/console', true),
            $alertTimes,
            $entityPreferences,
            $config->getNested('clearlag/preferences.entities.entity/blacklist', [])
        );
    }

    /**
     * @return Translations
     */
    public function getTranslations(): Translations
    {
        return $this->translations;
    }

    /**
     * @return CommandPreferences
     */
    public function getCommandPreferences(): CommandPreferences
    {
        return $this->commandPreferences;
    }

    /**
     * @return RemovalPreferences
     */
    public function getRemovalPreferences(): RemovalPreferences
    {
        return $this->removalPreferences;
    }

}
