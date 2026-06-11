<?php
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/lib.php');

$THEME->name = 'gflacso4cooperacion';
$THEME->parents = ['boost'];

$THEME->sheets = [];
$THEME->editor_sheets = [];
$THEME->yuicssmodules = [];

$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->enable_dock = false;
$THEME->hidefromselector = false;
$THEME->haseditswitch = true;

$THEME->removedprimarynavitems = ['home'];

$THEME->scss = function($theme) {
    return theme_gflacso4cooperacion_get_main_scss_content($theme);
};


