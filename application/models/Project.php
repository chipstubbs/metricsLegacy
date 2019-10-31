<?php

class Project extends ActiveRecord\Model
{
    static $belongs_to = array(
        array('company')
    );

    static $has_many = array(
        array("project_has_tasks"),
        array('project_has_files'),
        array('project_has_workers'),
        array('project_has_invoices'),
        array('project_has_activities',
            'order' => 'datetime DESC'),
        array('messages'),
        array('production')
    );

    static function getEventsByYearAndCompany($fields, $year, $company_id)
    {
        $events = Project::find_by_sql("SELECT $fields FROM projects WHERE name=6 and company_id=$company_id and YEAR(STR_TO_DATE(event_date, '%m/%d/%Y')) = $year and STR_TO_DATE(event_date, '%m/%d/%Y') < CURRENT_DATE()");
        return $events;
    }
}
