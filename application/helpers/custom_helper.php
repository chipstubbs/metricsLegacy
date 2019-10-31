<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function flatten(array $array)
{
    $return = array();
    array_walk_recursive($array, function ($a) use (&$return) {
        $return[] = $a;
    });
    return $return;
}

function getViewFilename($id)
{
    $options = array("", "production", "annuities", "acat", "hot_prospects", "hot_clients", "event_metrics", "life_sheet", "other_sheet", "aum_sheet", "y2y");
    return $options[$id];
}

function paid_within_year($event_date, $paid_date)
{
    $event_date = strtotime($event_date);
    //    echo $event_date ."\n";
    //    echo $event_date;
    //    $paid_date = strtotime($paid_date);
        $paid_date = date_create_from_format('Y-m-d', $paid_date);
    //    echo $paid_date->getTimestamp() ."\n";
    //    echo date_timestamp_get($paid_date);
        if (method_exists($paid_date, 'getTimestamp')) {
            $difference = $paid_date->getTimestamp() - $event_date;
        } else return true;

    //    echo "Difference:  ".$difference."\n";
        return $difference < 31622400;
}

function compare($leadjigs, $metrics){
	$e = getEventOptionsforCompare();
	$match = 0;
	if (strpos(strtolower($leadjigs), strtolower($e[$metrics])) !== false) {
        $match = $e[$metrics];
    }
    elseif ($leadjigs == 'Adult Ed' && $metrics == 'teacherpro') {
        $match = $e[$metrics];
    }
	return $match;
}

function getUnmatchedEvents($bigArray, $matched, $dateSet) {
    $tmpArray = array();

    foreach($bigArray as $data1) {

        $duplicate = false;
        foreach($matched as $data2) {
            if($data1['leadjig_id'] === $data2['leadjig_id'] && $data1['event'] === $data2['event'] && $data1['event_date'] === $data2['event_date']) {
                $duplicate = true;
            }
        }

        if($duplicate === false) {
            // See if event date is past our integration date (set to 01/01/2018)
            $events = new DateTime($data1['event_date']);
            if ($dateSet == NULL) {
                $chosenDate = new DateTime("01/01/2018");
            }
            elseif ($dateSet != NULL) {
                $formattedDate = date('m/d/Y', $dateSet);
                $chosenDate = new DateTime($formattedDate);
            }
            
            if ( $events >= $chosenDate )
                $tmpArray[] = $data1;
        } 
    }
    return $tmpArray;
}

function compareSingle($leadjigs){
	$e = getEventOptionsforCompare();
    $match = 0;
    $teacherproArray = array( 
        'Adult Ed', 
        'Learn the Core Values to Help Protect Your Money in Good Times and Bad.',
        'Return on Principle',
        'Stop The Financial Insanity',
        'Financial Insanity'
    );
    $rmdArray = array( "RMD's", "RMDs" );

	foreach ($e as $k=>$v){
		if (strpos(strtolower($leadjigs), strtolower($v)) !== false) {
		 $match = $k;
		}
    }
    if (in_array($leadjigs, $teacherproArray)) {
        $match = 'teacherpro';
    }
    if (in_array($leadjigs, $rmdArray)) {
        $match = 'rmd';
    }

	return $match;
}
function getEventOptionsforCompare()
{
    return array(
        '' => '-',
        'clients' => 'Clients',
        'unsolicited' => 'Unsolicited Referrals',
        'solicited' => 'Solicited Referrals',
        'ss' => 'Social Security',
        'rmd' => 'RMD',
        'estate' => 'Estate',
        'taxpro' => 'TaxPro',
        'college' => 'College Planning',
        'federalemployee' => 'Federal Employee Benefits Specialist',
        'teacherpro' => 'Teacher Pro',
        'radio' => 'Radio',
        'cpa-attorney' => 'CPA / Attorney',
        'pcpartnership' => 'P&C Partnership',
        'financialliteracy' => 'Financial Literacy',
        'guestspeaker' => 'Guest Speaker',
        'platinumreferral' => 'Platinum Referrals',
        'advisoryboard' => 'Advisory Board',
        'lunchnlink' => 'Lunch &amp; Link',
        'clientparty' => 'Client Appreciation Party',
        'selectclub' => 'Select Club',
        'bday' => 'Birthday',
        'retirement' => 'Retirement',
        'manipedi' => 'Mani / Pedi',
        'dinnerseminar' => 'Dinner Seminar',
        'dinnerseminar' => 'Halftime',
        'wiseradvisor' => 'WiserAdvisor',
        'other1' => 'Other 1',
        'other2' => 'Other 2',
        'other3' => 'Other 3',
        'workshop1' => 'Workshop 1',
        'workshop2' => 'Workshop 2',
        'workshop3' => 'Workshop 3',
        'website' => 'Website',
        'paladin' => 'Paladin',
        'delwebb' => 'Del Webb',
        'tv' => 'TV',
        'god' => 'God',
        'ira' => 'IRA',
        'taxes' => 'Tax Workshop',
        'risradio' => 'RIS Radio',
        'ristv' => 'RIS TV',
        'webinar' => 'Webinar'
    );
}

