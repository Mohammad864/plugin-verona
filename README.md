# plugin-verona

## Acknowledgements

This plugin was developed as part of an assignment project. Special thanks to the WordPress community for their comprehensive documentation and support.
Based on [Rabbit Framework](https://github.com/veronalabs/rabbit)

## Features
- **Custom Database Table:** Adds a `books_info` table with the columns `ID`, `post_id`, and `isbn` to the WordPress database upon plugin activation.
- **Custom Post Type:** Registers a "Book" custom post type, including taxonomies for "Publisher" and "Authors".
- **Meta Box for ISBN:** A custom Meta Box in the "Book" post type allows entry of an ISBN number, which is then saved to the `books_info` table.
- **Admin Display:** The plugin provides an admin page to display the contents of the `books_info` table, using the `WP_List_Table` class for efficient data management.
- **Internationalization Support:** The plugin is designed to support language files for easy internationalization.
- **Security:** All user inputs and outputs are sanitized to meet WordPress security standards.
- **WordPress Coding Standards:** The plugin adheres to WordPress coding standards and best practices.
-  **Multilingual Compatibility:** The plugin is compatible with multilingual sites, following standard internationalization practices.



## Requirements

1. PHP 7.4 or higher.
2. Composer

## Usage

```bash
composer require veronalabs/plugin
```

## Development

If you are planning to add style to your plugin, make sure you have the following requirements:
```bash
node.js: <= v14.16.0
npm: <= 6.14.11
```

And run these commands:

**Install packages**
```bash
npm install
```

**Run the start command**
```bash
npm start
// or
npm run start
```

### Commands

```
"compile:scss" : Compiles scss files
"postcss:autoprefixer": Parses your CSS and adds vendor prefixes
"dev": Runs "compile:scss" and "postcss:autoprefixer" in a sequence
"watch": Watches for changes in the /assets/src/scss/ folder and run "dev" command on every change
"start": Runs "dev" and "watch" commands concurrently
```


