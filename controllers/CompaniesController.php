<?php

/**
 * The CompaniesController class manages companies
 */
class CompaniesController extends Controller
{
    protected function handleRequest(&$request)
    {
        $user = $this->getUserSession();
        $this->assign('user', $user);

        if (isset($request['action']))
            $this->handleAction($request);

        $companies = Company::queryRecords($this->pdo, array('sort' => 'company_name'));
        
        // Add survey count to each company
        foreach ($companies as $company)
        {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM survey WHERE company_id = ?");
            $stmt->execute(array($company->company_id));
            $company->survey_count = $stmt->fetchColumn();
        }
        
        $this->assign('companies', $companies);

        if (isset($request['status']))
        {
            if ($request['status'] == 'added')
                $this->assign('statusMessage', 'Company added successfully');
            elseif ($request['status'] == 'deleted')
                $this->assign('statusMessage', 'Company deleted successfully');
        }
    }
    protected function handleAction(&$request)
    {
        switch ($request['action'])
        {
            case 'add_company':
                $this->addCompany($request);
                break;

            case 'delete_company':
                $this->deleteCompany($request);
                break;
        }
    }

    protected function addCompany(&$request)
    {
        if (!empty($request['company_name']))
        {
            $company = new Company();
            $company->company_name = trim($request['company_name']);
            $company->storeRecord($this->pdo);
            $this->redirect('companies.php?status=added');
        }
    }

    protected function deleteCompany(&$request)
    {
        if (!empty($request['company_id']))
        {
            // Note: This will set company_id to NULL for all surveys belonging to this company
            $company = Company::queryRecordById($this->pdo, $request['company_id']);
            if ($company)
            {
                $company->deleteRecord($this->pdo);
                $this->redirect('companies.php?status=deleted');
            }
        }
    }
}

?>