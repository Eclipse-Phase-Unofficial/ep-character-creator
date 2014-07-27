class lamp::mysql {
  define set_root_password(
    $password=$title,
    $lamp_package = $lamp::params::lamp_package
  ) {
    exec {"mysql-set-root-password":
      command  => "/usr/bin/mysqladmin -u root password '${password}'; touch /etc/mysql/.root_pass_set;",
      require => $lamp_package,
      creates => "/etc/mysql/.root_pass_set"
    }
  }

  define create_database(
    $db_name = $title,
    $user,
    $password,
    $lamp_package = $lamp::params::lamp_package,
    $run_sql = false,
    $sql_file_path = false
  ) {
    exec {"mysql-create-database-${db_name}":
      command  => "/usr/bin/mysql --user=${user} --password='${password}' -e 'create database ${db_name};'; touch /etc/mysql/.database_${db_name}_created;",
      require => [ $lamp_package, Exec["mysql-set-root-password"] ],
      creates => "/etc/mysql/.database_${db_name}_created"
    }

    if $run_sql {
      exec {"init-mysql-create-database-${db_name}":
        command  => "/usr/bin/mysql --user=${user} --password='${password}' -e 'use ${db_name}; source ${sql_file_path};' ; touch /etc/mysql/.database_${db_name}_initialized;",
        require => Exec["mysql-create-database-${db_name}"],
        creates => "/etc/mysql/.database_${db_name}_initialized"
      }
    }
  }

  define create_user(
    $user = $title,
    $password,
    $db_name,
    $host = '%',
    $lamp_package = $lamp::params::lamp_package,
    $mysql_root_user = 'root',
    $mysql_root_password
  ) {
    exec {"mysql-create-user-${user}@${host}":
      command  => "/usr/bin/mysql --user=${mysql_root_user} --password='${mysql_root_password}' -e \"CREATE USER '${user}'@'${host}' IDENTIFIED BY '${password}'; GRANT ALL PRIVILEGES ON ${db_name}.* TO '${user}'@'${host}'; FLUSH PRIVILEGES;\" ; touch /etc/mysql/.user_${user}@${host}_created;",
      require => Exec["mysql-create-database-${db_name}"],
      creates => "/etc/mysql/.user_${user}@${host}_created"
    }
  }
}