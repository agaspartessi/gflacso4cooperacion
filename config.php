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
$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->hidefromselector = false;
$THEME->haseditswitch = true;
$THEME->removedprimarynavitems = ['home'];

// Read this theme's stored Raw settings explicitly, retaining native inheritance.
$THEME->prescsscallback = 'theme_gflacso4cooperacion_get_pre_scss';
$THEME->extrascsscallback = 'theme_gflacso4cooperacion_get_extra_scss';

// post.scss precedes the final Raw settings. The callbacks avoid adding Raw twice.
$THEME->scss = function($theme) {
    return theme_gflacso4cooperacion_get_main_scss_content($theme);
};
