<?php
declare(strict_types=1);

namespace tobias14\clearlag;

use pocketmine\lang\Language;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use tobias14\clearlag\command\ClearLagCommand;
use tobias14\clearlag\task\ClearTask;
use tobias14\clearlag\utils\ParseTimeException;
use tobias14\clearlag\utils\TimeParserTrait;
use tobias14\clearlag\utils\TranslationTrait;
use function define;

class ClearLag extends PluginBase
{

    use TimeParserTrait;
    use TranslationTrait;

    /** @var self $instance */
    private static self $instance;

    public static function getInstance(): self
    {
        return self::$instance;
    }

    /** @var string $prefix */
    protected string $prefix;

    /** @var int $clearDelay */
    protected int $clearDelay;

    /** @var string[] $entityExceptions */
    protected array $entityExceptions;

    /** @var Language $lang */
    protected Language $lang;

    public function getLanguage(): Language
    {
        return $this->lang;
    }

    public function getMessagePrefix(): string
    {
        return $this->prefix;
    }

    protected function onEnable(): void
    {
        self::$instance = $this;
        define("tobias14\clearlag\LANGUAGE", 'lang_' . $this->getDescription()->getVersion());
        $this->reloadConfig();
        $this->saveResource(LANGUAGE . '.ini');
        $this->initialize();
        ClearTask::run($this->clearDelay, $this->entityExceptions);
        $this->getServer()->getCommandMap()->register('ClearLag', new ClearLagCommand($this, $this->entityExceptions));
    }

    private function initialize(): void
    {
        $config = $this->getConfig();

        /** @var string $prefix */
        $prefix = $config->get('message-prefix', '&7[&cClearLag&7]');
        $this->prefix = TextFormat::colorize($prefix) . TextFormat::RESET;

        /** @var string $clearDelay */
        $clearDelay = $config->get('clear-delay', '15m');
        try {
            $this->clearDelay = $this->parseTimeString($clearDelay);
        } catch(ParseTimeException $e) {
            $this->getLogger()->critical($e->getMessage());
            $this->getLogger()->critical('Using default value...');
            $this->clearDelay = 900;
        }

        $exceptions = $config->get('exceptions', []);
        if(!is_array($exceptions)) {
            $this->getLogger()->critical('Wrong type for exceptions key: expected array');
            $exceptions = [];
        }
        /** @var string[] $exceptions */
        $this->entityExceptions = $exceptions;

        # @phpstan-ignore-next-line
        $this->lang = new Language(LANGUAGE, $this->getDataFolder(), LANGUAGE);
    }

}
