<?php

/**
 * The SurveysController class is a Controller that shows a user a list of surveys 
 * grouped by company in the database
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
class SurveysController extends Controller
{
    /**
     * Handle the page request
     *
     * @param array $request the page parameters from a form post or query string
     */
    protected function handleRequest(&$request)
    {
        $user = $this->getUserSession();
        $this->assign('user', $user);

        // Get all companies
        $companies = Company::queryRecords($this->pdo, array('sort' => 'company_name'));
        
        // Group surveys by company
        $companiesWithSurveys = array();
        foreach ($companies as $company)
        {
            $surveys = $company->getSurveys($this->pdo);
            if (!empty($surveys))
            {
                $company->surveys = $surveys;
                $companiesWithSurveys[] = $company;
            }
        }
        
        // Also get surveys without a company
        $unassignedSurveys = Survey::queryRecordsWithWhereClause(
            $this->pdo, 
            'company_id IS NULL', 
            array()
        );
        
        $this->assign('companies', $companiesWithSurveys);
        $this->assign('unassignedSurveys', $unassignedSurveys);

        if (isset($request['status']) && $request['status'] == 'deleted')
            $this->assign('statusMessage', 'Assessment deleted successfully');
    }
}

?>