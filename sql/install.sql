CREATE TABLE IF NOT EXISTS `#__jtransport_core_acl_aro` (
  `user_id` int(11) NOT NULL,
  `aro_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__jtransport_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `tbl_key` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `cid` int(11) NOT NULL DEFAULT '0',
  `class` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `cache` int(11) NOT NULL,
  `xmlpath` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `#__jtransport_extensions` (`id`, `name`, `title`, `tbl_key`, `source`, `destination`, `cid`, `class`, `status`, `cache`, `xmlpath`) VALUES
(1, 'extensions', 'Check extensions', '', '', '', 0, 'JTransportCheckExtensions', 2, 0, ''),
(2, 'ext_components', 'Check components', 'id', 'components', 'extensions', 0, 'JTransportExtensionsComponents', 0, 0, ''),
(3, 'ext_modules', 'Check modules', 'id', 'modules', 'extensions', 0, 'JTransportExtensionsModules', 0, 0, ''),
(4, 'ext_plugins', 'Check plugins', 'id', 'plugins', 'extensions', 0, 'JTransportExtensionsPlugins', 0, 0, '');

CREATE TABLE IF NOT EXISTS `#__jtransport_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `tbl_key` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `cid` int(11) NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `cache` int(11) NOT NULL,
  `extension` int(1) NOT NULL DEFAULT '0',
  `total` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `stop` int(11) NOT NULL,
  `first` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;