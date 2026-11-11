<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Guards the dashboard progress doughnuts.
 *
 * The failure this locks down: an overview partial once contained its Blade
 * echo split across lines -
 *
 *     { { $inProgressPercentage } }
 *
 * Blade does not recognise that as an echo, so it was emitted to the browser
 * verbatim. The result was a JavaScript SyntaxError, which killed the whole
 * <script> block and left every ring painted in the flat grey track colour -
 * the reported "colour of the doughnut chart is not working".
 *
 * Rendering each partial and asserting the emitted call carries a real number
 * catches both that regression and any undefined-variable / wrong-name mistake.
 */
class ProgressChartTest extends TestCase
{
    /** @return array<string, array{0:string, 1:array<string,int>}> */
    public static function overviewProvider(): array
    {
        $publishing = [
            'inProgressCount' => 2,
            'inProgressPercentage' => 40,
            'pendingCount' => 1,
            'pendingPercentage' => 35,
            'approvalCount' => 1,
            'approvalPercentage' => 25,
        ];

        $posted = [
            'inProgressCount' => 2,
            'inProgressPercentage' => 40,
            'postedCount' => 1,
            'postedPercentage' => 35,
            'approvalCount' => 1,
            'approvalPercentage' => 25,
        ];

        return [
            'layout' => ['admin.Creatives.layout.overview', $publishing],
            'production' => ['admin.Creatives.production.overview', $publishing],
            'uzpost' => ['admin.Creatives.uzpost.overview', $publishing],
            'uzpr' => ['admin.Creatives.uzpr.overview', $publishing],
            'photography' => ['admin.photography.overview', $posted],
            'socmed' => ['admin.socmed.overview', $posted],
            'softcopy' => ['admin.softcopy.overview', $posted + [
                'denyCount' => 1,
                'denyPercentage' => 25,
            ]],
        ];
    }

    #[DataProvider('overviewProvider')]
    public function test_doughnut_charts_receive_numeric_percentages(string $view, array $data): void
    {
        $html = view($view, $data)->render();

        // Every coloured arc must be handed a bare number, never a Blade echo
        // that leaked through as text.
        $this->assertStringNotContainsString('{ {', $html, "$view leaked an uncompiled Blade echo.");
        $this->assertStringNotContainsString('{{', $html, "$view leaked an uncompiled Blade echo.");

        preg_match_all("/createProgressChart\(\s*'([^']+)'\s*,\s*([^,]+?)\s*,\s*'(#[0-9a-fA-F]{3,8})'\s*\)/", $html, $matches, PREG_SET_ORDER);

        $this->assertCount(3, $matches, "$view should initialise exactly three progress charts.");

        $seen = [];

        foreach ($matches as $match) {
            [$call, $id, $value, $color] = $match;

            $this->assertNotContains($id, $seen, "$view initialises the canvas '$id' more than once.");
            $seen[] = $id;

            $this->assertMatchesRegularExpression(
                '/^\d+(\.\d+)?$/',
                trim($value),
                "$view passes a non-numeric percentage to '$id': {$value}"
            );

            $percentage = (float) trim($value);
            $this->assertGreaterThanOrEqual(0, $percentage, "$view passed a negative percentage to '$id'.");
            $this->assertLessThanOrEqual(100, $percentage, "$view passed more than 100% to '$id'.");

            // The ring is drawn as [percentage, 100 - percentage]; a bad value
            // here silently yields NaN and an invisible arc.
            $this->assertNotSame('', trim($color));
        }
    }

    /**
     * A zero percentage is legitimate, but it must still be emitted as the
     * number 0 rather than an empty string, otherwise `100 - percentage`
     * becomes NaN and the ring never paints.
     */
    public function test_zero_percentages_are_emitted_as_a_number(): void
    {
        $html = view('admin.socmed.overview', [
            'inProgressCount' => 0,
            'inProgressPercentage' => 0,
            'postedCount' => 0,
            'postedPercentage' => 0,
            'approvalCount' => 0,
            'approvalPercentage' => 0,
        ])->render();

        $this->assertMatchesRegularExpression(
            "/createProgressChart\('dynamicInProgressChart', 0, '#ffc107'\)/",
            $html
        );
        $this->assertStringNotContainsString('NaN', $html);
    }
}
