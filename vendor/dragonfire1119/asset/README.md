A port of Laravel 3's Asset class. Made to work with Laravel 4. This was a fork from <https://github.com/teepluss/laravel4-asset> because it needed to be fixed were it's usable for Laravel 4.1.

#Laravel 4
Add to your ``composer.json`` file ``"require"``:

```
"dragonfire1119/asset": "dev-master"
```

Add the following to your ``app/config/app.php`` :

1. Service Provider array ``'Dragonfire1119\Asset\AssetServiceProvider',``
2. Aliases array ``'Asset' => 'Dragonfire1119\Asset\Facades\Asset',``

## Documentation
<http://three.laravel.com/docs/views/assets>

## Contributing

If you have a idea on making this better feel free to fork it.
