<?php
 if ( ! defined( 'WPINC' ) ) {
	exit; // Exit if accessed directly
 }
/**
 * Creating REST API endpoint 
 *
 * This class create REST API endpoint for CRUD
 *
 * @since      1.0.0
 * @package    WP_Library
 * @subpackage WP_Library/includes
 * @author     MD Hiron Mia <hironmd647@gmail.com>
 */

class WP_Library_API {

    /**
     * All CRUD callback are comming from WP_Library_CRUD train
     * 
     * @since 1.0.0
     */
    use WP_Library_CRUD;


    /**
	 * Creating REST API endpoint 
	 *  
	 * @since    1.0.0
	 */
    private $table_name;

    
    /**
     * Contructor for WP_Library_API
     * 
     * @since   1.0.0
     */
    public function __construct(){
        global $wpdb;
        $this->table_name = $wpdb->prefix . TABLE_NAME;
    }

    /**
     * All custom routes are registered here
     * 
     * @since  1.0.0
     */
    public function register_rest_routes(){
        /**
         * Endpoint for get all book list with pagination and search paramater
         */
        register_rest_route( 'library/v1', '/books', array(
            'methods' => 'GET',
            'callback' => array( $this, 'get_books' )
        ));

        /**
         * Endpoint for get a single book data from database
         */
        register_rest_route( 'library/v1', '/book/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array( $this, 'get_book' )
        ));

        /**
         * Endpoint for create book on database
         */
        register_rest_route( 'library/v1', '/create_book', array(
            'methods' => 'POST',
            'callback' => array( $this, 'create_book' ),
            'permission_callback' => array( $this, 'permission_check' )
        ));

        /**
         * Endpoint for update book on databae
         */
        register_rest_route('library/v1', '/books/(?P<id>\d+)', [
            'methods' => 'PUT',
            'callback' => [$this, 'update_book'],
            'permission_callback' => [$this, 'permission_check']
        ]);

        /**
         * Endpoint for delete book from databae
         */
        register_rest_route('library/v1', '/books/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'delete_book'],
            'permission_callback' => [$this, 'permission_check']
        ]);
    }
}