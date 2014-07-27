class lamp::params {
  $lamp_package_name = "lamp-server^"
  $lamp_package = Package[$lamp_package_name]
  $apache_service_name = "apache2"
  $apache_service = Service[$apache_service_name]
}