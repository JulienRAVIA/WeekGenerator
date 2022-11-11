<?php

namespace Xylis\WeekGenerator;

use Recurr\Exception;
use Recurr\Exception\InvalidArgument;
use Recurr\Exception\InvalidRRule;
use Recurr\Exception\InvalidWeekday;
use Recurr\Recurrence;
use Recurr\RecurrenceCollection;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;

/**
 * @package xylis/week-generator
 */
class Generator
{
    /** @var ArrayTransformer */
    private $arrayTransformer;

    public function __construct(ArrayTransformer $arrayTransformer)
    {
        $this->arrayTransformer = $arrayTransformer;
    }

    /**
     * @throws GeneratorException
     */
    public function generate(int $year, array $days, string $frequency = 'WEEKLY', string $timeZone = 'Europe/Paris'): RecurrenceCollection
    {
        $timeZone = new \DateTimeZone($timeZone);
        $startDate   = new \DateTime(sprintf('%s-01-01 00:00:00', $year), $timeZone);
        $endDate     = new \DateTime(sprintf('%s-12-31 23:59:00', $year), $timeZone);

        try {
            $rule = (new Rule())
                ->setStartDate($startDate)
                ->setUntil($endDate)
                ->setFreq($frequency)
                ->setByDay($days)
            ;

            return $this->arrayTransformer->transform($rule);
        } catch (Exception $exception) {
            throw new GeneratorException($exception->getMessage());
        }
    }
}