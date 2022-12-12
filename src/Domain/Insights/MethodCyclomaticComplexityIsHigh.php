<?php

declare(strict_types=1);

namespace NunoMaduro\PhpInsights\Domain\Insights;

use NunoMaduro\PhpInsights\Domain\Contracts\GlobalInsight;
use NunoMaduro\PhpInsights\Domain\Contracts\HasDetails;
use NunoMaduro\PhpInsights\Domain\Details;
use NunoMaduro\PhpInsights\Domain\Configuration;
use NunoMaduro\PhpInsights\Domain\Container;

/**
 * @see \Tests\Domain\Insights\MethodCyclomaticComplexityIsHighTest
 */
final class MethodCyclomaticComplexityIsHigh extends Insight implements HasDetails, GlobalInsight
{
    /**
     * @var array<Details>
     */
    private array $details = [];

    private Configuration $configuration;

    public function hasIssue(): bool
    {
        return $this->details !== [];
    }

    public function getTitle(): string
    {
        return sprintf(
            'Having `methods` with more than %s cyclomatic complexity is prohibited - Consider refactoring',
            $this->getMaxMethodComplexity()
        );
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function process(): void
    {
        $this->configuration = Container::make()->get(Configuration::class);
        // Exclude in collector all excluded files
        if ($this->excludedFiles !== []) {
            $this->collector->excludeComplexityFiles($this->excludedFiles);
        }
        $complexityLimit = $this->getMaxMethodComplexity();

        $classesMethodsComplexity = array_filter(
            $this->collector->getMethodsComplexity(),
            static fn ($complexity): bool => $complexity > $complexityLimit
        );

        $this->details = array_map(static fn ($class, $complexity): Details => Details::make()
            ->setFile($class)
            ->setMessage("${complexity} cyclomatic complexity"), array_keys($classesMethodsComplexity), $classesMethodsComplexity);
    }

    private function getMaxMethodComplexity(): int
    {
        return $this->configuration->getMaxMethodComplexity() ?? 10;
    }
}
