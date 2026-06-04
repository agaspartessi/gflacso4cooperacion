<?php

// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.

// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();

// We will add callbacks here as we add features to our theme.

function theme_gflacso4cooperacion_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';

    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();
    $context = context_system::instance();

    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_gflacso4cooperacion', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    $pre = file_get_contents($CFG->dirroot . '/theme/gflacso4cooperacion/scss/pre.scss');
    $post = file_get_contents($CFG->dirroot . '/theme/gflacso4cooperacion/scss/post.scss');

    return $pre . "\n" . $scss . "\n" . $post;
}

function theme_gflacso4cooperacion_get_pre_scss($theme) {
    $scss = '';

    $brandcolor = get_config('theme_gflacso4cooperacion', 'brandcolor');
    if (!empty($brandcolor)) {
        $scss .= '$primary: ' . $brandcolor . ";\n";
        $scss .= '$brand-primary: ' . $brandcolor . ";\n";
    }

    $rawpre = get_config('theme_gflacso4cooperacion', 'scsspre');
    if (!empty($rawpre)) {
        $scss .= "\n" . $rawpre . "\n";
    }

    return $scss;
}

function theme_gflacso4cooperacion_get_extra_scss($theme) {
    $rawscss = get_config('theme_gflacso4cooperacion', 'scss');

    if (!empty($rawscss)) {
        return "\n" . $rawscss . "\n";
    }

    return '';
}
