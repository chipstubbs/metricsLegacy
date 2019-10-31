<?php
if (!$selected_year) {
    $selected_year = date("Y");
}
// Begin ACATS
    $pendingACATs = 0;
    foreach ($production as &$pr) {
        $unix_year = $submitted_year = 0;
        $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
        $submitted_year = date('Y', $unix_year);
        if(!empty($pr->production_submitted) && $pr->production_type == 'acat' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year ) {
            $pendingACATs += ($pr->production_amount/100);
        }
    }

    $notsubmittedACATs = 0;
    foreach ($production as &$pr) {
        $unix_year = $received_year = 0;
        $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
        $received_year = date('Y', $unix_year);
        if(empty($pr->production_submitted) && $pr->production_type == 'acat' && empty($pr->prem_paid) && $received_year <= $selected_year ) {
            $notsubmittedACATs += ($pr->production_amount/100);
        }
    }

    $completedACATs = 0;
    foreach ($production_by_paid as &$pr) {
        if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'acat') {
            $completedACATs += ($pr->prem_paid/100);
        }
    }
// End ACATs

// Begin AUM
    $pendingAum = 0;
    foreach ($production as &$pr) {
        $unix_year = $submitted_year = 0;
        $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
        $submitted_year = date('Y', $unix_year);
        if(!empty($pr->production_submitted) && $pr->production_type == 'aum' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year ) {
            $pendingAum += ($pr->production_amount/100);
        }
    }
    
    $notsubmittedAum = 0;
    foreach ($production as &$pr) {
        $unix_year = $received_year = 0;
        $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
        $received_year = date('Y', $unix_year);
        if(empty($pr->production_submitted) && $pr->production_type == 'aum' && empty($pr->prem_paid_month) && $received_year <= $selected_year ) {
            $notsubmittedAum += ($pr->production_amount/100);
        }
    }
    
    $completedAum = 0;
    foreach ($production_by_paid as &$pr) {
        if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'aum') {
            $completedAum += ($pr->prem_paid/100);
        }
    }
// End Aum

// Begin Annuities
    $annuitiesPending = 0;
    foreach ($production as &$pr) {
        $unix_year = $submitted_year = 0;
        $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
        $submitted_year = date('Y', $unix_year);
        if(!empty($pr->production_submitted) && $pr->production_type == 'annuity' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year ) {
            $annuitiesPending += ($pr->production_amount/100);
        }
    }
    
    $notsubmittedAnnuities = 0;
    foreach ($production as &$pr) {
        $unix_year = $received_year = 0;
        $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
        $received_year = date('Y', $unix_year);
        if(empty($pr->production_submitted) && $pr->production_type == 'annuity' && empty($pr->prem_paid) && $received_year <= $selected_year) {
            $notsubmittedAnnuities += ($pr->production_amount/100);
        }
    }

    $completedAnnuities =  0;
    foreach ($production_by_paid as &$pr) {
       if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'annuity') {
           $completedAnnuities += ($pr->prem_paid/100);
       }
    }
// End Annuities

// Begin Life
    $pendingLife = 0;
    foreach ($production as &$pr) {
        $unix_year = $submitted_year = 0;
        $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
        $submitted_year = date('Y', $unix_year);
        if(!empty($pr->production_submitted) && $pr->production_type == 'life' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year ) {
            $pendingLife += ($pr->production_amount/100);
        }
    }
    
    $notsubmittedLife = 0;
    foreach ($production as &$pr) {
        $unix_year = $received_year = 0;
        $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
        $received_year = date('Y', $unix_year);
        if(empty($pr->production_submitted) && $pr->production_type == 'life' && empty($pr->prem_paid) && $received_year <= $selected_year ) {
            $notsubmittedLife += ($pr->production_amount/100);
        }
    }
    
    $completedLife = 0;
    foreach ($production_by_paid as &$pr) {
        if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'life') {
            $completedLife += ($pr->prem_paid/100);
        }
    }
// End Life

// Begin Other
    $pendingOther = 0;
    foreach ($production as &$pr) {
        $unix_year = $submitted_year = 0;
        $unix_year = human_to_unix($pr->production_submitted . ' 00:00');
        $submitted_year = date('Y', $unix_year);
        if(!empty($pr->production_submitted) && $pr->production_type == 'other' && empty($pr->prem_paid) && empty($pr->prem_paid_month) && $submitted_year <= $selected_year ) {
            $pendingOther += ($pr->production_amount/100);
        }
    }
    
    $notsubmittedOther = 0;
    foreach ($production as &$pr) {
        $unix_year = $received_year = 0;
        $unix_year = human_to_unix($pr->app_date_received . ' 00:00');
        $received_year = date('Y', $unix_year);
        if(empty($pr->production_submitted) && $pr->production_type == 'other' && empty($pr->prem_paid) && $received_year <= $selected_year ) {
            $notsubmittedOther += ($pr->production_amount/100);
        }
    }
    
    $completedOther = 0;
    foreach ($production_by_paid as &$pr) {
        if(!empty($pr->prem_paid) && !empty($pr->prem_paid_month) && $pr->production_type == 'other') {
            $completedOther += ($pr->prem_paid/100);
        }
    }
// End Other

// YTD
    $januaryFirst = date('z',strtotime(date('Y-01-01')));
    $currentDateNum = date('z');
    $yearToDateDollar = ($annualGoal * $currentDateNum) / 365; // Tells us how much production they should be at each day of the year according to their goal
    $yearToDatePercent = ($completedAnnuities / $yearToDateDollar);
    
// End YTD

$response = new stdClass();

$response->acats = new stdClass();
$response->aum = new stdClass();
$response->annuities = new stdClass();
$response->life = new stdClass();
$response->other = new stdClass();

$response->name = $fullName;
    $response->company = $companyName;
    $response->userimage = $userimage;
    $response->acats->pending = $pendingACATs;
    $response->acats->notSubmitted = $notsubmittedACATs;
    $response->acats->completed = $completedACATs;

    $response->aum->pending = $pendingAum;
    $response->aum->notSubmitted = $notsubmittedAum;
    $response->aum->completed = $completedAum;

    $response->annuities->pending = $annuitiesPending;
    $response->annuities->notSubmitted = $notsubmittedAnnuities;
    $response->annuities->completed = $completedAnnuities;

    $response->life->pending = $pendingLife;
    $response->life->notSubmitted = $notsubmittedLife;
    $response->life->completed = $completedLife;

    $response->other->pending = $pendingOther;
    $response->other->notSubmitted = $notsubmittedOther;
    $response->other->completed = $completedOther;

    $response->annualGoal = $annualGoal;
    $response->ytdPercent = $yearToDatePercent;
    $response->ytdAnnuityProduction = $completedAnnuities;
    $response->ytdAnnuityGoal = $yearToDateDollar;

$response = json_encode($response, JSON_PRETTY_PRINT | JSON_PARTIAL_OUTPUT_ON_ERROR);

echo $response;

?>