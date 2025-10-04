<?php

/**
 * The Company class is a Model representing the company table
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class Company extends Model
{
    // The primary key used to uniquely identify a record
    protected static $primaryKey = 'company_id';
    
    // The list of fields in the table
    protected static $fields = array(
        'company_id',
        'company_name',
    );
    
    /**
     * Get all surveys for this company
     *
     * @param PDO $pdo the database to search in
     * @return array returns array of Survey objects
     */
    public function getSurveys(PDO $pdo)
    {
        $search = array('company_id' => $this->company_id, 'sort' => 'survey_name');
        return Survey::queryRecords($pdo, $search);
    }
}

?>