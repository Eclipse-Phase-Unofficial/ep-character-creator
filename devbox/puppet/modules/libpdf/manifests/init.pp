class libpdf (
  $lamp_package,
  $apache_service
) {

  exec { "install-libpdf":
    cwd => '/var/opt/',
    command => "/vagrant/devbox/puppet/modules/libpdf/files/scripts/install_libpdf.sh",
    creates => '/usr/local/lib/libpdf.so',
    timeout => 0,
    require => $lamp_package
  }

  exec { "install-pecl-pdflib":
    command => "echo '/usr/local/' > /tmp/pdflib; pecl install pdflib < /tmp/pdflib; rm -f /tmp/pdflib;",
    path => ['/bin', '/usr/bin'],
    require => [ $lamp_package, Package['php5-dev'], Package['php-pear'], Exec['install-libpdf'] ],
    creates => '/usr/share/doc/php5-common/PEAR/pdflib'
  }

  file { "/etc/php5/mods-available/pdf.ini":
    ensure => file,
    mode => 0644,
    owner => 'root',
    group => 'root',
    source => 'puppet:///modules/libpdf/php5/pdf.ini',
    require => Exec['install-pecl-pdflib'],
    notify => $apache_service,
  }

  exec { "install-libpdf-symlinks":
    command => "ln -s ../../mods-available/pdf.ini /etc/php5/apache2/conf.d/30-pdf.ini; ln -s ../../mods-available/pdf.ini /etc/php5/cli/conf.d/30-pdf.ini; ",
    path => ['/bin'],
    require => File['/etc/php5/mods-available/pdf.ini'],
    notify => $apache_service,
    creates => ['/etc/php5/apache2/conf.d/30-pdf.ini','/etc/php5/cli/conf.d/30-pdf.ini']
  }
}