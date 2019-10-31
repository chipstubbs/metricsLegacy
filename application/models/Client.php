<?php

class Client extends ActiveRecord\Model
{
    static $has_many = array(
        array('projects'),
        array('invoices')
    );

    static $belongs_to = array(
        array('company')
    );

    static function insert_csv($data)
    {
        $this->db->insert_batch('clients', $data);
    }

//    static function getAllActiveClientsByCompany($company_id)
//    {
//        return Client::all(array('conditions' => 'inactive = 0 and company_id =' . $company_id));
//    }
//    static function getAllActiveClientsByEventIDArray($fields,$event_ids){
//        $clients = Client::find_by_sql('SELECT '.$fields.' FROM clients JOIN projects ON projects.id= clients.event_id WHERE (inactive= 0) AND clients.event_id IN ( '.$event_ids.')');
//        return $clients;
//    }

    static function getAllActiveClientsByEventForYear($fields,$company_id,$year){
//        $query = "SELECT $fields FROM clients JOIN projects ON projects.id= clients.event_id JOIN production ON clients.id= production.client_id WHERE (inactive= 0 AND clients.company_id= $company_id) AND (YEAR(STR_TO_DATE(projects.event_date, '%m/%d/%Y'))=$year OR (YEAR(production.prem_paid_month) = $year AND clients.event_id in ( 1, 2, 3 )))";
//        $query = "SELECT $fields FROM clients JOIN projects ON projects.id= clients.event_id WHERE (inactive= 0 AND clients.company_id= $company_id) AND (YEAR(STR_TO_DATE(projects.event_date, '%m/%d/%Y'))=$year OR clients.event_id in ( 1, 2, 3 ))";
        $query = "SELECT $fields FROM clients JOIN projects ON projects.id= clients.event_id WHERE (inactive= 0 AND clients.company_id= $company_id) AND YEAR(STR_TO_DATE(projects.event_date, '%m/%d/%Y'))=$year";
        $clients = Client::find_by_sql($query);
        return $clients;
    }

    static function getAllActiveClients_SR_UR_who_did_production($fields,$company_id,$year){
//        $query = "SELECT $fields FROM clients JOIN projects ON projects.id= clients.event_id JOIN production ON clients.id= production.client_id WHERE (inactive= 0 AND clients.company_id= $company_id) AND (YEAR(STR_TO_DATE(projects.event_date, '%m/%d/%Y'))=$year OR (YEAR(production.prem_paid_month) = $year AND clients.event_id in ( 1, 2, 3 )))";
//        $query = "SELECT $fields FROM clients JOIN projects ON projects.id= clients.event_id WHERE (inactive= 0 AND clients.company_id= $company_id) AND (YEAR(STR_TO_DATE(projects.event_date, '%m/%d/%Y'))=$year OR clients.event_id in ( 1, 2, 3 ))";
        $query = "SELECT $fields FROM clients JOIN projects ON projects.id= clients.event_id JOIN production ON clients.id= production.client_id WHERE (inactive= 0 AND clients.company_id= $company_id ) AND YEAR(production.prem_paid_month) = $year AND clients.event_id in ( 1, 2, 3 ) GROUP BY clients.id";
        $clients = Client::find_by_sql($query);
        return $clients;
    }
}
