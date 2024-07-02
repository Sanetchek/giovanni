Installation
---------------

### Quick Start

1. Search and replace `giovanni` to your theme name `mega-theme`


### Setup

To start using all the tools that come with `giovanni`  you need to install the necessary Node.js and Composer dependencies :

```sh
$ composer install
$ npm install
$ npm run start
```

### Available CLI commands

`giovanni` comes packed with CLI commands tailored for WordPress theme development :

- `composer lint:wpcs` : checks all PHP files against [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
- `composer lint:php` : checks all PHP files for syntax errors.
- `composer make-pot` : generates a .pot file in the `languages/` directory.
- `npm run compile:rtl` : generates an RTL stylesheet.
- `minify:js` : "gulp minify-js",
- `lint:js` : checks all JavaScript files against [JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/).
- `lint:css` : checks all CSS files against [CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/).
- `compile:rtl` : "rtlcss style-rtl.css style.css",
- `start` : run all commands from `gulpfile.js`

Now you're ready to go! The next step is easy to say, but harder to do: make an awesome WordPress theme. :)

Good luck!
