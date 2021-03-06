# Cronner

I created a Cronner application for importing data from csv files into the database. 

## Architectural Notebook

My goal was to create a tested and maintainable code.

My solution is quite simple, but a real process can require the following steps as well:
* The Partner uploads the CSV file using an admin interface
* The file is uploaded to an upload server, and a queue element is created for Cronner
* The Cronner application takes a task from a queue
    * Inserts logs for the whole process (helps for debugging)
    * Gets and read the file
    * Validates the data
        * Notify relevant parties if any significant error has occourred
    * Insert the valid data
    * Sets the queue element as completed

## Database

The database is MySQL, and the following file describes the definitions of the tables: /database/init.sql 

## Install the Application

To run the application in development, you have to use `docker-compose` to run the app with `docker`:

```bash
docker-compose up -d
```

Run this command from CLI mode of the docker-alpine container from the directory in which you want to install the application.

```bash
composer install
```

## Run unit tests

Run this command from CLI mode of the docker-alpine container to run the unit tests.

```bash
composer test
```

## Run unit tests and show code coverage

Run this command from CLI mode of the docker-alpine container to run the unit tests with code coverage displaying.

```bash
composer display-coverage
```

## Run unit tests and write code coverage to html

Run this command from CLI mode of the docker-alpine container to run the unit tests and write code coverage to html.

```bash
composer html-coverage
```

## Usage

### Multi Insert Version

This behaviour is available through http://localhost:8080

### Load Data DEV Version

This behaviour is available through http://localhost:8080/loadfile.php . The partners have to be represented and the product table should be empty in the database. 

## Possible improvements
* Introduce validation for description value using regular expression or DOM Parser
* Introduce Load Data Solution and replace the Multi Insert Version, because this Load Data version would be the most optimized considering running time. 
Based on my research the mysql operations would be 10x time faster. Some overhead is required (CSV writing) but probably the entire benefit would be significant.