<?php

class Admin_Model_Coin extends Application_Model_Base {

    protected $_db;
    private $coin;
    public $_session;

    public function __construct() {
        parent::__construct();
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $this->_session = Zend_Registry::get("session");

        $config = array(
            'name' => 'coin',
            'primary' => 'CoinID'
        );
        $this->coin = new Zend_Db_Table($config);
    }

    public function getGridCoin($searchData = array()) {
        $grid = $this->setGrid("coin");
        $where = "coin.IsDeleted = '0'";
        $where .= $this->getSearchWhereString($searchData);
        $select = $this->_db->select()
                ->from(array("coin" => "coin"), array(
                    'coin.CoinID',
                    'coin.CoinName',
                    '(CASE WHEN (coin.IsActive = "1") THEN "active" ELSE "inactive" END) as status'
                ))
                ->order("coin.CoinName ASC")
                ->where($where);
        $fselect = $this->_db->select()
                ->from(array("t" => new Zend_Db_Expr("(SELECT @i:=0)")), array('(@i:=@i+1) as Serialno'))
                ->from(array("temp" => new Zend_Db_Expr('(' . $select . ')')), array(
            'temp.CoinID',
            'temp.CoinName',
            'temp.status'
        ));

        $grid->query($fselect);
        $this->queryLog($grid->getSelect(), true);
        return $grid;
    }
    
    public function getCoinByCategoryID($CoincategoryID = '', $isAjax = false) {

        $cacheFileName = "Coin_getCoinByCategoryID_" . $CoincategoryID;
        $coinData = $this->getCacheData($cacheFileName, "site");
       
        if (empty($coinData)) {
            $query = $this->_db->select()->from("coin", array("coin.CoinID", "coin.CoinName"))
                    ->where("IsDeleted = '0' AND coin.CoinName IS NOT NULL ");
           
            $coinData = $this->fetchData($query, $cacheFileName, "site");
        }
        if (!empty($coinData)) {
            if (!$isAjax) {
                $site = array();
                foreach ($coinData as $key => $value) {
                    $coin[$value["CoinID"]] = ucwords($value["CoinName"]);
                }

                return $coin;
            } else
                return $coinData;
        }
        return array();
    }

    public function addCoin($formData) {
        try {
            $this->_db->beginTransaction();

            $Data = array(
                'CoinName' => $formData['CoinName'],
                'Description' => $formData['Description'],
                'IsActive' => $formData['IsActive'],
                'CreateBy' => $this->_session->UserID
            );
            $this->coin->insert($Data);
            $this->queryLog();


            $this->_db->commit();
            $this->removeCacheByTag(array("grid_coin"));
            $this->removeCacheByTag(array("coin"));
            return true;
        } catch (Exception $e) {
            $this->rollback($e);
        }
    }

    public function editCoin($formData) {
        try {
            $this->_db->beginTransaction();
            $coinData = array('CoinName' => $formData['CoinName'],
               'Description' => $formData['Description'],
                'IsActive' => $formData['IsActive'],
            );
            $where = "CoinID = " . $formData["CoinID"];
            $this->coin->update($coinData, $where);

            $this->queryLog();
            $this->_db->commit();
            $this->removeCacheByTag(array("grid_coin"));
            $this->removeCacheByTag(array("coin"));
            return true;
        } catch (Exception $e) {
            $this->rollback($e);
        }
    }

    public function getCoinByID($coinID) {
        $cacheFileName = "Coin_getCoinByID_" . $coinID;
        $coinData = $this->getCacheData($cacheFileName, "coin");
        if (empty($coinData)) {
            $where = "coin.IsDeleted = '0'";
            if (!empty($coinID)) {
                $where .= " AND coin.CoinID = '" . $coinID . "'";
            }

            $select = $this->_db->select()
                    ->from(array("coin" => "coin"), array("coin.CoinID", "coin.CoinName", "coin.Description", "coin.IsActive", '(CASE WHEN (coin.IsActive = "1") THEN "active" ELSE "inactive" END) as status'))
                    ->where($where);
            $coinData = $this->fetchRowData($select, $cacheFileName, "coin");
        }
        if (!empty($coinData)) {
            return $coinData;
        }
        return array();
    }

    public function deleteCoin($CoinID) {
        try {
            $this->_db->beginTransaction();

            $row['IsDeleted'] = '1';
            //$row['CreateBy'] = $this->_session->UserID;
            $where = "CoinID = " . $CoinID;
            $this->coin->update($row, $where);
            $this->queryLog();
            $this->_db->commit();
            $this->removeCacheByTag(array("grid_coin"));
            $this->removeCacheByTag(array("coin"));
            return true;
        } catch (Exception $e) {
            $this->rollback($e);
        }
    }

    public function coinExist($params) {
        $select = $this->_db->select()
                ->from('coin', array("COUNT(*) as cnt"))
                ->where("coin.IsDeleted ='0'  AND coin.CoinName = '" . $params['CoinName'] . "'");
        if (!empty($params['CoinID'])) {
            $select->where("coin.CoinID != ?", $params['CoinID']);
        }
        $coinCount = $this->fetchRow($select);
        return ($coinCount['cnt'] == '0') ? false : true;
    }

}
