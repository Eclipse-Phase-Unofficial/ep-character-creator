class epcc(
  $database_name,
  $database_user,
  $database_password,
  $database_root_user,
  $database_root_password,
  $database_sql_file,
  $lamp_package,
  $apache_service
) {

  lamp::apache::enable_site{"epcc":
    install_conf => true,
    conf_source => "puppet:///modules/epcc/apache2/epcc.conf"
  }

  lamp::mysql::create_database{$database_name:
    user => $database_root_user,
    password => $database_root_password,
    run_sql => true,
    sql_file_path => $database_sql_file
  }

  lamp::mysql::create_user{"${database_user}@%":
    user => $database_user,
    password => $database_password,
    db_name => $database_name,
    mysql_root_user => $database_root_user,
    mysql_root_password => $database_root_password,
    host => '%'
  }

  lamp::mysql::create_user{"${database_user}@localhost":
    user => $database_user,
    password => $database_password,
    db_name => $database_name,
    mysql_root_user => $database_root_user,
    mysql_root_password => $database_root_password,
    host => 'localhost'
  }

  class { "libpdf":
    lamp_package => $lamp_package,
    apache_service => $apache_service
  }
}