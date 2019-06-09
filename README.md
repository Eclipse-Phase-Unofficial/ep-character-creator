# Eclipse Phase Character Creator

A web-based character creator application for the Eclipse Phase role-playing game.

* [Source](https://github.com/Eclipse-Phase-Unofficial/ep-character-creator)
* [Releases](https://github.com/Eclipse-Phase-Unofficial/ep-character-creator/releases)
* [Issues](https://github.com/Eclipse-Phase-Unofficial/ep-character-creator/issues)
* Websites that Host EPCC
    * [cd-net.net](https://epcc.cd-net.net/)

## License

This work is licensed under the **Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License**.

You can read the full license description [here](https://github.com/Eclipse-Phase-Unofficial/ep-character-creator/blob/master/LICENSE.txt).


## Authors
See [here](https://github.com/Eclipse-Phase-Unofficial/ep-character-creator/blob/master/Authors.md) for a list of everyone who has contributed to this project.

## Version
The current version is **1.51 Gate Jump**!  See the [Changelog](https://github.com/Eclipse-Phase-Unofficial/ep-character-creator/blob/master/CHANGELOG.md). 

## EPCC Configuration

There is a single configuration file for EPCC: [src/php/config.ini](https://github.com/EmperorArthur/ep-character-creator/blob/master/src/php/config.ini).
You will need to maintain a separate version of that file outside of this repository for your production environment.

The rest of the information (Eclipse Phase content) is stored in the database. There is a full SQL dump of the database in
[src/database/database.sql](https://github.com/EmperorArthur/ep-character-creator/blob/master/database/database.sql).

## Running via Docker

Docker is the recommended, and only officially supported method or running this app.
While the setup steps below can be used for development, it is **highly recommended** that deployment be done via Docker.

The following command builds an image of the applciation that is ready to be deployed.
```bash
docker image build --tag ep-character-creator .
```

The official docker image can be found [here](https://hub.docker.com/r/emperorarthur/ep-character-creator/)

In most cases the following command will allow you to test the build image locally.
It exposes a webserver on port 8080 that you can access to view the container.
```bash
docker container run --rm -it -p 8080:80 --name epcc ep-character-creator
```

If deploying to the internet, you may want to consider using your own '.env' file.
Doing so allows you to change things like the error reporting location, or almost any other configuration.
Simply add `--enf-file custom.env` to the run command above.

##  Local Development and Testing
You will need:

* [php 7.2 or greater](https://php.net)
* Either: [mySql 14.14 or greater](https://dev.mysql.com/downloads/)
* Or: [sqlite3](https://www.sqlite.org/download.html) (Recommended)

### Database Setup
#### SQLite:
1. Create a sqlite3 database
    ```bash
    sed 's/\\n/ /g' database.sql > tmp.sql
    sqlite3 --init tmp.sql database.sqlite
    ```
3. Copy `standalone.env` to `.env`.

#### MySql
1. create a MySql database
    ```mySql
    CREATE USER 'epcc_www'@'localhost' IDENTIFIED BY '$DATABASE_PASSWORD';
    GRANT ALL PRIVILEGES ON EclipsePhaseData.* TO 'epcc_www'@'localhost' WITH GRANT OPTION;
    CREATE DATABASE EclipsePhaseData;
    USE EclipsePhaseData;
    ```
2. Import the database
    ```
    mysql -h localhost -u epcc_www -p'$DATABASE_PASSWORD' EclipsePhaseData < database/database.sql
    ```
3. Configure database access in your custom `.env` file.  
See [here](https://laravel.com/docs/5.6/database#configuration) for how to do that.

### Saving database changes
#### SQLite:
To save changes made to the Sqlite database run:
```bash
echo -e ".once database.sql\n.dump"|sqlite3 database.sqlite
```
WARNING:  If you use this feature, skip the `sed` step when creating the database.

### Using the built in php web server
1. Set up the database.
2. From a command prompt in the top level of this project run `php artisan serve`
3. Browse to http://localhost:8080
