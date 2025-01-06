# Localization

Cashier uses some translation strings, for example the cycle definition when an order item is created. These strings are
provided in english by default, but you're able to translate them to your language using laravel's localization feature.
Publish the translation files using `php artisan vendor:publish --tag=cashier-translations`. The files will appear in
`resources/lang/vendor/cashier/en`. You can now add a translation for your own language by creating a folder with the translation
files for your language in `resources/lang/vendor/cashier`. If your applications locale is set to that language, cashier will
automatically use your translations for your application.
