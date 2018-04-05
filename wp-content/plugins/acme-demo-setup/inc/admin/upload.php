<?php
/**
 * Acme Demo Setup
 *
 * @package Acme Themes
 * @subpackage Acme Demo Setup
 */
if( !class_exists( 'Acme_Demo_Setup') ):
    class Acme_Demo_Setup {
        /**
         * @return void
         */
        function __construct( ) {
            add_action( 'mime_types',             array( $this, 'mime_types' ) );
            add_action( 'admin_menu',             array( $this, 'menu' ) );
            /* enqueue script and style for about page */
            add_action( 'admin_enqueue_scripts', array( $this, 'style_and_scripts' ) );
            /*ajax callback for demo content installation*/
            add_action( 'wp_ajax_acme_demo_setup_ajax_setup', array( $this, 'handler' ) );

        }
        /**
         * Load css and scripts for the about page
         */
        public function style_and_scripts( $hook_suffix ) {
            if ( 'appearance_page_acme-demo-setup' == $hook_suffix ) {
                wp_enqueue_script( 'acme-demo-setup', ACME_DEMO_SETUP_URL.'inc/assets/js/acme-demo-setup.js', array( 'jquery' ) );
                wp_localize_script( 'acme-demo-setup', 'acme_demo_setup_object', array(
                    'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
                    'importing'                => esc_html__('Importing','acme-demo-setup'),
                    'imported'                 => esc_html__('Task Completed, view the log below','acme-demo-setup')
                ) );
            }
        }
        /**
         * @return array
         */
        function mime_types( $mimes ) {
            $add_mimes = array(
                'dat' => 'text/plain',
                'xml' => 'application/xml',
                'wie' => 'text/html'
            );

            return array_merge( $mimes, $add_mimes );
        }

        /**
         * Create admin pages in menu
         *
         * @return void
         */
        function menu() {
            add_theme_page( __( 'Acme Demo Setup', 'acme-demo-setup' ), __( 'Acme Demo Setup', 'acme-demo-setup' ), 'upload_files', 'acme-demo-setup', array( $this, 'screen' ) );
        }

        /**
         * The Admin Screen
         */
        function screen() {
            echo '<div class="wrap">';
            echo '<h2>' . __( 'Acme Demo Setup', 'acme-demo-setup' ) . '</h2>';
            $this->handler();
            $this->import_form();
            echo '</div>';
        }

        /**
         * Handle the demo content upload and called to process
         *
         * @return void
         */
        function handler() {
            $error = '';
            $required_files = array();
            if ( isset( $_FILES[ 'upload-zip-archive' ][ 'name' ] ) && ! empty( $_FILES[ 'upload-zip-archive' ][ 'name' ] ) ) {

                /*check for security*/
                if ( ! current_user_can( 'upload_files' ) ) {
                    wp_die( __( 'Sorry, you are not allowed to install demo on this site.', 'acme-demo-setup' ) );
                }
                check_admin_referer( 'acme-demo-setup' );

                /*file process*/
                esc_html_e('Uploading Zip...','acme-demo-import');
                echo "<br />";
                $upload_zip_archive = $_FILES[ 'upload-zip-archive' ];
                WP_Filesystem();
                global $wp_filesystem;
                $upload_dir = wp_upload_dir();
                $destination =  $upload_dir['basedir'] . '/acme-demo-importer/';

                /*the zip file shouldn't content greater than 3 files*/
                if( function_exists( 'ZipArchive')){
	                $za = new ZipArchive();
	                $za->open( $upload_zip_archive['tmp_name'] );
	                $file_on_zip =  $za->numFiles;
	                if( $file_on_zip > 3 ){
		                $error[] = __( "Invalid ZIP greater than 3 files" ,'acme-demo-importer');
	                }
                }

                /*unzip file*/
                esc_html_e('Unzipping File...','acme-demo-import');
                echo "<br />";
                $unzipfile = unzip_file( $upload_zip_archive['tmp_name'], $destination);
                if ( !$unzipfile ) {
                    $error[] = __( "Error on unzipping, Please try again" ,'acme-demo-importer');
                }

                /*get required file*/
                $dirlist = $wp_filesystem->dirlist($destination);
                foreach ( (array) $dirlist as $filename => $fileinfo ) {
                    $filetype = wp_check_filetype($filename);
	                $filetype_alternative = substr($filename, strrpos($filename, '.') + 1);
                    if( 'xml' == $filetype['ext'] || 'xml' == $filetype_alternative){
                        $required_files['xml'] = $destination.$filename;
                    }
                    elseif ('wie' == $filetype['ext'] || 'wie' == $filetype_alternative){
                        $required_files['wie'] = $destination.$filename;
                    }
                    elseif ( 'dat' == $filetype['ext'] || 'dat' == $filetype_alternative){
                        $required_files['dat'] = $destination.$filename;
                    }
                    else{
                        $error[] = sprintf( __( "Invalid ZIP destination file %s" ,'acme-demo-importer'),$destination.$filename );
                    }
                }

                /*prepare array of files to import*/
                if( !isset( $required_files['xml'] ) || empty( $required_files['xml'] )){
                    $error[] = __( "xml file not included" ,'acme-demo-importer');

                }
                if( !isset( $required_files['wie'] ) && empty( $required_files['wie'] )){
                    $error[] = __( "wie file not included" ,'acme-demo-importer');

                }
                if( !isset( $required_files['dat'] ) && empty( $required_files['dat'] )){
                    $error[] = __( "dat file not included" ,'acme-demo-importer');
                }
                if( is_array( $error ) && !empty( $error ) ){
                    foreach ( $error as $e ){
                        echo $e;
                        echo "<br />";
                    }
                }

                /*process import*/
                $this->import( $required_files , 0);

                /*delete demo files*/
	            $wp_filesystem->rmdir($destination, true );

	            exit;
            }
        }

        /*import */
	    function import( $required_files, $exit = 1 ){
		    /*before import*/
		    do_action( 'acme_demo_setup_before_import',$required_files );

		    /*xml demo import*/
		    if( isset( $required_files['xml'] ) && !empty( $required_files['xml'] ) ){
			    // Try to update PHP memory limit (so that it does not run out of it).
			    ini_set( 'memory_limit', apply_filters( 'acme_demo_setup_memory_limit', '50M' ) );

			    $xml_import = new Acme_Demo_Setup_Wp_Import();
			    $xml_import->fetch_attachments = true;
			    set_time_limit(0);
			    $xml_import -> import( $required_files['xml'] );

			    esc_html_e('Imported Demo Content xml...','acme-demo-import');
			    echo "<br /><br />";
		    }

		    /*customizer import*/
		    if( isset( $required_files['dat'] ) && !empty( $required_files['dat'] ) ){

			    $wie_import = new Acme_Demo_Setup_CEI_Core();
			    $wie_import -> _import( $required_files['dat'] );

			    esc_html_e('Imported Customizer Data...','acme-demo-import');
			    echo "<br /><br />";
		    }

		    /*widget import*/
		    if( isset( $required_files['dat'] ) && !empty( $required_files['dat'] ) ){

			    acme_demo_setup_wie_process_import_file( $required_files['wie'] );

			    esc_html_e('Imported Widget Data...','acme-demo-import');
			    echo "<br /><br />";
		    }

		    do_action( 'acme_demo_setup_after_import',$required_files );

		    printf( esc_html__('All Done Visit your %s site %s','acme-demo-import'),'<a href='.esc_url( get_home_url()).' target="_blank">','</a>' );
		    echo "<br /><br />";

		    if( $exit ){
			    exit;
		    }
	    }

        /**
         * The upload form
         *
         * @param null
         */
        function import_form( ) {
            echo '<form action="" method="post" enctype="multipart/form-data" id="acme-demo-setup-upload-zip-form">';
            echo '<h3 class="media-title">'. __( 'Upload a zip file containing demo content', 'acme-demo-setup' ) .'</h3>';
            echo '<p><input type="file" name="upload-zip-archive" id="upload-zip-archive" size="50" /></p>';
            echo '<p>'. sprintf( __( 'Maximum upload file size: %s','acme-demo-setup' ), size_format( wp_max_upload_size() ) ) .'</p>';
            wp_nonce_field( 'acme-demo-setup' );
            submit_button( __( 'Upload and Import', 'acme-demo-setup' ) );
            echo "<div id='at-demo-ajax-install-error' style='display: none;color: #d54e21'>".esc_html__('Select File and Try Again!','acme-demo-setup')."</div>";
            echo '</form>';
            echo "<span id='at-demo-ajax-install-result-loading' class='button button-primary' style='display: none'>".esc_html__('Importing...','acme-demo-setup')."<img src='".admin_url()."images/loading.gif' style='padding-left:15px'>"."</span>";
            echo "<h4 id='at-demo-ajax-install-result-title' style='display: none'>".esc_html__('Task Completed, view the log below','acme-demo-setup')."</h4>";
            echo "<h4 id='at-demo-ajax-install-result-error' style='display: none'>".sprintf( esc_html__('Some Error Occur While Importing Demo, Please contact %s theme support %s ','acme-demo-setup'),'<a href="https://www.acmethemes.com/supports/" target="_blank">','</a>' )."</h4>";
            echo "<div id='at-demo-ajax-install-result'></div>";
        }

    }//end class
endif;
$Acme_Demo_Setup = new Acme_Demo_Setup();
