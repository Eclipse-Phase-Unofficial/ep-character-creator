Hi,

The following text is just for helping you installing the needed component for this Character creator :

On a web server you need 
-------------------------

- php 5.3 or greater (php.net)
- mySql 14.14 or greater (dev.mysql.com/downloads/)
- pdfLib 7.0.5 or greater (www.pdflib.com)

If you plane to run it locally for development or use I suggest  
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
	
You will need the "free" version of pdfLib (www.pdflib.com), unfortunately it put a watermark on the page,
for a "clean" version you have to pay of find a web hosting with the licence. (www.infomaniak.com)


Deployment
----------

1) create a MySql database
2) rune the script sql/FullDatabase.sql or FullDatabaseBig.sql
3) copy the package content on your web site or if you run it locally on your web-server folder.
4) IMPORTANT : Remove the "management" and sql folder if you are on a web server, if run locally, no need.
5) set the database setting on the php/config.ini
	databaseUser = "DATABASE USER NAME"
	databasePassword = "DATABASE USER PASSWORD"
	serverName = "NAME OF YOUR DATABASE SERVER" (generally "localhost" work fine)
	databaseName = "YOUR DATABASE NAME"
	databasePort = "THE DATABASE PORT" (for my sql generaly : 3306)
	
You are good to go.

If you make cool new development with this let us know :) and if you have questions I will try to answer it (no guarantee it depend of my disponibility)

game@oook.ch

