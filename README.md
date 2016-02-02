# Eclipse Phase Character Creator

A web-based character creator application for the Eclipse Phase role-playing game.

* Websites that Host EPCC
    * [next-loop.com](http://eclipsephase.next-loop.com/)
* [Source](https://github.com/rbewley4/ep-character-creator)
* [Releases](https://github.com/rbewley4/ep-character-creator/releases)
* [Issues](https://github.com/rbewley4/ep-character-creator/issues)


## License

This work is licensed under the **Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License**.

You can read the full license description [here](https://github.com/rbewley4/ep-character-creator/blob/master/LICENSE.txt).

### Derivative Work

You can download the original source code for EPCC V 1.0 (03.2014) from
[GitHub](https://github.com/rbewley4/ep-character-creator/releases/tag/v1.0.0).

You may also view the README file that came with the original work
[here](https://github.com/rbewley4/ep-character-creator/blob/master/OookReadme.txt).


## EPCC Configuration

There is a single configuration file for EPCC: [src/php/config.ini](https://github.com/rbewley4/ep-character-creator/blob/master/src/php/config.ini).
You will need to maintain a separate version of that file outside of this repository for your production environment.

The rest of the information (Eclipse Phase content) is stored in the database. There is a full SQL dump of the database in
[src/sql/FullDatabase.sql](https://github.com/rbewley4/ep-character-creator/blob/master/src/sql/FullDatabase.sql).


## Development VM

I have configured a development virtual machine, using Vagrant and Puppet, that you can use to quickly get up and running.
It is a standard LAMP stack running on Ubuntu 12.04.


### Prerequisites

Before initializing the VM, you will need to install:

- [Vagrant](http://www.vagrantup.com/) v1.5
- [Virtual Box](https://www.virtualbox.org/) v4.3
- [vagrant-hostsupdater](https://github.com/cogitatio/vagrant-hostsupdater) v0.0.11 (optional)


### Setup

You can use a terminal to setup the VM with a few commands:

```bash
cd ep-character-creator/
vagrant up
```

If you installed the vagrant-hostsupdater plugin, you will be prompted for a password at then end of the VM provisioning
phase, so that Vagrant can update your `/etc/hosts` file.

> FYI: setup typically takes less than 10 minutes.


#### VM Configuration

If you want to change usernames and passwords that are used to access the database, you can edit
[devbox/config/epcc.yaml](https://github.com/rbewley4/ep-character-creator/blob/master/devbox/config/epcc.yaml)
before running `vagrant up`.

> Warning: DO NOT use these passwords on your production system.


### Testing

You can access the web server on the VM in your browser:

- **http://epcc.local/** (if you installed the vagrant-hostsupdater plugin)
- **http://192.168.123.45/**


#### Code

The `src/` directory is shared with the VM, so your code changes will show up automatically on the web server.


#### Logs

You can find web server logs (Apache and PHP) on the VM in `/var/log/apache2/`:

* `epcc_access.log`
* `epcc_error.log`
