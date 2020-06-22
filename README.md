![Meet Chirp, a free Twitter activity tracker](https://d1srrlgsf6kxjv.cloudfront.net/img/banner.jpg)

# Chirp

[Chirp](https://chirp.benjamincrozat.com) is a Laravel based app running on AWS Lambda. It also uses RDS, SQS and DynamoDB. I hope you'll be able to learn something from it. Also, I'm always thrilled to receive good critisism and PRs.

## Table of contents

- [Development](#development)
- [Testing](#testing)
- [Deployment](#deployment)

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

## Deployment

```bash
cp .env.production.example .env.production

make
```

## License

MIT
