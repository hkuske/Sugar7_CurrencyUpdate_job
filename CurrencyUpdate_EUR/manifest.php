<?php
/*
  Currency_Update_EUR
*/

$manifest = array (
  'acceptable_sugar_versions' => array (
      'regex_matches' => array(
        '7.*.*',
        '8.*.*',
        '9.*.*',
        '10.*.*',
      ),
  ),
  'acceptable_sugar_flavors' => array (
    0 => 'PRO',
    1 => 'CORP',
    2 => 'ENT',
    3 => 'ULT',
  ),
  'readme' => '',
  'key' => '',
  'author' => 'kuske',
  'description' => 'Scheduler: Currency_Update EUR',
  'icon' => '',
  'is_uninstallable' => true,
  'name' => 'Currency_Update_EUR',
  'published_date' => '2020-04-01 00:00:00',
  'type' => 'module',
  'version' => '202004',
  'remove_tables' => 'false',
);

$installdefs = array (
  'id' => 'Currency_Update_EUR',
  'copy' => array (
    0 => array (
      'from' => '<basepath>/SugarModules/custom/Extension/modules/Schedulers/Ext/ScheduledTasks/Update_Currencies_EUR.php',
      'to' => 'custom/Extension/modules/Schedulers/Ext/ScheduledTasks/Update_Currencies_EUR.php',
    ),
  ),
);
