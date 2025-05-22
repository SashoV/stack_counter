<?php

namespace App\Services;

class BookStackCalculationService
{
    /**
     * Calculate the number of visible stacks around the NxN matrix.
     *
     * @param array $matrix NxN matrix of integers (height values).
     * @return int
     */
    public function calculateVisibleStacks(array $matrix): int
    {
        $n = count($matrix);
        $visible = [];

        // Left to right
        for ($i = 0; $i < $n; $i++) {
            $maxHeight = -1;
            for ($j = 0; $j < $n; $j++) {
                $height = $matrix[$i][$j];
                if ($height > $maxHeight && $height > 0) {
                    $visible["$i,$j"] = true;
                    $maxHeight = $height;
                }
            }
        }

        // Right to left
        for ($i = 0; $i < $n; $i++) {
            $maxHeight = -1;
            for ($j = $n - 1; $j >= 0; $j--) {
                $height = $matrix[$i][$j];
                if ($height > $maxHeight && $height > 0) {
                    $visible["$i,$j"] = true;
                    $maxHeight = $height;
                }
            }
        }

        // Top to bottom
        for ($j = 0; $j < $n; $j++) {
            $maxHeight = -1;
            for ($i = 0; $i < $n; $i++) {
                $height = $matrix[$i][$j];
                if ($height > $maxHeight && $height > 0) {
                    $visible["$i,$j"] = true;
                    $maxHeight = $height;
                }
            }
        }

        // Bottom to top
        for ($j = 0; $j < $n; $j++) {
            $maxHeight = -1;
            for ($i = $n - 1; $i >= 0; $i--) {
                $height = $matrix[$i][$j];
                if ($height > $maxHeight && $height > 0) {
                    $visible["$i,$j"] = true;
                    $maxHeight = $height;
                }
            }
        }

        return count($visible);
    }
}
