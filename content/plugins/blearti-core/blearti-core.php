<?php
/**
 * Plugin Name: Blearti Core
 * Description: Core functionality plugin for Blearti21 WordPress project.
 * Version: 0.1.0
 * Author: Blearti21
 * License: GPL-2.0-or-later
 */

if (!defined('ABSPATH')) {
    exit;
}

// Autoload simple includes (no Composer here)
foreach (glob(__DIR__ . '/includes/*.php') as $includeFile) {
    require_once $includeFile;
}
