<?php
 if ( ! defined( 'WPINC' ) ) {
	exit; // Exit if accessed directly
 }
/**
 * Traits for reusbale component and multiple inheritane 
 *
 * This class provide all CRUD method
 *
 * @since      1.0.0
 * @package    WP_Library
 * @subpackage WP_Library/includes
 * @author     MD Hiron Mia <hironmd647@gmail.com>
 */

trait WP_Library_CRUD {
   
    /**
     * Get a list of books from the database
     *
     * @param array $data Query parameters for fetching books
     * @return WP_REST_Response|WP_Error
     * 
     * @since   1.0.0
     */
    public function get_books( $data ){
        if( !isset( $data ) ){
            return new WP_Error('no_args', __('Args not fuond', 'wp-library'), ['status' => 500]);
        }

        global $wpdb;
        $table_name = $wpdb->prefix . TABLE_NAME;

        $search = isset($data['search']) ? '%' . $wpdb->esc_like($data['search']) . '%' : '';
        $page = isset($data['page']) ? intval($data['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
    
        $query = "SELECT * FROM " . $table_name . " WHERE 1=1 ORDER BY book_id DESC";
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

    /**
     * Get a a single data of a book from the database
     *
     * @param array $data Query parameters for fetching books
     * @return WP_REST_Response|WP_Error
     * 
     * @since   1.0.0
     */
    public function get_book( $data ){
        global $wpdb;
        $table_name = $wpdb->prefix . TABLE_NAME;
  
        $book = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE book_id = %d", $data['id'] ) );

        return rest_ensure_response( $book );

    }

    /**
     * Create a book on database
     *
     * @param array $request Query parameters for inserting book data
     * @return WP_REST_Response|WP_Error
     * 
     * @since   1.0.0
     */
    public function create_book( $request ){
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
            return new WP_REST_Response($wpdb->insert_id, 201);
        }

        return new WP_Error('cant_create', __('Cannot create book', 'wp-library'), ['status' => 500]);
    }

    /**
     * Update book data
     *
     * @param array $request Query parameters for update book data
     * @return WP_REST_Response|WP_Error
     * 
     * @since   1.0.0
     */
    public function update_book( $request ){
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
            return new WP_REST_Response(null, 204);
        }
    
        return new WP_Error('cant_update', __('Cannot update book', 'wp-library'), ['status' => 500]);
    }

    /**
     * Delete a book data from database
     *
     * @param array $data Query parameters for inserting book data
     * @return WP_REST_Response|WP_Error
     * 
     * @since   1.0.0
     */
    public function delete_book( $data ) {
        global $wpdb;
        $table_name = $wpdb->prefix . TABLE_NAME;

        $result = $wpdb->delete( $table_name, array( 'book_id' => $data['id'] ) );

        if ($result) {
            return new WP_REST_Response(null, 204);
        }

        return new WP_Error('cant_delete', __('Cannot delete book', 'wp-library'), ['status' => 500]);
    }

    /**
     * Permission check and nonce verify
     *
     * @param array $request Query parameters for security
     * @return NULL|WP_Error
     * 
     * @since   1.0.0
     */
    public function permission_check( $request ){

        if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
            return new WP_Error('rest_forbidden', esc_html__('Invalid nonce', 'wp-library'), ['status' => 403]);
        }

        return current_user_can( 'edit_posts' );
    }
}