# plugin-verona

## Features
- **Custom Database Table:** Adds a `books_info` table with the columns `ID`, `post_id`, and `isbn` to the WordPress database upon plugin activation.
- **Custom Post Type:** Registers a "Book" custom post type, including taxonomies for "Publisher" and "Authors".
- **Meta Box for ISBN:** A custom Meta Box in the "Book" post type allows entry of an ISBN number, which is then saved to the `books_info` table.
- **Admin Display:** The plugin provides an admin page to display the contents of the `books_info` table, using the `WP_List_Table` class for efficient data management.
- **Internationalization Support:** The plugin is designed to support language files for easy internationalization.
- **Security:** All user inputs and outputs are sanitized to meet WordPress security standards.
- **WordPress Coding Standards:** The plugin adheres to WordPress coding standards and best practices.
-  **Multilingual Compatibility:** The plugin is compatible with multilingual sites, following standard internationalization practices.
