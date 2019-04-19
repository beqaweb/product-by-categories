# Installation

Please use docker-compose to install/deploy the app.

First of all, create a `app-data/products-by-categories` directory in your user home directory. Then add file named `env_file` in the that directory.

Being in the project directory, you can use:
`mkdir -p ~/app-data/products-by-categories && cp .env.example ~/app-data/products-by-categories/`. (FYI, I'm not sure this command are available on Windows OS)

Then open terminal, cd to the project directory and fire the following commands one after another:
1) `docker-compose up -d`
2) `docker exec products-by-categories-laravel ./docker-build/laravel-optimize.sh`

# Other info

By default, I've set the port to `5555`, so after docker compose the app will be available at http://localhost:5555. You can change the port in `docker-compose.yml` file (line 33).