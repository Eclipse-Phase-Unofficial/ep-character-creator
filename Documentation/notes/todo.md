* Have Analytics track if users are actually using site features
* Add a survey to see what users want
* New user assistance:
  * Add tutorial
  * Add explanations for what each item means
* Site layout:
  * Change layout to be more user friendly
  * (possibly) Convert to using a top menu bar instead of the buttons on the right
 * Improve mobile layout
* Improve site responsiveness
  * Add/Remove check marks in real time.
  * Display information about something by hovering over it
    * Working on this by adding a `description` tag to the `li` element, but need to make sure that everything is escaped properly

# Backend

* Rework save system to only save crucial data
  * [x] Clean up the Database
  * [x] Set up foreign keys in DB properly
  * [ ] Link models to each other via the pivot tables (with the extra info)
  * [ ] Use automated tools to clean up Models
  * [ ] Clean up EPListProvider to use models directly for Skill Prefix. (SQL injection vlun!)
  * [ ] Change EPAtoms to rely on Models
    * [ ] Set EPSkill's linked Aptitude to a getter/setter
  * [ ] Burn EPListProvider and Database.php to the ground (Requires Frontend Work)

* [ ] Fix Infos in DB ()
* [ ] Add 2nd database only for EP1 data
* [ ] Set up Migrations (low priority)

* [ ] Remove Duplicate Ego/Morph Traits
  * Prior to the DB cleanup, a Trait could not affect an ego and a morph at the same time.
  * Even after the cleanup, need to make sure the BonusMalus works correctly! 
