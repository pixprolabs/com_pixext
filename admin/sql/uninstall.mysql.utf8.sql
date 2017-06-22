DROP TABLE IF EXISTS `#__pixext_tags`;
DROP TABLE IF EXISTS `#__pixext_types`;
DROP TABLE IF EXISTS `#__pixext_extensions`;
DROP TABLE IF EXISTS `#__pixext_versions`;
DROP TABLE IF EXISTS `#__pixext_log_types`;
DROP TABLE IF EXISTS `#__pixext_log_ips`;
DROP TABLE IF EXISTS `#__pixext_logs`;
DROP TABLE IF EXISTS `#__pixext_licenses`;
DROP TABLE IF EXISTS `#__pixext_packages`;
DROP TABLE IF EXISTS `#__pixext_package_versions`;
DROP TABLE IF EXISTS `#__pixext_package_licenses`;
DROP TABLE IF EXISTS `#__pixext_package_extension_versions`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_pixext.%');