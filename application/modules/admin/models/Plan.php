<?php

class Admin_Model_Plan extends Application_Model_Base {

    protected $_db;
    private $plan;
    public $_session;

    public function __construct() {
        parent::__construct();
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $this->_session = Zend_Registry::get("session");

        $config = array(
            'name' => 'plan',
            'primary' => 'PlanID'
        );
        $this->plan = new Zend_Db_Table($config);
    }

    public function getGridPlan($searchData = array()) {
        $grid = $this->setGrid("plan");
        $where = "plan.IsDeleted = '0'";
        $where .= $this->getSearchWhereString($searchData);
        $select = $this->_db->select()
                ->from(array("plan" => "plan"), array(
                    'plan.PlanID',
                    'plan.PlanName',
                    'plan.Price',
                    'plan.HashPower',
                    'plan.PowerType',
                    '(CASE WHEN (plan.IsActive = "1") THEN "active" ELSE "inactive" END) as status'
                ))
                ->join(array("coin" => "coin"), "coin.CoinID = plan.CoinID and coin.IsDeleted='0'", array("coin.CoinName"))
                ->order("plan.PlanName ASC")
                ->where($where);
        $fselect = $this->_db->select()
                ->from(array("t" => new Zend_Db_Expr("(SELECT @i:=0)")), array('(@i:=@i+1) as Serialno'))
                ->from(array("temp" => new Zend_Db_Expr('(' . $select . ')')), array(
            'temp.PlanID',
            "temp.CoinName",
            'temp.PlanName',
            'temp.Price',
            'temp.HashPower',
            'temp.PowerType',
            'temp.status'
        ));
        
        $grid->query($fselect);
        $this->queryLog($grid->getSelect(), true);
        return $grid;
    }
    
    public function getPlanByCategoryID($CoinID = '', $isAjax = false) {

        $cacheFileName = "Plan_getPlanByCategoryID_" . $CoinID;
        $planData = $this->getCacheData($cacheFileName, "site");
       
        if (empty($planData)) {
            $query = $this->_db->select()->from("plan", array("plan.PlanID", "plan.PlanName"))
                    ->where("IsDeleted = '0' AND plan.PlanName IS NOT NULL ");
            if($CoinID != '')
                $query->where("CoinID = ?",$CoinID);
            
            $planData = $this->fetchData($query, $cacheFileName, "site");
        }
        if (!empty($planData)) {
            if (!$isAjax) {
                $site = array();
                foreach ($planData as $key => $value) {
                    $plan[$value["PlanID"]] = ucwords($value["PlanName"]);
                }

                return $plan;
            } else
                return $planData;
        }
        return array();
    }

    public function addPlan($formData) {
        try {
            $this->_db->beginTransaction();

            $Data = array(
                'PlanName' => $formData['PlanName'],
                'CoinID' => $formData['CoinID'],
                'Description' => $formData['Description'],
                'Price' => $formData['Price'],
                'HashPower' => $formData['HashPower'],
                'IsActive' => $formData['IsActive'],
                'CreateBy' => $this->_session->UserID
            );
            $this->plan->insert($Data);
            $this->queryLog();


            $this->_db->commit();
            $this->removeCacheByTag(array("grid_plan"));
            $this->removeCacheByTag(array("plan"));
            return true;
        } catch (Exception $e) {
            $this->rollback($e);
        }
    }

    public function editPlan($formData) {
        try {
            $this->_db->beginTransaction();
            $planData = array('PlanName' => $formData['PlanName'],
                'CoinID' => $formData['CoinID'],
                'Description' => $formData['Description'],
                'Price' => $formData['Price'],
                'HashPower' => $formData['HashPower'],
                'IsActive' => $formData['IsActive'],
            );
            $where = "PlanID = " . $formData["PlanID"];
            $this->plan->update($planData, $where);

            $this->queryLog();
            $this->_db->commit();
            $this->removeCacheByTag(array("grid_plan"));
            $this->removeCacheByTag(array("plan"));
            return true;
        } catch (Exception $e) {
            $this->rollback($e);
        }
    }

    public function getPlanByID($planID) {
        $cacheFileName = "Plan_getPlanByID_" . $planID;
        $planData = $this->getCacheData($cacheFileName, "plan");
        if (empty($planData)) {
            $where = "plan.IsDeleted = '0'";
            if (!empty($planID)) {
                $where .= " AND plan.PlanID = '" . $planID . "'";
            }

            $select = $this->_db->select()
                    ->from(array("plan" => "plan"), array("plan.PlanID", "plan.PlanName", "plan.Description","plan.Price", "plan.HashPower", "plan.PowerType","plan.IsActive", '(CASE WHEN (plan.IsActive = "1") THEN "active" ELSE "inactive" END) as status'))
                    ->join(array("coin" => "coin"), "coin.CoinID = plan.CoinID and coin.IsDeleted='0'", array("coin.CoinID","coin.CoinName"))
                    ->where($where);
            $planData = $this->fetchRowData($select, $cacheFileName, "plan");
        }
        if (!empty($planData)) {
            return $planData;
        }
        return array();
    }

    public function deletePlan($PlanID) {
        try {
            $this->_db->beginTransaction();

            $row['IsDeleted'] = '1';
            //$row['CreateBy'] = $this->_session->UserID;
            $where = "PlanID = " . $PlanID;
            $this->plan->update($row, $where);
            $this->queryLog();
            $this->_db->commit();
            $this->removeCacheByTag(array("grid_plan"));
            $this->removeCacheByTag(array("plan"));
            return true;
        } catch (Exception $e) {
            $this->rollback($e);
        }
    }

    public function planExist($params) {
        $select = $this->_db->select()
                ->from('plan', array("COUNT(*) as cnt"))
                ->join(array("coin" => "coin"), "coin.CoinID = plan.CoinID and coin.IsDeleted='0'", array("coin.CoinName"))
                ->where("plan.IsDeleted ='0'  AND plan.PlanName = '" . $params['PlanName'] . "'");
        if (!empty($params['PlanID'])) {
            $select->where("plan.PlanID != ?", $params['PlanID']);
        }
        $planCount = $this->fetchRow($select);
        return ($planCount['cnt'] == '0') ? false : true;
    }

}
