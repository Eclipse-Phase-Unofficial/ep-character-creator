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
[src/sql/init/FullDatabase.sql](https://github.com/EmperorArthur/ep-character-creator/blob/master/src/sql/FullDatabase.sql).

## Testing
You will need:

* php 5.3 or greater (php.net)
* sqlite3

1. Set up the sqlite database as explained in the Deployment section.
2. From a command prompt in the `src` directory run `php -S localhost:8080`
3. Browse to http://localhost:8080

### Testing with Docker

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

<a name="Deployment"></a>
## Deployment
You will need:

* php 5.3 or greater (php.net)
* mySql 14.14 or greater (dev.mysql.com/downloads/)


1. Ensure that the webserver is pointing to the src directory.
2. IMPORTANT : Remove the "management" and sql folders before making the site publicly accessable!
3. Set the Google Analytics Id.

#### MySql Database Setup (For publicly accessable websites)
1. create a MySql database

    ```mySql
    CREATE USER 'epcc_www'@'localhost' IDENTIFIED BY '0928sdGdsfa8#_+';
    GRANT ALL PRIVILEGES ON EclipsePhaseData.* TO 'epcc_www'@'localhost' WITH GRANT OPTION;
    CREATE DATABASE EclipsePhaseData;
    USE EclipsePhaseData;
    ```

2. Import the database

    ```
    mysql -h localhost -u epcc_www -p'0928sdGdsfa8#_+' EclipsePhaseData < sql/init/FullDatabase.sql
    ```
3. configure database access in php/config.ini

    ```ini
    databaseUser = "DATABASE USER NAME"
    databasePassword = "DATABASE USER PASSWORD"
    databasePDO = "mysql:dbname=<Database Name>;host=<DATABASE SERVER>("localhost" for local server);port=<Database Port> (for my sql generaly : 3306)"
    ```

#### Sqlite Database Setup (For local testing)
1. Create a sqlite database

    ```bash
    cat FullDatabase.sql | sed 's/\\n/ /g' | sqlite3 FullDatabase.sqlite3
    ```

3. configure database access in php/config.ini

    ```ini
    databasePDO = 'sqlite:../../../sql/init/FullDatabase.sqlite3'
    ````
