<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Read a stored Raw setting unless the active inherited callback chain supplies it.
 *
 * Moodle versions can execute parent and child callbacks together. Collecting the
 * active chain with this helper disabled lets us detect that case without assuming
 * which parent callback is installed. Only this helper is guarded: native parent
 * callbacks still execute normally. No theme settings or database values change.
 *
 * @param theme_config $theme The child theme.
 * @param string $setting scsspre or scss.
 * @return string Missing Raw code, or an empty string if already inherited.
 */
function theme_gflacso4cooperacion_get_missing_raw_scss($theme, string $setting): string {
    static $collecting = [];
    $key = spl_object_id($theme) . ':' . $setting;
    if (!empty($collecting[$key])) {
        return '';
    }

    $raw = (string)get_config('theme_gflacso4cooperacion', $setting);
    if ($raw === '') {
        return '';
    }

    $collecting[$key] = true;
    try {
        $inherited = $setting === 'scsspre'
            ? $theme->get_pre_scss_code()
            : $theme->get_extra_scss_code();
    } finally {
        unset($collecting[$key]);
    }

    return strpos($inherited, $raw) === false ? $raw . "\n" : '';
}

/**
 * Add stored Initial SCSS before this theme's !default variables.
 * @param theme_config $theme Theme configuration.
 * @return string
 */
function theme_gflacso4cooperacion_get_pre_scss($theme): string {
    return theme_gflacso4cooperacion_get_missing_raw_scss($theme, 'scsspre')
        . file_get_contents(__DIR__ . '/scss/pre.scss');
}

/**
 * Add stored final SCSS after post.scss, if the parent has not supplied it.
 * @param theme_config $theme Theme configuration.
 * @return string
 */
function theme_gflacso4cooperacion_get_extra_scss($theme): string {
    return theme_gflacso4cooperacion_get_missing_raw_scss($theme, 'scss');
}

/**
 * Return the selected Boost preset followed by Cooperación's own styles.
 *
 * Initial Raw SCSS precedes pre.scss and the preset; final Raw SCSS follows
 * post.scss. Child callbacks supply any Raw code missing from the parent chain.
 *
 * @param theme_config $theme Theme configuration.
 * @return string
 */
function theme_gflacso4cooperacion_get_main_scss_content($theme): string {
    global $CFG;

    $preset = $theme->settings->preset ?? 'default.scss';
    $scss = null;

    if (!in_array($preset, ['default.scss', 'plain.scss'], true) && !empty($preset)) {
        $fs = get_file_storage();
        $context = context_system::instance();
        $presetfile = $fs->get_file($context->id, 'theme_gflacso4cooperacion', 'preset', 0, '/', $preset);
        if ($presetfile) {
            $scss = $presetfile->get_content();
        }
    }

    if ($scss === null) {
        // In Moodle 5.3 dirroot already points to public; do not append public.
        $filename = $preset === 'plain.scss' ? 'plain.scss' : 'default.scss';
        $scss = file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/' . $filename);
    }

    // Boost 5.3's plain preset omits MDS tokens used by Moodle's core SCSS.
    if ($preset === 'plain.scss') {
        $scss = "@import \"design-system\";\n" . $scss;
    }

    return $scss . "\n" . file_get_contents(__DIR__ . '/scss/post.scss');
}
