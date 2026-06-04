<?php

// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.

// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();

// We will add callbacks here as we add features to our theme.

function theme_gflacso4cooperacion_get_main_scss_content($theme) {
    global $CFG;

    // 1) Raw initial SCSS desde la configuración del theme.
    // Esto entra antes de todo. Sirve especialmente para variables.
    $scsspre = '';

    if (!empty($theme->settings->brandcolor)) {
        $brandcolor = $theme->settings->brandcolor;
        $scsspre .= '$primary: ' . $brandcolor . " !default;\n";
        $scsspre .= '$brand-primary: ' . $brandcolor . " !default;\n";
    }

    if (!empty($theme->settings->scsspre)) {
        $scsspre .= "\n" . $theme->settings->scsspre . "\n";
    }

    // 2) Archivo pre.scss propio del theme.
    $pre = '';
    $prepath = $CFG->dirroot . '/theme/gflacso4cooperacion/scss/pre.scss';

    if (is_readable($prepath)) {
        $pre = file_get_contents($prepath);
    }

    // 3) Preset principal: Boost default/plain o preset subido al theme.
    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();
    $context = context_system::instance();

    if ($filename === 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename === 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_gflacso4cooperacion', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    // 4) Archivo post.scss propio del theme.
    $post = '';
    $postpath = $CFG->dirroot . '/theme/gflacso4cooperacion/scss/post.scss';

    if (is_readable($postpath)) {
        $post = file_get_contents($postpath);
    }

    // 5) Raw SCSS desde la configuración del theme.
    // Esto entra AL FINAL, después de post.scss, por lo que debería pisar todo.
    $scsspost = '';

    if (!empty($theme->settings->scss)) {
        $scsspost .= "\n" . $theme->settings->scss . "\n";
    }

    return $scsspre . "\n" . $pre . "\n" . $scss . "\n" . $post . "\n" . $scsspost;
}

/**
 * Returns SCSS to prepend before the main SCSS.
 * This loads the theme setting theme_gflacso4cooperacion/scsspre.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_gflacso4cooperacion_get_pre_scss($theme) {
    $scss = '';

    // Map the brand colour setting to Bootstrap/Moodle variables.
    $configurable = [
        'brandcolor' => ['primary', 'brand-primary'],
    ];

    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }

        foreach ((array)$targets as $target) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }
    }

    if (!empty($theme->settings->scsspre)) {
        $scss .= "\n" . $theme->settings->scsspre . "\n";
    }

    return $scss;
}

/**
 * Returns SCSS to append after the main SCSS.
 * This loads the theme setting theme_gflacso4cooperacion/scss.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_gflacso4cooperacion_get_extra_scss($theme) {
    return !empty($theme->settings->scss) ? $theme->settings->scss : '';
}