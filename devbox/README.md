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
