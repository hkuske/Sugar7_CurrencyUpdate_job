<?php
/*********************************************************************************
 *  UPDATE_CURRENCIES from ECB
 * ...\custom\Extension\modules\Schedulers\Ext\ScheduledTasks\Update_Currencies.php
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
$func = 'Update_Currencies';
$job_strings[] = $func;
$mod_strings['LBL_'.strtoupper($func)] = $func;

/**
 * This is the code for the "Job"
 */
function Update_Currencies() {
//  Version: 02.04.2015
    $GLOBALS['log']->info('----->Scheduler fired job of type Update_Currencies()');

    $exc_rates = array();
	
    //This is a PHP(4/5) script example on how eurofxref-daily.xml can be parsed 
    //Read eurofxref-daily.xml file in memory  
    //For this command you will need the config option allow_url_fopen=On (default) 
    //$XMLContent=file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml"); 
    //the file is updated daily between 2.15 p.m. and 3.00 p.m. CET 	
	
	//Just another way with curl:
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, "https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv: 1.9.0.8) Gecko/2009032609 Firefox/3.0.8 (.NET CLR 3.5.30729)');
	if($data = @curl_exec($ch)) {
		$CURLContent = $data;
	}
	curl_close($ch);
	$XMLContent = explode("\n",$CURLContent);
	$GLOBALS['log']->info("XMLContent=".print_r($XMLContent,true));
	
    foreach($XMLContent as $line){ 
        if(preg_match("/currency='([[:alpha:]]+)'/",$line,$currencyCode)){ 
            if(preg_match("/rate='([[:graph:]]+)'/",$line,$rate)){ 
                //Output the value of 1EUR for a currency code 
//              echo'1&euro;='.$rate[1].' '.$currencyCode[1].'<br/>';
                //-------------------------------------------------- 
                //Here you can add your code for inserting 
                //$rate[1] and $currencyCode[1] into your database 
                //-------------------------------------------------- 
         		$GLOBALS['log']->info("NEW EXCHANGE RATE: 1&euro;=".$rate[1]." ".$currencyCode[1]);
                $exc_rates[$currencyCode[1]] = 	$rate[1];			
            } 
        } 
	} 
	
    $db = DBManagerFactory::getInstance();

	$qg = "select * from currencies where status='Active' and deleted=0"; 
    $rg = $db->query($qg); 
    while($row = $db->fetchByAssoc($rg))
    {
	   if(array_key_exists($row['iso4217'],$exc_rates))
	   {
	      $qs = "update currencies set conversion_rate='".$exc_rates[$row['iso4217']]."' where id = '".$row['id']."'";
		  $GLOBALS['log']->info("SET EXCHANGE RATE:".$qs);
		  $rS = $db->query($qs); 
	   }
	}

    $GLOBALS['log']->info('----->Scheduler ended job of type Update_Currencies()');

    return true;
}

// Other Possibilities:
// http://www.webservicex.net/currencyconvertor.asmx/ConversionRate?FromCurrency=USD&ToCurrency=EUR

?>
