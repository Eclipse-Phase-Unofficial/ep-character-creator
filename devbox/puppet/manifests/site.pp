node 'epcc.local' {

  # apt-get update before any packages are installed/updated
  exec { "apt-update":
    command => "/usr/bin/apt-get update",
    timeout => 0
  }
  Exec["apt-update"] -> Package <| |>

  class { 'lamp::params':} # need this to fix scope issues
  class { 'lamp':
    disable_default_sites => true,
    install_phpinfo_file => false
  }

  class { 'epcc':
    lamp_package => $lamp::params::lamp_package,
    apache_service => $lamp::params::apache_service
  }

  file { ['/etc/php5/apache2/conf.d/01-testing.ini', '/etc/php5/cli/conf.d/01-testing.ini']:
    ensure => present,
    owner => root,
    group => root,
    mode => 644,
    source => "puppet:///modules/testing/php/testing.ini",
    require => $lamp::params::lamp_package,
    notify => $lamp::params::apache_service
  }

}