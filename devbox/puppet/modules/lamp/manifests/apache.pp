class lamp::apache {
  define disable_site (
    $site_name = $title,
    $apache_service = $lamp::params::apache_service,
    $lamp_package = $lamp::params::lamp_package
  ) {
    exec {"apache-disable-site-${site_name}":
      command  => "/usr/sbin/a2dissite ${site_name}",
      notify => $apache_service,
      require => $lamp_package,
      onlyif => "/usr/bin/test -f /etc/apache2/sites-enabled/${site_name}.conf"
    }
  }

  define enable_site (
    $site_name = $title,
    $install_conf = false,
    $conf_source = false,
    $lamp_package = $lamp::params::lamp_package,
    $apache_service = $lamp::params::apache_service
  ) {

    if $install_conf {
      file { "/etc/apache2/sites-available/${site_name}.conf":
        ensure => file,
        source => $conf_source,
        mode   => 0644,
        owner  => 'root',
        group  => 'root',
        before => Exec["apache-enable-site-${site_name}"],
        require => $lamp_package,
        notify => $apache_service
      }
    }

    exec {"apache-enable-site-${site_name}":
      command  => "/usr/sbin/a2ensite ${site_name}",
      notify => $apache_service,
      require => $lamp_package,
      creates => "/etc/apache2/sites-enabled/${site_name}.conf"
    }
  }
}