DROP TABLE IF EXISTS action_recorder;
CREATE TABLE action_recorder (
  id int(11) NOT NULL AUTO_INCREMENT,
  module varchar(255) NOT NULL,
  user_id int(11) DEFAULT NULL,
  user_name varchar(255) DEFAULT NULL,
  identifier varchar(255) NOT NULL,
  success char(1) DEFAULT NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (id),
  KEY idx_action_recorder_module (module),
  KEY idx_action_recorder_user_id (user_id),
  KEY idx_action_recorder_identifier (identifier),
  KEY idx_action_recorder_date_added (date_added)
)  CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS address_book;
CREATE TABLE address_book (
  address_book_id int(11) NOT NULL AUTO_INCREMENT,
  customers_id int(11) NOT NULL,
  entry_gender char(1) DEFAULT NULL,
  entry_company varchar(255) DEFAULT NULL,
  entry_firstname varchar(255) NOT NULL,
  entry_lastname varchar(255) NOT NULL,
  entry_street_address varchar(255) NOT NULL,
  entry_suburb varchar(255) DEFAULT NULL,
  entry_postcode varchar(255) NOT NULL,
  entry_city varchar(255) NOT NULL,
  entry_state varchar(255) DEFAULT NULL,
  entry_country_id int(11) NOT NULL DEFAULT '0',
  entry_zone_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (address_book_id),
  KEY idx_address_book_customers_id (customers_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS address_format;
CREATE TABLE address_format (
  address_format_id int(11) NOT NULL AUTO_INCREMENT,
  address_format varchar(128) NOT NULL,
  address_summary varchar(48) NOT NULL,
  PRIMARY KEY (address_format_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS administrators;
CREATE TABLE administrators (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_name varchar(255) NOT NULL,
  user_password varchar(60) NOT NULL,
  PRIMARY KEY (id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS am_attributes_to_templates;
CREATE TABLE am_attributes_to_templates (
  template_id int(5) unsigned NOT NULL,
  options_id int(5) unsigned NOT NULL,
  option_values_id int(5) unsigned NOT NULL,
  price_prefix char(1) DEFAULT '+',
  options_values_price decimal(15,4) DEFAULT '0.0000',
  products_options_sort_order int(11) DEFAULT '0',
  weight_prefix char(1) DEFAULT '+',
  options_values_weight decimal(6,3) DEFAULT '0.000',
  KEY template_id (template_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS am_templates;
CREATE TABLE am_templates (
  template_id int(5) unsigned NOT NULL AUTO_INCREMENT,
  template_name varchar(255) NOT NULL,
  PRIMARY KEY (template_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS banned_ip;
CREATE TABLE banned_ip (
  id_banned int(3) NOT NULL AUTO_INCREMENT,
  bannedip varchar(15) DEFAULT NULL,
  PRIMARY KEY (id_banned)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS banners;
CREATE TABLE banners (
  banners_id int(11) NOT NULL AUTO_INCREMENT,
  banners_title varchar(64) NOT NULL,
  banners_url varchar(255) NOT NULL,
  banners_image varchar(64) NOT NULL,
  banners_group varchar(10) NOT NULL,
  banners_html_text text,
  expires_impressions int(7) DEFAULT '0',
  expires_date datetime DEFAULT NULL,
  date_scheduled datetime DEFAULT NULL,
  date_added datetime NOT NULL,
  date_status_change datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (banners_id),
  KEY idx_banners_group (banners_group)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS banners_history;
CREATE TABLE banners_history (
  banners_history_id int(11) NOT NULL AUTO_INCREMENT,
  banners_id int(11) NOT NULL,
  banners_shown int(5) NOT NULL DEFAULT '0',
  banners_clicked int(5) NOT NULL DEFAULT '0',
  banners_history_date datetime NOT NULL,
  PRIMARY KEY (banners_history_id),
  KEY idx_banners_history_banners_id (banners_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  cache_id varchar(32) NOT NULL DEFAULT '',
  cache_language_id tinyint(1) NOT NULL DEFAULT '0',
  cache_name varchar(255) NOT NULL DEFAULT '',
  cache_data mediumtext NOT NULL,
  cache_global tinyint(1) NOT NULL DEFAULT '1',
  cache_gzip tinyint(1) NOT NULL DEFAULT '1',
  cache_method varchar(20) NOT NULL DEFAULT 'RETURN',
  cache_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  cache_expires datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (cache_id,cache_language_id),
  KEY cache_id (cache_id),
  KEY cache_language_id (cache_language_id),
  KEY cache_global (cache_global)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS catediscount;
CREATE TABLE catediscount (
  catediscount_id int(11) NOT NULL AUTO_INCREMENT,
  catediscount_name varchar(128) NOT NULL DEFAULT '',
  catediscount_groups_id int(11) NOT NULL DEFAULT '0',
  catediscount_customers_id int(11) NOT NULL DEFAULT '0',
  catediscount_categories_id int(11) NOT NULL DEFAULT '0',
  catediscount_discount decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (catediscount_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  categories_id int(11) NOT NULL AUTO_INCREMENT,
  categories_image varchar(64) DEFAULT NULL,
  parent_id int(11) NOT NULL DEFAULT '0',
  sort_order int(3) DEFAULT NULL,
  date_added datetime DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  categories_status tinyint(1) unsigned NOT NULL DEFAULT '0',
  categories_hide_from_groups varchar(255) NOT NULL DEFAULT '@',
  categories_to_stores varchar(255) NOT NULL DEFAULT '@',  
  PRIMARY KEY (categories_id),
  KEY idx_categories_parent_id (parent_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS categories_description;
CREATE TABLE categories_description (
  categories_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  categories_name varchar(32) NOT NULL,
  categories_htc_title_tag varchar(80) DEFAULT NULL,
  categories_htc_title_tag_alt varchar(80) DEFAULT NULL,
  categories_htc_title_tag_url varchar(80) DEFAULT NULL,  
  categories_htc_breadcrumb_text varchar(80) DEFAULT NULL,   
  categories_htc_desc_tag longtext,
  categories_htc_keywords_tag longtext,
  categories_htc_description longtext,
  PRIMARY KEY (categories_id,language_id),
  KEY idx_categories_name (categories_name)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS catemanudiscount;
CREATE TABLE catemanudiscount (
  catemanudiscount_id int(11) NOT NULL AUTO_INCREMENT,
  catemanudiscount_name varchar(128) NOT NULL DEFAULT '',
  catemanudiscount_groups_id int(11) NOT NULL DEFAULT '0',
  catemanudiscount_customers_id int(11) NOT NULL DEFAULT '0',
  catemanudiscount_categories_id int(11) NOT NULL DEFAULT '0',
  catemanudiscount_manufacturers_id int(11) NOT NULL DEFAULT '0',
  catemanudiscount_discount decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (catemanudiscount_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS configuration;
DROP TABLE IF EXISTS configuration_std;
CREATE TABLE configuration (
  configuration_id int(11) NOT NULL AUTO_INCREMENT,
  configuration_title varchar(255) NOT NULL,
  configuration_key varchar(255) NOT NULL,
  configuration_value text NOT NULL,
  configuration_description varchar(255) NOT NULL,
  configuration_group_id int(11) NOT NULL,
  sort_order int(5) DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  date_added datetime NOT NULL,
  use_function varchar(255) DEFAULT NULL,
  set_function varchar(255) DEFAULT NULL,
  PRIMARY KEY (configuration_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS configuration_group;
CREATE TABLE configuration_group (
  configuration_group_id int NOT NULL auto_increment,
  languages_id int NOT NULL,
  configuration_group_title varchar(64) NOT NULL,
  configuration_group_description varchar(255) NOT NULL,
  sort_order int(5) NULL,
  visible int(1) DEFAULT '1' NULL,
  PRIMARY KEY (configuration_group_id, languages_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS configuration_languages;
CREATE TABLE configuration_languages (
  configuration_id int(11) NOT NULL AUTO_INCREMENT,
  languages_id int(11) NOT NULL,
  configuration_title varchar(255) NOT NULL,
  configuration_description varchar(255) NOT NULL,
  configuration_group_id int(11) NOT NULL,
  configuration_sort_order int(11) NOT NULL,
  PRIMARY KEY (configuration_id,languages_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS configuration_languages_default;
CREATE TABLE configuration_languages_default (
  configuration_id int(11) NOT NULL AUTO_INCREMENT,
  languages_id int(11) NOT NULL,
  configuration_title varchar(255) NOT NULL,
  configuration_description varchar(255) NOT NULL,
  configuration_group_id int(11) NOT NULL,
  configuration_sort_order int(11) NOT NULL,
  PRIMARY KEY (configuration_id,languages_id)
);

DROP TABLE IF EXISTS counter;
CREATE TABLE counter (
  startdate char(8) DEFAULT NULL,
  counter int(12) DEFAULT NULL
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS counter_history;
CREATE TABLE counter_history (
  `month` char(8) DEFAULT NULL,
  counter int(12) DEFAULT NULL
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS countries;
CREATE TABLE countries (
  countries_id int(11) NOT NULL AUTO_INCREMENT,
  countries_name varchar(255) NOT NULL,
  countries_iso_code_2 char(2) NOT NULL,
  countries_iso_code_3 char(3) NOT NULL,
  address_format_id int(11) NOT NULL,
  PRIMARY KEY (countries_id),
  KEY IDX_COUNTRIES_NAME (countries_name)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS cron_simulator;
CREATE TABLE cron_simulator (
  enabled tinyint(1) NOT NULL DEFAULT '0',
  script_name varchar(64) NOT NULL DEFAULT '',
  file_name varchar(64) NOT NULL DEFAULT '',
  time_to_run varchar(4) NOT NULL DEFAULT '',
  frequency varchar(32) NOT NULL DEFAULT 'Daily',
  last_updated datetime NOT NULL DEFAULT '2010-09-01 00:00:00'
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS currencies;
CREATE TABLE currencies (
  currencies_id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(32) NOT NULL,
  code char(3) NOT NULL,
  symbol_left varchar(12) DEFAULT NULL,
  symbol_right varchar(12) DEFAULT NULL,
  decimal_point char(1) DEFAULT NULL,
  thousands_point char(1) DEFAULT NULL,
  decimal_places char(1) DEFAULT NULL,
  value float(13,8) DEFAULT NULL,
  last_updated datetime DEFAULT NULL,
  currencies_to_stores varchar(255) NOT NULL DEFAULT '@',
  PRIMARY KEY (currencies_id),
  KEY idx_currencies_code (`code`)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers;
CREATE TABLE customers (
  customers_id int(11) NOT NULL AUTO_INCREMENT,
  customers_gender char(1) DEFAULT NULL,
  customers_firstname varchar(255) NOT NULL,
  customers_lastname varchar(255) NOT NULL,
  customers_dob datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  customers_email_address varchar(255) NOT NULL,
  customers_default_address_id int(11) DEFAULT NULL,
  customers_telephone varchar(255) NOT NULL,
  customers_fax varchar(255) DEFAULT NULL,
  customers_password varchar(60) NOT NULL,
  customers_newsletter char(1) DEFAULT NULL,
  customers_group_id smallint(5) unsigned NOT NULL DEFAULT '0',
  customers_group_ra enum('0','1') NOT NULL,
  customers_payment_allowed varchar(255) NOT NULL DEFAULT '',
  customers_shipment_allowed varchar(255) NOT NULL DEFAULT '',
  customers_order_total_allowed varchar(255) NOT NULL DEFAULT '',
  customers_specific_taxes_exempt varchar(255) NOT NULL DEFAULT '',
  entry_company_tax_id varchar(32) DEFAULT NULL,
  customers_discount decimal(8,2) NOT NULL DEFAULT '0.00',
  customers_suspended enum('True','False') DEFAULT "False",
  customers_stores_id int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (customers_id),
  KEY idx_customers_email_address (customers_email_address)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers_basket;
CREATE TABLE customers_basket (
  customers_basket_id int(11) NOT NULL AUTO_INCREMENT,
  customers_id int(11) NOT NULL,
  products_id tinytext NOT NULL,
  customers_basket_quantity int(2) NOT NULL,
  final_price decimal(15,4) DEFAULT NULL,
  customers_basket_date_added char(8) DEFAULT NULL,
  PRIMARY KEY (customers_basket_id),
  KEY idx_customers_basket_customers_id (customers_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers_basket_attributes;
CREATE TABLE customers_basket_attributes (
  customers_basket_attributes_id int(11) NOT NULL AUTO_INCREMENT,
  customers_id int(11) NOT NULL,
  products_id tinytext NOT NULL,
  products_options_id int(11) NOT NULL,
  products_options_value_id int(11) NOT NULL,
  products_options_value_text text,
  PRIMARY KEY (customers_basket_attributes_id),
  KEY idx_customers_basket_att_customers_id (customers_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers_groups;
CREATE TABLE customers_groups (
  customers_group_id smallint(5) unsigned NOT NULL,
  customers_group_name varchar(32) NOT NULL DEFAULT '',
  customers_group_show_tax enum('1','0') NOT NULL,
  customers_group_tax_exempt enum('0','1') NOT NULL,
  group_payment_allowed varchar(255) NOT NULL DEFAULT '',
  group_shipment_allowed varchar(255) NOT NULL DEFAULT '',
  group_order_total_allowed varchar(255) NOT NULL DEFAULT '',
  group_specific_taxes_exempt varchar(255) NOT NULL DEFAULT '',
  customers_group_discount decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (customers_group_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS customers_info;
CREATE TABLE customers_info (
  customers_info_id int(11) NOT NULL,
  customers_info_date_of_last_logon datetime DEFAULT NULL,
  customers_info_number_of_logons int(5) DEFAULT NULL,
  customers_info_date_account_created datetime DEFAULT NULL,
  customers_info_date_account_last_modified datetime DEFAULT NULL,
  global_product_notifications int(1) DEFAULT '0',
  password_reset_key char(40) DEFAULT NULL,
  password_reset_date datetime DEFAULT NULL,
  PRIMARY KEY (customers_info_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers_searches;
CREATE TABLE customers_searches (
  search varchar(255) DEFAULT NULL,
  language_id int(11) unsigned DEFAULT NULL,
  freq int(10) unsigned DEFAULT '0',
  search_id int(10) NOT NULL AUTO_INCREMENT,
  stores_id int(11) NOT NULL DEFAULT 1,  
  PRIMARY KEY (search_id,stores_id),
  KEY lang (language_id,stores_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers_to_discount_codes;
CREATE TABLE customers_to_discount_codes (
  customers_id int(11) NOT NULL DEFAULT '0',
  discount_codes_id int(11) NOT NULL DEFAULT '0',
  KEY discount_codes_id (discount_codes_id),
  KEY customers_id (customers_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers_wishlist;
CREATE TABLE customers_wishlist (
  customers_wishlist_id int unsigned NOT NULL auto_increment,
  customers_id int unsigned NOT NULL default '0',
  products_id tinytext NOT NULL,
  PRIMARY KEY  (customers_wishlist_id),
  KEY idx_wishlist_customers_id (customers_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS customers_wishlist_attributes;
CREATE TABLE customers_wishlist_attributes (
  customers_wishlist_attributes_id int unsigned NOT NULL auto_increment,
  customers_id int unsigned NOT NULL default '0',
  products_id tinytext NOT NULL,
  products_options_id int unsigned NOT NULL default '0',
  products_options_value_id int unsigned NOT NULL default '0',
  PRIMARY KEY  (customers_wishlist_attributes_id),
  KEY idx_wishlist_att_customers_id (customers_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS database_optimizer;
CREATE TABLE `database_optimizer` (
  `last_update` date NOT NULL,
  `customers_last_update` date NOT NULL,
  `customers_old_last_update` date NOT NULL,
  `orders_last_update` date NOT NULL,
  `orphan_addr_book_last_update` date NOT NULL,
  `products_notifications_last_update` date NOT NULL,
  `sessions_last_update` date NOT NULL,
  `user_tracking_last_update` date NOT NULL
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS discount_categories;
CREATE TABLE discount_categories (
  discount_categories_id int(11) NOT NULL AUTO_INCREMENT,
  discount_categories_name varchar(255) NOT NULL,
  PRIMARY KEY (discount_categories_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS discount_codes;
CREATE TABLE discount_codes (
  discount_codes_id int(11) NOT NULL AUTO_INCREMENT,
  products_id text,
  categories_id text,
  manufacturers_id text,
  excluded_products_id text,
  customers_id text,
  orders_total tinyint(1) NOT NULL DEFAULT '0',
  order_info tinyint(1) NOT NULL,
  exclude_specials tinyint(1) NOT NULL,
  discount_codes varchar(8) NOT NULL DEFAULT '',
  discount_values varchar(8) NOT NULL DEFAULT '',
  minimum_order_amount decimal(15,4) NOT NULL DEFAULT '0.0000',
  expires_date datetime NOT NULL DEFAULT '0000-00-00',
  number_of_orders int(4) NOT NULL DEFAULT '0',
  number_of_use int(4) NOT NULL,
  number_of_products int(4) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (discount_codes_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS eorder_text;
CREATE TABLE eorder_text (
  eorder_text_id tinyint(3) unsigned NOT NULL DEFAULT '0',
  language_id tinyint(3) unsigned NOT NULL DEFAULT '1',
  eorder_text_one text,
  eorder_title varchar(64) NOT NULL,
  stores_id int(11) NOT NULL,
  PRIMARY KEY (eorder_text_id,language_id, stores_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS faq;
CREATE TABLE faq (
  faq_id int(11) NOT NULL AUTO_INCREMENT,
  faq_status tinyint(1) NOT NULL DEFAULT '1',
  sort_order int(11) NOT NULL DEFAULT '0',
  last_modified datetime NOT NULL,
  PRIMARY KEY (faq_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS faq_description;
CREATE TABLE faq_description (
  faq_id int(11) NOT NULL,
  language_id int(11) NOT NULL,
  faq_question text,
  faq_answer text,
  PRIMARY KEY (faq_id,language_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS files_uploaded;
CREATE TABLE files_uploaded (
  files_uploaded_id int(11) NOT NULL AUTO_INCREMENT,
  sesskey varchar(32) DEFAULT NULL,
  customers_id int(11) DEFAULT NULL,
  files_uploaded_name varchar(64) NOT NULL,
  `date` varchar(32) DEFAULT NULL,
  PRIMARY KEY (files_uploaded_id)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;



DROP TABLE IF EXISTS geo_zones;
CREATE TABLE geo_zones (
  geo_zone_id int(11) NOT NULL AUTO_INCREMENT,
  geo_zone_name varchar(32) NOT NULL,
  geo_zone_description varchar(255) NOT NULL,
  last_modified datetime DEFAULT NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (geo_zone_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS headertags;
CREATE TABLE headertags (
  page_name varchar(64) NOT NULL DEFAULT '',
  page_title varchar(120) NOT NULL DEFAULT '',
  page_description varchar(255) NOT NULL DEFAULT '',
  page_keywords varchar(255) NOT NULL DEFAULT '',
  page_logo varchar(255) NOT NULL DEFAULT '',
  page_logo_1 varchar(255) NOT NULL DEFAULT '',
  page_logo_2 varchar(255) NOT NULL DEFAULT '',
  page_logo_3 varchar(255) NOT NULL DEFAULT '',
  page_logo_4 varchar(255) NOT NULL DEFAULT '',
  append_default_title tinyint(1) NOT NULL DEFAULT '0',
  append_default_description tinyint(1) NOT NULL DEFAULT '0',
  append_default_keywords tinyint(1) NOT NULL DEFAULT '0',
  append_default_logo tinyint(1) NOT NULL DEFAULT '0',
  append_category tinyint(1) NOT NULL DEFAULT '0',
  append_manufacturer tinyint(1) NOT NULL DEFAULT '0',
  append_model tinyint(1) NOT NULL DEFAULT '0',
  append_product tinyint(1) NOT NULL DEFAULT '1',
  append_root tinyint(1) NOT NULL DEFAULT '1',
  sortorder_title tinyint(2) NOT NULL DEFAULT '0',
  sortorder_description tinyint(2) NOT NULL DEFAULT '0',
  sortorder_keywords tinyint(2) NOT NULL DEFAULT '0',
  sortorder_logo tinyint(2) NOT NULL DEFAULT '0',
  sortorder_logo_1 tinyint(2) NOT NULL DEFAULT '0',
  sortorder_logo_2 tinyint(2) NOT NULL DEFAULT '0',
  sortorder_logo_3 tinyint(2) NOT NULL DEFAULT '0',
  sortorder_logo_4 tinyint(2) NOT NULL DEFAULT '0',
  sortorder_category tinyint(2) NOT NULL DEFAULT '0',
  sortorder_manufacturer tinyint(2) NOT NULL DEFAULT '0',
  sortorder_model tinyint(2) NOT NULL DEFAULT '0',
  sortorder_product tinyint(2) NOT NULL DEFAULT '10',
  sortorder_root tinyint(2) NOT NULL DEFAULT '1',
  sortorder_root_1 tinyint(2) NOT NULL DEFAULT '1',
  sortorder_root_2 tinyint(2) NOT NULL DEFAULT '1',
  sortorder_root_3 tinyint(2) NOT NULL DEFAULT '1',
  sortorder_root_4 tinyint(2) NOT NULL DEFAULT '1',
  language_id int(11) NOT NULL DEFAULT '1',
  stores_id int(11) NOT NULL,  
  PRIMARY KEY idx_page_name ( page_name,language_id,stores_id ),
  INDEX idx_page_description ( page_description,stores_id ),
  INDEX idx_page_keywords ( page_keywords,stores_id )
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS headertags_cache;
CREATE TABLE headertags_cache (
  title text,
  `data` text
);

DROP TABLE IF EXISTS headertags_default;
CREATE TABLE headertags_default (
  default_title varchar(255) NOT NULL DEFAULT '',
  default_description varchar(255) NOT NULL DEFAULT '',
  default_keywords varchar(255) NOT NULL DEFAULT '',
  default_logo_text varchar(255) NOT NULL DEFAULT '',
  home_page_text TEXT NOT NULL default '', 
  default_logo_append_group tinyint(1) NOT NULL DEFAULT '1',
  default_logo_append_category tinyint(1) NOT NULL DEFAULT '1',
  default_logo_append_manufacturer tinyint(1) NOT NULL DEFAULT '1',
  default_logo_append_product tinyint(1) NOT NULL DEFAULT '1',
  meta_google tinyint(1) NOT NULL DEFAULT '0',
  meta_language tinyint(1) NOT NULL DEFAULT '0',
  meta_noodp tinyint(1) NOT NULL DEFAULT '1',
  meta_noydir tinyint(1) NOT NULL DEFAULT '1',
  meta_replyto tinyint(1) NOT NULL DEFAULT '0',
  meta_revisit tinyint(1) NOT NULL DEFAULT '0',
  meta_robots tinyint(1) NOT NULL DEFAULT '0',
  meta_unspam tinyint(1) NOT NULL DEFAULT '0',
  meta_canonical tinyint(1) NOT NULL DEFAULT '0',
  meta_og tinyint( 1 ) NOT NULL default 1,
  language_id int(11) NOT NULL DEFAULT '1',
  stores_id int(11) NOT NULL,
  PRIMARY KEY idx_default_title ( default_title, language_id ,stores_id),
  INDEX idx_meta_canonical ( meta_canonical,stores_id )
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS headertags_keywords;
CREATE TABLE headertags_keywords (
  id int(11) NOT NULL AUTO_INCREMENT,
  keyword varchar(120) NOT NULL DEFAULT '',
  counter int(11) NOT NULL DEFAULT '1',
  last_search datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  google_last_position tinyint(4) NOT NULL,
  google_date_position_check datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `found` tinyint(1) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  stores_id int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (id,stores_id),
  KEY keyword (keyword,stores_id),
  KEY `found` (`found`)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS headertags_search;
CREATE TABLE headertags_search (
  product_id int(11) NOT NULL,
  keyword varchar(64) NOT NULL,
  language_id int(11) NOT NULL,
  stores_id int(11) NOT NULL DEFAULT 1,
  KEY keyword (keyword,stores_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS headertags_social;
CREATE TABLE headertags_social (
  unique_id INT ( 4 ) NOT NULL AUTO_INCREMENT , 
  section VARCHAR( 48 ) NOT NULL , 
  groupname VARCHAR (24 ) NOT NULL, 
  url VARCHAR ( 255 ) NOT NULL, 
  data TEXT NOT NULL , 
  stores_id int(11) NOT NULL DEFAULT 1,
   PRIMARY KEY (unique_id), 
   KEY idx_section (section)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS headertags_silo;
CREATE TABLE headertags_silo (
  category_id int(11) NOT NULL DEFAULT '0',
  box_heading varchar(60) NOT NULL,
  is_disabled tinyint(1) NOT NULL DEFAULT '0',
  max_links int(11) NOT NULL DEFAULT '6',
  sorton tinyint(2) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  stores_id int(11) NOT NULL, 
  PRIMARY KEY (category_id,language_id,stores_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS information;
CREATE TABLE information (
  information_id tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  information_group_id int(11) unsigned NOT NULL DEFAULT '0',
  information_title varchar(255) NOT NULL DEFAULT '',
  information_description text NOT NULL,
  parent_id int(11) DEFAULT NULL,
  sort_order tinyint(3) unsigned NOT NULL DEFAULT '0',
  visible enum('1','0') NOT NULL DEFAULT '1',
  language_id int(11) NOT NULL DEFAULT '0',
  stores_id int(11) NOT NULL DEFAULT '1',  
  PRIMARY KEY (information_id,language_id,stores_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS information_group;
CREATE TABLE information_group (
  information_group_id int(11) NOT NULL AUTO_INCREMENT,
  information_group_title varchar(64) NOT NULL DEFAULT '',
  information_group_description varchar(255) NOT NULL DEFAULT '',
  sort_order int(5) DEFAULT NULL,
  visible int(1) DEFAULT '1',
  locked varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (information_group_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS languages;
CREATE TABLE languages (
  languages_id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(32) NOT NULL,
  code char(2) NOT NULL,
  image varchar(64) DEFAULT NULL,
  directory varchar(32) DEFAULT NULL,
  sort_order int(3) DEFAULT NULL,
  languages_to_stores varchar(255) NOT NULL DEFAULT '@',
  PRIMARY KEY (languages_id),
  KEY IDX_LANGUAGES_NAME (name)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS location_map;
CREATE TABLE location_map (
  locationmap_id int(11) NOT NULL AUTO_INCREMENT,
  locationmap_name varchar(32) NOT NULL,
  locationmap_image varchar(64) DEFAULT NULL,
  date_added datetime DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  locationmap_to_stores varchar(255) NOT NULL DEFAULT '@',
  locationmap_address varchar(32) NOT NULL,
  locationmap_zoom int(11) NOT NULL DEFAULT '14',
  PRIMARY KEY (locationmap_id),
  KEY IDX_LOCATIONMAP (locationmap_name)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS location_map_info;
CREATE TABLE IF NOT EXISTS location_map_info (
  locationmap_id int(11) NOT NULL,
  languages_id int(11) NOT NULL,
  locationmap_url  varchar(255) NOT NULL,
  url_clicked  int(5) NOT NULL DEFAULT '0',
  date_last_click  datetime DEFAULT NULL,
  location_text_map longtext,
  location_text_marker  longtext,
  PRIMARY KEY (locationmap_id,languages_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS manudiscount;
CREATE TABLE manudiscount (
  manudiscount_id int(11) NOT NULL AUTO_INCREMENT,
  manudiscount_name varchar(128) NOT NULL DEFAULT '',
  manudiscount_groups_id int(11) NOT NULL DEFAULT '0',
  manudiscount_customers_id int(11) NOT NULL DEFAULT '0',
  manudiscount_manufacturers_id int(11) NOT NULL DEFAULT '0',
  manudiscount_discount decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (manudiscount_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS manufacturers;
CREATE TABLE manufacturers (
  manufacturers_id int(11) NOT NULL AUTO_INCREMENT,
  manufacturers_name varchar(32) NOT NULL,
  manufacturers_image varchar(64) DEFAULT NULL,
  date_added datetime DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  manufacturers_to_stores varchar(255) NOT NULL DEFAULT '@',  
  PRIMARY KEY (manufacturers_id),
  KEY IDX_MANUFACTURERS_NAME (manufacturers_name)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS manufacturers_info;
CREATE TABLE manufacturers_info (
  manufacturers_id int(11) NOT NULL,
  languages_id int(11) NOT NULL,
  manufacturers_url varchar(255) NOT NULL,
  url_clicked int(5) NOT NULL DEFAULT '0',
  date_last_click datetime DEFAULT NULL,
  manufacturers_htc_title_tag varchar(80) DEFAULT NULL,
  manufacturers_htc_title_tag_alt varchar(80) DEFAULT NULL,
  manufacturers_htc_title_tag_url varchar(80) DEFAULT NULL,
  manufacturers_htc_breadcrumb_text varchar(80) DEFAULT NULL,  
  manufacturers_htc_desc_tag longtext,
  manufacturers_htc_keywords_tag longtext,
  manufacturers_htc_description longtext,
  manufacturers_to_stores varchar(255) NOT NULL DEFAULT '@',  
  PRIMARY KEY (manufacturers_id,languages_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS newsletters;
CREATE TABLE newsletters (
  newsletters_id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  content text NOT NULL,
  module varchar(255) NOT NULL,
  date_added datetime NOT NULL,
  date_sent datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  locked int(1) DEFAULT '0',
  send_to_customer_groups varchar(32) DEFAULT NULL,
  send_to_stores varchar(32) DEFAULT NULL,
  newsletters_unsubscribe_text varchar(255) NOT NULL,  
  PRIMARY KEY (newsletters_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS newsletter_subscription;
CREATE TABLE `newsletter_subscription` (
  `subscription_id` int(11) NOT NULL AUTO_INCREMENT, 
  `subscription_email_address` varchar(80) NOT NULL DEFAULT '',
  `subscription_name` varchar(255) NOT NULL,
  `subscription_date_creation` datetime DEFAULT '0000-00-00 00:00:00',
  `subscription_newsletter` int(4) DEFAULT NULL,
  `subscription_customers_group_id` smallint(5) DEFAULT '0',
  `subscription_stores_id` int(11) DEFAULT '1',
  PRIMARY KEY (`subscription_id`,`subscription_email_address`)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
  orders_id int(11) NOT NULL AUTO_INCREMENT,
  customers_id int(11) NOT NULL,
  customers_name varchar(255) NOT NULL,
  customers_company varchar(255) DEFAULT NULL,
  customers_street_address varchar(255) NOT NULL,
  customers_suburb varchar(255) DEFAULT NULL,
  customers_city varchar(255) NOT NULL,
  customers_postcode varchar(255) NOT NULL,
  customers_state varchar(255) DEFAULT NULL,
  customers_country varchar(255) NOT NULL,
  customers_telephone varchar(255) NOT NULL,
  customers_email_address varchar(255) NOT NULL,
  customers_address_format_id int(5) NOT NULL,
  delivery_name varchar(255) NOT NULL,
  delivery_company varchar(255) DEFAULT NULL,
  delivery_street_address varchar(255) NOT NULL,
  delivery_suburb varchar(255) DEFAULT NULL,
  delivery_city varchar(255) NOT NULL,
  delivery_postcode varchar(255) NOT NULL,
  delivery_state varchar(255) DEFAULT NULL,
  delivery_country varchar(255) NOT NULL,
  delivery_address_format_id int(5) NOT NULL,
  billing_name varchar(255) NOT NULL,
  billing_company varchar(255) DEFAULT NULL,
  billing_street_address varchar(255) NOT NULL,
  billing_suburb varchar(255) DEFAULT NULL,
  billing_city varchar(255) NOT NULL,
  billing_postcode varchar(255) NOT NULL,
  billing_state varchar(255) DEFAULT NULL,
  billing_country varchar(255) NOT NULL,
  billing_address_format_id int(5) NOT NULL,
  payment_method varchar(255) NOT NULL,
  cc_type varchar(20) DEFAULT NULL,
  cc_owner varchar(255) DEFAULT NULL,
  cc_number varchar(32) DEFAULT NULL,
  cc_expires varchar(4) DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  date_purchased datetime DEFAULT NULL,
  orders_status int(5) NOT NULL,
  orders_date_finished datetime DEFAULT NULL,
  currency char(3) DEFAULT NULL,
  currency_value decimal(14,6) DEFAULT NULL,
  shipping_module varchar(255) DEFAULT NULL,
  customer_service_id varchar(15) DEFAULT NULL,
  billing_stores_id int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (orders_id),
  KEY idx_orders_customers_id (customers_id),
  KEY customers_state (customers_state),
  KEY orders_status (orders_status),
  KEY customers_postcode (customers_postcode),
  KEY customers_postcode_2 (customers_postcode)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_products;
CREATE TABLE orders_products (
  orders_products_id int(11) NOT NULL AUTO_INCREMENT,
  orders_id int(11) NOT NULL,
  products_id int(11) NOT NULL,
  products_model varchar(12) DEFAULT NULL,
  products_name varchar(64) NOT NULL,
  products_price decimal(15,4) NOT NULL,
  products_cost decimal(15,4) NOT NULL DEFAULT '0.0000',
  final_price decimal(15,4) NOT NULL,
  products_tax decimal(7,4) NOT NULL,
  products_quantity int(2) NOT NULL,
  products_stock_attributes varchar(255) DEFAULT NULL,
  PRIMARY KEY (orders_products_id),
  KEY idx_orders_products_orders_id (orders_id),
  KEY idx_orders_products_products_id (products_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_products_attributes;
CREATE TABLE orders_products_attributes (
  orders_products_attributes_id int(11) NOT NULL AUTO_INCREMENT,
  orders_id int(11) NOT NULL,
  orders_products_id int(11) NOT NULL,
  products_options varchar(32) NOT NULL,
  products_options_values varchar(32) NOT NULL,
  options_values_price decimal(15,4) NOT NULL,
  price_prefix char(1) NOT NULL,
  PRIMARY KEY (orders_products_attributes_id),
  KEY idx_orders_products_att_orders_id (orders_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_products_download;
CREATE TABLE orders_products_download (
  orders_products_download_id int(11) NOT NULL AUTO_INCREMENT,
  orders_id int(11) NOT NULL DEFAULT '0',
  orders_products_id int(11) NOT NULL DEFAULT '0',
  orders_products_filename varchar(255) NOT NULL DEFAULT '',
  download_maxdays int(2) NOT NULL DEFAULT '0',
  download_count int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (orders_products_download_id),
  KEY idx_orders_products_download_orders_id (orders_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_status;
CREATE TABLE orders_status (
  orders_status_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  orders_status_name varchar(32) NOT NULL,
  public_flag int(11) DEFAULT '1',
  downloads_flag int(11) DEFAULT '0',
  orders_status_sort_order int(3) NOT NULL DEFAULT '999',  
  PRIMARY KEY (orders_status_id,language_id),
  KEY idx_orders_status_name (orders_status_name)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_status_history;
CREATE TABLE orders_status_history (
  orders_status_history_id int(11) NOT NULL AUTO_INCREMENT,
  orders_id int(11) NOT NULL,
  orders_status_id int(5) NOT NULL,
  date_added datetime NOT NULL,
  customer_notified int(1) DEFAULT '0',
  comments text,
  track_num varchar(20) DEFAULT NULL,
  track_pcode varchar(6) DEFAULT NULL,
  PRIMARY KEY (orders_status_history_id),
  KEY idx_orders_status_history_orders_id (orders_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS orders_total;
CREATE TABLE orders_total (
  orders_total_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  orders_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL,
  class varchar(32) NOT NULL,
  sort_order int(11) NOT NULL,
  PRIMARY KEY (orders_total_id),
  KEY idx_orders_total_orders_id (orders_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS orders_to_latlng;
CREATE TABLE orders_to_latlng (
  id int(11) NOT NULL AUTO_INCREMENT,
  orders_id int(11) NOT NULL,
  lat double NOT NULL,
  lng double NOT NULL,
  PRIMARY KEY (id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS popups;
CREATE TABLE IF NOT EXISTS `popups` (
  `popups_id` int(11) NOT NULL AUTO_INCREMENT,
  `popups_title` varchar(64) NOT NULL,
  `popups_url` varchar(255) NOT NULL,
  `popups_image` varchar(64) NOT NULL,
  `popups_html_text` text,
  `expires_date` datetime DEFAULT NULL,
  `date_scheduled` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `date_status_change` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `stores_id` varchar(255) NOT NULL DEFAULT '@', 
  PRIMARY KEY (`popups_id`)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

drop table if exists popups_description;
create table popups_description (
   popups_id int(11) not null,
   language_id int(11) default '1' not null,
   popups_html_text text 
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products;
CREATE TABLE products (
  products_id int(11) NOT NULL AUTO_INCREMENT,
  products_quantity int(4) NOT NULL,
  products_model varchar(12) DEFAULT NULL,
  products_image varchar(64) DEFAULT NULL,
  products_price decimal(15,4) NOT NULL,
  products_cost decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_date_added datetime NOT NULL,
  products_last_modified datetime DEFAULT NULL,
  products_date_available datetime DEFAULT NULL,
  products_weight decimal(5,2) NOT NULL,
  products_status tinyint(1) NOT NULL,
  products_purchase tinyint(1) NOT NULL  DEFAULT '1' ,
  products_tax_class_id int(11) NOT NULL,
  manufacturers_id int(11) DEFAULT NULL,
  products_ordered int(11) NOT NULL DEFAULT '0',
  products_qty_blocks int(11) NOT NULL DEFAULT '1',
  products_sort_order int(3) DEFAULT '999',
  payment_methods text,
  products_min_order_qty int(4) NOT NULL DEFAULT '1',
  products_hide_from_groups varchar(255) NOT NULL DEFAULT '@',
  products_instock_id varchar(255) DEFAULT NULL,
  products_nostock_id varchar(255) DEFAULT NULL,
  products_to_stores varchar(255) NOT NULL DEFAULT '@',  
  google_product_category int(11) NOT NULL,
  PRIMARY KEY (products_id),
  KEY idx_products_model (products_model),
  KEY idx_products_date_added (products_date_added)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS products_attributes;
CREATE TABLE products_attributes (
  products_attributes_id int(11) NOT NULL AUTO_INCREMENT,
  products_id int(11) NOT NULL,
  options_id int(11) NOT NULL,
  options_values_id int(11) NOT NULL,
  options_values_price decimal(15,4) NOT NULL,
  price_prefix char(1) NOT NULL,
  products_options_sort_order int(10) unsigned NOT NULL DEFAULT '0',
  attributes_hide_from_groups varchar(255) NOT NULL DEFAULT '@',
  PRIMARY KEY (products_attributes_id),
  KEY idx_products_attributes_products_id (products_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_attributes_download;
CREATE TABLE products_attributes_download (
  products_attributes_id int(11) NOT NULL,
  products_attributes_filename varchar(255) NOT NULL DEFAULT '',
  products_attributes_maxdays int(2) DEFAULT '0',
  products_attributes_maxcount int(2) DEFAULT '0',
  PRIMARY KEY (products_attributes_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_attributes_groups;
CREATE TABLE products_attributes_groups (
  products_attributes_id int(11) NOT NULL DEFAULT '0',
  customers_group_id smallint(5) NOT NULL DEFAULT '0',
  options_values_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  price_prefix char(1) NOT NULL DEFAULT '',
  products_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (customers_group_id,products_attributes_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_availability;
CREATE TABLE products_availability (
  products_availability_id int(11) NOT NULL AUTO_INCREMENT,
  language_id int(11) NOT NULL DEFAULT '1',
  products_availability_name varchar(255) NOT NULL DEFAULT '',
  products_availability_image varchar(64) DEFAULT NULL,
  date_added datetime DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  PRIMARY KEY (products_availability_id,language_id),
  KEY IDX_PRODUCTS_AVAILABILITY_NAME (products_availability_name)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_description;
CREATE TABLE products_description (
  products_id int(11) NOT NULL AUTO_INCREMENT,
  language_id int(11) NOT NULL DEFAULT '1',
  products_name varchar(64) NOT NULL DEFAULT '',
  products_description text,
  products_url varchar(255) DEFAULT NULL,
  products_viewed int(5) DEFAULT '0',
  products_head_title_tag varchar(80) DEFAULT NULL,
  products_head_breadcrumb_text varchar(80) DEFAULT NULL,
  products_head_title_tag_alt varchar(80) DEFAULT NULL,
  products_head_title_tag_url varchar(80) DEFAULT NULL,  
  products_head_desc_tag longtext,
  products_head_keywords_tag longtext,
  products_head_listing_text longtext,
  products_head_sub_text longtext,
  PRIMARY KEY (products_id,language_id),
  KEY products_name (products_name)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS products_viewed;
CREATE TABLE IF NOT EXISTS products_viewed (
  products_id int(11),
  language_id int(11) NOT NULL DEFAULT '1',
  products_viewed int(5) DEFAULT '0',
  stores_id INT(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (products_id,language_id,stores_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_groups;
CREATE TABLE products_groups (
  customers_group_id smallint(5) unsigned NOT NULL DEFAULT '0',
  customers_group_price decimal(15,4) DEFAULT NULL,
  products_id int(11) NOT NULL DEFAULT '0',
  products_qty_blocks int(4) NOT NULL DEFAULT '1',
  products_min_order_qty int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (customers_group_id,products_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_group_prices_cg_1;
CREATE TABLE products_group_prices_cg_1 (
  products_id int(11) NOT NULL DEFAULT '0',
  products_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  specials_new_products_price decimal(15,4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (products_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_google_taxonomy;
CREATE TABLE `products_google_taxonomy` (
  google_taxonomy_id int(11) NOT NULL,
  google_taxonomy_number varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  google_taxonomy_name varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  date_added datetime DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
   PRIMARY KEY (`google_taxonomy_id`),
   KEY `IDX_MANUFACTURERS_NAME` (`google_taxonomy_name`)
) CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS products_images;
CREATE TABLE products_images (
  id int(11) NOT NULL AUTO_INCREMENT,
  products_id int(11) NOT NULL,
  image varchar(64) DEFAULT NULL,
  htmlcontent text,
  sort_order int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY products_images_prodid (products_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_notifications;
CREATE TABLE products_notifications (
  products_id int(11) NOT NULL,
  customers_id int(11) NOT NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (products_id,customers_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_options;
CREATE TABLE products_options (
  products_options_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  products_options_name varchar(32) NOT NULL DEFAULT '',
  products_options_track_stock tinyint(4) NOT NULL DEFAULT '0',
  products_options_type int(5) NOT NULL,
  products_options_length smallint(2) NOT NULL DEFAULT '32',
  products_options_comment varchar(32) DEFAULT NULL,
  products_options_sort_order int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (products_options_id,language_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_options_values;
CREATE TABLE products_options_values (
  products_options_values_id int(11) NOT NULL DEFAULT '0',
  language_id int(11) NOT NULL DEFAULT '1',
  products_options_values_name varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (products_options_values_id,language_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_options_values_to_products_options;
CREATE TABLE products_options_values_to_products_options (
  products_options_values_to_products_options_id int(11) NOT NULL AUTO_INCREMENT,
  products_options_id int(11) NOT NULL,
  products_options_values_id int(11) NOT NULL,
  PRIMARY KEY (products_options_values_to_products_options_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_price_break;
CREATE TABLE products_price_break (
  products_price_break_id int(11) NOT NULL AUTO_INCREMENT,
  products_id int(11) NOT NULL,
  products_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_qty int(11) NOT NULL DEFAULT '0',
  customers_group_id smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (products_price_break_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_stock;
CREATE TABLE products_stock (
  products_stock_id int(11) NOT NULL AUTO_INCREMENT,
  products_id int(11) NOT NULL DEFAULT '0',
  products_stock_attributes varchar(255) NOT NULL,
  products_stock_quantity int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (products_stock_id),
  UNIQUE KEY idx_products_stock_attributes (products_id,products_stock_attributes)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_tags;
CREATE TABLE products_tags (
  products_id int(11) NOT NULL,
  tag_id int(10) NOT NULL
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_to_categories;
CREATE TABLE products_to_categories (
  products_id int(11) NOT NULL,
  categories_id int(11) NOT NULL,
  PRIMARY KEY (products_id,categories_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_to_discount_categories;
CREATE TABLE products_to_discount_categories (
  products_id int(11) NOT NULL,
  discount_categories_id int(11) NOT NULL,
  customers_group_id smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (products_id,customers_group_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS products_xsell;
CREATE TABLE products_xsell (
  ID int(10) NOT NULL AUTO_INCREMENT,
  products_id int(10) unsigned NOT NULL DEFAULT '1',
  xsell_id int(10) unsigned NOT NULL DEFAULT '1',
  sort_order int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (ID),
  KEY idx_products_id (products_id),
  KEY idx_xsell_id (xsell_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
  reviews_id int(11) NOT NULL AUTO_INCREMENT,
  products_id int(11) NOT NULL,
  customers_id int(11) DEFAULT NULL,
  customers_name varchar(255) NOT NULL,
  reviews_rating int(1) DEFAULT NULL,
  date_added datetime DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  reviews_status tinyint(1) NOT NULL DEFAULT '0',
  reviews_read int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (reviews_id),
  KEY idx_reviews_products_id (products_id),
  KEY idx_reviews_customers_id (customers_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS reviews_description;
CREATE TABLE reviews_description (
  reviews_id int(11) NOT NULL,
  languages_id int(11) NOT NULL,
  reviews_text text NOT NULL,
  PRIMARY KEY (reviews_id,languages_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS search_queries;
CREATE TABLE search_queries (
  search_id int(11) NOT NULL AUTO_INCREMENT,
  search_text tinytext,
  search_count int(11) NOT NULL DEFAULT '1',
  search_date datetime NOT NULL,
  stores_id INT(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (search_id,stores_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS sec_directory_whitelist;
CREATE TABLE sec_directory_whitelist (
  id int(11) NOT NULL AUTO_INCREMENT,
  `directory` varchar(255) NOT NULL,
  PRIMARY KEY (id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS sessions;
CREATE TABLE sessions (
  sesskey varchar(128) NOT NULL,
  expiry int(11) unsigned NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (sesskey)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS specials;
CREATE TABLE specials (
  specials_id int(11) NOT NULL AUTO_INCREMENT,
  products_id int(11) NOT NULL,
  specials_new_products_price decimal(15,4) NOT NULL,
  specials_date_added datetime DEFAULT NULL,
  specials_last_modified datetime DEFAULT NULL,
  expires_date datetime DEFAULT NULL,
  date_status_change datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  start_date datetime DEFAULT NULL,
  limit_specials_quantity int(4) NOT NULL DEFAULT '0',
  customers_group_id smallint(5) unsigned NOT NULL DEFAULT '0',
  stores_id int(11) NOT NULL,  
  PRIMARY KEY (specials_id),
  KEY idx_specials_products_id (products_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS specials_retail_prices;
CREATE TABLE specials_retail_prices (
  products_id int(11) NOT NULL DEFAULT '0',
  specials_new_products_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  `status` tinyint(4) DEFAULT NULL,
  customers_group_id smallint(6) DEFAULT NULL,
  stores_id int(11) NOT NULL,
  PRIMARY KEY (products_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS stores;
CREATE TABLE stores (
   stores_id int NOT NULL auto_increment,
   stores_name varchar(64) NOT NULL DEFAULT '',
   stores_image varchar(64),
   stores_url varchar(255) DEFAULT NULL,
   stores_absolute varchar(255) DEFAULT NULL,   
   stores_config_table varchar(64) NOT NULL,
   stores_status tinyint(1) NOT NULL,
   stores_std_cust_group smallint(5) NOT NULL DEFAULT 0,   
   date_added datetime,
   last_modified datetime,
   stores_admin_color varchar(255) DEFAULT 'default',  
   PRIMARY KEY (stores_id),
   UNIQUE (stores_config_table)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


DROP TABLE IF EXISTS supertracker;
CREATE TABLE supertracker (
  tracking_id bigint(20) NOT NULL AUTO_INCREMENT,
  ip_address varchar(15) NOT NULL DEFAULT '',
  browser_string varchar(255) NOT NULL DEFAULT '',
  browser varchar(100) NOT NULL DEFAULT '',
  country_code char(2) NOT NULL DEFAULT '',
  country_name varchar(100) NOT NULL DEFAULT '',
  country_region varchar(100) NOT NULL DEFAULT '',
  country_city varchar(100) NOT NULL DEFAULT '',
  customer_id int(11) NOT NULL DEFAULT '0',
  order_id int(11) NOT NULL DEFAULT '0',
  referrer varchar(255) NOT NULL DEFAULT '',
  referrer_query_string varchar(255) NOT NULL DEFAULT '',
  landing_page varchar(255) NOT NULL DEFAULT '',
  exit_page varchar(100) NOT NULL DEFAULT '',
  time_arrived datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_click datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  num_clicks int(11) NOT NULL DEFAULT '1',
  added_cart varchar(5) NOT NULL DEFAULT 'false',
  completed_purchase varchar(5) NOT NULL DEFAULT 'false',
  categories_viewed varchar(255) NOT NULL DEFAULT '',
  products_viewed varchar(255) NOT NULL DEFAULT '',
  cart_contents mediumtext NOT NULL,
  cart_total int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (tracking_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tags;
CREATE TABLE tags (
  tag_id int(10) NOT NULL AUTO_INCREMENT,
  tag_text varchar(50) NOT NULL,
  PRIMARY KEY (tag_id),
  UNIQUE KEY tag_text (tag_text)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tax_class;
CREATE TABLE tax_class (
  tax_class_id int(11) NOT NULL AUTO_INCREMENT,
  tax_class_title varchar(32) NOT NULL,
  tax_class_description varchar(255) NOT NULL,
  last_modified datetime DEFAULT NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (tax_class_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS tax_rates;
CREATE TABLE tax_rates (
  tax_rates_id int(11) NOT NULL AUTO_INCREMENT,
  tax_zone_id int(11) NOT NULL,
  tax_class_id int(11) NOT NULL,
  tax_priority int(5) DEFAULT '1',
  tax_rate decimal(7,4) NOT NULL,
  tax_description varchar(255) NOT NULL,
  last_modified datetime DEFAULT NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (tax_rates_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS usu_cache;
CREATE TABLE usu_cache (
  cache_name varchar(64) NOT NULL,
  cache_data mediumtext NOT NULL,
  cache_date datetime NOT NULL,
  UNIQUE KEY cache_name (cache_name)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS whos_online;
CREATE TABLE whos_online (
  customer_id int(11) DEFAULT NULL,
  full_name varchar(255) NOT NULL,
  session_id varchar(128) NOT NULL,
  ip_address varchar(15) NOT NULL,
  hostname varchar(255) NOT NULL,
  country_code varchar(2) NOT NULL,
  country_name varchar(64) NOT NULL,
  region_name varchar(64) NOT NULL,
  city varchar(64) NOT NULL,
  latitude float NOT NULL,
  longitude float NOT NULL,
  time_entry varchar(14) NOT NULL,
  time_last_click varchar(14) NOT NULL,
  last_page_url text NOT NULL,
  http_referer varchar(255) NOT NULL,
  user_agent varchar(255) NOT NULL,
  KEY ip_address (ip_address),
  KEY country_code (country_code)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS zones;
CREATE TABLE zones (
  zone_id int(11) NOT NULL AUTO_INCREMENT,
  zone_country_id int(11) NOT NULL,
  zone_code varchar(32) NOT NULL,
  zone_name varchar(255) NOT NULL,
  PRIMARY KEY (zone_id),
  KEY idx_zones_country_id (zone_country_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS zones_to_geo_zones;
CREATE TABLE zones_to_geo_zones (
  association_id int(11) NOT NULL AUTO_INCREMENT,
  zone_country_id int(11) NOT NULL,
  zone_id int(11) DEFAULT NULL,
  geo_zone_id int(11) DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (association_id),
  KEY idx_zones_to_geo_zones_country_id (zone_country_id)
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO address_format (address_format_id, address_format, address_summary) VALUES
(1, '$firstname $lastname$cr$streets$cr$city, $postcode$cr$statecomma$country', '$city / $country'),
(2, '$firstname $lastname$cr$streets$cr$city, $state    $postcode$cr$country', '$city, $state / $country'),
(3, '$firstname $lastname$cr$streets$cr$city$cr$postcode - $statecomma$country', '$state / $country'),
(4, '$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country', '$postcode / $country'),
(5, '$firstname $lastname$cr$streets$cr$postcode $city$cr$country', '$city / $country');

INSERT INTO banners (banners_id, banners_title, banners_url, banners_image, banners_group, banners_html_text, expires_impressions, expires_date, date_scheduled, date_added, date_status_change, `status`) VALUES
(1, 'osCommerce', 'http://www.oscommerce.com', 'boonstoppel_afb_1.jpg', 'index', '', 0, NULL, NULL, '2010-12-26 18:53:09', NULL, 1);

INSERT INTO banned_ip (id_banned, bannedip) VALUES
(1, '205.205.1.1');

INSERT INTO catediscount (catediscount_id, catediscount_name, catediscount_groups_id, catediscount_customers_id, catediscount_categories_id, catediscount_discount) VALUES
(6, 'test 1', 0, 0, 21, '-10.00');

INSERT INTO categories (categories_id, categories_image, parent_id, sort_order, date_added, last_modified, categories_status, categories_hide_from_groups, categories_to_stores) VALUES
(1, 'categories/category_hardware.jpg', 0, 1, '2010-12-26 18:53:09', '2012-05-08 19:51:08', 1, '@', '@,1'),
(2, 'categories/category_software.jpg', 0, 2, '2010-12-26 18:53:09', '2011-12-21 20:43:59', 1, '@', '@,1'),
(3, 'categories/category_dvd_movies.jpg', 0, 3, '2010-12-26 18:53:09', '2012-01-12 21:17:01', 1, '@', '@,1'),
(4, 'categories/subcategory_graphic_cards.jpg', 1, 0, '2010-12-26 18:53:09', '2012-05-08 19:51:08', 1, '@', '@,1'),
(5, 'categories/subcategory_printers.jpg', 1, 0, '2010-12-26 18:53:09', '2012-05-08 19:51:08', 1, '@', '@,1'),
(6, 'categories/subcategory_monitors.jpg', 1, 0, '2010-12-26 18:53:09', '2012-05-08 19:51:08', 1, '@', '@,1'),
(7, 'categories/subcategory_speakers.jpg', 1, 0, '2010-12-26 18:53:09', '2012-05-08 19:51:08', 1, '@', '@,1'),
(8, 'categories/subcategory_keyboards.jpg', 1, 0, '2010-12-26 18:53:09', '2012-05-08 19:51:08', 1, '@', '@,1'),
(9, 'categories/subcategory_mice.jpg', 1, 0, '2010-12-26 18:53:09', '2012-05-08 19:51:08', 1, '@', '@,1'),
(10, 'categories/subcategory_action.jpg', 3, 0, '2010-12-26 18:53:09', '2012-01-12 21:17:01', 1, '@', '@,1'),
(11, 'categories/subcategory_science_fiction.jpg', 3, 0, '2010-12-26 18:53:09', '2012-01-12 21:17:01', 1, '@', '@,1'),
(12, 'categories/subcategory_comedy.jpg', 3, 0, '2010-12-26 18:53:09', '2012-01-12 21:17:01', 1, '@', '@,1'),
(13, 'categories/subcategory_cartoons.jpg', 3, 0, '2010-12-26 18:53:09', '2012-01-12 21:17:01', 1, '@', '@,1'),
(14, 'categories/subcategory_thriller.jpg', 3, 0, '2010-12-26 18:53:09', '2012-01-12 21:17:01', 1, '@', '@,1'),
(15, 'categories/subcategory_drama.jpg', 3, 0, '2010-12-26 18:53:09', '2012-01-12 21:17:01', 1, '@', '@,1'),
(16, 'categories/subcategory_memory.jpg', 1, 0, '2010-12-26 18:53:09', '2012-05-08 19:51:08', 1, '@', '@,1'),
(17, 'categories/subcategory_cdrom_drives.jpg', 1, 0, '2010-12-26 18:53:09', '2012-05-08 19:51:08', 1, '@', '@,1'),
(18, 'categories/subcategory_simulation.jpg', 2, 0, '2010-12-26 18:53:09', '2011-12-21 20:43:59', 1, '@', '@,1'),
(19, 'categories/subcategory_action_games.jpg', 2, 0, '2010-12-26 18:53:09', '2011-12-21 20:43:59', 1, '@', '@,1'),
(20, 'categories/subcategory_strategy.jpg', 2, 0, '2010-12-26 18:53:09', '2011-12-21 20:43:59', 1, '@', '@,1'),
(21, 'categories/category_gadgets.png', 0, 4, '2010-12-26 18:53:09', '2012-02-22 20:04:33', 1, '@', '@,1');

INSERT INTO categories_description (categories_id, language_id, categories_name, categories_htc_title_tag, categories_htc_desc_tag, categories_htc_keywords_tag, categories_htc_description) VALUES
(21, 3, 'Gadgets', 'Gadgets', 'Gadgets', 'Gadgets', ''),
(20, 3, 'Strategy', NULL, NULL, NULL, NULL),
(19, 3, 'Action', NULL, NULL, NULL, NULL),
(18, 3, 'Simulation', NULL, NULL, NULL, NULL),
(17, 3, 'CDROM Drives', NULL, NULL, NULL, NULL),
(16, 3, 'Memory', NULL, NULL, NULL, NULL),
(15, 3, 'Drama', NULL, NULL, NULL, NULL),
(14, 3, 'Thriller', NULL, NULL, NULL, NULL),
(13, 3, 'Cartoons', NULL, NULL, NULL, NULL),
(12, 3, 'Comedy', NULL, NULL, NULL, NULL),
(11, 3, 'Science Fiction', NULL, NULL, NULL, NULL),
(10, 3, 'Action', NULL, NULL, NULL, NULL),
(9, 3, 'Mice', NULL, NULL, NULL, NULL),
(8, 3, 'Keyboards', NULL, NULL, NULL, NULL),
(7, 3, 'Speakers', NULL, NULL, NULL, NULL),
(6, 3, 'Monitors', NULL, NULL, NULL, NULL),
(5, 3, 'Printers', 'Printers', 'Printers', 'Printers', ''),
(1, 2, 'Hardware 2', 'hardware', 'hardware', 'Hardware', ''),
(2, 2, 'Software', 'Software', 'Software', 'Software', ''),
(3, 2, 'DVD Movies', 'DVD Movies', 'DVD Movies', 'DVD Movies', ''),
(4, 2, 'Graphics Cards', 'Graphics Cards', 'Graphics Cards', 'Graphics Cards', ''),
(5, 2, 'Printers', 'Printers', 'Printers', 'Printers', ''),
(6, 2, 'Monitors', NULL, NULL, NULL, NULL),
(7, 2, 'Speakers', NULL, NULL, NULL, NULL),
(8, 2, 'Keyboards', NULL, NULL, NULL, NULL),
(9, 2, 'Mice', NULL, NULL, NULL, NULL),
(10, 2, 'Action', NULL, NULL, NULL, NULL),
(11, 2, 'Science Fiction', NULL, NULL, NULL, NULL),
(12, 2, 'Comedy', NULL, NULL, NULL, NULL),
(13, 2, 'Cartoons', NULL, NULL, NULL, NULL),
(14, 2, 'Thriller', NULL, NULL, NULL, NULL),
(15, 2, 'Drama', NULL, NULL, NULL, NULL),
(16, 2, 'Memory', NULL, NULL, NULL, NULL),
(17, 2, 'CDROM Drives', NULL, NULL, NULL, NULL),
(18, 2, 'Simulation', NULL, NULL, NULL, NULL),
(19, 2, 'Action', NULL, NULL, NULL, NULL),
(20, 2, 'Strategy', NULL, NULL, NULL, NULL),
(21, 2, 'Gadgets', 'Gadgets', 'Gadgets', 'Gadgets', ''),
(4, 3, 'Graphics Cards', 'Graphics Cards', 'Graphics Cards', 'Graphics Cards', ''),
(1, 3, 'Hardware 2', 'hardware', 'hardware', 'Hardware', ''),
(2, 3, 'Software', 'Software', 'Software', 'Software', ''),
(3, 3, 'DVD Movies', 'DVD Movies', 'DVD Movies', 'DVD Movies', '');


INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Installed Modules', 'MODULE_ORDER_TOTAL_INSTALLED', 'ot_subtotal.php;ot_shipping.php;ot_fixed_payment_chg.php;ot_cod_fee.php;ot_discount.php;ot_loworderfee.php;ot_subtotal_ex.php;ot_tax.php;ot_total.php', 'Lijst van order_totaal module bestandnamen gescheiden door een puntkomma. Dit wordt automatisch aangepast (Voorbeeld: ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php)', 6, 0, '2012-08-25 21:59:51', '2010-12-26 18:53:09', NULL, NULL),
( 'Installed Modules', 'MODULE_SHIPPING_INSTALLED', 'table.php;zones.php', 'Lijst van verzend module bestandsnamen gescheiden door een puntkomma. Dit wordt automatisch aangepast. (Voorbeeld: ups.php;flat.php;item.php)', 6, 0, '2013-01-05 21:30:24', '2010-12-26 18:53:09', NULL, NULL),
( 'Installed Modules', 'MODULE_ACTION_RECORDER_INSTALLED', 'ar_admin_login.php;ar_contact_us.php;ar_reset_password.php;ar_tell_a_friend.php', 'Lijst van actie recorder module bestandsnamen gescheiden door een puntkomma. Dit wordt automatisch aangepast.', 6, 0, '2012-08-21 19:49:01', '2010-12-26 18:53:09', NULL, NULL),
( 'Standaard Munteenheid', 'DEFAULT_CURRENCY', 'EUR', 'Standaard Munteenheid', 6, 0, NULL, '2010-12-26 18:53:09', NULL, NULL),
( 'Standaard Taal', 'DEFAULT_LANGUAGE', 'nl', 'Standaard Taal', 6, 0, NULL, '2010-12-26 18:53:09', NULL, NULL),
( 'Standaard Verkoop Status Voor Nieuwe Verkopen', 'DEFAULT_ORDERS_STATUS_ID', '1', 'Als een nieuwe verkoop plaatsvind, is dit de standaard status', 6, 0, NULL, '2010-12-26 18:53:09', NULL, NULL),
( 'Minimum Minutes Per E-Mail', 'MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES', '15', 'Minimum number of minutes to allow 1 e-mail to be sent (eg, 15 for 1 e-mail every 15 minutes)', 6, 0, NULL, '2012-08-21 19:49:01', NULL, NULL),
( 'Minimum Minutes Per E-Mail', 'MODULE_ACTION_RECORDER_TELL_A_FRIEND_EMAIL_MINUTES', '15', 'Minimum number of minutes to allow 1 e-mail to be sent (eg, 15 for 1 e-mail every 15 minutes)', 6, 0, NULL, '2012-08-21 19:53:30', NULL, NULL),
( 'Allowed Attempts', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_ATTEMPTS', '3', 'Number of login attempts to allow within the specified period.', 6, 0, NULL, '2012-08-16 20:51:34', NULL, NULL),
( 'Allowed Minutes', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES', '5', 'Number of minutes to allow login attempts to occur.', 6, 0, NULL, '2012-08-16 20:51:34', NULL, NULL),
('Last Update Check Time', 'LAST_UPDATE_CHECK_TIME', '', 'Last time a check for new versions of osCommerce was run', '6', '0', NULL, now(), NULL, NULL),
( 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2011-07-12 20:41:43', NULL, NULL),
( 'Geinstalleerde Modules', 'MODULE_HEADER_TAGS_INSTALLED', 'ht_category_title.php;ht_product_title.php', 'Lijst met header tag module bestandsnamen gescheiden door een puntkomma. Dit wordt automatisch aangepast', 6, 0, '2012-08-23 20:54:39', '2010-12-26 18:53:09', NULL, NULL),
( 'Geinstalleerde Modules', 'MODULE_ADMIN_DASHBOARD_INSTALLED', '', 'Lijst met Beheer Hulpmiddelen Dashboard module bestandnamen gescheiden door een puntkomma. Dit wordt automatisch aangepast', 6, 0, '2012-08-22 20:27:40', '2010-12-26 18:53:09', NULL, NULL),
( 'GeinstalleerdeTemplate Blok Groepen', 'TEMPLATE_BLOCK_GROUPS', 'header_tags;boxes;header_footer_contents;java_css', 'Dit wordt automatisch aangepast', 6, 0, '2011-10-02 19:49:46', '2010-12-26 18:53:09', NULL, NULL),
( 'Installed Modules', 'MODULE_SOCIAL_BOOKMARKS_INSTALLED', 'sb_digg.php;sb_email.php;sb_facebook.php;sb_facebook_like.php;sb_google_buzz.php;sb_google_plus.php;sb_twitter.php;sb_twitter_button.php', 'This is automatically updated. No need to edit.', 6, 0, '2012-08-19 19:01:32', '2011-07-12 20:19:45', NULL, NULL),
( 'Installed Modules', 'MODULE_ADMIN_BACKEND_INSTALLED', 'be_theme_admin_switcher.php', 'This is automatically updated. No need to edit.', 6, 0, '2013-01-01 19:42:24', '2011-02-20 20:52:22', NULL, NULL),
( 'Installed Modules', 'MODULE_THEMES_INSTALLED', 'th_themes_switcher.php;th_themes_switcher_mobile.php', 'This is automatically updated. No need to edit.', 6, 0, '2011-02-27 19:16:10', '2011-02-27 19:16:06', NULL, NULL),
( 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2011-07-12 20:36:48', NULL, NULL),
( 'Table Method', 'MODULE_SHIPPING_TABLE_MODE', 'price', 'The shipping cost is based on the order total or the total weight of the items ordered.', 6, 0, NULL, '2011-07-31 17:26:05', NULL, 'tep_cfg_select_option(array(''weight'', ''price''), '),
( 'Handling Fee', 'MODULE_SHIPPING_TABLE_HANDLING', '0', 'Handling fee for this shipping method.', 6, 0, NULL, '2011-07-31 17:26:05', NULL, NULL),
( 'Zone 1 Shipping Table', 'MODULE_SHIPPING_ZONES_COST_1', '3:8.50,7:10.50,99:20.00', 'Shipping rates to Zone 1 destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone 1 destinations.', 6, 0, NULL, '2013-01-05 21:30:33', NULL, NULL),
( 'Zone 1 Countries', 'MODULE_SHIPPING_ZONES_COUNTRIES_1', 'US,CA', 'Comma separated list of two character ISO country codes that are part of Zone 1.', 6, 0, NULL, '2013-01-05 21:30:33', NULL, NULL),
( 'Sort Order', 'MODULE_SHIPPING_ZONES_SORT_ORDER', '0', 'Sort order of display.', 6, 0, NULL, '2013-01-05 21:30:33', NULL, NULL),
( 'Enable Table Method', 'MODULE_SHIPPING_TABLE_STATUS', 'True', 'Do you want to offer table rate shipping?', 6, 0, NULL, '2011-07-31 17:26:05', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Shipping Table', 'MODULE_SHIPPING_TABLE_COST', '250:7.025,10000:0.00', 'The shipping cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc', 6, 0, NULL, '2011-07-31 17:26:05', NULL, NULL),
( 'Sort Order', 'MODULE_SHIPPING_TABLE_SORT_ORDER', '0', 'Sort order of display.', 6, 0, NULL, '2011-07-31 17:26:05', NULL, NULL),
( 'Shipping Zone', 'MODULE_SHIPPING_TABLE_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 0, NULL, '2011-07-31 17:26:05', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
( 'Tax Class', 'MODULE_SHIPPING_TABLE_TAX_CLASS', '2', 'Use the following tax class on the shipping fee.', 6, 0, NULL, '2011-07-31 17:26:05', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes('),
( 'Allowed Attempts', 'MODULE_ACTION_RECORDER_RESET_PASSWORD_ATTEMPTS', '1', 'Number of password reset attempts to allow within the specified period.', 6, 0, NULL, '2012-07-26 22:03:16', NULL, NULL),
( 'Allowed Minutes', 'MODULE_ACTION_RECORDER_RESET_PASSWORD_MINUTES', '0', 'Number of minutes to allow password resets to occur.', 6, 0, NULL, '2012-07-26 22:03:16', NULL, NULL),
( 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_DIGG_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-08-19 18:42:31', NULL, NULL),
( 'Enable Twitter Button Module', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_STATUS', 'True', 'Do you want to allow products to be shared through Twitter Button?', 6, 0, NULL, '2012-08-19 18:44:01', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Shop Owner Twitter Account', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_ACCOUNT', '', 'The Twitter account to attribute the tweet to and is recommended to the user to follow.', 6, 0, NULL, '2012-08-19 18:44:01', NULL, NULL),
( 'Related Twitter Account', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_RELATED_ACCOUNT', '', 'A related Twitter account that is also recommended to the user to follow.', 6, 0, NULL, '2012-08-19 18:44:01', NULL, NULL),
( 'Related Twitter Account Description', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_RELATED_ACCOUNT_DESC', '', 'A description for the related Twitter account.', 6, 0, NULL, '2012-08-19 18:44:01', NULL, NULL),
( 'Count Position', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_COUNT_POSITION', 'Horizontal', 'The position of the counter.', 6, 0, NULL, '2012-08-19 18:44:01', NULL, 'tep_cfg_select_option(array(''Horizontal'', ''Vertical'', ''None''), '),
( 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_BUTTON_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-08-19 18:44:01', NULL, NULL),
( 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-08-19 18:47:52', NULL, NULL),
( 'Width', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_LIKE_WIDTH', '125', 'The width of the iframe in pixels.', 6, 0, NULL, '2012-08-19 19:00:05', NULL, NULL),
( 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_LIKE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-08-19 19:00:05', NULL, NULL),
( 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_EMAIL_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-08-19 19:01:05', NULL, NULL),
( 'Sort Order', 'MODULE_SOCIAL_BOOKMARKS_GOOGLE_BUZZ_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-08-19 19:01:32', NULL, NULL),
( 'Sort Order', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-08-19 21:31:49', NULL, NULL),
( 'Sort Order', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2012-08-19 22:15:02', NULL, NULL),
( 'Zone 1 Handling Fee', 'MODULE_SHIPPING_ZONES_HANDLING_1', '0', 'Handling Fee for this shipping zone', 6, 0, NULL, '2013-01-05 21:30:33', NULL, NULL),
( 'Tax Class', 'MODULE_SHIPPING_ZONES_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 0, NULL, '2013-01-05 21:30:33', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes('),
( 'Enable Zones Method', 'MODULE_SHIPPING_ZONES_STATUS', 'True', 'Do you want to offer zone rate shipping?', 6, 0, NULL, '2013-01-05 21:30:33', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display Shipping', 'MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'true', 'Do you want to display the order shipping cost?', 6, 1, NULL, '2012-08-25 21:53:57', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Toon Subtotaal', 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'true', 'Wilt u het subtotaal van de verkoop tonen?', 6, 1, NULL, '2010-12-26 18:53:09', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Toon Belasting', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'true', 'Wilt u de belasting tonen van de verkoop?', 6, 1, NULL, '2010-12-26 18:53:09', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Toon Totaal', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true', 'Wilt u de totale verkoop waarde tonen?', 6, 1, NULL, '2010-12-26 18:53:09', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Display Total', 'MODULE_ORDER_TOTAL_DISCOUNT_STATUS', 'true', 'Do you want to display the total order value?', 6, 1, NULL, '2011-07-12 19:40:07', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Enable Admin theme switcher', 'MODULE_THEMES_SWITCHER_STATUS', 'True', 'Do you want to be able to select a theme here?', 6, 1, NULL, '2011-02-27 19:16:10', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Enable Facebook Module', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_STATUS', 'True', 'Do you want to allow products to be shared through Facebook?', 6, 1, NULL, '2011-07-12 20:41:43', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Enable Google Plus Module', 'MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_STATUS', 'True', 'Do you want to allow products to be shared through Google Plus?', 6, 1, NULL, '2011-07-12 20:36:48', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display fee', 'MODULE_FIXED_PAYMENT_CHG_STATUS', 'true', 'Display fee related to the payment type', 6, 1, NULL, '2011-05-07 22:40:16', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Display COD', 'MODULE_ORDER_TOTAL_COD_STATUS', 'true', 'Do you want this module to display?', 6, 1, NULL, '2011-07-31 19:04:59', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Enable admin Toolbar in header', 'MODULE_ADMIN_BACKEND_THEME_ADMIN_SWITCHER_HEADERBAR_STATUS', 'True', 'Do you want to be able the toolbar in the header?', 6, 1, NULL, '2013-01-01 19:42:24', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Enable Admin theme switcher', 'MODULE_ADMIN_BACKEND_THEME_ADMIN_SWITCHER_STATUS', 'True', 'Do you want to be able to select a theme here?', 6, 1, NULL, '2013-01-01 19:42:24', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Enable Digg Module', 'MODULE_SOCIAL_BOOKMARKS_DIGG_STATUS', 'True', 'Do you want to allow products to be shared through Digg?', 6, 1, NULL, '2012-08-19 18:42:31', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Enable Twitter Module', 'MODULE_SOCIAL_BOOKMARKS_TWITTER_STATUS', 'True', 'Do you want to allow products to be shared through Twitter?', 6, 1, NULL, '2012-08-19 18:47:52', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Enable Facebook Like Module', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_LIKE_STATUS', 'True', 'Do you want to allow products to be shared through Facebook Like?', 6, 1, NULL, '2012-08-19 19:00:05', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Layout Style', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_LIKE_STYLE', 'Standard', 'Determines the size and amount of social context next to the button.', 6, 1, NULL, '2012-08-19 19:00:05', NULL, 'tep_cfg_select_option(array(''Standard'', ''Button Count''), '),
( 'Show Faces', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_LIKE_FACES', 'False', 'Show profile pictures below the button?', 6, 1, NULL, '2012-08-19 19:00:05', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Verb to Display', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_LIKE_VERB', 'Like', 'The verb to display in the button.', 6, 1, NULL, '2012-08-19 19:00:05', NULL, 'tep_cfg_select_option(array(''Like'', ''Recommend''), '),
( 'Color Scheme', 'MODULE_SOCIAL_BOOKMARKS_FACEBOOK_LIKE_SCHEME', 'Light', 'The color scheme of the button.', 6, 1, NULL, '2012-08-19 19:00:05', NULL, 'tep_cfg_select_option(array(''Light'', ''Dark''), '),
( 'Enable E-Mail Module', 'MODULE_SOCIAL_BOOKMARKS_EMAIL_STATUS', 'True', 'Do you want to allow products to be shared through e-mail?', 6, 1, NULL, '2012-08-19 19:01:05', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Enable Google Buzz Module', 'MODULE_SOCIAL_BOOKMARKS_GOOGLE_BUZZ_STATUS', 'True', 'Do you want to allow products to be shared through Google Buzz?', 6, 1, NULL, '2012-08-19 19:01:32', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Enable Category Title Module', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_STATUS', 'True', 'Do you want to allow category titles to be added to the page title?', 6, 1, NULL, '2012-08-19 21:31:49', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Enable Product Title Module', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_STATUS', 'True', 'Do you want to allow product titles to be added to the page title?', 6, 1, NULL, '2012-08-19 22:15:02', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display Low Order Fee', 'MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS', 'true', 'Do you want to display the low order fee?', 6, 1, NULL, '2012-08-25 21:42:21', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Display Sub-Total Excl.', 'MODULE_ORDER_TOTAL_SUBTOTAL_EX_STATUS', 'true', 'Do you want to display the order sub-total cost Excl. ?', 6, 1, NULL, '2012-08-25 21:59:51', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Enable Mobile Theme Switcher', 'MODULE_THEMES_SWITCHER_MOBILE_STATUS', 'True', 'Do you want to be able to select a theme here?', 6, 1, NULL, '2013-01-26 20:11:46', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Sort Order', 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', '2', 'Sort order of display.', 6, 2, NULL, '2012-08-25 21:53:57', NULL, NULL),
( 'Sorteer Volgorde', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '1', 'Sorteer volgorde van de weergave', 6, 2, NULL, '2010-12-26 18:53:09', NULL, NULL),
( 'Sorteer Volgorde', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '4', 'Sorteer volgorde van de weergave', 6, 2, NULL, '2010-12-26 18:53:09', NULL, NULL),
( 'Sorteer Volgorde', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '5', 'Sorteer volgorde van de weergave', 6, 2, NULL, '2010-12-26 18:53:09', NULL, NULL),
( 'Sort Order', 'MODULE_ORDER_TOTAL_DISCOUNT_SORT_ORDER', '4', 'Sort order of display.', 6, 2, NULL, '2011-07-12 19:40:07', NULL, NULL),
( 'Sort Order', 'MODULE_THEMES_SWITCHER_SORT_ORDER', '1', 'Sort order of display. Lowest is displayed first.', 6, 2, NULL, '2011-02-27 19:16:10', NULL, NULL),
( 'Sort Order', 'MODULE_FIXED_PAYMENT_CHG_SORT_ORDER', '3', 'Display sort order.', 6, 2, NULL, '2011-05-07 22:40:16', NULL, NULL),
( 'Fee for payment type', 'MODULE_FIXED_PAYMENT_CHG_TYPE', 'cod:2.70:0.035,paypal_ipn:0:0.03', 'Payment methods with minimal fee (any) and normal fee (0 to 1, 1 is 100%) all splitted by colons, enter like this: [cod:xx:0.yy,paypal_ipn:xx:0.yy] ', 6, 2, NULL, '2011-05-07 22:40:16', NULL, NULL),
( 'Sort Order', 'MODULE_ORDER_TOTAL_COD_SORT_ORDER', '4', 'Sort order of display.', 6, 2, NULL, '2011-07-31 19:04:59', NULL, NULL),
( 'Sort Order', 'MODULE_ADMIN_BACKEND_THEME_ADMIN_SWITCHER_SORT_ORDER', '1', 'Sort order of display. Lowest is displayed first.', 6, 2, NULL, '2013-01-01 19:42:24', NULL, NULL),
( 'Sort Order', 'MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER', '4', 'Sort order of display.', 6, 2, NULL, '2012-08-25 21:42:21', NULL, NULL),
( 'Sort Order', 'MODULE_ORDER_TOTAL_SUBTOTAL_EX_SORT_ORDER', '1', 'Sort order of display.', 6, 2, NULL, '2012-08-25 21:59:51', NULL, NULL),
( 'Sort Order', 'MODULE_THEMES_SWITCHER_MOBILE_SORT_ORDER', '1', 'Sort order of display. Lowest is displayed first.', 6, 2, NULL, '2013-01-26 20:11:46', NULL, NULL),
( 'Allow Free Shipping', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING', 'false', 'Do you want to allow free shipping?', 6, 3, NULL, '2012-08-25 21:53:57', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Theme <br /> Create/Download new themes on <a href="http://getbootstrap.com/customize/">Bootstrap Customizer</a><br />', 'MODULE_THEMES_SWITCHER_THEME', 'default', 'Select the theme that you want to use.', 6, 3, NULL, '2011-02-27 19:16:10', NULL, 'tep_cfg_pull_down_themes('),
( 'COD Fee for FLAT', 'MODULE_ORDER_TOTAL_COD_FEE_FLAT', 'NL:8,5,AT:3.00,DE:3.58,00:9.99', 'FLAT: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', 6, 3, NULL, '2011-07-31 19:04:59', NULL, NULL),
( 'Theme <br /> Create/Download new themes on <a href="http://jqueryui.com/themeroller/">Jquery UI Themeroller</a><br />', 'MODULE_ADMIN_BACKEND_THEME_ADMIN_SWITCHER_THEME', 'ui-lightness', 'Select the theme that you want to use.', 6, 3, NULL, '2013-01-01 19:42:24', NULL, 'tep_admin_cfg_pull_down_themes('),
( 'Allow Low Order Fee', 'MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE', 'false', 'Do you want to allow low order fees?', 6, 3, NULL, '2012-08-25 21:42:21', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Theme <br /> Create/Download new themes on <a href="http://jquerymobile.com/themeroller/">Jquery UI MOBILE Themeroller</a><br />', 'MODULE_THEMES_SWITCHER_MOBILE_THEME', 'base 1.4.0', 'Select the theme that you want to use.', 6, 3, NULL, '2013-01-26 20:11:46', NULL, 'tep_cfg_pull_down_mobile_themes('),
( 'COD Fee for ITEM', 'MODULE_ORDER_TOTAL_COD_FEE_ITEM', 'NL:8,5,AT:3.00,DE:3.58,00:9.99', 'ITEM: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', 6, 4, NULL, '2011-07-31 19:04:59', NULL, NULL),
( 'Free Shipping For Orders Over', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER', '50', 'Provide free shipping for orders over the set amount.', 6, 4, NULL, '2012-08-25 21:53:57', 'currencies->format', NULL),
( 'Version Bootstrap CSS file <b>STYLESHEET</b> (complet name with extension)', 'MODULE_THEMES_SWITCHER_VERSION_CSS', 'bootstrap.min.css', 'Boostrap CSS file ', 6, 4, NULL, '2011-02-27 19:16:10', NULL, NULL),
( 'Version jquery-ui <b>STYLESHEET</b> (complet name with extension)', 'MODULE_ADMIN_BACKEND_THEME_ADMIN_SWITCHER_VERSION_CSS', 'jquery-ui.css', 'Jquery ui version ', 6, 4, NULL, '2013-01-01 19:42:24', NULL, NULL),
( 'Version jquery-ui <b>JAVASCRIPT</b> (complet name with extension)', 'MODULE_ADMIN_BACKEND_THEME_ADMIN_SWITCHER_VERSION_JS', 'jquery-ui.18.24.min.js', 'Jquery ui version ', 6, 4, NULL, '2013-01-01 19:42:24', NULL, NULL),
( 'Order Fee For Orders Under', 'MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER', '50', 'Add the low order fee to orders under this amount.', 6, 4, NULL, '2012-08-25 21:42:21', 'currencies->format', NULL),
( 'Version jquery-ui <b>JAVASCRIPT</b> (complete name with extension)', 'MODULE_THEMES_SWITCHER_MOBILE_VERSION_JS', 'jquery.mobile-1.4.0.min.js', 'Jquery ui version ', 6, 4, NULL, '2013-01-26 20:11:46', NULL, NULL),
( 'Provide Free Shipping For Orders Made', 'MODULE_ORDER_TOTAL_SHIPPING_DESTINATION', 'national', 'Provide free shipping for orders sent to the set destination.', 6, 5, NULL, '2012-08-25 21:53:57', NULL, 'tep_cfg_select_option(array(''national'', ''international'', ''both''), '),
( 'COD Fee for TABLE', 'MODULE_ORDER_TOTAL_COD_FEE_TABLE', 'NL:8,5,AT:3.00,DE:3.58,00:9.99', 'TABLE: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', 6, 5, NULL, '2011-07-31 19:04:59', NULL, NULL),
( 'Version jquery-ui <b>STYLESHEET</b> (complete name with extension)', 'MODULE_THEMES_SWITCHER_MOBILE_VERSION_CSS', 'jquery.mobile.theme-1.4.0.min.css', 'Jquery ui version ', 6, 5, NULL, '2013-01-26 20:11:46', NULL, NULL),
( 'Order Fee', 'MODULE_ORDER_TOTAL_LOWORDERFEE_FEE', '5', 'Low order fee.', 6, 5, NULL, '2012-08-25 21:42:21', 'currencies->format', NULL),
( 'COD Fee for UPS', 'MODULE_ORDER_TOTAL_COD_FEE_UPS', 'NL:8,5,CA:4.50,US:3.00,00:9.99', 'UPS: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', 6, 6, NULL, '2011-07-31 19:04:59', NULL, NULL);
INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Tax', 'MODULE_FIXED_PAYMENT_CHG_TAX_CLASS', '1', 'Use the following tax class:', 6, 6, NULL, '2011-05-07 22:40:16', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes('),
( 'Attach Low Order Fee On Orders Made', 'MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION', 'both', 'Attach low order fee for orders sent to the set destination.', 6, 6, NULL, '2012-08-25 21:42:21', NULL, 'tep_cfg_select_option(array(''national'', ''international'', ''both''), '),
( 'COD Fee for USPS', 'MODULE_ORDER_TOTAL_COD_FEE_USPS', 'NL:8,5,CA:4.50,US:3.00,00:9.99', 'USPS: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', 6, 7, NULL, '2011-07-31 19:04:59', NULL, NULL),
( 'Tax Class', 'MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS', '0', 'Use the following tax class on the low order fee.', 6, 7, NULL, '2012-08-25 21:42:21', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes('),
( 'COD Fee for ZONES', 'MODULE_ORDER_TOTAL_COD_FEE_ZONES', 'CA:4.50,US:3.00,00:9.99', 'ZONES: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', 6, 8, NULL, '2011-07-31 19:04:59', NULL, NULL),
( 'COD Fee for Austrian Post', 'MODULE_ORDER_TOTAL_COD_FEE_AP', 'NL:8,5,AT:3.63,00:9.99', 'Austrian Post: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', 6, 9, NULL, '2011-07-31 19:04:59', NULL, NULL),
( 'COD Fee for DHL Worldwide', 'MODULE_ORDER_TOTAL_COD_FEE_DHL', 'NL:8,5,DE:3.58,00:9.99', 'DHL Worldwide: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', 6, 10, NULL, '2011-07-31 19:04:59', NULL, NULL),
( 'COD Fee for German Post', 'MODULE_ORDER_TOTAL_COD_FEE_DP', 'NL:8,5,DE:3.58,00:9.99', 'German Post: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', 6, 10, NULL, '2011-07-31 19:04:59', NULL, NULL),
( 'Which Mobile Theme ( choose a Letter )', 'MODULE_THEMES_SWITCHER_MOBILE_LETTER', 'a', 'Which Letter Theme do you want to use in your Mobile Shop ?. The chosen Letter must be present in the custom CSS file.', 6, 10, NULL, '2013-01-26 20:11:46', NULL, 'tep_cfg_select_option(array(''a'', ''b'', ''c'', ''d'', ''e'', ''f'', ''g'', ''h'', ''i'', ''j'', ''k'', ''l'', ''m'', ''n'', ''o'', ''p'', ''q'', ''r'', ''s'', ''t'', ''u'', ''v'', ''w'', ''x'', ''y'', ''z''), '),
( 'Tax Class', 'MODULE_ORDER_TOTAL_COD_TAX_CLASS', '0', 'Use the following tax class on the COD fee.', 6, 11, NULL, '2011-07-31 19:04:59', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes('),
( 'COD Fee for FedEx', 'MODULE_ORDER_TOTAL_COD_FEE_FEDEX', 'US:3.00', 'FedEx: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', 6, 12, NULL, '2011-07-31 19:04:59', NULL, NULL),
( 'COD Fee for Servicepakke', 'MODULE_ORDER_TOTAL_COD_FEE_SERVICEPAKKE', 'NO:69', 'Servicepakke: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)', 6, 12, NULL, '2011-07-31 19:04:59', NULL, NULL),
( 'Structure CSS file jquery-ui mobile<b>STYLESHEET</b> (complete name with extension)', 'MODULE_THEMES_SWITCHER_MOBILE_STRUCTURE_CSS', 'jquery.mobile.structure-1.4.0.min.css', 'Which Mobile Theme Structure file do you want to use in your Mobile Shop ?. ( leave this blanc if your CSS file in the selected CSS directory has everthing that jquery mobile needs. )', 6, 15, NULL, '2013-01-26 20:11:46', NULL, NULL),
( 'Choose a Mobile Transition Effect', 'MODULE_THEMES_SWITCHER_MOBILE_TRANSITION', 'pop', 'Which Mobile Transition Effect do you want to use in your Mobile Shop ?.', 6, 15, NULL, '2013-01-26 20:11:46', NULL, 'tep_cfg_select_option(array(''none'', ''fade'', ''pop'', ''flip'', ''turn'', ''flow'', ''slidefade'', ''slide'', ''slideup'', ''slidedown''), '),
( 'Max Featured Products', 'MAX_DISPLAY_FEATURED_PRODUCTS', '10', 'Set the maximum number of featured products to allow.', 6, 222, NULL, '2012-05-16 22:41:15', NULL, NULL);

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added) VALUES ('Store Logo', 'STORE_LOGO', 'store_logo.png', 'This is the filename of your Store Logo. This should be updated at Admin > Configuration > Store Logo', '6', '0', NULL, now());


INSERT INTO `configuration` ( `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Content Placement', 'MODULE_BOXES_BREADCRUMB_CONTENT_PLACEMENT', 'Bread Column', 'Should the module be loaded in the header or the footer position?', 6, 2, NULL, '2014-09-24 20:01:00', NULL, 'tep_cfg_select_option(array(''Left Column'', ''Right Column'', ''Bread Column'', ''Head Column'', ''Foot Column'', ''Left Header'', ''Center Header'', ''Right Header'', ''Header Line'', ''Left Footer'', ''Center Footer'', ''Right Footer'', ''Footer Line''),'),
( 'Display in pages.', 'MODULE_BOXES_BREADCRUMB_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 5, NULL, '2014-09-24 20:01:00', NULL, 'tep_cfg_select_pages('),
( 'Sort Order', 'MODULE_BOXES_BREADCRUMB_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 3, NULL, '2014-09-24 20:01:00', NULL, NULL),
( 'Enable Languages Module', 'MODULE_BOXES_BREADCRUMB_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2014-09-24 20:01:00', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Module Version Number', 'MODULE_BOXES_BREADCRUMB_VERSION_NUMBER', 'v1.0', 'Version number of installed module', 6, 4, NULL, '2014-09-24 20:01:00', NULL, 'tep_sanitize_string('),
( 'Content Placement', 'MODULE_BOXES_CATEGORIES_ACCORDION_CONTENT_PLACEMENT', 'Left Column', 'Should the module be be placed in: ?', 6, 3, NULL, '2012-12-08 21:46:27', NULL, 'tep_cfg_select_option(array(''Left Column'', ''Right Column'', ''Bread Column'', ''Head Column'', ''Foot Column'', ''Left Header'', ''Center Header'', ''Right Header'', ''Header Line'', ''Left Footer'', ''Center Footer'', ''Right Footer'', ''Footer Line''),'),
( 'Display in pages.', 'MODULE_BOXES_CATEGORIES_ACCORDION_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 30, NULL, '2012-12-08 21:46:27', NULL, 'tep_cfg_select_pages('),
( 'Unselected Icon', 'MODULE_BOXES_CATEGORIES_ACCORDION_ICON', 'plus', 'Select the icon to use for the unselected tabs.', 6, 25, NULL, '2012-12-08 21:46:27', NULL, 'tep_cfg_pull_down_icon('),
( 'Selected Icon', 'MODULE_BOXES_CATEGORIES_ACCORDION_ICON_SELECTED', 'minus', 'Select the icon to use for the selected tab.', 6, 20, NULL, '2012-12-08 21:46:27', NULL, 'tep_cfg_pull_down_icon('),
( 'Sort Order', 'MODULE_BOXES_CATEGORIES_ACCORDION_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 15, NULL, '2012-12-08 21:46:27', NULL, NULL),
( 'Enable Categories Module', 'MODULE_BOXES_CATEGORIES_ACCORDION_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2012-12-08 21:46:27', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Placement', 'MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT', 'Left Column', 'Should the module be be placed in: ?', 6, 3, NULL, '2014-09-24 20:01:29', NULL, 'tep_cfg_select_option(array(''Left Column'', ''Right Column'', ''Bread Column'', ''Head Column'', ''Foot Column'', ''Left Header'', ''Center Header'', ''Right Header'', ''Header Line'', ''Left Footer'', ''Center Footer'', ''Right Footer'', ''Footer Line''),'),
( 'Display in pages.', 'MODULE_BOXES_CATEGORIES_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 5, NULL, '2014-09-24 20:01:29', NULL, 'tep_cfg_select_pages('),
( 'Sort Order', 'MODULE_BOXES_CATEGORIES_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 4, NULL, '2014-09-24 20:01:29', NULL, NULL),
( 'Enable Categories Module', 'MODULE_BOXES_CATEGORIES_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2014-09-24 20:01:29', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display in pages.', 'MODULE_BOXES_CATEGORIES_TREEVIEW_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 0, NULL, '2011-07-23 20:13:26', NULL, 'tep_cfg_select_pages('),
( 'Dutch Contents', 'MODULE_BOXES_GENERIC2_CONTENT_DUTCH', 'Generic Contents', 'Enter the contents that you want in your box in dutch', 6, 20, NULL, '2012-08-18 22:40:06', NULL, 'tep_draw_textarea_ckeditor(''configuration[MODULE_BOXES_GENERIC2_CONTENT_DUTCH]'', false, 115, 100, tep_get_config_value( MODULE_BOXES_GENERIC2_CONTENT_DUTCH ),  tep_get_text_class() , '),
( 'Dutch Title', 'MODULE_BOXES_GENERIC2_TITLE_DUTCH', 'Generic Title', 'Enter the title that you want on your box in dutch', 6, 10, NULL, '2012-08-18 22:40:06', NULL, NULL),
( 'Display in pages.', 'MODULE_BOXES_GENERIC_CONTENT_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 0, NULL, '2014-09-24 19:42:42', NULL, 'tep_cfg_select_pages('),
( 'Content Placement', 'MODULE_BOXES_INFORMATION_MANAGER_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', 6, 1, NULL, '2014-09-24 20:01:43', NULL, 'tep_cfg_select_option(array(''Left Column'', ''Right Column'', ''Bread Column'', ''Head Column'', ''Foot Column'', ''Left Header'', ''Center Header'', ''Right Header'', ''Header Line'', ''Left Footer'', ''Center Footer'', ''Right Footer'', ''Footer Line''),'),
( 'Use FAQ in box.', 'MODULE_BOXES_INFORMATION_MANAGER_DISPLAY_FAQ', 'False', 'Use the FAQ pages in the information Box. ', 6, 0, NULL, '2014-09-24 20:01:43', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Use  Location Map in box.', 'MODULE_BOXES_INFORMATION_MANAGER_DISPLAY_MAP', 'False', 'Use the Location Map in the information Box. ', 6, 0, NULL, '2014-09-24 20:01:43', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display in pages.', 'MODULE_BOXES_INFORMATION_MANAGER_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 0, NULL, '2014-09-24 20:01:43', NULL, 'tep_cfg_select_pages('),
( 'Sort Order', 'MODULE_BOXES_INFORMATION_MANAGER_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2014-09-24 20:01:43', NULL, NULL),
( 'Enable Information Module', 'MODULE_BOXES_INFORMATION_MANAGER_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2014-09-24 20:01:43', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Placement', 'MODULE_BOXES_NAVBAR_CONTENT_PLACEMENT', 'Header Line', 'Where should the module be placed : ', 6, 1, NULL, '2014-09-24 20:02:14', NULL, 'tep_cfg_select_option(array(''Header Line'', ''Bread Column'', ''Footer Line''),'),
( 'Display in pages.', 'MODULE_BOXES_NAVBAR_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 0, NULL, '2014-09-24 20:02:14', NULL, 'tep_cfg_select_pages('),
( 'Inverted Navigation Bar', 'MODULE_BOXES_NAVBAR_INVERTED', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2014-09-24 20:02:14', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Sort Order', 'MODULE_BOXES_NAVBAR_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2014-09-24 20:02:14', NULL, NULL),
( 'Enable Navigation Bar Module', 'MODULE_BOXES_NAVBAR_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2014-09-24 20:02:14', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Placement', 'MODULE_BOXES_STORE_LOGO_CONTENT_PLACEMENT', 'Header Line', 'Where should the module be placed : ', 6, 1, NULL, '2014-09-24 20:01:14', NULL, 'tep_cfg_select_option(array(''Left Column'', ''Right Column'', ''Bread Column'', ''Head Column'', ''Foot Column'', ''Left Header'', ''Center Header'', ''Right Header'', ''Header Line'', ''Left Footer'', ''Center Footer'', ''Right Footer'', ''Footer Line''),'),
( 'Display in pages.', 'MODULE_BOXES_STORE_LOGO_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 0, NULL, '2014-09-24 20:01:14', NULL, 'tep_cfg_select_pages('),
( 'Sort Order', 'MODULE_BOXES_STORE_LOGO_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2014-09-24 20:01:14', NULL, NULL),
( 'Enable Currencies Module', 'MODULE_BOXES_STORE_LOGO_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2014-09-24 20:01:14', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');

INSERT INTO `configuration` ( `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Geinstalleerde Modules', 'MODULE_BOXES_INSTALLED', 'bm_breadcrumb.php;bm_categories.php;bm_information_manager.php;bm_navbar.php;bm_store_logo.php', 'Lijst van box module bestandsnamen gescheiden door een puntkomma. Dit wordt automatisch aangepast', 6, 0, '2014-09-24 20:02:14', '2010-12-26 18:53:09', NULL, NULL);

INSERT INTO `configuration` ( `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Display in pages.', 'MODULE_JAVA_CSS_BOOTSTRAP_CSS_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 100, NULL, '2014-09-08 16:52:36', NULL, 'tep_cfg_select_pages('),
( 'Location of Bootstrap CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_CSS_JS_LOCTION_SERVER', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/', 'The location of the CSS file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', 6, 40, NULL, '2014-09-08 16:52:36', NULL, NULL),
( 'Name of Bootstrap CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_CSS_JS_NAME', 'bootstrap.min.css', 'The name of the Bootstrap CSS file located on your local or CDNJS server.', 6, 30, NULL, '2014-09-08 16:52:36', NULL, NULL),
( 'Location Bootstrap CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_CSS_LOCATION', 'Local', 'Get the Bootstrap CSS File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', 6, 20, NULL, '2014-09-08 16:52:36', NULL, 'tep_cfg_select_option(array(''Local'', ''Cdnjs''), '),
( 'Theme <br /> Create/Download new themes on <a href=\"http://http://getbootstrap.com/customize/\">Bootstrap Themes</a><br />', 'MODULE_JAVA_CSS_BOOTSTRAP_CSS_SWITCHER_THEME', 'default', 'Select the theme that you want to use.', 6, 100, NULL, NULL, null, 'tep_cfg_pull_down_themes(' ),
( 'Sort Order', 'MODULE_JAVA_CSS_BOOTSTRAP_CSS_SORT_ORDER', '40', 'Sort order of display. Lowest is displayed first.', 6, 10, NULL, '2014-09-08 16:52:36', NULL, NULL),
( 'Enable Bootstrap CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_CSS_STATUS', 'True', 'Activate this Bootstrap CSS Module if you want to speed up the load time of the main page an product listing of your shop?', 6, 1, NULL, '2014-09-08 16:52:36', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display in pages.', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_CSS_DISPLAY_PAGES', 'account_edit.php;advanced_search.php;create_account.php;', 'select pages where this box should be displayed. ', 6, 100, NULL, '2014-09-28 19:46:38', NULL, 'tep_cfg_select_pages('),
( 'Location of Bootstrap CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_CSS_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/', 'The location of the CSS file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', 6, 40, NULL, '2014-09-28 19:46:38', NULL, NULL),
( 'Name of Bootstrap CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_CSS_JS_NAME', 'bootstrap-datepicker3.min.css', 'The name of the Bootstrap CSS file located on your local or CDNJS server.', 6, 30, NULL, '2014-09-28 19:46:38', NULL, NULL),
( 'Location Bootstrap CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_CSS_LOCATION', 'Local', 'Get the DatePicker CSS File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', 6, 20, NULL, '2014-09-28 19:46:38', NULL, 'tep_cfg_select_option(array(''Local'', ''Cdnjs''), '),
( 'Sort Order', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_CSS_SORT_ORDER', '60', 'Sort order of display. Lowest is displayed first.', 6, 10, NULL, '2014-09-28 19:46:38', NULL, NULL),
( 'Enable Bootstrap CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_CSS_STATUS', 'True', 'Activate this Bootstrap CSS Module if you want to speed up the load time of the main page an product listing of your shop?', 6, 1, NULL, '2014-09-28 19:46:38', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display in pages.', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_DISPLAY_PAGES', 'account_edit.php;advanced_search.php;create_account.php;', 'select pages where this box should be displayed. ', 6, 100, NULL, '2014-09-28 19:48:32', NULL, 'tep_cfg_select_pages('),
( 'Location of Java ColorBox File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/', 'The location of the Javascript file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', 6, 40, NULL, '2014-09-28 19:48:32', NULL, NULL),
( 'Name of Java ColorBox File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_JS_NAME', 'bootstrap-datepicker.min.js', 'The name of the Javascript file located on your local or CDNJS server.', 6, 30, NULL, '2014-09-28 19:48:32', NULL, NULL),
( 'Location Java ColorBox File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_LOCATION', 'Local', 'Get the Jquery ColorBox File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', 6, 20, NULL, '2014-09-28 19:48:32', NULL, 'tep_cfg_select_option(array(''Local'', ''Cdnjs''), '),
( 'Sort Order', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', 6, 10, NULL, '2014-09-28 19:48:32', NULL, NULL),
( 'Enable Java ColorBox File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_STATUS', 'True', 'Activate this JavaScript ColorBox Module if you want to speed up the load time of the main page an product listing of your shop?', 6, 1, NULL, '2014-09-28 19:48:32', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display in pages.', 'MODULE_JAVA_CSS_BOOTSTRAP_FILE_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 100, NULL, '2014-09-08 18:16:16', NULL, 'tep_cfg_select_pages('),
( 'Location of Java ColorBox File', 'MODULE_JAVA_CSS_BOOTSTRAP_FILE_JS_LOCTION_SERVER', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/', 'The location of the Javascript file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', 6, 40, NULL, '2014-09-08 18:16:16', NULL, NULL),
( 'Name of Java ColorBox File', 'MODULE_JAVA_CSS_BOOTSTRAP_FILE_JS_NAME', 'bootstrap.min.js', 'The name of the Javascript file located on your local or CDNJS server.', 6, 30, NULL, '2014-09-08 18:16:16', NULL, NULL),
( 'Location Java ColorBox File', 'MODULE_JAVA_CSS_BOOTSTRAP_FILE_LOCATION', 'Local', 'Get the Jquery ColorBox File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', 6, 20, NULL, '2014-09-08 18:16:16', NULL, 'tep_cfg_select_option(array(''Local'', ''Cdnjs''), '),
( 'Sort Order', 'MODULE_JAVA_CSS_BOOTSTRAP_FILE_SORT_ORDER', '160', 'Sort order of display. Lowest is displayed first.', 6, 10, NULL, '2014-09-08 18:16:16', NULL, NULL),
( 'Enable Java ColorBox File', 'MODULE_JAVA_CSS_BOOTSTRAP_FILE_STATUS', 'True', 'Activate this JavaScript ColorBox Module if you want to speed up the load time of the main page an product listing of your shop?', 6, 1, NULL, '2014-09-08 18:16:16', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display in pages.', 'MODULE_JAVA_CSS_COLORBOX_CSS_DISPLAY_PAGES', 'product_info.php;', 'select pages where this box should be displayed. ', 6, 100, NULL, '2014-06-11 20:53:33', NULL, 'tep_cfg_select_pages('),
( 'Location of CSS ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_CSS_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/jquery.colorbox/1.6.4/', 'The location of the Javascript file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', 6, 40, NULL, '2014-06-11 20:53:33', NULL, NULL),
( 'Name of CSS ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_CSS_JS_NAME', 'colorbox.css', 'The name of the Javascript file located on your local or CDNJS server.', 6, 30, NULL, '2014-06-11 20:53:33', NULL, NULL),
( 'Location CSS ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_CSS_LOCATION', 'Local', 'Get the Jquery ColorBox File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', 6, 20, NULL, '2014-06-11 20:53:33', NULL, 'tep_cfg_select_option(array(''Local'', ''Cdnjs''), '),
( 'Sort Order', 'MODULE_JAVA_CSS_COLORBOX_CSS_SORT_ORDER', '20', 'Sort order of display. Lowest is displayed first.', 6, 10, NULL, '2014-06-11 20:53:33', NULL, NULL),
( 'Enable CSS ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_CSS_STATUS', 'True', 'Activate this JavaScript ColorBox Module if you want to speed up the load time of the main page an product listing of your shop?', 6, 1, NULL, '2014-06-11 20:53:33', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display in pages.', 'MODULE_JAVA_CSS_COLORBOX_FILE_DISPLAY_PAGES', 'product_info.php;', 'select pages where this box should be displayed. ', 6, 100, NULL, '2014-11-17 20:21:04', NULL, 'tep_cfg_select_pages('),
( 'Location of Java ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_FILE_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/jquery.colorbox/1.6.4/', 'The location of the Javascript file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', 6, 40, NULL, '2014-11-17 20:21:04', NULL, NULL),
( 'Name of Java ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_FILE_JS_NAME', 'jquery.colorbox-min.js', 'The name of the Javascript file located on your local or CDNJS server.', 6, 30, NULL, '2014-11-17 20:21:04', NULL, NULL),
( 'Location Java ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_FILE_LOCATION', 'Local', 'Get the Jquery ColorBox File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', 6, 20, NULL, '2014-11-17 20:21:04', NULL, 'tep_cfg_select_option(array(''Local'', ''Cdnjs''), '),
( 'Sort Order', 'MODULE_JAVA_CSS_COLORBOX_FILE_SORT_ORDER', '160', 'Sort order of display. Lowest is displayed first.', 6, 10, NULL, '2014-11-17 20:21:04', NULL, NULL),
( 'Enable Java ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_FILE_STATUS', 'True', 'Activate this JavaScript ColorBox Module if you want to speed up the load time of the main page an product listing of your shop?', 6, 1, NULL, '2014-11-17 20:21:04', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Default Desktop View', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_DEFAULT', 'three', 'Default products per row in grid view', 6, 2, NULL, '2014-08-22 19:15:41', NULL, 'tep_cfg_select_option(array(''two'',''three'', ''four''), '),
( 'Display in pages.', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_DISPLAY_PAGES', 'advanced_search_result.php;index.php;products_new.php;specials.php;', 'select pages where this box should be displayed. ', 6, 100, NULL, '2014-08-22 19:15:41', NULL, 'tep_cfg_select_pages('),
( 'Location of Jquery Cookie JS File', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/', 'The location of the Jquery Cookie JS  file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', 6, 40, NULL, '2014-08-22 19:15:41', NULL, NULL),
( 'Name of Jquery Cookie JS File', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_JS_NAME', 'jquery.cookie.js', 'The name of the Jquery Cookie JS  file located on your local or CDNJS server.', 6, 30, NULL, '2014-08-22 19:15:41', NULL, NULL),
( 'Location Jquery Cookie JS File', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_LOCATION', 'Local', 'Get the Jquery Cookie JS File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', 6, 20, NULL, '2014-08-22 19:15:41', NULL, 'tep_cfg_select_option(array(''Local'', ''Cdnjs''), '),
( 'Default Mobile View', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_MOBILE', 'two', 'Default products per row in mobile grid view', 6, 3, NULL, '2014-08-22 19:15:41', NULL, 'tep_cfg_select_option(array(''one'',''two''), '),
( 'Sort Order', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_SORT_ORDER', '160', 'Sort order of display. Lowest is displayed first.', 6, 10, NULL, '2014-08-22 19:15:41', NULL, NULL),
( 'Enable Java ColorBox File', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_STATUS', 'True', 'Activate this JavaScript ColorBox Module if you want to speed up the load time of the main page an product listing of your shop?', 6, 1, NULL, '2014-08-22 19:15:41', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display in pages.', 'MODULE_JAVA_CSS_JQUERY_FILE_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 100, NULL, '2014-03-13 20:14:09', NULL, 'tep_cfg_select_pages('),
( 'Location of Javascript File', 'MODULE_JAVA_CSS_JQUERY_FILE_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/', 'The location of the Javascript file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', 6, 40, NULL, '2014-03-13 20:14:09', NULL, NULL),
( 'Name of Javascript File', 'MODULE_JAVA_CSS_JQUERY_FILE_JS_NAME', 'jquery.min.js', 'The name of the Javascript file located on your local or CDNJS server.', 6, 30, NULL, '2014-03-13 20:14:09', NULL, NULL),
( 'Location Javascript Files', 'MODULE_JAVA_CSS_JQUERY_FILE_LOCATION', 'Local', 'Get the Jquery JS File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', 6, 20, NULL, '2014-03-13 20:14:09', NULL, 'tep_cfg_select_option(array(''Local'', ''Cdnjs''), '),
( 'Sort Order', 'MODULE_JAVA_CSS_JQUERY_FILE_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', 6, 10, NULL, '2014-03-13 20:14:09', NULL, NULL),
( 'Enable Javascript Files', 'MODULE_JAVA_CSS_JQUERY_FILE_STATUS', 'True', 'Activate this Header Tag Module if you want to speed up the load time of the main page an product listing of your shop?', 6, 1, NULL, '2014-03-13 20:14:09', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Display in pages.', 'MODULE_JAVA_CSS_PHOTOGRID_FILE_DISPLAY_PAGES', 'product_info.php;', 'select pages where this box should be displayed. ', 6, 100, NULL, '2014-06-11 20:53:45', NULL, 'tep_cfg_select_pages('),
( 'Location of Java PhotoGrid File', 'MODULE_JAVA_CSS_PHOTOGRID_FILE_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/photoset-grid/1.0.1/', 'The location of the Javascript file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', 6, 40, NULL, '2014-06-11 20:53:45', NULL, NULL),
( 'Name of Java PhotoGrid File', 'MODULE_JAVA_CSS_PHOTOGRID_FILE_JS_NAME', 'jquery.photoset-grid.min.js', 'The name of the Javascript file located on your local or CDNJS server.', 6, 30, NULL, '2014-06-11 20:53:45', NULL, NULL),
( 'Location Java PhotoGrid File', 'MODULE_JAVA_CSS_PHOTOGRID_FILE_LOCATION', 'Local', 'Get the Jquery PhotoGrid File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', 6, 20, NULL, '2014-06-11 20:53:45', NULL, 'tep_cfg_select_option(array(''Local'', ''Cdnjs''), '),
( 'Sort Order', 'MODULE_JAVA_CSS_PHOTOGRID_FILE_SORT_ORDER', '150', 'Sort order of display. Lowest is displayed first.', 6, 10, NULL, '2014-06-11 20:53:45', NULL, NULL),
( 'Enable Java PhotoGrid File', 'MODULE_JAVA_CSS_PHOTOGRID_FILE_STATUS', 'True', 'Activate this JavaScript PhotoGrid Module if you want to speed up the load time of the main page an product listing of your shop?', 6, 1, NULL, '2014-06-11 20:53:45', NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');

INSERT INTO `configuration` ( `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Enable Bootstrap Select CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_CSS_STATUS', 'True', 'Activate this Bootstrap Select CSS Module if you want to speed up the load time of the main page an product listing of your shop?', 6, 1, NULL, '2016-04-07 21:29:11', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Sort Order', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_CSS_SORT_ORDER', '65', 'Sort order of display. Lowest is displayed first.', 6, 10, NULL, '2016-04-07 21:29:11', NULL, NULL),
( 'Location Bootstrap Select CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_CSS_LOCATION', 'Local', 'Get the Bootstrap Select CSS File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', 6, 20, NULL, '2016-04-07 21:29:11', NULL, 'tep_cfg_select_option(array(''Local'', ''Cdnjs''), '),
( 'Name of Bootstrap Select CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_CSS_JS_NAME', 'bootstrap-select.min.css', 'The name of the Bootstrap Select CSS file located on your local or CDNJS server.', 6, 30, NULL, '2016-04-07 21:29:11', NULL, NULL),
( 'Location of Bootstrap Select CSS File', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_CSS_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/', 'The location of the CSS file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', 6, 40, NULL, '2016-04-07 21:29:11', NULL, NULL),
( 'Display in pages.', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_CSS_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 100, NULL, '2016-04-07 21:29:11', NULL, 'tep_cfg_select_pages('),
( 'Enable Java Bootstrap Select File', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_FILE_STATUS', 'True', 'Activate this JavaScript Bootstrap Select Module if you want to speed up the load time of the main page an product listing of your shop?', 6, 1, NULL, '2016-04-07 22:08:24', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Sort Order', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_FILE_SORT_ORDER', '210', 'Sort order of display. Lowest is displayed first.', 6, 10, NULL, '2016-04-07 22:08:24', NULL, NULL),
( 'Location Java Bootstrap Select File', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_FILE_LOCATION', 'Local', 'Get the Jquery Bootstrap Select File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', 6, 20, NULL, '2016-04-07 22:08:24', NULL, 'tep_cfg_select_option(array(''Local'', ''Cdnjs''), '),
( 'Name of Java Bootstrap Select File', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_FILE_JS_NAME', 'bootstrap-select.min.js', 'The name of the Bootstrap Select Javascript file located on your local or CDNJS server.', 6, 30, NULL, '2016-04-07 22:08:24', NULL, NULL),
( 'Location of Java Bootstrap Select File', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_FILE_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/', 'The location of the Bootstrap Select Javascript file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', 6, 40, NULL, '2016-04-07 22:08:24', NULL, NULL),
( 'Display in pages.', 'MODULE_JAVA_CSS_BOOTSTRAP_SELECT_FILE_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', 6, 100, NULL, '2016-04-07 22:08:24', NULL, 'tep_cfg_select_pages(');


INSERT INTO `configuration` ( `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Installed Modules', 'MODULE_JAVA_CSS_INSTALLED', 'jcs_javascript_colorbox_css.php;jcs_javascript_bootstrap_css.php;jcs_javascript_bootstrap_datepicker_css.php;jcs_javascript_jquery_file.php;jcs_javascript_photogrid_file.php;jcs_javascript_bootstrap_file.php;jcs_javascript_bootstrap_datepicker_file.php;jcs_javascript_colorbox_file.php;jcs_javascript_grid_list_view.php;jcs_javascript_bootstrap_select_file.php;jcs_javascript_bootstrap_select_css.php', 'This is automatically updated. No need to edit.', 6, 0, '2014-11-17 20:21:04', '2014-03-07 20:27:55', NULL, NULL);

INSERT INTO `configuration` ( `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Installed Modules', 'MODULE_PAYMENT_INSTALLED', 'cod.php;cop.php;moneyorder.php', 'Lijst met betaalmodule filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: cod.php;paypal_express.php)', 6, 0, '2014-10-12 20:13:54', '2010-12-26 18:53:09', NULL, NULL) ;

INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Display in pages.', 'MODULE_PAYMENT_COD_ACTIVE_WITH_SHIPING', 'all', 'select pages where this box should be displayed. ', 6, 0, NULL, '2011-07-28 20:47:48', NULL, 'tep_cfg_select_payment('),
( 'Set Order Status', 'MODULE_PAYMENT_COD_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', 6, 0, NULL, '2014-10-12 20:13:54', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
( 'Sort order of display.', 'MODULE_PAYMENT_COD_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2014-10-12 20:13:54', NULL, NULL),
( 'Enable Cash On Delivery Module', 'MODULE_PAYMENT_COD_STATUS', 'True', 'Do you want to accept Cash On Delevery payments?', 6, 1, NULL, '2014-10-12 20:13:54', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Use Payment in combination with Shipping.', 'MODULE_PAYMENT_COD_USE_PAYMENTS', 'table;zones;', 'select shipping method to be used with this payment option. ', 6, 0, NULL, '2014-10-12 20:13:54', NULL, 'tep_cfg_select_payment('),
( 'Payment Zone', 'MODULE_PAYMENT_COD_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', 6, 2, NULL, '2014-10-12 20:13:54', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
( 'Set Order Status', 'MODULE_PAYMENT_COP_ORDER_STATUS_ID', '0', 'Zet de status van de orders gemaakt met deze betalingsmethode met deze gegeven', 6, 0, NULL, '2014-10-12 20:14:13', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
( 'Sorteer order op het scherm.', 'MODULE_PAYMENT_COP_SORT_ORDER', '0', 'Sorteer order op het scherm. Laagste wordt het eerst op het scherm.', 6, 0, NULL, '2014-10-12 20:14:13', NULL, NULL),
( 'Toestaan van Contant of Pinnen Module', 'MODULE_PAYMENT_COP_STATUS', 'True', 'Wilt U Contante Betaling in de Winkel activeren?', 6, 1, NULL, '2014-10-12 20:14:13', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Use Payment in combination with Shipping.', 'MODULE_PAYMENT_COP_USE_PAYMENTS', 'spu;', 'select shipping method to be used with this payment option. ', 6, 0, NULL, '2014-10-12 20:14:13', NULL, 'tep_cfg_select_payment('),
( 'Payment Zone', 'MODULE_PAYMENT_COP_ZONE', '0', 'Als een zone is geselecteerd, activeer deze betalingsmethode voor deze zone.', 6, 2, NULL, '2014-10-12 20:14:13', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes('),
( 'Set Order Status', 'MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', 6, 0, NULL, '2014-10-12 20:14:30', 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses('),
( 'Make Payable to:', 'MODULE_PAYMENT_MONEYORDER_PAYTO', '', 'Who should payments be made payable to?', 6, 1, NULL, '2014-10-12 20:14:30', NULL, NULL),
( 'Sort order of display.', 'MODULE_PAYMENT_MONEYORDER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2014-10-12 20:14:30', NULL, NULL),
( 'Enable Check/Money Order Module', 'MODULE_PAYMENT_MONEYORDER_STATUS', 'True', 'Do you want to accept Check/Money Order payments?', 6, 1, NULL, '2014-10-12 20:14:30', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Use Payment in combination with Shipping.', 'MODULE_PAYMENT_MONEYORDER_USE_PAYMENTS', 'table;zones;', 'select shipping method to be used with this payment option. ', 6, 0, NULL, '2014-10-12 20:14:30', NULL, 'tep_cfg_select_payment('),
( 'Payment Zone', 'MODULE_PAYMENT_MONEYORDER_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', 6, 2, NULL, '2014-10-12 20:14:30', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(');


INSERT INTO `configuration` ( `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Number of Reviews', 'MODULE_CONTENT_PI_PRICE_BRUTO_CONTENT_LIMIT', '4', 'How many reviews should be shown?', 6, 1, NULL, '2015-01-05 20:38:19', NULL, NULL),
( 'Align Text Product Availability', 'MODULE_CONTENT_PI_BUTTON_REVIEW_ALIGN_TEXT', 'Right', 'Align the Text for this module ?', 6, 1, NULL, '2015-01-10 20:11:52', NULL, 'tep_cfg_select_option(array(''Left'', ''Center'', ''Right''), '),
( 'Enable Product Name Model Module', 'MODULE_CONTENT_PI_NAME_MODEL_STATUS', 'True', 'Should the Product Name / Model block be shown on the product info page?', 6, 1, NULL, '2015-01-21 19:53:52', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_PI_NAME_MODEL_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', 6, 1, NULL, '2015-01-21 19:53:52', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Align Text Product Availability', 'MODULE_CONTENT_PI_NAME_MODEL_ALIGN_TEXT', 'Left', 'Align the Text for this module ?', 6, 1, NULL, '2015-01-21 19:53:52', NULL, 'tep_cfg_select_option(array(''Left'', ''Center'', ''Right''), '),
( 'Sort Order', 'MODULE_CONTENT_PI_NAME_MODEL_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-01-21 19:53:52', NULL, NULL),
( 'Enable Price Box Module', 'MODULE_CONTENT_PI_PRICE_BOX_STATUS', 'True', 'Should the Price Box be shown on the product info page?', 6, 1, NULL, '2015-01-21 19:54:13', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_PI_PRICE_BOX_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 1, NULL, '2015-01-21 19:54:13', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Align Text Bruto Price', 'MODULE_CONTENT_PI_PRICE_BOX_ALIGN_TEXT', 'Left', 'How many reviews should be shown?', 6, 1, NULL, '2015-01-21 19:54:13', NULL, 'tep_cfg_select_option(array(''Left'', ''Center'', ''Right''), '),
( 'Sort Order', 'MODULE_CONTENT_PI_PRICE_BOX_SORT_ORDER', '20', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-01-21 19:54:13', NULL, NULL),
( 'Enable Product Description Module', 'MODULE_CONTENT_PI_DESCRIPTION_STATUS', 'True', 'Should the Product Description Text be shown on the product info page?', 6, 1, NULL, '2015-01-21 19:54:35', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_PI_DESCRIPTION_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', 6, 1, NULL, '2015-01-21 19:54:35', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Align Text Product Availability', 'MODULE_CONTENT_PI_DESCRIPTION_ALIGN_TEXT', 'Left', 'Align the Text for this module ?', 6, 1, NULL, '2015-01-21 19:54:35', NULL, 'tep_cfg_select_option(array(''Left'', ''Center'', ''Right''), '),
( 'Sort Order', 'MODULE_CONTENT_PI_DESCRIPTION_SORT_ORDER', '30', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-01-21 19:54:35', NULL, NULL),
( 'Enable Product Images Module', 'MODULE_CONTENT_PI_IMAGES_STATUS', 'True', 'Should the Product Images block be shown on the product info page?', 6, 1, NULL, '2015-01-21 19:54:48', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_PI_IMAGES_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', 6, 1, NULL, '2015-01-21 19:54:48', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_PI_IMAGES_SORT_ORDER', '40', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-01-21 19:54:48', NULL, NULL),
( 'Enable Product Options Module', 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_STATUS', 'True', 'Should the Product Options block be shown on the product info page?', 6, 1, NULL, '2015-01-21 19:55:27', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 1, NULL, '2015-01-21 19:55:27', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-01-21 19:55:27', NULL, NULL),
( 'Enable Product Button Buy Module', 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_STATUS', 'True', 'Should the Product Button Buy be shown on the product info page?', 6, 1, NULL, '2015-01-21 19:56:06', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_CONTENT_WIDTH', '10', 'What width container should the content be shown in?', 6, 1, NULL, '2015-01-21 19:56:06', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_SORT_ORDER', '60', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-01-21 19:56:06', NULL, NULL),
( 'Enable Product Reviews Button Module', 'MODULE_CONTENT_PI_BUTTON_REVIEW_STATUS', 'True', 'Should the Product Reviews Button be shown on the product info page?', 6, 1, NULL, '2015-01-21 19:56:24', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_PI_BUTTON_REVIEW_CONTENT_WIDTH', '2', 'What width container should the content be shown in?', 6, 1, NULL, '2015-01-21 19:56:24', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_PI_BUTTON_REVIEW_SORT_ORDER', '70', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-01-21 19:56:24', NULL, NULL),
( 'Enable Product Date Available On Module', 'MODULE_CONTENT_PI_PRODUCT_DATE_AVAILABLE_ON_STATUS', 'True', 'Should the Product Date Available On Text be shown on the product info page?', 6, 1, NULL, '2015-01-21 19:57:28', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_PI_PRODUCT_DATE_AVAILABLE_ON_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 1, NULL, '2015-01-21 19:57:28', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Align Text Product Availability', 'MODULE_CONTENT_PI_PRODUCT_DATE_AVAILABLE_ON_ALIGN_TEXT', 'Left', 'Align the Text for this module ?', 6, 1, NULL, '2015-01-21 19:57:28', NULL, 'tep_cfg_select_option(array(''Left'', ''Center'', ''Right''), '),
( 'Sort Order', 'MODULE_CONTENT_PI_PRODUCT_DATE_AVAILABLE_ON_SORT_ORDER', '80', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-01-21 19:57:28', NULL, NULL),
( 'Enable Product Xsell Box Module', 'MODULE_CONTENT_PI_PRODUCT_XSELL_STATUS', 'True', 'Should the Product Name / Model block be shown on the product info page?', 6, 1, NULL, '2015-01-21 19:57:43', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_PI_PRODUCT_XSELL_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 1, NULL, '2015-01-21 19:57:43', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_PI_PRODUCT_XSELL_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-01-21 19:57:43', NULL, NULL),
( 'Enable Product Currently Viewing Module', 'MODULE_CONTENT_PI_PRODUCT_CURRENTLY_VIEWING_STATUS', 'True', 'Should the Product Currently Viewing block be shown on the product info page?', 6, 1, NULL, '2015-01-21 19:58:14', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_PI_PRODUCT_CURRENTLY_VIEWING_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 1, NULL, '2015-01-21 19:58:14', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Align Text Product Availability', 'MODULE_CONTENT_PI_PRODUCT_CURRENTLY_VIEWING_ALIGN_TEXT', 'Left', 'Align the Text for this module ?', 6, 1, NULL, '2015-01-21 19:58:14', NULL, 'tep_cfg_select_option(array(''Left'', ''Center'', ''Right''), '),
( 'Sort Order', 'MODULE_CONTENT_PI_PRODUCT_CURRENTLY_VIEWING_SORT_ORDER', '120', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-01-21 19:58:14', NULL, NULL);


INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Content Width', 'MODULE_CONTENT_SC_CHECKOUT_ALT_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 2, NULL, '2016-04-24 22:32:26', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_SC_CHECKOUT_ALT_SORT_ORDER', '600', 'Sort order of display. Lowest is displayed first.', 6, 3, NULL, '2016-04-24 22:32:26', NULL, NULL),
( 'Enable Shopping Cart Alternative Checkout Button', 'MODULE_CONTENT_SC_CHECKOUT_ALT_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2016-04-24 22:32:26', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Alt text position', 'MODULE_CONTENT_SC_CHECKOUT_ALT_TEXT_POSITION', 'top', 'The position of the alternative text ("or") relative to the button.', 6, 1, NULL, '2016-04-24 22:32:26', NULL, 'tep_cfg_select_option(array(''top'', ''bottom'', ''left'', ''right'', ''none''), '),
( 'Content Width', 'MODULE_CONTENT_SC_CHECKOUT_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 2, NULL, '2016-04-24 22:33:48', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_SC_CHECKOUT_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', 6, 3, NULL, '2016-04-24 22:33:48', NULL, NULL),
( 'Enable Shopping Cart Checkout Button', 'MODULE_CONTENT_SC_CHECKOUT_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2016-04-24 22:33:48', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_SC_NO_PRODUCTS_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 2, NULL, '2016-04-24 22:34:06', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_SC_NO_PRODUCTS_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', 6, 3, NULL, '2016-04-24 22:34:06', NULL, NULL),
( 'Enable Shopping Cart No Products Message', 'MODULE_CONTENT_SC_NO_PRODUCTS_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2016-04-24 22:34:06', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_SC_ORDER_SUBTOTAL_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 2, NULL, '2016-04-24 22:33:57', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_SC_ORDER_SUBTOTAL_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2016-04-24 22:33:57', NULL, NULL),
( 'Enable Shopping Cart Order SubTotal', 'MODULE_CONTENT_SC_ORDER_SUBTOTAL_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2016-04-24 22:33:57', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_SC_PRODUCT_LISTING_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 2, NULL, '2016-04-24 22:34:00', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_SC_PRODUCT_LISTING_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', 6, 3, NULL, '2016-04-24 22:34:00', NULL, NULL),
( 'Enable Shopping Cart Product Listing', 'MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2016-04-24 22:34:00', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_SC_REMOVE_ALL_PROD_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 2, NULL, '2016-04-24 22:34:14', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_SC_REMOVE_ALL_PROD_SORT_ORDER', '400', 'Sort order of display. Lowest is displayed first.', 6, 3, NULL, '2016-04-24 22:34:14', NULL, NULL),
( 'Enable Shopping Cart Checkout Button', 'MODULE_CONTENT_SC_REMOVE_ALL_PROD_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2016-04-24 22:34:14', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_SC_STOCK_NOTICE_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 2, NULL, '2016-04-24 22:34:10', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_SC_STOCK_NOTICE_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.', 6, 3, NULL, '2016-04-24 22:34:10', NULL, NULL),
( 'Enable Shopping Cart Stock Notice', 'MODULE_CONTENT_SC_STOCK_NOTICE_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NULL, '2016-04-24 22:34:10', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_SHOPPING_CART_XSELL_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 3, NULL, '2016-04-24 22:33:52', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Max Products', 'MODULE_CONTENT_SHOPPING_CART_XSELL_MAX_DISPLAY_PRODUCTS', '9', 'Maximum number of xsell products to show on the shopping cart page.', 6, 5, NULL, '2016-04-24 22:33:52', NULL, NULL),
( 'Product Order', 'MODULE_CONTENT_SHOPPING_CART_XSELL_ORDER_BY', 'Popular', 'What order should the xsell products be shown in?', 6, 6, NULL, '2016-04-24 22:33:52', NULL, 'tep_cfg_select_option(array(''Popular'', ''Random''), '),
( 'Product Width', 'MODULE_CONTENT_SHOPPING_CART_XSELL_PRODUCT_WIDTH', '4', 'What width container should the individual products be shown in?', 6, 4, NULL, '2016-04-24 22:33:52', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_SHOPPING_CART_XSELL_SORT_ORDER', '2000', 'Sort order of display. Lowest is displayed first.', 6, 2, NULL, '2016-04-24 22:33:52', NULL, NULL),
( 'Enable Xsell Module', 'MODULE_CONTENT_SHOPPING_CART_XSELL_STATUS', 'True', 'Should the xsell products block be shown on the shopping cart page?', 6, 1, NULL, '2016-04-24 22:33:52', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Module Version', 'MODULE_CONTENT_SHOPPING_CART_XSELL_VERSION', '1.0', 'The version of this module that you are running.', 6, 0, NULL, '2016-04-24 22:33:52', NULL, 'tep_cfg_disabled(');


INSERT INTO `configuration` ( `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Enable Index Categories Description Module', 'MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_STATUS', 'True', 'Do you want to enable the Index Categories Description  content module?', 6, 1, NULL, '2015-02-14 19:49:19', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-14 19:49:19', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-14 19:49:19', NULL, NULL),
( 'Enable Index Categories Description Module', 'MODULE_CONTENT_INDEX_CATEGORIES_TITLE_STATUS', 'False', 'Do you want to enable the Index Categories Description  content module?', 6, 1, NULL, '2015-02-14 19:55:43', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_CATEGORIES_TITLE_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-14 19:55:43', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_CATEGORIES_TITLE_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-14 19:55:43', NULL, NULL),
( 'Enable Index Categories Description Module', 'MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_STATUS', 'True', 'Do you want to enable the Index Categories Description  content module?', 6, 1, NULL, '2015-02-14 20:47:05', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-14 20:47:05', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-14 20:47:05', NULL, NULL),
( 'Enable Index Categories New Products Module', 'MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_STATUS', 'False', 'Do you want to enable the Index Categories New Products  content module?', 6, 1, NULL, '2015-02-15 19:20:51', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-15 19:20:51', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_SORT_ORDER', '150', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-15 19:20:51', NULL, NULL),
( 'Enable Index Categories Header Tags Social Bookmark Module', 'MODULE_CONTENT_INDEX_CATEGORIES_HT_SOCIAL_STATUS', 'True', 'Do you want to enable the Index Categories Header Tags Social Bookmark  content module?', 6, 1, NULL, '2015-02-15 20:17:03', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_CATEGORIES_HT_SOCIAL_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-15 20:17:03', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_CATEGORIES_HT_SOCIAL_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-15 20:17:03', NULL, NULL);


INSERT INTO `configuration` ( `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_CUSTOMER_GREETING_SORT_ORDER', '60', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-18 20:01:26', NULL, NULL),
( 'Enable Customer Greeting', 'MODULE_CONTENT_INDEX_FRONTPAGE_CUSTOMER_GREETING_STATUS', 'True', 'Do you want to show the heading title?', 6, 1, NULL, '2015-02-18 20:01:26', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_CUSTOMER_GREETING_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-18 20:01:26', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-18 20:11:03', NULL, NULL),
( 'Enable Featured Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_STATUS', 'True', 'Do you want to show the Featured box on the front page?', 6, 1, NULL, '2015-02-18 20:11:03', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Max Featured Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_MAX_DISPLAY', '6', 'How many featured products do you want to show?', 6, 3, NULL, '2015-02-18 20:11:03', NULL, NULL),
( 'Number of Columns', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_COLUMNS', '3', 'Number of columns of products to show', 6, 4, NULL, '2015-02-18 20:11:03', NULL, NULL),
( 'Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-18 20:11:41', NULL, NULL),
( 'Enable Featured Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_STATUS', 'True', 'Do you want to show the Featured box on the front page?', 6, 1, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Max Featured Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_MAX_DISPLAY', '6', 'How many featured products do you want to show?', 6, 3, NULL, '2015-02-18 20:11:41', NULL, NULL),
( 'Number of Columns', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_COLUMNS', '3', 'Number of columns of products to show', 6, 4, NULL, '2015-02-18 20:11:41', NULL, NULL),
( 'Use Cache for Featured Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_USE_CACHE', 'False', 'If th shop cache is activated. Activate the cache for the Featured Products', 6, 20, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Dutch Title', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_FRONT_TITLE_DUTCH', 'uitgelicht', 'Enter the title that you want on your box in dutch', 6, 14, NULL, '2015-02-18 20:11:41', NULL, NULL),
( 'English Title', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_FRONT_TITLE_ENGLISH', 'Title', 'Enter the title that you want on your box in english', 6, 14, NULL, '2015-02-18 20:11:41', NULL, NULL),
( 'Featured Product #1', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_1', '5', 'Select featured product #1 to show', 6, 99, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_pull_down_products('),
( 'Featured Product #2', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_2', '16', 'Select featured product #2 to show', 6, 99, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_pull_down_products('),
( 'Featured Product #3', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_3', '13', 'Select featured product #3 to show', 6, 99, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_pull_down_products('),
( 'Featured Product #4', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_4', '14', 'Select featured product #4 to show', 6, 99, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_pull_down_products('),
( 'Featured Product #5', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_5', '12', 'Select featured product #5 to show', 6, 99, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_pull_down_products('),
( 'Featured Product #6', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_6', '15', 'Select featured product #6 to show', 6, 99, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_pull_down_products('),
( 'Featured Product #7', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_7', '0', 'Select featured product #7 to show', 6, 99, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_pull_down_products('),
( 'Featured Product #8', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_8', '0', 'Select featured product #8 to show', 6, 99, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_pull_down_products('),
( 'Featured Product #9', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_9', '0', 'Select featured product #9 to show', 6, 99, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_pull_down_products('),
( 'Featured Product #10', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_10', '0', 'Select featured product #10 to show', 6, 99, NULL, '2015-02-18 20:11:41', NULL, 'tep_cfg_pull_down_products('),
( 'Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-19 20:21:59', NULL, NULL),
( 'Enable Heading Title', 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_STATUS', 'True', 'Do you want to show the heading title?', 6, 1, NULL, '2015-02-19 20:21:59', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-19 20:21:59', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Enable New Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_STATUS', 'True', 'Do you want to show the New Products box on the front page?', 6, 0, NULL, '2015-02-19 20:45:52', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', 6, 1, NULL, '2015-02-19 20:45:52', NULL, NULL),
( 'Max New Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_MAX_DISPLAY', '6', 'How many New Products do you want to show on the front page?', 6, 2, NULL, '2015-02-19 20:45:52', NULL, NULL),
( 'Number of Columns', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_COLUMNS', '3', 'Number of columns of products to show', 6, 3, NULL, '2015-02-19 20:45:52', NULL, NULL),
( 'Use Cache for New Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_USE_CACHE', 'True', 'If th shop cache is activated. Activate the cache for the New Products Box', 6, 6, NULL, '2015-02-19 20:45:52', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-19 20:45:52', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Dutch Title', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_TITLE_DUTCH', 'Title %s', 'Enter the title that you want on your box in dutch (%s inserts the current month).', 6, 14, NULL, '2015-02-19 20:45:52', NULL, NULL),
( 'English Title', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_TITLE_ENGLISH', 'Title %s', 'Enter the title that you want on your box in english (%s inserts the current month).', 6, 14, NULL, '2015-02-19 20:45:52', NULL, NULL),
( 'Enable Scroller', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_STATUS', 'True', 'Do you want to show the scroller?', 6, 0, NULL, '2015-02-21 19:54:54', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_SORT_ORDER', '75', 'Sort order of display. Lowest is displayed first.', 6, 1, NULL, '2015-02-21 19:54:54', NULL, NULL),
( 'How much products in scroller', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_QNTY_PRODUCTS', '4', 'The amount of products shown per scroll.', 6, 3, NULL, '2015-02-21 19:54:54', NULL, 'tep_cfg_select_option(array(''4'', ''6'', ''12''), '),
( 'Manual Scroll Interval', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_SCROLL_INTERVAL', '4000', 'The time between each manual scroll step (milliseconds).', 6, 12, NULL, '2015-02-21 19:54:55', NULL, NULL),
( 'Products Shown', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCTS_TYPE', 'all', 'What products do you want to show?', 6, 15, NULL, '2015-02-21 19:54:55', NULL, 'tep_cfg_select_option(array(''all'', ''specials'', ''new'', ''featured''), '),
( 'Products Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCTS_ORDER', 'random', 'In what order do you want your products to show?', 6, 16, NULL, '2015-02-21 19:54:55', NULL, 'tep_cfg_select_option(array(''random'', ''date added'', ''last modified''), '),
( 'Use Cache for Products Scroller', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_USE_CACHE', 'True', 'If th shop cache is activated. Activate the cache for the Products Scroller', 6, 20, NULL, '2015-02-21 19:54:55', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Max Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_MAX_DISPLAY', '20', 'The maximum number of products to display in the scroller.', 6, 14, NULL, '2015-02-21 19:54:55', NULL, NULL),
( 'Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-21 19:54:55', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Enable Text Main', 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_STATUS', 'True', 'Do you want to show the main text block on the front page?', 6, 0, NULL, '2015-02-21 20:40:29', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_SORT_ORDER', '80', 'Sort order of display. Lowest is displayed first.', 6, 1, NULL, '2015-02-21 20:40:29', NULL, NULL),
( 'Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-21 20:40:29', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Dutch Text', 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_DUTCH', '<p>Quid ergo hunc aliud moliri, quid optare censetis aut quam omnino causam esse belli?</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>nederlands</p>', 'Enter the text that you want to show on the front page in dutch', 6, 2, NULL, '2015-02-21 20:40:29', NULL, 'tep_draw_textarea_ckeditor(''configuration[MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_DUTCH]'', false, 115, 100, tep_get_config_value( MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_DUTCH ),  tep_get_text_class() , '),
( 'English Text', 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_ENGLISH', '<p>Quid ergo hunc aliud moliri, quid optare censetis aut quam omnino causam esse belli?</p>', 'Enter the text that you want to show on the front page in english', 6, 2, NULL, '2015-02-21 20:40:29', NULL, 'tep_draw_textarea_ckeditor(''configuration[MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_ENGLISH]'', false, 115, 100, tep_get_config_value( MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_ENGLISH ),  tep_get_text_class() , '),
( 'Enable Index Categories Header Tags Social Bookmark Module', 'MODULE_CONTENT_INDEX_FRONTPAGE__HT_SOCIAL_STATUS', 'False', 'Do you want to enable the Index Categories Header Tags Social Bookmark  content module?', 6, 1, NULL, '2015-02-22 20:29:17', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE__HT_SOCIAL_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-22 20:29:17', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE__HT_SOCIAL_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-22 20:29:17', NULL, NULL);

INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Enable Index Products Title Module', 'MODULE_CONTENT_INDEX_PRODUCTS_TITLE_STATUS', 'True', 'Do you want to enable the Index Products Title  content module?', 6, 1, NULL, '2015-02-22 20:49:38', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_PRODUCTS_TITLE_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-22 20:49:38', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_PRODUCTS_TITLE_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-22 20:49:38', NULL, NULL),
( 'Enable Index Categories Description Module', 'MODULE_CONTENT_INDEX_PRODUCTS_DESCRIPTION_STATUS', 'True', 'Do you want to enable the Index Categories Description  content module?', 6, 1, NULL, '2015-02-22 20:57:14', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_PRODUCTS_DESCRIPTION_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-22 20:57:14', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_PRODUCTS_DESCRIPTION_SORT_ORDER', '20', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-22 20:57:14', NULL, NULL),
( 'Enable Index Products Header Tags Social Bookmark Module', 'MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_STATUS', 'True', 'Do you want to enable the Index Products Header Tags Social Bookmark  content module?', 6, 1, NULL, '2015-02-22 21:05:44', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_CONTENT_WIDTH', '3', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-22 21:05:44', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-22 21:05:44', NULL, NULL),
( 'Enable Index Products Manufacturers Select Module', 'MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_STATUS', 'True', 'Do you want to enable the Index Products Manufacturers Select  content module?', 6, 1, NULL, '2015-02-23 19:46:24', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-23 19:46:24', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_SORT_ORDER', '30', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-23 19:46:24', NULL, NULL),
( 'Enable Index Products Title Module', 'MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_STATUS', 'True', 'Do you want to enable the Index Products Title  content module?', 6, 1, NULL, '2015-02-26 19:20:10', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_CONTENT_WIDTH', '12', 'What width container should the content be shown in? (12 = full width, 6 = half width).', 6, 1, NULL, '2015-02-26 19:20:10', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2015-02-26 19:20:10', NULL, NULL);

INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Enable Mail Us Module', 'MODULE_CONTENT_CONTACT_US_MAIL_US_STATUS', 'True', 'Should the Mail module be shown on the contact us page?', 6, 1, NULL, '2016-04-13 19:16:36', NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Content Width', 'MODULE_CONTENT_CONTACT_US_MAIL_US_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', 6, 1, NULL, '2016-04-13 19:16:36', NULL, 'tep_cfg_select_option(array(''12'', ''11'', ''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1''), '),
( 'Sort Order', 'MODULE_CONTENT_CONTACT_US_MAIL_US_SORT_ORDER', '20', 'Sort order of display. Lowest is displayed first.', 6, 0, NULL, '2016-04-13 19:16:36', NULL, NULL);


INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Store Name', 'STORE_NAME', 'osCommerce', 'The name of my store', 1, 1, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Store Owner', 'STORE_OWNER', 'Harald Ponce de Leon', 'The name of my store owner', 1, 2, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('E-Mail Address', 'STORE_OWNER_EMAIL_ADDRESS', 'root@localhost', 'The e-mail address of my store owner', 1, 3, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('E-Mail From', 'EMAIL_FROM', 'osCommerce <root@localhost>', 'The e-mail address used in (sent) e-mails', 1, 4, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Country', 'STORE_COUNTRY', '223', 'The country my store is located in <br /><br /><strong>Note: Please remember to update the store zone.</strong>', 1, 6, 'tep_get_country_name', 'tep_cfg_pull_down_country_list(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Zone', 'STORE_ZONE', '18', 'The zone my store is located in', 1, 7, 'tep_cfg_get_zone_name', 'tep_cfg_pull_down_zone_list(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Expected Sort Order', 'EXPECTED_PRODUCTS_SORT', 'desc', 'This is the sort order used in the expected products box.', 1, 8, 'tep_cfg_select_option(array(\'asc\', \'desc\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Expected Sort Field', 'EXPECTED_PRODUCTS_FIELD', 'date_expected', 'The column to sort by in the expected products box.', 1, 9, 'tep_cfg_select_option(array(\'products_name\', \'date_expected\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Switch To Default Language Currency', 'USE_DEFAULT_LANGUAGE_CURRENCY', 'false', 'Automatically switch to the languages currency when it is changed', 1, 10, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Send Extra Order Emails To', 'SEND_EXTRA_ORDER_EMAILS_TO', '', 'Send extra order emails to the following email addresses, in this format: Name 1 &lt;email@address1&gt;, Name 2 &lt;email@address2&gt;', 1, 11, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Use Search-Engine Safe URLs', 'SEARCH_ENGINE_FRIENDLY_URLS', 'false', 'Use search-engine safe urls for all site links', 1, 12, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Cart After Adding Product', 'DISPLAY_CART', 'true', 'Display the shopping cart after adding a product (or return back to their origin)', 1, 14, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow Guest To Tell A Friend', 'ALLOW_GUEST_TO_TELL_A_FRIEND', 'false', 'Allow guests to tell a friend about a product', 1, 15, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Default Search Operator', 'ADVANCED_SEARCH_DEFAULT_OPERATOR', 'and', 'Default search operators', 1, 17, 'tep_cfg_select_option(array(\'and\', \'or\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Address and Phone', 'STORE_NAME_ADDRESS', 'Store Name\nAddress\nCountry\nPhone', 'This is the Store Name, Address and Phone used on printable documents and displayed online', 1, 18, 'tep_cfg_textarea(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Show Category Counts', 'SHOW_COUNTS', 'false', 'Count recursively how many products are in each category', 1, 19, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Tax Decimal Places', 'TAX_DECIMAL_PLACES', '0', 'Pad the tax value this amount of decimal places', 1, 20, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Prices with Tax', 'DISPLAY_PRICE_WITH_TAX', 'false', 'Display prices with tax included (true) or add the tax at the end (false)', 1, 21, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function) VALUES 
( 'Default Tax Class', 'DEFAULT_PRODUCT_TAX_CLASS', '2', 'Sets the default tax class used for new products in the program CATEGORIES.', 1, 22, 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes('),
( 'Activate BreadCrumb', 'SYS_USE_BREADCRUMB', 'true', 'Activate the BreadCrumb row. The breadcrumb row shows the path to the different categories and products', 1, 50, NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
( 'Use Box New Products', 'SYS_USE_NEW_PROD_INDEX', 'False', 'Activate the BreadCrumb row. The breadcrumb row shows the path to the different categories and products', 1, 100, NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Use Box Customers Also Purchased', 'SYS_USE_ALSO_PURCH_PRO_INFO', 'True', 'Use the box Customers Also Purchased in the Product Info Page.', 1, 105, NULL, 'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Use Banners', 'SYS_USE_BANNERS', 'True', 'Use Banners in the Webshop.', 1, 110, NULL,  'tep_cfg_select_option(array(''True'', ''False''), '),
( 'Use Supertracker', 'SYS_USE_SUPERTRACKER', 'True', 'Use the possibility to store the browser history of all users/visitors of the webshop.', 1, 120, NULL, 'tep_cfg_select_option(array(''True'', ''False''), ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Address', 'STORE_ADDRESS', 'Address Line 1\nAddress Line 2\nCountry\nPhone', 'This is the Address of my store used on printable documents and displayed online', '1', '25', 'tep_cfg_textarea(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Phone', 'STORE_PHONE', '555-1234', 'This is the phone number of my store used on printable documents and displayed online', '1', '26', 'tep_cfg_textarea(', now());


INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('First Name', 'ENTRY_FIRST_NAME_MIN_LENGTH', '2', 'Minimum length of first name', '2', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Last Name', 'ENTRY_LAST_NAME_MIN_LENGTH', '2', 'Minimum length of last name', '2', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Date of Birth', 'ENTRY_DOB_MIN_LENGTH', '10', 'Minimum length of date of birth', '2', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('E-Mail Address', 'ENTRY_EMAIL_ADDRESS_MIN_LENGTH', '6', 'Minimum length of e-mail address', '2', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Street Address', 'ENTRY_STREET_ADDRESS_MIN_LENGTH', '5', 'Minimum length of street address', '2', '5', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Company', 'ENTRY_COMPANY_MIN_LENGTH', '2', 'Minimum length of company name', '2', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Post Code', 'ENTRY_POSTCODE_MIN_LENGTH', '4', 'Minimum length of post code', '2', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('City', 'ENTRY_CITY_MIN_LENGTH', '3', 'Minimum length of city', '2', '8', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('State', 'ENTRY_STATE_MIN_LENGTH', '2', 'Minimum length of state', '2', '9', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Telephone Number', 'ENTRY_TELEPHONE_MIN_LENGTH', '3', 'Minimum length of telephone number', '2', '10', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Password', 'ENTRY_PASSWORD_MIN_LENGTH', '5', 'Minimum length of password', '2', '11', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Credit Card Owner Name', 'CC_OWNER_MIN_LENGTH', '3', 'Minimum length of credit card owner name', '2', '12', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Credit Card Number', 'CC_NUMBER_MIN_LENGTH', '10', 'Minimum length of credit card number', '2', '13', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Review Text', 'REVIEW_TEXT_MIN_LENGTH', '50', 'Minimum length of review text', '2', '14', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Best Sellers', 'MIN_DISPLAY_BESTSELLERS', '1', 'Minimum number of best sellers to display', '2', '15', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Also Purchased', 'MIN_DISPLAY_ALSO_PURCHASED', '1', 'Minimum number of products to display in the \'This Customer Also Purchased\' box', '2', '16', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Address Book Entries', 'MAX_ADDRESS_BOOK_ENTRIES', '5', 'Maximum address book entries a customer is allowed to have', '3', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Search Results', 'MAX_DISPLAY_SEARCH_RESULTS', '20', 'Amount of products to list', '3', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Page Links', 'MAX_DISPLAY_PAGE_LINKS', '5', 'Number of \'number\' links use for page-sets', '3', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Special Products', 'MAX_DISPLAY_SPECIAL_PRODUCTS', '9', 'Maximum number of products on special to display', '3', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('New Products Module', 'MAX_DISPLAY_NEW_PRODUCTS', '9', 'Maximum number of new products to display in a category', '3', '5', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Products Expected', 'MAX_DISPLAY_UPCOMING_PRODUCTS', '10', 'Maximum number of products expected to display', '3', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Manufacturers List', 'MAX_DISPLAY_MANUFACTURERS_IN_A_LIST', '0', 'Used in manufacturers box; when the number of manufacturers exceeds this number, a drop-down list will be displayed instead of the default list', '3', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Manufacturers Select Size', 'MAX_MANUFACTURERS_LIST', '1', 'Used in manufacturers box; when this value is \'1\' the classic drop-down list will be used for the manufacturers box. Otherwise, a list-box with the specified number of rows will be displayed.', '3', '8', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Length of Manufacturers Name', 'MAX_DISPLAY_MANUFACTURER_NAME_LEN', '15', 'Used in manufacturers box; maximum length of manufacturers name to display', '3', '9', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('New Reviews', 'MAX_DISPLAY_NEW_REVIEWS', '6', 'Maximum number of new reviews to display', '3', '10', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Selection of Random Reviews', 'MAX_RANDOM_SELECT_REVIEWS', '10', 'How many records to select from to choose one random product review', '3', '11', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Selection of Random New Products', 'MAX_RANDOM_SELECT_NEW', '10', 'How many records to select from to choose one random new product to display', '3', '12', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Selection of Products on Special', 'MAX_RANDOM_SELECT_SPECIALS', '10', 'How many records to select from to choose one random product special to display', '3', '13', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Categories To List Per Row', 'MAX_DISPLAY_CATEGORIES_PER_ROW', '3', 'How many categories to list per row', '3', '14', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('New Products Listing', 'MAX_DISPLAY_PRODUCTS_NEW', '10', 'Maximum number of new products to display in new products page', '3', '15', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Best Sellers', 'MAX_DISPLAY_BESTSELLERS', '10', 'Maximum number of best sellers to display', '3', '16', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Also Purchased', 'MAX_DISPLAY_ALSO_PURCHASED', '6', 'Maximum number of products to display in the \'This Customer Also Purchased\' box', '3', '17', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Cross Sell', 'MAX_DISPLAY_XSELL', '6', 'Maximum number of products to display in the ''Cross Sell'' box', 3, 18, now() );
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Customer Order History Box', 'MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX', '6', 'Maximum number of products to display in the customer order history box', '3', '19', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Order History', 'MAX_DISPLAY_ORDER_HISTORY', '10', 'Maximum number of orders to display in the order history page', '3', '20', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Product Quantities In Shopping Cart', 'MAX_QTY_IN_CART', '99', 'Maximum number of product quantities that can be added to the shopping cart (0 for no limit)', '3', '21', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Products in Product List', 'MAX_PRODUCT_IN_LIST', '99', 'Maximum product per pages shown in the product list', '3', '22', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Small Image Width', 'SMALL_IMAGE_WIDTH', '100', 'The pixel width of small images', '4', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Small Image Height', 'SMALL_IMAGE_HEIGHT', '80', 'The pixel height of small images', '4', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Heading Image Width', 'HEADING_IMAGE_WIDTH', '57', 'The pixel width of heading images', '4', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Heading Image Height', 'HEADING_IMAGE_HEIGHT', '40', 'The pixel height of heading images', '4', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Subcategory Image Width', 'SUBCATEGORY_IMAGE_WIDTH', '100', 'The pixel width of subcategory images', '4', '5', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Subcategory Image Height', 'SUBCATEGORY_IMAGE_HEIGHT', '57', 'The pixel height of subcategory images', '4', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Calculate Image Size', 'CONFIG_CALCULATE_IMAGE_SIZE', 'true', 'Calculate the size of images?', '4', '7', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Image Required', 'IMAGE_REQUIRED', 'true', 'Enable to display broken images. Good for development.', '4', '8', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());

INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Height Images Mobile Shop',                     'MOBILE_SYS_IMAGE_HEIGHT', '80', 'The Height of the Images in pixels used in the Mobile Shop.', 4, 300, NULL, now(), NULL, NULL),
( 'Width Images Mobile Shop',                      'MOBILE_SYS_IMAGE_WIDTH', '80', 'The Width of the Images in pixels used in the Mobile Shop.', 4, 310, NULL, now(), NULL, NULL),
( 'Height Images Categories Mobile Shop',          'MOBILE_SYS_LISTVIEW_IMAGE_HEIGHT', '80', 'The Height of the Images in pixels used for the categories in the Mobile Shop.', 4, 340, NULL, now(), NULL, NULL),
( 'Width Images Categories Mobile Shop',           'MOBILE_SYS_LISTVIEW_IMAGE_WIDTH', '80', 'The Width of the Images in pixels used for the categoreis in the Mobile Shop.', 4, 350, NULL, now(), NULL, NULL),
( 'Height Image In Stock Mobile Shop',             'MOBILE_SYS_IMG_MAX_HEIGHT_INSTOCK', '20', 'The Height of the Image in pixels used for In Stock in the Mobile Shop.', 4, 360, NULL, now(), NULL, NULL),
( 'Width Image No Stock Mobile Shop',              'MOBILE_SYS_IMG_MAX_WIDTH_INSTOCK', '20', 'The Width of the Image in pixels used for No Stock in the Mobile Shop.', 4, 370, NULL, now(), NULL, NULL) ;


INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'In Stock Image Height', 'SYS_IMAGE_AVAILABILITY_INSTOCK_HEIGHT', '50', 'Image In Stock Height in Product Info ', 4, 200, '2012-06-15 20:09:36', '2012-06-14 22:18:07', NULL, NULL),
( 'In Stock Image Width', 'SYS_IMAGE_AVAILABILITY_INSTOCK_WIDTH', '50', 'Image In Stock Width in Product Info ', 4, 210, '2012-06-15 20:09:42', '2012-06-14 22:18:07', NULL, NULL), 
( 'No Stock Image Height', 'SYS_IMAGE_AVAILABILITY_NOSTOCK_HEIGHT', '100', 'Image No Stock Height in Product Info ', 4, 240, '2012-06-15 20:09:49', '2012-06-14 22:18:07', NULL, NULL),
( 'No Stock Image Width', 'SYS_IMAGE_AVAILABILITY_NOSTOCK_WIDTH', '100', 'Image No Stock Width in Product Info', 4, 250, '2012-06-15 20:09:55', '2012-06-14 22:18:07', NULL, NULL) ;

INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Product Listing Image Width', 'PRODUCT_LIST_IMAGE_WIDTH', '140', 'Product List Image Width.', 4, 400, NULL, '2011-10-08 22:47:30', NULL, ''),
( 'Product Listing Image Height', 'PRODUCT_LIST_IMAGE_HEIGHT', '100', 'Product List Image Height.', 4, 410, NULL, '2011-10-08 22:47:30', NULL, '') ;

INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Product Info Image Width', 'PRODUCT_INFO_IMAGE_WIDTH', '300', 'Product Info Image Width.', 4, 450, NULL, '2011-10-08 22:47:30', NULL, ''),
( 'Product Info Image Height', 'PRODUCT_INFO_IMAGE_HEIGHT', '200', 'Product Info Image Height.', 4, 460, NULL, '2011-10-08 22:47:30', NULL, '') ;

INSERT INTO configuration ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES 
( 'Clear Cache Thumbnails Images', 'SYS_REMOVE_CACHE_THUMBNAILS', 'false', 'Remove all Thumbnails Images.', 4, 500, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), 'tep_remove_thumbnails_images');

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Gender', 'ACCOUNT_GENDER', 'true', 'Display gender in the customers account', '5', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Date of Birth', 'ACCOUNT_DOB', 'true', 'Display date of birth in the customers account', '5', '2', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Company', 'ACCOUNT_COMPANY', 'true', 'Display company in the customers account', '5', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Suburb', 'ACCOUNT_SUBURB', 'true', 'Display suburb in the customers account', '5', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('State', 'ACCOUNT_STATE', 'true', 'Display state in the customers account', '5', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Country of Origin', 'SHIPPING_ORIGIN_COUNTRY', '223', 'Select the country of origin to be used in shipping quotes.', '7', '1', 'tep_get_country_name', 'tep_cfg_pull_down_country_list(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Postal Code', 'SHIPPING_ORIGIN_ZIP', 'NONE', 'Enter the Postal Code (ZIP) of the Store to be used in shipping quotes.', '7', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enter the Maximum Package Weight you will ship', 'SHIPPING_MAX_WEIGHT', '50', 'Carriers have a max weight limit for a single package. This is a common one for all.', '7', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Package Tare weight.', 'SHIPPING_BOX_WEIGHT', '3', 'What is the weight of typical packaging of small to medium packages?', '7', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Larger packages - percentage increase.', 'SHIPPING_BOX_PADDING', '10', 'For 10% enter 10', '7', '5', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Orders Not Matching Defined Shipping Zones ', 'SHIPPING_ALLOW_UNDEFINED_ZONES', 'True', 'Should orders be allowed to shipping addresses not matching defined shipping module shipping zones?', '7', '10', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now()); 
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Add Shipping with Create Orders ', 'SHIPPING_CREATE_ORDER', 'all', 'Add this shipping option when adding an order via create order', '7', '20', 'tep_cfg_select_order_total(', now()); 

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Image', 'PRODUCT_LIST_IMAGE', '1', 'Do you want to display the Product Image?', '8', '10', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Manufaturer Name','PRODUCT_LIST_MANUFACTURER', '0', 'Do you want to display the Product Manufacturer Name?', '8', '20', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Model', 'PRODUCT_LIST_MODEL', '0', 'Do you want to display the Product Model?', '8', '30', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Name', 'PRODUCT_LIST_NAME', '2', 'Do you want to display the Product Name?', '8', '40', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Price', 'PRODUCT_LIST_PRICE', '3', 'Do you want to display the Product Price', '8', '50', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Quantity', 'PRODUCT_LIST_QUANTITY', '0', 'Do you want to display the Product Quantity?', '8', '60', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Weight', 'PRODUCT_LIST_WEIGHT', '0', 'Do you want to display the Product Weight?', '8', '70', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Buy Now column', 'PRODUCT_LIST_BUY_NOW', '4', 'Do you want to display the Buy Now column?', '8', '80', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Category/Manufacturer Filter (0=disable; 1=enable)', 'PRODUCT_LIST_FILTER', '1', 'Do you want to display the Category/Manufacturer Filter?', '8', '200', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Location of Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 'PREV_NEXT_BAR_LOCATION', '2', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', '8', '210', now());

INSERT INTO `configuration` ( `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Display Product Sort Order', 'PRODUCT_SORT_ORDER', '0', 'Do you want to display the Product Sort Order column? ( if this is 0 this coloumn will not been show )', 8, 90, '2011-09-05 20:00:26', '0000-00-00 00:00:00', NULL, NULL),
( 'Display Product Omschrijving', 'PRODUCT_LIST_DESCRIPTION', '0', 'Do you want to display the Product Description column? ( if this is 0 this coloumn will not been show )', 8, 110, '2011-09-05 20:00:26', '0000-00-00 00:00:00', NULL, NULL),
( 'Display Compare Products', 'PRODUCT_COMPARE', '0', 'Activate Compare Products in product listing and place this on the screen ? ( if this is 0 this coloumn will not been show )', 8, 100, '2011-08-28 22:53:45', '0000-00-00 00:00:00', NULL, NULL);

INSERT INTO `configuration` ( `configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Product Listing Buy Now / Details Button', 'LISTING_BUTTON', 'both', 'Display a &lsquo;Buy Now&rsquo; or &lsquo;Details&rsquo; Button', 8, 300, '2012-07-05 19:56:09', '2011-10-08 22:47:30', NULL, 'tep_cfg_select_option(array(''none'', ''buy now'', ''details'', ''both''),'),
( 'Product Listing Headings', 'LISTING_HEADINGS', 'true', 'Show Listing Column Headings (false prevents user sorting listing).', 8, 310, NULL, '2011-10-08 22:47:30', NULL, 'tep_cfg_select_option(array(''true'', ''false''),'),
( 'Product Listing Price Size', 'PRODUCT_PRICE_SIZE', '4', 'Product Listing Price Font Size.', 8, 320, '2013-01-02 20:08:48', '2011-10-08 22:47:30', NULL, ''),
( 'Display Product Truncated Description', 'PRODUCT_LIST_DESCRIPTION_TEXT', 'true', 'Include truncated product description in the product listing. The product \ndescription will be included under the product name, so, that must be included in the product listing too.', 8, 400, NULL, '2011-10-08 22:47:30', NULL, 'tep_cfg_select_option(array(''true'', ''false''),'),
( 'Product Truncated Description Length', 'PRODUCT_LIST_DESCRIPTION_MAX_LENGTH', 50, 'Maximum number of characters for the product truncated description in the product listing.', 8, 410, NULL, '2011-10-08 22:47:30', NULL, '') ;

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check stock level', 'STOCK_CHECK', 'true', 'Check to see if sufficent stock is available', '9', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Subtract stock', 'STOCK_LIMITED', 'true', 'Subtract product in stock by product orders', '9', '2', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow Checkout', 'STOCK_ALLOW_CHECKOUT', 'true', 'Allow customer to checkout even if there is insufficient stock', '9', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Mark product out of stock', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', '***', 'Display something on screen so customer can see which product has insufficient stock', '9', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Stock Re-order level', 'STOCK_REORDER_LEVEL', '5', 'Define when stock needs to be re-ordered', '9', '5', now());

INSERT INTO `configuration` VALUES ('', 'Standard In Stock Status', 'SYS_DEFAULT_AVAILABILITY_INSTOCK', '', 'Which Stock Status should be used standard for In Stock for a new product in the program CATEGORIES.', 9, 100, NULL, now(), 'tep_get_stock_status_title', 'tep_cfg_pull_down_stock_status(');
INSERT INTO `configuration` VALUES ('', 'Standaard Niet Voorraad Status', 'SYS_DEFAULT_AVAILABILITY_NOSTOCK', '', 'Which Stock Status should be used standard for Out of Stock for a new product in the program CATEGORIES.', 9, 110, NULL, now(), 'tep_get_stock_status_title', 'tep_cfg_pull_down_stock_status(');
INSERT INTO `configuration` VALUES ('', 'Use Stock Status', 'SYS_DEFAULT_USE_AVAILABILITY', 'False', 'Use Stock Status on the Produktinfo page.', 9, 120, NULL, now(), NULL, 'tep_cfg_select_option(array(\'True\', \'False\'), ');


INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Product Info Attribute Display Plugin', 'PRODINFO_ATTRIBUTE_PLUGIN', 'multiple_dropdowns', 'The plugin used for displaying attributes on the product information page.', 9, 200, now(), NULL, 'tep_cfg_pull_down_class_files(\'pad_\',');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Show Out of Stock Attributes', 'PRODINFO_ATTRIBUTE_SHOW_OUT_OF_STOCK', 'True', '<b>If True:</b> Attributes that are out of stock will be displayed.<br /><br /><b>If False:</b> Attributes that are out of stock will <b><em>not</em></b> be displayed.</b><br /><br /><b>Default is True.</b>', 9, 210, now(), NULL, 'tep_cfg_select_option(array(\'True\', \'False\'),');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Mark Out of Stock Attributes', 'PRODINFO_ATTRIBUTE_MARK_OUT_OF_STOCK', 'Right', 'Controls how out of stock attributes are marked as out of stock.', 9, 220, now(), NULL, 'tep_cfg_select_option(array(\'None\', \'Right\', \'Left\'),');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Display Out of Stock Message Line', 'PRODINFO_ATTRIBUTE_OUT_OF_STOCK_MSGLINE', 'True', '<b>If True:</b> If an out of stock attribute combination is selected by the customer, a message line informing on this will displayed.', 9, 230, now(), NULL, 'tep_cfg_select_option(array(\'True\', \'False\'),');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Prevent Adding Out of Stock to Cart', 'PRODINFO_ATTRIBUTE_NO_ADD_OUT_OF_STOCK', 'True', '<b>If True:</b> Customer will not be able to ad a product with an out of stock attribute combination to the cart. A javascript form will be displayed.', 9, 240, now(), NULL, 'tep_cfg_select_option(array(\'True\', \'False\'),');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Use Actual Price Pull Downs', 'PRODINFO_ATTRIBUTE_ACTUAL_PRICE_PULL_DOWN', 'False', '<font color="red"><b>NOTE:</b></font> This can only be used with a satisfying result if you have only one option per product.<br /><br /><b>If True:</b> Option prices will displayed as a final product price.<br /><br /><b>Default is false.</b>', 9, 250, now(), NULL, 'tep_cfg_select_option(array(\'True\', \'False\'),');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Display table with stock information', 'PRODINFO_ATTRIBUTE_DISPLAY_STOCK_LIST', 'True', '<b>If True:</b> A table with information on whats on stock will be displayed to the customer. If product doesn\'t have any attributes with tracked stock; the table won\'t be displayed.<br /><br /><b>Default is true.</b>', 9, 260, now(), NULL, 'tep_cfg_select_option(array(\'True\', \'False\'),');

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Page Parse Time', 'STORE_PAGE_PARSE_TIME', 'false', 'Store the time it takes to parse a page', '10', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Log Destination', 'STORE_PAGE_PARSE_TIME_LOG', '/var/log/www/tep/page_parse_time.log', 'Directory and filename of the page parse time log', '10', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Log Date Format', 'STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S', 'The date format', '10', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display The Page Parse Time', 'DISPLAY_PAGE_PARSE_TIME', 'true', 'Display the page parse time (store page parse time must be enabled)', '10', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Database Queries', 'STORE_DB_TRANSACTIONS', 'false', 'Store the database queries in the page parse time log', '10', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Use Cache', 'USE_CACHE', 'false', 'Use caching features', '11', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Cache Directory', 'DIR_FS_CACHE', '/tmp/', 'The directory where the cached files are saved', '11', '2', now());

INSERT INTO `configuration` ( `configuration_title` , `configuration_key` , `configuration_value` , `configuration_description` , `configuration_group_id` , `sort_order` , `last_modified` , `date_added` , `use_function` , `set_function` ) VALUES ( 'Enable Page Cache', 'ENABLE_PAGE_CACHE', 'false', 'Enable the page cache features to reduce server load and faster page renders?<br><br>Contribution by: <b>Chemo</b>', '11', '30', NULL , '0000-00-00 00:00:00', NULL , 'tep_cfg_select_option(array(''true'', ''false''),' );
INSERT INTO `configuration` ( `configuration_title` , `configuration_key` , `configuration_value` , `configuration_description` , `configuration_group_id` , `sort_order` , `last_modified` , `date_added` , `use_function` , `set_function` ) VALUES ( 'Cache Lifetime', 'PAGE_CACHE_LIFETIME', '5', 'How long to cache the pages (in minutes) ?<br><br>Contribution by: <b>Chemo</b>', '11', '35', NULL , '0000-00-00 00:00:00', NULL , NULL );
INSERT INTO `configuration` ( `configuration_title` , `configuration_key` , `configuration_value` , `configuration_description` , `configuration_group_id` , `sort_order` , `last_modified` , `date_added` , `use_function` , `set_function` ) VALUES ( 'Turn on Debug Mode?', 'PAGE_CACHE_DEBUG_MODE', 'false', 'Turn on the global debug output (located at the footer) ? This affects ALL browsers and is NOT for live shops!  YOu can turn on debug mode JUST for your browser by adding "?debug=1" to your URL.<br><br>Contribution by: <b>Chemo</b>', '11', '40' , NULL , '0000-00-00 00:00:00', NULL , 'tep_cfg_select_option(array(''true'', ''false''),' );
INSERT INTO `configuration` ( `configuration_title` , `configuration_key` , `configuration_value` , `configuration_description` , `configuration_group_id` , `sort_order` , `last_modified` , `date_added` , `use_function` , `set_function` ) VALUES ( 'Disable URL Parameters?', 'PAGE_CACHE_DISABLE_PARAMETERS', 'false', 'In some cases (such as search engine safe URL''s) or large number of affiliate referrals will cause excessive page writing.<br><br>Contribution by: <b>Chemo</b>', '11', '45', NULL , '0000-00-00 00:00:00', NULL , 'tep_cfg_select_option(array(''true'', ''false''),' );
INSERT INTO `configuration` ( `configuration_title` , `configuration_key` , `configuration_value` , `configuration_description` , `configuration_group_id` , `sort_order` , `last_modified` , `date_added` , `use_function` , `set_function` ) VALUES ( 'Delete Cache Files?', 'PAGE_CACHE_DELETE_FILES', 'false', 'If set to true the next catalog page request will delete all the cache files and then reset this value to false again.<br><br>Contribution by: <b>Chemo</b>', '11', '50', NULL , '0000-00-00 00:00:00', 'tep_reset_page_cache' , 'tep_cfg_select_option(array(''true'', ''false''),' );
INSERT INTO `configuration` ( `configuration_title` , `configuration_key` , `configuration_value` , `configuration_description` , `configuration_group_id` , `sort_order` , `last_modified` , `date_added` , `use_function` , `set_function` ) VALUES ( 'Config Cache Update File?', 'PAGE_CACHE_UPDATE_CONFIG_FILES', 'none', 'If you have a configuration cache contribution enter the FULL path to the update file.<br><br>Contribution by: <b>Chemo</b>', '11', '60', NULL , '0000-00-00 00:00:00', NULL , NULL );

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('E-Mail Transport Method', 'EMAIL_TRANSPORT', 'sendmail', 'Defines if this server uses a local connection to sendmail or uses an SMTP connection via TCP/IP. Servers running on Windows and MacOS should change this setting to SMTP.', '12', '1', 'tep_cfg_select_option(array(\'sendmail\', \'smtp\'),', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('E-Mail Linefeeds', 'EMAIL_LINEFEED', 'LF', 'Defines the character sequence used to separate mail headers.', '12', '2', 'tep_cfg_select_option(array(\'LF\', \'CRLF\'),', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Use MIME HTML When Sending Emails', 'EMAIL_USE_HTML', 'true', 'Send e-mails in HTML format', '12', '3', 'tep_cfg_select_option(array(\'true\', \'false\'),', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Verify E-Mail Addresses Through DNS', 'ENTRY_EMAIL_ADDRESS_CHECK', 'false', 'Verify e-mail address through a DNS server', '12', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Send E-Mails', 'SEND_EMAILS', 'true', 'Send out e-mails', '12', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());

INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Send Admin Email New Order to', 'EMAIL_ADMIN_CREATE_ORDER', '', 'Enter the Email adress for sending a Email after created New Order by a customer ( if the Email address is empty the Email will not be send )', 12, 10, '2011-05-22 21:50:30', '2009-03-01 18:10:45', NULL, ''),
( 'Attach PDF invoice Email new Order', 'EMAIL_PDF_CREATE_ORDER', 'false', 'Enter if there must be a PDF invoice attachted to the email for a new order', 12, 20, '2011-05-23 20:19:48', '2009-03-01 18:10:45', NULL, 'tep_cfg_select_option(array(''false'', ''true''),'),
( 'Send Admin Email New Customer To', 'EMAIL_ADMIN_CREATE_CUSTOMER', '', 'Enter the Email adress for sending a Email after creation of a New Customer ( if the Email address is empty the Email will not be send )', 12, 30, '2011-05-29 20:05:56', '2009-03-01 18:10:45', NULL, ''),
( 'Send Admin Email New Review to', 'EMAIL_ADMIN_NEW_REVIEW', '', 'Enter the Email adress for sending a Email after the creation of a new Review ( if the Email address is empty the Email will not be send )', 12, 40, '2011-06-06 19:31:49', '2009-03-01 18:10:45', NULL, '');

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable download', 'DOWNLOAD_ENABLED', 'false', 'Enable the products download functions.', '13', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Download by redirect', 'DOWNLOAD_BY_REDIRECT', 'false', 'Use browser redirection for download. Disable on non-Unix systems.', '13', '2', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Expiry delay (days)' ,'DOWNLOAD_MAX_DAYS', '7', 'Set number of days before the download link expires. 0 means no limit.', '13', '3', '', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Maximum number of downloads' ,'DOWNLOAD_MAX_COUNT', '5', 'Set the maximum number of downloads. 0 means no download authorized.', '13', '4', '', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable GZip Compression', 'GZIP_COMPRESSION', 'false', 'Enable HTTP GZip compression.', '14', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Compression Level', 'GZIP_LEVEL', '2', 'Use this compression level 0-9 (0 = minimum, 9 = maximum).', '14', '2', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Session Directory', 'SESSION_WRITE_DIRECTORY', '/tmp', 'If sessions are file based, store them in this directory.', '15', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Force Cookie Use', 'SESSION_FORCE_COOKIE_USE', 'True', 'Force the use of sessions when cookies are only enabled.', '15', '2', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check SSL Session ID', 'SESSION_CHECK_SSL_SESSION_ID', 'False', 'Validate the SSL_SESSION_ID on every secure HTTPS page request.', '15', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check User Agent', 'SESSION_CHECK_USER_AGENT', 'False', 'Validate the clients browser user agent on every page request.', '15', '4', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check IP Address', 'SESSION_CHECK_IP_ADDRESS', 'False', 'Validate the clients IP address on every page request.', '15', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Prevent Spider Sessions', 'SESSION_BLOCK_SPIDERS', 'True', 'Prevent known spiders from starting a session.', '15', '6', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Recreate Session', 'SESSION_RECREATE', 'True', 'Recreate the session to generate a new session ID when the customer logs on or creates an account (PHP >=4.1 needed).', '15', '7', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());

INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
('Add category parent to beginning of category uris?', 'USU5_ADD_CAT_PARENT', 'true', 'This setting will add the category parent name to the beginning of the category URLs (i.e. - parent-category-c-1.html).', 16, 10, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
('Reset USU5 Cache', 'USU5_RESET_CACHE', 'false', 'This will reset the cache data for USU5', 16, 17, '2011-01-01 20:43:25', '2011-01-01 20:43:25', 'tep_reset_cache_data_usu5', 'tep_cfg_select_option(array(''reset'', ''false''), '),
('Force www.mysite.com/ when www.mysite.com/index.php', 'USU5_HOME_PAGE_REDIRECT', 'false', 'Force a redirect to www.mysite.com/ when www.mysite.com/index.php', 16, 16, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
('Turn variable reporting on true/false.', 'USU5_DEBUG_OUPUT_VARS', 'false', '<span style="color: red;">Variable reporting should not be set to ON on a live site</span><br>It is for reporting the contents of USU classes and shows unformatted at the bottom of your site.', 16, 15, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
('Choose the uri format', 'USU5_URLS_TYPE', 'standard', '<b>Choose USU5 URL format:</b>', 16, 7, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''standard'',''path_standard'',''rewrite'',''path_rewrite'',), '),
('Choose how your product link text is made up', 'USU5_PRODUCTS_LINK_TEXT_ORDER', 'p', 'Product link text can be made up of:<br /><b>p</b> = product name<br /><b>c</b> = category name<br /><b>b</b> = manufacturer (brand)<br /><b>m</b> = model<br />e.g. <b>bp</b> (brand/product)', 16, 8, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, NULL),
('Enable SEO URLs 5?', 'USU5_ENABLED', 'true', 'Turn Seo Urls 5 on', 16, 1, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
('Enable the cache?', 'USU5_CACHE_ON', 'true', 'Turn the cache system on', 16, 2, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
('Enable multi language support?', 'USU5_MULTI_LANGUAGE_SEO_SUPPORT', 'true', 'Enable the multi language functionality', 16, 3, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
('Output W3C valid URLs?', 'USU5_USE_W3C_VALID', 'true', 'This setting will output W3C valid URLs.', 16, 4, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
('Select your chosen cache system?', 'USU5_CACHE_SYSTEM', 'file', 'Choose from the 4 available caching strategies.', 16, 5, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''mysql'', ''file'',''sqlite'',''memcache''), '),
('Set the number of days to store the cache.', 'USU5_CACHE_DAYS', '7', 'Set the number of days you wish to retain cached data, after this the cache will auto reset.', 16, 6, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, NULL),
('Enter special character conversions. <b>(Better to use the file based character conversions)</b>', 'USU5_CHAR_CONVERT_SET', '', 'This setting will convert characters.<br><br>The format <b>MUST</b> be in the form: <b>char=>conv,char2=>conv2</b>', 16, 13, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, NULL),
('Turn performance reporting on true/false.', 'USU5_OUPUT_PERFORMANCE', 'false', '<span style="color: red;">Performance reporting should not be set to ON on a live site</span><br>It is for reporting re: performance and queries and shows at the bottom of your site.', 16, 14, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
('Remove all non-alphanumeric characters?', 'USU5_REMOVE_ALL_SPEC_CHARS', 'true', 'This will remove all non-letters and non-numbers. If your language has special characters then you will need to use the character conversion system.', 16, 11, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
('Add cPath to product URLs?', 'USU5_ADD_CPATH_TO_PRODUCT_URLS', 'false', 'This setting will append the cPath to the end of product URLs (i.e. - some-product-p-1.html?cPath=xx).', 16, 12, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
('Filter Short Words', 'USU5_FILTER_SHORT_WORDS', '2', '<b>This setting will filter words.</b><br>1 = Remove words of 1 letter<br>2 = Remove words of 2 letters or less<br>3 = Remove words of 3 letters or less<br>', 16, 9, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''1'',''2'',''3'',), ');

INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Use Progress Bars?', 'OPTIONS_TYPE_PROGRESS', 'Both', 'Set to use the Progress bar for Text Options<br>None = No Progress Bars<br>Text = Textfields only<br>TextArea = TextAreas only<br>Both = Both Text Fields and Areas', 17, 4, now(), now(), NULL, 'tep_cfg_select_option(array(\'None\', \'Text\', \'TextArea\', \'Both\'),'),
( 'Upload File Prefix', 'OPTIONS_TYPE_FILEPREFIX', 'Database', 'The prefix that is used to generate unique filenames for uploads.<br>Database = insert id from database<br>Date = the upload Date<br>Time = the upload Time<br>DateTime = Upload Date and Time', 17, 5, now(), now(), NULL, 'tep_cfg_select_option(array(\'Database\', \'Date\', \'Time\', \'DateTime\'),'),
( 'Delete Uploads older than', 'OPTIONS_TYPE_PURGETIME', '-2 weeks', 'Uploads in the Temporary folder are automatically deleted when older than this setting.<br>Usage: -2 weeks/-5 days/-1 year/etc.', 17, 6, now(), now(), NULL, NULL),
( 'Upload Directory', 'UPL_DIR', 'images/uploads/', 'The directory to store uploads from registered customers.', 17, 7, now(), now(), NULL, NULL),
( 'Temporary Directory', 'TMP_DIR', 'images/temp/', 'The directory to store temporary uploads (from guests) which is automatically cleaned.', 17, 8, now(), now(), NULL, NULL),
( 'Option Type Image - Images Directory', 'OPTIONS_TYPE_IMAGEDIR', 'images/options/', 'What directory to look for Option Type Images.<br>This is where the Images should be stored.', 17, 9, now(), now(), NULL, NULL),
( 'Option Type Image - Images Prefix', 'OPTIONS_TYPE_IMAGEPREFIX', 'Option_', 'What prefix to use when looking for Option Type Images.<br>This is what the Images name should begin with.', 17, 10, now(), now(), NULL, NULL),
( 'Option Type Image - Images Name', 'OPTIONS_TYPE_IMAGENAME', 'Name', 'What Option Value item to use as Name for the Option Type Images.<br>When set to "Name", the images should be named: "PREFIX"-"Option value name"-"LanguageID".jpg (Option_RedShirt_1.jpg)<br>When set to "ID", the images should be named: "PREFIX"-"Option value ID"-"LanguageID".jpg (Option_5_1.jpg)', 17, 11, now(), now(), NULL, 'tep_cfg_select_option(array(\'Name\', \'ID\'),'),
( 'Option Type Image - Use Language ID', 'OPTIONS_TYPE_IMAGELANG', 'Yes', 'Use language ID in Option Type Images Names?<br>This is only needed if different images are used per Language (images with text for example).', 17, 12, now(), now(), NULL, 'tep_cfg_select_option(array(\'Yes\', \'No\'),');

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, last_modified, date_added)
 VALUES ('Enable Version Checker', 'EASYMAP_ENABLE_VERSION_CHECKER', 'false', 'Enables the version checking code to automatically check if an update is available.', 30, 10, 'tep_cfg_select_option(array(\'true\', \'false\'), ', NULL, now(), now() );
INSERT INTO configuration ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, last_modified, date_added) 
 VALUES ('Activeer een HTML Editor', 'EASYMAP_HTML_EDITOR', 'CKEditor', 'Use an HTML editor, if selected. !!! Warning !!! The selected editor must be installed for it to work!!!)', 30, 15, 'tep_cfg_select_option(array(\'CKEditor\', \'FCKEditor\', \'TinyMCE\', \'No Editor\'),', NULL, now(), now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function) VALUES ('<B>Down for Maintenance: ON/OFF</B>', 'DOWN_FOR_MAINTENANCE', 'false', 'Down for Maintenance <br>(true=on false=off)', '32', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function) VALUES ('Down for Maintenance: filename', 'DOWN_FOR_MAINTENANCE_FILENAME', 'down_for_maintenance.php', 'Down for Maintenance filename Default=down_for_maintenance.php', '32', '2', '', now(), NULL);
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function) VALUES ('Down for Maintenance: Hide Header', 'DOWN_FOR_MAINTENANCE_HEADER_OFF', 'false', 'Down for Maintenance: Hide Header <br>(true=hide false=show)', '32', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function) VALUES ('Down for Maintenance: Hide Column Left', 'DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF', 'false', 'Down for Maintenance: Hide Column Left <br>(true=hide false=show)', '32', '4', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function) VALUES ('Down for Maintenance: Hide Column Right', 'DOWN_FOR_MAINTENANCE_COLUMN_RIGHT_OFF', 'false', 'Down for Maintenance: Hide Column Right <br>(true=hide false=show)r', '32', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function) VALUES ('Down for Maintenance: Hide Footer', 'DOWN_FOR_MAINTENANCE_FOOTER_OFF', 'false', 'Down for Maintenance: Hide Footer <br>(true=hide false=show)', '32', '6', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function) VALUES ('Down for Maintenance: Hide Prices', 'DOWN_FOR_MAINTENANCE_PRICES_OFF', 'false', 'Down for Maintenance: Hide Prices <br>(true=hide false=show)', '32', '7', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Down For Maintenance (exclude this IP-Address)', 'EXCLUDE_ADMIN_IP_FOR_MAINTENANCE', 'your IP (ADMIN)', 'This IP Address is able to access the website while it is Down For Maintenance (like webmaster)', 32, 8, '2003-03-21 13:43:22', '2003-03-21 21:20:07', NULL, NULL);
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('NOTICE PUBLIC Before going Down for Maintenance: ON/OFF', 'WARN_BEFORE_DOWN_FOR_MAINTENANCE', 'false', 'Give a WARNING some time before you put your website Down for Maintenance<br>(true=on false=off)<br>If you set the \'Down For Maintenance: ON/OFF\' to true this will automaticly be updated to false', 32, 9, '2003-03-21 13:08:25', '2003-03-21 11:42:47', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Date and hours for notice before maintenance', 'PERIOD_BEFORE_DOWN_FOR_MAINTENANCE', '15/05/2003  2-3 PM', 'Date and hours for notice before maintenance website, enter date and hours for maintenance website', 32, 10, '2003-03-21 13:08:25', '2003-03-21 11:42:47', NULL, NULL);
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Display when webmaster has enabled maintenance', 'DISPLAY_MAINTENANCE_TIME', 'false', 'Display when Webmaster has enabled maintenance <br>(true=on false=off)<br>', 32, 11, '2003-03-21 13:08:25', '2003-03-21 11:42:47', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Display website maintenance period', 'DISPLAY_MAINTENANCE_PERIOD', 'false', 'Display Website maintenance period <br>(true=on false=off)<br>', 32, 12, '2003-03-21 13:08:25', '2003-03-21 11:42:47', NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Website maintenance period', 'TEXT_MAINTENANCE_PERIOD_TIME', '2h00', 'Enter Website Maintenance period (hh:mm)', 32, 13, '2003-03-21 13:08:25', '2003-03-21 11:42:47', NULL, NULL);

INSERT into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Display the Payment Method dropdown?', 'ORDER_EDITOR_PAYMENT_DROPDOWN', 'true', 'Based on this selection Order Editor will display the payment method as a dropdown menu (true) or as an input field (false).', '72', '1', now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Use prices from Separate Pricing Per Customer?', 'ORDER_EDITOR_USE_SPPC', 'false', 'Leave this set to false unless SPPC is installed.', '72', '3', now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Use QTPro contribution?', 'ORDER_EDITOR_USE_QTPRO', 'false', 'Leave this set to false unless you have QTPro Installed.', '72', '4', now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Allow the use of AJAX to update order information?', 'ORDER_EDITOR_USE_AJAX', 'true', 'This must be set to false if using a browser on which JavaScript is disabled or not available.', '72', '5', now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');
INSERT into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Select your credit card payment method', 'ORDER_EDITOR_CREDIT_CARD', 'Credit Card', 'Order Editor will display the credit card fields when this payment method is selected.', '72', '6', now(), now(), NULL, 'tep_cfg_pull_down_payment_methods(');
INSERT into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) values ('Attach PDF Invoice to New Order Email', 'ORDER_EDITOR_ADD_PDF_INVOICE_EMAIL', 'false', 'When you send a new Order Email a PDF Invoice kan be attach to your email. This function only works if the contribution PDF Invoice is installed.', '72', '15', now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');

INSERT into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
('Maximum number of price break levels', 'PRICE_BREAK_NOF_LEVELS', '10', 'Configures the number of price break levels that can be entered on admin side. Levels that are left empty will not be shown to the customer', '73', '1', now(), now(), NULL, NULL), 
('Number of price breaks for dropdown', 'NOF_PRICE_BREAKS_FOR_DROPDOWN', '10', 'Set the number of price breaks at which you want to show a dropdown plus "from Low Price" instead of a table', '73', '2', now(), now(), NULL, NULL);
;

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
( 'Shop Logo Print on Label',       'SHIPLABEL_SHOW_LOGO',         'true',                  'Print the shoplogo on the label ? False = no, True = Yes',                                                            200, 1,  '2005-06-08 21:01:41', '0000-00-00 00:00:00', NULL,    'tep_cfg_select_option(array(\'true\', \'false\')'),
( 'Location off the ShopLogo',      'SHIPLABEL_STORE_LOGO',        'store_logo.png', 'Give the location of the ShopeLogo',                                                                                  200, 2,  '2005-06-09 17:36:29', '0000-00-00 00:00:00', NULL, NULL),
( 'ShippingLabel Widht',            'SHIPLABEL_WIDTH',             '148',                   'The width of the label in millimeters.',                                                                              200, 3,  '2005-06-08 20:50:20', '0000-00-00 00:00:00', NULL, NULL),
( 'ShippingLabel Height',           'SHIPLABEL_HEIGHT',            '105',                   'The height of the label in millimeters.',                                                                             200, 4,  '2005-06-09 15:35:12', '0000-00-00 00:00:00', NULL, NULL),
( 'Shipping Label Background Color','SHIPLABEL_BG_COLOR',          '250,250,250',           'The backgroundcolor of the page. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Values per color from 0-255.',   200, 5,  '2005-06-10 14:52:30', '0000-00-00 00:00:00', NULL, NULL),
( 'Text Color',                     'SHIPLABEL_BODY_COLOR_TEXT',   '0,0,0',                 'The Text color of the page. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Values per color from 0-255.',        200, 6,  '2005-06-10 14:54:36', '0000-00-00 00:00:00', NULL, NULL),
( 'Text letter height',             'SHIPLABEL_TEXT_HEIGHT',       '30',                    'The height of the text',                                                                                              200, 7,  '2005-06-10 14:52:30', '0000-00-00 00:00:00', NULL, NULL),
( 'Footer Text',                    'SHIPLABEL_FOOTER_TEXT',       '',                      'The footer text.',                                                                                                    200, 8,  '2005-06-10 14:45:05', '0000-00-00 00:00:00', NULL, NULL),
( 'Footer Text Color',              'SHIPLABEL_FOOTER_COLOR_TEXT', '200,0,0',               'The backgroundcolor of the page. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Values per color from 0-255.',   200, 9,  '2005-06-10 14:45:05', '0000-00-00 00:00:00', NULL, NULL),
( 'Label Orientation',              'SHIPLABEL_PORTRAIT_LANDSCAPE','L',                     'The Label oriontation. P= protrait L= Landscape',                                                                     200, 10, '2005-06-09 15:35:12', '0000-00-00 00:00:00', NULL, NULL),
( 'Footer Text Height',             'SHIPLABEL_FOOTER_TEXT_HEIGHT','5',                     'The hieght of the footer text',                                                                                       200, 11, '2005-06-10 14:52:30', '0000-00-00 00:00:00', NULL, NULL) ;

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES
('Display the model', 'DISPLAY_MODEL', 'true', 'Enable/Disable the model displaying', 300, 1, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Modify the model', 'MODIFY_MODEL', 'false', 'Allow/Disallow the model modification', 300, 2, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Modify the name of the products', 'MODIFY_NAME', 'false', 'Allow/Disallow the name modification?', 300, 3, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Modify the status of the products', 'DISPLAY_STATUT', 'true', 'Allow/Disallow the Statut displaying and modification', 300, 4, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Modify the weight of the products', 'DISPLAY_WEIGHT', 'true', 'Allow/Disallow the Weight displaying and modification?', 300, 5, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Modify the quantity of the products', 'DISPLAY_QUANTITY', 'true', 'Allow/Disallow the quantity displaying and modification', 300, 6, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Modify the image of the products', 'DISPLAY_IMAGE', 'false', 'Allow/Disallow the Image displaying and modification', 300, 7, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Modify the manufacturer of the products', 'MODIFY_MANUFACTURER', 'false', 'Allow/Disallow the Manufacturer displaying and modification', 300, 8, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Modify the class of tax of the products', 'MODIFY_TAX', 'false', 'Allow/Disallow the Class of tax displaying and modification', 300, 9, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Display price with all included of tax', 'DISPLAY_TVA_OVER', 'true', 'Enable/Disable the displaying of the Price with all tax included when your mouse is over a product', 300, 10, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Display price with all included of tax', 'DISPLAY_TVA_UP', 'true', 'Enable/Disable the displaying of the Price with all tax included when you are typing the price?', 300, 11, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Display the link towards the products information page', 'DISPLAY_PREVIEW', 'false', 'Enable/Disable the display of the link towards the products information page ', 300, 12, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Display the link towards the page where you will be able to edit the product', 'DISPLAY_EDIT', 'true', 'Enable/Disable the display of the link towards the page where you will be able to edit the product', 300, 13, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Display the manufacturer', 'DISPLAY_MANUFACTURER', 'false', 'Do you want just display the manufacturer ?', 300, 7, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Display the tax', 'DISPLAY_TAX', 'true', 'Do you want just display the tax ?', 300, 8, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),'),
('Activate or deactivate the commercial margin', 'ACTIVATE_COMMERCIAL_MARGIN', 'true', 'Do you want that the commercial margin be activate or not ?', 300, 14, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),');

INSERT INTO configuration ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added ) VALUES 
( 'Category Icon Mode',                   'BRWCAT_ICON_MODE',     'off',
  'Choose between Disabled, Text and Image without or with Caption for Current Level Categories Icons.<br /><b>Note</b>: Image Only mode causes the category name to be displayed on top of its sub-category links.',
  401, 1, 'tep_cfg_select_option(array(\'off\', \'text\', \'image only\', \'image with caption\'), ', now()
);
INSERT INTO configuration (
  configuration_title, configuration_key, configuration_value, configuration_description,
  configuration_group_id, sort_order, set_function, date_added
) VALUES (
  'Sub-Category Link Mode',               'BRWCAT_SUBCAT_MODE',   'right top',
  'Choose between Disabled, Bottom or Right position of Sub-Category Links.',
  401, 2, 'tep_cfg_select_option(array(\'off\', \'bottom\', \'right top\', \'right middle\', \'right bottom\'), ', now()
);
INSERT INTO configuration (
  configuration_title, configuration_key, configuration_value, configuration_description,
  configuration_group_id, sort_order, set_function, date_added
) VALUES (
  'Max number of category Icons per Row', 'BRWCAT_ICONS_PER_ROW', '2',
  'Choose how many Current Level Categories to display per row.',
  401, 3, null, now()
);
INSERT INTO configuration (
  configuration_title, configuration_key, configuration_value, configuration_description,
  configuration_group_id, sort_order, set_function, date_added
) VALUES (
  'Sub-Category Links Bullet',            'BRWCAT_SUBCAT_BULLET', '»&nbsp;',
  'Select Bullet character to prefix each Sub Category Link.<br /><b>Note</b>: Default bullet is "» ", where the whitespace must be entered has entity &nbsp.',
  401, 4, null, now()
);
INSERT INTO configuration (
  configuration_title, configuration_key, configuration_value, configuration_description,
  configuration_group_id, sort_order, set_function, date_added
) VALUES (
  'Sub-Category Products Count',          'BRWCAT_SUBCAT_COUNTS', '(%s)',
  'Define sprintf format to display Sub-Category Products count.<br /><b>Note</b>: Default format is (%s) that causes the products count to be displayed surrounded by parentesis. For more information, read the PHP manual for sprintf function.',
  401, 5, null, now()
);
INSERT INTO configuration (
  configuration_title, configuration_key, configuration_value, configuration_description,
  configuration_group_id, sort_order, set_function, date_added
) VALUES (
  'Category Name Case',                   'BRWCAT_NAME_CASE',     'same',
  'Choose between same case, upper case, lower case or title case for Current Level Categories Name.',
  401, 6, 'tep_cfg_select_option(array(\'same\', \'upper\', \'lower\', \'title\'), ', now()
);

INSERT INTO configuration VALUES ('', 'Google API Key', 							'GOOGLE_MAP_API_KEY', 			'Your Google Maps API Key', 'Enter your Google Maps API Key.<br />If you dont have one you can sign up for one free <a target="_blank" class="adminLink" href="http://code.google.com/apis/maps/signup.html"><b>Google Maps API</b></a>', 449, 2, NULL, now(), NULL, NULL);

INSERT INTO configuration VALUES ('', 'Store Street Address - Used by Google Maps', 'GOOGLE_STORE_STREET_ADDRESS', '111 Any St', 'The street address of my store', 449, 6, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Store City - Used by Google Maps', 			'GOOGLE_STORE_CITY', 'Anytown', 'The city my store is located in', 449, 7, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Store State - Used by Google Maps', 			'GOOGLE_STORE_STATE', 'Anystate', 'The state my store is located in', 449, 8, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Store Postal Code - Used by Google Maps', 	'GOOGLE_STORE_POSTCODE', '11111', 'The postal zip code of my store', 449, 9, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Store Country - Used by Google Maps', 		'GOOGLE_STORE_COUNTRY', 'United States', 'The country my store is located in', 449, 10, NULL, now(), NULL, NULL);

INSERT INTO configuration VALUES ('', 'Google Maps Heading Text           ', 'GOOGLE_MAP_HEADING_TITLE',    'Customer Chart',   		'The Text at the Top of the Google Map ', 		449, 15, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Google Maps Breedte                ', 'GOOGLE_MAP_WIDTH',            '1050',           			'The Length of the Google Map',           		449, 16, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Google Maps Hoogte                 ', 'GOOGLE_MAP_HEIGHT',           '650',            			'The Height of the Google Map',            		449, 17, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Google Maps Latitude Position      ', 'GOOGLE_MAP_CENTER_LAT',       '52.216314',      			'The middle postion on the Google Map Latitude',  	449, 18, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Google Maps Longtitude Position    ', 'GOOGLE_MAP_CENTER_LNG',       '5.964650',       			'The middle postion on the Google Map Longtitude', 	449, 19, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Google Maps Zoom Setting           ', 'GOOGLE_MAP_CENTER_ZOOM',      '6',              			'The Zoom factor on the map',    			449, 20, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Google Maps Text Details Order     ', 'GOOGLE_MAP_DETAIL_TXT',       'Order Details',			'The Text for the order details ', 			449, 21, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Google Maps Text Loading           ', 'GOOGLE_MAP_LOADING_TXT',      'Google Maps loading...',           'The Text during loading of the Google Map',    	449, 22, NULL, now(), NULL, NULL);
INSERT INTO configuration VALUES ('', 'Google Maps Order Totaal           ', 'GOOGLE_MAP_HIGHLIGHTVALUE',   '100.0',  				'The order totaal for displaying the different pins ',  449, 23, NULL, now(), NULL, NULL);

INSERT into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) 
                          VALUES(     'SelectOrder Status'                 , 'GOOGLE_MAP_ORDER_STATUS',     '3',                   'Select one Order Status if this order status is changed by the customer order the Google Map coordinats will updated with the google map coordinates.', '449', '25', now(), now(), NULL, 'tep_cfg_pull_down_order_statuses(');

INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Automatically Add New Pages', 'HEADER_TAGS_AUTO_ADD_PAGES', 'true', 'Adds any new pages when Page Control is accessed<br>(true=on false=off)', 543, 10, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Check for Missing Tags', 'HEADER_TAGS_CHECK_TAGS', 'true', 'Check to see if any products, categories or manufacturers contain empty meta tag fields<br>(true=on false=off)', 543, 20, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Clear Cache', 'HEADER_TAGS_CLEAR_CACHE', 'false', 'Remove all Header Tags cache entries from the database.', 543, 30, 'tep_cfg_select_option(array(\'clear\', \'false\'), ', now(), 'header_tags_reset_cache');
  
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=purple>Display Category Parents in Title and Tags</font>', 'HEADER_TAGS_ADD_CATEGORY_PARENTS', 'Standard', 'Adds all categories in the current path (Full), all immediate categories if the product is in more than one category (Duplicate) or only the immediate category (Standard). These settings only work if the Category checkbox is enabled in Page Control.', 543, 40, 'tep_cfg_select_option(array(''Full Category Path'', ''Duplicate Categories'', ''Standard''), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=purple>Display Column Box</font>', 'HEADER_TAGS_DISPLAY_COLUMN_BOX', 'false', 'Display product box in column while on product page<br>(true=on false=off)', 543, 50, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=purple>Display Currently Viewing</font>', 'HEADER_TAGS_DISPLAY_CURRENTLY_VIEWING', 'true', 'Display a link near the bottom of the product page.<br>(true=on false=off)', 543, 60, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=purple>Display Help Popups</font>', 'HEADER_TAGS_DISPLAY_HELP_POPUPS', 'true', 'Display short popup messages that describes a feature<br>(true=on false=off)', 543, 70, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=purple>Disable Permission Warning</font>', 'HEADER_TAGS_DIABLE_PERMISSION_WARNING', 'false', 'Prevent the warning that appears if the permissions for the includes/header_tags.php file appear to be incoorect.<br>(true=on false=off)', 543, 80, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=purple>Display Page Top Title</font>', 'HEADER_TAGS_DISPLAY_PAGE_TOP_TITLE', 'true', 'Displays the page title at the very top of the page<br>(true=on false=off)', 543, 90, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=purple>Display Silo Links</font>', 'HEADER_TAGS_DISPLAY_SILO_BOX', 'false', 'Display a box displaying links based on the settings in Silo Control<br>(true=on false=off)', 543, 100, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=purple>Display Social Bookmark</font>', 'HEADER_TAGS_DISPLAY_SOCIAL_BOOKMARKS', 'multi', 'Display social bookmarks on the product page<br>(true=on false=off)', 543, 110, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=purple>Display Tag Cloud</font>', 'HEADER_TAGS_DISPLAY_TAG_CLOUD', 'false', 'Display the Tag Cloud infobox<br>(true=on false=off)', 543, 120, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
  
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=blue>Enable AutoFill - Listing Text</font>', 'HEADER_TAGS_ENABLE_AUTOFILL_LISTING_TEXT', 'false', 'If true, text will be shown on the product listing page automatically. If false, the text only shows if the field has text in it.', 543, 130, 'tep_cfg_select_option(array(\'true\', \'false\'),', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=blue>Enable Cache</font>', 'HEADER_TAGS_ENABLE_CACHE', 'None', 'Enables cache for Header Tags. The GZip option will use gzip to try to increase speed but may be a little slower if the Header Tags data is small.', 543, 140, 'tep_cfg_select_option(array(\'None\', \'Normal\', \'GZip\'),', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=blue>Enable an HTML Editor</font>', 'HEADER_TAGS_ENABLE_HTML_EDITOR', 'CKEditor', 'Use an HTML editor, if selected. !!! Warning !!! The selected editor must be installed for it to work!!!)', 543, 150, 'tep_cfg_select_option(array(\'CKEditor\', \'FCKEditor\', \'TinyMCE\', \'No Editor\'),', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=blue>Enable HTML Editor for Category Descriptions</font>', 'HEADER_TAGS_ENABLE_EDITOR_CATEGORIES', 'true', 'Enables the selected HTML editor for the categories description box. The editor must be installed for this to work.', 543, 160, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=blue>Enable HTML Editor for Products Descriptions</font>', 'HEADER_TAGS_ENABLE_EDITOR_PRODUCTS', 'true', 'Enables the selected HTML editor for the products description box. The editor must be installed for this to work.', 543, 170, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=blue>Enable HTML Editor for Product Listing text</font>', 'HEADER_TAGS_ENABLE_EDITOR_LISTING_TEXT', 'false', 'Enables the selected HTML editor for the Header Tags text on the product listing page. The editor must be installed for this to work.', 543, 180, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=blue>Enable HTML Editor for Product Sub Text</font>', 'HEADER_TAGS_ENABLE_EDITOR_SUB_TEXT', 'false', 'Enables the selected HTML editor for the sub text on the products page. The editor must be installed for this to work.', 543, 190, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'<font color=blue>Enable Version Checker</font>', 'HEADER_TAGS_ENABLE_VERSION_CHECKER', 'true', 'Enables the code that checks if updates are available.', 543, 200, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
  
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Keyword Density Range', 'HEADER_TAGS_KEYWORD_DENSITY_RANGE', '0.02,0.06', 'Set the limits for the keyword density use to dynamically select the keywords. Enter two figures, separated by a comma.', 543, 210, NULL, now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Position Domain', 'HEADER_TAGS_POSITION_DOMAIN', '', 'Set the domain name to be used in the keyword position checking code, like www.domain_name.com or domain_name.com/shop.', 543, 220, NULL, now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Position Page Count', 'HEADER_TAGS_POSITION_PAGE_COUNT', '2', 'Set the number of pages to search when checking keyword positions (10 urls per page).', 543, 230, NULL, now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Separator - Description', 'HEADER_TAGS_SEPARATOR_DESCRIPTION', '-', 'Set the separator to be used for the description (and titles and logo).', 543, 240, NULL, now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Separator - Keywords', 'HEADER_TAGS_SEPARATOR_KEYWORD', ',', 'Set the separator to be used for the keywords.', 543, 250, NULL, now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Search Keywords', 'HEADER_TAGS_SEARCH_KEYWORDS', 'false', 'This option allows keywords stored in the Header Tags SEO search table to be searched when a search is performed on the site.', 543, 260, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Store Keywords', 'HEADER_TAGS_STORE_KEYWORDS', 'true', 'This option stores the searched for keywords so they can be used by other parts of Header Tags, like in the Tag Cloud option.', 543, 270, 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Tag Cloud Column Count', 'HEADER_TAGS_TAG_CLOUD_COLUMN_COUNT', '8', 'Set the number of keywords to display in a row in the Tag Cloud box.', 543, 280, NULL, now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Display Category Short Description', 'HEADER_TAGS_DISPLAY_CATEGORY_SHORT_DESCRIPTION', 'Off', 'If a number is entered, that many characters of the category description will be displayed under the category name on the category listing page. <br><br>Leave blank to display all of the text (not recommended). <br><br>Enter \'Off\' to disable this option..', 543, 300, NULL, now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Keyword Highlighter', 'HEADER_TAGS_KEYWORD_HIGHLIGHTER', 'No Highlighting', 'Bold any keywords found on the page.', 543, 320, 'tep_cfg_select_option(array(\'No Highlighting\', \'Highlight Full Words Only\', \'Highlight Individual Words\'),', now(), NULL);
INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function ) VALUES (NULL,'Use Item Name on Page', 'HEADER_TAGS_USE_PAGE_NAME', 'false', 'If true, the title on the page will be the name of the item (category, manufacturer or product). If false, the Header Tags SEO title will be used.', 543, 340, 'tep_cfg_select_option(array(\'true\', \'false\'),', now(), NULL);


INSERT INTO configuration ( configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ) VALUES (null, 'Activeer HTML Editor voor Meta Descriptions', 'HEADER_TAGS_ENABLE_EDITOR_META_DESC', 'false', 'Activeer de geselecteerde HTML editor voor de  meta tag omschrijving box. De editor moet geinstalleerd zijn.', 543, 290, 'tep_cfg_select_option(array(''true'', ''false''), ') ;

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Must accept when registering', 'MATC_AT_REGISTER', 'false', '<b>If true</b>, the customer must accept the Terms &amp; Conditions <b>when registrating</b>.', '730', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Must accept at checkout', 'MATC_AT_CHECKOUT', 'false', '<b>If true</b>, the customer must accept the Terms &amp; Conditions <b>at the order confirmation</b>.', '730', '2', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Link - Show?', 'MATC_SHOW_LINK', 'true', '<b>If true</b>, a link to the Terms &amp; Conditions will be <b>displayed</b> next to the checkbox.', '730', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Link - Filename', 'MATC_FILENAME', 'conditions.php', 'This is the filename of the terms and conditions. <br><br><b>Example:</b> <i>conditions.php</i>', '730', '4', '', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Link - Parameters', 'MATC_PARAMETERS', '', 'This is the parameters to use together with the filename in the URL. This will need to be used only when certain other contributions is installed. <br><br><b>Example:</b> <i>hello=world&foo=bar</i>', '730', '5', '', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Textarea - Show?', 'MATC_SHOW_TEXTAREA', 'true', '<b>If true</b>, the Terms &amp; Conditions will be displayed in a <b>textarea at the same page</b>.', '730', '6', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Textarea - Languagefile Filename', 'MATC_TEXTAREA_FILENAME', 'conditions.php', 'Pick a languagefile to require. If set to nothing, nothing will be required. <br><br><b>Example:</b> <i>conditions.php</i>', '730', '7', '', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Textarea - Mode (How to get the contents)', 'MATC_TEXTAREA_MODE', 'Returning code', 'Returning code will be "php-evaluated" and should return the text. SQL should be a string and have the text aliased to "thetext".<br><br><b>Default:</b> <i>Returning code</i>', '730', '8', 'tep_cfg_select_option(array(\'Returning code\', \'SQL\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Textarea - Returning Code', 'MATC_TEXTAREA_RETURNING_CODE', 'TEXT_INFORMATION', 'A <b>pice of code which returns</b> the contents of the textarea. This can for example be a definition that you loaded from the languagefile.<br><br><b>Example:</b> <i>TEXT_INFORMATION</i>', '730', '9', '', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Textarea - SQL', 'MATC_TEXTAREA_SQL', '"SELECT products_description AS thetext FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE language_id = ".$languages_id." AND products_id = 1;"', 'SQL should be a string and have the text aliased to "thetext".<br><br><b>Example:</b> <i>"SELECT products_description AS thetext FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE language_id = ".$languages_id." AND products_id = 1;"</i>', '730', '10', '', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Textarea - Use HTML to Plain text convertion tool?', 'MATC_TEXTAREA_HTML_2_PLAIN_TEXT_CONVERT', 'true', '<b>If true</b>, the loaded text will be converted from html <b>to plain text</b>, using this conversion tool: <a href="http://www.chuggnutt.com/html2text.php" style="color:green;">http://www.chuggnutt.com/html2text.php</a>', '730', '11', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Disabled buttonstyle', 'MATC_BUTTONSTYLE', 'transparent', '<b><i>&quot;transparent&quot;</i></b> will work on all servers but <b><i>&quot;gray&quot;</i></b> requires php version >= 5 ', '730', '12', 'tep_cfg_select_option(array(\'transparent\', \'gray\'), ', now());

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function)
 VALUES
   (NULL, 'Database Optimizer ON/OFF', 'DATABASE_OPTIMIZER_ENABLE',         'true', 'Optimize DataBase <br>(true=on false=off)', '779', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL),
   (NULL, 'Optimize Database Period',  'DATABASE_OPTIMIZER_PERIOD',         '4',    'How often the database should be optimized. (<b>Value entered must be in days</b>)', '779', '15', NULL, now(), NULL),
   (NULL, 'Analyze Database Period',   'DATABASE_OPTIMIZER_ANALYZE',        '14',   'How often the database should be analyzed. (<b>Value entered must be in days</b>)', '779', '20', NULL, now(), NULL),
   (NULL, 'Truncate Customers',        'DATABASE_OPTIMIZER_CUSTOMERS',      '20',   'Should older entries in the customers and customers basket tables be removed? Enter the number of days between removals or leave blank for no removal. (<b>Value entered must be in days</b>)', '779', '25', NULL, now(), NULL),
   (NULL, 'Truncate Orders CC Number', 'DATABASE_OPTIMIZER_ORDERS_CC',      '10',   'Should credit card details be removed from the orders table? Enter the number of days between removals or leave blank for no removal. (<b>Value entered must be in days</b>)', '779', '35', NULL, now(), NULL),
   (NULL, 'Truncate Sessions',         'DATABASE_OPTIMIZER_SESSIONS',       '14',   'Should older entries in the sessions table be removed? Enter the number of days between removals or leave blank for no removal. (<b>Value entered must be in days</b>)', '779', '45', NULL, now(), NULL),
   (NULL, 'Truncate User Tracking',    'DATABASE_OPTIMIZER_USER_TRACKING',  '3',   'Should older entries in the user tracking table be removed? Enter the number of days between removals or leave blank for no removal. (<b>Value entered must be in days</b>)', '779', '55', NULL, now(), NULL),
   (NULL, 'Enable Version Checker',    'DATABASE_OPTIMIZER_ENABLE_VERSION_CHECKER', 'false', 'Enables the code that checks if updates are available.', '779', '70', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL)

 ;

INSERT INTO configuration ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
('Invoice Print Shop Logo on Invoice',                 'PDF_INV_SHOW_LOGO',               'true',                  'Should the Sop Logo be printed on the Invoice  ? False = No, True = Yes',                                                                                                             7200, 1,  NULL, now(), NULL,    'tep_cfg_select_option(array(\'true\', \'false\'), ') ,
('Invoice location of the shop logo',                  'PDF_INV_STORE_LOGO',              'store_logo.png', 'Give the location of the ShopeLogo',                                                                                                                                                  7200, 2,  NULL, now(), NULL, NULL),
('Invoice Print Addres Webshop ',                      'PDF_INV_SHOW_ADRESSSHOP',         'true',                  'Print the Addres etc on The Invoice  ? False = No, True = Yes',                                                                                                                       7200, 3,  NULL, now(), NULL,    'tep_cfg_select_option(array(\'true\', \'false\'), ') ,
('Invoice Print Email en WebAddres',                   'PDF_INV_SHOW_MAILWEB',            'true',                  'Print the Email en Webaddres of The Shop on The Invoice ? False = No, True = Yes',                                                                                                    7200, 4,  NULL, now(), NULL,    'tep_cfg_select_option(array(\'true\', \'false\'), ') ,
('Invoice Print Box Send To ',                         'PDF_INV_SHOW_SOLDTO',             'true',                  'Print the Sold To Box on The Invoice  ? False = No, True = Yes',                                                                                                                      7200, 5,  NULL, now(), NULL,    'tep_cfg_select_option(array(\'true\', \'false\'), ') ,
('Invoice Print Box Sold To ',                         'PDF_INV_SHOW_SENDTO',             'true',                  'Print the Send To Box on The Invoice  ? False = No, True = Yes',                                                                                                                      7200, 6,  NULL, now(), NULL,    'tep_cfg_select_option(array(\'true\', \'false\'), ') ,
('Invoice paper Width',                                'PDF_INV_PAPER_WIDTH',             '210',                   'The width on the Invoice in millimeters.',                                                                                                                                            7200, 7,  NULL, now(), NULL, NULL),
('Invoice paper height',                               'PDF_INV_PAPER_HEIGHT',            '297',                   'The Height on the Invoice in millimeters.',                                                                                                                                           7200, 8,  NULL, now(), NULL, NULL),
('Invoice Orientation',                                'PDF_INV_PORTRAIT_LANDSCAPE',      'P',                     'The Orientation on the Invoice. P= protrait L= Landscape',                                                                                                                            7200, 9,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'P\', \'L\'), '), 
('Invoice Background Fill Color',                      'PDF_INV_PAGE_FILL_COLOR',         '255,255,255',           'The Color of the background of the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                                             7200, 10,  NULL, now(), NULL, NULL ), 

('Invoice HeaderText Color Box Line',                  'PDF_INV_HEADER_LINE_COLOR',       '0,0,0',                 'The Color of the Border of the Header Text on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                              7200, 20,  NULL, now(), NULL, NULL),
('Invoice HeaderText BackgroundFill Color',            'PDF_INV_HEADER_FILL_COLOR',       '255,255,255',           'The Color of the background of the Text in the Send To Box on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',              7200, 21,  NULL, now(), NULL, NULL),
('Invoice HeaderText Color',                           'PDF_INV_HEADER_TEXT_COLOR',       '0,0,0',                 'The Color of the Text Invoice in the Header on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                             7200, 22,  NULL, now(), NULL, NULL),
('Invoice HeaderText Text Size',                       'PDF_INV_HEADER_TEXT_HEIGHT',      '10',                    'The Text Size of the Text Invoice in the Header on the Invoice',                                                                                                                      7200, 23,  NULL, now(), NULL, NULL),
('Invoice HeaderText Text Effect',                     'PDF_INV_HEADER_TEXT_EFFECT',      'B',                     'The effect of the Text Invoice in the Header B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                  7200, 24,  NULL, now(), NULL, NULL),
('Invoice HeaderText Text Font',                       'PDF_INV_HEADER_TEXT_FONT',        'Helvetica',             'Het font of the Text Invoice in the Header on the Invoice',                                                                                                                           7200, 25,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('Invoice Text Invoice Color',                         'PDF_INV_HEADINVOICE_TEXT_COLOR',  '209,52,0',              'The Color of the Text  INVOICE in the Header on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                            7200, 30,  NULL, now(), NULL, NULL),
('Invoice Text Invoice Text Size',                     'PDF_INV_HEADINVOICE_TEXT_HEIGHT', '15',                    'The Text Size of the Text INVOICE in header on the Invoice',                                                                                                                          7200, 31,  NULL, now(), NULL, NULL),
('Invoice Text Invoice Text Effect',                   'PDF_INV_HEADINVOICE_TEXT_EFFECT', 'BI',                    'The effect of the Text INVOICE in the Header B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                  7200, 32,  NULL, now(), NULL, NULL),
('Invoice Text Invoice Text Font',                     'PDF_INV_HEADINVOICE_TEXT_FONT',   'Helvetica',             'The font of the Text INVOICE in the kop on the Invoice',                                                                                                                              7200, 33,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('Invoice Color Box Line Sold To ',                    'PDF_INV_SOLDTO_LINE_COLOR',       '77,210,255',            'The Color of the Border of the Box Sold To on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                              7200, 40,  NULL, now(), NULL, NULL),
('Invoice Color Background Text Sold To',              'PDF_INV_SOLDTO_FILL_COLOR',       '255,255,255',           'The Color of the Background of the Text in the Box Sold To on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',              7200, 41,  NULL, now(), NULL, NULL),
('Invoice Text Sold To Color',                         'PDF_INV_SOLDTO_TEXT_COLOR',       '0,0,0',                 'The Color of the Text in the Box Sold To on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                                7200, 42,  NULL, now(), NULL, NULL),
('Invoice Text Sold To Text Size',                     'PDF_INV_SOLDTO_TEXT_HEIGHT',      '8',                     'The Text Size of the Text in the Box of Sold To on the Invoice',                                                                                                                      7200, 43,  NULL, now(), NULL, NULL),
('Invoice Text Sold To Text Effect',                   'PDF_INV_SOLDTO_TEXT_EFFECT',      'B',                     'The effect of the Text in the Box Sold To B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                     7200, 44,  NULL, now(), NULL, NULL),
('Invoice Text Sold To Text Font',                     'PDF_INV_SOLDTO_TEXT_FONT',        'Helvetica',             'The font of the Text in the Box of Sold To on the Invoice',                                                                                                                           7200, 45,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('Invoice Color Line Box Send To ',                    'PDF_INV_SENDTO_LINE_COLOR',       '77,210,255',             'The Color of the Border of the The Text of het Send To box on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',              7200, 50,  NULL, now(), NULL, NULL),
('Invoice Color Background Text Send To',              'PDF_INV_SENDTO_FILL_COLOR',       '255,255,255',             'The Color of the Background of the Text in The Box of Send To on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',           7200, 51,  NULL, now(), NULL, NULL),
('Invoice Text Send To Color',                         'PDF_INV_SENDTO_TEXT_COLOR',       '0,0,0',                 'The Color of the Text in The Box of Send To on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                             7200, 52,  NULL, now(), NULL, NULL),
('Invoice Text Send To Text Size',                     'PDF_INV_SENDTO_TEXT_HEIGHT',      '8',                     'The Text Size of the Text in The Box of Send To on the Invoice',                                                                                                                      7200, 53,  NULL, now(), NULL, NULL),
('Invoice Text Send To Text Effect',                   'PDF_INV_SENDTO_TEXT_EFFECT',      'B',                     'The effect of the Text in The The Box of Send To B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                              7200, 54,  NULL, now(), NULL, NULL),
('Invoice Text Send To Text Font',                     'PDF_INV_SENDTO_TEXT_FONT',        'Helvetica',                 'Het font of the Text in The The Box of Send To on the Invoice',                                                                                                                       7200, 55,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('Invoice Invoice Details Color Line Box ',            'PDF_INV_ORDERDETAILS_LINE_COLOR', '77,210,255',           'The Color of the Border of the Box of the Order Details on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                 7200, 60,  NULL, now(), NULL, NULL),
('Invoice Invoice Details Color Background',           'PDF_INV_ORDERDETAILS_FILL_COLOR', '255,255,255',             'The Color of the Background of the Text in the Box of Order Details on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',     7200, 61,  NULL, now(), NULL, NULL),
('Invoice Invoice Details Text Color',                 'PDF_INV_ORDERDETAILS_TEXT_COLOR', '0,0,0',                 'The Color of the Text in The Box of Order Details on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                       7200, 62,  NULL, now(), NULL, NULL),
('Invoice Invoice Details Text Text Size',             'PDF_INV_ORDERDETAILS_TEXT_HEIGHT','8',                     'The Text Size of the Text in the Box of Order Details on the Invoice',                                                                                                                7200, 63,  NULL, now(), NULL, NULL),
('Invoice Invoice Details Text Text Effect',           'PDF_INV_ORDERDETAILS_TEXT_EFFECT','B',                     'The effect of the Text in the Box of Order Details B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                            7200, 64,  NULL, now(), NULL, NULL),
('Invoice Invoice Details Text Text Font',             'PDF_INV_ORDERDETAILS_TEXT_FONT',  'Helvetica',                 'The font of the Text in the Box of Order Details on the Invoice',                                                                                                                     7200, 65,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('Invoice ProductsHeaderText Color Line Box ',         'PDF_INV_TABLEHEADING_LINE_COLOR', '255,255,255',           'The Color of the Border of the Box of HeaderText Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',               7200, 70,  NULL, now(), NULL, NULL),
('Invoice ProductsHeaderText Color Background',        'PDF_INV_TABLEHEADING_FILL_COLOR', '77,110,255',            'The Color of the Background of the Text in the Box HeaderText Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',  7200, 71,  NULL, now(), NULL, NULL),
('Invoice ProductsHeaderText Text Color',              'PDF_INV_TABLEHEADING_TEXT_COLOR', '0,0,0',                 'The Color of the Text in The Box HeaderText Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                    7200, 72,  NULL, now(), NULL, NULL),
('Invoice ProductsHeaderText Text Text Size',          'PDF_INV_TABLEHEADING_TEXT_HEIGHT','8',                     'The Text Size of the Text in The Box HeaderText Products on the Invoice',                                                                                                             7200, 73,  NULL, now(), NULL, NULL),
('Invoice ProductsHeaderText Text Text Effect',        'PDF_INV_TABLEHEADING_TEXT_EFFECT','B',                     'The effect of the Text in the Box HeaderText Products B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                         7200, 74,  NULL, now(), NULL, NULL),
('Invoice ProductsHeaderText Text Text Font',          'PDF_INV_TABLEHEADING_TEXT_FONT',  'Helvetica',             'The font of the Text in the Box HeaderText Products on the Invoice',                                                                                                                  7200, 75,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('Invoice Products Text Color Line Box ',              'PDF_INV_PRODUCTS_LINE_COLOR',     '255,255,255',           'The Color of the Border of the Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                                 7200, 80,  NULL, now(), NULL, NULL),
('Invoice Products Text Color Background',             'PDF_INV_PRODUCTS_FILL_COLOR',     '77,110,255',            'The Color of the Background of the Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                             7200, 81,  NULL, now(), NULL, NULL),
('Invoice Products Text Text Color',                   'PDF_INV_PRODUCTS_TEXT_COLOR',     '0,0,0',                 'The Color of the Text of Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                                       7200, 82,  NULL, now(), NULL, NULL),
('Invoice Products Text Text Text Size',               'PDF_INV_PRODUCTS_TEXT_HEIGHT',    '8',                     'The Text Size of the Text of Products on the Invoice',                                                                                                                                7200, 83,  NULL, now(), NULL, NULL),
('Invoice Products Text Text Text Effect',             'PDF_INV_PRODUCTS_TEXT_EFFECT',    '',                      'The effect of the Text of Products B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                            7200, 84,  NULL, now(), NULL, NULL),
('Invoice Products Text Text Text Font',               'PDF_INV_PRODUCTS_TEXT_FONT',      'Helvetica',                 'The font of the Text of Products on the Invoice',                                                                                                                                     7200, 85,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('Invoice OrderTotal Bedragen Line Box ',              'PDF_INV_ORDERTOTAL1_LINE_COLOR',  '255,255,255',           'The Color of the Border of the Text of OrderTotals on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                      7200, 90,  NULL, now(), NULL, NULL),
('Invoice OrderTotal Bedragen Color Background',       'PDF_INV_ORDERTOTAL1_FILL_COLOR',  '77,110,255',           'The Color of the Background of the Text of OrderTotals on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                  7200, 91,  NULL, now(), NULL, NULL),
('Invoice OrderTotal Bedragen Text Color',             'PDF_INV_ORDERTOTAL1_TEXT_COLOR',  '0,0,0',             'The Color of the Text of OrderTotals on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                                    7200, 92,  NULL, now(), NULL, NULL),
('Invoice OrderTotal Bedragen Text Text Size',         'PDF_INV_ORDERTOTAL1_TEXT_HEIGHT', '10',                    'The Text Size of the Text of OrderTotals on the Invoice',                                                                                                                             7200, 93,  NULL, now(), NULL, NULL),
('Invoice OrderTotal Bedragen Text Text Effect',       'PDF_INV_ORDERTOTAL1_TEXT_EFFECT', '',                      'The effect of the Text of OrderTotals   B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                       7200, 94,  NULL, now(), NULL, NULL),
('Invoice OrderTotal Bedragen Text Text Font',         'PDF_INV_ORDERTOTAL1_TEXT_FONT',   'Helvetica',                 'The font of the Text of OrderTotals on the Invoice',                                                                                                                                  7200, 95,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('Invoice OrderTotal InvoiceTotal Color Line Box ',    'PDF_INV_ORDERTOTAL2_LINE_COLOR',  '229,238,100',           'The Color of the Border of the The Text of OrderTotal InvoiceTotal on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',      7200, 100,  NULL, now(), NULL, NULL),
('Invoice OrderTotal InvoiceTotal Color Background',   'PDF_INV_ORDERTOTAL2_FILL_COLOR',  '255,90,183',            'The Color of the Background of the Text of OrderTotal InvoiceTotal on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',      7200, 101,  NULL, now(), NULL, NULL),
('Invoice OrderTotal InvoiceTotal Text Color',         'PDF_INV_ORDERTOTAL2_TEXT_COLOR',  '0,255,255',             'The Color of the Text OrderTotal InvoiceTotal on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                           7200, 102,  NULL, now(), NULL, NULL),
('Invoice OrderTotal InvoiceTotal Text Text Size',     'PDF_INV_ORDERTOTAL2_TEXT_HEIGHT', '10',                    'The Text Size of the Text OrderTotal InvoiceTotal on the Invoice',                                                                                                                    7200, 103,  NULL, now(), NULL, NULL),
('Invoice OrderTotal InvoiceTotal Text Text Effect',   'PDF_INV_ORDERTOTAL2_TEXT_EFFECT', '',                      'The effect of the Text of OrderTotal InvoiceTotal B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                             7200, 104,  NULL, now(), NULL, NULL),
('Invoice OrderTotal InvoiceTotal Text Text Font',     'PDF_INV_ORDERTOTAL2_TEXT_FONT',   'Helvetica',                    'The font of the Text of OrderTotal InvoiceTotal on the Invoice',                                                                                                                   7200, 105,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('Invoice Footer Color Background',                    'PDF_INV_FOOTER_FILL_COLOR',       '255,255,183',            'The Color of the Background of the Text of Footer on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                       7200, 110,  NULL, now(), NULL, NULL),
('Invoice Footer Color',                               'PDF_INV_FOOTER_TEXT_COLOR',       '77,0,255',             'The Color of the Text in The Footer on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                                     7200, 111,  NULL, now(), NULL, NULL),
('Invoice Footer Text Size',                           'PDF_INV_FOOTER_TEXT_HEIGHT',      '10',                    'The Text Size of the Text in Footer on the Invoice',                                                                                                                                  7200, 112,  NULL, now(), NULL, NULL),
('Invoice Footer Text Effect',                         'PDF_INV_FOOTER_TEXT_EFFECT',      'B',                     'The effect of the Text in The Footer B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                          7200, 113,  NULL, now(), NULL, NULL),
('Invoice Footer Text Font',                           'PDF_INV_FOOTER_TEXT_FONT',        'Helvetica',                 'Het font of the Text in The Footer on the Invoice',                                                                                                                                   7200, 114,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,
('Invoice Footer Text',                                'PDF_INV_FOOTER_TEXT',             'The Text in The Footer on the Invoice.', 'The Text in the Footer on the Invoice. ',                                                                                                                          7200, 115,  '2005-06-10 14:45:05', now(), NULL, NULL) ;

INSERT INTO configuration ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
('PackingSlip Print Shop Logo on PackingSlip',             'PDF_PCKSLP_SHOW_LOGO',               'true',                  'Print the Sop Logo be printed on the PackingSlip  ? False = No, True = Yes',                                                                                                              7200, 301,  NULL, now(), NULL,    'tep_cfg_select_option(array(\'true\', \'false\'), ') ,
('PackingSlip location of the shop logo',                  'PDF_PCKSLP_STORE_LOGO',              'store_logo.png', 'Give the location of the ShopeLogo',                                                                                                                                                      7200, 302,  NULL, now(), NULL, NULL),
('PackingSlip Print Addres Webshop ',                      'PDF_PCKSLP_SHOW_ADRESSSHOP',         'true',                  'Print the Addres etc on The PackingSlip  ? False = No, True = Yes',                                                                                                                       7200, 303,  NULL, now(), NULL,    'tep_cfg_select_option(array(\'true\', \'false\'), ') ,
('PackingSlip Print Email en WebAddres',                   'PDF_PCKSLP_SHOW_MAILWEB',            'true',                  'Print the Email en Webaddres of The Shop on The PackingSlip ? False = No, True = Yes',                                                                                                    7200, 304,  NULL, now(), NULL,    'tep_cfg_select_option(array(\'true\', \'false\'), ') ,
('PackingSlip Print Box Send To ',                         'PDF_PCKSLP_SHOW_SOLDTO',             'true',                  'Print the Sold To Box on The PackingSlip  ? False = No, True = Yes',                                                                                                                      7200, 305,  NULL, now(), NULL,    'tep_cfg_select_option(array(\'true\', \'false\'), ') ,
('PackingSlip Print Box Sold To ',                         'PDF_PCKSLP_SHOW_SENDTO',             'true',                  'Print the Send To Box on The PackingSlip  ? False = No, True = Yes',                                                                                                                      7200, 306,  NULL, now(), NULL,    'tep_cfg_select_option(array(\'true\', \'false\'), ') ,
('PackingSlip paper Width',                                'PDF_PCKSLP_PAPER_WIDTH',             '210',                   'The width on the PackingSlip in millimeters.',                                                                                                                                            7200, 307,  NULL, now(), NULL, NULL),
('PackingSlip paper height',                               'PDF_PCKSLP_PAPER_HEIGHT',            '297',                   'The Height on the PackingSlip in millimeters.',                                                                                                                                           7200, 308,  NULL, now(), NULL, NULL),
('PackingSlip Orientation',                                'PDF_PCKSLP_PORTRAIT_LANDSCAPE',      'P',                     'The Orientation on the PackingSlip. P= protrait L= Landscape',                                                                                                                            7200, 309,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'P\', \'L\'), '), 
('PackingSlip Background Fill Color',                      'PDF_PCKSLP_PAGE_FILL_COLOR',         '255,255,255',           'The Color of the background of the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                                             7200, 310,  NULL, now(), NULL, NULL ), 

('PackingSlip HeaderText Color Box Line',                  'PDF_PCKSLP_HEADER_LINE_COLOR',       '0,0,0',                 'The Color of the Border of the Header Text on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                              7200, 320,  NULL, now(), NULL, NULL),
('PackingSlip HeaderText BackgroundFill Color',            'PDF_PCKSLP_HEADER_FILL_COLOR',       '255,255,255',           'The Color of the background of the Text in the Send To Box on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',              7200, 321,  NULL, now(), NULL, NULL),
('PackingSlip HeaderText Color',                           'PDF_PCKSLP_HEADER_TEXT_COLOR',       '0,0,0',                 'The Color of the Text PackingSlip in the Header on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                         7200, 322,  NULL, now(), NULL, NULL),
('PackingSlip HeaderText Text Size',                       'PDF_PCKSLP_HEADER_TEXT_HEIGHT',      '10',                    'The Text Size of the Text PackingSlip in the Header on the PackingSlip',                                                                                                                  7200, 323,  NULL, now(), NULL, NULL),
('PackingSlip HeaderText Text Effect',                     'PDF_PCKSLP_HEADER_TEXT_EFFECT',      'B',                     'The effect of the Text PackingSlip in the Header B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                  7200, 324,  NULL, now(), NULL, NULL),
('PackingSlip HeaderText Text Font',                       'PDF_PCKSLP_HEADER_TEXT_FONT',        'Helvetica',             'The font of the Text PackingSlip in the Header on the PackingSlip',                                                                                                                       7200, 325,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('PackingSlip Text PackingSlip Color',                     'PDF_PCKSLP_HEADINVOICE_TEXT_COLOR',  '209,52,0',              'The Color of the Text  PackingSlip in the Header on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                        7200, 330,  NULL, now(), NULL, NULL),
('PackingSlip Text PackingSlip Text Size',                 'PDF_PCKSLP_HEADINVOICE_TEXT_HEIGHT', '15',                    'The Text Size of the Text PackingSlip in header on the PackingSlip',                                                                                                                      7200, 331,  NULL, now(), NULL, NULL),
('PackingSlip Text PackingSlip Text Effect',               'PDF_PCKSLP_HEADINVOICE_TEXT_EFFECT', 'BI',                    'The effect of the Text PackingSlip in the Header B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                  7200, 332,  NULL, now(), NULL, NULL),
('PackingSlip Text PackingSlip Text Font',                 'PDF_PCKSLP_HEADINVOICE_TEXT_FONT',   'Helvetica',             'The font of the Text PackingSlip in the kop on the PackingSlip',                                                                                                                          7200, 333,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('PackingSlip Color Line Box Send To ',                    'PDF_PCKSLP_SENDTO_LINE_COLOR',       '77,210,255',            'The Color of the Border of the The Text of het Send To box on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',              7200, 350,  NULL, now(), NULL, NULL),
('PackingSlip Color Background Text Send To',              'PDF_PCKSLP_SENDTO_FILL_COLOR',       '255,255,255',           'The Color of the Background of the Text in The Box of Send To on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',           7200, 351,  NULL, now(), NULL, NULL),
('PackingSlip Text Send To Color',                         'PDF_PCKSLP_SENDTO_TEXT_COLOR',       '0,0,0',                 'The Color of the Text in The Box of Send To on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                             7200, 352,  NULL, now(), NULL, NULL),
('PackingSlip Text Send To Text Size',                     'PDF_PCKSLP_SENDTO_TEXT_HEIGHT',      '8',                     'The Text Size of the Text in The Box of Send To on the PackingSlip',                                                                                                                      7200, 353,  NULL, now(), NULL, NULL),
('PackingSlip Text Send To Text Effect',                   'PDF_PCKSLP_SENDTO_TEXT_EFFECT',      'B',                     'The effect of the Text in The The Box of Send To B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                  7200, 354,  NULL, now(), NULL, NULL),
('PackingSlip Text Send To Text Font',                     'PDF_PCKSLP_SENDTO_TEXT_FONT',        'Helvetica',             'Het font of the Text in The The Box of Send To on the PackingSlip',                                                                                                                       7200, 355,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('PackingSlip PackingSlip Details Color Line Box ',        'PDF_PCKSLP_ORDERDETAILS_LINE_COLOR', '77,210,255',            'The Color of the Border of the Box of the Order Details on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                 7200, 360,  NULL, now(), NULL, NULL),
('PackingSlip PackingSlip Details Color Background',       'PDF_PCKSLP_ORDERDETAILS_FILL_COLOR', '255,255,255',           'The Color of the Background of the Text in the Box of Order Details on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',     7200, 361,  NULL, now(), NULL, NULL),
('PackingSlip PackingSlip Details Text Color',             'PDF_PCKSLP_ORDERDETAILS_TEXT_COLOR', '0,0,0',                 'The Color of the Text in The Box of Order Details on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                       7200, 362,  NULL, now(), NULL, NULL),
('PackingSlip PackingSlip Details Text Text Size',         'PDF_PCKSLP_ORDERDETAILS_TEXT_HEIGHT','8',                     'The Text Size of the Text in the Box of Order Details on the PackingSlip',                                                                                                                7200, 363,  NULL, now(), NULL, NULL),
('PackingSlip PackingSlip Details Text Text Effect',       'PDF_PCKSLP_ORDERDETAILS_TEXT_EFFECT','B',                     'The effect of the Text in the Box of Order Details B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                7200, 364,  NULL, now(), NULL, NULL),
('PackingSlip PackingSlip Details Text Text Font',         'PDF_PCKSLP_ORDERDETAILS_TEXT_FONT',  'Helvetica',                 'The font of the Text in the Box of Order Details on the PackingSlip',                                                                                                                     7200, 365,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('PackingSlip ProductsHeaderText Color Line Box ',         'PDF_PCKSLP_TABLEHEADING_LINE_COLOR', '77,210,255',            'The Color of the Border of the Box of HeaderText Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',               7200, 370,  NULL, now(), NULL, NULL),
('PackingSlip ProductsHeaderText Color Background',        'PDF_PCKSLP_TABLEHEADING_FILL_COLOR', '255,255,255',           'The Color of the Background of the Text in the Box HeaderText Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',  7200, 371,  NULL, now(), NULL, NULL),
('PackingSlip ProductsHeaderText Text Color',              'PDF_PCKSLP_TABLEHEADING_TEXT_COLOR', '0,0,0',                 'The Color of the Text in The Box HeaderText Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                    7200, 372,  NULL, now(), NULL, NULL),
('PackingSlip ProductsHeaderText Text Text Size',          'PDF_PCKSLP_TABLEHEADING_TEXT_HEIGHT','8',                     'The Text Size of the Text in The Box HeaderText Products on the PackingSlip',                                                                                                             7200, 373,  NULL, now(), NULL, NULL),
('PackingSlip ProductsHeaderText Text Text Effect',        'PDF_PCKSLP_TABLEHEADING_TEXT_EFFECT','B',                     'The effect of the Text in the Box HeaderText Products B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                             7200, 374,  NULL, now(), NULL, NULL),
('PackingSlip ProductsHeaderText Text Text Font',          'PDF_PCKSLP_TABLEHEADING_TEXT_FONT',  'Helvetica',             'The font of the Text in the Box HeaderText Products on the PackingSlip',                                                                                                                  7200, 375,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('PackingSlip Products Text Color Line Box ',              'PDF_PCKSLP_PRODUCTS_LINE_COLOR',     '77,210,255',           'The Color of the Border of the Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                                 7200, 380,  NULL, now(), NULL, NULL),
('PackingSlip Products Text Color Background',             'PDF_PCKSLP_PRODUCTS_FILL_COLOR',     '255,255,255',          'The Color of the Background of the Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                             7200, 381,  NULL, now(), NULL, NULL),
('PackingSlip Products Text Text Color',                   'PDF_PCKSLP_PRODUCTS_TEXT_COLOR',     '0,0,0',                'The Color of the Text of Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                                       7200, 382,  NULL, now(), NULL, NULL),
('PackingSlip Products Text Text Text Size',               'PDF_PCKSLP_PRODUCTS_TEXT_HEIGHT',    '8',                    'The Text Size of the Text of Products on the PackingSlip',                                                                                                                                7200, 383,  NULL, now(), NULL, NULL),
('PackingSlip Products Text Text Text Effect',             'PDF_PCKSLP_PRODUCTS_TEXT_EFFECT',    '',                     'The effect of the Text of Products B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                                7200, 384,  NULL, now(), NULL, NULL),
('PackingSlip Products Text Text Text Font',               'PDF_PCKSLP_PRODUCTS_TEXT_FONT',      'Helvetica',            'The font of the Text of Products on the PackingSlip',                                                                                                                                     7200, 385,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,

('PackingSlip Footer Color Background',                    'PDF_PCKSLP_FOOTER_FILL_COLOR',       '255,90,183',            'The Color of the Background of the Text of Footer on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                       7200, 390,  NULL, now(), NULL, NULL),
('PackingSlip Footer Color',                               'PDF_PCKSLP_FOOTER_TEXT_COLOR',       '0,0,0',                 'The Color of the Text in The Footer on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.',                                     7200, 391,  NULL, now(), NULL, NULL),
('PackingSlip Footer Text Size',                           'PDF_PCKSLP_FOOTER_TEXT_HEIGHT',      '10',                    'The Text Size of the Text in Footer on the PackingSlip',                                                                                                                                  7200, 392,  NULL, now(), NULL, NULL),
('PackingSlip Footer Text Effect',                         'PDF_PCKSLP_FOOTER_TEXT_EFFECT',      'B',                     'The effect of the Text in The Footer B= Bold I= Cursief U= Underline. A combination of effects is possible',                                                                              7200, 393,  NULL, now(), NULL, NULL),
('PackingSlip Footer Text Font',                           'PDF_PCKSLP_FOOTER_TEXT_FONT',        'Helvetica',                 'Het font of the Text in The Footer on the PackingSlip',                                                                                                                                   7200, 394,  NULL, now(), NULL, 'tep_cfg_select_option(array(\'Helvetica\', \'Courier\', \'Times\'), ') ,
('PackingSlip Footer Text',                                'PDF_PCKSLP_FOOTER_TEXT',             'The Text in The Footer on the PackingSlip.', 'The Text in the Footer on the PackingSlip. ',                                                                                                                        7200, 395,  '2005-06-10 14:45:05', now(), NULL, NULL) ;

INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
('Activate Dutch TRack and Trace?', 'SYS_TRACK_TRACE_DUTCH', 'false', 'This setting will activate the dutch track and trace funtion in admin orders and in the account order history.', 8000, 10, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, 'tep_cfg_select_option(array(''true'', ''false''), '),
('The URL for the search window', 'SYS_TRACK_TRACE_URLTONR', 'http://www.postnlpakketten.nl/klantenservice/tracktrace/basicsearch.aspx?lang=nl&B=', 'This is the URL to the search window of the dutch track and trace', 8000, 20, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, NULL ),
('The second URL for the search window', 'SYS_TRACK_TRACE_URLTOPC', '&P=', 'This is the second part URL to the search window of the dutch track and trace', 8000, 30, '2011-01-01 20:43:25', '2011-01-01 20:43:25', NULL, NULL ) ;

INSERT INTO configuration ( configuration_title , configuration_key , configuration_value , configuration_description , configuration_group_id , sort_order , last_modified , date_added , use_function , set_function ) VALUES ( 'Max Wish List', 'MAX_DISPLAY_WISHLIST_PRODUCTS', '12', 'How many wish list items to show per page on the main wishlist.php file', '12954', 10, now(), now(), NULL , NULL );
INSERT INTO configuration ( configuration_title , configuration_key , configuration_value , configuration_description , configuration_group_id , sort_order , last_modified , date_added , use_function , set_function ) VALUES ( 'Max Wish List Box', 'MAX_DISPLAY_WISHLIST_BOX', '4', 'How many wish list items to display in the infobox before it changes to a counter', '12954', 20, now(), now(), NULL , NULL );
INSERT INTO configuration ( configuration_title , configuration_key , configuration_value , configuration_description , configuration_group_id , sort_order , last_modified , date_added , use_function , set_function ) VALUES ( 'Display Emails', 'DISPLAY_WISHLIST_EMAILS', '10', 'How many emails to display when the customer emails their wish list link', '12954', 30, now(), now(), NULL , NULL );
INSERT INTO configuration ( configuration_title , configuration_key , configuration_value , configuration_description , configuration_group_id , sort_order , last_modified , date_added , use_function , set_function ) VALUES ( 'Wish List Redirect', 'WISHLIST_REDIRECT', 'No', 'Do you want to redirect back to the product_info.php page when a customer adds a product to their wish list?', '12954', 40, now(), now(), NULL , 'tep_cfg_select_option(array(\'Yes\', \'No\'),' );

# installed Content Modules
INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES
( 'Installed Modules', 'MODULE_CONTENT_INSTALLED', 'contact_us/cm_cu_mail_us;account/cm_account_set_password;checkout_success/cm_cs_redirect_old_order;checkout_success/cm_cs_thank_you;checkout_success/cm_cs_product_notifications;checkout_success/cm_cs_downloads;footer/cm_footer_contact_us;footer/cm_footer_information_links;footer/cm_footer_account;footer/cm_footer_text;footer/cm_footer_popup;footer_suffix/cm_footer_extra_copyright;footer_suffix/cm_footer_extra_icons;header/cm_header_buttons;index_categories/cm_index_cat_images;index_categories/cm_index_cat_description;index_categories/cm_index_cat_new_products;index_categories/cm_index_cat_title;index_categories/cm_index_cat_headertags_social;index_frontpage/cm_index_fp_heading_title;index_frontpage/cm_index_fp_customer_greeting;index_frontpage/cm_index_fp_scroller;index_frontpage/cm_index_fp_text_main;index_frontpage/cm_index_fp_featured;index_frontpage/cm_index_fp_new_products;index_frontpage/cm_index_fp_headertags_social;index_products/cm_index_products_title;index_products/cm_index_products_description;index_products/cm_index_products_manufacturers;index_products/cm_index_products_product_listing;index_products/cm_index_products_headertags_social;login/cm_login_form;login/cm_create_account_link;product_info/cm_pi_product_name_model;product_info/cm_pi_price_box;product_info/cm_pi_product_description;product_info/cm_pi_product_images;product_info/cm_pi_product_attributes;product_info/cm_pi_button_buy;product_info/cm_pi_button_reviews;product_info/cm_pi_product_date_available_on;product_info/cm_pi_product_xsell;product_info/cm_pi_product_currently_viewing;shopping_cart/cm_sc_no_products;shopping_cart/cm_sc_product_listing;shopping_cart/cm_sc_order_subtotal;shopping_cart/cm_sc_remove_all_products;shopping_cart/cm_sc_checkout;shopping_cart/cm_sc_checkout_alt;shopping_cart/cm_sc_stock_notice;shopping_cart/cm_sc_xsell_in_cart', 'This is automatically updated. No need to edit.', 6, 0, NULL, '2014-12-29 20:42:35', NULL, NULL);

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Set Account Password', 'MODULE_CONTENT_ACCOUNT_SET_PASSWORD_STATUS', 'True', 'Do you want to enable the Set Account Password module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Local Passwords', 'MODULE_CONTENT_ACCOUNT_SET_PASSWORD_ALLOW_PASSWORD', 'True', 'Allow local account passwords to be set.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_ACCOUNT_SET_PASSWORD_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Redirect Old Order Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_STATUS', 'True', 'Should customers be redirected when viewing old checkout success orders?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Redirect Minutes', 'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_MINUTES', '60', 'Redirect customers to the My Account page after an order older than this amount is viewed.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Thank You Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_THANK_YOU_STATUS', 'True', 'Should the thank you block be shown on the checkout success page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_THANK_YOU_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Notifications Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_PRODUCT_NOTIFICATIONS_STATUS', 'True', 'Should the product notifications block be shown on the checkout success page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_PRODUCT_NOTIFICATIONS_SORT_ORDER', '2000', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Downloads Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_DOWNLOADS_STATUS', 'True', 'Should ordered product download links be shown on the checkout success page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_DOWNLOADS_SORT_ORDER', '3000', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Login Form Module', 'MODULE_CONTENT_LOGIN_FORM_STATUS', 'True', 'Do you want to enable the login form module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_LOGIN_FORM_CONTENT_WIDTH', 'Half', 'Should the content be shown in a full or half width container?', '6', '1', 'tep_cfg_select_option(array(\'Full\', \'Half\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_LOGIN_FORM_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable New User Module', 'MODULE_CONTENT_CREATE_ACCOUNT_LINK_STATUS', 'True', 'Do you want to enable the new user module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_CREATE_ACCOUNT_LINK_CONTENT_WIDTH', 'Half', 'Should the content be shown in a full or half width container?', '6', '1', 'tep_cfg_select_option(array(\'Full\', \'Half\'), ', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CREATE_ACCOUNT_LINK_SORT_ORDER', '2000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());


INSERT INTO configuration_languages ( languages_id, configuration_title, configuration_description, configuration_group_id, configuration_sort_order) VALUES
(2, 'Winkel Naam', 'De naam van mijn winkel', 1, 1),
(2, 'Winkel Eigenaar', 'De naam van de winkel eigenaar', 1, 2),
(2, 'E-Mail Adres', 'E-mail adres van de winkel eigenaar', 1, 3),
(2, 'E-Mail Adress van', 'Het Email adres welke wordt gebruikt voor het verzenden van e-mails door de webwinkel', 1, 4),
(2, 'Land', 'Het land waar mijn winkel gevestigd is\n\n<strong>Let op: Pas ook de zone van de winkel aan.</strong>', 1, 6),
(2, 'Zone', 'De zone waarin mijn winkel is gevestigd', 1, 7),
(2, 'Verwachtte Sorteer Volgorde', 'De sorteer volgorde die gebruikt wordt in de te verwachten producten box.', 1, 8),
(2, 'Verwacht Sorteer Veld', 'De kolom waarop gesorteerd wordt in de te verwachten producten box.', 1, 9),
(2, 'Schakel om naar de standaard munteenheid van een taal', 'Automatisch omschakelen naar de munteenheid die bij een taal hoort', 1, 10),
(2, 'Verstuur Extra Verkoop Emails Naar', 'Verstuur extra verkoop e-mails naar de volgende e-mail adressen, in het format: Naam 1 <email@adres1>, Naam 2 <email@adres2>', 1, 11),
(2, 'Gebruik Zoekmachine Vriendelijke URL''s', 'Gebruik zoekmachine vriendelijke urls voor alle links binnen de site', 1, 12),
(2, 'Laat Winkelwagen Zien Na Toevoeging', 'Laat de winkelwagen zien wanneer er een product is toegevoegd.', 1, 14),
(2, 'Gasten Toestaan Een Vriend Te Vertellen', 'Sta gasten toe een vriend te vertellen over een product', 1, 15),
(2, 'Standaard Zoek Operator', 'Standaard zoek operators', 1, 17),
(2, 'Winkel Adres Informatie', 'Winkelnaam\r\nAdres\r\nLand\r\nTelefoon\r\n\r\nDit zijn de winkelgegevens zoals die op de e-mail ter bevestiging of printbare bestanden komen', 1, 18),
(2, 'Toon Catgorie Aantallen', 'Laat zien hoeveel producten er in een categorie staan', 1, 19),
(2, 'Belasting Decimalen Aantal', 'Het aantal decimalen wat gebruikt wordt voor de belasting', 1, 20),
(2, 'Toon Prijzen Inclusief Belasting', 'Toon prijzen inclusief belasting (true) of voeg de belasting pas later toe (false)', 1, 21),
(2, 'Standaard BTW tarief', 'Welke BTW tarief is standaard van toepassing bij het toevoegen van een nieuw produkt in het programma CATEGORIES.', 1, 22),
(2, 'Activeer  BreadCrumb Regel', 'Activeer de BreadCrumb regel op het scherm. Hierin wordt het pad geplaatst van de verschillende categorien en producten', 1, 50),
(2, 'Activeer Box Nieuwe Producten', 'Activeer de mogelijkheid om in de hoofdpagina de box Nieuwe Producten voor ?? op het scherm te plaatsen.', 1, 100),
(2, 'Activeer Box Klanten Kochten ook', 'Activeer de mogelijkheid om in de Product Info Pagina de box Deze Klanten Kochten ook op het scherm te plaatsen.', 1, 105),
(2, 'Activeer Banners', 'Activeer de mogelijkheid om Banners in de Webshop op het scherm te plaatsen.', 1, 110),
(2, 'Activeer SuperTracker', 'Activeer de mogelijkheid om Browser Gegevens van bezoekers aan de webshop op te slaanBanners in een bestand om deze later te bekijken via de Supertracker optie.', 1, 120),
(2, 'Winkel Adres ', 'Winkelnaam\r\nAdres\r\nLand\r\n\r\n\r\nDit zijn de winkelgegevens zoals die op de e-mail ter bevestiging of printbare bestanden komen', 1, 25),
(2, 'Winkel Telefoonnummer', 'Winkel Telefoon\r\n\r\nDit is het telefoonnummer van de winkel zoals die op de e-mail ter bevestiging of printbare bestanden komen', 1, 26);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Voornaam', 'Minimum lengte van de voornaam', 2, 1),
( 2, 'Achternaam', 'Minimum lengte van de achternaam', 2, 2),
( 2, 'Geboortedatum', 'Minimum lengte van de geboortedatum', 2, 3),
( 2, 'E-Mail Adres', 'Minimum lengte van het e-mail adres', 2, 4),
( 2, 'Straatnaam', 'Minimum lengte van de straatnaam', 2, 5),
( 2, 'Bedrijf', 'Minimum lengte van de bedrijfsnaam', 2, 6),
( 2, 'Postcode', 'Minimum lengte van de postcode', 2, 7),
( 2, 'Woonplaats', 'Minimum lengte van de woonplaats', 2, 8),
( 2, 'Provincie', 'Minimum lengte van de provincie', 2, 9),
( 2, 'Telefoon Nummer', 'Minimum lengte van het telefoonnummer', 2, 10),
( 2, 'Wachtwoord', 'Minimum lengte van het wachtwoord', 2, 11),
( 2, 'Creditcard Eigenaar Naam', 'Minimum lengte van naam eigenaar creditcard', 2, 12),
( 2, 'Creditcard Nummer', 'Minimum lengte van het nummer van de creditcard', 2, 13),
( 2, 'BeschouwingText', 'Minimum lengte van de tekst van een een beschouwing', 2, 14),
( 2, 'Bestsellers', 'Minimum aantal bestsellers om te tonen', 2, 15),
( 2, 'Ook Gekocht', 'Minimum aantal te tonen producten in de ''Deze Klant Kocht Ook box', 2, 16);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Adres Boek Invoer', 'Maximum aantal adressen dat een klant mag hebben', 3, 1),
( 2, 'Zoek Resultaten', 'Maximum aantal producten in de zoekresultaten', 3, 2),
( 2, 'Pagina Links', 'Aantal links in gebruik voor pagina instellingen', 3, 3),
( 2, 'Aanbieding Producten', 'Maximum aantal producten in de aanbieding', 3, 4),
( 2, 'Nieuwe Producten Module', 'Maximum aantal nieuwe producten om te tonen in een categorie', 3, 5),
( 2, 'Verwachtte Producten', 'Maximum aantal te tonen verwachtte producten', 3, 6),
( 2, 'Fabrikanten Lijst', 'Gebruikt in fabrikanten box; als het aantal fabrikanten meer is dan het aantal hier aangegeven verschijnt een drop-down lijst.', 3, 7),
( 2, 'Fabrikanten Selecteer Grootte', 'Gebruikt in fabrikanten box; Als deze waarde ''1'' is zal de drop-down lijst verschijnen. Anders verschijnt er een lijst met het hier aangegeven aantal rijen.', 3, 8),
( 2, 'Lengte van Fabrikanten Naam', 'Gebruikt in de fabrikanten box; maximum te tonen lengte van een fabrikanten naam', 3, 9),
( 2, 'Volgende Beschouwingen', 'Maximum aantal te tonen beschouwingen', 3, 10),
( 2, 'Selectie Willekeurige Beschouwingen', 'Aantal beschouwingen waaruit gekozen moet worden voor 1 willekeurige product beschouwing', 3, 11),
( 2, 'Selectie Willekeurige Nieuwe Producten', 'Aantal records om 1 nieuw product uit te halen om te tonen', 3, 12),
( 2, 'Selectie van Aanbiedingen', 'Aantal records om uit te zoeken om 1 aanbieding te tonen', 3, 13),
( 2, 'Categorien Tonen Per Rij', 'Hoeveel categorien per rij', 3, 14),
( 2, 'Nieuwe Producten Lijst', 'Maximum aantal nieuwe producten zichtbaar op de pagina nieuwe producten', 3, 15),
( 2, 'Bestsellers', 'Maximum aantal zichtbare best sellers', 3, 16),
( 2, 'Ook Gekocht', 'Maximum aantal zichtbare producten in de Deze Klant Kocht Ook box', 3, 17),
( 2, 'Maximale Aantal Gerelateerde Verkopen', 'Het maximale aantal producten welke worden getoond in de Gerelateerde Produkten Box in Produkt Info ', 3, 18),
( 2, 'Klanten Verkoop Geschiedenis Box', 'Maximum aantal producten in de Klanten Geschiedenis box', 3, 19),
( 2, 'Verkoop Geschiedenis', 'Maximum aantal verkopen op de pagina Verkoop Geschiedenis', 3, 20),
( 2, 'Product Hoeveelheid in de Winkelwagen', 'Maximum aantal van een product dat in de winkelwagen mag (0 voor geen limiet)', 3, 21),
( 2, 'Aantal Producten in Lijst', 'Maximum aantal van een product welke getoond wordt in de lijst', 3, 22);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Kleine Afbeelding Breedte', 'De pixel breedte van kleine afbeeldingen', 4, 1),
( 2, 'Kleine Afbeelding Hoogte', 'De pixel hoogte van kleine afbeeldingen', 4, 2),
( 2, 'Heading Afbeelding Breedte', 'De pixel breedte van heading afbeeldingen', 4, 3),
( 2, 'Heading Afbeelding Hoogte', 'De pixel hoogte van heading afbeeldingen', 4, 4),
( 2, 'Subcategorie Afbeelding Breedte', 'De pixel breedte van subcategorie afbeeldingen', 4, 5),
( 2, 'Subcategorie Afbeelding Hoogte', 'De pixel hoogte van subcategorie afbeeldingen', 4, 6),
( 2, 'Bereken Afbeelding Grootte', 'De grootte van afbeeldingen berekenen?', 4, 7),
( 2, 'Afbeelding Vereist', 'Inschakelen om mislukte afbeeldingen te tonen. Geschikt voor ontwikkeling.', 4, 8),
( 2, 'Op Voorraad Status Afbeelding Hoogte', 'Afbeelding Op Voorraad Status Hoogte in Produkt Info ', 4, 200),
( 2, 'Op Voorraad Status Afbeelding Breedte', 'Afbeelding Op Voorraad Status Breedte in Produkt Info ', 4, 210),
( 2, 'Geen Voorraad Status Afbeelding Hoogte', 'Afbeelding Niet Op Voorraad Status Hoogte in Produkt Info ', 4, 240),
( 2, 'Geen Voorraad Status Afbeelding Breedte', 'Afbeelding Niet Op Voorraad Status Breedte in Produkt Info ', 4, 250),
( 2, 'Product Listing Afbeelding Breedte', 'Product Lijst Afbeelding Breedte.', 4, 400),
( 2, 'Product Listing Afbeelding Hoogte', 'Product Lijst Afbeelding Hoogte.', 4, 410),
( 2, 'Product Info Afbeelding Breedte', 'Product Info Afbeelding Breedte.', 4, 450),
( 2, 'Product Info Afbeelding Hoogte', 'Product Info Afbeelding Hoogte.', 4, 460),
( 2, 'Verwijder de Thumbnails Afbeeldingen', 'Verwijder alle Thumbnails Afbeeldingen van de server', 4, 500 ) ;

INSERT INTO configuration_languages ( languages_id, configuration_title, configuration_description, configuration_group_id, configuration_sort_order) VALUES
( 2, 'Hoogte Afbeeldingen Mobiele Winkel',              'De hoogte van de Afbeeldingen in pixels welke worden gebruikt in de mobiele Winkel.', 4, 300 ),
( 2, 'Breedte Afbeeldingen Mobiele Winkel',             'De breedte van de Afbeeldingen in pixels welke worden gebruikt in de mobiele Winkel.', 4, 310),
( 2, 'Hoogte Afbeeldingen Categories Mobiele Winkel',   'De hoogte van de Afbeeldingen in pixels welke worden gebruikt van de categories in de mobiele Winkel.', 4, 340),
( 2, 'Breedte Afbeeldingen Categories Mobiele Winkel',  'De breedte van de Afbeeldingen in pixels welke worden gebruikt van de categoreis in de mobiele Winkel.', 4, 350),
( 2, 'Hoogte Afbeelding In Voorraad Mobiele Winkel',    'De hoogte van de Afbeelding in pixels In Voorraad in de mobiele Winkel.', 4, 360),
( 2, 'Breedte Afbeelding Geen Voorraad Mobiele Winkel', 'De breedte van de Afbeelding in pixels Geen Voorraad in de mobiele Winkel.', 4, 370) ;

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Geslacht', 'Toon geslacht bij account klant', 5, 1),
( 2, 'Geboortedatum', 'Toon geboortedatum bij account klant', 5, 2),
( 2, 'Bedrijf', 'Toon bedrijf bij account klant', 5, 3),
( 2, 'Woonwijk', 'Toon woonwijk bij account klant', 5, 4),
( 2, 'Provincie', 'Toon provincie bij account klant', 5, 5);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Land van Herkomst', 'Selecteer het land van herkomst dat gebruikt wordt in verzend voorstellen.', 7, 1),
( 2, 'Postcode', 'Voer de postcode van de winkel in voor de verzend voorstellen.', 7, 2),
( 2, 'Voer het maximale pakket gewicht in dat verzonden wordt', 'Verzendbedrijven hanteren een maximaal gewicht per pakket. Dit is een standaard instelling voor alle verzendbedrijven.', 7, 3),
( 2, 'Tarra Gewicht Verpakking', 'Wat is het gemiddelde gewicht van een lege verpakking?', 7, 4),
( 2, 'Grotere Pakketten - percentage verhoging', 'Voor 10% gebruik 10', 7, 5),
( 2, 'Verstuur naar onbekende zones', 'Verstuur naar onbekende zones', 7, 10 ),
( 2, 'Voeg Verzendig toe', 'Voeg Verzendings optie tijdens Toevoegen Orders', 7, 20 );

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Toon Product Afbeelding', 'Wilt u de product afbeelding tonen?', 8, 10),
( 2, 'Toon Fabrikant Naam', 'Wilt u de naam van de fabrikant tonen?', 8, 20),
( 2, 'Toon Product Model', 'Wilt u de modelnaam van een product tonen?', 8, 30),
( 2, 'Toon Product Naam', 'Wilt u de naam van een product tonen?', 8, 40),
( 2, 'Toon Product Prijs', 'Wilt u de product prijs tonen?', 8, 50),
( 2, 'Toon Product Hoeveelheid', 'Wilt u de hoeveelheid van een product tonen?', 8, 60),
( 2, 'Toon Product Gewicht', 'Wilt u het gewicht van een product tonen?', 8, 70),
( 2, 'Toon Nu Kopen Kolom', 'Wilt u de Nu Kopen kolom tonen?', 8, 80),
( 2, 'Toon Categorie/Fabrikanten Filter (0=uitegeschakeld; 1=ingeschakeld)', 'Wilt u een filter voor categorie/fabrikant tonen?', 8, 200),
( 2, 'Locatie van Vorige/Volgend Navigatie Knoppen (1-boven, 2-onder, 3-beide)', 'Stel de locatie in van de Vorige/Volgende Navigatie knoppen (1-boven, 2-onder, 3-beide)', 8, 210),
( 2, 'Toon Product Sorteer Volgorder', 'Wilt u het nummer van de sorteer volgorde van de artikelen op het scherm plaatsen ? ( indien de waarde 0 is wordt deze kolom niet vertoond )', 8, 90),
( 2, 'Toon Product Omschrijving', 'Wilt u de Omschrijving van de artikelen op het scherm plaatsen ? ( indien de waarde 0 is wordt deze kolom niet vertoond )', 8, 110),
( 2, 'Product Listing Koop Nu / Details Button', 'Koop Nu of Detals Knop op het scherm of eventueel beide', 8, 300),
( 2, 'Product Listing KopTekst', 'Kolom Tekst laten zien in de Product Lijst (false de gebruiker kan de sorteer methode niet wijzigen).', 8, 310),
( 2, 'Product Listing Prijs Tekst Groote', 'Product Lijst Prijs Letter Type Grootte.', 8, 320),
( 2, 'Product Listing Product verkorte Omschrijving', 'Geef de afgekorte product omschrijving weer in de product listing. De verkorte omschrijving zal worden geplaatst onder de product naam.', 8, 400),
( 2, 'Product Listing Product verkorte Omschrijving Lengte', 'Maximale aantal karakters voor de verkorte omschrijving in de product listing.', 8, 410),
( 2, 'Toon Vergelijken Artikelen ', 'Wilt u de mogelijkheid van verschillende producten vergelijken activeren en op het scherm plaatsen ? ( indien de waarde 0 is wordt deze kolom niet vertoond )', 8, 100);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Controleer Voorraad Niveau', 'Controleer of er voldoende voorraad aanwezig is', 9, 1),
( 2, 'Voorraad Bijwerken Bij Verkoop', 'Werk de voorraad bij als er een verkoop is', 9, 2),
( 2, 'Kassa Toestaan', 'Sta toe dat klanten kopen als er geen voorraad aanwezig is', 9, 3),
( 2, 'Markeer Product Niet Op Voorraad', 'Toon dat een product niet op voorraad is', 9, 4),
( 2, 'Voorraad Bestel Niveau', 'Geef aan vanaf welke hoeveelheid de voorraad aangevuld moet worden', 9, 5),
( 2, 'Standaard Op Voorraad Status', 'Welke Voorraad Status voor Op Voorraad is standaard van toepassing bij het toevoegen van een nieuw produkt in het programma CATEGORIES.', 9, 100),
( 2, 'Standaard Niet Op Voorraad Status', 'Welke Voorraad Status voor Niet Voorraad standaard van toepassing is bij het toevoegen van een nieuw produkt in het programma CATEGORIES.', 9, 110),
( 2, 'Activeer Voorraad Status', 'Activeer de mogelijkheid om de voorraadstatus van een produkt in de pagina Produktinfo te laten zien.', 9, 120),
( 2, 'Welke Product Info Attribute Plugin', 'De plugin welke wordt gebruikt om de verschillende opties/attributes op het scherm te plaatsten op de product pagina .', 9, 200),
( 2, 'Activeer GEEN VOORRAAD Opties/Attributes', '<b>Als True:</b> worden de Opties/Attributes welke niet op voorraad zijn ook op het scherm getoond.<br /><br /><b>Als False:</b>  worden de Opties/Attributes welke niet op voorraad zijn <b><em>niet</em></b> op het scherm getoond.</b><br /><br /><b>Standaa', 9, 210),
( 2, 'Markeer Niet op Voorraad Opties/Attributes', 'Controleerd hoe de Niet Op Voorraad markering wordt geplaatst.', 9, 220),
( 2, 'Activeer wel/geen Voorraad Fout Melding', '<b>Als True:</b> Als een Optie/Attribute wordt gekozen moet er dan een foutmelding worden gegeven', 9, 230),
( 2, 'Activeer toevoegen aan winkelwagen geen voorraad', '<b>Als True:</b> een klant kan geen produkt toevoegen welke Optie/Attributes geen voorraaad heeft. een javascript formulier zal geworden getoond', 9, 240),
( 2, 'Activeer Huidige Prijs Pull Downs', '<font color="red"><b>NOTE:</b></font> Deze optie is alleen zinvol als de Optie/Attributes enkele opties zijn.<br /><br /><b>Als True:</b> de Optie/Attributes prijzen zullen worden getoond als totaal prijzene.<br /><br /><b>Standaard is false.</b>', 9, 250),
( 2, 'Activeer Box met Voorraad Gegevens', '<b>Als True:</b> Op de product pagina wordt een box getoond meet de voorraad van de verschillende te selecteren Opties/Attributes. Als een product geen Opties/Attributes heeft met een voorraad optie zal deze box niet vertoond worden.<br /><br /><b>Standda', 9, 260);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Bewaar Verwerkingstijd Pagina', 'Bewaar de tijd in die het duurt om een pagina te verwerken', 10, 1),
( 2, 'Log Bestand', 'Map en bestand voor het logbestand van verwerkingstijd van pagina''s', 10, 2),
( 2, 'Log Datum Format', 'Datum formaat', 10, 3),
( 2, 'Toon verwerkingstijd', 'Toon de verwerkingstijd van de pagina (bewaar verwerkingstijd pagina moet ingeschakeld zijn)', 10, 4),
( 2, 'Bewaar SQL-opdrachten', 'Bewaar SQL-opdrachten in log', 10, 5) ;

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Gebruik Cache', 'Gebruik caching mogelijkheden', 11, 1),
( 2, 'Cache Map', 'De map waar de cache bestanden worden opgeslagen', 11, 2),
( 2, 'Activeer Pagina Cache', 'Activeer pagina cache om de webshop pagina sneller te laden ', 11, 30),
( 2, 'TijdLimiet Cache Bewaren', 'Bepaal de tijd om de pagina te bewaren in een cache bestand (in minuten) ?', 11, 35),
( 2, 'Activeer Debug Modus?', 'Activeer de debug output (located at the footer) ? Dit werk met alle browsers and is NIET geschikt voor actieve webshops!  ', 11, 40),
( 2, 'Disable URL Parameters?', 'In somige gevallen (zoals search engine safe URL''s) of met een groot aantal affiliate zal de de tijd om een pagina te laden langer duren standaard false', 11, 45),
( 2, 'Verwijder Cache Bestanden?', 'Als de instelling true wordt tijdens de volgende aanroep van een pagina alle cache bestanden verwijderd en zal deze instelling weer op flase worden gezet', 11, 50),
( 2, 'Config Cache Update Bestand?', 'Als u een configuration heeft voor deze contribution vul dan het volledige path naar dit bestand in', 11, 60);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Controleer E-Mail Adressen met DNS', 'Controleer een e-mailadres met een DNS server', 12, 4),
( 2, 'Verstuur E-Mails', 'Verstuur e-mails', 12, 5),
( 2, 'Use MIME HTML When Sending Emails', 'Send e-mails in HTML format', 12, 3),
( 2, 'E-Mail Transport Method', 'Defines if this server uses a local connection to sendmail or uses an SMTP connection via TCP/IP. Servers running on Windows and MacOS should change this setting to SMTP.', 12, 1),
( 2, 'E-Mail Linefeeds', 'Defines the character sequence used to separate mail headers.', 12, 2),
( 2, 'Verzend Admin Email NIeuwe Order naar', 'Voer het Email adress in voor het versturen van een Email na het plaatsen van een nieuwe order ( als het Email adres leeg is wordt geen Email naar deit adres verstuurt )', 12, 10),
( 2, 'Voeg PDF factuur toe aan de Email van de nieuwe Order', 'Geef aan of een PDF factuur van de geplaatste order moet worden toegevoegd aan de Email voor het plaatsen van de order die naar de Klasnt wordt verstuurd', 12, 20),
( 2, 'Verzend Admin Email NIeuwe Klant naar', 'Voer het Email adress in voor het versturen van een Email na het aanmaken van een Nieuwe Klant ( als het Email adres leeg is wordt geen Email naar dit Email adres verstuurt )', 12, 30),
( 2, 'Verzend Admin Email Nieuwe Beoordeling naar', 'Voer het Email adress in voor het versturen van een Email na het aanmaken van een Nieuwe Beoordeling ( als het Email adres leeg is wordt geen Email naar dit Email adres verstuurt )', 12, 40);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Inschakelen Download', 'Schakel de product download functies in.', 13, 1),
( 2, 'Download via doorverwijzing', 'Gebruik browser doorverwijzing voor download. Uitschakelen op non-Unix systemen.', 13, 2),
( 2, 'Verloop Vertraging (dagen)', 'Stel het aantal dagen in waarna de downloadlink verloopt. (0 is geen limiet)', 13, 3),
( 2, 'Maximum Aantal downloads', 'Stel het maximum aantal downloads in. (0 is geen download)', 13, 4);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Inschakelen GZip Compressie', 'Schakel HTTP GZip compressie in', 14, 1),
( 2, 'Compressie Niveau', 'Gebruik dit compressie niveau 0-9 (0 = minimum, 9 = maximum).', 14, 2) ;

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Sessie Map', 'Als sessies als bestand worden opgeslagen, stel hier de map in.', 15, 1),
( 2, 'Forceer Cookie Gebruik', 'Forceer het gebruik van sessies als cookies alleen zijn ingeschakeld', 15, 2),
( 2, 'Controleer SSL Sessie ID', 'Valideer de SSL_SESSION_ID op elk veilig HTTPS pagina verzoek', 15, 3),
( 2, 'Controleer User Agent', 'Valideer de client browser user agent bij elk pagina verzoek.', 15, 4),
( 2, 'Controleer IP Adres', 'Valideer het clients IP adres bij elk pagina verzoek', 15, 5),
( 2, 'Voorkom Spider Sessies', 'Voorkom dat bekende spiders een sessie beginnen', 15, 6),
( 2, 'Sessie Overnieuw', 'Doe een sessie overnieuw om zo een nieuwe sessie_ID te maken wanneer een klant opnieuw inlogt of een nieuw account aanmaakt (PHP >=4.1 noodzakelijk).', 15, 7);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Activeer SEO URLs 5?',                                                                               'Activeer Seo Urls 5',                                                                                                                                                                                                                                             16, 01),
( 2, 'Activeer de cache voor SEO URLs 5?',                                                                 'Activeer de cache systeem voor SEO URLs 5',                                                                                                                                                                                                                       16, 02),
( 2, 'Activeer Multi talen support ?',                                                                     'Activeer de multi talen functienaliteit aan of uit',                                                                                                                                                                                                              16, 03),
( 2, 'Genereer W3C valid URLs?',                                                                           'Deze instelling genereeerd W3C valid URLs.',                                                                                                                                                                                                                      16, 04),
( 2, 'Selecteer caching systeem.',                                                                         '<b>Filesystem:</b><br>Geen queries nadat de cache is geladen.<br><b>file:</b><br>1 query nadat de cache is geladen<br><b>Memcached:</b><br>De optie memcached moet actief zijn in apache and php.ini.',                                                           16, 05),
( 2, 'Het aantal dagen voor het bewaren van de cache.',                                                    'Stel het aantal dagen in dat de SEOURL cache wordt bewaard, na het aantal dagen wordt de cache automatische verwijderd.',                                                                                                                                         16, 06),
( 2, 'Kies de Uri formaat ',                                                                               '<b>Kies USU5 URL formaat:</b>',                                                                                                                                                                                                                                   16, 07),
( 2, 'Kies de methode hoe een product link tekst wordt weergegeven',                                       'Product link tekst kan worden weergegeven als:<br /><b>p</b> = product naam<br /><b>c</b> = categorie naam<br /><b>b</b> = fabrikant (merk)<br /><b>m</b> = model<br />e.g. <b>bp</b> (merk/product)',                                                            16, 08),
( 2, 'Verwijder/Vervang Korte Woorden',                                                                    '<b>Deze instelling verwijderd/vervangt korte woorden.</b><br>1 = Verwijder woorden van 1 letter<br>2 = Verwijder woorden van 2 letters of minder<br>3 = Verwijder woorden van 3 letters of minder<br>',                                                           16, 09),
( 2, 'Verwijder alle niet-Alfanumeriek characters?',                                                       'Deze instelling verwijderd alle characters die niet vallen binnen 0-9 en a-z. Als binnen de taal speciale characters zijn dient  de instellnig the character conversion system actief te zijn.',                                                                  16, 11),
( 2, 'Voeg cPath toe aan de product URLs?',                                                                'Deze instelling voegt de cPath naam toe aan het einde van de product URLs (i.e. - some-product-p-1.html?cPath=xx).',                                                                                                                                              16, 12),
( 2, 'Voer de speciale character conversies in.',                                                          'Deze instelling vervangt letters/cijfers.<br><br>Het formaat <b>MOET</b> worden gedefineerd in het volgende formaat: <b>char=>conv,char2=>conv2</b> <br>(Better to use the file based character conversions.See extras/character_conversion_pack/instructions.t', 16, 13),
( 2, 'Instelling van performance rapport op true/false.',                                                  '<span style="color: red;">Performance reporting <b>MAG NIET</b> worden geactiveerd voor een bestaande en actieve webshop/website</span><br>De optie is bedoeld voor programmeer doeleinden. performance en queries worden aan het eind van de webshop getoond.',  16, 14),
( 2, 'Voeg de hoofd categorie toe aan het begin van de categorie URLs',                                    'Deze instelling voegt de naam van de hoofd categoerie toe aan de SEO URL van de actieve categorie.',                                                                                                                                                              16, 10),
( 2, 'Forceer een verwijzing naar www.mijnwebsite.com als de vermelding www.mijnwebsite.com/index.php is', 'Forceer een verwijzing naar www.mijnwebsite.com als de vermelding www.mijnwebsite.com/index.php is',                                                                                                                                                              16, 16),
( 2, 'Verwijder de SEO URLs Cache',                                                                        'Deze instelling verwijderd de cache data van de SEO URL',                                                                                                                                                                                                         16, 17), 
( 2, 'Instelling van de debug reporting op true/false.',                                                   '<span style="color: red;">Debug reporting <b>MAG NIET</b> worden geactiveerd voor een bestaande en actieve webshop/website</span><br>De optie is bedoeld voor programmeer doeleinden.',                                                                           16, 15) ;

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2,'Activeer Progress Bars?', 'Activeer de Progress bar for Text Optie<br>None = Geen Progress Bars<br>Text = Textvelden<br>TextArea = TextAreas <br>Both = 17 Fields and Areas', 17, 4),
( 2, 'Upload Bestand Prefix', 'De prefix welke wordt gebruikt om een unique naam te maken van de upload bestanden.<br>Database = insert id van database<br>Date = De upload Datum<br>Time = De upload Time<br>DateTime = Upload Datum and Tijd', 17, 5),
( 2, 'Verwijder Uploads older dan', 'Uploads in De Tijdelijke folder worden automatisch verwijderd na.<br>Usage: -2 weeks/-5 days/-1 year/etc.', 17, 6),
( 2, 'Upload Directory', 'De directory om de bestanden van de geregistreerde klant(en) op te slaan.', 17, 7),
( 2,'Tijdelijke Directory', 'De directory to store temporary uploads (from guests) which is automatically cleaned.', 17, 8 ),
( 2,'Option Type Image - Afbeeldings Directory', 'What directory to look for Option Type Images.<br>This is where De Images should be stored.', 17, 9),
( 2, 'Option Type Image - Afbeeldings Prefix', 'What prefix to use when looking for Option Type Images.<br>This is what De Images name should begin with.', 17, 10),
( 2, 'Option Type Image - Afbeeldings Name', 'What Option Value item to use as Name for De Option Type Images.<br>When set to "Name", De images should be named: "PREFIX"-"Option value name"-"LanguageID".jpg (Option_RedShirt_1.jpg)<br>When set to "ID", De images should be named: "PREFIX"-"Option value ID"-"LanguageID".jpg (Option_5_1.jpg)', 17, 11),
( 2, 'Option Type Image - Gebruik Taal ID', 'Gebruik TaalID in Option Type Afbeeldings  Naam?<br>This is only needed if different images are used per Language (images with text for example).', 17, 12);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Activeer Versie Controle', 'Activeer de mogelijkheid om te controleren of een nieuwe versie va deze easy maps aanwezig is.', 30, 10),
( 2, 'Activeer een HTML Editor', 'Gebruik de geselecteerde HTML editor . !!! Let Op !!! De geselecteerde editor moet geinstalleerd zijn om actief te worden!!!)', 30, 15);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, '<B>Gesloten wegens onderhoud: ON/OFF</B>', 'Gesloten wegens Onderhoud <br>(true=on false=off)', 32, 1),
( 2, 'Gesloten wegens onderhoud: BestandsNaam', 'Gesloten wegens onderhoud filename Default=down_for_maintenance.php', 32, 2),
( 2, 'Gesloten wegens onderhoud: Verberg Koptekst', 'Gesloten wegens onderhoud: Verberg Koptekst <br>(true=hide false=show)', 32, 3),
( 2, 'Gesloten wegens onderhoud: Verberg Linkse Kolom', 'Gesloten wegens onderhoud: Verberg Linkse Kolom <br>(true=hide false=show)', 32, 4),
( 2, 'Gesloten wegens onderhoud: Verberg Rechtse Kolom', 'Gesloten wegens onderhoud: Verberg Rechtse Kolom <br>(true=hide false=show)r', 32, 5),
( 2, 'Gesloten wegens onderhoud: Verberg Voet Tekst', 'Gesloten wegens onderhoud:Verberg Voet Tekst <br>(true=hide false=show)', 32, 6),
( 2, 'Gesloten wegens onderhoud: Verberg Prijzen', 'Gesloten wegens onderhoud: Verberg Prijzen<br>(true=hide false=show)', 32, 7),
( 2, 'Gesloten wegens onderhoud (maak een uitzondering met dit IP-Address)', 'Wanneer de webshop gesloten is mag deze IP adress ( de administrator ) de webshop benaderen om deze te testen', 32, 8),
( 2, 'Geef een waarschuwing als de webshop gesloten wordt: ON/OFF', 'Geef een waarschuwing aan de aanwezige klanten als de webshop gesloten wordt wegens onderhoud<br>(true=on false=off)<br>If you set the ''Gesloten wegens onderhoud: ON/OFF'' to true this will automaticly be updated to false', 32, 9),
( 2, 'Datum en tijd voor de waarschuwing', 'Geef een datum en tijd in wanneer de webshop wordt gesloten wegens onderhoud', 32, 10),
( 2, 'Geef de tijd weer wanneer het onderhoud plaats vindt', 'Geef de tijd weer wanneer het onderhoud plaats vindt <br>(true=on false=off)<br>', 32, 11),
( 2, 'Geef de periode weer van het onderhoud', 'Geef de periode weer van het onderhoud<br>(true=on false=off)<br>', 32, 12),
( 2, 'Webshop onderhouds tijd periode', 'Webshop onderhouds tijd periode (hh:mm)', 32, 13);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Laat de Betalings Mogelijkheden dropdown zien op het scherm?', 'Gebaseerd op deze instelling zullen de mogelijke betalingsmethode worden geplaats op het scherm true = een dropdown menu false = een invoer veld.', 72, 1),
( 2, 'Gebruik de prijzen van de Contributie Separate Pricing Per Customer?', 'Indien de contributie SPPC is geinstalleerd dan true, anders deze mogelijkheid op false laten staan.', 72, 3),
( 2, 'Gebruik de QTPro contribution?', 'Activeer deze optie alleen als de contributie QTPro geinstalleerd is !!.', 72, 4),
( 2, 'Gebruik AJAX methode voor de update van de gegevens van de order?', 'Deze optie moet op false gezet worden indien u een browser gebruikt waarbij javascript is gedeactiveerd of javascript niet geinstallerd is.', 72, 5),
( 2, 'Selecteer Uw credit card betalingsmethode', 'Order Editor zal hwet credit card invoerveld laten zien als deze optie op true is gezet.', 72, 6),
( 2, 'PDF Factuur Toevoegen Nieuwe Order Email', 'Tijdens het versturen van een nieuwe order Email kan een PDF factuur worden meegestuurd. Deze optie werkt alleen als de contributie PDF Invoice is geinstalleerd.', 72, 15);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Maximum aantal prijs afspraken per artikel', 'Geef het aantal prijsafspraken per artikel op die worden gebruikt bij de artikelen. Als deze instelling op 0 of leeg staat worden er geen prijs afspraken getoond', 73, 1),
( 2, 'Gebruik Prijs dropdown vanaf', 'Stel in na hoeveel Prijs Afpspraken de Dropdown menu gebruikt wordt in plaats van de standaard tekstkolom box', 73, 2);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Winkel logo printen op het Etiket', 'Moet het winkel logo op het eitket geprint worden ? False = nee, True = Ja', 200, 1),
( 2, 'Locatie van het Winkel logo', 'Voer de locatie in van het Winkellogo', 200, 2),
( 2, 'Verzendetiket breedte', 'De breedte van het Verzend etiket in millimeters.', 200, 3),
( 2, 'Verzend etiket hoogte', 'De Hoogte van het verzend etiket in millimeters.', 200, 4),
( 2, 'Achtergrond Kleur', 'De Achtergrond kleur van het etiket. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 200, 5),
( 2, 'Tekst Kleur', 'De tekstkleur van de adresgegevens. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 200, 6),
( 2, 'Tekst letter hoogte', 'De letter hoogte van de adresgegevens', 200, 7),
( 2, 'Voetnoot Tekst', 'De tekst in de voetnoot. ', 200, 8),
( 2, 'Voetnoot Tekst Kleur', 'De kleur van de tekst in de voetnoot.Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 200, 9),
( 2, 'Afdrukstand van het etiket', 'De Afdrukstand van het etiket. P= protrait L= Landscape', 200, 10),
( 2, 'Voetnoot letter hoogte', 'De letter hoogte van de voetnoot', 200, 11);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Het model op het scherm', 'Enable/Disable het model op het scherm', 300, 1),
( 2, 'Wijzig het model', 'Toestaan/Weigeren van het wijzigen van het model ', 300, 2),
( 2, 'Wijzig de naam van de produkten', 'Toestaan/Weigeren van het wijzigen van de naam?', 300, 3),
( 2, 'Wijzig de status van de produkten', 'Toestaan/Weigeren van het wijzigen en laten zien van de status', 300, 4),
( 2, 'Wijzig het gewicht van de produkten', 'Toestaan/Weigeren van het wijzigen en laten zien van het gewicht?', 300, 5),
( 2, 'Wijzig het aantal van de produkten', 'Toestaan/Weigeren van het wijzigen en laten zien van de hoeveelheid', 300, 6),
( 2, 'Wijzig de afbeelding van de prokten', 'Toestaan/Weigeren van het wijzigen en laten zien van de afbeelding', 300, 7),
( 2, 'Wijzig de leverancier van de produkten', 'Toestaan/Weigeren van het wijzigen en laten zien van de leverancier', 300, 8),
( 2, 'Wijzig het BTW tarief van de produkten', 'Toestaan/Weigeren van het wijzigen en laten zien van het BTW tarief', 300, 9),
( 2, 'De prijs incl BTW met mouse op scherm', 'Enable/Disable het op scherm wanneer U met de muis over de prijs beweegt', 300, 10),
( 2, 'De prijs incl BTW bij prijsinvoer excl BTW op scherm', 'Enable/Disable het laten zien van de prijs incl BTW bij invoer van de ecl BTW prijs?', 300, 11),
( 2, 'De link naar op het produkt info pagina op het scherm', 'Enable/Disable de link voor de produkct information pagina op het scherm ', 300, 12),
( 2, 'De link op het scherm voor het wijzigen van het produkt', 'Enable/Disable de link voor de pagina voor het wijzigen van de proukten op het scherm', 300, 13),
( 2, 'De leverancier op het scherm', 'Wilt U de leverancier op het scherm ?', 300, 7),
( 2, 'Het BTW tarief op het scherm', 'Wilt U het BTW tarief op het scherm ?', 300, 8),
( 2, 'Actief of niet actief de commerciele marge', 'Wilt u de commerciele marge geactiveerd of niet ?', 300, 14);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Categorie Icon Modus', 'Kies tussen Off ( Niet Actief)<br />\r\n               Text ( allen tekst),<br /> \r\n			   Image only ( Alleen Afbeelding v/d categorie ) of <br />\r\n			   Image with caption ( Afbeelding v/d categorie met omschrijving) .<br />\r\n  <b>Let op</b>: Image Only mo', 401, 1),
( 2, 'Sub-Category Link Modus', 'Kies tussen Off          ( Niet Actief ), <br />\r\n               Bottom       ( Onderkant ),<br />\r\n			   Right Top    ( RechtsBoven van de Sub-Categorie Link ), <br />\r\n			   Right Middle ( RechtsMidden van de Sub-Categorie Link ), <br />\r\n			   Right Bo', 401, 2),
( 2, 'Maximale Aantal Categorien per regels', 'Vul hier het aantal levels van categorien per regel kunnen worden weergegeven.', 401, 3),
( 2, 'Sub-Category Links Bullet', 'Selecteer de afbeelding die geplaatst wordt voor elke Sub Categorie Link.<br />\r\n  <b>Let op</b>: De standaard afbeelding is "» ", where the whitespace must be entered has entity &nbsp.', 401, 4),
( 2, 'Sub-Category Producten Aantal', 'Define sprintf format to display Sub-Category Products count.<br /><b>Note</b>: Default format is (%s) that causes the products count to be displayed surrounded by parentesis. For more information, read the PHP manual for sprintf function.', 401, 5),
( 2, 'Category Name Weergave', 'Stel hier het formaat in van de Categorien Name Kies uit same   ( gelijke weergave ), <br />\r\n                                                            upper  ( alles hoofdletters ),<br />\r\n															lower  ( alle letters kleine letters ) of <br />', 401, 6);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Google API Key', 'Vul hier de Google Maps API Key in .<br />Als U geen geldige Google Key heeft kunt u zich ier gratis een Google Key ophalen<a target="_blank" class="adminLink" href="http://code.google.com/apis/maps/signup.html"><b>Google Maps API</b></a>', 449, 2),
( 2, 'Webshop / Winkel Adres Straat     ', 'De Naam van de straat van de Webshop /Winkel', 449, 6),
( 2, 'Webshop / Winkel Adres Stad /Dorp ', 'De naam van het Dorp of Stad van de Webshop /Winkel', 449, 7),
( 2, 'Webshop / Winkel Adres Staat      ', 'De Naam van de Staat van de Webshop /Winkel', 449, 8),
( 2, 'Webshop / Winkel Adres Postcode   ', 'De postcode van de Webshop /Winkel', 449, 9),
( 2, 'Webshop / Winkel Adres Land       ', 'De Naam van het land van de Webshop /Winkel', 449, 10),
( 2, 'Google Kaart Kop Tekst              ', 'De Tekst aan de bovenkant van de Google Map ', 449, 15),
( 2, 'Google Kaart Breedte                ', 'De breedte van de Google Landkaart', 449, 16),
( 2, 'Google Kaart Hoogte                 ', 'De Hoogte van de Google Landkaart', 449, 17),
( 2, 'Google Kaart BreedteGraad Positie   ', 'De middenpostie op de Google Landkaart Breedtegraad', 449, 18),
( 2, 'Google Kaart LengteGraad Positie    ', 'De middenpostie op de Google Landkaart LengteGraad', 449, 19),
( 2, 'Google Kaart Zoom Instelling        ', 'Het Inzoomen of Uitzomen met een factor van', 449, 20),
( 2, 'Google Kaart Tekst Details Rekening ', 'De Tekst in gevens van de Orders ', 449, 21),
( 2, 'Google Kaart Tekst voor het laden   ', 'De Tekst tijdens het laden van de Google Kaart', 449, 22),
( 2, 'Google Kaart Bedrag Order           ', 'Het bedrag per Order waarbij de verschillende kleuren moeten worden weergegeven', 449, 23),
( 2, 'Selecteer Order Status', 'Selecteer een Order Status zodat bij deze Order Status het Bestand met de lengte en breedtegraden gebasserd op de Google coordinaten wordt bijgewerkt.', 449, 25);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Automatisch Toevoegen van Nieuwe Paginas','Voegt nieuwe paginas toe als Pagina Control gebruikt wordt<br>(true=on false=off)', 543, 10),
( 2, 'Controleer op ontbrekende Meta Tags ','Controleert bij elk product, categorie of fabrikant of een lege Meta Tag bestaat<br>(true=on false=off)', 543, 20),
( 2, 'Cache bestand leegmaken', 'Verwijder alle Header Tags cache uit de database.', 543, 30),
( 2, 'Gebruik Hogere Categorie Naam Parents in Titel en Tags', 'Voeg alle categorien toe aan het huidige pad ( breadcrum ) (Full), alle categorien als het product in meerdere categorie voorkomt (Duplicate) of alleen de huidige categorie (Standard). Deze instelling wiordt alleen gebruikt als de Categorie checkbox actie', 543, 40),
( 2, 'Kolom Box op het scherm', 'Plaatst de Product box in de kolom als U op de Product pagina bent<br>(true=on false=off)', 543, 50),
( 2, 'Plaats Currently Viewing op het scherm', 'Plaats een link met tekst van het produkt aan het eind van de product info pagina.<br>(true=on false=off)', 543, 60),
( 2, 'Plaatst Help Popups op het Scherm', 'Plaatst de popup berichten van een optie op het scherm<br>(true=on false=off)', 543, 70),
( 2, 'Geen Toegangs Waarschuwing', 'Geeft geen waarschuwing als de toegang voor de includes/header_tags.php file incorrect zijn.<br>(true=on false=off)', 543, 80),
( 2, 'Plaats Pagina Titel', 'Plaats de pagina titlel aan de bovenkant van de paginae<br>(true=on false=off)', 543, 90),
( 2, 'Plaats Silo Links op het scherm', 'Plaats een box op het scherm die informatie geeft over het produkt gebaseerd op de instellingen van Silo Control<br>(true=on false=off)', 543, 100),
( 2, 'Plaatst de Social Bookmark op het scherm', 'Plaats de social bookmarks op het scherm in de producten pagina<br>(true=on false=off)', 543, 110),
( 2, 'Plaats Tag Cloud op het scherm', 'Plaats de Tag Cloud infobox op het scherm<br>(true=on false=off)', 543, 120),
( 2, 'Activeer AutoFill - Listing Tekst', 'Als deze optie actief, zal de tekst automatisch verschijnen op de product listing pagina. Als deze optie niet actief is, alleen de tekst in met tekst ingevoerd wordt getoond.', 543, 130),
( 2, 'Activeer Cache', 'Activeer cache voor Header Tags. De GZip optie zal Gzip gebruiken om sneller de header tags te plaatsen maar kan langzamer zijn als de Header Tags data klein is.', 543, 140),
( 2, 'Activeer een HTML Editor', 'Use an HTML editor, if selected. !!! Warning !!! The selected editor must be installed for it to work!!!)', 543, 150),
( 2, 'Activeer HTML Editor voor Categorie Omschrijving', 'Activeer de geselecteerde HTML editor voor de categorie omschrijving box. De editor moet geinstalleerd zijn.', 543, 160),
( 2, 'Activeer HTML Editor voor Products', 'Activeer de geselecteerde HTML editor voor de products omschrijving box. De editor moet geinstalleerd zijn.', 543, 170),
( 2, 'Activeer de Editor voor Product Listing tekst', 'Activeer de geselecteerde HTML editor voor de Header Tags text op de product listing pagina.  De editor moet geinstalleerd zijn.', 543, 180),
( 2, 'Activeer de Editor voor Product Sub Tekst', 'Activeer de geselecteerde HTML editor voor de sub tekst op de producten paginae.  De editor moet geinstalleerd zijn.', 543, 190),
( 2, 'Activeer Version Checker', 'Activeer de code voor het controelen op een nieuwere versie van header tags.', 543, 200),
( 2, 'Sleutelwoord Dichtheid Range', 'Bepaal de de limieten van de sletelwoord dichtheid die worden gebruikt dynamisch voor de sleutelwoorden. Voer twee getallen gescheiden door een comma', 543, 210),
( 2, 'Domain Naam', 'Voer de domain naam die gebruikt moet worden voor position checking code, zoals www.domain_name.com of domain_name.com/shop.', 543, 220),
( 2, 'Positie Pagina Aantal', 'Voer het aantal paginas in vooet het zoeken van de keyword positions (10 urls per pagina).', 543, 230),
( 2, 'Scheidingteken - Beschrijvingen', 'Bepaal het Scheidingteken voor het gebruik van de beschrijvingen (en titel en logo).', 543, 240),
( 2, 'Scheidingteken - Sleutelwoorden', 'Bepaal het Scheidingteken voor het gebruik van de Sleutelwoorden.', 543, 250),
( 2, 'Zoek Keywords', 'De optie maakt het mogelijk om zoektermen van de webshop op te slaan in een zoek bestand voor later gebruik in een info box.', 543, 260),
( 2, 'Webshop Keywords', 'Deze optie activeerd de mogelijkheid om zoektermen op te slaan die kunne worden gebruikt voor andere onderdelen van Header Tags, zoals de Tag Cloud optie.', 543, 270),
( 2, 'Tag Cloud Kolom Aantal', 'Voer het aantal zoektermen op die per regel getoond worden in de info box Tag Cloud .', 543, 280),
( 2, 'Activeer HTML Editor voor Meta Descriptions', 'Activeer de geselecteerde HTML editor voor de  meta tag omschrijving box. De editor moet geinstalleerd zijn.', 543, 290),
( 2, 'Toon Korte omschrijving', 'Toon de Korte Omschijving van de Categorie op het scherm', 543, 300),
( 2, 'Keyword Highlighter', 'Plaatst elk gevonden keyword opvallend op het scherm', 543, 320),
( 2, 'Toon de Item Naam op de Pagina', 'Als deze instelling true is, wordt de titel van de pagina de naam van het Item (categorie, fabrikant of product). Als deze instelling false is, wordt de Header Tags SEO titel op het scherm getoond.', 543, 340);


INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Moet voorwaarden accepteren aanmaken nieuwe klant', 'Als de optie <b>true</b> is, moet de klant de algemende Voorwaarden accepteren tijdens het aanmeken van de klanten fische.', 730, 1),
( 2, 'Moet voorwaarden accepteren tijdens het plaatsen van een order', 'Als de optie <b>true</b> is , moet de klat de algemende voorwaarden accepteren tijdens het plaatsen van een nieuwe order.', 730, 2),
( 2, 'Link - Op het scherm?', 'Als de optie <b>true</b> is , wordt de de naam van het programma voor de link van de algemene voorwaarden weergegeven.', 730, 3),
( 2, 'Link - Bestandsnaam?', 'Geef de bestandsnaam op voor de algemene voorwaarden. <br><br><b>Voorbeeld:</b> <i>conditions.php</i>', 730, 4),
( 2, 'Link - Parameters', 'Dit is de parameters die worden doorgeven aan het bestand in de URL. Dit wordt gebruikt voor sommige contributions. <br><br><b>Voorbeeld:</b> <i>hello=world&foo=bar</i>', 730, 5),
( 2, 'Textarea - Op het scherm?', 'Als de optie <b>true</b> is, worden de algemene voorwaarden op het scherm getoond.', 730, 6),
( 2, 'Textarea - Welk Taal Bestand', 'Geef een bestand van de gewenste Taal op. Als deze optie leeg is wordt deze niet getoond. <br><br><b>Voorbeeld:</b> <i>conditions.php</i>', 730, 7),
( 2, 'Textarea - Mode (How to get the contents)', 'Returning code will be "php-evaluated" and should return the text. SQL should be a string and have the text aliased to "thetext".<br><br><b>standaard:</b> <i>Returning code</i>', 730, 8),
( 2, 'Textarea - Returning Code', 'A <b>pice of code which returns</b> the contents of the textarea. This can for example be a definition that you loaded from the languagefile.<br><br><b>Example:</b> <i>TEXT_INFORMATION</i>', 730, 9),
( 2, 'Textarea - SQL commando', 'SQL should be a string and have the text aliased to "thetext".<br><br><b>Voorbeeld:</b> <i>"SELECT products_description AS thetext FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE language_id = ".$languages_id." AND products_id = 1;"</i>', 730, 10),
( 2, 'Textarea - Gebruik HTML naar gewone text convertion tool?', 'Als de optie <b>true</b> is , zal de te tonen tekst geconverteerd worden van html <b>naar gewone tekst</b>, gebruik deze conversie programma: <a href="http://www.chuggnutt.com/html2text.php" style="color:green;">http://www.chuggnutt.com/html2text.php</a>', 730, 11),
( 2, 'Disabled buttonstyle', '<b><i>&quot;transparent&quot;</i></b> will work on all servers but <b><i>&quot;gray&quot;</i></b> requires php version >= 5 ', 730, 12);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Database Optimizer ', 'Optimeer DataBase bestanden <br>(true=on false=off)', 779, 1),
( 2, 'Optimize Database Periode', 'Geef het aantal optimeer rondes voor de database . (<b>Het getal geeft het aantal dagen weer</b>)', 779, 15),
( 2, 'Analyze Database Periode', 'Geef aan hoeveel keer de database geanalizeerd moet worden. (<b>Het getal geeft het aantal dagen weer</b>)', 779, 20),
( 2, 'Verwijder Oude Klanten', 'Verwijder de oude gegevens in de data bestanden KLANTEN en KLANTEN WINKELWAGEN ? Voer het aantal dagen welke verwijderd moeten worden of bij een leeg veld worden geen gegevens verwijderd. (<b>Het getal geeft het aantal dagen weer</b>)', 779, 25),
( 2, 'Verwijder Credit Card Info', 'Verwijder de credit card details in de ORDERS database tabel? Voer het aantal dagen in voor verwijdering of bij een leeg veld worden geen gegevens verwijderd. (<b>Het getal geeft het aantal dagen weer</b>)', 779, 35),
( 2, 'Verwijder Sessie info', 'Verijder de gegevens in de SESSIE database tabel ? Voer het aantal dagen in welke de gegevens verwijderd moeten worden of bij een leeg veld worden geen gegevens verwijderd. (<b>Het getal geeft het aantal dagen weer</b>)', 779, 45),
( 2, 'Verwijder Gebruikers Info', 'Verwijder de gegevens in de GEBRUIKERS TRACKING database tabel ? Voer het aantal dagen in welke de gegevens verwijderd moeten worden of bij een leeg veld worden geen gegevens verwijderd. (<b>Het getal geeft het aantal dagen weer</b>)', 779, 55),
( 2, 'Activeer Versie Controle', 'Activeer de mogelijkheid om te controleren op een nieuwe versie', 779, 70);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Factuur Winkel logo printen', 'Moet het winkel logo op de factuur geprint worden ? False = nee, True = Ja', 7200, 1),
( 2, 'Factuur locatie van het Winkel logo', 'Voer de locatie in van het Winkellogo voor de factuur', 7200, 2),
( 2, 'Factuur Adres Webshop Printen ', 'Moet het Adres,Adres van de winkel op de factuur geprint worden ? False = nee, True = Ja', 7200, 3),
( 2, 'Factuur Email en WebAdres printen', 'Moet het Email en Webadres vande winkel op de factuur geprint worden ? False = nee, True = Ja', 7200, 4),
( 2, 'Factuur Box Verzenden Aan ( Verzend Adres ) printen', 'Moet het winkel logo op de factuur geprint worden ? False = nee, True = Ja', 7200, 5),
( 2, 'Factuur Box Verkocht Aan ( FactuurAdres ) printen', 'Moet het winkel logo op de factuur geprint worden ? False = nee, True = Ja', 7200, 6),
( 2, 'Factuur papier breedte', 'De Breedte van de factuur in millimeters.', 7200, 7),
( 2, 'Factuur papier hoogte', 'De Hoogte van de factuur in millimeters.', 7200, 8),
( 2, 'Factuur Afdrukstand van de factuur', 'De Afdrukstand van de factuur. P= protrait L= Landscape', 7200, 9),
( 2, 'Factuur Pagina Achtergrond Kleur', 'De Achtergrond kleur van de de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 10),
( 2, 'Factuur KopTekst Kleur Lijnen Box  ', 'De kleur van de rand om de tekst van het Verzenden Naar box van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 20),
( 2, 'Factuur KopTekst Kleur Ondergrond Tekst', 'De kleur van de achtergrond van de tekst in de Box van Verzenden Naar van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 21),
( 2, 'Factuur KopTekst Kleur', 'De kleur van de tekst FACTUUR in de kop van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 22),
( 2, 'Factuur KopTekst letter hoogte', 'De letter hoogte van de tekst FACTUUR in kop van de factuur', 7200, 23),
( 2, 'Factuur KopTekst letter Effect', 'Het effect van de tekst FACTUUR in de kop B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 24),
( 2, 'Factuur KopTekst letter Type', 'Het lettertype van de tekst FACTUUR in de kop van de factuur', 7200, 25),
( 2, 'Factuur Tekst Factuur Kleur', 'De kleur van de tekst in de kop van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 30),
( 2, 'Factuur Tekst Factuur letter hoogte', 'De letter hoogte van de tekst in kop van de factuur', 7200, 31),
( 2, 'Factuur Tekst Factuur letter Effect', 'Het effect van de tekst in de kop B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 32),
( 2, 'Factuur Tekst Factuur letter Type', 'Het lettertype van de tekst in de kop van de factuur', 7200, 33),
( 2, 'Factuur Kleur Lijnen Box Verkocht Aan ', 'De kleur van de rand om de tekst van het Verkocht Aan box van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 40),
( 2, 'Factuur Kleur Ondergrond Tekst Verkocht Aan', 'De kleur van de achtergrond van de tekst in de Box van Verkocht Aan van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 41),
( 2, 'Factuur Tekst Verkocht Aan Kleur', 'De kleur van de tekst in de Box van Verkocht Aan van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 42),
( 2, 'Factuur Tekst Verkocht Aan letter hoogte', 'De letter hoogte van de tekst in de Box van Verkocht Aan van de factuur', 7200, 43),
( 2, 'Factuur Tekst Verkocht Aan letter Effect', 'Het effect van de tekst in de de Box van Verkocht Aan B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 44),
( 2, 'Factuur Tekst Verkocht Aan letter Type', 'Het lettertype van de tekst in de de Box van Verkocht Aan van de factuur', 7200, 45),
( 2, 'Factuur Kleur Lijnen Box Verzenden Naar ', 'De kleur van de rand om de tekst van het Verzenden Naar box van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 50),
( 2, 'Factuur Kleur Ondergrond Tekst Verzenden Naar', 'De kleur van de achtergrond van de tekst in de Box van Verzenden Naar van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 51),
( 2, 'Factuur Tekst Verzenden Naar Kleur', 'De kleur van de tekst in de Box van Verzenden Naar van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 52),
( 2, 'Factuur Tekst Verzenden Naar letter hoogte', 'De letter hoogte van de tekst in de Box van Verzenden Naar van de factuur', 7200, 53),
( 2, 'Factuur Tekst Verzenden Naar letter Effect', 'Het effect van de tekst in de de Box van Verzenden Naar B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 54),
( 2, 'Factuur Tekst Verzenden Naar letter Type', 'Het lettertype van de tekst in de de Box van Verzenden Naar van de factuur', 7200, 55),
( 2, 'Factuur Factuur Details Lijnen Box ', 'De kleur van de rand om de tekst van het Order Details box van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 60),
( 2, 'Factuur Factuur Details Kleur Ondergrond', 'De kleur van de achtergrond van de tekst in de Box van Order Details van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 61),
( 2, 'Factuur Factuur Details Tekst Kleur', 'De kleur van de tekst in de Box van Order Details van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 62),
( 2, 'Factuur Factuur Details Tekst letter hoogte', 'De letter hoogte van de tekst in de Box van Order Details van de factuur', 7200, 63),
( 2, 'Factuur Factuur Details Tekst letter Effect', 'Het effect van de tekst in de de Box van Order Details B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 64),
( 2, 'Factuur Factuur Details Tekst letter Type', 'Het lettertype van de tekst in de de Box van Order Details van de factuur', 7200, 65),
( 2, 'Factuur Producten Koptekst Lijnen Box ', 'De kleur van de rand om de tekst van KopTekst Producten van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 70),
( 2, 'Factuur Producten KopTekst Kleur Ondergrond', 'De kleur van de achtergrond van de tekst in de Box van KopTekst Producten van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 71),
( 2, 'Factuur Producten KopTekst Tekst Kleur', 'De kleur van de tekst in de Box van KopTekst Producten van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 72),
( 2, 'Factuur Producten KopTekst Tekst letter hoogte', 'De letter hoogte van de tekst in de Box van KopTekst Producten van de factuur', 7200, 73),
( 2, 'Factuur Producten KopTekst Tekst letter Effect', 'Het effect van de tekst in de de Box van KopTekst Producten B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 74),
( 2, 'Factuur Producten KopTekst Tekst letter Type', 'Het lettertype van de tekst in de de Box van KopTekst Producten van de factuur', 7200, 75),
( 2, 'Factuur Producten Tekst Lijnen Box ', 'De kleur van de rand om de tekst van Producten van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 80),
( 2, 'Factuur Producten Tekst Kleur Ondergrond', 'De kleur van de achtergrond van de tekst in de Box van Producten van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 81),
( 2, 'Factuur Producten Tekst Tekst Kleur', 'De kleur van de tekst in de Box van Producten van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 82),
( 2, 'Factuur Producten Tekst Tekst letter hoogte', 'De letter hoogte van de tekst in de Box van Producten van de factuur', 7200, 83),
( 2, 'Factuur Producten Tekst Tekst letter Effect', 'Het effect van de tekst in de de Box van Producten B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 84),
( 2, 'Factuur Producten Tekst Tekst letter Type', 'Het lettertype van de tekst in de de Box van Producten van de factuur', 7200, 85),
( 2, 'Factuur OrderTotaal Bedragen Lijnen Box ', 'De kleur van de rand om de tekst van OrderTotaal Bedragen van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 90),
( 2, 'Factuur OrderTotaal Bedragen Kleur Ondergrond', 'De kleur van de achtergrond van de tekst in de Box van OrderTotaal Bedragen van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 91),
( 2, 'Factuur OrderTotaal Bedragen Tekst Kleur', 'De kleur van de tekst in de Box van OrderTotaal Bedragen van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 92),
( 2, 'Factuur OrderTotaal Bedragen Tekst letter hoogte', 'De letter hoogte van de tekst in de Box van OrderTotaal Bedragen van de factuur', 7200, 93),
( 2, 'Factuur OrderTotaal Bedragen Tekst letter Effect', 'Het effect van de tekst in de de Box van OrderTotaal Bedragen  B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 94),
( 2, 'Factuur OrderTotaal Bedragen Tekst letter Type', 'Het lettertype van de tekst in de de Box van OrderTotaal Bedragen van de factuur', 7200, 95),
( 2, 'Factuur OrderTotaal TotaalBedrag Lijnen Box ', 'De kleur van de rand om de tekst van OrderTotaal TotaalBedrag van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 100),
( 2, 'Factuur OrderTotaal TotaalBedrag Kleur Ondergrond', 'De kleur van de achtergrond van de tekst van OrderTotaal TotaalBedrag van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 101),
( 2, 'Factuur OrderTotaal TotaalBedrag Tekst Kleur', 'De kleur van de tekst in de Box van OrderTotaal TotaalBedrag van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 102),
( 2, 'Factuur OrderTotaal TotaalBedrag Tekst letter hoogte', 'De letter hoogte van de tekst in de Box van OrderTotaal TotaalBedrag van de factuur', 7200, 103),
( 2, 'Factuur OrderTotaal TotaalBedrag Tekst letter Effect', 'Het effect van de tekst in de de Box van OrderTotaal TotaalBedrag B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 104),
( 2, 'Factuur OrderTotaal TotaalBedrag Tekst letter Type', 'Het lettertype van de tekst in de de Box van OrderTotaal TotaalBedrag van de factuur', 7200, 105),
( 2, 'Factuur VoetTekst Kleur Ondergrond', 'De kleur van de achtergrond van de tekst van VoetTekst van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 110),
( 2, 'Factuur VoetTekst Kleur', 'De kleur van de tekst in de kop van de factuur. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 111),
( 2, 'Factuur VoetTekst letter hoogte', 'De letter hoogte van de tekst in kop van de factuur', 7200, 112),
( 2, 'Factuur VoetTekst letter Effect', 'Het effect van de tekst in de kop B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 113),
( 2, 'Factuur VoetTekst letter Type', 'Het lettertype van de tekst in de kop van de factuur', 7200, 114),
( 2, 'Factuur Voetnoot Tekst', 'De tekst in de voetnoot van de factuur. ', 7200, 115);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Bestelbon Winkel logo printen', 'Moet het winkel logo op de Bestelbon geprint worden ? False = nee, True = Ja', 7200, 301),
( 2, 'Bestelbon locatie van het Winkel logo', 'Voer de locatie in van het Winkellogo voor de Bestelbon', 7200, 302),
( 2, 'Bestelbon Adres Webshop Printen ', 'Moet het Adres,Adres van de winkel op de Bestelbon geprint worden ? False = nee, True = Ja', 7200, 303),
( 2, 'Bestelbon Email en WebAdres printen', 'Moet het Email en Webadres vande winkel op de Bestelbon geprint worden ? False = nee, True = Ja', 7200, 304),
( 2, 'Bestelbon Box Verzenden Aan ( Verzend Adres ) printen', 'Moet het winkel logo op de Bestelbon geprint worden ? False = nee, True = Ja', 7200, 305),
( 2, 'Bestelbon Box Verkocht Aan ( BestelbonAdres ) printen', 'Moet het winkel logo op de Bestelbon geprint worden ? False = nee, True = Ja', 7200, 306),
( 2, 'Bestelbon papier breedte', 'De Breedte van de Bestelbon in millimeters.', 7200, 307),
( 2, 'Bestelbon papier hoogte', 'De Hoogte van de Bestelbon in millimeters.', 7200, 308),
( 2, 'Bestelbon Afdrukstand van de Bestelbon', 'De Afdrukstand van de Bestelbon. P= protrait L= Landscape', 7200, 309),
( 2, 'Bestelbon Pagina Achtergrond Kleur', 'De Achtergrond kleur van de de BestelBon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 310),
( 2, 'Bestelbon KopTekst Kleur Lijnen Box  ', 'De kleur van de rand om de tekst van KopTekst box van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 320),
( 2, 'Bestelbon KopTekst Kleur Ondergrond Tekst', 'De kleur van de achtergrond van de tekst in de KopTekst  van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 321),
( 2, 'Bestelbon KopTekst Kleur', 'De kleur van de tekst Bestelbon in de KopTekst van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 322),
( 2, 'Bestelbon KopTekst letter hoogte', 'De letter hoogte van de tekst Bestelbon in KopTekst van de Bestelbon', 7200, 323),
( 2, 'Bestelbon KopTekst letter Effect', 'Het effect van de tekst Bestelbon in de KopTekst B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 324),
( 2, 'Bestelbon KopTekst letter Type', 'Het lettertype van de tekst Bestelbon in de KopTekst van de Bestelbon', 7200, 325),
( 2, 'Bestelbon Tekst Bestelbon Kleur', 'De kleur van de tekst BESTELBON in de KopTekst van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 330),
( 2, 'Bestelbon Tekst Bestelbon letter hoogte', 'De letter hoogte van de tekst BESTELBON in KopTekst van de Bestelbon', 7200, 331),
( 2, 'Bestelbon Tekst Bestelbon letter Effect', 'Het effect van de tekst BESTELBON in de KopTekst B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 332),
( 2, 'Bestelbon Tekst Bestelbon letter Type', 'Het lettertype van de tekst BESTELBON in de KopTekst van de Bestelbon', 7200, 333),
( 2, 'Bestelbon Kleur Lijnen Box Verzenden Naar ', 'De kleur van de rand om de tekst van het Verzenden Naar box van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 350),
( 2, 'Bestelbon Kleur Ondergrond Tekst Verzenden Naar', 'De kleur van de achtergrond van de tekst in de Box van Verzenden Naar van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 351),
( 2, 'Bestelbon Tekst Verzenden Naar Kleur', 'De kleur van de tekst in de Box van Verzenden Naar van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 352),
( 2, 'Bestelbon Tekst Verzenden Naar letter hoogte', 'De letter hoogte van de tekst in de Box van Verzenden Naar van de Bestelbon', 7200, 353),
( 2, 'Bestelbon Tekst Verzenden Naar letter Effect', 'Het effect van de tekst in de de Box van Verzenden Naar B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 354),
( 2, 'Bestelbon Tekst Verzenden Naar letter Type', 'Het lettertype van de tekst in de de Box van Verzenden Naar van de Bestelbon', 7200, 355),
( 2, 'Bestelbon Bestelbon Details Lijnen Box ', 'De kleur van de rand om de tekst van het Order Details box van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 360),
( 2, 'Bestelbon Bestelbon Details Kleur Ondergrond', 'De kleur van de achtergrond van de tekst in de Box van Order Details van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 361),
( 2, 'Bestelbon Bestelbon Details Tekst Kleur', 'De kleur van de tekst in de Box van Order Details van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 362),
( 2, 'Bestelbon Bestelbon Details Tekst letter hoogte', 'De letter hoogte van de tekst in de Box van Order Details van de Bestelbon', 7200, 363),
( 2, 'Bestelbon Bestelbon Details Tekst letter Effect', 'Het effect van de tekst in de de Box van Order Details B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 364),
( 2, 'Bestelbon Bestelbon Details Tekst letter Type', 'Het lettertype van de tekst in de de Box van Order Details van de Bestelbon', 7200, 365),
( 2, 'Bestelbon Producten Koptekst Lijnen Box ', 'De kleur van de rand om de tekst van KopTekst Producten van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 370),
( 2, 'Bestelbon Producten KopTekst Kleur Ondergrond', 'De kleur van de achtergrond van de tekst in de Box van KopTekst Producten van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 371),
( 2, 'Bestelbon Producten KopTekst Tekst Kleur', 'De kleur van de tekst in de Box van KopTekst Producten van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 372),
( 2, 'Bestelbon Producten KopTekst Tekst letter hoogte', 'De letter hoogte van de tekst in de Box van KopTekst Producten van de Bestelbon', 7200, 373),
( 2, 'Bestelbon Producten KopTekst Tekst letter Effect', 'Het effect van de tekst in de de Box van KopTekst Producten B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 374),
( 2, 'Bestelbon Producten KopTekst Tekst letter Type', 'Het lettertype van de tekst in de de Box van KopTekst Producten van de Bestelbon', 7200, 375),
( 2, 'Bestelbon Producten Koptekst Lijnen Box ', 'De kleur van de rand om de tekst van Producten van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 380),
( 2, 'Bestelbon Producten KopTekst Kleur Ondergrond', 'De kleur van de achtergrond van de tekst in de Box van Producten van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 381),
( 2, 'Bestelbon Producten KopTekst Tekst Kleur', 'De kleur van de tekst in de Box van Producten van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 382),
( 2, 'Bestelbon Producten KopTekst Tekst letter hoogte', 'De letter hoogte van de tekst in de Box van Producten van de Bestelbon', 7200, 383),
( 2, 'Bestelbon Producten KopTekst Tekst letter Effect', 'Het effect van de tekst in de de Box van Producten B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 384),
( 2, 'Bestelbon Producten KopTekst Tekst letter Type', 'Het lettertype van de tekst in de de Box van Producten van de Bestelbon', 7200, 385),
( 2, 'Bestelbon VoetTeskt Kleur Ondergrond', 'De kleur van de achtergrond van de tekst in de VoetTekst van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 390),
( 2, 'Bestelbon VoetTekst Kleur', 'De kleur van de tekst in de VoetTekst van de Bestelbon. Voer een met komma gescheiden RGB-Waarde in (Rood,Geel,Blauw).Geldige waarde zijn per kleur 0-255.', 7200, 391),
( 2, 'Bestelbon VoetTekst letter hoogte', 'De letter hoogte van de tekst in VoetTekst van de Bestelbon', 7200, 392),
( 2, 'Bestelbon VoetTekst letter Effect', 'Het effect van de tekst in de VoetTekst B= Bold I= Cursief U= Onderstreept. Een combinatie van effecten is ook mogelijk', 7200, 393),
( 2, 'Bestelbon VoetTekst letter Type', 'Het lettertype van de tekst in de VoetTekst van de Bestelbon', 7200, 394),
( 2, 'Bestelbon Voetnoot Tekst', 'De tekst in de voetnoot van de Bestelbon. ', 7200, 395);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 2, 'Activeer Nederlandse Track en Trace?', 'Deze instelling activeerd de nederlandse track en trace funtie in admin orders en in de klant historie.', 8000, 10),
( 2, 'De URL van de zoek pagina', 'Dit is de URL van de nederlandse track ten trace pagina van de nederlandse post ', 8000, 20),
( 2, 'Het tweede deel van de zoek pagina', 'Dit is het tweede deel van de URL van de nederlandse track ten trace pagina van de nederlandse post ', 8000, 30);

INSERT INTO configuration_languages ( languages_id, configuration_title, configuration_description, configuration_group_id, configuration_sort_order) VALUES
( 2, 'Multi Winkel Website HTTP URL',                                   'De volledige URL naar de website van deze winkel.(bv. http://www.domain.com)', 8600, 10),
( 2, 'Multi Winkel Website SSL URL',                                    'De volledige SSL URL naar de website van deze winkel.(bv. https://www.domain.com)', 8600, 20),
( 2, 'Activeer SSL Winkel',                                             'Activeer SSL links voor de Winkel', 8600, 30),
( 2, 'Multi Winkel Website Absolute Path',                              'Het absolute Path voor deze Winkel (absolute path vereist -- bv. /catalog/)', 8600, 40),
( 2, 'Multi Winkel Website Absolute Bestandsnaam Path',                 'Het volledige asolute Path van de bestandslocatie van de Winkel(absolute path vereist -- bv. /home/user/public_html/catalog/)', 8600, 50),
( 2, 'Multi Winkel Website HTTP URL Afbeeldingen',                      'Het volledige URL naar de website van deze Winkel waar de afbeeldingen zijn opgeslagen (met backslash -- bv. http://www.domain.com/catalog/images/)', 8600, 60),
( 2, 'Multi Winkel Website HTTP URL Talen',                             'Het volledige URL naar de website van deze Winkel waar de bestanden van de verschillende talen zijn opgeslagen (met backslash -- bv. http://www.domain.com/catalog/languages/)', 8600, 70 ),
( 2, 'Multi Winkel Website Absolute Bestandsnaam Path Talen ',          'Het volledige asolute Path van de bestandslocatie van de Talen welke gebruikt worden in de Winkel(absolute path vereist met backslash -- bv. /home/user/public_html/catalog/languages/)', 8600, 80),
( 2, 'Multi Winkel Website Absolute Bestandsnaam Path Afbeeldingen',    'Het volledige asolute Path van de bestandslocatie van de Afbeeldingen welke gebruikt worden in de Winkel(absolute path vereist met backslash -- bv. /home/user/public_html/catalog/images/)', 8600, 90),
( 2, 'Multi Winkel Website Absolute Bestandsnaam Path Modules',         'Het volledige asolute Path van de bestandslocatie van de Modules welke worden gebruikt in de Winkel(absolute path vereist met backslash -- bv. /home/user/public_html/catalog/includes/modules/)', 8600, 100) ;

INSERT INTO configuration_languages ( languages_id, configuration_title, configuration_description, configuration_group_id, configuration_sort_order) VALUES
( 2, 'Max Verlanglijst',         'Hoeveel Verlanglijst items per pagina op de hoofd Verlanglijstscherm ( wishlist.php file)', '12954', 10 ),
( 2, 'Max Verlanglijst Box',     'Hoeveel Verlanglijst items in de infobox tonen voordat de namen wijzigen in een teller', '12954', 20),
( 2, 'Toon Emails',              'Hoeveel emails mogen er geroond worden op het scherm als de klant de Verlanglijst wenst te emailen', '12954', 30 ),
( 2, 'Verlanglijst Redirect',    'Wilt u de klant doorverwijzen naar de product_info.php pagina wanneer deze een product toevoegd aan de Verlanglijst ?', '12954', 40 );

INSERT INTO configuration_languages ( languages_id, configuration_title, configuration_description, configuration_group_id, configuration_sort_order) VALUES
( 3, 'Show Category Counts', 'Count recursively how many products are in each category', 1, 19),
( 3, 'Tax Decimal Places', 'Pad the tax value this amount of decimal places', 1, 20),
( 3, 'Display Prices with Tax', 'Display prices with tax included (true) or add the tax at the end (false)', 1, 21),
( 3, 'Default Search Operator', 'Default search operators', 1, 17),
( 3, 'Store Address and Phone', 'This is the Store Name, Address and Phone used on printable documents and displayed online', 1, 18),
( 3, 'Display Cart After Adding Product', 'Display the shopping cart after adding a product (or return back to their origin)', 1, 14),
( 3, 'Allow Guest To Tell A Friend', 'Allow guests to tell a friend about a product', 1, 15),
( 3, 'Use Search-Engine Safe URLs', 'Use search-engine safe urls for all site links', 1, 12),
( 3, 'Send Extra Order Emails To', 'Send extra order emails to the following email addresses, in this format: Name 1 &lt;email@address1&gt;, Name 2 &lt;email@address2&gt;', 1, 11),
( 3, 'Switch To Default Language Currency', 'Automatically switch to the languages currency when it is changed', 1, 10),
( 3, 'Expected Sort Field', 'The column to sort by in the expected products box.', 1, 9),
( 3, 'Zone', 'The zone my store is located in', 1, 7),
( 3, 'Expected Sort Order', 'This is the sort order used in the expected products box.', 1, 8),
( 3, 'E-Mail From', 'The e-mail address used in (sent) e-mails', 1, 4),
( 3, 'Country', 'The country my store is located in <br /><br /><strong>Note: Please remember to update the store zone.</strong>', 1, 6),
( 3, 'Store Name', 'The name of my store', 1, 1),
( 3, 'Store Owner', 'The name of my store owner', 1, 2),
( 3, 'E-Mail Address', 'The e-mail address of my store owner', 1, 3),
( 3, 'Default Tax Class', 'Sets the default tax class used for new products.', 1, 22),
( 3, 'Activate BreadCrumb ', 'Activate the BreadCrumb row. The breadcrumb row shows the path to the different categories and products', 1, 50),
( 3, 'Use Box New Products', 'Use the box New Products in the front Page and Page listings.', 1, 100),
( 3, 'Use Box Customers Also Purchased', 'Use the box Customers Also Purchased in the Product Info Page.', 1, 105),
( 3, 'Use Banners', 'Use Banners in the Webshop.', 1, 110),
( 3, 'Use Supertracker', 'Use the possibility to store the browser history of all users/visitors of the webshop.', 1, 120),
( 3, 'Store Address', 'This is the Store Name, Address and used on printable documents and displayed online', 1, 25),
( 3, 'Store Phone', 'This is the Store Phone used on printable documents and displayed online', 1, 26) ;

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'First Name', 'Minimum length of first name', 2, 1),
( 3, 'Last Name', 'Minimum length of last name', 2, 2),
( 3, 'Date of Birth', 'Minimum length of date of birth', 2, 3),
( 3, 'E-Mail Address', 'Minimum length of e-mail address', 2, 4),
( 3, 'Street Address', 'Minimum length of street address', 2, 5),
( 3, 'Company', 'Minimum length of company name', 2, 6),
( 3, 'Post Code', 'Minimum length of post code', 2, 7),
( 3, 'City', 'Minimum length of city', 2, 8),
( 3, 'State', 'Minimum length of state', 2, 9),
( 3, 'Telephone Number', 'Minimum length of telephone number', 2, 10),
( 3, 'Password', 'Minimum length of password', 2, 11),
( 3, 'Credit Card Owner Name', 'Minimum length of credit card owner name', 2, 12),
( 3, 'Credit Card Number', 'Minimum length of credit card number', 2, 13),
( 3, 'Review Text', 'Minimum length of review text', 2, 14),
( 3, 'Best Sellers', 'Minimum number of best sellers to display', 2, 15),
( 3, 'Also Purchased', 'Minimum number of products to display in the ''This Customer Also Purchased'' box', 2, 16);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Address Book Entries', 'Maximum address book entries a customer is allowed to have', 3, 1),
( 3, 'Search Results', 'Amount of products to list', 3, 2),
( 3, 'Page Links', 'Number of ''number'' links use for page-sets', 3, 3),
( 3, 'Special Products', 'Maximum number of products on special to display', 3, 4),
( 3, 'New Products Module', 'Maximum number of new products to display in a category', 3, 5),
( 3, 'Products Expected', 'Maximum number of products expected to display', 3, 6),
( 3, 'Manufacturers List', 'Used in manufacturers box; when the number of manufacturers exceeds this number, a drop-down list will be displayed instead of the default list', 3, 7),
( 3, 'Manufacturers Select Size', 'Used in manufacturers box; when this value is ''1'' the classic drop-down list will be used for the manufacturers box. Otherwise, a list-box with the specified number of rows will be displayed.', 3, 8),
( 3, 'Length of Manufacturers Name', 'Used in manufacturers box; maximum length of manufacturers name to display', 3, 9),
( 3, 'New Reviews', 'Maximum number of new reviews to display', 3, 10),
( 3, 'Selection of Random Reviews', 'How many records to select from to choose one random product review', 3, 11),
( 3, 'Selection of Random New Products', 'How many records to select from to choose one random new product to display', 3, 12),
( 3, 'Selection of Products on Special', 'How many records to select from to choose one random product special to display', 3, 13),
( 3, 'Categories To List Per Row', 'How many categories to list per row', 3, 14),
( 3, 'New Products Listing', 'Maximum number of new products to display in new products page', 3, 15),
( 3, 'Best Sellers', 'Maximum number of best sellers to display', 3, 16),
( 3, 'Also Purchased', 'Maximum number of products to display in the ''This Customer Also Purchased'' box', 3, 17),
( 3, 'Cross Sell', 'Maximum number of products to display in the ''Cross Sell'' box', 3, 18),
( 3, 'Customer Order History Box', 'Maximum number of products to display in the customer order history box', 3, 19),
( 3, 'Order History', 'Maximum number of orders to display in the order history page', 3, 20),
( 3, 'Product Quantities In Shopping Cart', 'Maximum number of product quantities that can be added to the shopping cart (0 for no limit)', 3, 21),
( 3, 'Products in Product List', 'Maximum product per pages shown in the product list', 3, 22);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Small Image Width', 'The pixel width of small images', 4, 1),
( 3, 'Small Image Height', 'The pixel height of small images', 4, 2),
( 3, 'Heading Image Width', 'The pixel width of heading images', 4, 3),
( 3, 'Heading Image Height', 'The pixel height of heading images', 4, 4),
( 3, 'Subcategory Image Width', 'The pixel width of subcategory images', 4, 5),
( 3, 'Subcategory Image Height', 'The pixel height of subcategory images', 4, 6),
( 3, 'Calculate Image Size', 'Calculate the size of images?', 4, 7),
( 3, 'Image Required', 'Enable to display broken images. Good for development.', 4, 8),
( 3, 'In Stock Image Height', 'Image In Stock Height in Product Info ', 4, 200),
( 3, 'In Stock Image Width', 'Image In Stock Width in Product Info ', 4, 210),
( 3, 'No Stock Image Height', 'Image No Stock Height in Product Info ', 4, 240),
( 3, 'No Stock Image Width', 'Image No Stock Width in Product Info ', 4, 250),
( 3, 'Product Listing Image Width', 'Product Listing Image Width.', 4, 400),
( 3, 'Product Listing Image Height', 'Product Listing Image Height.', 4, 410),
( 3, 'Product Info Image Width', 'Product Info Image Width.', 4, 450),
( 3, 'Product Info Image Height', 'Product Info Image Height.', 4, 460),
( 3, 'Clear Cache Thumbnails Images', 'Remove all Thumbnails Images from the server.', 4, 500 );

INSERT INTO configuration_languages ( languages_id, configuration_title, configuration_description, configuration_group_id, configuration_sort_order) VALUES
( 3, 'Height Images Mobile Shop',                      'The Height of the Images in pixels used in the Mobile Shop.', 4, 300),
( 3, 'Width Images Mobile Shop',                       'The Width of the Images in pixels used in the Mobile Shop.', 4, 310),
( 3, 'Height Images Categories Mobile Shop',           'The Height of the Images in pixels used for the categories in the Mobile Shop.', 4, 340),
( 3, 'Width Images Categories Mobile Shop',            'The Width of the Images in pixels used for the categoreis in the Mobile Shop.', 4, 350),
( 3, 'Height Image In Stock Mobile Shop',              'The Height of the Image in pixels used for In Stock in the Mobile Shop.', 4, 360),
( 3, 'Width Image No Stock Mobile Shop',               'The Width of the Image in pixels used for No Stock in the Mobile Shop.', 4, 370) ;

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Gender', 'Display gender in the customers account', 5, 1),
( 3, 'Date of Birth', 'Display date of birth in the customers account', 5, 2),
( 3, 'Company', 'Display company in the customers account', 5, 3),
( 3, 'Suburb', 'Display suburb in the customers account', 5, 4),
( 3, 'State', 'Display state in the customers account', 5, 5);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Country of Origin', 'Select the country of origin to be used in shipping quotes.', 7, 1),
( 3, 'Postal Code', 'Enter the Postal Code (ZIP) of the Store to be used in shipping quotes.', 7, 2),
( 3, 'Enter the Maximum Package Weight you will ship', 'Carriers have a max weight limit for a single package. This is a common one for all.', 7, 3),
( 3, 'Package Tare weight.', 'What is the weight of typical packaging of small to medium packages?', 7, 4),
( 3, 'Larger packages - percentage increase.', 'For 10% enter 10', 7, 5),
( 3, 'Allow Orders Not Matching Defined Shipping Zones ', 'Allow Orders Not Matching Defined Shipping Zones ', 7, 10 ),
( 3, 'Add Shipping with Create Orders  ', 'Add this shipping option when adding an order via create order', 7, 20 );

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Display Product Image', 'Do you want to display the Product Image?', 8, 10),
( 3, 'Display Product Manufaturer Name', 'Do you want to display the Product Manufacturer Name?', 8, 20),
( 3, 'Display Product Model', 'Do you want to display the Product Model?', 8, 30),
( 3, 'Display Product Name', 'Do you want to display the Product Name?', 8, 40),
( 3, 'Display Product Price', 'Do you want to display the Product Price', 8, 50),
( 3, 'Display Product Quantity', 'Do you want to display the Product Quantity?', 8, 60),
( 3, 'Display Product Weight', 'Do you want to display the Product Weight?', 8, 70),
( 3, 'Display Buy Now column', 'Do you want to display the Buy Now column?', 8, 80),
( 3, 'Display Category/Manufacturer Filter (0=disable; 1=enable)', 'Do you want to display the Category/Manufacturer Filter?', 8, 200),
( 3, 'Location of Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 8, 210),
( 3, 'Display Product Sort Order', 'Do you want to display the Product Sort Order column?', 8, 90),
( 3, 'Display Product Description', 'Do you want to display the Product Description column?', 8, 110),
( 3, 'Product Listing Buy Now / Details Button', 'Display a &lsquo;Buy Now&rsquo; or &lsquo;Details&rsquo; Button', 8, 300),
( 3, 'Product Listing Headings', 'Show Listing Column Headings (false prevents user sorting listing).', 8, 310),
( 3, 'Product Listing Price Size', 'Product Listing Price Font Size.', 8, 320),
( 3, 'Display Product Truncated Description', 'Include truncated product description in the product listing. The product \ndescription will be included under the product name, so, that must be included in the product listing too.', 8, 400),
( 3, 'Product Truncated Description Length', 'Maximum number of characters for the product truncated description in the product listing.', 8, 410),
( 3, 'Activate Compare Products', 'Activate Compare Products in product listing and place this on the screen ? ( if this is 0 this coloumn will not been show )', 8, 100);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Check stock level', 'Check to see if sufficent stock is available', 9, 1),
( 3, 'Subtract stock', 'Subtract product in stock by product orders', 9, 2),
( 3, 'Allow Checkout', 'Allow customer to checkout even if there is insufficient stock', 9, 3),
( 3, 'Mark product out of stock', 'Display something on screen so customer can see which product has insufficient stock', 9, 4),
( 3, 'Stock Re-order level', 'Define when stock needs to be re-ordered', 9, 5),
( 3, 'Standard In Stock Status', 'Which Stock Status should be used standard for In Stock for a new product in the program CATEGORIES.', 9, 100),
( 3, 'Standard No Voorraad Status', 'Which Stock Status should be used standard for Out of Stock for a new product in the program CATEGORIES.', 9, 110),
( 3, 'Use Stock Status', 'Use Stock Status on the Produktinfo page.', 9, 120),
( 3, 'Product Info Attribute Display Plugin', 'The plugin used for displaying attributes on the product information page.', 9, 200),
( 3, 'Show Out of Stock Attributes', '<b>If True:</b> Attributes that are out of stock will be displayed.<br /><br /><b>If False:</b> Attributes that are out of stock will <b><em>not</em></b> be displayed.</b><br /><br /><b>Default is True.</b>', 9, 210),
( 3, 'Mark Out of Stock Attributes', 'Controls how out of stock attributes are marked as out of stock.', 9, 220),
( 3, 'Display Out of Stock Message Line', '<b>If True:</b> If an out of stock attribute combination is selected by the customer, a message line informing on this will displayed.', 9, 230),
( 3, 'Prevent Adding Out of Stock to Cart', '<b>If True:</b> Customer will not be able to ad a product with an out of stock attribute combination to the cart. A javascript form will be displayed.', 9, 240),
( 3, 'Use Actual Price Pull Downs', '<font color="red"><b>NOTE:</b></font> This can only be used with a satisfying result if you have only one option per product.<br /><br /><b>If True:</b> Option prices will displayed as a final product price.<br /><br /><b>Default is false.</b>', 9, 250),
( 3, 'Display table with stock information', '<b>If True:</b> A table with information on whats on stock will be displayed to the customer. If product doesn''t have any attributes with tracked stock; the table won''t be displayed.<br /><br /><b>Default is true.</b>', 9, 260);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Store Page Parse Time', 'Store the time it takes to parse a page', 10, 1),
( 3, 'Log Destination', 'Directory and filename of the page parse time log', 10, 2),
( 3, 'Log Date Format', 'The date format', 10, 3),
( 3, 'Display The Page Parse Time', 'Display the page parse time (store page parse time must be enabled)', 10, 4),
( 3, 'Store Database Queries', 'Store the database queries in the page parse time log', 10, 5);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Use Cache', 'Use caching features', 11, 1),
( 3, 'Cache Directory', 'The directory where the cached files are saved', 11, 2),
( 3, 'Enable Page Cache', 'Enable the page cache features to reduce server load and faster page renders?<br><br>Contribution by: <b>Chemo</b>', 11, 30),
( 3, 'Cache Lifetime', 'How long to cache the pages (in minutes) ?<br><br>Contribution by: <b>Chemo</b>', 11, 35),
( 3, 'Turn on Debug Mode?', 'Turn on the global debug output (located at the footer) ? This affects ALL browsers and is NOT for live shops!  YOu can turn on debug mode JUST for your browser by adding "?debug=1" to your URL.<br><br>Contribution by: <b>Chemo</b>', 11, 40),
( 3, 'Disable URL Parameters?', 'In some cases (such as search engine safe URL''s) or large number of affiliate referrals will cause excessive page writing.<br><br>Contribution by: <b>Chemo</b>', 11, 45),
( 3, 'Delete Cache Files?', 'If set to true the next catalog page request will delete all the cache files and then reset this value to false again.<br><br>Contribution by: <b>Chemo</b>', 11, 50),
( 3, 'Config Cache Update File?', 'If you have a configuration cache contribution enter the FULL path to the update file.<br><br>Contribution by: <b>Chemo</b>', 11, 60);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Verify E-Mail Addresses Through DNS', 'Verify e-mail address through a DNS server', 12, 4),
( 3, 'Send E-Mails', 'Send out e-mails', 12, 5),
( 3, 'Use MIME HTML When Sending Emails', 'Send e-mails in HTML format', 12, 3),
( 3, 'E-Mail Transport Method', 'Defines if this server uses a local connection to sendmail or uses an SMTP connection via TCP/IP. Servers running on Windows and MacOS should change this setting to SMTP.', 12, 1),
( 3, 'E-Mail Linefeeds', 'Defines the character sequence used to separate mail headers.', 12, 2),
( 3, 'Send Admin Email New Order to', 'Enter the Email adress for sending a Email after created New Order by a customer ( if the Email address is empty the Email will not be send )', 12, 10),
( 3, 'Attach PDF invoice Email new Order', 'Enter if there must be a PDF invoice attachted to the email for a new order', 12, 20),
( 3, 'Send Admin Email New Costomer to', 'Enter the Email adress for sending a Email after creation of a New Customer ( if the Email address is empty the Email will not be send )', 12, 30),
( 3, 'Send Admin Email New REview to', 'Enter the Email adress for sending a Email after the creation of a new Review ( if the Email address is empty the Email will not be send )', 12, 40);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Enable download', 'Enable the products download functions.', 13, 1),
( 3, 'Download by redirect', 'Use browser redirection for download. Disable on non-Unix systems.', 13, 2),
( 3, 'Expiry delay (days)', 'Set number of days before the download link expires. 0 means no limit.', 13, 3),
( 3, 'Maximum number of downloads', 'Set the maximum number of downloads. 0 means no download authorized.', 13, 4);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Enable GZip Compression', 'Enable HTTP GZip compression.', 14, 1),
( 3, 'Compression Level', 'Use this compression level 0-9 (0 = minimum, 9 = maximum).', 14, 2) ;

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Session Directory', 'If sessions are file based, store them in this directory.', 15, 1),
( 3, 'Force Cookie Use', 'Force the use of sessions when cookies are only enabled.', 15, 2),
( 3, 'Check SSL Session ID', 'Validate the SSL_SESSION_ID on every secure HTTPS page request.', 15, 3),
( 3, 'Check User Agent', 'Validate the clients browser user agent on every page request.', 15, 4),
( 3, 'Check IP Address', 'Validate the clients IP address on every page request.', 15, 5),
( 3, 'Prevent Spider Sessions', 'Prevent known spiders from starting a session.', 15, 6),
( 3, 'Recreate Session', 'Recreate the session to generate a new session ID when the customer logs on or creates an account (PHP >=4.1 needed).', 15, 7);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Enable SEO URLs 5?', 'Turn Seo Urls 5 on', 16, 1),
( 3, 'Enable the cache?', 'Turn the cache system on', 16, 2),
( 3, 'Enable multi language support?', 'Enable the multi language functionality', 16, 3),
( 3, 'Output W3C valid URLs?', 'This setting will output W3C valid URLs.', 16, 4),
( 3, 'Select your chosen cache system?', 'Choose from the 4 available caching strategies.', 16, 5),
( 3, 'Set the number of days to store the cache.', 'Set the number of days you wish to retain cached data, after this the cache will auto reset.', 16, 6),
( 3, 'Choose the uri format', '<b>Choose USU5 URL format:</b>', 16, 7),
( 3, 'Choose how your product link text is made up', 'Product link text can be made up of:<br /><b>p</b> = product name<br /><b>c</b> = category name<br /><b>b</b> = manufacturer (brand)<br /><b>m</b> = model<br />e.g. <b>bp</b> (brand/product)', 16, 8),
( 3, 'Filter Short Words', '<b>This setting will filter words.</b><br>1 = Remove words of 1 letter<br>2 = Remove words of 2 letters or less<br>3 = Remove words of 3 letters or less<br>', 16, 9),
( 3, 'Add category parent to beginning of category uris?', 'This setting will add the category parent name to the beginning of the category URLs (i.e. - parent-category-c-1.html).', 16, 10),
( 3, 'Remove all non-alphanumeric characters?', 'This will remove all non-letters and non-numbers. If your language has special characters then you will need to use the character conversion system.', 16, 11),
( 3, 'Add cPath to product URLs?', 'This setting will append the cPath to the end of product URLs (i.e. - some-product-p-1.html?cPath=xx).', 16, 12),
( 3, 'Enter special character conversions. <b>(Better to use the file based character conversions)</b>', 'This setting will convert characters.<br><br>The format <b>MUST</b> be in the form: <b>char=>conv,char2=>conv2</b>', 16, 13),
( 3, 'Turn performance reporting on true/false.', '<span style="color: red;">Performance reporting should not be set to ON on a live site</span><br>It is for reporting re: performance and queries and shows at the bottom of your site.', 16, 14),
( 3, 'Turn variable reporting on true/false.', '<span style="color: red;">Variable reporting should not be set to ON on a live site</span><br>It is for reporting the contents of USU classes and shows unformatted at the bottom of your site.', 16, 15),
( 3, 'Force www.mysite.com/ when www.mysite.com/index.php', 'Force a redirect to www.mysite.com/ when www.mysite.com/index.php', 16, 16),
( 3, 'Reset USU5 Cache', 'This will reset the cache data for USU5', 16, 17);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Use Progress Bars?', 'Set to use the Progress bar for Text Options<br>None = No Progress Bars<br>Text = Textfields only<br>TextArea = TextAreas only<br>Both = Both Text Fields and Areas', 17, 4),
( 3, 'Upload File Prefix', 'The prefix that is used to generate unique filenames for uploads.<br>Database = insert id from database<br>Date = the upload Date<br>Time = the upload Time<br>DateTime = Upload Date and Time', 17, 5),
( 3, 'Delete Uploads older than', 'Uploads in the Temporary folder are automatically deleted when older than this setting.<br>Usage: -2 weeks/-5 days/-1 year/etc.', 17, 6),
( 3, 'Upload Directory', 'The directory to store uploads from registered customers.', 17, 7),
( 3, 'Temporary Directory', 'The directory to store temporary uploads (from guests) which is automatically cleaned.', 17, 8),
( 3, 'Option Type Image - Images Directory', 'What directory to look for Option Type Images.<br>This is where the Images should be stored.', 17, 9),
( 3, 'Option Type Image - Images Prefix', 'What prefix to use when looking for Option Type Images.<br>This is what the Image''s name should begin with.', 17, 10),
( 3, 'Option Type Image - Images Name', 'What Option Value item to use as Name for the Option Type Images.<br>When set to "Name", the images should be named: "PREFIX"-"Option value name"-"LanguageID".jpg (Option_RedShirt_1.jpg)<br>When set to "ID", the images should be named: "PREFIX"-"Option va', 17, 11),
( 3, 'Option Type Image - Use Language ID', 'Use language ID in Option Type Images Names?<br>This is only needed if different images are used per Language (images with text for example).', 17, 12);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Enable Version Checker', 'Enables the version checking code to automatically check if an update is available.', 30, 10),
( 3, 'Activeer een HTML Editor', 'Use an HTML editor, if selected. !!! Warning !!! The selected editor must be installed for it to work!!!)', 30, 15);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, '<B>Down for Maintenance: ON/OFF</B>', 'Down for Maintenance <br>(true=on false=off)', 32, 1),
( 3, 'Down for Maintenance: filename', 'Down for Maintenance filename Default=down_for_maintenance.php', 32, 2),
( 3, 'Down for Maintenance: Hide Header', 'Down for Maintenance: Hide Header <br>(true=hide false=show)', 32, 3),
( 3, 'Down for Maintenance: Hide Column Left', 'Down for Maintenance: Hide Column Left <br>(true=hide false=show)', 32, 4),
( 3, 'Down for Maintenance: Hide Column Right', 'Down for Maintenance: Hide Column Right <br>(true=hide false=show)r', 32, 5),
( 3, 'Down for Maintenance: Hide Footer', 'Down for Maintenance: Hide Footer <br>(true=hide false=show)', 32, 6),
( 3, 'Down for Maintenance: Hide Prices', 'Down for Maintenance: Hide Prices <br>(true=hide false=show)', 32, 7),
( 3, 'Down For Maintenance (exclude this IP-Address)', 'This IP Address is able to access the website while it is Down For Maintenance (like webmaster)', 32, 8),
( 3, 'NOTICE PUBLIC Before going Down for Maintenance: ON/OFF', 'Give a WARNING some time before you put your website Down for Maintenance<br>(true=on false=off)<br>If you set the ''Down For Maintenance: ON/OFF'' to true this will automaticly be updated to false', 32, 9),
( 3, 'Date and hours for notice before maintenance', 'Date and hours for notice before maintenance website, enter date and hours for maintenance website', 32, 10),
( 3, 'Display when webmaster has enabled maintenance', 'Display when Webmaster has enabled maintenance <br>(true=on false=off)<br>', 32, 11),
( 3, 'Display website maintenance period', 'Display Website maintenance period <br>(true=on false=off)<br>', 32, 12),
( 3, 'Website maintenance period', 'Enter Website Maintenance period (hh:mm)', 32, 13);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
(3, 'Display the Payment Method dropdown?', 'Based on this selection Order Editor will display the payment method as a dropdown menu (true) or as an input field (false).', 72, 1),
(3, 'Use prices from Separate Pricing Per Customer?', 'Leave this set to false unless SPPC is installed.', 72, 3),
(3, 'Use QTPro contribution?', 'Leave this set to false unless you have QTPro Installed.', 72, 4),
( 3, 'Allow the use of AJAX to update order information?', 'This must be set to false if using a browser on which JavaScript is disabled or not available.', 72, 5),
( 3, 'Select your credit card payment method', 'Order Editor will display the credit card fields when this payment method is selected.', 72, 6),
( 3, 'Attach PDF Invoice to New Order Email', 'When you send a new Order Email a PDF Invoice kan be attach to your email. This function only works if the contribution PDF Invoice is installed.', 72, 15);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Maximum number of price break levels', 'Configures the number of price break levels that can be entered on admin side. Levels that are left empty will not be shown to the customer', 73, 1),
( 3, 'Number of price breaks for dropdown', 'Set the number of price breaks at which you want to show a dropdown plus "from Low Price" instead of a table', 73, 2);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Shop Logo Print on Label', 'Print the shoplogo on the label ? False = no, True = Yes', 200, 1),
( 3, 'Location off the ShopLogo', 'Give the location of the ShopeLogo', 200, 2),
( 3, 'ShippingLabel Widht', 'The width of the label in millimeters.', 200, 3),
( 3, 'ShippingLabel Height', 'The height of the label in millimeters.', 200, 4),
( 3, 'Shipping Label Background Color', 'The backgroundcolor of the page. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Values per color from 0-255.', 200, 5),
( 3, 'Text Color', 'The Text color of the page. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Values per color from 0-255.', 200, 6),
( 3, 'Text letter height', 'The height of the text', 200, 7),
( 3, 'Footer Text', 'The footer text.', 200, 8),
( 3, 'Footer Text Color', 'The backgroundcolor of the page. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Values per color from 0-255.', 200, 9),
( 3, 'Label Orientation', 'The Label oriontation. P= protrait L= Landscape', 200, 10),
( 3, 'Footer Text Height', 'The height of the footer text', 200, 11);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Display the model', 'Enable/Disable the model displaying', 300, 1),
( 3, 'Modify the model', 'Allow/Disallow the model modification', 300, 2),
( 3, 'Modify the name of the products', 'Allow/Disallow the name modification?', 300, 3),
( 3, 'Modify the status of the products', 'Allow/Disallow the Statut displaying and modification', 300, 4),
( 3, 'Modify the weight of the products', 'Allow/Disallow the Weight displaying and modification?', 300, 5),
( 3, 'Modify the quantity of the products', 'Allow/Disallow the quantity displaying and modification', 300, 6),
( 3, 'Modify the image of the products', 'Allow/Disallow the Image displaying and modification', 300, 7),
( 3, 'Modify the manufacturer of the products', 'Allow/Disallow the Manufacturer displaying and modification', 300, 8),
( 3, 'Modify the class of tax of the products', 'Allow/Disallow the Class of tax displaying and modification', 300, 9),
( 3, 'Display price with all included of tax', 'Enable/Disable the displaying of the Price with all tax included when your mouse is over a product', 300, 10),
( 3, 'Display price with all included of tax', 'Enable/Disable the displaying of the Price with all tax included when you are typing the price?', 300, 11),
( 3, 'Display the link towards the products information page', 'Enable/Disable the display of the link towards the products information page ', 300, 12),
( 3, 'Display the link towards the page where you will be able to edit the product', 'Enable/Disable the display of the link towards the page where you will be able to edit the product', 300, 13),
( 3, 'Display the manufacturer', 'Do you want just display the manufacturer ?', 300, 7),
( 3, 'Display the tax', 'Do you want just display the tax ?', 300, 8),
( 3, 'Activate or desactivate the commercial margin', 'Do you want that the commercial margin be activate or not ?', 300, 14);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Category Icon Mode', 'Choose between Disabled, Text and Image without or with Caption for Current Level Categories Icons.<br /><b>Note</b>: Image Only mode causes the category name to be displayed on top of its sub-category links.', 401, 1),
( 3, 'Sub-Category Link Mode', 'Choose between Disabled, Bottom or Right position of Sub-Category Links.', 401, 2),
( 3, 'Max number of category Icons per Row', 'Choose how many Current Level Categories to display per row.', 401, 3),
( 3, 'Sub-Category Links Bullet', 'Select Bullet character to prefix each Sub Category Link.<br /><b>Note</b>: Default bullet is "» ", where the whitespace must be entered has entity &nbsp.', 401, 4),
( 3, 'Sub-Category Products Count', 'Define sprintf format to display Sub-Category Products count.<br /><b>Note</b>: Default format is (%s) that causes the products count to be displayed surrounded by parentesis. For more information, read the PHP manual for sprintf function.', 401, 5),
( 3, 'Category Name Case', 'Choose between same case, upper case, lower case or title case for Current Level Categories Name.', 401, 6);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Google API Key', 'Enter your Google Maps API Key.<br />If you dont have one you can sign up for one free <a target="_blank" class="adminLink" href="http://code.google.com/apis/maps/signup.html"><b>Google Maps API</b></a>', 449, 2),
( 3, 'Store Street Address - Used by Google Maps', 'The street address of my store', 449, 6),
( 3, 'Store City - Used by Google Maps', 'The city my store is located in', 449, 7),
( 3, 'Store State - Used by Google Maps', 'The state my store is located in', 449, 8),
( 3, 'Store Postal Code - Used by Google Maps', 'The postal zip code of my store', 449, 9),
( 3, 'Store Country - Used by Google Maps', 'The country my store is located in', 449, 10),
( 3, 'Google Maps Heading Text           ', 'The Text at the Top of the Google Map ', 449, 15),
( 3, 'Google Maps Breedte                ', 'The Length of the Google Map', 449, 16),
( 3, 'Google Maps Hoogte                 ', 'The Height of the Google Map', 449, 17),
( 3, 'Google Maps Latitude Position      ', 'The middle postion on the Google Map Latitude', 449, 18),
( 3, 'Google Maps Longtitude Position    ', 'The middle postion on the Google Map Longtitude', 449, 19),
( 3, 'Google Maps Zoom Setting           ', 'The Zoom factor on the map', 449, 20),
( 3, 'Google Maps Text Details Order     ', 'The Text for the order details ', 449, 21),
( 3, 'Google Maps Text Loading           ', 'The Text during loading of the Google Map', 449, 22),
( 3, 'Google Maps Order Totaal           ', 'The order totaal for displaying the different pins ', 449, 23),
( 3, 'SelectOrder Status', 'Select one Order Status if this order status is changed by the customer order the Google Map coordinats will updated with the google map coordinates.', 449, 25);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Automatically Add New Pages', 'Adds any new pages when Page Control is accessed<br>(true=on false=off)', 543, 10),
( 3, 'Check for Missing Tags', 'Check to see if any products, categories or manufacturers contain empty meta tag fields<br>(true=on false=off)', 543, 20),
( 3, 'Clear Cache', 'Remove all Header Tags cache entries from the database.', 543, 30),
( 3, '<font color=purple>Display Category Parents in Title and Tags</font>', 'Adds all categories in the current path (Full), all immediate categories if the product is in more than one category (Duplicate) or only the immediate category (Standard). These settings only work if the Category checkbox is enabled in Page Control.', 543, 40),
( 3, '<font color=purple>Display Column Box</font>', 'Display product box in column while on product page<br>(true=on false=off)', 543, 50),
( 3, '<font color=purple>Display Help Popups</font>', 'Display short popup messages that describes a feature<br>(true=on false=off)', 543, 60),
( 3, '<font color=purple>Display Currently Viewing</font>', 'Display a link near the bottom of the product page.<br>(true=on false=off)', 543, 70),
( 3, '<font color=purple>Disable Permission Warning</font>', 'Prevent the warning that appears if the permissions for the includes/header_tags.php file appear to be incoorect.<br>(true=on false=off)', 543, 80),
( 3, '<font color=purple>Display Page Top Title</font>', 'Displays the page title at the very top of the page<br>(true=on false=off)', 543, 90),
( 3, '<font color=purple>Display Silo Links</font>', 'Display a box displaying links based on the settings in Silo Control<br>(true=on false=off)', 543, 100),
( 3, '<font color=purple>Display Social Bookmark</font>', 'Display social bookmarks on the product page<br>(true=on false=off)', 543, 110),
( 3, '<font color=purple>Display Tag Cloud</font>', 'Display the Tag Cloud infobox<br>(true=on false=off)', 543, 120),
( 3, '<font color=blue>Enable AutoFill - Listing Text</font>', 'If true, text will be shown on the product listing page automatically. If false, the text only shows if the field has text in it.', 543, 130),
( 3, '<font color=blue>Enable Cache</font>', 'Enables cache for Header Tags. The GZip option will use gzip to try to increase speed but may be a little slower if the Header Tags data is small.', 543, 140),
( 3, '<font color=blue>Enable an HTML Editor</font>', 'Use an HTML editor, if selected. !!! Warning !!! The selected editor must be installed for it to work!!!)', 543, 150),
( 3, '<font color=blue>Enable HTML Editor for Category Descriptions</font>', 'Enables the selected HTML editor for the categories description box. The editor must be installed for this to work.', 543, 160),
( 3, '<font color=blue>Enable HTML Editor for Products Descriptions</font>', 'Enables the selected HTML editor for the products description box. The editor must be installed for this to work.', 543, 170),
( 3, '<font color=blue>Enable HTML Editor for Product Listing text</font>', 'Enables the selected HTML editor for the Header Tags text on the product listing page. The editor must be installed for this to work.', 543, 180),
( 3, '<font color=blue>Enable HTML Editor for Product Sub Text</font>', 'Enables the selected HTML editor for the sub text on the products page. The editor must be installed for this to work.', 543, 190),
( 3, '<font color=blue>Enable Version Checker</font>', 'Enables the code that checks if updates are available.', 543, 200),
( 3, 'Keyword Density Range', 'Set the limits for the keyword density use to dynamically select the keywords. Enter two figures, separated by a comma.', 543, 210),
( 3, 'Position Domain', 'Set the domain name to be used in the keyword position checking code, like www.domain_name.com or domain_name.com/shop.', 543, 220),
( 3, 'Position Page Count', 'Set the number of pages to search when checking keyword positions (10 urls per page).', 543, 230),
( 3, 'Separator - Description', 'Set the separator to be used for the description (and titles and logo).', 543, 240),
( 3, 'Separator - Keywords', 'Set the separator to be used for the keywords.', 543, 250),
( 3, 'Search Keywords', 'This option allows keywords stored in the Header Tags SEO search table to be searched when a search is performed on the site.', 543, 260),
( 3, 'Store Keywords', 'This option stores the searched for keywords so they can be used by other parts of Header Tags, like in the Tag Cloud option.', 543, 270),
( 3, 'Tag Cloud Column Count', 'Set the number of keywords to display in a row in the Tag Cloud box.', 543, 280),
( 3, 'Display Category Short Description', 'If a number is entered, that many characters of the category description will be displayed under the category name on the category listing page. <br><br>Leave blank to display all of the text (not recommended). <br><br>Enter \'Off\' to disable this option..', 543, 300),
( 3, 'Keyword Highlighter', 'Bold any keywords found on the page.', 543, 320 ),
( 3, 'Use Item Name on Page', 'If true, the title on the page will be the name of the item (category, manufacturer or product). If false, the Header Tags SEO title will be used.', 543, 340 ) ;



INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Must accept when registering', '<b>If true</b>, the customer must accept the Terms &amp; Conditions <b>when registrating</b>.', 730, 1),
( 3, 'Must accept at checkout', '<b>If true</b>, the customer must accept the Terms &amp; Conditions <b>at the order confirmation</b>.', 730, 2),
( 3, 'Link - Show?', '<b>If true</b>, a link to the Terms &amp; Conditions will be <b>displayed</b> next to the checkbox.', 730, 3),
( 3, 'Link - Filename', 'This is the filename of the terms and conditions. <br><br><b>Example:</b> <i>conditions.php</i>', 730, 4),
( 3, 'Link - Parameters', 'This is the parameters to use together with the filename in the URL. This will need to be used only when certain other contributions is installed. <br><br><b>Example:</b> <i>hello=world&foo=bar</i>', 730, 5),
( 3, 'Textarea - Show?', '<b>If true</b>, the Terms &amp; Conditions will be displayed in a <b>textarea at the same page</b>.', 730, 6),
( 3, 'Textarea - Languagefile Filename', 'Pick a languagefile to require. If set to nothing, nothing will be required. <br><br><b>Example:</b> <i>conditions.php</i>', 730, 7),
( 3, 'Textarea - Mode (How to get the contents)', 'Returning code will be "php-evaluated" and should return the text. SQL should be a string and have the text aliased to "thetext".<br><br><b>Default:</b> <i>Returning code</i>', 730, 8),
( 3, 'Textarea - Returning Code', 'A <b>pice of code which returns</b> the contents of the textarea. This can for example be a definition that you loaded from the languagefile.<br><br><b>Example:</b> <i>TEXT_INFORMATION</i>', 730, 9),
( 3, 'Textarea - SQL', 'SQL should be a string and have the text aliased to "thetext".<br><br><b>Example:</b> <i>"SELECT products_description AS thetext FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE language_id = ".$languages_id." AND products_id = 1;"</i>', 730, 10),
( 3, 'Textarea - Use HTML to Plain text convertion tool?', '<b>If true</b>, the loaded text will be converted from html <b>to plain text</b>, using this conversion tool: <a href="http://www.chuggnutt.com/html2text.php" style="color:green;">http://www.chuggnutt.com/html2text.php</a>', 730, 11),
( 3, 'Disabled buttonstyle', '<b><i>&quot;transparent&quot;</i></b> will work on all servers but <b><i>&quot;gray&quot;</i></b> requires php version >= 5 ', 730, 12);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Database Optimizer ON/OFF', 'Optimize DataBase <br>(true=on false=off)', 779, 1),
( 3, 'Optimize Database Period', 'How often the database should be optimized. (<b>Value entered must be in days</b>)', 779, 15),
( 3, 'Analyze Database Period', 'How often the database should be analyzed. (<b>Value entered must be in days</b>)', 779, 20),
( 3, 'Truncate Customers', 'Should older entries in the customers and customers basket tables be removed? Enter the number of days between removals or leave blank for no removal. (<b>Value entered must be in days</b>)', 779, 25),
( 3, 'Truncate Orders CC Number', 'Should credit card details be removed from the orders table? Enter the number of days between removals or leave blank for no removal. (<b>Value entered must be in days</b>)', 779, 35),
( 3, 'Truncate Sessions', 'Should older entries in the sessions table be removed? Enter the number of days between removals or leave blank for no removal. (<b>Value entered must be in days</b>)', 779, 45),
( 3, 'Truncate User Tracking', 'Should older entries in the user tracking table be removed? Enter the number of days between removals or leave blank for no removal. (<b>Value entered must be in days</b>)', 779, 55),
( 3, 'Enable Version Checker', 'Enables the code that checks if updates are available.', 779, 70);

INSERT INTO `configuration_languages` ( `languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Invoice Print Store Logo on Invoice', 'Should the Store Logo be printed on the Invoice  ? False = No, True = Yes', 7200, 1),
( 3, 'Invoice location of the Store logo', 'Give the location of the Store Logo', 7200, 2),
( 3, 'Invoice Print Addres Webshop ', 'Print the Addres etc on The Invoice  ? False = No, True = Yes', 7200, 3),
( 3, 'Invoice Print Email en WebAddres', 'Print the Email en Webaddres of The Shop on The Invoice ? False = No, True = Yes', 7200, 4),
( 3, 'Invoice Print Box Send To ', 'Print the Sold To Box on The Invoice  ? False = No, True = Yes', 7200, 5),
( 3, 'Invoice Print Box Sold To ', 'Print the Send To Box on The Invoice  ? False = No, True = Yes', 7200, 6),
( 3, 'Invoice paper Width', 'The width on the Invoice in millimeters.', 7200, 7),
( 3, 'Invoice paper height', 'The Height on the Invoice in millimeters.', 7200, 8),
( 3, 'Invoice Orientation', 'The Orientation on the Invoice. P= protrait L= Landscape', 7200, 9),
( 3, 'Invoice Background Fill Color', 'The Color of the background of the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 10),
( 3, 'Invoice HeaderText Color Box Line', 'The Color of the Border of the Header Text on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 20),
( 3, 'Invoice HeaderText BackgroundFill Color', 'The Color of the background of the Text in the Send To Box on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 21),
( 3, 'Invoice HeaderText Color', 'The Color of the Text Invoice in the Header on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 22),
( 3, 'Invoice HeaderText Text Size', 'The Text Size of the Text Invoice in the Header on the Invoice', 7200, 23),
( 3, 'Invoice HeaderText Text Effect', 'The effect of the Text Invoice in the Header B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 24),
( 3, 'Invoice HeaderText Text Font', 'Het font of the Text Invoice in the Header on the Invoice', 7200, 25),
( 3, 'Invoice Text Invoice Color', 'The Color of the Text  INVOICE in the Header on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 30),
( 3, 'Invoice Text Invoice Text Size', 'The Text Size of the Text INVOICE in header on the Invoice', 7200, 31),
( 3, 'Invoice Text Invoice Text Effect', 'The effect of the Text INVOICE in the Header B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 32),
( 3, 'Invoice Text Invoice Text Font', 'The font of the Text INVOICE in the kop on the Invoice', 7200, 33),
( 3, 'Invoice Color Box Line Sold To ', 'The Color of the Border of the Box Sold To on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 40),
( 3, 'Invoice Color Background Text Sold To', 'The Color of the Background of the Text in the Box Sold To on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 41),
( 3, 'Invoice Text Sold To Color', 'The Color of the Text in the Box Sold To on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 42),
( 3, 'Invoice Text Sold To Text Size', 'The Text Size of the Text in the Box of Sold To on the Invoice', 7200, 43),
( 3, 'Invoice Text Sold To Text Effect', 'The effect of the Text in the Box Sold To B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 44),
( 3, 'Invoice Text Sold To Text Font', 'The font of the Text in the Box of Sold To on the Invoice', 7200, 45),
( 3, 'Invoice Color Line Box Send To ', 'The Color of the Border of the The Text of het Send To box on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 50),
( 3, 'Invoice Color Background Text Send To', 'The Color of the Background of the Text in The Box of Send To on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 51),
( 3, 'Invoice Text Send To Color', 'The Color of the Text in The Box of Send To on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 52),
( 3, 'Invoice Text Send To Text Size', 'The Text Size of the Text in The Box of Send To on the Invoice', 7200, 53),
( 3, 'Invoice Text Send To Text Effect', 'The effect of the Text in The The Box of Send To B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 54),
( 3, 'Invoice Text Send To Text Font', 'Het font of the Text in The The Box of Send To on the Invoice', 7200, 55),
( 3, 'Invoice Invoice Details Color Line Box ', 'The Color of the Border of the Box of the Order Details on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 60),
( 3, 'Invoice Invoice Details Color Background', 'The Color of the Background of the Text in the Box of Order Details on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 61),
( 3, 'Invoice Invoice Details Text Color', 'The Color of the Text in The Box of Order Details on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 62),
( 3, 'Invoice Invoice Details Text Text Size', 'The Text Size of the Text in the Box of Order Details on the Invoice', 7200, 63),
( 3, 'Invoice Invoice Details Text Text Effect', 'The effect of the Text in the Box of Order Details B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 64),
( 3, 'Invoice Invoice Details Text Text Font', 'The font of the Text in the Box of Order Details on the Invoice', 7200, 65),
( 3, 'Invoice ProductsHeaderText Color Line Box ', 'The Color of the Border of the Box of HeaderText Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 70),
( 3, 'Invoice ProductsHeaderText Color Background', 'The Color of the Background of the Text in the Box HeaderText Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 71),
( 3, 'Invoice ProductsHeaderText Text Color', 'The Color of the Text in The Box HeaderText Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 72),
( 3, 'Invoice ProductsHeaderText Text Text Size', 'The Text Size of the Text in The Box HeaderText Products on the Invoice', 7200, 73),
( 3, 'Invoice ProductsHeaderText Text Text Effect', 'The effect of the Text in the Box HeaderText Products B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 74),
( 3, 'Invoice ProductsHeaderText Text Text Font', 'The font of the Text in the Box HeaderText Products on the Invoice', 7200, 75),
( 3, 'Invoice Products Text Color Line Box ', 'The Color of the Border of the Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 80),
( 3, 'Invoice Products Text Color Background', 'The Color of the Background of the Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 81),
( 3, 'Invoice Products Text Text Color', 'The Color of the Text of Products on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 82),
( 3, 'Invoice Products Text Text Text Size', 'The Text Size of the Text of Products on the Invoice', 7200, 83),
( 3, 'Invoice Products Text Text Text Effect', 'The effect of the Text of Products B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 84),
( 3, 'Invoice Products Text Text Text Font', 'The font of the Text of Products on the Invoice', 7200, 85) ,
( 3, 'Invoice OrderTotal InvoiceTotal Line Box ', 'The Color of the Border of the Text of OrderTotals on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 90),
( 3, 'Invoice OrderTotal InvoiceTotal Color Background', 'The Color of the Background of the Text of OrderTotals on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 91),
( 3, 'Invoice OrderTotal InvoiceTotal Text Color', 'The Color of the Text of OrderTotals on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 92),
( 3, 'Invoice OrderTotal InvoiceTotal Text Text Size', 'The Text Size of the Text of OrderTotals on the Invoice', 7200, 93),
( 3, 'Invoice OrderTotal InvoiceTotal Text Text Effect', 'The effect of the Text of OrderTotals   B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 94),
( 3, 'Invoice OrderTotal InvoiceTotal Text Text Font', 'The font of the Text of OrderTotals on the Invoice', 7200, 95),
( 3, 'Invoice OrderTotal InvoiceTotal Color Line Box ', 'The Color of the Border of the The Text of OrderTotal InvoiceTotal on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 100),
( 3, 'Invoice OrderTotal InvoiceTotal Color Background', 'The Color of the Background of the Text of OrderTotal InvoiceTotal on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 101),
( 3, 'Invoice OrderTotal InvoiceTotal Text Color', 'The Color of the Text OrderTotal InvoiceTotal on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 102),
( 3, 'Invoice OrderTotal InvoiceTotal Text Text Size', 'The Text Size of the Text OrderTotal InvoiceTotal on the Invoice', 7200, 103),
( 3, 'Invoice OrderTotal InvoiceTotal Text Text Effect', 'The effect of the Text of OrderTotal InvoiceTotal B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 104),
( 3, 'Invoice OrderTotal InvoiceTotal Text Text Font', 'The font of the Text of OrderTotal InvoiceTotal on the Invoice', 7200, 105),
( 3, 'Invoice Footer Color Background', 'The Color of the Background of the Text of Footer on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 110),
( 3, 'Invoice Footer Color', 'The Color of the Text in The Footer on the Invoice. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 111),
( 3, 'Invoice Footer Text Size', 'The Text Size of the Text in Footer on the Invoice', 7200, 112),
( 3, 'Invoice Footer Text Effect', 'The effect of the Text in The Footer B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 113),
( 3, 'Invoice Footer Text Font', 'Het font of the Text in The Footer on the Invoice', 7200, 114),
( 3, 'Invoice Footer Text', 'The Text in the Footer on the Invoice. ', 7200, 115);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'PackingSlip Print Store Logo on PackingSlip', 'Print the Store Logo be printed on the PackingSlip  ? False = No, True = Yes', 7200, 301),
( 3, 'PackingSlip location of the Store logo', 'Give the location of the Store Logo', 7200, 302),
( 3, 'PackingSlip Print Addres of the Store ', 'Print the Addres etc on The PackingSlip  ? False = No, True = Yes', 7200, 303),
( 3, 'PackingSlip Print Email en WebAddres', 'Print the Email en Webaddres of The Shop on The PackingSlip ? False = No, True = Yes', 7200, 304),
( 3, 'PackingSlip Print Box Send To ', 'Print the Sold To Box on The PackingSlip  ? False = No, True = Yes', 7200, 305),
( 3, 'PackingSlip Print Box Sold To ', 'Print the Send To Box on The PackingSlip  ? False = No, True = Yes', 7200, 306),
( 3, 'PackingSlip paper Width', 'The width on the PackingSlip in millimeters.', 7200, 307),
( 3, 'PackingSlip paper height', 'The Height on the PackingSlip in millimeters.', 7200, 308),
( 3, 'PackingSlip Orientation', 'The Orientation on the PackingSlip. P= protrait L= Landscape', 7200, 309),
( 3, 'PackingSlip Background Fill Color', 'The Color of the background of the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 310),
( 3, 'PackingSlip HeaderText Color Box Line', 'The Color of the Border of the Header Text on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 320),
( 3, 'PackingSlip HeaderText BackgroundFill Color', 'The Color of the background of the Text in the Send To Box on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 321),
( 3, 'PackingSlip HeaderText Color', 'The Color of the Text PackingSlip in the Header on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 322),
( 3, 'PackingSlip HeaderText Text Size', 'The Text Size of the Text PackingSlip in the Header on the PackingSlip', 7200, 323),
( 3, 'PackingSlip HeaderText Text Effect', 'The effect of the Text PackingSlip in the Header B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 324),
( 3, 'PackingSlip HeaderText Text Font', 'Het font of the Text PackingSlip in the Header on the PackingSlip', 7200, 325),
( 3, 'PackingSlip Text PackingSlip Color', 'The Color of the Text  PackingSlip in the Header on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 330),
( 3, 'PackingSlip Text PackingSlip Text Size', 'The Text Size of the Text PackingSlip in header on the PackingSlip', 7200, 331),
( 3, 'PackingSlip Text PackingSlip Text Effect', 'The effect of the Text PackingSlip in the Header B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 332),
( 3, 'PackingSlip Text PackingSlip Text Font', 'The font of the Text PackingSlip in the kop on the PackingSlip', 7200, 333),
( 3, 'PackingSlip Color Line Box Send To ', 'The Color of the Border of the The Text of het Send To box on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 350),
( 3, 'PackingSlip Color Background Text Send To', 'The Color of the Background of the Text in The Box of Send To on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 351),
( 3, 'PackingSlip Text Send To Color', 'The Color of the Text in The Box of Send To on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 352),
( 3, 'PackingSlip Text Send To Text Size', 'The Text Size of the Text in The Box of Send To on the PackingSlip', 7200, 353),
( 3, 'PackingSlip Text Send To Text Effect', 'The effect of the Text in The The Box of Send To B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 354),
( 3, 'PackingSlip Text Send To Text Font', 'Het font of the Text in The The Box of Send To on the PackingSlip', 7200, 355),
( 3, 'PackingSlip PackingSlip Details Color Line Box ', 'The Color of the Border of the Box of the Order Details on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 360),
( 3, 'PackingSlip PackingSlip Details Color Background', 'The Color of the Background of the Text in the Box of Order Details on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 361),
( 3, 'PackingSlip PackingSlip Details Text Color', 'The Color of the Text in The Box of Order Details on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 362),
( 3, 'PackingSlip PackingSlip Details Text Text Size', 'The Text Size of the Text in the Box of Order Details on the PackingSlip', 7200, 363),
( 3, 'PackingSlip PackingSlip Details Text Text Effect', 'The effect of the Text in the Box of Order Details B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 364),
( 3, 'PackingSlip PackingSlip Details Text Text Font', 'The font of the Text in the Box of Order Details on the PackingSlip', 7200, 365),
( 3, 'PackingSlip ProductsHeaderText Color Line Box ', 'The Color of the Border of the Box of HeaderText Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 370),
( 3, 'PackingSlip ProductsHeaderText Color Background', 'The Color of the Background of the Text in the Box HeaderText Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 371),
( 3, 'PackingSlip ProductsHeaderText Text Color', 'The Color of the Text in The Box HeaderText Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 372),
( 3, 'PackingSlip ProductsHeaderText Text Text Size', 'The Text Size of the Text in The Box HeaderText Products on the PackingSlip', 7200, 373),
( 3, 'PackingSlip ProductsHeaderText Text Text Effect', 'The effect of the Text in the Box HeaderText Products B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 374),
( 3, 'PackingSlip ProductsHeaderText Text Text Font', 'The font of the Text in the Box HeaderText Products on the PackingSlip', 7200, 375),
( 3, 'PackingSlip Products Text Color Line Box ', 'The Color of the Border of the Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 380),
( 3, 'PackingSlip Products Text Color Background', 'The Color of the Background of the Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 381),
( 3, 'PackingSlip Products Text Text Color', 'The Color of the Text of Products on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 382),
( 3, 'PackingSlip Products Text Text Text Size', 'The Text Size of the Text of Products on the PackingSlip', 7200, 383),
( 3, 'PackingSlip Products Text Text Text Effect', 'The effect of the Text of Products B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 384),
( 3, 'PackingSlip Products Text Text Text Font', 'The font of the Text of Products on the PackingSlip', 7200, 385),
( 3, 'PackingSlip Footer Color Background', 'The Color of the Background of the Text of Footer on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 390),
( 3, 'PackingSlip Footer Color', 'The Color of the Text in The Footer on the PackingSlip. Insert a commaseparated RGB-Value (Red,Yellow,Blue).Permitted number Values are from 0-255.', 7200, 391),
( 3, 'PackingSlip Footer Text Size', 'The Text Size of the Text in Footer on the PackingSlip', 7200, 392),
( 3, 'PackingSlip Footer Text Effect', 'The effect of the Text in The Footer B= Bold I= Cursief U= Underline. A combination of effects is possible', 7200, 393),
( 3, 'PackingSlip Footer Text Font', 'Het font of the Text in The Footer on the PackingSlip', 7200, 394),
( 3, 'PackingSlip Footer Text', 'The Text in the Footer on the PackingSlip. ', 7200, 395);

INSERT INTO `configuration_languages` (`languages_id`, `configuration_title`, `configuration_description`, `configuration_group_id`, `configuration_sort_order`) VALUES
( 3, 'Activate Dutch TRack and Trace?', 'This setting will activate the dutch track and trace funtion in admin orders and in the account order history.', 8000, 10),
( 3, 'The URL for the search window', 'This is the URL to the search window of the dutch track and trace', 8000, 20),
( 3, 'The second URL for the search window', 'This is the second part URL to the search window of the dutch track and trace', 8000, 30);


INSERT INTO configuration_languages ( languages_id, configuration_title, configuration_description, configuration_group_id, configuration_sort_order) VALUES
( 3, 'Store Catalog Website URL',                           'The URL for your stores catalog (eg. http://www.domain.com)', 8600, 10),
( 3, 'Store Catalog Website SSL URL',                       'The SSL URL for your stores catalog (eg. https://www.domain.com)', 8600, 20),
( 3, 'Enable SSL Store Catalog',                            'Enable SSL links for Store Catalog', 8600, 30),
( 3, 'Store Catalog Website Path',                          'Directory Website Path for Store Catalog (absolute path required -- eg. /catalog/)', 8600, 40),
( 3, 'Store Catalog Filesystem Path',                       'Directory Filesystem Path for Store Catalog (absolute path required -- eg. /home/user/public_html/catalog/)', 8600, 50),
( 3, 'Store Catalog Website Images Path',                   'Store Catalog Website Images Path (with trailing slash -- eg. http://www.domain.com/catalog/images/)', 8600, 60),
( 3, 'Store Catalog Website Languages Path',                'Store Catalog Website Languages Path (with trailing slash -- eg. http://www.domain.com/catalog/includes/languages/)', 8600, 70 ),
( 3, 'Store Catalog Filesystem Languages Path',             'Store Catalog Filesystem Languages Path (with trailing slash -- eg. /home/user/public_html/catalog/includes/languages/)', 8600, 80),
( 3, 'Store Catalog Filesystem Images Path',                'Store Catalog Filesystem Images Path (with trailing slash -- eg. /home/user/public_html/catalog/images/)', 8600, 90),
( 3, 'Store Catalog Filesystem Modules Path',               'Store Catalog Filesystem Modules Path (with trailing slash -- eg. /home/user/public_html/catalog/includes/modules/)', 8600, 100) ;


INSERT INTO configuration_languages ( languages_id, configuration_title, configuration_description, configuration_group_id, configuration_sort_order) VALUES
( 3, 'Max Wish List',         'How many wish list items to show per page on the main wishlist.php file', '12954', 10 ),
( 3, 'Max Wish List Box',     'How many wish list items to display in the infobox before it changes to a counter', '12954', 20),
( 3, 'Display Emails',        'How many emails to display when the customer emails their wish list link', '12954', 30 ),
( 3, 'Wish List Redirect',    'Do you want to redirect back to the product_info.php page when a customer adds a product to their wish list?', '12954', 40 );


INSERT INTO configuration_group (configuration_group_id, languages_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES
(1,      2, 'Mijn Winkel', 'Algemene informatie over mijn winkel',                                                        1, 1),
(2,      2, 'Minimum Waardes', 'De minimum waardes voor functies/gegevens',                                               10, 1),
(3,      2, 'Maximum Waardes', 'De maximum waardes voor functies/gegevens',                                               15, 1),
(4,      2, 'Afbeeldingen', 'Afbeelding instellingen',                                                                    20, 1),
(5,      2, 'KlantDetails', 'Klant account configuratie',                                                                 25, 1),
(6,      2, 'Module Opties', 'Verborgen voor de configuratie',                                                            30, 0),
(7,      2, 'Verzending/Verpakking', 'Verzendopties in mijn winkel',                                                      35, 1),
(8,      2, 'Product Lijst', 'Product Lijst configuratie opties',                                                         40, 1),
(9,      2, 'Voorraad', 'Voorraad configuratie opties',                                                                   45, 1),
(10,     2, 'Logging', 'Logging configuratie opties',                                                                     50, 1),
(11,     2, 'Cache', 'Caching configuratie opties',                                                                       55, 1),
(12,     2, 'E-Mail Opties', 'Algemene e-mail instellingen voor verzending en HTML',                                      60, 1),
(13,     2, 'Download', 'Downloadbare product opties',                                                                    65, 1),
(14,     2, 'GZip Compressie', 'GZip compressie opties',                                                                  70, 1),
(15,     2, 'Sessies', 'Sessies opties',                                                                                  75, 1),
(543,    2, 'Header Tags SEO', 'Header Tags SEO site wide opties',                                                        80, 1),
(30,     2, 'EasyMap', 'EasyMap - configuraties opties',                                                                  85, 1),
(32,     2, 'Webshop Onderhoud', 'Webshop Onderhoud Opties',                                                              90, 1),
(16,     2, 'Seo Urls 5', 'Opties voor ULTIMATE Seo Urls 5 by FWR Media',                                                 95, 1),
(779,    2, 'Databank  Optimalisatie', 'Databank Optimalisatie Opties',                                                   100, 1),
(73,     2, 'PrijsAfspraken', 'Opties voor PrijsAfspraken',                                                               110, 1),
(72,     2, 'Order Editor', 'Instellen van Opties voor Order Editor',                                                     120, 1),
(7200,   2, 'PDF Factuur/PDF Verzendlijst', 'Wijzigen van de gegevens van de PDF Factuur/PDF Verzendlijst van de Orders', 130, 1),
(200,    2, 'Verzend Etiket', 'Wijzigen van de gegevens van de Verzend Etiket van de Orders',                             140, 1),
(401,    2, 'Zoeken op Categorien', 'Zoeken op Categorien opties',                                                        150, 1),
(300,    2, 'Snelle Updates', 'Hier kunt aangeven welke opties gewijzigd kunnen worden in Quick Updates.',                160, 1),
(730,    2, 'Algemene Voorwaarden', 'Configuration opties voor de Algemende Voorwaarden.',                                170, 1),
(8000,   2, 'Track Trace Opties Nederland ', 'Opties voor Nederlandse Track Trace of Pakketten',                          180, 1),
(449,    2, 'Google Maps', 'Google Maps Instellingen',                                                                    200, 1),
(17,     2, 'Option Types', 'Configuratie van Option Types and Upload instellingen.',                                     210, 1),
(8600,   2, 'Opties Multi Winkels', 'Configuratie van de Opties van meerdere inkels of Standaard Winkel.',                220, 1),
(12954,  2, 'Verlanglijst', 'Configuratie van de Verlanglijst Opties',                                                    230, 1 );


INSERT INTO configuration_group (configuration_group_id, languages_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES
('1',    3,   'My Store', 'General information about my store',                                                   '1', '1'),
('2',    3,   'Minimum Values', 'The minimum values for functions / data',                                        '10', '1'),
('3',    3,   'Maximum Values', 'The maximum values for functions / data',                                        '15', '1'),
('4',    3,   'Images', 'Image parameters',                                                                       '20', '1'),
('5',    3,   'Customer Details', 'Customer account configuration',                                               '25', '1'),
('6',    3,   'Module Options', 'Hidden from configuration',                                                      '30', '0'),
('7',    3,   'Shipping/Packaging', 'Shipping options available at my store',                                     '35', '1'),
('8',    3,   'Product Listing', 'Product Listing    configuration options',                                      '40', '1'),
('9',    3,   'Stock', 'Stock configuration options',                                                             '45', '1'),
('10',   3,   'Logging', 'Logging configuration options',                                                         '50', '1'),
('11',   3,   'Cache', 'Caching configuration options',                                                           '55', '1'),
('12',   3,   'E-Mail Options', 'General setting for E-Mail transport and HTML E-Mails',                          '60', '1'),
('13',   3,   'Download', 'Downloadable products options',                                                        '65', '1'),
('14',   3,   'GZip Compression', 'GZip compression options',                                                     '70', '1'),
('15',   3,   'Sessions', 'Session options',                                                                      '75', '1'),
('543',  3,   'Header Tags SEO', 'Header Tags SEO site wide options',                                             '80' , '1'),
('30',   3,   'EasyMap', 'EasyMap - configuration options',                                                       '85', '1'),
('32',   3,   'Website Maintenance', 'Website Maintenance Options',                                               '90', '1'),
('16',   3,   'Seo Urls 5', 'Opties voor ULTIMATE Seo Urls 5 by FWR Media',                                       '95', '1'),
('779',  3,   'Database Optimizer', 'Database Optimizer Options',                                                 '100', '1'),
('73',   3,   'Price breaks', 'Configuration options for price breaks',                                           '110', '1'),
('72',   3,    'Order Editor', 'Settings for Order Editor',                                                       '120', '1'),
('7200', 3,    'PDF Invoice/PDF Packinglist', 'Change the settings of the PDF Invoice/PDF PackingList',           '130', '1'),
('200',  3,    'Shipping Label', 'Settings for the shipping Label of the orders',                                 '140',  '1'),
('401',  3,    'Browse by Categories', 'Browse by Categories options',                                            '150',  '1'),
('300',  3,    'Quick Updates', 'Here you can configure what you will be able to modify in Quick Updates.',       '160', '1'),
('730',  3,    'Terms &amp; Conditions', 'Configuration options for Terms &amp; Conditions.',                     '170',  '1'),
('8000', 3,    'Track Trace Options Dutch ', 'Options for Dutch Track Trace of Parcels',                          '180',  '1'),
('449',  3,    'Google Maps', 'Settings Google Maps',                                                             '200',  '1'),
('17',   3,    'Option Types', 'Configure Option Types and Upload settings.',                                     '210',  '1'),
('8600', 3,    'Option Stores', 'Configure Options for the Multi Stores of Standard Store.',                      '220',  '1'),
('12954',3,    'Wish List Settings', 'Settings for your Wish List',                                               '230',  '1' );


INSERT INTO countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES
(1, 'Afghanistan', 'AF', 'AFG', 1),
(2, 'Albania', 'AL', 'ALB', 1),
(3, 'Algeria', 'DZ', 'DZA', 1),
(4, 'American Samoa', 'AS', 'ASM', 1),
(5, 'Andorra', 'AD', 'AND', 1),
(6, 'Angola', 'AO', 'AGO', 1),
(7, 'Anguilla', 'AI', 'AIA', 1),
(8, 'Antarctica', 'AQ', 'ATA', 1),
(9, 'Antigua and Barbuda', 'AG', 'ATG', 1),
(10, 'Argentina', 'AR', 'ARG', 1),
(11, 'Armenia', 'AM', 'ARM', 1),
(12, 'Aruba', 'AW', 'ABW', 1),
(13, 'Australia', 'AU', 'AUS', 1),
(14, 'Austria', 'AT', 'AUT', 5),
(15, 'Azerbaijan', 'AZ', 'AZE', 1),
(16, 'Bahamas', 'BS', 'BHS', 1),
(17, 'Bahrain', 'BH', 'BHR', 1),
(18, 'Bangladesh', 'BD', 'BGD', 1),
(19, 'Barbados', 'BB', 'BRB', 1),
(20, 'Belarus', 'BY', 'BLR', 1),
(21, 'Belgium', 'BE', 'BEL', 1),
(22, 'Belize', 'BZ', 'BLZ', 1),
(23, 'Benin', 'BJ', 'BEN', 1),
(24, 'Bermuda', 'BM', 'BMU', 1),
(25, 'Bhutan', 'BT', 'BTN', 1),
(26, 'Bolivia', 'BO', 'BOL', 1),
(27, 'Bosnia and Herzegowina', 'BA', 'BIH', 1),
(28, 'Botswana', 'BW', 'BWA', 1),
(29, 'Bouvet Island', 'BV', 'BVT', 1),
(30, 'Brazil', 'BR', 'BRA', 1),
(31, 'British Indian Ocean Territory', 'IO', 'IOT', 1),
(32, 'Brunei Darussalam', 'BN', 'BRN', 1),
(33, 'Bulgaria', 'BG', 'BGR', 1),
(34, 'Burkina Faso', 'BF', 'BFA', 1),
(35, 'Burundi', 'BI', 'BDI', 1),
(36, 'Cambodia', 'KH', 'KHM', 1),
(37, 'Cameroon', 'CM', 'CMR', 1),
(38, 'Canada', 'CA', 'CAN', 1),
(39, 'Cape Verde', 'CV', 'CPV', 1),
(40, 'Cayman Islands', 'KY', 'CYM', 1),
(41, 'Central African Republic', 'CF', 'CAF', 1),
(42, 'Chad', 'TD', 'TCD', 1),
(43, 'Chile', 'CL', 'CHL', 1),
(44, 'China', 'CN', 'CHN', 1),
(45, 'Christmas Island', 'CX', 'CXR', 1),
(46, 'Cocos (Keeling) Islands', 'CC', 'CCK', 1),
(47, 'Colombia', 'CO', 'COL', 1),
(48, 'Comoros', 'KM', 'COM', 1),
(49, 'Congo', 'CG', 'COG', 1),
(50, 'Cook Islands', 'CK', 'COK', 1),
(51, 'Costa Rica', 'CR', 'CRI', 1),
(52, 'Cote D''Ivoire', 'CI', 'CIV', 1),
(53, 'Croatia', 'HR', 'HRV', 1),
(54, 'Cuba', 'CU', 'CUB', 1),
(55, 'Cyprus', 'CY', 'CYP', 1),
(56, 'Czech Republic', 'CZ', 'CZE', 1),
(57, 'Denmark', 'DK', 'DNK', 1),
(58, 'Djibouti', 'DJ', 'DJI', 1),
(59, 'Dominica', 'DM', 'DMA', 1),
(60, 'Dominican Republic', 'DO', 'DOM', 1),
(61, 'East Timor', 'TP', 'TMP', 1),
(62, 'Ecuador', 'EC', 'ECU', 1),
(63, 'Egypt', 'EG', 'EGY', 1),
(64, 'El Salvador', 'SV', 'SLV', 1),
(65, 'Equatorial Guinea', 'GQ', 'GNQ', 1),
(66, 'Eritrea', 'ER', 'ERI', 1),
(67, 'Estonia', 'EE', 'EST', 1),
(68, 'Ethiopia', 'ET', 'ETH', 1),
(69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', 1),
(70, 'Faroe Islands', 'FO', 'FRO', 1),
(71, 'Fiji', 'FJ', 'FJI', 1),
(72, 'Finland', 'FI', 'FIN', 1),
(73, 'France', 'FR', 'FRA', 1),
(74, 'France, Metropolitan', 'FX', 'FXX', 1),
(75, 'French Guiana', 'GF', 'GUF', 1),
(76, 'French Polynesia', 'PF', 'PYF', 1),
(77, 'French Southern Territories', 'TF', 'ATF', 1),
(78, 'Gabon', 'GA', 'GAB', 1),
(79, 'Gambia', 'GM', 'GMB', 1),
(80, 'Georgia', 'GE', 'GEO', 1),
(81, 'Germany', 'DE', 'DEU', 5),
(82, 'Ghana', 'GH', 'GHA', 1),
(83, 'Gibraltar', 'GI', 'GIB', 1),
(84, 'Greece', 'GR', 'GRC', 1),
(85, 'Greenland', 'GL', 'GRL', 1),
(86, 'Grenada', 'GD', 'GRD', 1),
(87, 'Guadeloupe', 'GP', 'GLP', 1),
(88, 'Guam', 'GU', 'GUM', 1),
(89, 'Guatemala', 'GT', 'GTM', 1),
(90, 'Guinea', 'GN', 'GIN', 1),
(91, 'Guinea-bissau', 'GW', 'GNB', 1),
(92, 'Guyana', 'GY', 'GUY', 1),
(93, 'Haiti', 'HT', 'HTI', 1),
(94, 'Heard and Mc Donald Islands', 'HM', 'HMD', 1),
(95, 'Honduras', 'HN', 'HND', 1),
(96, 'Hong Kong', 'HK', 'HKG', 1),
(97, 'Hungary', 'HU', 'HUN', 1),
(98, 'Iceland', 'IS', 'ISL', 1),
(99, 'India', 'IN', 'IND', 1),
(100, 'Indonesia', 'ID', 'IDN', 1),
(101, 'Iran (Islamic Republic of)', 'IR', 'IRN', 1),
(102, 'Iraq', 'IQ', 'IRQ', 1),
(103, 'Ireland', 'IE', 'IRL', 1),
(104, 'Israel', 'IL', 'ISR', 1),
(105, 'Italy', 'IT', 'ITA', 1),
(106, 'Jamaica', 'JM', 'JAM', 1),
(107, 'Japan', 'JP', 'JPN', 1),
(108, 'Jordan', 'JO', 'JOR', 1),
(109, 'Kazakhstan', 'KZ', 'KAZ', 1),
(110, 'Kenya', 'KE', 'KEN', 1),
(111, 'Kiribati', 'KI', 'KIR', 1),
(112, 'Korea, Democratic People''s Republic of', 'KP', 'PRK', 1),
(113, 'Korea, Republic of', 'KR', 'KOR', 1),
(114, 'Kuwait', 'KW', 'KWT', 1),
(115, 'Kyrgyzstan', 'KG', 'KGZ', 1),
(116, 'Lao People''s Democratic Republic', 'LA', 'LAO', 1),
(117, 'Latvia', 'LV', 'LVA', 1),
(118, 'Lebanon', 'LB', 'LBN', 1),
(119, 'Lesotho', 'LS', 'LSO', 1),
(120, 'Liberia', 'LR', 'LBR', 1),
(121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', 1),
(122, 'Liechtenstein', 'LI', 'LIE', 1),
(123, 'Lithuania', 'LT', 'LTU', 1),
(124, 'Luxembourg', 'LU', 'LUX', 1),
(125, 'Macau', 'MO', 'MAC', 1),
(126, 'Macedonia, The Former Yugoslav Republic of', 'MK', 'MKD', 1),
(127, 'Madagascar', 'MG', 'MDG', 1),
(128, 'Malawi', 'MW', 'MWI', 1),
(129, 'Malaysia', 'MY', 'MYS', 1),
(130, 'Maldives', 'MV', 'MDV', 1),
(131, 'Mali', 'ML', 'MLI', 1),
(132, 'Malta', 'MT', 'MLT', 1),
(133, 'Marshall Islands', 'MH', 'MHL', 1),
(134, 'Martinique', 'MQ', 'MTQ', 1),
(135, 'Mauritania', 'MR', 'MRT', 1),
(136, 'Mauritius', 'MU', 'MUS', 1),
(137, 'Mayotte', 'YT', 'MYT', 1),
(138, 'Mexico', 'MX', 'MEX', 1),
(139, 'Micronesia, Federated States of', 'FM', 'FSM', 1),
(140, 'Moldova, Republic of', 'MD', 'MDA', 1),
(141, 'Monaco', 'MC', 'MCO', 1),
(142, 'Mongolia', 'MN', 'MNG', 1),
(143, 'Montserrat', 'MS', 'MSR', 1),
(144, 'Morocco', 'MA', 'MAR', 1),
(145, 'Mozambique', 'MZ', 'MOZ', 1),
(146, 'Myanmar', 'MM', 'MMR', 1),
(147, 'Namibia', 'NA', 'NAM', 1),
(148, 'Nauru', 'NR', 'NRU', 1),
(149, 'Nepal', 'NP', 'NPL', 1),
(150, 'Netherlands', 'NL', 'NLD', 5),
(151, 'Netherlands Antilles', 'AN', 'ANT', 1),
(152, 'New Caledonia', 'NC', 'NCL', 1),
(153, 'New Zealand', 'NZ', 'NZL', 1),
(154, 'Nicaragua', 'NI', 'NIC', 1),
(155, 'Niger', 'NE', 'NER', 1),
(156, 'Nigeria', 'NG', 'NGA', 1),
(157, 'Niue', 'NU', 'NIU', 1),
(158, 'Norfolk Island', 'NF', 'NFK', 1),
(159, 'Northern Mariana Islands', 'MP', 'MNP', 1),
(160, 'Norway', 'NO', 'NOR', 1),
(161, 'Oman', 'OM', 'OMN', 1),
(162, 'Pakistan', 'PK', 'PAK', 1),
(163, 'Palau', 'PW', 'PLW', 1),
(164, 'Panama', 'PA', 'PAN', 1),
(165, 'Papua New Guinea', 'PG', 'PNG', 1),
(166, 'Paraguay', 'PY', 'PRY', 1),
(167, 'Peru', 'PE', 'PER', 1),
(168, 'Philippines', 'PH', 'PHL', 1),
(169, 'Pitcairn', 'PN', 'PCN', 1),
(170, 'Poland', 'PL', 'POL', 1),
(171, 'Portugal', 'PT', 'PRT', 1),
(172, 'Puerto Rico', 'PR', 'PRI', 1),
(173, 'Qatar', 'QA', 'QAT', 1),
(174, 'Reunion', 'RE', 'REU', 1),
(175, 'Romania', 'RO', 'ROM', 1),
(176, 'Russian Federation', 'RU', 'RUS', 1),
(177, 'Rwanda', 'RW', 'RWA', 1),
(178, 'Saint Kitts and Nevis', 'KN', 'KNA', 1),
(179, 'Saint Lucia', 'LC', 'LCA', 1),
(180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', 1),
(181, 'Samoa', 'WS', 'WSM', 1),
(182, 'San Marino', 'SM', 'SMR', 1),
(183, 'Sao Tome and Principe', 'ST', 'STP', 1),
(184, 'Saudi Arabia', 'SA', 'SAU', 1),
(185, 'Senegal', 'SN', 'SEN', 1),
(186, 'Seychelles', 'SC', 'SYC', 1),
(187, 'Sierra Leone', 'SL', 'SLE', 1),
(188, 'Singapore', 'SG', 'SGP', 4),
(189, 'Slovakia (Slovak Republic)', 'SK', 'SVK', 1),
(190, 'Slovenia', 'SI', 'SVN', 1),
(191, 'Solomon Islands', 'SB', 'SLB', 1),
(192, 'Somalia', 'SO', 'SOM', 1),
(193, 'South Africa', 'ZA', 'ZAF', 1),
(194, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', 1),
(195, 'Spain', 'ES', 'ESP', 3),
(196, 'Sri Lanka', 'LK', 'LKA', 1),
(197, 'St. Helena', 'SH', 'SHN', 1),
(198, 'St. Pierre and Miquelon', 'PM', 'SPM', 1),
(199, 'Sudan', 'SD', 'SDN', 1),
(200, 'Suriname', 'SR', 'SUR', 1),
(201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', 1),
(202, 'Swaziland', 'SZ', 'SWZ', 1),
(203, 'Sweden', 'SE', 'SWE', 1),
(204, 'Switzerland', 'CH', 'CHE', 1),
(205, 'Syrian Arab Republic', 'SY', 'SYR', 1),
(206, 'Taiwan', 'TW', 'TWN', 1),
(207, 'Tajikistan', 'TJ', 'TJK', 1),
(208, 'Tanzania, United Republic of', 'TZ', 'TZA', 1),
(209, 'Thailand', 'TH', 'THA', 1),
(210, 'Togo', 'TG', 'TGO', 1),
(211, 'Tokelau', 'TK', 'TKL', 1),
(212, 'Tonga', 'TO', 'TON', 1),
(213, 'Trinidad and Tobago', 'TT', 'TTO', 1),
(214, 'Tunisia', 'TN', 'TUN', 1),
(215, 'Turkey', 'TR', 'TUR', 1),
(216, 'Turkmenistan', 'TM', 'TKM', 1),
(217, 'Turks and Caicos Islands', 'TC', 'TCA', 1),
(218, 'Tuvalu', 'TV', 'TUV', 1),
(219, 'Uganda', 'UG', 'UGA', 1),
(220, 'Ukraine', 'UA', 'UKR', 1),
(221, 'United Arab Emirates', 'AE', 'ARE', 1),
(222, 'United Kingdom', 'GB', 'GBR', 1),
(223, 'United States', 'US', 'USA', 2),
(224, 'United States Minor Outlying Islands', 'UM', 'UMI', 1),
(225, 'Uruguay', 'UY', 'URY', 1),
(226, 'Uzbekistan', 'UZ', 'UZB', 1),
(227, 'Vanuatu', 'VU', 'VUT', 1),
(228, 'Vatican City State (Holy See)', 'VA', 'VAT', 1),
(229, 'Venezuela', 'VE', 'VEN', 1),
(230, 'Viet Nam', 'VN', 'VNM', 1),
(231, 'Virgin Islands (British)', 'VG', 'VGB', 1),
(232, 'Virgin Islands (U.S.)', 'VI', 'VIR', 1),
(233, 'Wallis and Futuna Islands', 'WF', 'WLF', 1),
(234, 'Western Sahara', 'EH', 'ESH', 1),
(235, 'Yemen', 'YE', 'YEM', 1),
(236, 'Yugoslavia', 'YU', 'YUG', 1),
(237, 'Zaire', 'ZR', 'ZAR', 1),
(238, 'Zambia', 'ZM', 'ZMB', 1),
(239, 'Zimbabwe', 'ZW', 'ZWE', 1);

INSERT INTO currencies (currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, `value`, last_updated, currencies_to_stores) VALUES
(1, 'U.S. Dollar', 'USD', '$', '', '.', ',', '2', 1.30050004, '2012-01-04 19:12:49', '@,1'),
(2, 'Euro', 'EUR', '', '�', '.', ',', '2', 1.00000000, '2012-01-04 19:12:50', '@,1');

INSERT INTO customers_groups (customers_group_id, customers_group_name, customers_group_show_tax, customers_group_tax_exempt, group_payment_allowed, group_shipment_allowed, group_order_total_allowed, group_specific_taxes_exempt, customers_group_discount) VALUES
(0, 'Retail', '1', '0', '', '', '', '', '0.00'),
(1, 'Wholesale', '1', '0', '', '', '', '', '0.00');

INSERT INTO database_optimizer (last_update, customers_last_update, orders_last_update, sessions_last_update, user_tracking_last_update) VALUES
('2012-05-06', '2012-05-06', '2012-05-06', '2012-05-06', '2011-01-01');

INSERT INTO discount_categories (discount_categories_id, discount_categories_name) VALUES
(1, 'kortings categorie');

INSERT INTO database_optimizer (last_update, customers_last_update, orders_last_update, sessions_last_update, user_tracking_last_update) VALUES
('2012-05-06', '2012-05-06', '2012-05-06', '2012-05-06', '2011-01-01');

INSERT INTO geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES
(2, 'Netherlands', 'Nederland', NULL, '2011-03-30 20:58:22');

INSERT INTO headertags_social (unique_id, section, groupname, url, data) VALUES
('1','socialicons', 'digg', 'http://digg.com/submit?phase=2&url=URL&TITLE', '16x16'),
('2','socialicons', 'facebook', 'http://www.facebook.com/share.php?u=URL&TITLE', '16x16'),
('3','socialicons', 'google', 'http://www.google.com/bookmarks/mark?op=edit&bkmk=URL&TITLE', '16x16'),
('4','socialicons', 'pintrest', 'http://pinterest.com/pin/create/button/?url=URL&TITLE', '16x16'),
('5','socialicons', 'reddit', 'http://reddit.com/submit?url=URL&TITLE', '16x16'),
('6', 'socialicons', 'google+', 'https://plus.google.com/share?url=URL&TITLE', '16x16'),
('7', 'socialicons', 'linkedin', 'http://www.linkedin.com/shareArticle?mini=true&url=&title=TITLE=&source=URL', '16x16'),
('8', 'socialicons', 'newsvine', 'http://www.newsvine.com/_tools/seed&amp;save?u=URL&h=TITLE', '16x16'),
('9', 'socialicons', 'stumbleupon', 'http://www.stumbleupon.com/submit?url=URL&TITLE', '16x16'),
('10', 'socialicons', 'twitter', 'http://twitter.com/home?status=URL&TITLE', '16x16');


INSERT INTO information (information_id, information_group_id, information_title, information_description, parent_id, sort_order, visible, language_id) VALUES
(8, 1, 'Delivery', '<p>\r\n	information in english</p>', 0, 1, '1', 3),
(8, 1, 'Verzenden', '<p>\r\n	informatie over het verzenden</p>', 0, 1, '1', 2);

INSERT INTO information_group (information_group_id, information_group_title, information_group_description, sort_order, visible, locked) VALUES
(1, 'Information pages', 'Information pages', 1, 1, '');

INSERT INTO languages (languages_id, languages_to_stores, `name`, code, image, `directory`, sort_order) VALUES
(3, '@,1', 'English', 'en', 'icon.gif', 'english', 5),
(2, '@,1', 'dutch', 'nl', 'icon.gif', 'dutch', 1);

INSERT INTO manufacturers (manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified) VALUES
(1, 'Matrox', 'manufacturers/manufacturer_matrox.gif', '2010-12-26 18:53:09', NULL),
(2, 'Microsoft', 'manufacturers/manufacturer_microsoft.gif', '2010-12-26 18:53:09', NULL),
(3, 'Warner', 'manufacturers/manufacturer_warner.gif', '2010-12-26 18:53:09', NULL),
(4, 'Fox', 'manufacturers/manufacturer_fox.gif', '2010-12-26 18:53:09', NULL),
(5, 'Logitech', 'manufacturers/manufacturer_logitech.gif', '2010-12-26 18:53:09', NULL),
(6, 'Canon', 'manufacturers/manufacturer_canon.gif', '2010-12-26 18:53:09', NULL),
(7, 'Sierra', 'manufacturers/manufacturer_sierra.gif', '2010-12-26 18:53:09', NULL),
(8, 'GT Interactive', 'manufacturers/manufacturer_gt_interactive.gif', '2010-12-26 18:53:09', NULL),
(9, 'Hewlett Packard', 'manufacturers/manufacturer_hewlett_packard.gif', '2010-12-26 18:53:09', NULL),
(10, 'Samsung', 'manufacturers/manufacturer_samsung.png', '2010-12-26 18:53:09', NULL);

INSERT INTO manufacturers_info (manufacturers_id, languages_id, manufacturers_url, url_clicked, date_last_click, manufacturers_htc_title_tag, manufacturers_htc_desc_tag, manufacturers_htc_keywords_tag, manufacturers_htc_description, manufacturers_to_stores) VALUES
(9, 3, 'http://www.hewlettpackard.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(8, 3, 'http://www.infogrames.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(7, 3, 'http://www.sierra.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(6, 3, 'http://www.canon.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(5, 3, 'http://www.logitech.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(4, 3, 'http://www.fox.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(3, 3, 'http://www.warner.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(2, 3, 'http://www.microsoft.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(1, 3, 'http://www.matrox.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(1, 2, 'http://www.matrox.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(2, 2, 'http://www.microsoft.com', 1, '2011-08-06 22:17:47', NULL, NULL, NULL, NULL, '@,1'),
(3, 2, 'http://www.warner.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(4, 2, 'http://www.fox.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(5, 2, 'http://www.logitech.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(6, 2, 'http://www.canon.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(7, 2, 'http://www.sierra.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(8, 2, 'http://www.infogrames.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(9, 2, 'http://www.hewlettpackard.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(10, 2, 'http://www.samsung.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1'),
(10, 3, 'http://www.samsung.com', 0, NULL, NULL, NULL, NULL, NULL, '@,1');

INSERT INTO orders_status (orders_status_id, language_id, orders_status_name, public_flag, downloads_flag) VALUES
(4, 3, 'PayPal [Transactions]', 1, 0),
(3, 3, 'Delivered', 1, 1),
(2, 3, 'Processing', 1, 0),
(1, 3, 'Pending', 1, 0),
(1, 2, 'in behandeling', 1, 0),
(2, 2, 'verwerken', 1, 0),
(3, 2, 'verzonden', 1, 1),
(4, 2, 'PayPal [betalingen]', 1, 0),
(5, 2, 'Preparing [WorldPay]', 0, 0),
(5, 3, 'Preparing [WorldPay]', 0, 0),
(6, 2, 'Sofortüberweisung Vorbereitung', 0, 0),
(6, 3, 'Sofortüberweisung Vorbereitung', 0, 0);

INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_cost, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered, products_qty_blocks, products_sort_order, payment_methods, products_min_order_qty, products_to_stores, products_instock_id, products_nostock_id) VALUES
(2, 12, 'MG400-32MB', 'products/mg400-32mb.gif', '499.9900', '0.0000', '2010-12-26 18:53:09', '2012-07-26 20:47:09', NULL, '23.00', 1, 2, 1, 5, 1, 10, '', 1, '@,1', '1', '2'),
(3, -3, 'MSIMPRO', 'products/msimpro.gif', '49.9900', '0.0000', '2010-12-26 18:53:09', '2011-08-06 23:35:13', NULL, '7.00', 1, 2, 2, 5, 1, 999, '', 1, '@,1', NULL, NULL),
(4, 13, 'DVD-RPMK', 'products/replacement_killers.gif', '42.0000', '0.0000', '2010-12-26 18:53:09', NULL, NULL, '23.00', 1, 2, 2, 0, 1, 15, NULL, 1, '@,0', NULL, NULL),
(5, 12, 'DVD-BLDRNDC', 'products/blade_runner.gif', '35.9900', '0.0000', '2010-12-26 18:53:09', '2012-06-25 23:18:09', NULL, '7.00', 1, 2, 3, 5, 1, 999, '', 1, '@,1', '', ''),
(7, 9, 'DVD-YGEM', 'products/youve_got_mail.gif', '34.9900', '0.0000', '2010-12-26 18:53:09', '2011-12-17 20:46:40', NULL, '7.00', 1, 2, 3, 1, 1, 999, '', 1, '@,1', NULL, NULL),
(8, 5, 'DVD-ABUG', 'products/a_bugs_life.gif', '35.9900', '0.0000', '2010-12-26 18:53:09', '2012-07-25 20:36:59', '2012-08-01 00:00:00', '7.00', 1, 2, 3, 5, 1, 999, '', 1, '@,1', '1', '2'),
(9, 10, 'DVD-UNSG', 'products/under_siege.gif', '29.9900', '0.0000', '2010-12-26 18:53:09', '2012-07-05 19:55:22', NULL, '7.00', 1, 2, 3, 0, 1, 55, '', 1, '@,1', '', ''),
(10, 10, 'DVD-UNSG2', 'products/under_siege2.gif', '29.9900', '0.0000', '2010-12-26 18:53:09', '2012-07-05 19:55:00', NULL, '7.00', 1, 2, 3, 0, 1, 10, '', 1, '@,1', '', ''),
(11, 9, 'DVD-FDBL', 'products/fire_down_below.gif', '29.9900', '0.0000', '2010-12-26 18:53:09', NULL, NULL, '7.00', 1, 2, 3, 1, 1, 999, NULL, 1, '@,1', NULL, NULL),
(12, 9, 'DVD-DHWV', 'products/die_hard_3.gif', '39.9900', '0.0000', '2010-12-26 18:53:09', NULL, NULL, '7.00', 1, 2, 4, 1, 1, 999, NULL, 1, '@,1', NULL, NULL),
(13, 10, 'DVD-LTWP', 'products/lethal_weapon.gif', '34.9900', '0.0000', '2010-12-26 18:53:09', NULL, NULL, '7.00', 1, 2, 3, 0, 1, 50, NULL, 1, '@,1', NULL, NULL),
(14, 10, 'DVD-REDC', 'products/red_corner.gif', '32.0000', '0.0000', '2010-12-26 18:53:09', NULL, NULL, '7.00', 1, 2, 3, 0, 1, 999, NULL, 1, '@,1', NULL, NULL),
(15, 10, 'DVD-FRAN', 'products/frantic.gif', '35.0000', '0.0000', '2010-12-26 18:53:09', '2011-12-20 20:30:47', NULL, '7.00', 1, 2, 3, 0, 1, 999, '', 1, '@,1', NULL, NULL),
(16, 5, 'DVD-CUFI', 'products/courage_under_fire.gif', '38.9900', '0.0000', '2010-12-26 18:53:09', '2011-12-18 21:07:27', NULL, '7.00', 1, 2, 4, 11, 1, 999, '', 1, '@,1', NULL, NULL),
(17, 10, 'DVD-SPEED', 'products/speed.gif', '39.9900', '0.0000', '2010-12-26 18:53:09', '2011-12-16 20:29:41', NULL, '7.00', 1, 2, 4, 0, 1, 999, '', 1, '@,1', NULL, NULL),
(18, 8, 'DVD-SPEED2', 'products/speed_2.gif', '42.0000', '0.0000', '2010-12-26 18:53:09', '2011-12-31 15:51:22', NULL, '7.00', 1, 2, 4, 0, 1, 999, '', 1, '@,1', NULL, NULL),
(19, 10, 'DVD-TSAB', 'products/theres_something_about_mary.gif', '49.9900', '0.0000', '2010-12-26 18:53:09', NULL, NULL, '7.00', 1, 2, 4, 0, 1, 999, NULL, 1, '@,1', NULL, NULL),
(20, 5, 'DVD-BELOVED', 'products/beloved.gif', '54.9900', '0.0000', '2010-12-26 18:53:09', '2012-06-30 21:50:29', NULL, '7.00', 1, 2, 3, 7, 1, 999, '', 1, '@,1', '', ''),
(21, 6, 'PC-SWAT3', 'products/swat_3.gif', '79.9900', '0.0000', '2010-12-26 18:53:09', '2012-06-15 19:51:05', NULL, '7.00', 1, 2, 7, 0, 1, 999, '', 1, '@,1', '1', '2'),
(22, 0, 'PC-UNTM', 'products/unreal_tournament.gif', '90.0000', '0.0000', '2010-12-26 18:53:09', '2011-12-16 22:34:16', NULL, '7.00', 1, 2, 8, 5, 1, 999, '', 1, '@,1', NULL, NULL),
(23, 15, 'PC-TWOF', 'products/wheel_of_time.gif', '99.9900', '0.0000', '2010-12-26 18:53:09', '2011-12-16 21:26:46', NULL, '10.00', 1, 2, 8, 1, 1, 999, '', 1, '@,1', NULL, NULL),
(24, 17, 'PC-DISC', 'products/disciples.gif', '90.0000', '0.0000', '2010-12-26 18:53:09', '2011-12-31 22:33:29', NULL, '8.00', 1, 2, 8, 0, 1, 999, '', 1, '@,1', NULL, NULL),
(25, 16, 'MSINTKB', 'products/intkeyboardps2.gif', '69.9900', '0.0000', '2010-12-26 18:53:09', NULL, NULL, '8.00', 1, 2, 2, 0, 1, 999, NULL, 1, '@,1', NULL, NULL),
(26, 0, 'MSIMEXP', 'products/imexplorer.gif', '64.9500', '0.0000', '2010-12-26 18:53:09', '2012-06-25 22:59:32', NULL, '8.00', 1, 2, 2, 0, 1, 999, '', 1, '@,1', '', ''),
(27, 1, 'HPLJ1100XI', 'products/lj1100xi.gif', '499.9900', '0.0000', '2010-12-26 18:53:09', '2011-12-20 21:30:52', NULL, '45.00', 1, 2, 9, 7, 1, 999, '', 1, '@,1', NULL, NULL),
(28, 23, 'GT-P1000', 'products/galaxy_tab.gif', '200.0000', '450.0000', '2010-12-26 18:53:09', '2012-07-26 20:49:00', NULL, '1.00', 1, 2, 10, 65, 1, 999, 'cod;cop;moneyorder;ar_reset_password', 3, '@,1', '1', '2'),
(38, 12, 'MG400-32MB', 'products/mg400-32mb.gif', '525.2101', '0.0000', '2012-05-17 20:24:50', '2012-05-17 20:44:01', '0000-00-00 00:00:00', '23.00', 1, 2, 1, 0, 1, 10, '', 1, '@,1', NULL, NULL);

INSERT INTO products_attributes (products_attributes_id, products_id, options_id, options_values_id, options_values_price, price_prefix, products_options_sort_order, attributes_hide_from_groups) VALUES
(77, 8, 11, 18, '10.0000', '+', 0, '@,0,1'),
(78, 8, 11, 17, '0.0000', '+', 0, '@,0,1'),
(105, 28, 4, 1, '0.0000', '', 2, '@'),
(89, 2, 6, 0, '0.0000', '+', 0, '@'),
(10, 26, 3, 8, '0.0000', '+', 1, '@'),
(11, 26, 3, 9, '6.0000', '+', 2, '@'),
(26, 22, 5, 10, '0.0000', '+', 1, '@'),
(27, 22, 5, 13, '0.0000', '+', 2, '@'),
(79, 8, 11, 16, '0.0000', '+', 0, '@,0,1'),
(109, 28, 4, 2, '15.0000', '+', 3, '@'),
(108, 28, 3, 7, '0.0000', '', 0, '@'),
(107, 28, 3, 6, '0.0000', '', 1, '@');

INSERT INTO products_attributes_download (products_attributes_id, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount) VALUES
(26, 'unreal.zip', 7, 3);

INSERT INTO products_availability (products_availability_id, language_id, products_availability_name, products_availability_image, date_added, last_modified) VALUES
(1, 2, 'Op Voorraad', 'green.png', NULL, NULL),
(1, 3, 'In Stock', 'green.png', NULL, NULL),
(2, 2, 'Niet op Voorraad', 'red.png', NULL, NULL),
(2, 3, 'Out Of Stock', 'red.png', NULL, NULL);

INSERT INTO products_description (products_id, language_id, products_name, products_description, products_url, products_viewed, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_head_listing_text, products_head_sub_text) VALUES
(28, 3, 'Samsung Galaxy Tab', '<p>\r\n	de Samsung GALAXY Tab in the english</p>', 'http://galaxytab.samsungmobile.com', 19, 'Samsung Galaxy Tab english', 'Samsung Galaxy Tab oms english', 'Samsung Galaxy Tab,keyword, english', '', ''),
(27, 3, 'Hewlett Packard LaserJet 1100Xi', '<p>\r\n	HP has always set the pace in laser printing technology. The new generation HP LaserJet 1100 series sets another impressive pace, delivering a stunning 8 pages per minute print speed. The 600 dpi print resolution with HP&#39;s Resolution Enhancement technology (REt) makes every document more professional.<br />\r\n	<br />\r\n	Enhanced print speed and laser quality results are just the beginning. With 2MB standard memory, HP LaserJet 1100xi users will be able to print increasingly complex pages. Memory can be increased to 18MB to tackle even more complex documents with ease. The HP LaserJet 1100xi supports key operating systems including Windows 3.1, 3.11, 95, 98, NT 4.0, OS/2 and DOS. Network compatibility available via the optional HP JetDirect External Print Servers.<br />\r\n	<br />\r\n	HP LaserJet 1100xi also features The Document Builder for the Web Era from Trellix Corp. (featuring software to create Web documents).</p>', 'www.pandi.hp.com/pandi-db/prodinfo.main?product=laserjet1100', 0, 'Hewlett Packard LaserJet 1100Xi', 'Hewlett Packard LaserJet 1100Xi', 'Hewlett Packard LaserJet 1100Xi', '', ''),
(26, 3, 'Microsoft IntelliMouse Explorer', '<p>\r\n	Microsoft introduces its most advanced mouse, the IntelliMouse Explorer! IntelliMouse Explorer features a sleek design, an industrial-silver finish, a glowing red underside and taillight, creating a style and look unlike any other mouse. IntelliMouse Explorer combines the accuracy and reliability of Microsoft IntelliEye optical tracking technology, the convenience of two new customizable function buttons, the efficiency of the scrolling wheel and the comfort of expert ergonomic design. All these great features make this the best mouse for the PC!</p>', 'www.microsoft.com/hardware/mouse/explorer.asp', 1, 'Microsoft IntelliMouse Explorer', 'Microsoft IntelliMouse Explorer', 'Microsoft IntelliMouse Explorer', '', ''),
(25, 3, 'Microsoft Internet Keyboard PS/2', 'The Internet Keyboard has 10 Hot Keys on a comfortable standard keyboard design that also includes a detachable palm rest. The Hot Keys allow you to browse the web, or check e-mail directly from your keyboard. The IntelliType Pro software also allows you to customize your hot keys - make the Internet Keyboard work the way you want it to!', '', 2, 'Microsoft Internet Keyboard PS/2', 'Microsoft Internet Keyboard PS/2', 'Microsoft Internet Keyboard PS/2', '', ''),
(24, 3, 'Disciples: Sacred Lands', '<p>\r\n	A new age is dawning...<br />\r\n	<br />\r\n	Enter the realm of the Sacred Lands, where the dawn of a New Age has set in motion the most momentous of wars. As the prophecies long foretold, four races now clash with swords and sorcery in a desperate bid to control the destiny of their gods. Take on the quest as a champion of the Empire, the Mountain Clans, the Legions of the Damned, or the Undead Hordes and test your faith in battles of brute force, spellbinding magic and acts of guile. Slay demons, vanquish giants and combat merciless forces of the dead and undead. But to ensure the salvation of your god, the hero within must evolve.<br />\r\n	<br />\r\n	The day of reckoning has come... and only the chosen will survive.</p>', '', 0, 'Disciples: Sacred Lands', 'Disciples: Sacred Lands', 'Disciples: Sacred Lands', '', ''),
(23, 3, 'The Wheel Of Time', '<p>\r\n	The world in which The Wheel of Time takes place is lifted directly out of Jordan&#39;s pages; it&#39;s huge and consists of many different environments. How you navigate the world will depend largely on which game - single player or multipayer - you&#39;re playing. The single player experience, with a few exceptions, will see Elayna traversing the world mainly by foot (with a couple notable exceptions). In the multiplayer experience, your character will have more access to travel via Ter&#39;angreal, Portal Stones, and the Ways. However you move around, though, you&#39;ll quickly discover that means of locomotion can easily become the least of the your worries...<br />\r\n	<br />\r\n	During your travels, you quickly discover that four locations are crucial to your success in the game. Not surprisingly, these locations are the homes of The Wheel of Time&#39;s main characters. Some of these places are ripped directly from the pages of Jordan&#39;s books, made flesh with Legend&#39;s unparalleled pixel-pushing ways. Other places are specific to the game, conceived and executed with the intent of expanding this game world even further. Either way, they provide a backdrop for some of the most intense first person action and strategy you&#39;ll have this year.</p>', 'www.wheeloftime.com', 3, 'The Wheel Of Time', 'The Wheel Of Time', 'The Wheel Of Time', '', ''),
(22, 3, 'Unreal Tournament', '<p>\r\n	From the creators of the best-selling Unreal, comes Unreal Tournament. A new kind of single player experience. A ruthless multiplayer revolution.<br />\r\n	<br />\r\n	This stand-alone game showcases completely new team-based gameplay, groundbreaking multi-faceted single player action or dynamic multi-player mayhem. It&#39;s a fight to the finish for the title of Unreal Grand Master in the gladiatorial arena. A single player experience like no other! Guide your team of &#39;bots&#39; (virtual teamates) against the hardest criminals in the galaxy for the ultimate title - the Unreal Grand Master.</p>', 'www.unrealtournament.net', 0, 'Unreal Tournament', 'Unreal Tournament', 'Unreal Tournament', '', ''),
(21, 3, 'SWAT 3: Close Quarters Battle', '<p>\r\n	<strong>Windows 95/98</strong><br />\r\n	<br />\r\n	211 in progress with shots fired. Officer down. Armed suspects with hostages. Respond Code 3! Los Angles, 2005, In the next seven days, representatives from every nation around the world will converge on Las Angles to witness the signing of the United Nations Nuclear Abolishment Treaty. The protection of these dignitaries falls on the shoulders of one organization, LAPD SWAT. As part of this elite tactical organization, you and your team have the weapons and all the training necessary to protect, to serve, and &quot;When needed&quot; to use deadly force to keep the peace. It takes more than weapons to make it through each mission. Your arsenal includes C2 charges, flashbangs, tactical grenades. opti-Wand mini-video cameras, and other devices critical to meeting your objectives and keeping your men free of injury. Uncompromised Duty, Honor and Valor!</p>', 'www.swat3.com', 3, 'SWAT 3: Close Quarters Battle', 'SWAT 3: Close Quarters Battle', 'SWAT 3: Close Quarters Battle', '', ''),
(20, 3, 'Beloved', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 164 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 0, 'Beloved', 'Beloved', 'Beloved', '', ''),
(19, 3, 'There''s Something About Mary', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br />\nLanguages: English, Deutsch.\r<br />\nSubtitles: English, Deutsch, Spanish.\r<br />\nAudio: Dolby Surround 5.1.\r<br />\nPicture Format: 16:9 Wide-Screen.\r<br />\nLength: (approx) 114 minutes.\r<br />\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', '', 1, 'There''s Something About Mary', 'There''s Something About Mary', 'There''s Something About Mary', '', ''),
(18, 3, 'Speed 2: Cruise Control', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 120 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 0, 'Speed 2: Cruise Control', 'Speed 2: Cruise Control', 'Speed 2: Cruise Control', '', ''),
(17, 3, 'Speed', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 112 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 0, 'Speed', 'Speed', 'Speed', '', ''),
(16, 3, 'Courage Under Fire', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 112 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 0, 'Courage Under Fire', 'Courage Under Fire', 'Courage Under Fire', '', ''),
(15, 3, 'Frantic', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 115 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 0, 'Frantic', 'Frantic', 'Frantic', '', ''),
(13, 3, 'Lethal Weapon', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br />\nLanguages: English, Deutsch.\r<br />\nSubtitles: English, Deutsch, Spanish.\r<br />\nAudio: Dolby Surround 5.1.\r<br />\nPicture Format: 16:9 Wide-Screen.\r<br />\nLength: (approx) 100 minutes.\r<br />\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', '', 1, 'Lethal Weapon', 'Lethal Weapon', 'Lethal Weapon', '', ''),
(14, 3, 'Red Corner', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br />\nLanguages: English, Deutsch.\r<br />\nSubtitles: English, Deutsch, Spanish.\r<br />\nAudio: Dolby Surround 5.1.\r<br />\nPicture Format: 16:9 Wide-Screen.\r<br />\nLength: (approx) 117 minutes.\r<br />\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', '', 0, 'Red Corner', 'Red Corner', 'Red Corner', '', ''),
(11, 3, 'Fire Down Below', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br />\nLanguages: English, Deutsch.\r<br />\nSubtitles: English, Deutsch, Spanish.\r<br />\nAudio: Dolby Surround 5.1.\r<br />\nPicture Format: 16:9 Wide-Screen.\r<br />\nLength: (approx) 100 minutes.\r<br />\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', '', 1, 'Fire Down Below', 'Fire Down Below', 'Fire Down Below', '', ''),
(12, 3, 'Die Hard With A Vengeance', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br />\nLanguages: English, Deutsch.\r<br />\nSubtitles: English, Deutsch, Spanish.\r<br />\nAudio: Dolby Surround 5.1.\r<br />\nPicture Format: 16:9 Wide-Screen.\r<br />\nLength: (approx) 122 minutes.\r<br />\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', '', 1, 'Die Hard With A Vengeance', 'Die Hard With A Vengeance', 'Die Hard With A Vengeance', '', ''),
(8, 3, 'A Bug''s Life', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Digital 5.1 / Dobly Surround Stereo.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 91 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', 'www.abugslife.com', 10, 'A Bug''s Life', 'A Bug''s Life', 'A Bug''s Life', '', ''),
(9, 3, 'Under Siege', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 98 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 1, 'Under Siege', 'Under Siege', 'Under Siege', '', ''),
(10, 3, 'Under Siege 2 - Dark Territory', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 98 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 0, 'Under Siege 2 - Dark Territory', 'Under Siege 2 - Dark Territory', 'Under Siege 2 - Dark Territory', '', ''),
(7, 3, 'You''ve Got Mail', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch, Spanish.<br />\r\n	Subtitles: English, Deutsch, Spanish, French, Nordic, Polish.<br />\r\n	Audio: Dolby Digital 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 115 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', 'www.youvegotmail.com', 0, 'You''ve Got Mail', 'You''ve Got Mail', 'You''ve Got Mail', '', ''),
(5, 3, 'Blade Runner - Director''s Cut', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 112 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', 'www.bladerunner.com', 5, 'Blade Runner - Director''s Cut', 'Blade Runner - Director''s Cut', 'Blade Runner - Director''s Cut', '', ''),
(4, 3, 'The Replacement Killers', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />Languages: English, Deutsch.<br />Subtitles: English, Deutsch, Spanish.<br />Audio: Dolby Surround 5.1.<br />Picture Format: 16:9 Wide-Screen.<br />Length: (approx) 80 minutes.<br />Other: Interactive Menus, Chapter Selection, Subtitles (more languages).', 'www.replacement-killers.com', 1, 'The Replacement Killers', 'The Replacement Killers', 'The Replacement Killers', '', ''),
(3, 3, 'Microsoft IntelliMouse Pro', '<p>\r\n	Every element of IntelliMouse Pro - from its unique arched shape to the texture of the rubber grip around its base - is the product of extensive customer and ergonomic research. Microsoft&#39;s popular wheel control, which now allows zooming and universal scrolling functions, gives IntelliMouse Pro outstanding comfort and efficiency.</p>', 'www.microsoft.com/hardware/mouse/intellimouse.asp', 4, 'Microsoft IntelliMouse Pro', 'Microsoft IntelliMouse Pro', 'Microsoft IntelliMouse Pro', '', ''),
(2, 3, 'Matrox G400 32MB', '<p>\r\n	<strong>Dramatically Different High Performance Graphics</strong><br />\r\n	<br />\r\n	Introducing the Millennium G400 Series - a dramatically different, high performance graphics experience. Armed with the industry&#39;s fastest graphics chip, the Millennium G400 Series takes explosive acceleration two steps further by adding unprecedented image quality, along with the most versatile display options for all your 3D, 2D and DVD applications. As the most powerful and innovative tools in your PC&#39;s arsenal, the Millennium G400 Series will not only change the way you see graphics, but will revolutionize the way you use your computer.<br />\r\n	<br />\r\n	<strong>Key features:</strong></p>\r\n<ul>\r\n	<li>\r\n		New Matrox G400 256-bit DualBus graphics chip</li>\r\n	<li>\r\n		Explosive 3D, 2D and DVD performance</li>\r\n	<li>\r\n		DualHead Display</li>\r\n	<li>\r\n		Superior DVD and TV output</li>\r\n	<li>\r\n		3D Environment-Mapped Bump Mapping</li>\r\n	<li>\r\n		Vibrant Color Quality rendering</li>\r\n	<li>\r\n		UltraSharp DAC of up to 360 MHz</li>\r\n	<li>\r\n		3D Rendering Array Processor</li>\r\n	<li>\r\n		Support for 16 or 32 MB of memory</li>\r\n</ul>', 'www.matrox.com/mga/products/mill_g400/home.htm', 27, 'Matrox G400 32MB', 'Matrox G400 32MB', 'Matrox G400 32MB', 'product listing text nederlands', 'extra text bottom of product info page'),
(37, 2, 'cd rom drive', '<p>\r\n	test cd rom</p>', '', 67, 'cd rom drive', 'cd rom drive', 'cd rom drive', '', ''),
(37, 3, '', '<p>\r\n	dfghdfgh</p>', '', 0, '', '', '', '', ''),
(2, 2, 'Retail Matrox G400 32MB', '<p>\r\n	<strong>Dramatically Different High Performance Graphics</strong><br />\r\n	<br />\r\n	Introducing the Millennium G400 Series - a dramatically different, high performance graphics experience. Armed with the industry&#39;s fastest graphics chip, the Millennium G400 Series takes explosive acceleration two steps further by adding unprecedented image quality, along with the most versatile display options for all your 3D, 2D and DVD applications. As the most powerful and innovative tools in your PC&#39;s arsenal, the Millennium G400 Series will not only change the way you see graphics, but will revolutionize the way you use your computer.<br />\r\n	<br />\r\n	<strong>Key features:</strong></p>\r\n<ul>\r\n	<li>\r\n		New Matrox G400 256-bit DualBus graphics chip</li>\r\n	<li>\r\n		Explosive 3D, 2D and DVD performance</li>\r\n	<li>\r\n		DualHead Display</li>\r\n	<li>\r\n		Superior DVD and TV output</li>\r\n	<li>\r\n		3D Environment-Mapped Bump Mapping</li>\r\n	<li>\r\n		Vibrant Color Quality rendering</li>\r\n	<li>\r\n		UltraSharp DAC of up to 360 MHz</li>\r\n	<li>\r\n		3D Rendering Array Processor</li>\r\n	<li>\r\n		Support for 16 or 32 MB of memory</li>\r\n</ul>', 'www.matrox.com/mga/products/mill_g400/home.htm', 468, 'Matrox G400 32MB ned', 'Matrox G400 32MB ned', 'Matrox G400 32MB ned', 'product listing text nederlands', 'extra text onderkant product pagina'),
(3, 2, 'Microsoft IntelliMouse Pro', '<p>\r\n	Every element of IntelliMouse Pro - from its unique arched shape to the texture of the rubber grip around its base - is the product of extensive customer and ergonomic research. Microsoft&#39;s popular wheel control, which now allows zooming and universal scrolling functions, gives IntelliMouse Pro outstanding comfort and efficiency.</p>', 'www.microsoft.com/hardware/mouse/intellimouse.asp', 7, 'Microsoft IntelliMouse Pro', 'Microsoft IntelliMouse Pro', 'Microsoft IntelliMouse Pro', '', ''),
(4, 2, 'The Replacement Killers', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />Languages: English, Deutsch.<br />Subtitles: English, Deutsch, Spanish.<br />Audio: Dolby Surround 5.1.<br />Picture Format: 16:9 Wide-Screen.<br />Length: (approx) 80 minutes.<br />Other: Interactive Menus, Chapter Selection, Subtitles (more languages).', 'www.replacement-killers.com', 7, 'The Replacement Killers', 'The Replacement Killers', 'The Replacement Killers', '', ''),
(5, 2, 'Blade Runner - Director''s Cut', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 112 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', 'www.bladerunner.com', 69, 'Blade Runner - Director''s Cut', 'Blade Runner - Director''s Cut', 'Blade Runner - Director''s Cut', '', ''),
(7, 2, 'You''ve Got Mail', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch, Spanish.<br />\r\n	Subtitles: English, Deutsch, Spanish, French, Nordic, Polish.<br />\r\n	Audio: Dolby Digital 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 115 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', 'www.youvegotmail.com', 13, 'You''ve Got Mail', 'You''ve Got Mail', 'You''ve Got Mail', '', ''),
(8, 2, 'A Bug''s Life', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Digital 5.1 / Dobly Surround Stereo.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 91 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).<img alt="" src="/images/nv_japanspachtel.jpg" style="width: 219px; height: 244px;" /></p>', 'www.abugslife.com', 156, 'A Bug''s Life', 'A Bug''s Life', 'A Bug''s Life', '', ''),
(9, 2, 'Under Siege', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 98 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 4, 'Under Siege', 'Under Siege', 'Under Siege', '', ''),
(10, 2, 'Under Siege 2 - Dark Territory', '<p>\r\n	omschrijving nederlands</p>', '', 7, 'Under Siege 2 - Dark Territory', 'Under Siege 2 - Dark Territory', 'Under Siege 2 - Dark Territory', '', ''),
(11, 2, 'Fire Down Below', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br />\nLanguages: English, Deutsch.\r<br />\nSubtitles: English, Deutsch, Spanish.\r<br />\nAudio: Dolby Surround 5.1.\r<br />\nPicture Format: 16:9 Wide-Screen.\r<br />\nLength: (approx) 100 minutes.\r<br />\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', '', 6, 'Fire Down Below', 'Fire Down Below', 'Fire Down Below', '', ''),
(12, 2, 'Die Hard With A Vengeance', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br />\nLanguages: English, Deutsch.\r<br />\nSubtitles: English, Deutsch, Spanish.\r<br />\nAudio: Dolby Surround 5.1.\r<br />\nPicture Format: 16:9 Wide-Screen.\r<br />\nLength: (approx) 122 minutes.\r<br />\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', '', 4, 'Die Hard With A Vengeance', 'Die Hard With A Vengeance', 'Die Hard With A Vengeance', '', ''),
(13, 2, 'Lethal Weapon', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br />\nLanguages: English, Deutsch.\r<br />\nSubtitles: English, Deutsch, Spanish.\r<br />\nAudio: Dolby Surround 5.1.\r<br />\nPicture Format: 16:9 Wide-Screen.\r<br />\nLength: (approx) 100 minutes.\r<br />\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', '', 4, 'Lethal Weapon', 'Lethal Weapon', 'Lethal Weapon', '', ''),
(14, 2, 'Red Corner', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br />\nLanguages: English, Deutsch.\r<br />\nSubtitles: English, Deutsch, Spanish.\r<br />\nAudio: Dolby Surround 5.1.\r<br />\nPicture Format: 16:9 Wide-Screen.\r<br />\nLength: (approx) 117 minutes.\r<br />\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', '', 1, 'Red Corner', 'Red Corner', 'Red Corner', '', ''),
(15, 2, 'Frantic', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 115 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 10, 'Frantic', 'Frantic', 'Frantic', '', ''),
(16, 2, 'Courage Under Fire', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 112 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 10, 'Courage Under Fire', 'Courage Under Fire', 'Courage Under Fire', '', ''),
(17, 2, 'Speed', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 112 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 18, 'Speed', 'Speed', 'Speed', '', ''),
(18, 2, 'Speed 2: Cruise Control', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 120 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 18, 'Speed 2: Cruise Control', 'Speed 2: Cruise Control', 'Speed 2: Cruise Control', '', ''),
(19, 2, 'There''s Something About Mary', 'Regional Code: 2 (Japan, Europe, Middle East, South Africa).\r<br />\nLanguages: English, Deutsch.\r<br />\nSubtitles: English, Deutsch, Spanish.\r<br />\nAudio: Dolby Surround 5.1.\r<br />\nPicture Format: 16:9 Wide-Screen.\r<br />\nLength: (approx) 114 minutes.\r<br />\nOther: Interactive Menus, Chapter Selection, Subtitles (more languages).', '', 9, 'There''s Something About Mary', 'There''s Something About Mary', 'There''s Something About Mary', '', ''),
(20, 2, 'Beloved', '<p>\r\n	Regional Code: 2 (Japan, Europe, Middle East, South Africa).<br />\r\n	Languages: English, Deutsch.<br />\r\n	Subtitles: English, Deutsch, Spanish.<br />\r\n	Audio: Dolby Surround 5.1.<br />\r\n	Picture Format: 16:9 Wide-Screen.<br />\r\n	Length: (approx) 164 minutes.<br />\r\n	Other: Interactive Menus, Chapter Selection, Subtitles (more languages).</p>', '', 49, 'Beloved', 'Beloved', 'Beloved', '', ''),
(21, 2, 'SWAT 3: Close Quarters Battle', '<p>\r\n	<strong>Windows 95/98</strong><br />\r\n	<br />\r\n	211 in progress with shots fired. Officer down. Armed suspects with hostages. Respond Code 3! Los Angles, 2005, In the next seven days, representatives from every nation around the world will converge on Las Angles to witness the signing of the United Nations Nuclear Abolishment Treaty. The protection of these dignitaries falls on the shoulders of one organization, LAPD SWAT. As part of this elite tactical organization, you and your team have the weapons and all the training necessary to protect, to serve, and &quot;When needed&quot; to use deadly force to keep the peace. It takes more than weapons to make it through each mission. Your arsenal includes C2 charges, flashbangs, tactical grenades. opti-Wand mini-video cameras, and other devices critical to meeting your objectives and keeping your men free of injury. Uncompromised Duty, Honor and Valor!</p>', 'www.swat3.com', 185, 'SWAT 3: Close Quarters Battle', 'SWAT 3: Close Quarters Battle', 'SWAT 3: Close Quarters Battle', '', ''),
(22, 2, 'Unreal Tournament', '<p>\r\n	From the creators of the best-selling Unreal, comes Unreal Tournament. A new kind of single player experience. A ruthless multiplayer revolution.<br />\r\n	<br />\r\n	This stand-alone game showcases completely new team-based gameplay, groundbreaking multi-faceted single player action or dynamic multi-player mayhem. It&#39;s a fight to the finish for the title of Unreal Grand Master in the gladiatorial arena. A single player experience like no other! Guide your team of &#39;bots&#39; (virtual teamates) against the hardest criminals in the galaxy for the ultimate title - the Unreal Grand Master.</p>', 'www.unrealtournament.net', 7, 'Unreal Tournament', 'Unreal Tournament', 'Unreal Tournament', '', ''),
(23, 2, 'The Wheel Of Time', '<p>\r\n	The world in which The Wheel of Time takes place is lifted directly out of Jordan&#39;s pages; it&#39;s huge and consists of many different environments. How you navigate the world will depend largely on which game - single player or multipayer - you&#39;re playing. The single player experience, with a few exceptions, will see Elayna traversing the world mainly by foot (with a couple notable exceptions). In the multiplayer experience, your character will have more access to travel via Ter&#39;angreal, Portal Stones, and the Ways. However you move around, though, you&#39;ll quickly discover that means of locomotion can easily become the least of the your worries...<br />\r\n	<br />\r\n	During your travels, you quickly discover that four locations are crucial to your success in the game. Not surprisingly, these locations are the homes of The Wheel of Time&#39;s main characters. Some of these places are ripped directly from the pages of Jordan&#39;s books, made flesh with Legend&#39;s unparalleled pixel-pushing ways. Other places are specific to the game, conceived and executed with the intent of expanding this game world even further. Either way, they provide a backdrop for some of the most intense first person action and strategy you&#39;ll have this year.</p>', 'www.wheeloftime.com', 30, 'The Wheel Of Time', 'The Wheel Of Time', 'The Wheel Of Time', '', ''),
(24, 2, 'Disciples: Sacred Lands', '<p>\r\n	A new age is dawning...<br />\r\n	<br />\r\n	Enter the realm of the Sacred Lands, where the dawn of a New Age has set in motion the most momentous of wars. As the prophecies long foretold, four races now clash with swords and sorcery in a desperate bid to control the destiny of their gods. Take on the quest as a champion of the Empire, the Mountain Clans, the Legions of the Damned, or the Undead Hordes and test your faith in battles of brute force, spellbinding magic and acts of guile. Slay demons, vanquish giants and combat merciless forces of the dead and undead. But to ensure the salvation of your god, the hero within must evolve.<br />\r\n	<br />\r\n	The day of reckoning has come... and only the chosen will survive.</p>', '', 6, 'Disciples: Sacred Lands', 'Disciples: Sacred Lands', 'Disciples: Sacred Lands', '', ''),
(25, 2, 'Microsoft Internet Keyboard PS/2', 'The Internet Keyboard has 10 Hot Keys on a comfortable standard keyboard design that also includes a detachable palm rest. The Hot Keys allow you to browse the web, or check e-mail directly from your keyboard. The IntelliType Pro software also allows you to customize your hot keys - make the Internet Keyboard work the way you want it to!', '', 25, 'Microsoft Internet Keyboard PS/2', 'Microsoft Internet Keyboard PS/2', 'Microsoft Internet Keyboard PS/2', '', ''),
(26, 2, 'Microsoft IntelliMouse Explorer', '<p>\r\n	Microsoft introduces its most advanced mouse, the IntelliMouse Explorer! IntelliMouse Explorer features a sleek design, an industrial-silver finish, a glowing red underside and taillight, creating a style and look unlike any other mouse. IntelliMouse Explorer combines the accuracy and reliability of Microsoft IntelliEye optical tracking technology, the convenience of two new customizable function buttons, the efficiency of the scrolling wheel and the comfort of expert ergonomic design. All these great features make this the best mouse for the PC!</p>', 'www.microsoft.com/hardware/mouse/explorer.asp', 41, 'Microsoft IntelliMouse Explorer', 'Microsoft IntelliMouse Explorer', 'Microsoft IntelliMouse Explorer', '', ''),
(27, 2, 'Hewlett Packard LaserJet 1100Xi', '<p>\r\n	HP has always set the pace in laser printing technology. The new generation HP LaserJet 1100 series sets another impressive pace, delivering a stunning 8 pages per minute print speed. The 600 dpi print resolution with HP&#39;s Resolution Enhancement technology (REt) makes every document more professional.<br />\r\n	<br />\r\n	Enhanced print speed and laser quality results are just the beginning. With 2MB standard memory, HP LaserJet 1100xi users will be able to print increasingly complex pages. Memory can be increased to 18MB to tackle even more complex documents with ease. The HP LaserJet 1100xi supports key operating systems including Windows 3.1, 3.11, 95, 98, NT 4.0, OS/2 and DOS. Network compatibility available via the optional HP JetDirect External Print Servers.<br />\r\n	<br />\r\n	HP LaserJet 1100xi also features The Document Builder for the Web Era from Trellix Corp. (featuring software to create Web documents).</p>', 'www.pandi.hp.com/pandi-db/prodinfo.main?product=laserjet1100', 11, 'Hewlett Packard LaserJet 1100Xi', 'Hewlett Packard LaserJet 1100Xi', 'Hewlett Packard LaserJet 1100Xi', '', ''),
(28, 2, 'Samsung Galaxy Tab', '<p>\r\n	de Samsung GALAXY Tab in het nederlands</p>\r\n<p>\r\n	nummer 12</p>\r\n<p>\r\n	met</p>\r\n<p>\r\n	meerdere</p>\r\n<p>\r\n	regels</p>\r\n<p>\r\n	en tekst</p>\r\n<p>\r\n	met regels</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	</p>\r\n<p>\r\n	<br />\r\n	</p>', 'http://galaxytab.samsungmobile.com', 1498, 'Samsung Galaxy Tab ned', 'Samsung Galaxy Tab oms ned', 'Samsung ,Galaxy ,Tab , keyword,ned', 'test text product listing nederlands', 'test text sub ttext'),
(38, 2, 'Retail Matrox G500 64MB', '<p>\r\n	<strong>Dramatically Different High Performance Graphics</strong><br />\r\n	<br />\r\n	Introducing the Millennium G400 Series - a dramatically different, high performance graphics experience. Armed with the industry&#39;s fastest graphics chip, the Millennium G400 Series takes explosive acceleration two steps further by adding unprecedented image quality, along with the most versatile display options for all your 3D, 2D and DVD applications. As the most powerful and innovative tools in your PC&#39;s arsenal, the Millennium G400 Series will not only change the way you see graphics, but will revolutionize the way you use your computer.<br />\r\n	<br />\r\n	<strong>Key features:</strong></p>\r\n<ul>\r\n	<li>\r\n		New Matrox G400 256-bit DualBus graphics chip</li>\r\n	<li>\r\n		Explosive 3D, 2D and DVD performance</li>\r\n	<li>\r\n		DualHead Display</li>\r\n	<li>\r\n		Superior DVD and TV output</li>\r\n	<li>\r\n		3D Environment-Mapped Bump Mapping</li>\r\n	<li>\r\n		Vibrant Color Quality rendering</li>\r\n	<li>\r\n		UltraSharp DAC of up to 360 MHz</li>\r\n	<li>\r\n		3D Rendering Array Processor</li>\r\n	<li>\r\n		Support for 16 or 32 MB of memory</li>\r\n</ul>', 'www.matrox.com/mga/products/mill_g400/home.htm', 39, 'Matrox G400 32MB ned', 'Matrox G400 32MB ned', 'Matrox G400 32MB ned', 'product listing text nederlands', 'extra text onderkant product pagina'),
(38, 3, 'Matrox G500 64MB', '<p>\r\n	<strong>Dramatically Different High Performance Graphics</strong><br />\r\n	<br />\r\n	Introducing the Millennium G400 Series - a dramatically different, high performance graphics experience. Armed with the industry&#39;s fastest graphics chip, the Millennium G400 Series takes explosive acceleration two steps further by adding unprecedented image quality, along with the most versatile display options for all your 3D, 2D and DVD applications. As the most powerful and innovative tools in your PC&#39;s arsenal, the Millennium G400 Series will not only change the way you see graphics, but will revolutionize the way you use your computer.<br />\r\n	<br />\r\n	<strong>Key features:</strong></p>\r\n<ul>\r\n	<li>\r\n		New Matrox G400 256-bit DualBus graphics chip</li>\r\n	<li>\r\n		Explosive 3D, 2D and DVD performance</li>\r\n	<li>\r\n		DualHead Display</li>\r\n	<li>\r\n		Superior DVD and TV output</li>\r\n	<li>\r\n		3D Environment-Mapped Bump Mapping</li>\r\n	<li>\r\n		Vibrant Color Quality rendering</li>\r\n	<li>\r\n		UltraSharp DAC of up to 360 MHz</li>\r\n	<li>\r\n		3D Rendering Array Processor</li>\r\n	<li>\r\n		Support for 16 or 32 MB of memory</li>\r\n</ul>', 'www.matrox.com/mga/products/mill_g400/home.htm', 35, 'Matrox G400 32MB', 'Matrox G400 32MB', 'Matrox G400 32MB', 'product listing text nederlands', 'extra text bottom of product info page');

INSERT INTO products_groups (customers_group_id, customers_group_price, products_id, products_qty_blocks, products_min_order_qty) VALUES
(1, '100.0000', 28, 1, 2),
(1, '350.0000', 2, 1, 1),
(1, '25.0000', 16, 1, 1),
(1, '16.0000', 17, 1, 1),
(1, '80.0000', 23, 1, 1),
(1, '150.0000', 27, 1, 1),
(1, '350.0000', 38, 1, 1),
(1, '1000000.0000', 20, 1, 1),
(1, '50.0000', 26, 1, 1),
(1, '40.0000', 5, 1, 1),
(1, '20.0000', 10, 1, 1),
(1, '15.0000', 9, 1, 1);

INSERT INTO products_group_prices_cg_1 (products_id, products_price, specials_new_products_price, `status`) VALUES
(2, '350.0000', '150.0000', 1),
(3, '49.9900', NULL, NULL),
(4, '42.0000', NULL, NULL),
(5, '40.0000', '5.0000', 1),
(7, '34.9900', NULL, NULL),
(8, '35.9900', NULL, NULL),
(9, '29.9900', NULL, NULL),
(10, '29.9900', NULL, NULL),
(11, '29.9900', NULL, NULL),
(12, '39.9900', NULL, NULL),
(13, '34.9900', NULL, NULL),
(14, '32.0000', NULL, NULL),
(15, '35.0000', NULL, NULL),
(16, '25.0000', NULL, NULL),
(17, '16.0000', NULL, NULL),
(18, '42.0000', NULL, NULL),
(19, '49.9900', NULL, NULL),
(20, '20.0000', '15.0000', 1),
(21, '79.9900', NULL, NULL),
(22, '90.0000', NULL, NULL),
(23, '80.0000', NULL, NULL),
(24, '90.0000', NULL, NULL),
(25, '69.9900', NULL, NULL),
(26, '50.0000', NULL, NULL),
(27, '150.0000', NULL, NULL),
(28, '100.0000', '85.0000', 1),
(37, '100.0000', NULL, NULL),
(38, '350.0000', NULL, NULL);

INSERT INTO products_images (id, products_id, image, htmlcontent, sort_order) VALUES
(1, 28, 'products/galaxy_tab_1.jpg', '', 1),
(2, 28, 'products/galaxy_tab_2.jpg', '', 2),
(3, 28, 'products/galaxy_tab_3.jpg', '', 3),
(4, 28, 'products/galaxy_tab_4.jpg', '<iframe width="560" height="315" src="http://www.youtube.com/embed/AN8Z7mRre0o?autoplay=0" frameborder="0" allowfullscreen></iframe>', 4),
(17, 2, 'products/mg400-32mb.gif', '', 1);

INSERT INTO products_notifications (products_id, customers_id, date_added) VALUES
(27, 23, '2011-10-26 19:41:25'),
(2, 23, '2011-10-26 19:41:55');

INSERT INTO products_options (products_options_id, language_id, products_options_name, products_options_track_stock, products_options_type, products_options_length, products_options_comment, products_options_sort_order) VALUES
(5, 3, 'Version', 1, 0, 32, NULL, 0),
(4, 3, 'Memory', 1, 0, 32, '', 15),
(3, 3, 'Model', 1, 0, 32, '', 0),
(2, 3, 'Size', 0, 0, 32, NULL, 0),
(1, 3, 'Color1', 0, 0, 32, '', 0),
(1, 2, 'Color1', 0, 0, 32, '', 0),
(2, 2, 'Size', 0, 0, 32, NULL, 0),
(3, 2, 'Model', 1, 0, 32, '', 0),
(4, 2, 'Memory', 1, 0, 32, '', 15),
(5, 2, 'Version', 0, 0, 32, NULL, 0),
(10, 3, 'file english', 0, 5, 0, '', 6),
(6, 2, 'kleurnummer', 0, 1, 32, '', 10),
(6, 3, 'color', 0, 1, 32, '', 10),
(7, 2, 'test met textarea', 0, 2, 32, 'textarea', 3),
(7, 3, 'text area eng', 0, 2, 32, 'textarea', 3),
(8, 2, 'test met radio button', 0, 3, 0, '', 4),
(8, 3, '', 0, 3, 0, '', 4),
(9, 2, 'test met checkbox', 0, 4, 0, 'checkbox', 1),
(9, 3, 'test with checkbox', 0, 4, 0, 'checkbox', 1),
(10, 2, 'test met bestand', 0, 5, 0, '', 6),
(11, 2, 'test met image', 0, 6, 0, '', 6),
(11, 3, '', 0, 6, 0, '', 6),
(12, 3, '', 0, 0, 15, 'some', 0),
(12, 2, 'voorraad', 0, 0, 15, 'iets', 0),
(13, 2, 'test', 0, 1, 0, 'test2', 0),
(13, 3, 'test', 0, 1, 0, 'test2', 0);

INSERT INTO products_options_values (products_options_values_id, language_id, products_options_values_name) VALUES
(10, 3, 'Download: Windows - English'),
(9, 3, 'USB'),
(8, 3, 'PS/2'),
(7, 3, 'Deluxe'),
(6, 3, 'Premium'),
(5, 3, 'Value'),
(4, 3, '32 mb'),
(3, 3, '16 mb'),
(2, 3, '8 mb'),
(1, 3, '4 mb'),
(1, 2, '4 mb'),
(2, 2, '8 mb'),
(3, 2, '16 mb'),
(4, 2, '32 mb'),
(5, 2, 'Value'),
(6, 2, 'Premium'),
(7, 2, 'Deluxe'),
(8, 2, 'PS/2'),
(9, 2, 'USB'),
(10, 2, 'Download: Windows - English'),
(13, 2, 'Box: Windows - English'),
(13, 3, 'Box: Windows - English'),
(0, 3, 'CUSTOMER-INPUT'),
(0, 2, 'CUSTOMER-INPUT'),
(14, 2, 'kleur 2'),
(14, 3, 'color 2'),
(15, 2, 'radio 16mb'),
(15, 3, 'radio 16mb'),
(19, 2, ''),
(19, 3, '');

INSERT INTO products_options_values_to_products_options (products_options_values_to_products_options_id, products_options_id, products_options_values_id) VALUES
(1, 4, 1),
(2, 4, 2),
(3, 4, 3),
(4, 4, 4),
(5, 3, 5),
(6, 3, 6),
(7, 3, 7),
(8, 3, 8),
(9, 3, 9),
(10, 5, 10),
(13, 5, 13),
(22, 11, 18),
(21, 11, 17),
(20, 11, 16),
(23, 4, 19);

INSERT INTO products_price_break (products_price_break_id, products_id, products_price, products_qty, customers_group_id) VALUES
(1, 28, '110.0000', 6, 0),
(2, 28, '90.0000', 6, 1),
(3, 28, '100.0000', 8, 0),
(4, 28, '80.0000', 8, 1),
(5, 28, '70.0000', 10, 1),
(9, 28, '50.0000', 100, 1),
(8, 28, '60.0000', 20, 1),
(16, 2, '50.0000', 5, 1),
(15, 2, '50.0000', 5, 0),
(14, 18, '25.0000', 15, 1),
(17, 38, '50.0000', 5, 0),
(18, 38, '50.0000', 5, 1),
(19, 5, '30.0000', 2, 0);

INSERT INTO products_stock (products_stock_id, products_id, products_stock_attributes, products_stock_quantity) VALUES
(24, 28, '3-6,4-1', 7),
(25, 28, '3-6,4-2', 10),
(26, 28, '3-7,4-1', 6);

INSERT INTO products_tags (products_id, tag_id) VALUES
(8, 1),
(27, 2),
(28, 3),
(17, 4),
(18, 4),
(26, 5),
(3, 5),
(25, 6),
(25, 5);

INSERT INTO products_to_categories (products_id, categories_id) VALUES
(2, 4),
(3, 9),
(4, 10),
(5, 11),
(7, 12),
(8, 13),
(9, 10),
(10, 10),
(11, 10),
(12, 10),
(13, 10),
(14, 15),
(15, 14),
(16, 15),
(17, 10),
(18, 10),
(19, 12),
(20, 15),
(21, 18),
(22, 19),
(23, 4),
(24, 4),
(25, 8),
(26, 9),
(27, 5),
(28, 21),
(37, 17),
(38, 4);

INSERT INTO products_to_discount_categories (products_id, discount_categories_id, customers_group_id) VALUES
(28, 1, 0),
(7, 1, 0),
(23, 1, 1);

INSERT INTO products_xsell (ID, products_id, xsell_id, sort_order) VALUES
(37, 8, 20, 1),
(8, 5, 19, 1),
(73, 8, 28, 1),
(39, 20, 8, 1),
(71, 11, 28, 1),
(66, 28, 15, 1),
(72, 15, 28, 1),
(70, 28, 12, 1),
(69, 28, 5, 1),
(67, 28, 8, 1),
(65, 28, 16, 1),
(64, 28, 11, 1),
(25, 0, 17, 1),
(26, 17, 0, 1),
(74, 20, 28, 1),
(68, 28, 20, 1);

INSERT INTO sec_directory_whitelist (id, `directory`) VALUES
(1, 'admin/backups'),
(2, 'admin/images/graphs'),
(3, 'images'),
(4, 'images/banners'),
(5, 'images/dvd'),
(6, 'images/gt_interactive'),
(7, 'images/hewlett_packard'),
(8, 'images/matrox'),
(9, 'images/microsoft'),
(10, 'images/sierra'),
(11, 'includes/work'),
(12, 'pub');

INSERT INTO specials (specials_id, products_id, specials_new_products_price, specials_date_added, specials_last_modified, expires_date, date_status_change, `status`, start_date, limit_specials_quantity, customers_group_id) VALUES
(164, 28, '170.0000', '2012-02-09 22:35:11', '2012-07-14 21:07:10', NULL, NULL, 1, NULL, 0, 0),
(158, 24, '42.0168', '2011-12-29 20:34:48', '2011-12-29 20:37:30', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, NULL, 0, 0),
(161, 28, '85.0000', '2012-01-13 21:07:24', '2012-01-13 21:15:00', NULL, NULL, 1, NULL, 0, 1),
(162, 37, '90.0000', '2012-01-13 21:18:40', NULL, NULL, NULL, 1, NULL, 0, 0),
(165, 5, '10.0000', '2012-02-11 19:09:40', NULL, NULL, NULL, 1, NULL, 0, 0),
(166, 5, '5.0000', '2012-02-11 19:14:54', NULL, NULL, NULL, 1, NULL, 0, 1),
(155, 20, '15.0000', '2011-12-28 20:21:04', NULL, NULL, NULL, 1, NULL, 0, 1),
(154, 20, '45.0000', '2011-12-28 20:20:22', NULL, NULL, NULL, 1, NULL, 0, 0),
(153, 2, '15.0000', '2011-12-18 21:23:14', NULL, NULL, NULL, 1, NULL, 0, 0),
(152, 2, '150.0000', '2011-12-15 21:16:59', NULL, NULL, NULL, 1, NULL, 0, 1),
(167, 9, '10.0000', '2012-07-05 19:52:49', NULL, NULL, NULL, 1, NULL, 0, 1);

INSERT INTO specials_retail_prices (products_id, specials_new_products_price, `status`, customers_group_id) VALUES
(28, '185.0000', 1, 0),
(24, '42.0168', 1, 0),
(37, '90.0000', 1, 0),
(5, '10.0000', 1, 0),
(20, '45.0000', 1, 0),
(2, '15.0000', 1, 0);

INSERT INTO tax_class (tax_class_id, tax_class_title, tax_class_description, last_modified, date_added) VALUES
(1, 'Taxable Goods', 'The following types of products are included non-food, services, etc', '2010-12-26 18:53:09', '2010-12-26 18:53:09'),
(2, 'BTW 19%', 'BTW 19%', NULL, '2011-12-16 19:57:03'),
(3, 'BTW 6%', 'BTW 6%', NULL, '2011-12-16 19:57:17');

INSERT INTO tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_priority, tax_rate, tax_description, last_modified, date_added) VALUES
(1, 2, 3, 1, '6.0000', 'BTW 19%', '2011-12-17 21:31:21', '2010-12-26 18:53:09'),
(2, 2, 2, 1, '19.0000', 'btw  19', '2011-12-17 23:47:49', '2011-12-17 21:02:54');

INSERT INTO usu_cache (cache_name, cache_data, cache_date) VALUES
('15983f45bfe08c9cd878d8230ec00f03', 'dZPdTsMwDIVfpcoTLOl+vSvUCpBQYWKI28pkZotYmykNQxXau5Nu2hbAvUuizyfHJw6ChO8GRiD0Av1GzBHU8WQIQpZy0h1ciEesSMwbkApElj8/FUnuzJ4aMT80kHYFQ54PYncOdxujmyRDt4oqpmzFDMQDtW/2wp7sjFl4DKKgyrr2qjpjwSBSGE1XjNebBszWxlsX+Rz1oQtnak8xyocW0OWO8OOEGpBsUgMQ96HpL3SUqHPnqpR8Q6HzG+2NrSOSD7QTXprqc4u/cTXoNesdelq3R7OqD7LvvvN6FkxLyQv+cxrItO/qMCLe2rqJWNWnmtmKVm1E8g8V1rnDCiNQ8kGFzVIbqjUlt+avZX6+g+WXjTPbLbljWmnfC+SveVLYvaHTCCh2Bibhs+BqTb6DDocf', '2012-07-02 22:27:06');

INSERT INTO zones (zone_id, zone_country_id, zone_code, zone_name) VALUES
(1, 223, 'AL', 'Alabama'),
(2, 223, 'AK', 'Alaska'),
(3, 223, 'AS', 'American Samoa'),
(4, 223, 'AZ', 'Arizona'),
(5, 223, 'AR', 'Arkansas'),
(6, 223, 'AF', 'Armed Forces Africa'),
(7, 223, 'AA', 'Armed Forces Americas'),
(8, 223, 'AC', 'Armed Forces Canada'),
(9, 223, 'AE', 'Armed Forces Europe'),
(10, 223, 'AM', 'Armed Forces Middle East'),
(11, 223, 'AP', 'Armed Forces Pacific'),
(12, 223, 'CA', 'California'),
(13, 223, 'CO', 'Colorado'),
(14, 223, 'CT', 'Connecticut'),
(15, 223, 'DE', 'Delaware'),
(16, 223, 'DC', 'District of Columbia'),
(17, 223, 'FM', 'Federated States Of Micronesia'),
(18, 223, 'FL', 'Florida'),
(19, 223, 'GA', 'Georgia'),
(20, 223, 'GU', 'Guam'),
(21, 223, 'HI', 'Hawaii'),
(22, 223, 'ID', 'Idaho'),
(23, 223, 'IL', 'Illinois'),
(24, 223, 'IN', 'Indiana'),
(25, 223, 'IA', 'Iowa'),
(26, 223, 'KS', 'Kansas'),
(27, 223, 'KY', 'Kentucky'),
(28, 223, 'LA', 'Louisiana'),
(29, 223, 'ME', 'Maine'),
(30, 223, 'MH', 'Marshall Islands'),
(31, 223, 'MD', 'Maryland'),
(32, 223, 'MA', 'Massachusetts'),
(33, 223, 'MI', 'Michigan'),
(34, 223, 'MN', 'Minnesota'),
(35, 223, 'MS', 'Mississippi'),
(36, 223, 'MO', 'Missouri'),
(37, 223, 'MT', 'Montana'),
(38, 223, 'NE', 'Nebraska'),
(39, 223, 'NV', 'Nevada'),
(40, 223, 'NH', 'New Hampshire'),
(41, 223, 'NJ', 'New Jersey'),
(42, 223, 'NM', 'New Mexico'),
(43, 223, 'NY', 'New York'),
(44, 223, 'NC', 'North Carolina'),
(45, 223, 'ND', 'North Dakota'),
(46, 223, 'MP', 'Northern Mariana Islands'),
(47, 223, 'OH', 'Ohio'),
(48, 223, 'OK', 'Oklahoma'),
(49, 223, 'OR', 'Oregon'),
(50, 223, 'PW', 'Palau'),
(51, 223, 'PA', 'Pennsylvania'),
(52, 223, 'PR', 'Puerto Rico'),
(53, 223, 'RI', 'Rhode Island'),
(54, 223, 'SC', 'South Carolina'),
(55, 223, 'SD', 'South Dakota'),
(56, 223, 'TN', 'Tennessee'),
(57, 223, 'TX', 'Texas'),
(58, 223, 'UT', 'Utah'),
(59, 223, 'VT', 'Vermont'),
(60, 223, 'VI', 'Virgin Islands'),
(61, 223, 'VA', 'Virginia'),
(62, 223, 'WA', 'Washington'),
(63, 223, 'WV', 'West Virginia'),
(64, 223, 'WI', 'Wisconsin'),
(65, 223, 'WY', 'Wyoming'),
(66, 38, 'AB', 'Alberta'),
(67, 38, 'BC', 'British Columbia'),
(68, 38, 'MB', 'Manitoba'),
(69, 38, 'NF', 'Newfoundland'),
(70, 38, 'NB', 'New Brunswick'),
(71, 38, 'NS', 'Nova Scotia'),
(72, 38, 'NT', 'Northwest Territories'),
(73, 38, 'NU', 'Nunavut'),
(74, 38, 'ON', 'Ontario'),
(75, 38, 'PE', 'Prince Edward Island'),
(76, 38, 'QC', 'Quebec'),
(77, 38, 'SK', 'Saskatchewan'),
(78, 38, 'YT', 'Yukon Territory'),
(79, 81, 'NDS', 'Niedersachsen'),
(80, 81, 'BAW', 'Baden-WÃ¼rttemberg'),
(81, 81, 'BAY', 'Bayern'),
(82, 81, 'BER', 'Berlin'),
(83, 81, 'BRG', 'Brandenburg'),
(84, 81, 'BRE', 'Bremen'),
(85, 81, 'HAM', 'Hamburg'),
(86, 81, 'HES', 'Hessen'),
(87, 81, 'MEC', 'Mecklenburg-Vorpommern'),
(88, 81, 'NRW', 'Nordrhein-Westfalen'),
(89, 81, 'RHE', 'Rheinland-Pfalz'),
(90, 81, 'SAR', 'Saarland'),
(91, 81, 'SAS', 'Sachsen'),
(92, 81, 'SAC', 'Sachsen-Anhalt'),
(93, 81, 'SCN', 'Schleswig-Holstein'),
(94, 81, 'THE', 'ThÃ¼ringen'),
(95, 14, 'WI', 'Wien'),
(96, 14, 'NO', 'NiederÃ¶sterreich'),
(97, 14, 'OO', 'OberÃ¶sterreich'),
(98, 14, 'SB', 'Salzburg'),
(99, 14, 'KN', 'KÃ¤rnten'),
(100, 14, 'ST', 'Steiermark'),
(101, 14, 'TI', 'Tirol'),
(102, 14, 'BL', 'Burgenland'),
(103, 14, 'VB', 'Voralberg'),
(104, 204, 'AG', 'Aargau'),
(105, 204, 'AI', 'Appenzell Innerrhoden'),
(106, 204, 'AR', 'Appenzell Ausserrhoden'),
(107, 204, 'BE', 'Bern'),
(108, 204, 'BL', 'Basel-Landschaft'),
(109, 204, 'BS', 'Basel-Stadt'),
(110, 204, 'FR', 'Freiburg'),
(111, 204, 'GE', 'Genf'),
(112, 204, 'GL', 'Glarus'),
(113, 204, 'JU', 'GraubÃ¼nden'),
(114, 204, 'JU', 'Jura'),
(115, 204, 'LU', 'Luzern'),
(116, 204, 'NE', 'Neuenburg'),
(117, 204, 'NW', 'Nidwalden'),
(118, 204, 'OW', 'Obwalden'),
(119, 204, 'SG', 'St. Gallen'),
(120, 204, 'SH', 'Schaffhausen'),
(121, 204, 'SO', 'Solothurn'),
(122, 204, 'SZ', 'Schwyz'),
(123, 204, 'TG', 'Thurgau'),
(124, 204, 'TI', 'Tessin'),
(125, 204, 'UR', 'Uri'),
(126, 204, 'VD', 'Waadt'),
(127, 204, 'VS', 'Wallis'),
(128, 204, 'ZG', 'Zug'),
(129, 204, 'ZH', 'ZÃ¼rich'),
(130, 195, 'A CoruÃ±a', 'A CoruÃ±a'),
(131, 195, 'Alava', 'Alava'),
(132, 195, 'Albacete', 'Albacete'),
(133, 195, 'Alicante', 'Alicante'),
(134, 195, 'Almeria', 'Almeria'),
(135, 195, 'Asturias', 'Asturias'),
(136, 195, 'Avila', 'Avila'),
(137, 195, 'Badajoz', 'Badajoz'),
(138, 195, 'Baleares', 'Baleares'),
(139, 195, 'Barcelona', 'Barcelona'),
(140, 195, 'Burgos', 'Burgos'),
(141, 195, 'Caceres', 'Caceres'),
(142, 195, 'Cadiz', 'Cadiz'),
(143, 195, 'Cantabria', 'Cantabria'),
(144, 195, 'Castellon', 'Castellon'),
(145, 195, 'Ceuta', 'Ceuta'),
(146, 195, 'Ciudad Real', 'Ciudad Real'),
(147, 195, 'Cordoba', 'Cordoba'),
(148, 195, 'Cuenca', 'Cuenca'),
(149, 195, 'Girona', 'Girona'),
(150, 195, 'Granada', 'Granada'),
(151, 195, 'Guadalajara', 'Guadalajara'),
(152, 195, 'Guipuzcoa', 'Guipuzcoa'),
(153, 195, 'Huelva', 'Huelva'),
(154, 195, 'Huesca', 'Huesca'),
(155, 195, 'Jaen', 'Jaen'),
(156, 195, 'La Rioja', 'La Rioja'),
(157, 195, 'Las Palmas', 'Las Palmas'),
(158, 195, 'Leon', 'Leon'),
(159, 195, 'Lleida', 'Lleida'),
(160, 195, 'Lugo', 'Lugo'),
(161, 195, 'Madrid', 'Madrid'),
(162, 195, 'Malaga', 'Malaga'),
(163, 195, 'Melilla', 'Melilla'),
(164, 195, 'Murcia', 'Murcia'),
(165, 195, 'Navarra', 'Navarra'),
(166, 195, 'Ourense', 'Ourense'),
(167, 195, 'Palencia', 'Palencia'),
(168, 195, 'Pontevedra', 'Pontevedra'),
(169, 195, 'Salamanca', 'Salamanca'),
(170, 195, 'Santa Cruz de Tenerife', 'Santa Cruz de Tenerife'),
(171, 195, 'Segovia', 'Segovia'),
(172, 195, 'Sevilla', 'Sevilla'),
(173, 195, 'Soria', 'Soria'),
(174, 195, 'Tarragona', 'Tarragona'),
(175, 195, 'Teruel', 'Teruel'),
(176, 195, 'Toledo', 'Toledo'),
(177, 195, 'Valencia', 'Valencia'),
(178, 195, 'Valladolid', 'Valladolid'),
(179, 195, 'Vizcaya', 'Vizcaya'),
(180, 195, 'Zamora', 'Zamora'),
(181, 195, 'Zaragoza', 'Zaragoza'),
(182, 150, 'NL', 'Netherlands');

INSERT INTO zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES
(2, 150, 182, 2, '2011-12-17 23:47:31', '2011-12-16 20:31:45');
