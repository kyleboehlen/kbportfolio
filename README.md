# KBPortfolio

This Laravel application is my personal portfolio site for my resume, software projects, and photography.

I switched to developing on macOS and moved the production instance to AWS. I'll let you determine whether that is an improvement

## Local Development (Docker)

### Install WSL2

If on Windows verify you are doing the setup with WSL2. This is the bash shell you should be using, and also the Docker driver you should be using.

If it is not installed you can do so by simply running the following command in powershell:

`wsl --install -d "Ubuntu-20.04"`

### Docker Desktop

Make sure that you have [Docker Desktop](https://www.docker.com/products/docker-desktop) installed and running

### Clone Repo

Clone the codebase, preferably somewhere in your home directory

`git clone https://github.com/kyleboehlen/kbportfolio.git`

You'll then need to create your .env file by copying the example env file

`cp .env.example.local .env`

### Sail Setup

The rest of the instructions use the `sail` command. You should alias this command to the following:

`./vendor/bin/sail`

Before starting the Docker container install the composer packages, once you have the container running you may want to run a composer update if any of the local path packages didn't install correctly

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
Start the container in detached mode

`sail up -d`

Generate an application key

`sail artisan key:generate`

And fill out the missing variables in the .env file

- LOG_DISCORD_WEBHOOK_URL=""

Once it's up you can migrate and seed the database

`sail artisan migrate && sail artisan db:seed`

And set an admin password for the admin panel login, the username is the email specified in the .env file

`sail artisan admin:password`

You'll also need to generate the assets

`sail npm run dev`

Lastly, you'll need to go to `localhost:9000` to access the MinIO console

Login using `sail` as the username and `password` as the password and create a bucket named `local` with a Public access policy

You'll then need to sync the static assets with

`sail artisan assets:sync-static`

## Production (AWS Elastic Beanstalk)

The CodePipeline for the AWS EB istance is set to track the master branch, just push to that branch and add the commented out vars in `.env.production` to the EB enviroment properties.

You'll need to generate an `APP_KEY` elsewhere otherwise the build process would change it every time.

Be sure to enable CloudWatch because it's set up to stream the laravel log files to CloudWatch.

You're good to go👍