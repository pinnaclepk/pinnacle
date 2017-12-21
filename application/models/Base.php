<?php
class Application_Model_Base extends Zend_Db_Table {

    public $Log_File;
    protected $__profiler;
    private $_enableCache;
    protected $_apiname;

    public function __construct() {

        if (!Zend_Registry::isRegistered('db')) {
            $this->connection();
        }
       // $this->initializeLog();

    }

    protected function connection() {
        $clientConfig = Zend_Registry::get("clientConfig");
        $db = Zend_Db::factory($clientConfig->resources->db->adapter, $clientConfig->resources->db->params->toArray()
        );
        $db->getProfiler()->setEnabled(true);
        $this->__profiler = $db->getProfiler();
        Zend_Registry::set('db', $db);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        $this->name = "";
    }
    public function initializeLog() {
        if (Zend_Registry::isRegistered("session")) {
            $session = Zend_Registry::get("session");
            $name = $session->UserName;
        }
        $logDir = Zend_Registry::Get("LogDir");
		$log_dir = $logDir . "/Default/";
        
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0775, true);
        }
        $FILE_LOG = $log_dir . "/" . date("Y-m-d") . ".log";
        $writer = new Zend_Log_Writer_Stream($FILE_LOG, 'a');
        $formatter = new Zend_Log_Formatter_Simple('%timestamp% %priorityName% (%priority%) : %message%' . PHP_EOL . PHP_EOL);
        $writer->setFormatter($formatter);
        $this->Log_File = new Zend_Log();
        $this->Log_File->addWriter($writer);
        $this->Log_File->setTimestampFormat("Y-m-d H:i:s");
    }

    public function paramLog($requestUrl, $str) {
        if (Zend_Registry::isRegistered("session")) {
            $session = Zend_Registry::get("session");
            $name = $session->UserName;
        }
        $logDir = Zend_Registry::Get("LogDir");
        if (!empty($requestUrl)) {
            $log_dir = $logDir . "api/" . $requestUrl . "/api";
        } else {
            if (isset($name) && $name != '') {
                $log_dir = $logDir . "/" . trim($name);
            } else {
                $log_dir = $logDir . "/Default/";
            }
        }
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0775, true);
        }
        $FILE_LOG = $log_dir . "/" . date("Y-m-d") . ".log";
        $writer = new Zend_Log_Writer_Stream($FILE_LOG, 'a');
        $formatter = new Zend_Log_Formatter_Simple('%timestamp% %priorityName% (%priority%) ' . Zend_Registry::get('uuid') . ': %message%' . PHP_EOL . PHP_EOL);
        $writer->setFormatter($formatter);
        $logger = new Zend_Log();
        $logger->addWriter($writer);
        $logger->info($str, Zend_Log::DEBUG);
    }

    protected function setGrid($model = '', $id = '') {

        $view = new Zend_View();
        $view->setEncoding('ISO-8859-1');
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/grid.ini', 'production');
        $grid = Bvb_Grid::factory('Table', $config, $id);
        Zend_Registry::set("gridConfig", $config);
        if (!$grid->getParam("perPage")) {
            $grid->clearParams();
        }
        $grid->setRecordsPerPage($config->grid->recordsPerPage);
        $grid->setEscapeOutput(false);
        $grid->setExport(array());
        $grid->setView($view);
        $grid->setNoFilters(1);
        $grid->saveParamsInSession(true);
        $grid->setPaginationInterval(array(50 => 50, 100 => 100, 500 => 500, 1000 => 1000));
        if ($this->_enableCache && !empty($model)) {
            $grid->setCache(array('enable' => true, 'instance' => Zend_Registry::get('cacheObj'), 'tag' => 'grid_' . $model));
        }
        return $grid;
    }

    protected function queryLog($querystr = "", $isProcess = false) {
        /*$elepstime = 0;
        if (!$isProcess) {
            $db = Zend_Db_Table_Abstract::getDefaultAdapter();
            $db->getProfiler()->setEnabled(true);
            $this->__profiler = $db->getProfiler();
            $query = $this->__profiler->getLastQueryProfile();
            $params = $query->getQueryParams();
            $querystr = $query->getQuery();
            $elepstime =  $this->__profiler->getTotalElapsedSecs();
            foreach ($params as $par) {
                $querystr = preg_replace('/\\?/', "'" . $par . "'", $querystr, 1);
            }
        }
        $querystr = is_array($querystr) ? var_export($querystr,1) : $querystr;
        $this->Log_File->info('time : '.$elepstime.' : '.$querystr, Zend_Log::DEBUG); */
    }

    public function convertArrayToOptions($data = array(), $fields = array()) {
        $tempArr = array();
        if (count($data)) {
            foreach ($data as $value) {
                if (!empty($fields[0])) {
                    $tempArr[$value[$fields[0]]] = $value[$fields[1]];
                } else {
                    $tempArr[] = $value[$fields[1]];
                }
            }
        }

        return $tempArr;
    }
    
    public function checkEntry($params, $parentTable, $keys = '') {
        $select = $this->_db->select()
                ->from(array($parentTable => $parentTable), array('COUNT(*) as num'));
        if(is_array($key))
        {
            foreach($keys as $key)
                $select->where($parentTable . '.' . $key . " = '" . $params[$key] . "'");
        }
        else
            $select->where($parentTable . '.' . $keys . " = '" . $params[$key] . "'");
                
        
        
        $cnt = $this->fetchRowData($select);
        return $cnt['num'];
    }

    public function fetchData($select, $cacheFileName = '', $cachePath = '', $lifeTime = '') {
        $data = $this->_getDataArray($select);
        $this->queryLog();
        if ($this->_enableCache && $cacheFileName != '') {
            $this->_setCacheData($data, $cacheFileName, $cachePath, $lifeTime);
        }
        return $data;
    }
    
    public function fetchRow($where = null, $order = null, $offset = null) {
        try {
            $data = $this->_db->fetchRow($where, $order, $offset);
            $this->queryLog();
            return $data;
        } catch (Zend_Db_Statement_Exception $e) {
            throw new Zend_Db_Statement_Exception($e->getMessage());
        }
    }
    
    private function _getDataArray($select) {
        try {
            $qry = $this->_db->query($select);
            return $qry->fetchAll();
        } catch (Zend_Db_Statement_Exception $e) {
            throw new Zend_Db_Statement_Exception($e->getMessage());
        }
    }

    private function _setCacheData($data, $cacheFileName = '', $cachePath = '', $lifeTime = '') {
        $appCachePath = Zend_Registry::get("cachePath");
        $objCache = new Cache();
        $objCache->cacheDirectory = $appCachePath . "/" . $cachePath;
        if (!empty($lifeTime)) {
            $cacheLifetime = $lifeTime;
        } else {
            $cacheLifetime = $objCache->getCacheLifeTime();
        }

        $tagsArray = explode('/', $cachePath);
        $finalTagsArray = array();
        if (count($tagsArray) > 0) {
            $tagString = '';
            foreach ($tagsArray as $key => $value) {
                $tagString .= $value . '_';
                if (trim($tagString, '_') != '') {
                    $finalTagsArray[] = trim($tagString, '_');
                }
            }
        }
        $objCache->createCache($data, $cacheFileName, $finalTagsArray, $cacheLifetime);
        $objCache->__destruct();
    }

    public function fetchRowData($select, $cacheFileName = '', $cachePath = '', $lifeTime = '') {
        try {
            $data = $this->_db->fetchRow($select);
            $this->queryLog();
            if ($this->_enableCache && $cacheFileName != '') {
                $this->_setCacheData($data, $cacheFileName, $cachePath, $lifeTime);
            }
            return $data;
        } catch (Zend_Db_Statement_Exception $e) {
            throw new Zend_Db_Statement_Exception($e->getMessage());
        }
    }

    public function fetchPairsData($select, $cacheFileName = '', $cachePath = '', $lifeTime = '') {
        try {
            $data = $this->_db->fetchPairs($select);
            $this->queryLog();
            if ($this->_enableCache && $cacheFileName != '') {
                $this->_setCacheData($data, $cacheFileName, $cachePath, $lifeTime);
            }
            return $data;
        } catch (Zend_Db_Statement_Exception $e) {
            throw new Zend_Db_Statement_Exception($e->getMessage());
        }
    }

    public function fetchOne($sql, $bind = array()) {
        try {
            $data = $this->_db->fetchOne($sql, $bind);
            $this->queryLog();
            return $data;
        } catch (Zend_Db_Statement_Exception $e) {
            throw new Zend_Db_Statement_Exception($e->getMessage());
        }
    }

    public function getCacheData($cacheName, $cachePath = '') {
        if ($this->_enableCache) {
            $cachePath = $cachePath;
            $cacheName = $this->processParameters($cacheName);
            $objCache = new Cache();
            return $objCache->getResult($cacheName);
        }
        return false;
    }

    public function removeCache($cacheFileName = '') {
        if ($this->_enableCache && $cacheFileName != '') {
            $cacheFileName = $this->processParameters($cacheFileName);
            if (!empty($cacheFileName)) {
                $cacheObj = new Cache();
                $cacheObj->removeCache($cacheFileName);
            }
        }
    }

    public function removeCacheByTag($tags = array()) {
        if ($this->_enableCache) {
            if (count($tags) > 0) {
                $cacheObj = new Cache();
                $cacheObj->removeCacheMatchingTags($tags);
            }
        }
    }

    public function rollback($e) {

//        if (APPLICATION_ENV == 'testing') {
//            $errorparams = array("message" => $e->getMessage(), "exception" => $e->getMessage(), "error" => $e->getTraceAsString(), "trace" => $e->getTraceAsString());
//            $commonObj = new Application_Model_Common();
//            $commonObj->testErrorLog($errorparams);
//        }
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $db->rollback();
        /*$priority = "ERROR (" . Zend_Log::INFO . ")";
        $emailBody = date('y-m-d\Th:i:s') . " : " . $priority . "  \n\n";
        $emailBody .= "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n\n";
        if (isset($_SERVER['HTTP_REFERER'])) {
            $emailBody .= "HTTP_REFERER: " . $_SERVER['HTTP_REFERER'] . "\n\n";
        }
        $username = Zend_Auth::getInstance()->getIdentity();
        $code = '';
        $errorCode = $e->getCode();
        if (!empty($errorCode)) {
            $code = '(' . $e->getCode() . ')';
        }
        $emailBody .= $e->getMessage() . " - " . $username . "\r\n\n";
        $emailBody .= "File : " . $e->getFile() . "\r\n\n" .
                $emailBody .= $e->getTraceAsString() . " - " . $username . "\r\n\n";
        $this->mailLog($emailBody, Zend_Log::INFO); */
    }

    public function mailLog($emailBody, $logType) {

        $config = Zend_Registry::get('application');
        $tr = new Zend_Mail_Transport_Smtp($config->smtp);
        Zend_Mail::setDefaultTransport($tr);
        $mail = new Zend_Mail();
        $mail->setFrom($config->adminFromEmail, '');
        $mail->addTo($config->adminToEmail, $config->adminName);
        $subject = 'An Error Occured';
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $db->getProfiler()->setEnabled(true);
        $this->__profiler = $db->getProfiler();
        $query = $this->__profiler->getLastQueryProfile();
        $queryProfiles = $this->__profiler->getQueryProfiles(null, true);
        $query = $queryProfiles[$this->__profiler->getTotalNumQueries() - 1];
        $querystr = $query->getQuery();
        if ($querystr == "rollback") {
            $query = $queryProfiles[$this->__profiler->getTotalNumQueries() - 2];
            $querystr = $query->getQuery();
        }
        $params = $query->getQueryParams();
        foreach ($params as $par) {
            $querystr = preg_replace('/\\?/', "'" . $par . "'", $querystr, 1);
        }
        $emailBody .= "Query : " . $querystr . "\r\n\n";
		print_r($emailBody);
		exit;
        //sendMail($emailBody, $logType, $subject);

    }

    public function getSearchWhereString($searchData = array()) {
        $where = '';
        unset($searchData['module']);
        unset($searchData['controller']);
        unset($searchData['action']);
        
        if (count($searchData)) {
            $value = '';
            foreach ($searchData as $val) {
                $searchValue = isset($val['value']) ? $val['value'] : '';
                if ($searchValue != '') {
                    if ($val['op'] == "LIKE") {
                        $value = '%' . $searchValue . '%';
                    } else {
                        $value = $searchValue;
                    }
                    $where .= " AND lower(" . $val['column'] . ') ' . $val['op'] . " '" . strtolower($value) . "'";
                }
            }
        }
        return $where;
    }

    public function processParameters($string) {
        return str_replace(array('-', '.'), '', preg_replace('/[^A-Za-z0-9\s.\s-]/', '', strip_tags($string, "")));
    }
    
    public function getPrecisionOfUser() {
        $objSession = Zend_Registry::get("session");
        $precision = 2;
        if (!empty($objSession->DecimalPrecision)) {
            $precision = $objSession->DecimalPrecision;
        }
        return $precision;
    }

}

?>
