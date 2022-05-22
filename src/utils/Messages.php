<?php
declare(strict_types=1);

namespace tobias14\clearlag\utils;

use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\TextFormat;
use tobias14\clearlag\preferences\Preferences;

final class Messages
{

    /** @var Message[] $messages */
    private static array $messages = [];

    /**
     * @param Preferences $preferences
     */
    public function __construct(Preferences $preferences)
    {
        $alertMessageTranslation = $preferences->getTranslations()->get('alert-message') ?? throw new AssumptionFailedError('Alert message was not found');
        self::$messages['alert-message'] = new Message($alertMessageTranslation->getType(), TextFormat::colorize($alertMessageTranslation->getText()));

        $successMessageTranslation = $preferences->getTranslations()->get('success-message') ?? throw new AssumptionFailedError('Success message was not found');
        self::$messages['success-message'] = new Message($successMessageTranslation->getType(), TextFormat::colorize($successMessageTranslation->getText()));
    }

    /**
     * @return Message
     */
    public static function ALERT_MESSAGE(): Message
    {
        return clone self::$messages['alert-message'];
    }

    /**
     * @return Message
     */
    public static function SUCCESS_MESSAGE(): Message
    {
        return clone self::$messages['success-message'];
    }

}
