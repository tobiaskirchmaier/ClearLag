<?php
declare(strict_types=1);

namespace tobias14\clearlag\utils;

use Closure;
use DateInterval;
use Exception;
use function preg_match;
use function strtoupper;
use function array_map;

trait TimeParserTrait
{

    /**
     * @throws ParseTimeException
     */
    public function parseTimeString(string $time): int
    {
        if(!preg_match('/^([1-9][0-9]*[hms])+$/i', $time)) {
            throw new ParseTimeException('Invalid time string given: ' . $time);
        }
        try {
            $interval = new DateInterval('PT' . strtoupper($time));
        } catch(Exception $e) {
            throw new ParseTimeException($e->getMessage());
        }
        return ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
    }

    /**
     * @param string[] $times
     * @return int[]
     */
    public function parseTimeStrings(array $times): array
    {
        $callback = Closure::fromCallable([$this, 'parseTimeString']);
        return array_map($callback, $times);
    }

    public function toHPU(int $seconds): float
    {
        if($seconds >= 3600) {
            return ceil($seconds / 3600);
        }
        if($seconds >= 60) {
            return ceil($seconds / 60);
        }
        return $seconds;
    }

    public function findHPU(int $seconds): string
    {
        if($seconds > 3600) {
            return 'unit.hours';
        }
        if($seconds === 3600) {
            return 'unit.hour';
        }
        if($seconds > 60) {
            return 'unit.minutes';
        }
        if($seconds === 60) {
            return 'unit.minute';
        }
        return $seconds === 1 ? 'unit.second' : 'unit.seconds';
    }

}
