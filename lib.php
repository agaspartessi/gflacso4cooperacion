<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Returns the selected Boost preset.
 *
 * Order handled by Moodle:
 * 1. prescsscallback  -> pre.scss + Raw initial SCSS
 * 2. scss             -> Boost preset
 * 3. extrascsscallback -> post.scss + Raw SCSS
 *
 * @param theme_config $theme
 * @return string
 */
function theme_gflacso4cooperacion_get_main_scss_content($theme): string {
    global $CFG;

    $preset = get_config('theme_gflacso4cooperacion', 'preset') ?: 'default.scss';

    if ($preset === 'plain.scss') {
        $presetpath = $CFG->dirroot . '/theme/boost/scss/preset/plain.scss';
        return is_readable($presetpath) ? file_get_contents($presetpath) : '';
    }

    if ($preset === 'default.scss') {
        $presetpath = $CFG->dirroot . '/theme/boost/scss/preset/default.scss';
        return is_readable($presetpath) ? file_get_contents($presetpath) : '';
    }

    $fs = get_file_storage();
    $context = context_system::instance();

    $presetfile = $fs->get_file(
        $context->id,
        'theme_gflacso4cooperacion',
        'preset',
        0,
        '/',
        $preset
    );

    if ($presetfile) {
        return $presetfile->get_content();
    }

    $fallback = $CFG->dirroot . '/theme/boost/scss/preset/default.scss';
    return is_readable($fallback) ? file_get_contents($fallback) : '';
}

/**
 * Prepends SCSS before the Boost preset.
 * This is where variables should go.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_gflacso4cooperacion_get_pre_scss($theme): string {
    global $CFG;

    $scss = '';

    $prepath = $CFG->dirroot . '/theme/gflacso4cooperacion/scss/pre.scss';

    if (is_readable($prepath)) {
        $scss .= "\n/* === GFLACSO PRE.SCSS FILE === */\n";
        $scss .= file_get_contents($prepath) . "\n";
    } else {
        $scss .= "\n/* === GFLACSO PRE.SCSS NOT READABLE: {$prepath} === */\n";
    }

    $brandcolor = get_config('theme_gflacso4cooperacion', 'brandcolor');
    if (!empty($brandcolor)) {
        $scss .= "\n/* === GFLACSO BRAND COLOR FROM SETTINGS === */\n";
        $scss .= '$primary: ' . $brandcolor . ";\n";
    }

    $rawpre = get_config('theme_gflacso4cooperacion', 'scsspre');
    if (!empty($rawpre)) {
        $scss .= "\n/* === GFLACSO RAW INITIAL SCSS FROM DB === */\n";
        $scss .= $rawpre . "\n";
    }

    return $scss;
}

/**
 * Appends SCSS after the Boost preset.
 * This is where final theme rules and admin Raw SCSS should go.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_gflacso4cooperacion_get_extra_scss($theme): string {
    global $CFG;

    $scss = '';

    $postpath = $CFG->dirroot . '/theme/gflacso4cooperacion/scss/post.scss';

    if (is_readable($postpath)) {
        $scss .= "\n/* === GFLACSO POST.SCSS FILE === */\n";
        $scss .= file_get_contents($postpath) . "\n";
    } else {
        $scss .= "\n/* === GFLACSO POST.SCSS NOT READABLE: {$postpath} === */\n";
    }

    $rawpost = get_config('theme_gflacso4cooperacion', 'scss');
    if (!empty($rawpost)) {
        $scss .= "\n/* === GFLACSO RAW SCSS FROM DB === */\n";
        $scss .= $rawpost . "\n";
    }

    return $scss;
}