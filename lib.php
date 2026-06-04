<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Devuelve SOLO el preset SCSS principal.
 * Moodle ensambla pre + main + extra por separado.
 */
function theme_gflacso4cooperacion_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();
    $context = context_system::instance();

    if ($filename === 'default.scss') {
        $scss = file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename === 'plain.scss') {
        $scss = file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file(
        $context->id, 'theme_gflacso4cooperacion', 'preset', 0, '/', $filename
    ))) {
        $scss = $presetfile->get_content();
    } else {
        // Fallback al preset default de Boost.
        $scss = file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    return $scss;
}

/**
 * SCSS que va ANTES del preset (variables, brandcolor, pre.scss, scsspre del admin).
 * Este es el lugar correcto para variables que el preset necesita.
 */
function theme_gflacso4cooperacion_get_pre_scss($theme) {
    global $CFG;

    $scss = '';

    // 1. Brandcolor → variables Bootstrap/Moodle.
    if (!empty($theme->settings->brandcolor)) {
        $brandcolor = $theme->settings->brandcolor;
        $scss .= '$primary: ' . $brandcolor . " !default;\n";
        $scss .= '$brand-primary: ' . $brandcolor . " !default;\n";
    }

    // 2. Raw SCSS "pre" ingresado por el admin en la UI.
    if (!empty($theme->settings->scsspre)) {
        $scss .= "\n" . $theme->settings->scsspre . "\n";
    }

    // 3. Archivo pre.scss del theme (variables propias, mixins, etc.).
    $prepath = $CFG->dirroot . '/theme/gflacso4cooperacion/scss/pre.scss';
    if (is_readable($prepath)) {
        $scss .= "\n" . file_get_contents($prepath) . "\n";
    }

    return $scss;
}

/**
 * SCSS que va DESPUÉS del preset (post.scss del theme + Raw SCSS del admin).
 * Aquí van los overrides finales. El Raw SCSS del admin pisa todo lo anterior.
 */
function theme_gflacso4cooperacion_get_extra_scss($theme) {
    global $CFG;

    $scss = '';

    // 1. Archivo post.scss del theme.
    $postpath = $CFG->dirroot . '/theme/gflacso4cooperacion/scss/post.scss';
    if (is_readable($postpath)) {
        $scss .= file_get_contents($postpath) . "\n";
    }

    // 2. Raw SCSS ingresado por el admin en la UI (pisa todo lo anterior).
    if (!empty($theme->settings->scss)) {
        $scss .= "\n" . $theme->settings->scss . "\n";
    }

    return $scss;
}