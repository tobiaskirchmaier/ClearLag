<?php
declare(strict_types=1);

namespace tobias14\clearlag\utils;

use DateInterval;
use Exception;
use InvalidArgumentException;

final class TimeUtils
{

    private const HOUR = 0xE10;
    private const MINUTE = 0x3C;
    private const SECOND = 0x1;

    public const UNIT_HOURS = 'hours';
    public const UNIT_HOUR = 'hour';
    public const UNIT_MINUTES = 'minutes';
    public const UNIT_MINUTE = 'minute';
    public const UNIT_SECONDS = 'seconds';
    public const UNIT_SECOND = 'second';

    /**
     * @param string $timeString
     * @return int
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public static function parseTimeString(string $timeString): int
    {
        if(!preg_match('/^([1-9]\d*[hms])+$/i', $timeString)) {
            throw new InvalidArgumentException('Invalid time string given: ' . $timeString);
        }
        $interval = new DateInterval('PT' . strtoupper($timeString));
        return ($interval->h * self::HOUR) + ($interval->i * self::MINUTE) + $interval->s;
    }

    /**
     * @param int $seconds
     * @return string
     */
    public static function getHighestUnit(int $seconds): string
    {
        if($seconds > self::HOUR) {
            return self::UNIT_HOURS;
        }
        if($seconds === self::HOUR) {
            return self::UNIT_HOUR;
        }
        if($seconds > self::MINUTE) {
            return self::UNIT_MINUTES;
        }
        if($seconds === self::MINUTE) {
            return self::UNIT_MINUTE;
        }
        return $seconds === self::SECOND ? self::UNIT_SECOND : self::UNIT_SECONDS;
    }

    /**
     * @param int $seconds
     * @return int
     */
    public static function toHighestUnit(int $seconds): int
    {
        if($seconds >= self::HOUR) {
            return (int) ceil($seconds / self::HOUR);
        }
        if($seconds >= self::MINUTE) {
            return (int) ceil($seconds / self::MINUTE);
        }
        return $seconds;
    }

}
