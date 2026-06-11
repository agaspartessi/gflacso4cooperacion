<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Builds the main SCSS content for the gflacso4cooperacion theme.
 *
 * Important order:
 * 1. theme scss/pre.scss
 * 2. Raw initial SCSS from theme settings
 * 3. Boost preset
 * 4. theme scss/post.scss
 * 5. Raw SCSS from theme settings
 *
 * This order allows pre.scss to define Bootstrap/Moodle variables before
 * the Boost preset is compiled.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_gflacso4cooperacion_get_main_scss_content($theme): string {
    global $CFG;

    $output = '';

    /*
     * 1. Theme pre.scss.
     * Must be loaded before the Boost preset, otherwise variables with !default
     * will arrive too late and will not affect Bootstrap/Moodle compilation.
     */
    $prepath = $CFG->dirroot . '/theme/gflacso4cooperacion/scss/pre.scss';

    if (is_readable($prepath)) {
        $output .= "\n/* === GFLACSO PRE.SCSS FILE === */\n";
        $output .= file_get_contents($prepath) . "\n";
    } else {
        $output .= "\n/* === GFLACSO PRE.SCSS NOT READABLE: " . $prepath . " === */\n";
    }

    /*
     * 2. Raw initial SCSS from admin settings.
     * This is placed after the file-based pre.scss so you can temporarily
     * override/test variables from the Moodle UI.
     */
    $rawpre = get_config('theme_gflacso4cooperacion', 'scsspre');

    if (!empty($rawpre)) {
        $output .= "\n/* === GFLACSO RAW INITIAL SCSS FROM DB === */\n";
        $output .= $rawpre . "\n";
    }

    /*
     * 3. Main Boost preset.
     * Respect the selected preset if the theme settings define one.
     */
    $preset = 'default.scss';

    if (!empty($theme->settings->preset)) {
        $preset = $theme->settings->preset;
    } else {
        $configuredpreset = get_config('theme_gflacso4cooperacion', 'preset');
        if (!empty($configuredpreset)) {
            $preset = $configuredpreset;
        }
    }

    $fs = get_file_storage();
    $context = context_system::instance();

    if ($preset === 'plain.scss') {
        $presetpath = $CFG->dirroot . '/theme/boost/scss/preset/plain.scss';

        if (is_readable($presetpath)) {
            $output .= "\n/* === BOOST PLAIN PRESET === */\n";
            $output .= file_get_contents($presetpath) . "\n";
        }

    } else if ($preset === 'default.scss') {
        $presetpath = $CFG->dirroot . '/theme/boost/scss/preset/default.scss';

        if (is_readable($presetpath)) {
            $output .= "\n/* === BOOST DEFAULT PRESET === */\n";
            $output .= file_get_contents($presetpath) . "\n";
        }

    } else {
        $presetfile = $fs->get_file(
            $context->id,
            'theme_gflacso4cooperacion',
            'preset',
            0,
            '/',
            $preset
        );

        if ($presetfile) {
            $output .= "\n/* === GFLACSO UPLOADED PRESET: " . $preset . " === */\n";
            $output .= $presetfile->get_content() . "\n";
        } else {
            $presetpath = $CFG->dirroot . '/theme/boost/scss/preset/default.scss';

            if (is_readable($presetpath)) {
                $output .= "\n/* === BOOST FALLBACK DEFAULT PRESET === */\n";
                $output .= file_get_contents($presetpath) . "\n";
            }
        }
    }

    /*
     * 4. Theme post.scss.
     * This should contain final theme rules. In your current testing workflow
     * you can keep it empty or minimal and continue using Raw SCSS.
     */
    $postpath = $CFG->dirroot . '/theme/gflacso4cooperacion/scss/post.scss';

    if (is_readable($postpath)) {
        $output .= "\n/* === GFLACSO POST.SCSS FILE === */\n";
        $output .= file_get_contents($postpath) . "\n";
    } else {
        $output .= "\n/* === GFLACSO POST.SCSS NOT READABLE: " . $postpath . " === */\n";
    }

    /*
     * 5. Raw SCSS from admin settings.
     * This goes at the end so it can be used for safe testing/overrides.
     */
    $rawpost = get_config('theme_gflacso4cooperacion', 'scss');

    if (!empty($rawpost)) {
        $output .= "\n/* === GFLACSO RAW SCSS FROM DB === */\n";
        $output .= $rawpost . "\n";
    }

    return $output;
}