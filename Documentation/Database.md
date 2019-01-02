# The Database

The Eclipse Phase Character Creator requries a relational database to work.
It will work with any database that PHP has a [PDO module](https://secure.php.net/manual/en/book.pdo.php) for.
Since, the creator never writes to the database, even NoSQL will work.

Instructions to set up the database can be found in the [README](../README.md#Deployment) file.

It's often convenient to use `sqlite` to test the database.  However, `MySQL` will not simply accept an `sqlite` dump file.  Some modifications are needed.

First, you must delete these two lines from the top of the file:
```text
PRAGMA foreign_keys=OFF;  
BEGIN TRANSACTION;
```
Then run these two commands:
```bash
sed -i 's/INSERT INTO \"\([A-z]*\)\"/INSERT INTO \1/' database.sql  
sed -i 's/CREATE TABLE `\([a-Z]*\)`/DROP TABLE IF EXISTS `\1`;\n\0/' database.sql
```

The first command removes the quotes from around the table name on the INSERT statements.
The second command drops tables if they exist, since otherwise MySQL complains about creating tables that already exist.
