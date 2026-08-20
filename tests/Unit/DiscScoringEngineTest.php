<?php

namespace Tests\Unit;

use App\Services\DiscScoringEngine;
use Database\Seeders\DiscMasterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscScoringEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_normalization_and_profile_classification(): void
    {
        $this->seed(DiscMasterSeeder::class);

        $engine = new DiscScoringEngine();

        $rawMost = ['D' => 8, 'I' => 7, 'S' => 4, 'C' => 3, '*' => 2];
        $rawLeast = ['D' => 2, 'I' => 5, 'S' => 8, 'C' => 5, '*' => 4];
        $rawChange = [
            'D' => $rawMost['D'] - $rawLeast['D'], // 6
            'I' => $rawMost['I'] - $rawLeast['I'], // 2
            'S' => $rawMost['S'] - $rawLeast['S'], // -4
            'C' => $rawMost['C'] - $rawLeast['C'], // -2
        ];

        $normG1 = $engine->normalizeGraph1($rawMost);
        $normG2 = $engine->normalizeGraph2($rawLeast);
        $normG3 = $engine->normalizeGraph3($rawChange);

        $this->assertIsArray($normG1);
        $this->assertIsArray($normG2);
        $this->assertIsArray($normG3);

        $this->assertArrayHasKey('D', $normG1);
        $this->assertArrayHasKey('I', $normG2);
        $this->assertArrayHasKey('S', $normG3);

        $profile = $engine->classifyProfile($normG3, $normG1);
        $this->assertNotEmpty($profile['code']);
        $this->assertNotEmpty($profile['name']);
        $this->assertNotEmpty($profile['interpretation']['description']);
    }
}