function getEventOptions()
{
    return array(
        '' => '-',
        'clients' => 'Clients',
        'unsolicited' => 'Unsolicited Referrals',
        'solicited' => 'Solicited Referrals',
        'ss' => 'Social Security',
        'rmd' => 'RMD',
        'estate' => 'Estate',
        'taxpro' => 'TaxPro',
        'college' => 'College Planning',
        'federalemployee' => 'Federal Employee Benefits Specialist',
        'teacherpro' => 'Teacher Pro',
        'radio' => 'Radio',
        'cpa-attorney' => 'CPA / Attorney',
        'pcpartnership' => 'P&C Partnership',
        'financialliteracy' => 'Financial Literacy',
        'guestspeaker' => 'Guest Speaker',
        'platinumreferral' => 'Platinum Referrals',
        'advisoryboard' => 'Advisory Board',
        'lunchnlink' => 'Lunch &amp; Link',
        'clientparty' => 'Client Appreciation Party',
        'selectclub' => 'Select Club',
        'bday' => 'Birthday',
        'retirement' => 'Retirement',
        'manipedi' => 'Mani / Pedi',
        'dinnerseminar' => 'Dinner Seminar',
        'wiseradvisor' => 'WiserAdvisor',
        'other1' => 'Other 1',
        'other2' => 'Other 2',
        'other3' => 'Other 3',
        'workshop1' => 'Workshop 1',
        'workshop2' => 'Workshop 2',
        'workshop3' => 'Workshop 3',
        'website' => 'Website',
        'paladin' => 'Paladin',
        'delwebb' => 'Del Webb',
        'tv' => 'TV',
        'god' => 'God',
        'ira' => 'IRA',
        'taxes' => 'Tax Workshop',
        'risradio' => 'RIS Radio',
        'ristv' => 'RIS TV',
        'webinar' => 'Webinar' 
    );
}

function getEventOptionsArray()
{
    return array(
        'ss',
        'rmd',
        'estate',
        'taxpro',
        'college',
        'federalemployee',
        'teacherpro',
        'radio',
        'cpa-attorney',
        'pcpartnership',
        'financialliteracy',
        'guestspeaker',
        'platinumreferral',
        'advisoryboard',
        'lunchnlink',
        'clientparty',
        'selectclub',
        'bday',
        'retirement',
        'manipedi',
        'dinnerseminar',
        'wiseradvisor',
        'other1',
        'other2',
        'other3',
        'workshop1',
        'workshop2',
        'workshop3',
        'website',
        'paladin',
        'delwebb',
        'tv',
        'god',
        'ira',
        'taxes',
        'risradio',
        'ristv',
        'webinar'
    );
}

function getMetricLabels()
{

    return array(
        'avg_response' => "Responses",
        'response_ratio' => "Response Ratio",
        'avg_buying_units' => "Buying Units Attended",
        'attendance_ratio' => "Attendance Ratio",
        'avg_appointments' => "Appointments Scheduled",
        'appointment_ratio' => "Appointment Ratio",
        'avg_appointment_kept' => "Appointsments Kept",
        'appointment_kept_ratio' => "Appointments Kept Ratio",
        'num_closed' => "Closed",
        'closingratio' => "Closing Ratio",
        'totaleventcost' => "Total Event Cost",
        'grossprofit' => "Gross Profit",
        'annuityavg' => "Annuity Average",
        'avgtoannuity' => "To Annuity",
        'totalannuity' => "Total Annuity"
    );
}

// Display event names
function eventName($name)
{
    switch ($name) {
        case "ss":
            return "Social Security";
            break;
        case "rmd":
            return "RMD";
            break;
        case "estate":
            return "Estate";
            break;
        case "taxpro":
            return "TaxPro";
            break;
        case "college":
            return "College Planning";
            break;
        case "federalemployee":
            return "Federal Employee Benefits Specialist";
            break;
        case "teacherpro":
            return "Teacher Pro";
            break;
        case "radio":
            return "Radio";
            break;
        case "cpa-attorney":
            return "CPA / Attorney";
            break;
        case "pcpartnership":
            return "P&amp;C Partnership";
            break;
        case "financialliteracy":
            return "Financial Literacy";
            break;
        case "guestspeaker":
            return "Guest Speaker";
            break;
        case "platinumreferral":
            return "Platinum Referrals";
            break;
        case "advisoryboard":
            return "Advisory Board";
            break;
        case "lunchnlink":
            return "Lunch &amp; Link";
            break;
        case "clientparty":
            return "Client Appreciation Party";
            break;
        case "selectclub":
            return "Select Club";
            break;
        case "bday":
            return "Birthday Party";
            break;
        case "retirement":
            return "Retirement Party";
            break;
        case "manipedi":
            return "Mani / Pedi";
            break;
        case "dinnerseminar":
            return "Dinner Seminar";
            break;
        case "wiseradvisor":
            return "WiserAdvisor";
            break;
        case "other1":
            return "Other 1";
            break;
        case "other2":
            return "Other 2";
            break;
        case "other3":
            return "Other 3";
            break;
        case "workshop1":
            return "Workshop 1";
            break;
        case "workshop2":
            return "Workshop 2";
            break;
        case "workshop3":
            return "Workshop 3";
            break;
        case "website":
            return "Website";
            break;
        case "paladin":
            return "Paladin";
            break;
        case "delwebb":
            return "Del Webb";
            break;
        case "tv":
            return "TV";
            break;
        case "god":
            return "God";
            break;
        case "ira":
            return "IRA";
            break;
        case 'taxes':
            return "Tax Workshop";
            break;
        case 'risradio':
            return "RIS Radio";
            break;
        case 'ristv':
            return "RIS TV";
            break;
        case 'webinar';
            return 'Webinar';
            break;
        default:
            return "No Event Type Assigned";
    }
}
