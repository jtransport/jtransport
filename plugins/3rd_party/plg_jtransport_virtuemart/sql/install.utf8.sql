-- VirtueMart table SQL script
-- This will install all the tables need to run VirtueMart


--
-- Table structure for table `#__virtuemart_adminmenuentries`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_adminmenuentries` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` tinyint(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The ID of the VM Module, this Item is assigned to',
  `parent_id` tinyint(11) unsigned NOT NULL DEFAULT '0',
  `name` char(64) NOT NULL DEFAULT '0',
  `link` char(64) NOT NULL DEFAULT '0',
  `depends` char(64) NOT NULL DEFAULT '' COMMENT 'Names of the Parameters, this Item depends on',
  `icon_class` char(96),
  `ordering` int(2) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `tooltip` char(128),
  `view` char(32),
  `task` char(32),
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Administration Menu Items' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_calcs`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_calcs` (
  `virtuemart_calc_id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Belongs to vendor',
  `calc_jplugin_id` int(11) NOT NULL DEFAULT '0',
  `calc_name` char(64) NOT NULL DEFAULT '' COMMENT 'Name of the rule',
  `calc_descr` char(128) NOT NULL DEFAULT '' COMMENT 'Description',
  `calc_kind` char(16) NOT NULL DEFAULT '' COMMENT 'Discount/Tax/Margin/Commission',
  `calc_value_mathop` char(8) NOT NULL DEFAULT '' COMMENT 'the mathematical operation like (+,-,+%,-%)',
  `calc_value` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT 'The Amount',
  `calc_currency` smallint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Currency of the Rule',
  `calc_shopper_published` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Visible for Shoppers',
  `calc_vendor_published` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Visible for Vendors',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Startdate if nothing is set = permanent',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Enddate if nothing is set = permanent',
  `for_override` tinyint(1) NOT NULL DEFAULT '0',
  `calc_params` varchar(18000),
  `ordering` int(2) NOT NULL DEFAULT '0',
  `shared` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_calc_id`),
  KEY `i_virtuemart_vendor_id` (`virtuemart_vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_calc_categories`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_calc_categories` (
  `id` mediumint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_calc_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_category_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_calc_id` (`virtuemart_calc_id`,`virtuemart_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__virtuemart_calc_manufacturers` (
  `id` mediumint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_calc_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_manufacturer_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_calc_id` (`virtuemart_calc_id`,`virtuemart_manufacturer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_calc_shoppergroups`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_calc_shoppergroups` (
  `id` mediumint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_calc_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_shoppergroup_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_calc_id` (`virtuemart_calc_id`,`virtuemart_shoppergroup_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_calc_countries`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_calc_countries` (
  `id` mediumint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_calc_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_country_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_calc_id` (`virtuemart_calc_id`,`virtuemart_country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_calc_states`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_calc_states` (
  `id` mediumint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_calc_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_state_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_calc_id` (`virtuemart_calc_id`,`virtuemart_state_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_categories`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_categories` (
  `virtuemart_category_id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `category_template` char(128),
  `category_layout` char(64),
  `category_product_layout` char(64),
  `products_per_row` tinyint(2),
  `limit_list_step` char(32),
  `limit_list_initial` smallint(1) UNSIGNED,
  `hits` int(1) unsigned NOT NULL DEFAULT '0',
  `metarobot` char(40) NOT NULL DEFAULT '',
  `metaauthor` char(64) NOT NULL DEFAULT '',
  `ordering` int(2) NOT NULL DEFAULT '0',
  `shared` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_category_id`),
  KEY `idx_category_virtuemart_vendor_id` (`virtuemart_vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Product Categories are stored here' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_categories_en_gb`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_categories_en_gb` (
  `virtuemart_category_id` int(1) unsigned NOT NULL,
  `category_name` char(180) NOT NULL DEFAULT '',
  `category_description` varchar(19000) NOT NULL DEFAULT '',
  `metadesc` varchar(400) NOT NULL DEFAULT '',
  `metakey` varchar(400) NOT NULL DEFAULT '',
  `customtitle` char(255) NOT NULL DEFAULT '',
  `slug` char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (`virtuemart_category_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_category_categories`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_category_categories` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_parent_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `category_child_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY (`category_child_id`),
  UNIQUE KEY `i_category_parent_id` (`category_parent_id`,`category_child_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Category child-parent relation list';

--
-- Table structure for table `#__virtuemart_category_medias`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_category_medias` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_category_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_media_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_category_id` (`virtuemart_category_id`,`virtuemart_media_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_countries`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_countries` (
  `virtuemart_country_id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_worldzone_id` tinyint(11) NOT NULL DEFAULT '1',
  `country_name` char(64),
  `country_3_code` char(3),
  `country_2_code` char(2),
  `ordering` int(2) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_country_id`),
  KEY `idx_country_3_code` (`country_3_code`),
  KEY `idx_country_2_code` (`country_2_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Country records' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_coupons`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_coupons` (
  `virtuemart_coupon_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `coupon_code` char(32) NOT NULL DEFAULT '',
  `percent_or_total` enum('percent','total') NOT NULL DEFAULT 'percent',
  `coupon_type` enum('gift','permanent') NOT NULL DEFAULT 'gift',
  `coupon_value` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `coupon_start_date` datetime,
  `coupon_expiry_date` datetime,
  `coupon_value_valid` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`virtuemart_coupon_id`),
   KEY `idx_coupon_code` (`coupon_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Used to store coupon codes' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_currencies`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_currencies` (
  `virtuemart_currency_id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL DEFAULT '1',
  `currency_name` char(64),
  `currency_code_2` char(2),
  `currency_code_3` char(3),
  `currency_numeric_code` int(4),
  `currency_exchange_rate` decimal(10,5),
  `currency_symbol` char(4),
  `currency_decimal_place` char(4),
  `currency_decimal_symbol` char(4),
  `currency_thousands` char(4),
  `currency_positive_style` char(64),
  `currency_negative_style` char(64),
  `ordering` int(2) NOT NULL DEFAULT '0',
  `shared` tinyint(1) NOT NULL DEFAULT '1',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_currency_id`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `idx_currency_code_3` (`currency_code_3`),
  KEY `idx_currency_numeric_code` (`currency_numeric_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Used to store currencies';


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_customs`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_customs` (
  `virtuemart_custom_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `custom_parent_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_vendor_id` smallint(11) NOT NULL DEFAULT '1',
  `custom_jplugin_id` int(11) NOT NULL DEFAULT '0',
  `custom_element` char(50) NOT NULL DEFAULT '',
  `admin_only` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:Display in admin only',
  `custom_title` char(255) NOT NULL DEFAULT '' COMMENT 'field title',
  `show_title` tinyint(1) NOT NULL DEFAULT '1',
  `custom_tip` char(255) NOT NULL DEFAULT '' COMMENT 'tip',
  `custom_value` char(255) COMMENT 'defaut value',
  `custom_field_desc` char(255) COMMENT 'description or unit',
  `field_type` char(1) NOT NULL DEFAULT '0' COMMENT 'S:string,I:int,P:parent, B:bool,D:date,T:time,H:hidden',
  `is_list` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'list of values',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:hidden',
  `is_cart_attribute` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Add attributes to cart',
  `layout_pos` char(24) COMMENT 'Layout Position',
  `custom_params` text,
  `shared` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'valide for all vendors?',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_custom_id`),
    KEY `idx_custom_plugin_virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `idx_custom_plugin_element` (`custom_element`),
  KEY `idx_custom_plugin_ordering` (`ordering`),
  KEY `idx_custom_parent_id` (`custom_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='custom fields definition' AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__virtuemart_invoices` (
  `virtuemart_invoice_id` INT(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL DEFAULT '1',
  `virtuemart_order_id` int(1) UNSIGNED,
  `invoice_number` char(64),
  `order_status` char(2),
  `xhtml` text,
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_invoice_id`),
  KEY `idx_virtuemart_order_id` (`virtuemart_order_id`),
  KEY `idx_virtuemart_vendor_id` (`virtuemart_vendor_id`),
  UNIQUE KEY `idx_invoice_number` (`invoice_number`,`virtuemart_vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='custom fields definition' AUTO_INCREMENT=1 ;

  
-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_manufacturers`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_manufacturers` (
  `virtuemart_manufacturer_id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_manufacturercategories_id` int(11),
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`virtuemart_manufacturer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Manufacturers are those who deliver products' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_manufacturers_en_gb`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_manufacturers_en_gb` (
  `virtuemart_manufacturer_id` int(1) unsigned NOT NULL,
  `mf_name` char(180) NOT NULL DEFAULT '',
  `mf_email` char(255) NOT NULL DEFAULT '',
  `mf_desc` varchar(19000) NOT NULL DEFAULT '',
  `mf_url` char(255) NOT NULL DEFAULT '',
  `slug` char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (`virtuemart_manufacturer_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- Table structure for table `#__virtuemart_manufacturer_medias`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_manufacturer_medias` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_manufacturer_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_media_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_manufacturer_id` (`virtuemart_manufacturer_id`,`virtuemart_media_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_manufacturercategories`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_manufacturercategories` (
  `virtuemart_manufacturercategories_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_manufacturercategories_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Manufacturers are assigned to these categories' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_manufacturercategories_en_gb`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_manufacturercategories_en_gb` (
  `virtuemart_manufacturercategories_id` int(1) unsigned NOT NULL,
  `mf_category_name` char(180) NOT NULL DEFAULT '',
  `mf_category_desc` varchar(19000) NOT NULL DEFAULT '',
  `slug` char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (`virtuemart_manufacturercategories_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_medias` (was  `#__virtuemart_product_files`)
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_medias` (
  `virtuemart_media_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(11) NOT NULL DEFAULT '1',
  `file_title` char(126) NOT NULL DEFAULT '',
  `file_description` char(254) NOT NULL DEFAULT '',
  `file_meta` char(254) NOT NULL DEFAULT '',
  `file_mimetype` char(64) NOT NULL DEFAULT '',
  `file_type` char(32) NOT NULL DEFAULT '',
  `file_url` varchar(900) NOT NULL DEFAULT '',
  `file_url_thumb` varchar(900) NOT NULL DEFAULT '',
  `file_is_product_image` tinyint(1) NOT NULL DEFAULT '0',
  `file_is_downloadable` tinyint(1) NOT NULL DEFAULT '0',
  `file_is_forSale` tinyint(1) NOT NULL DEFAULT '0',
  `file_params` varchar(17500),
  `file_lang` varchar(500) NOT NULL,
  `shared` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_media_id`),
  KEY `i_virtuemart_vendor_id` (`virtuemart_vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Additional Images and Files which are assigned to products' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_migration_oldtonew_ids` (only used for migration)
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_migration_oldtonew_ids` (
        `id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
        `cats` longblob,
        `catsxref` blob,
        `manus` longblob,
        `mfcats` blob,
        `shoppergroups` longblob,
        `products` longblob,
        `products_start` int(1),
        `orderstates` blob,
        `orders` longblob,
        `attributes` longblob,
        `relatedproducts` longblob,
        `orders_start` int(1),
        PRIMARY KEY (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='xref table for vm1 ids to vm2 ids' ;


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_modules`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_modules` (
  `module_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_name` char(255),
  `module_description` varchar(21000),
  `module_perms` char(255),
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `is_admin` enum('0','1') NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module_id`),
  KEY `idx_module_name` (`module_name`),
  KEY `idx_module_ordering` (`ordering`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='VirtueMart Core Modules, not: Joomla modules' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_orders`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_orders` (
  `virtuemart_order_id` INT(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_user_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `order_number` char(64),
  `customer_number` char(32),
  `order_pass` char(8),
  `order_total` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `order_salesPrice` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `order_billTaxAmount` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `order_billTax` varchar(400),
  `order_billDiscountAmount` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `order_discountAmount` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `order_subtotal` decimal(15,5),
  `order_tax` decimal(10,5),
  `order_shipment` decimal(10,2),
  `order_shipment_tax` decimal(10,5),
  `order_payment` decimal(10,2),
  `order_payment_tax` decimal(10,5),
  `coupon_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `coupon_code` char(32),
  `order_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_currency` smallint(1),
  `order_status` char(1),
  `user_currency_id` smallint(1),
  `user_currency_rate` DECIMAL(10,5) NOT NULL DEFAULT '1.00000',
  `virtuemart_paymentmethod_id` mediumint(1) UNSIGNED,
  `virtuemart_shipmentmethod_id` mediumint(1) UNSIGNED,
  `customer_note` varchar(21000),
  `order_language` char(7),
  `ip_address` char(15) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_order_id`),
  KEY `idx_orders_virtuemart_user_id` (`virtuemart_user_id`),
  KEY `idx_orders_virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `idx_orders_order_number` (`order_number`),
  KEY `idx_orders_virtuemart_paymentmethod_id` (`virtuemart_paymentmethod_id`),
  KEY `idx_orders_virtuemart_shipmentmethod_id` (`virtuemart_shipmentmethod_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to store all orders' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_order_histories`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_order_histories` (
  `virtuemart_order_history_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_order_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `order_status_code` char(1) NOT NULL DEFAULT '0',
  `customer_notified` tinyint(1) NOT NULL DEFAULT '0',
  `comments` varchar(21000),
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_order_history_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Stores all actions and changes that occur to an order' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_order_items`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_order_items` (
  `virtuemart_order_item_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_order_id` int(11),
  `virtuemart_vendor_id` smallint(11) NOT NULL DEFAULT '1',
  `virtuemart_product_id` int(11),
  `order_item_sku` char(64) NOT NULL DEFAULT '',
  `order_item_name` char(255) NOT NULL DEFAULT '',
  `product_quantity` int(11),
  `product_item_price` decimal(15,5),
  `product_priceWithoutTax` decimal(15,5),
  `product_tax` decimal(15,5),
  `product_basePriceWithTax` decimal(15,5),
  `product_discountedPriceWithoutTax` decimal(15,5),
  `product_final_price` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `product_subtotal_discount` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `product_subtotal_with_tax` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `order_item_currency` INT(11),
  `order_status` char(1),
  `product_attribute` text,
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_order_item_id`),
  KEY `virtuemart_product_id` (`virtuemart_product_id`),
  KEY `idx_order_item_virtuemart_order_id` (`virtuemart_order_id`),
  KEY `idx_order_item_virtuemart_vendor_id` (`virtuemart_vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Stores all items (products) which are part of an order' AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_order_calc_rules`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_order_calc_rules` (
  `virtuemart_order_calc_rule_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_calc_id` int(11),
  `virtuemart_order_id` int(11),
  `virtuemart_vendor_id` smallint(11) NOT NULL DEFAULT '1',
  `virtuemart_order_item_id` int(11),
  `calc_rule_name`  char(64) NOT NULL DEFAULT '' COMMENT 'Name of the rule',
  `calc_kind` char(16) NOT NULL DEFAULT '' COMMENT 'Discount/Tax/Margin/Commission',
  `calc_mathop` char(16) NOT NULL DEFAULT '' COMMENT 'Discount/Tax/Margin/Commission',
  `calc_amount` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `calc_result` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `calc_value` decimal(15,5) NOT NULL DEFAULT '0.00000',
  `calc_currency` smallint(1),
  `calc_params` varchar(18000),
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_order_calc_rule_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Stores all calculation rules which are part of an order' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_orderstates`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_orderstates` (
  `virtuemart_orderstate_id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(11) NOT NULL DEFAULT '1',
  `order_status_code` char(1) NOT NULL DEFAULT '',
  `order_status_name` char(64),
  `order_status_description` varchar(20000),
  `order_stock_handle` char(1) NOT NULL DEFAULT 'A',
  `ordering` int(2) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
 `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_orderstate_id`),
  KEY `idx_order_status_ordering` (`ordering`),
  KEY `idx_order_status_virtuemart_vendor_id` (`virtuemart_vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='All available order statuses' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_order_userinfos`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_order_userinfos` (
  `virtuemart_order_userinfo_id` INT(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_order_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_user_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `address_type` char(2),
  `address_type_name` char(32),
  `company` char(64),
  `title` char(32),
  `last_name` char(48),
  `first_name` char(48),
  `middle_name` char(48),
  `phone_1` char(32),
  `phone_2` char(32),
  `fax` char(32),
  `address_1` char(64) NOT NULL DEFAULT '',
  `address_2` char(64) ,
  `city` char(64) NOT NULL DEFAULT '',
  `virtuemart_state_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_country_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `zip` char(16) NOT NULL DEFAULT '',
  `email` char(128),
  `agreed` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_order_userinfo_id`),
  KEY `i_virtuemart_order_id` (`virtuemart_order_id`),
  KEY `i_virtuemart_user_id` (`virtuemart_user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Stores the BillTo and ShipTo Information at order time' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_paymentmethods`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_paymentmethods` (
  `virtuemart_paymentmethod_id` mediumint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(11) NOT NULL DEFAULT '1',
  `payment_jplugin_id` int(11) NOT NULL DEFAULT '0',
  `payment_element` char(50) NOT NULL DEFAULT '',
  `payment_params` varchar(19000),
  `shared` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'valide for all vendors?',
  `ordering` int(2) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_paymentmethod_id`),
    KEY `idx_payment_jplugin_id` (`payment_jplugin_id`),
    KEY `idx_payment_element` (payment_element,`virtuemart_vendor_id`),
    KEY `idx_payment_method_ordering` (`ordering`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='The payment methods of your store' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `j25_virtuemart_paymentmethods_en_gb`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_paymentmethods_en_gb` (
  `virtuemart_paymentmethod_id` int(1) unsigned NOT NULL,
  `payment_name` char(180) NOT NULL DEFAULT '',
  `payment_desc` varchar(19000) NOT NULL DEFAULT '',
  `slug` char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (`virtuemart_paymentmethod_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_paymentmethod_shoppergroups`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_paymentmethod_shoppergroups` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_paymentmethod_id` mediumint(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_shoppergroup_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_paymentmethod_id` (`virtuemart_paymentmethod_id`,`virtuemart_shoppergroup_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='xref table for paymentmethods to shoppergroup' AUTO_INCREMENT=1 ;



-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_products`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_products` (
  `virtuemart_product_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL DEFAULT '1',
  `product_parent_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `product_sku` char(64),
  `product_weight` decimal(10,4),
  `product_weight_uom` char(7),
  `product_length` decimal(10,4),
  `product_width` decimal(10,4),
  `product_height` decimal(10,4),
  `product_lwh_uom` char(7),
  `product_url` char(255),
  `product_in_stock` int(1) NOT NULL DEFAULT '0',
  `product_ordered` int(1) NOT NULL DEFAULT '0',
  `low_stock_notification` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `product_available_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `product_availability` char(32),
  `product_special` tinyint(1),
  `product_sales` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `product_unit` varchar(8),
  `product_packaging` decimal(8,4) UNSIGNED,
  `product_params` varchar(2000),
  `hits` int(11) unsigned,
  `intnotes` varchar(18000),
  `metarobot` varchar(400),
  `metaauthor` varchar(400),
  `layout` char(16),
  `published` tinyint(1),
  `pordering` mediumint(2) UNSIGNED NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_product_id`),
  KEY `idx_product_virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `idx_product_product_parent_id` (`product_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='All products are stored here.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_products_en_gb`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_products_en_gb` (
  `virtuemart_product_id` int(1) unsigned NOT NULL,
  `product_s_desc` varchar(2000) NOT NULL DEFAULT '',
  `product_desc` varchar(18400) NOT NULL DEFAULT '',
  `product_name` char(180) NOT NULL DEFAULT '',
  `metadesc` varchar(400) NOT NULL DEFAULT '',
  `metakey` varchar(400) NOT NULL DEFAULT '',
  `customtitle` char(255) NOT NULL DEFAULT '',
  `slug` char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (`virtuemart_product_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_product_categories`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_product_categories` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_category_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Maps Products to Categories';

-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_product_shoppergroups`
--
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_product_shoppergroups` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_shoppergroup_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_shoppergroup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Maps Products to Categories';

-- --------------------------------------------------------
--
-- Table structure `#__virtuemart_product_customfields`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_product_customfields` (
  `virtuemart_customfield_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'field id',
  `virtuemart_product_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_custom_id` int(11) NOT NULL DEFAULT '1' COMMENT 'custom group id',
  `custom_value` varchar(8000) COMMENT 'field value',
  `custom_price` decimal(15,5) COMMENT 'price',
  `custom_param` varchar(12800) COMMENT 'Param for Plugins',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_customfield_id`),
  KEY `idx_virtuemart_product_id` (`virtuemart_product_id`),
  KEY `idx_virtuemart_custom_id` (`virtuemart_custom_id`),
  KEY `idx_custom_value` (`custom_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='custom fields' AUTO_INCREMENT=1 ;



-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_product_medias`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_product_medias` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_media_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_media_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_product_manufacturers`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_product_manufacturers` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(11),
  `virtuemart_manufacturer_id` smallint(1) UNSIGNED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_manufacturer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Maps a product to a manufacturer';


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_product_prices`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_product_prices` (
  `virtuemart_product_price_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_shoppergroup_id` int(11),
  `product_price` decimal(15,5),
  `override` tinyint(1),
  `product_override_price` decimal(15,5),
  `product_tax_id` int(11),
  `product_discount_id` int(11),
  `product_currency` smallint(1),
  `product_price_publish_up` datetime,
  `product_price_publish_down` datetime,
  `price_quantity_start` int(11) unsigned,
  `price_quantity_end` int(11) unsigned,
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_product_price_id`),
  KEY `idx_product_price_product_id` (`virtuemart_product_id`),
  KEY `idx_product_price_virtuemart_shoppergroup_id` (`virtuemart_shoppergroup_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Holds price records for a product' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_product_relations`
-- is that needed that way?

CREATE TABLE IF NOT EXISTS `#__virtuemart_product_relations` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `related_products` int(11),
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_product_id` (`virtuemart_product_id`,`related_products`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_rating_reviews`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_rating_reviews` (
  `virtuemart_rating_review_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `comment` varchar(18000),
  `review_ok` tinyint(1) NOT NULL DEFAULT '0',
  `review_rates` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `review_ratingcount` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `review_rating` decimal(10,2) NOT NULL DEFAULT '0.00',
  `review_editable` tinyint(1) NOT NULL DEFAULT '1',
  `lastip` char(50) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_rating_review_id`),
  UNIQUE KEY `i_virtuemart_product_id` (`virtuemart_product_id`,`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_ratings`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_ratings` (
  `virtuemart_rating_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `rates` int(11) NOT NULL DEFAULT '0',
  `ratingcount` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `rating` decimal(10,1) NOT NULL DEFAULT '0.0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_rating_id`),
  UNIQUE KEY `i_virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_rating_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Stores all ratings for a product';


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_rating_votes`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_rating_votes` (
  `virtuemart_rating_vote_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `vote` int(11) NOT NULL DEFAULT '0',
  `lastip` char(50) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_rating_vote_id`),
  UNIQUE KEY `i_virtuemart_product_id` (`virtuemart_product_id`,`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Stores all ratings for a product';


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_shipmentmethods`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_shipmentmethods` (
  `virtuemart_shipmentmethod_id` mediumint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(11) NOT NULL DEFAULT '1',
  `shipment_jplugin_id` int(11) NOT NULL DEFAULT '0',
  `shipment_element` char(50) NOT NULL DEFAULT '',
  `shipment_params` varchar(19000),
  `ordering` int(2) NOT NULL DEFAULT '0',
  `shared` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_shipmentmethod_id`),
    KEY `idx_shipment_jplugin_id` (`shipment_jplugin_id`),
    KEY `idx_shipment_element` (shipment_element,`virtuemart_vendor_id`),
    KEY `idx_shipment_method_ordering` (`ordering`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Shipment created from the shipment plugins' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_shipmentmethods_shoppergroups`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_shipmentmethod_shoppergroups` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_shipmentmethod_id` mediumint(1) UNSIGNED,
  `virtuemart_shoppergroup_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_shipmentmethod_id` (`virtuemart_shipmentmethod_id`,`virtuemart_shoppergroup_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='xref table for shipment to shoppergroup' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_shoppergroups`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_shoppergroups` (
  `virtuemart_shoppergroup_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(11) NOT NULL DEFAULT '1',
  `shopper_group_name` char(32),
  `shopper_group_desc` char(128),
  `custom_price_display` tinyint(1) NOT NULL DEFAULT '0',
  `price_display` blob,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  `shared` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_shoppergroup_id`),
  KEY `idx_shopper_group_virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `idx_shopper_group_name` (`shopper_group_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Shopper Groups that users can be assigned to' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------


--
-- Table structure for table `#__virtuemart_states`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_states` (
  `virtuemart_state_id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL DEFAULT '1',
  `virtuemart_country_id` smallint(1) UNSIGNED NOT NULL DEFAULT '1',
  `virtuemart_worldzone_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `state_name` char(64),
  `state_3_code` char(3),
  `state_2_code` char(2),
  `ordering` int(2) NOT NULL DEFAULT '0',
  `shared` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_state_id`),
  KEY `i_virtuemart_vendor_id` (`virtuemart_vendor_id`),
  UNIQUE KEY `idx_state_3_code` (`virtuemart_country_id`,`state_3_code`),
  UNIQUE KEY `idx_state_2_code` (`virtuemart_country_id`,`state_2_code`),
  KEY `i_virtuemart_country_id` (`virtuemart_country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='States that are assigned to a country' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_vmusers`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_vmusers` (
  `virtuemart_user_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `user_is_vendor` tinyint(1) NOT NULL DEFAULT '0',
  `customer_number` char(32),
  `perms` char(40) NOT NULL DEFAULT 'shopper',
  `virtuemart_paymentmethod_id` mediumint(1) UNSIGNED,
  `virtuemart_shipmentmethod_id` mediumint(1) UNSIGNED,
  `agreed` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_user_id`),
  KEY `i_virtuemart_vendor_id` (`virtuemart_vendor_id`),
  UNIQUE KEY `i_virtuemart_user_id` (`virtuemart_user_id`,`virtuemart_vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Holds the unique user data' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_vmuser_shoppergroups`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_vmuser_shoppergroups` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_user_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_shoppergroup_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_user_id` (`virtuemart_user_id`,`virtuemart_shoppergroup_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='xref table for users to shopper group' ;


-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__virtuemart_permgroups` (
  `virtuemart_permgroup_id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL DEFAULT '1',
  `group_name` char(128),
  `group_level` int(11),
  `ordering` int(2) NOT NULL DEFAULT '0',
  `shared` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_permgroup_id`),
  KEY `i_virtuemart_vendor_id` (`virtuemart_vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Holds all the user groups' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
--
-- Table structure for table `#__virtuemart_userfields`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_userfields` (
  `virtuemart_userfield_id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL DEFAULT '1',
  `userfield_jplugin_id` int(11) NOT NULL DEFAULT '0',
  `name` char(255) NOT NULL DEFAULT '',
  `title` char(255) NOT NULL DEFAULT '',
  `description` mediumtext,
  `type` char(70) NOT NULL DEFAULT '',
  `maxlength` int(11),
  `size` int(11),
  `required` tinyint(4) NOT NULL DEFAULT '0',
  `cols` int(11),
  `rows` int(11),
  `value` char(255),
  `default` char(255),
  `registration` tinyint(1) NOT NULL DEFAULT '0',
  `shipment` tinyint(1) NOT NULL DEFAULT '0',
  `account` tinyint(1) NOT NULL DEFAULT '1',
  `readonly` tinyint(1) NOT NULL DEFAULT '0',
  `calculated` tinyint(1) NOT NULL DEFAULT '0',
  `sys` tinyint(4) NOT NULL DEFAULT '0',
  `params` varchar(17500),
  `ordering` int(2) NOT NULL DEFAULT '0',
  `shared` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_userfield_id`),
  KEY `i_virtuemart_vendor_id` (`virtuemart_vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Holds the fields for the user information' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_userfield_values`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_userfield_values` (
  `virtuemart_userfield_value_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_userfield_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `fieldtitle` char(255) NOT NULL DEFAULT '',
  `fieldvalue` char(255) NOT NULL DEFAULT '',
  `sys` tinyint(4) NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_userfield_value_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Holds the different values for dropdown and radio lists' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_userinfos`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_userinfos` (
  `virtuemart_userinfo_id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_user_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `address_type` char(2) NOT NULL DEFAULT '',
  `address_type_name` char(32) NOT NULL DEFAULT '',
  `name` char(64),
  `company` char(64),
  `title` char(32),
  `last_name` char(48),
  `first_name` char(48),
  `middle_name` char(48),
  `phone_1` char(32),
  `phone_2` char(32),
  `fax` char(32),
  `address_1` char(64) NOT NULL DEFAULT '',
  `address_2` char(64),
  `city` char(64) NOT NULL DEFAULT '',
  `virtuemart_state_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_country_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `zip` char(32) NOT NULL DEFAULT '',
  `agreed` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_userinfo_id`),
  KEY `idx_userinfo_virtuemart_user_id` (`virtuemart_userinfo_id`,`virtuemart_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Customer Information, BT = BillTo and ST = ShipTo';

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_vendors`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_vendors` (
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_name` char(64),
  `vendor_currency` int(11),
  `vendor_accepted_currencies` varchar(1536) NOT NULL DEFAULT '',
  `vendor_params` varchar(17000),
  `metarobot` char(20),
  `metaauthor` char(64),
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_vendor_id`),
  KEY `idx_vendor_name` (`vendor_name`)
--  KEY `idx_vendor_category_id` (`vendor_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Vendors manage their products in your store' AUTO_INCREMENT=1 ;

-- ----------------------------------------------------------

--
-- Table structure for table `#__virtuemart_vendors_en_gb`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_vendors_en_gb` (
  `virtuemart_vendor_id` int(1) unsigned NOT NULL,
  `vendor_store_desc` text NOT NULL,
  `vendor_terms_of_service` text NOT NULL,
  `vendor_legal_info` text NOT NULL,
  `vendor_letter_css` text NOT NULL,
  `vendor_letter_header_html` varchar(8000) NOT NULL DEFAULT '<h1>{vm:vendorname}</h1><p>{vm:vendoraddress}</p>',
  `vendor_letter_footer_html` varchar(8000) NOT NULL DEFAULT '<p>{vm:vendorlegalinfo}<br />Page {vm:pagenum}/{vm:pagecount}</p>',
  `vendor_store_name` char(180) NOT NULL DEFAULT '',
  `vendor_phone` char(26) NOT NULL DEFAULT '',
  `vendor_url` char(255) NOT NULL DEFAULT '',
  `metadesc` varchar(400) NOT NULL DEFAULT '',
  `metakey` varchar(400) NOT NULL DEFAULT '',
  `customtitle` char(255) NOT NULL DEFAULT '',
  `slug` char(192) NOT NULL DEFAULT '',
  PRIMARY KEY (`virtuemart_vendor_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__virtuemart_vendor_medias`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_vendor_medias` (
  `id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_media_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_virtuemart_vendor_id` (`virtuemart_vendor_id`,`virtuemart_media_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_waitingusers`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_waitingusers` (
  `virtuemart_waitinguser_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_product_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `virtuemart_user_id` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `notify_email` char(150) NOT NULL DEFAULT '',
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  `notify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ordering` int(2) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_waitinguser_id`),
  KEY `virtuemart_product_id` (`virtuemart_product_id`),
  KEY `notify_email` (`notify_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Stores notifications, users waiting f. products out of stock' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__virtuemart_worldzones`
--

CREATE TABLE IF NOT EXISTS `#__virtuemart_worldzones` (
  `virtuemart_worldzone_id` smallint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` smallint(1),
  `zone_name` char(255),
  `zone_cost` decimal(10,2),
  `zone_limit` decimal(10,2),
  `zone_description` varchar(18000),
  `zone_tax_rate` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  `shared` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`virtuemart_worldzone_id`),
  KEY `i_virtuemart_vendor_id` (`virtuemart_vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='The Zones managed by the Zone Shipment Module' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Dumping data for table `j25_virtuemart_modules`
--

INSERT INTO `#__virtuemart_modules` (`module_id`, `module_name`, `module_description`, `module_perms`, `published`, `is_admin`, `ordering`) VALUES
(1, 'product', 'Here you can administer your online catalog of products.  Categories , Products (view=product), Attributes  ,Product Types      Product Files (view=media), Inventory  , Calculation Rules ,Customer Reviews  ', 'storeadmin,admin', 1, '1', 1),
(2, 'order', 'View Order and Update Order Status:    Orders , Coupons , Revenue Report ,Shopper , Shopper Groups ', 'admin,storeadmin', 1, '1', 2),
(3, 'manufacturer', 'Manage the manufacturers of products in your store.', 'storeadmin,admin', 1, '1', 3),
(4, 'store', 'Store Configuration: Store Information, Payment Methods , Shipment, Shipment Rates', 'storeadmin,admin', 1, '1', 4),
(5, 'configuration', 'Configuration: shop configuration , currencies (view=currency), Credit Card List, Countries, userfields, order status  ', 'admin,storeadmin', 1, '1', 5),
(6, 'msgs', 'This module is unprotected an used for displaying system messages to users.  We need to have an area that does not require authorization when things go wrong.', 'none', 0, '0', 99),
(7, 'shop', 'This is the Washupito store module.  This is the demo store included with the VirtueMart distribution.', 'none', 1, '0', 99),
(8, 'store', 'Store Configuration: Store Information, Payment Methods , Shipment, Shipment Rates', 'storeadmin,admin', 1, '1', 4),
(9, 'account', 'This module allows shoppers to update their account information and view previously placed orders.', 'shopper,storeadmin,admin,demo', 1, '0', 99),
(10, 'checkout', '', 'none', 0, '0', 99),
(11, 'tools', 'Tools', 'admin', 1, '1', 8),
(13, 'zone', 'This is the zone-shipment module. Here you can manage your shipment costs according to Zones.', 'admin,storeadmin', 0, '1', 11);

--
-- Dumping data for table `#__virtuemart_adminmenuentries`
--

INSERT INTO `#__virtuemart_adminmenuentries` (`id`, `module_id`, `parent_id`, `name`, `link`, `depends`, `icon_class`, `ordering`, `published`, `tooltip`, `view`, `task`) VALUES
(1, 1, 0, 'COM_VIRTUEMART_CATEGORY_S', '', '', 'vmicon vmicon-16-folder_camera', 1, 1, '', 'category', ''),
(2, 1, 0, 'COM_VIRTUEMART_PRODUCT_S', '', '', 'vmicon vmicon-16-camera', 2, 1, '', 'product', ''),
(3, 1, 0, 'COM_VIRTUEMART_PRODUCT_CUSTOM_FIELD_S', '', '', 'vmicon vmicon-16-document_move', 5, 1, '', 'custom', ''),
(4, 1, 0, 'COM_VIRTUEMART_PRODUCT_INVENTORY', '', '', 'vmicon vmicon-16-price_watch', 7, 1, '', 'inventory', ''),
(5, 1, 0, 'COM_VIRTUEMART_CALC_S', '', '', 'vmicon vmicon-16-calculator', 8, 1, '', 'calc', ''),
(6, 1, 0, 'COM_VIRTUEMART_REVIEW_RATE_S', '', '', 'vmicon vmicon-16-comments', 9, 1, '', 'ratings', ''),
(7, 2, 0, 'COM_VIRTUEMART_ORDER_S', '', '', 'vmicon vmicon-16-page_white_stack', 1, 1, '', 'orders', ''),
(8, 2, 0, 'COM_VIRTUEMART_COUPON_S', '', '', 'vmicon vmicon-16-shopping', 10, 1, '', 'coupon', ''),
(9, 2, 0, 'COM_VIRTUEMART_REPORT', '', '', 'vmicon vmicon-16-to_do_list_cheked_1', 3, 1, '', 'report', ''),
(10, 2, 0, 'COM_VIRTUEMART_USER_S', '', '', 'vmicon vmicon-16-user', 4, 1, '', 'user', ''),
(11, 2, 0, 'COM_VIRTUEMART_SHOPPERGROUP_S', '', '', 'vmicon vmicon-16-user-group', 5, 1, '', 'shoppergroup', ''),
(12, 3, 0, 'COM_VIRTUEMART_MANUFACTURER_S', '', '', 'vmicon vmicon-16-wrench_orange', 1, 1, '', 'manufacturer', ''),
(13, 3, 0, 'COM_VIRTUEMART_MANUFACTURER_CATEGORY_S', '', '', 'vmicon vmicon-16-folder_wrench', 2, 1, '', 'manufacturercategories', ''),
(14, 4, 0, 'COM_VIRTUEMART_STORE', '', '', 'vmicon vmicon-16-reseller_account_template', 1, 1, '', 'user', 'editshop'),
(15, 4, 0, 'COM_VIRTUEMART_MEDIA_S', '', '', 'vmicon vmicon-16-pictures', 2, 1, '', 'media', ''),
(16, 4, 0, 'COM_VIRTUEMART_SHIPMENTMETHOD_S', '', '', 'vmicon vmicon-16-lorry', 3, 1, '', 'shipmentmethod', ''),
(17, 4, 0, 'COM_VIRTUEMART_PAYMENTMETHOD_S', '', '', 'vmicon vmicon-16-creditcards', 4, 1, '', 'paymentmethod', ''),
(18, 5, 0, 'COM_VIRTUEMART_CONFIGURATION', '', '', 'vmicon vmicon-16-config', 1, 1, '', 'config', ''),
(19, 5, 0, 'COM_VIRTUEMART_USERFIELD_S', '', '', 'vmicon vmicon-16-participation_rate', 2, 1, '', 'userfields', ''),
(20, 5, 0, 'COM_VIRTUEMART_ORDERSTATUS_S', '', '', 'vmicon vmicon-16-orderstatus', 3, 1, '', 'orderstatus', ''),
(21, 5, 0, 'COM_VIRTUEMART_CURRENCY_S', '', '', 'vmicon vmicon-16-coins', 5, 1, '', 'currency', ''),
(22, 5, 0, 'COM_VIRTUEMART_COUNTRY_S', '', '', 'vmicon vmicon-16-globe', 6, 1, '', 'country', ''),
(23, 11, 0, 'COM_VIRTUEMART_MIGRATION_UPDATE', '', '', 'vmicon vmicon-16-installer_box', 1, 1, '', 'updatesmigration', ''),
(24, 11, 0, 'COM_VIRTUEMART_ABOUT', '', '', 'vmicon vmicon-16-info', 2, 1, '', 'about', ''),
(25, 11, 0, 'COM_VIRTUEMART_HELP_TOPICS', 'http://docs.virtuemart.net/', '', 'vmicon vmicon-16-help', 4, 1, '', '', ''),
(26, 11, 0, 'COM_VIRTUEMART_COMMUNITY_FORUM', 'http://forum.virtuemart.net/', '', 'vmicon vmicon-16-reseller_programm', 6, 1, '', '', ''),
(27, 11, 0, 'COM_VIRTUEMART_STATISTIC_SUMMARY', '', '', 'vmicon vmicon-16-info', 1, 1, '', 'virtuemart', ''),
(28, 77, 0, 'COM_VIRTUEMART_USER_GROUP_S', '', '', 'vmicon vmicon-16-user', 2, 1, '', 'usergroups', '');
