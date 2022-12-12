<?php

declare(strict_types=1);

namespace NunoMaduro\PhpInsights\Domain\Metrics\Complexity;

use NunoMaduro\PhpInsights\Domain\Collector;
use NunoMaduro\PhpInsights\Domain\Contracts\HasInsights;
use NunoMaduro\PhpInsights\Domain\Insights\MethodCyclomaticComplexityIsHigh;

final class MethodComplexity implements HasInsights
{
    /**
     * {@inheritdoc}
     */
    public function getInsights(): array
    {
        return [
            MethodCyclomaticComplexityIsHigh::class,
        ];
    }
}
