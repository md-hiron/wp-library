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
	 * Creating REST API endpoint 
	 *  
	 * @since    1.0.0
	 */
    private $table_name;

    public function __construct(){
        global $wpdb;
        $this->table_name = $wpdb->prefix . TABLE_NAME;
    }

    public function register_rest_routes(){
        register_rest_route( 'library/v1', '/books', array(
            'methods' => 'GET',
            'callback' => array( self::class, 'get_books' )
        ));

        register_rest_route( 'library/v1', '/book/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array( self::class, 'get_book' )
        ));

        register_rest_route( 'library/v1', '/create_book', array(
            'methods' => 'POST',
            'callback' => array( self::class, 'create_book' ),
            'permission_callback' => array( self::class, 'permission_check' )
        ));

        register_rest_route('library/v1', '/books/(?P<id>\d+)', [
            'methods' => 'PUT',
            'callback' => [self::class, 'update_book'],
            'permission_callback' => [self::class, 'permission_check']
        ]);

        register_rest_route('library/v1', '/books/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [self::class, 'delete_book'],
            'permission_callback' => [self::class, 'permission_check']
        ]);
    }

    public static function get_books( $data ){
        global $wpdb;
        $table_name = $wpdb->prefix . TABLE_NAME;

        $search = isset($data['search']) ? '%' . $wpdb->esc_like($data['search']) . '%' : '';
        $page = isset($data['page']) ? intval($data['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
    
        $query = "SELECT * FROM " . $table_name . " WHERE 1=1";
        if ($search) {
            $query .= $wpdb->prepare(" AND (title LIKE %s OR author LIKE %s OR isbn LIKE %s)", $search, $search, $search);
        }
        $query .= $wpdb->prepare(" LIMIT %d OFFSET %d", $limit, $offset);

        // Build the query for counting total books
        $count_query = "SELECT COUNT(*) FROM " . $table_name . " WHERE 1=1";
        if ($search) {
            $count_query .= $wpdb->prepare(" AND (title LIKE %s OR author LIKE %s OR isbn LIKE %s)", $search, $search, $search);
        }

        // Get the total number of matching records
        $total_books = $wpdb->get_var($count_query);
        $total_pages = ceil($total_books / $limit);
        $books = $wpdb->get_results( $query );

        $response = [
            'books' => $books,
            'total_books' => $total_books,
            'total_pages' => $total_pages,
            'current_page' => $page,
            'per_page' => $limit
        ];

        return new WP_REST_Response($response, 200);
    }

    public static function get_book( $data ){
        global $wpdb;
        $table_name = $wpdb->prefix . TABLE_NAME;
  
        $book = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE book_id = %d", $data['id'] ) );

        return rest_ensure_response( $book );

    }

    public static function create_book( $request ){
        global $wpdb;
        $table_name = $wpdb->prefix . TABLE_NAME;
        $data = $request->get_params();
    
        $result = $wpdb->insert( $table_name, array(
            'title' => sanitize_text_field( $data['title'] ),
            'author' => sanitize_text_field( $data['author'] ),
            'publisher' => sanitize_text_field( $data['publisher'] ),
            'isbn' => sanitize_text_field( $data['isbn'] ),
            'publication_date' =>  sanitize_text_field( $data['publication_date'] ),
        ) );

        if ($result) {
            delete_transient('books');
            return new WP_REST_Response($wpdb->insert_id, 201);
        }

        return new WP_Error('cant_create', __('Cannot create book', 'wp-library'), ['status' => 500]);
    }

    public static function update_book( $request ){
        global $wpdb;
        $table_name = $wpdb->prefix . TABLE_NAME;
        $data = $request->get_params();

        $result = $wpdb->update($table_name, [
            'title' => sanitize_text_field($data['title']),
            'author' => sanitize_text_field($data['author']),
            'publisher' => sanitize_text_field($data['publisher']),
            'isbn' => sanitize_text_field($data['isbn']),
            'publication_date' => sanitize_text_field($data['publication_date']),
        ], ['book_id' => $data['book_id']]);
    
        if ($result !== false) {
            delete_transient('books');
            return new WP_REST_Response(null, 204);
        }
    
        return new WP_Error('cant_update', __('Cannot update book', 'wp-library'), ['status' => 500]);
    }

    public static function delete_book( $data ) {
        global $wpdb;
        $table_name = $wpdb->prefix . TABLE_NAME;

        $result = $wpdb->delete( $table_name, array( 'book_id' => $data['id'] ) );

        if ($result) {
            delete_transient('books');
            return new WP_REST_Response(null, 204);
        }

        return new WP_Error('cant_delete', __('Cannot delete book', 'wp-library'), ['status' => 500]);
    }

    public static function permission_check( $request ){

        if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
            return new WP_Error('rest_forbidden', esc_html__('Invalid nonce', 'wp-library'), ['status' => 403]);
        }

        return current_user_can( 'edit_posts' );
    }

}