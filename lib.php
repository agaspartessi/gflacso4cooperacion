<?php
defined('MOODLE_INTERNAL') || die();

function theme_gflacso4cooperacion_get_main_scss_content($theme): string {
    global $CFG;

    $scss = '';

    // Boost base.
    $boostpreset = $CFG->dirroot . '/theme/boost/scss/preset/default.scss';
    $scss .= file_get_contents($boostpreset);

    // Tu SCSS propio.
    $post = $CFG->dirroot . '/theme/gflacso4cooperacion/scss/post.scss';
    if (is_readable($post)) {
        $scss .= "\n" . file_get_contents($post);
    }

    return $scss;
}