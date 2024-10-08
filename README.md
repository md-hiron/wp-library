# WP Library

A comprehensive WordPress plugin for managing a library system, including book records using custom SQL queries and a REST API. The plugin features advanced PHP techniques, caching, security, adherence to coding standards, efficient database querying, and modern front-end development using React and Tailwind CSS.

## Features

- Custom database table for storing book records
- CRUD operations for book records using custom SQL queries
- Caching of frequently accessed queries using WordPress Transients API
- Secure REST API with nonces, input validation, and sanitization
- Search functionality with pagination
- Modern front-end using React and Tailwind CSS

## Installation

### Prerequisites

- WordPress 5.0+
- Node.js and npm installed on your machine

### Step-by-Step Guide

1. **Clone or Download the Plugin**

    ```bash
    git clone https://github.com/md-hiron/wp-library.git
    ```

2. **Navigate to the Plugin Directory**

    ```bash
    cd wp-library
    ```

3. **Install PHP Dependencies**

    No external PHP dependencies are required.

4. **Install JavaScript Dependencies**

    ```bash
    npm install
    ```

5. **Build the Front-End Assets**

    For development:

    ```bash
    npm run dev
    ```

    For production:

    ```bash
    npm run build
    ```

6. **Activate the Plugin**

    - Zip the `wp-library` directory.
    - Go to the WordPress admin dashboard.
    - Navigate to `Plugins > Add New`.
    - Click on `Upload Plugin` and select the zipped file.
    - Install and activate the plugin.

7. **Database Setup**

    The plugin will automatically create the necessary database table upon activation.

## Usage

### REST API Endpoints

- **Get All Books**

    ```http
    GET /wp-json/library/v1/books
    ```

- **Get a Single Book**

    ```http
    GET /wp-json/library/v1/book/{id}
    ```

- **Create a Book**

    ```http
    POST /wp-json/library/v1/create_book
    ```

- **Update a Book**

    ```http
    PUT /wp-json/library/v1/books/{id}
    ```

- **Delete a Book**

    ```http
    DELETE /wp-json/library/v1/books/{id}
    ```

### Search and Pagination

- **Search Books**

    ```http
    GET /wp-json/library/v1/books?search={query}
    ```

- **Paginate Books**

    ```http
    GET /wp-json/library/v1/books?page={page_number}
    ```

### Front-End Integration

The front-end interface is built using React and styled with Tailwind CSS. It provides an intuitive UI for interacting with the library system, including search functionality and pagination.

### Front-End Usage

1. **Embed the React Component**

    To embed the React front-end component in a WordPress page or post, use a shortcode provided by the plugin:

    ```php
    [library_system]
    ```

2. **Customizing Styles**

    Tailwind CSS is used for styling. Customize the `src/index.css` file and rebuild the assets using the appropriate npm script (`npm run dev` or `npm run build`).

## Development

### Coding Standards

- Adhere to WordPress PHP coding standards.
- Use PHPDoc for inline comments and documentation.
- Implement a PHP trait for common functionality, such as sanitization and validation.

### Security

- Implement nonces for all REST API requests.
- Validate and sanitize all user inputs.
- Use prepared statements for all custom SQL queries to prevent SQL injection.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your changes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

For any issues or feature requests, please open an issue on the [GitHub repository](https://github.com/your-username/library-plugin).

