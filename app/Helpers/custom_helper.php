<?php
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$precision}f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
}
