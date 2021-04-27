![Meet Chirp, a free Twitter activity tracker](https://user-images.githubusercontent.com/3613731/116201789-2ffd4000-a73a-11eb-816f-8e4c30181812.jpg)

# Chirp

A free Twitter activity tracker based on Laravel.

## Screenshot

![Screenshot](https://user-images.githubusercontent.com/3613731/116202257-a8fc9780-a73a-11eb-8868-daf341adb000.png)

## Development

To send requests to the Twitter API, you have to have an approved developer account: https://developer.twitter.com

Then, run the project locally on whatever environment you prefer. I personnaly love the simplicity of Laravel Valet.

```bash
composer install

cp .env.example .env

php artisan key:generate
php artisan migrate

yarn
yarn dev
```

## Testing

Few notes about my testing strategy:
- The "Sign in with Twitter" button on the home page is tested with Dusk;
- Twitter's API isn't mocked. I can't be confident about Chirp's stability if I can't hit the real stuff. To avoid rate limit errors, I just cache the responses and flush it whenever I think it's right.

```bash
composer test
```

## License

[WTFPL]()
