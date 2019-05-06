# Installation

Please use docker-compose to install/deploy the app.

Right after cloning the repo, first of all, create `~/app-data/products-by-categories` directory. You can use one-command solution:
`mkdir -p ~/app-data/products-by-categories`.

Then open terminal, `cd` to the project directory and fire the following commands one after another:
1) `docker-compose up -d`
2) `docker exec products-by-categories-laravel ./docker-build/laravel-optimize.sh`

# Usage

By default, I've set the port to `5555`, so after docker compose the app will be available at http://localhost:5555. You can change the port in `docker-compose.yml` file (line 33).

---

*Note: The first user registered will automatically be assigned the `Super admin` role*