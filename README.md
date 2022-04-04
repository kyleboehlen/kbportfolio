# KBPortfolio

This Laravel application is my personal portfolio site for my resume, software projects, and photography.

It's set up to use Docker (Laravel Sail) in development and Digital Ocean's App Platform in production while leveraging Spaces (S3 bucket) for assets.

## Local Development (Docker)

### Install WSL2

If on Windows verify you are doing the setup with WSL2. This is the bash shell you should be using, and also the Docker driver you should be using.

If it is not installed you can do so by simply running the following command in powershell:

`wsl --install -d "Ubuntu-20.04"`

Then make sure that you have [Docker Desktop](https://www.docker.com/products/docker-desktop) installed and running

Clone the codebase, preferably somewhere in your home directory

`git clone https://github.com/kyleboehlen/kbportfolio.git`

You'll then need to create your .env file by copying the example env file

`cp .env.example.local .env`

The rest of the instructions use the `sail` command. You should alias this command to the following:

`./vendor/bin/sail`

Generate an application key

`sail artisan key:generate`

And fill out the missing variables in the .env file

- PAPERTRAIL_PORT=
- LOG_DISCORD_WEBHOOK_URL=""

Before starting the Docker container install the composer packages, once you have the container running you may want to run a composer update if any of the local path packages didn't install correctly

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

Finally go ahead and start the container

`sail up -d`

Once it's up you can migrate and seed the database

`sail artisan migrate && sail artisan db:seed`

And set an admin password for the admin panel login, the username is the email specified in the .env file

`sail artisan admin:password`

You'll also need to generate the assets

`sail npm run dev`

Lastly, you'll need to go to `localhost:9000` to access the MinIO console

Login using sail as the username and password as the password and create a bucket named `local` with a Public access policy

## Production (Digital Ocean App Platform)

The digital ocean app platform is set up to track the master branch, use the .env.example.production file to create the enviroment variables for the application.

You'll need to generate an `APP_KEY` elsewhere otherwise the build process would change it every time.

A managed database instance is also needed, in the DB .env variables be sure to replace `YOUR_DATABASE_COMPONENT` with the name of your managed DB

You'll also need to get the `PAPERTRAIL_PORT` from your papertrail account, and the `LOG_DISCORD_WEBHOOK_URL` from the integrations setting page in your discord server

You'll also need to create a S3 compatible space for assets, generate a space API key in the Digital Ocean web console to fill out `DO_ACCESS_KEY_ID` and `DO_SECRET_ACCESS_KEY`, as well as replace `YOUR_REGION` with whatever region the space is in

`DO_BUCKET` is simply the name of the space component

Once you set the build command to 

`npm install && npm run prod`

And the run command to 

```
php artisan migrate --force &&
php artisan db:seed --force &&
php artisan config:cache &&
php artisan route:cache &&
php artisan assets:sync-static &&
php artisan assets:purge-deleted &&
heroku-php-apache2 public/
```

You're good to go👍