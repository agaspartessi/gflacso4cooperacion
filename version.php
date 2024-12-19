<?php
// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// This is the version of the plugin.
$plugin->version = 2024121900; // Fecha en formato YYYYMMDDXX (sin comillas)

// This is the version of Moodle this plugin requires.
$plugin->requires = 2021051700; // Ajusta según tu versión mínima requerida

// This is the component name of the plugin - it always starts with 'theme_'.
// It should match the name of the folder.
$plugin->component = 'theme_gflacso4cooperacion';

// This is a list of plugins, this plugin depends on (and their versions).
$plugin->dependencies = [
    'theme_boost' => 2021051700 // Ajusta según la versión de Boost instalada
];

$plugin->maturity = MATURITY_STABLE; // Nivel de madurez (puede ser ALPHA, BETA, RC o STABLE)
$plugin->release = '1.0'; // Versión del tema
