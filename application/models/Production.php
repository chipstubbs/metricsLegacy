<?php

class Production extends ActiveRecord\Model
{
    static $table_name = 'production';

//    static function getProductionByYear($year, $company_id)
//    {
//        return Production::find_by_sql('SELECT production.*, clients.firstname, clients.lastname, clients.event_id, clients.acat, clients.acat_date_received, clients.acat_received_amount, clients.annuity_date_received, clients.annuity_received_amount, clients.life_date_received, clients.life_received_amount, clients.other_date_received, clients.other_received_amount FROM production JOIN clients ON production.client_id = clients.id WHERE clients.inactive = 0 AND production.company_id = ' . $company_id . ' AND YEAR(prem_paid_month) = ' . $year);
//    }
//
//    static function getProductionEntriesByClientIDArray($fields, $client_ids)
//    {
//        $production = Production::find_by_sql("SELECT $fields FROM production JOIN clients ON production.client_id = clients.id JOIN projects ON clients.event_id = projects.id WHERE clients.inactive = 0 AND  CHAR_LENGTH(production.prem_paid_month) > 0 AND clients.id IN ($client_ids)");
//        return $production;
//    }

    static function getProductionEntriesByEventYear($fields, $company_id, $year)
    {
        $production = Production::find_by_sql("SELECT $fields FROM production JOIN clients ON production.client_id = clients.id JOIN projects ON clients.event_id = projects.id WHERE (clients.inactive = 0 AND production.company_id= $company_id AND CHAR_LENGTH(production.prem_paid_month) > 0) AND (YEAR(STR_TO_DATE(projects.event_date, '%m/%d/%Y'))= $year OR (YEAR(production.prem_paid_month) = $year AND clients.event_id in ( 1, 2, 3 ))) ORDER BY client_id");
        return $production;
    }

//    static function getProductionEntriesByYear($fields, $year)
//    {
//        $production = Production::find_by_sql("SELECT $fields FROM production JOIN clients ON production.client_id = clients.id JOIN projects ON clients.event_id= projects.id WHERE clients.inactive= 0 AND YEAR(prem_paid_month)= $year");
//        return $production;
//    }

    static function getProductionEntriesByYearAndCompany($fields, $company_id, $year)
    {
        //Gets production based off event date
        //for referrals and clients it comes from paid date
        $production = Production::find_by_sql("SELECT ".$fields." FROM production JOIN clients ON production.client_id = clients.id JOIN projects ON clients.event_id = projects.id WHERE (clients.inactive= 0 AND production.company_id= ".$company_id.") AND (production.prem_paid > 0 AND production.production_type = 'annuity') AND ( CASE WHEN (projects.id = 1 OR projects.id = 2 OR projects.id = 3) THEN YEAR(production.prem_paid_month) = ". $year ." ELSE production.prem_paid > 0 AND  YEAR(STR_TO_DATE(projects.event_date, '%m/%d/%Y')) = ".$year." END)");
        $productionOld = Production::find_by_sql("SELECT $fields FROM production JOIN clients ON production.client_id = clients.id JOIN projects ON clients.event_id = projects.id WHERE clients.inactive= 0 AND production.company_id= $company_id AND YEAR(prem_paid_month)= $year");

        return $production;
    }

    static function getProduction($fields, $company_id, $year)
    {
        //Gets production based solely off paid date
        $production = Production::find_by_sql("SELECT ".$fields." FROM production JOIN clients ON production.client_id = clients.id JOIN projects ON clients.event_id = projects.id WHERE (clients.inactive= 0 AND production.company_id= ".$company_id.") AND (production.prem_paid > 0 ) AND ( CASE WHEN (projects.id = 1 OR projects.id = 2 OR projects.id = 3) THEN YEAR(production.prem_paid_month) = ". $year ." ELSE production.prem_paid > 0 AND  YEAR(STR_TO_DATE(projects.event_date, '%m/%d/%Y')) = ".$year." END)");

        return $production;
    }

}
