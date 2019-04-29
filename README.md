# Installation

Please use docker-compose to install/deploy the app.

First of all, create `app-data/products-by-categories` directory in your home directory. Then add file named `env_file` in the directory. Being in the project directory, you can use one-command solution:
`mkdir -p ~/app-data/products-by-categories && cp .env.example ~/app-data/products-by-categories/`. (FYI, I'm not sure the commands will work on Windows OS)

Then open terminal, cd to the project directory and fire the following commands one after another:
1) `docker-compose up -d`
2) `docker exec products-by-categories-laravel ./docker-build/laravel-optimize.sh`

# Usage

By default, the port is set to `5555`, so after building the app, it will be available at `http://localhost:5555`. You can change the port in `docker-compose.yml` file (line 33).

Also, you can access the REST API documentation at `http://localhost:5555/api/documentation`.