# CoCart Benchmark Performance

📢 This is a [must-use plugin](https://wordpress.org/support/article/must-use-plugins/). Manual uploading of this plugin is required.

It's designed to prevent any plugin (with the exception of CoCart and WooCommerce) that is not whitelisted or not a CoCart add-on or WooCommerce extension from loading when making a REST API request to increase performance.

> Support is not provided for this. Use at your own risk.

## Whitelist a Plugin

If you wish to whitelist a plugin you can add it like so.

```php
add_filter( 'cocart_benchmark_whitelist_plugins', function( $plugins ) ) {
    $plugins[] = 'amazon-s3-and-cloudfront/wordpress-s3.php';

    return $plugins;
}
```

## License

Released under [The MIT License](https://mit-license.org/).

## Credits

CoCart Benchmark Performance is developed and maintained by [Sébastien Dumont](https://github.com/seb86).

---

[sebastiendumont.com](https://sebastiendumont.com) &nbsp;&middot;&nbsp;
GitHub [@seb86](https://github.com/seb86) &nbsp;&middot;&nbsp;
Twitter [@sebd86](https://twitter.com/sebd86)

<p align="center">
    <img src="https://raw.githubusercontent.com/seb86/my-open-source-readme-template/master/a-sebastien-dumont-production.png" width="353">
</p>