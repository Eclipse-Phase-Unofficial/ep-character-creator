# lamp server configuration for ubuntu only
class lamp (
  $disable_default_sites = false,
  $install_phpinfo_file = true,
  $lamp_package_name = $lamp::params::lamp_package_name,
  $lamp_package = $lamp::params::lamp_package,
  $apache_service_name = $lamp::params::apache_service_name,
  $apache_service = $lamp::params::apache_service,
  $mysql_root_password,
  $mysql_root_user = 'root'
){

  package { $lamp_package_name:
    ensure => "latest"
  }

  service { $apache_service_name:
    ensure => "running",
    enable => "true",
    require => $lamp_package
  }

  if $install_phpinfo_file {
    file { "/var/www/info.php":
      ensure => "present",
      content => "<?php phpinfo();?>",
      owner => "root",
      group => "root",
      mode => 644,
      require => $lamp_package
    }
  }

  if $disable_default_sites {
    lamp::apache::disable_site{"000-default":}
    lamp::apache::disable_site{"default-ssl":}
  }

  lamp::mysql::set_root_password{$mysql_root_password:}

  package { ['php5-dev', 'php-pear', 'php5-json']:
    ensure => latest,
    require => $lamp_package,
    notify => $apache_service
  }

}