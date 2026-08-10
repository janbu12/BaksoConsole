<?php

namespace App\Domain\Rentals;

use App\Models\Unit;
use Illuminate\Support\Collection;

final class SmartPickRecommender
{
    /**
     * Evaluate a list of units against SmartPick criteria.
     */
    public static function evaluate(Collection $units, ?int $players = null, ?int $duration = null, ?float $budget = null, ?int $categoryId = null): Collection
    {
        return $units->map(function (Unit $unit) use ($players, $duration, $budget, $categoryId) {
            $matchPlayers = ! $players || $unit->max_players >= $players;
            
            $estPrice = $duration ? ($unit->daily_price * $duration) : $unit->daily_price;
            $matchBudget = ! $budget || $estPrice <= $budget;
            
            $matchCategory = ! $categoryId || $unit->categories->contains('id', $categoryId);
            $matchDuration = ! $duration || ($duration >= 1 && $duration <= 5);

            $criteriaCount = 0;
            $matchesCount = 0;

            if ($players) {
                $criteriaCount++;
                if ($matchPlayers) $matchesCount++;
            }
            if ($budget) {
                $criteriaCount++;
                if ($matchBudget) $matchesCount++;
            }
            if ($categoryId) {
                $criteriaCount++;
                if ($matchCategory) $matchesCount++;
            }
            if ($duration) {
                $criteriaCount++;
                if ($matchDuration) $matchesCount++;
            }

            $score = $criteriaCount > 0 ? round(($matchesCount / $criteriaCount) * 100) : 0;
            $isBestMatch = ($criteriaCount > 0 && $matchesCount === $criteriaCount && $unit->status->value === 'available');

            $unit->smart_pick = [
                'has_filter' => $criteriaCount > 0,
                'score' => $score,
                'is_best_match' => $isBestMatch,
                'est_price' => $estPrice,
                'match_players' => $matchPlayers,
                'match_budget' => $matchBudget,
                'match_category' => $matchCategory,
                'match_duration' => $matchDuration,
                'badges' => array_values(array_filter([
                    $players && $matchPlayers ? '✓ Sesuai ' . $players . ' Pemain' : null,
                    $duration && $matchDuration ? '✓ Sesuai Durasi ' . $duration . ' Hari' : null,
                    $budget && $matchBudget ? '✓ Sesuai Budget Rp' . number_format($budget, 0, ',', '.') : null,
                ])),
            ];

            return $unit;
        })->sortByDesc(fn ($u) => $u->smart_pick['score'] * 1000 + ($u->status->value === 'available' ? 500 : 0));
    }
}
