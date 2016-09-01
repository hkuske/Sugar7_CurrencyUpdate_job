<?php
/*
  Currency_Update
 */

$manifest = array (
  'acceptable_sugar_versions' => 
  array (
    0 => '7.*.*',
  ),
  'acceptable_sugar_flavors' => 
  array (
    0 => 'PRO',
    1 => 'CORP',
    2 => 'ENT',
    3 => 'ULT',
  ),
  'readme' => '',
  'key' => '',
  'author' => 'kuske',
  'description' => 'Scheduler: Currency_Update',
  'icon' => '',
  'is_uninstallable' => true,
  'name' => 'Currency_Update',
  'published_date' => '2015-10-01 16:39:59',
  'type' => 'module',
  'version' => 151001,
  'remove_tables' => 'false',
);

$installdefs = array (
  'id' => 'Currency_Update',
  'copy' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/custom/',
      'to' => 'custom/',
    ),
  ),
);