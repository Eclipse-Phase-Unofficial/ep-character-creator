Hi,

The following text is just for helping you installing the needed component for this Character creator :

On a web server you need
-------------------------

- php 5.3 or greater (php.net)
- mySql 14.14 or greater (dev.mysql.com/downloads/)

If you plan to run it locally for development or use I suggest
-------------------------------------------------------------

Windows :
	- Xamp (www.apachefriends.org)
	- NetBean php (netbeans.org)

Mac OSx :
	- Mamp (www.mamp.info)
	- Coda2 (panic.com) (NetBean work fine to)

Linux :
	- apache and php package (apt-get)
	- NetBean php (netbeans.org)


Deployment
----------
1) copy the package content on your web site or if you run it locally on your web-server folder.
2) IMPORTANT : Remove the "management" and sql folder if you are on a web server, if run locally, no need.

Deployment (MySql)
----------
1) create a MySql database
    a) CREATE USER 'epcc_www'@'localhost' IDENTIFIED BY '0928sdGdsfa8#_+';
    b) GRANT ALL PRIVILEGES ON *.* TO 'epcc_www'@'localhost' WITH GRANT OPTION;
    c) CREATE DATABASE EclipsePhaseData;
    d) USE EclipsePhaseData;
2) run the script sql/FullDatabase.sql or FullDatabaseBig.sql
3) set the database setting on the php/config.ini
	databaseUser = "DATABASE USER NAME"
	databasePassword = "DATABASE USER PASSWORD"
	databasePDO = "mysql:dbname=<Database Name>;host=<DATABASE SERVER>("localhost" for local server);port=<Database Port> (for my sql generaly : 3306)"

Deployment (Sqlite)
----------
1) Create a sqlite database
	cat FullDatabase.sql | sed 's/\\n/ /g' | sqlite3 FullDatabase.sqlite3
2) set the database setting on the php/config.ini
	databasePDO = 'sqlite:../../../sql/FullDatabase.sqlite3'

You are good to go.

If you make cool new development with this let us know :)
If you have questions I will try to answer it (no guarantee it depend of my disponibility)

game@oook.ch
Arthur.Moore.git@cd-net.net
