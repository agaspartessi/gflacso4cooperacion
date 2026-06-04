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
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.                      
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');                                        
    } else if ($filename == 'plain.scss') {                                                                                         
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.                      
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');                                          
                                                                                                                                    
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_gflacso4cooperacion', 'preset', 0, '/', $filename))) {              
        // This preset file was fetched from the file area for theme_gflacso4cooperacion and not theme_boost (see the line above).                
        $scss .= $presetfile->get_content();                                                                                        
    } else {                                                                                                                        
        // Safety fallback - maybe new installs etc.                                                                                
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');                                        
    }          
    
     // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.                                        
     $pre = file_get_contents($CFG->dirroot . '/theme/gflacso4cooperacion/scss/pre.scss');                                                         
     // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.                                    
     $post = file_get_contents($CFG->dirroot . '/theme/gflacso4cooperacion/scss/post.scss');     
                 
     // Combine them together.                                                                                                       
     return $pre . "\n" . $scss . "\n" . $post;                                                                                                                  
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