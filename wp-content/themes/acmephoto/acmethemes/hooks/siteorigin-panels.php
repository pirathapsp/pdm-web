<?php
/**
 * Adds Acmephoto Theme Widgets in SiteOrigin Pagebuilder Tabs
 *
 * @since Acmephoto 1.2.0
 *
 * @param null
 * @return null
 *
 */
function acmephoto_widgets($widgets) {
    $theme_widgets = array(
        'acmephoto_author_widget'
    );
    foreach($theme_widgets as $theme_widget) {
        if( isset( $widgets[$theme_widget] ) ) {
            $widgets[$theme_widget]['groups'] = array('acmephoto');
            $widgets[$theme_widget]['icon']   = 'dashicons dashicons-screenoptions';
        }
    }
    return $widgets;
}
add_filter('siteorigin_panels_widgets', 'acmephoto_widgets');

/**
 * Add a tab for the theme widgets in the page builder
 *
 * @since Acmephoto 1.2.0
 *
 * @param null
 * @return null
 *
 */
function acmephoto_widgets_tab($tabs){
    $tabs[] = array(
        'title'  => __('AT Acmephoto Widgets', 'acmephoto'),
        'filter' => array(
            'groups' => array('acmephoto')
        )
    );
    return $tabs;
}
add_filter('siteorigin_panels_widget_dialog_tabs', 'acmephoto_widgets_tab', 20);