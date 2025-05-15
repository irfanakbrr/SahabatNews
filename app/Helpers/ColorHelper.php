<?php

namespace App\Helpers;

class ColorHelper
{
    /**
     * Determine if a color is light or dark.
     *
     * @param string $color Hex or RGB/RGBA color string.
     * @return bool True if light, false if dark.
     */
    public static function isLight(string $color): bool
    {
        $color = str_replace('#', '', $color);
        if (strlen($color) == 3) {
            $color = str_repeat(substr($color,0,1), 2) . str_repeat(substr($color,1,1), 2) . str_repeat(substr($color,2,1), 2);
        }

        if (preg_match('/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/', $color, $matches)) {
            $r = (int)$matches[1];
            $g = (int)$matches[2];
            $b = (int)$matches[3];
        } elseif (preg_match('/^rgba\((\d+),\s*(\d+),\s*(\d+),\s*([\d\.]+)\)$/', $color, $matches)) {
            // For RGBA, we can consider alpha, but for simplicity, we'll base it on RGB part
            $r = (int)$matches[1];
            $g = (int)$matches[2];
            $b = (int)$matches[3];
        } elseif (strlen($color) == 6) {
            $r = hexdec(substr($color, 0, 2));
            $g = hexdec(substr($color, 2, 4));
            $b = hexdec(substr($color, 4, 6));
        } else {
            return true; // Default to light if format is unknown
        }

        // Calculate luminance (standard formula)
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        return $luminance > 0.5;
    }
} 