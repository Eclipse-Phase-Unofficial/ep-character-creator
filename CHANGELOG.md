# 1.51 Gate Jump (December 2018)

This releases was based on a move to Laravel, which affected almost every part of the application.

* PHP 7.2 is now required
* Now available using Docker
* Database has experienced major changes (added auto incrementing id's and re-named tables)
* Javascript and CSS are now built using webpack, and injected on page load.

Removed:
* Removed the ability to buy more than one Muse/Ai
* Players may not modify anything that they have not added to their character, and removing something from their character may (in the future will) remove any modifications to that item.

And many other changes under the hood.

Incomplete/ToDo Items:
* Move from the custom .ini file to using the Laravel configuration system.
* Implement CRUD functionality with a JSON API
* Move to a proper single page application that consumes JSON.
    The current version sends raw POST data and consumes HTML.
* Don't read the entire database into the current session
* Save the session information to the database so nothing is lost if the server is rebooted.  Laravel Feature.
* Use object IDs for database objects instead of their names.  Names are NOT guaranteed to be unique.
* Finish moving the database to a proper relational model using object IDs. Currently it is using names, and objects are not linked to each other.
* Prepare for Eclipse Phase Version 2

# 1.50 Gate Prep (May 2018)

Final release before moving to Laravel

# 1.49 Nano Seed (December 2016)

First version with named releases

# Internal 0.93 (April 2016)
# 1.2.1 (July 2015)
# 1.2.0 (September 2014)
# 1.1.0 (August 2014)
# 1.0.0 Original EPCC Sources (July 2014)