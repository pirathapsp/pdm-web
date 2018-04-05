<?php
/**
 * Load frameworks.
 */
/*customizer export import*/
require_once( ACME_DEMO_SETUP_PATH . 'inc/frameworks/customizer-export-import/class-cei-option.php' );
require_once( ACME_DEMO_SETUP_PATH . 'inc/frameworks/customizer-export-import/class-cei-core.php' );

/*widget importer exporter*/
require_once( ACME_DEMO_SETUP_PATH . 'inc/frameworks/widget-importer-exporter/widgets.php' );
require_once( ACME_DEMO_SETUP_PATH . 'inc/frameworks/widget-importer-exporter/import.php' );

/*WordPress Importer*/
require_once( ACME_DEMO_SETUP_PATH . 'inc/frameworks/wordpress-importer/wordpress-importer.php' );

/*plugin hooks*/
require_once( ACME_DEMO_SETUP_PATH . 'inc/hooks/before-demo-setup.php' );
require_once( ACME_DEMO_SETUP_PATH . 'inc/hooks/after-demo-setup.php' );

/*upload screen*/
require_once( ACME_DEMO_SETUP_PATH . 'inc/admin/upload.php' );