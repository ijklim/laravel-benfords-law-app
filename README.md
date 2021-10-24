# Benford's Law App

## Installation

```sh
git clone https://github.com/ijklim/laravel-benfords-law-app.git

cd laravel-benfords-law-app
cp .env.example .env
# Configure docker settings in `.env`
# Change DOCKER_WEB_PORT_NUMBER to another port number if 80 is already used

php composer.phar install
```

## Access Application

```sh
# Start docker machine, take note of ip address of docker instance (e.g. 192.168.99.101)

# Start container from root of project folder where `docker-compose.yml` is located
docker-composer up

# Access site using docker ip address, including port number if it is not 80
```