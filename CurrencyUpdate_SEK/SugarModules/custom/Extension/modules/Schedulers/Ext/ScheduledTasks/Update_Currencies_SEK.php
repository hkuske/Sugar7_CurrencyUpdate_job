<?php
/*********************************************************************************
 *  Update_Currencies_SEK from riksbank.se
 * ...\custom\Extension\modules\Schedulers\Ext\ScheduledTasks\Update_Currencies_SEW.php
 ********************************************************************************/
/**
 * Set up an array of Jobs with the appropriate metadata
 * 'jobName' => array (
 *         'X' => 'name',
 * )
 * 'X' should be an increment of 1
 * 'name' should be the EXACT name of your function
 *
 * Your function should not be passed any parameters
 * Always  return a Boolean. If it does not the Job will not terminate itself
 * after completion, and the webserver will be forced to time-out that Job instance.
 * DO NOT USE sugar_cleanup(); in your function flow or includes.  this will
 * break Schedulers.  That function is called at the foot of cron.php
 *
 */

/**
 * This array provides the Schedulers admin interface with values for its "Job"
 * dropdown menu.
 */
$func = 'Update_Currencies_SEK';
$job_strings[] = $func;
$mod_strings['LBL_'.strtoupper($func)] = $func;

/**
 * This is the code for the "Job"
 */
function Update_Currencies_SEK() {
//  Version: 08.10.2019
    $GLOBALS['log']->info('----->Scheduler fired job of type Update_Currencies_SEK()');

    $exc_rates = array();
	
	$client = new SoapClient('https://swea.riksbank.se/sweaWS/wsdl/sweaWS_ssl.wsdl');
	$parameters = array(
		'year' => date('Y'),
		'month' => date('n'),
		'languageid' => 'en'    
	);
	$functions =  $client->getMonthlyAverageExchangeRates($parameters);
	$GLOBALS['log']->fatal("Update_Currencies_SEK1:".print_r($functions,true));
	
    foreach($functions->return->groups->series as $idx => $line){ 
		$GLOBALS['log']->fatal("Update_Currencies_SEK2:".print_r($line,true));
		$units = 1.00;
		if (is_numeric($line->unit)) $units = $line->unit;
//		$rate = $line->resultrows->min;
//		$rate = $line->resultrows->average;
  		$rate = $line->resultrows->max;
		if ($rate){
			$exc_rates[$line->seriesname] = ( $units / $rate );
		}
	} 
 	
    $db = DBManagerFactory::getInstance();

	$qg = "select * from currencies where status='Active' and deleted=0 ". 
	      "and iso4217 in ('".implode("','",array_keys($exc_rates))."')";
	$GLOBALS['log']->fatal("Update_Currencies_SEK3:".$qg);
    $rg = $db->query($qg); 
    while($row = $db->fetchByAssoc($rg))
    {
		if(array_key_exists($row['iso4217'],$exc_rates))
		{
			$bean = BeanFactory::getBean('Currencies', $row['id']);
			$bean->conversion_rate = $exc_rates[$row['iso4217']];
			$bean->save();
    $GLOBALS['log']->fatal('----->New rate '.$bean->id.' '.$bean->conversion_rate.' '.$bean->iso4217);
			$bean = NULL;
		}
	}

    $GLOBALS['log']->info('----->Scheduler ended job of type Update_Currencies_SEK()');

    return true;
}

?>