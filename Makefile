deploy:
	@echo "Running tests before deploying…"

	# Sometimes, I don't know why, the Chrome driver gets too old.
	php artisan dusk:chrome-driver

	composer test

	@echo "Installing and compiling dependencies for production…"

	composer install -n --no-dev --no-suggest

	yes | cp -rf .env .env.local
	yes | cp -rf .env.production .env

	php artisan event:cache
	php artisan route:cache
	php artisan view:cache

	yarn && yarn prod

	@echo "Deploying on AWS…"

	aws s3 sync public s3://chirp.benjamincrozat.com \
	    --acl public-read \
	    --exclude "*.DS_Store" \
	    --exclude "*.gitignore" \
	    --exclude "*.gitkeep" \
	    --exclude "*.php" \
	    --exclude "*.txt" \
	    --exclude "*.xml" \
	    --no-follow-symlinks

	aws cloudfront create-invalidation \
	    --distribution-id E35GTCXG8QLGI4 \
	    --paths /\*

	serverless deploy --stage prod

	vendor/bin/bref cli chirp-prod-artisan --region=eu-west-3 -- migrate --force

	@echo "Cleaning up…"

	yes | cp -rf .env .env.production
	yes | cp -rf .env.local .env

	composer install -n --no-suggest

	php artisan event:clear
	php artisan route:clear
	php artisan view:clear
