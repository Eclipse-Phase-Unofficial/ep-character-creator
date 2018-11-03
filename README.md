# Eclipse Phase Character Creator

A web-based character creator application for the Eclipse Phase role-playing game.

* [Source](https://github.com/EmperorArthur/ep-character-creator)
* [Releases](https://github.com/EmperorArthur/ep-character-creator/releases)
* [Issues](https://github.com/EmperorArthur/ep-character-creator/issues)
* Websites that Host EPCC
    * [next-loop.com](http://eclipsephase.next-loop.com/)
    * [cd-net.net](https://www.cd-net.net/ep-character-creator/)

## License

This work is licensed under the **Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License**.

You can read the full license description [here](https://github.com/EmperorArthur/ep-character-creator/blob/master/LICENSE.txt).

### Derivative Work

You can download the original source code for EPCC V 1.0 (03.2014) from
[GitHub](https://github.com/rbewley4/ep-character-creator/releases/tag/v1.0.0).


## EPCC Configuration

There is a single configuration file for EPCC: [src/php/config.ini](https://github.com/EmperorArthur/ep-character-creator/blob/master/src/php/config.ini).
You will need to maintain a separate version of that file outside of this repository for your production environment.

The rest of the information (Eclipse Phase content) is stored in the database. There is a full SQL dump of the database in
[src/database/database.sql](https://github.com/EmperorArthur/ep-character-creator/blob/master/database/database.sql).

## Setup
You will need:

* [php 7.2 or greater](https://php.net)
* Either: [mySql 14.14 or greater](https://dev.mysql.com/downloads/)
* Or: [sqlite3](https://www.sqlite.org/download.html)

### Database Setup
#### SQLite:
1. Create a sqlite3 database
    ```bash
    sed 's/\\n/ /g' database.sql > tmp.sql
    sqlite3 --init tmp.sql database.sqlite3
    ```
3. configure database access in php/config.ini
    ```ini
    databasePDO = 'sqlite:../../../database/database.sqlite3'
    ````

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
3. configure database access in php/config.ini
    ```
    databaseUser = "epcc_www"
    databasePassword = "$DATABASE_PASSWORD"
    databasePDO = "mysql:dbname=<Database Name>;host=<DATABASE SERVER>("localhost" for local server);port=<Database Port> (for my sql generaly : 3306)"
    ```

### Saving database changes
#### SQLite:
To save changes made to the Sqlite database run:
```bash
echo -e ".once database.sql\n.dump"|sqlite3 database.sqlite
```
WARNING:  If you use this feature, skip the `sed` step when creating the database.


## Testing
### Using the built in php web server
1. Set up the database.
2. From a command prompt in the `src` directory run `php -S localhost:8080`
3. Browse to http://localhost:8080

### Using Docker-Compose

If you have [Docker](https://www.docker.com/) installed, you can use the `docker-compose` command to run EPCC:

```bash
cd ep-character-creator/
docker-compose up
```

This will host EPCC at [http://localhost:8080](http://localhost:8080).

The first time you run the command, it will take a few minutes complete, but subsequent runs will be very fast.

If you experience an issue with EPCC not rendering properly, then the database is probably still being seeded.
Look at the console output, and wait for *mysqld* to start accepting connections. The line should look like this: 

```
epcc-db    | 2017-09-03T00:41:29.240576Z 0 [Note] mysqld: ready for connections.
```

In the event that the database needs to be updated, run the command `docker volume rm epcharactercreator_epcc-db-data` to delete the database.
Docker will automatically re-build it on next run

## Deployment
1. Ensure that the web server is pointing to the src directory.
2. IMPORTANT : Remove the "management" and sql folders before making the site publicly accessible!
3. Set the Google Analytics Id.

### Deployment via Docker (Recommended)
Run `docker image build -f Standalone.Dockerfile .`

If you would like a pre-build image, see [here](https://hub.docker.com/r/emperorarthur/ep-character-creator/).
