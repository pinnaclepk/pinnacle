<?php

require_once('Zend/Mail.php');

class Application_Model_Common extends Application_Model_Base {

    public function __construct() {
        parent::__construct();
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $this->_session = Zend_Registry::get("session");
    }

    public function getState($StateID = '', $fields = '*', $convert = true) {
        $arrWhere = array();
        if (!empty($StateID)) {
            $cacheFileName = "State_getState_" . $StateID;
            $arrWhere[] = "state.StateID = '" . $StateID . "'";
        } else {
            $cacheFileName = "State_getState";
        }
        $fields = "state.StateID, state.StateName";

        $stateData = $this->getCacheData($cacheFileName, "state");
        if (empty($stateData)) {
            $arrWhere[] = "state.IsDeleted = '0'";
            $where = implode(" AND ", $arrWhere);
            $select = $this->_db->select()
                    ->from(array("state" => "state"), array(new Zend_DB_Expr($fields)))
                    ->where($where);
            $stateData = $this->fetchData($select, $cacheFileName, "state");
        }
        if ($convert) {
            $stateData = $this->fetchPairsData($select, $cacheFileName, "state");
        }
        return count($stateData) ? $stateData : array();
    }

    public function getClient($ClientID = '', $fields = '*', $convert = true) {
        $arrWhere = array();
        if (!empty($ClientID)) {
            $cacheFileName = "Client_getClient_" . $ClientID;
            $arrWhere[] = "client.ClientID = '" . $ClientID . "'";
        } else {
            $cacheFileName = "Client_getClient";
        }
        $fields = "client.ClientID, client.BusinessName";

        $clientData = $this->getCacheData($cacheFileName, "client");
        if (empty($clientData)) {
            $arrWhere[] = "client.IsDeleted = '0'";
            $where = implode(" AND ", $arrWhere);
            $select = $this->_db->select()
                    ->from(array("client" => "client"), array(new Zend_DB_Expr($fields)))
                    ->where($where);
            $clientData = $this->fetchData($select, $cacheFileName, "client");
        }
        if ($convert) {
            $clientData = $this->fetchPairsData($select, $cacheFileName, "client");
        }
        return count($clientData) ? $clientData : array();
    }

    public function getUsertype($UsertypeID = '', $fields = '*', $convert = true) {
        $arrWhere = array();
        if (!empty($UsertypeID)) {
            $cacheFileName = "UsertypeID_getUsertype_" . $UsertypeID;
            $arrWhere[] = "usertype.ID = '" . $UsertypeID . "'";
        } else {
            $cacheFileName = "UsertypeID_getUsertype";
        }
        $fields = "usertype.ID as UserTypeID, usertype.TypeName";

        $usertypeData = $this->getCacheData($cacheFileName, "usertype");
        if (empty($usertypeData)) {
            $arrWhere[] = "usertype.IsActive = '1' AND usertype.ID != 1";
            $where = implode(" AND ", $arrWhere);
            $select = $this->_db->select()
                    ->from(array("usertype" => "usertype"), array(new Zend_DB_Expr($fields)))
                    ->where($where);
            
            $usertypeData = $this->fetchData($select, $cacheFileName, "usertype");
        }
        if ($convert) {
            $usertypeData = $this->fetchPairsData($select, $cacheFileName, "usertype");
        }
        return count($usertypeData) ? $usertypeData : array();
    }

    public function getSite($SiteID = '', $fields = '*', $convert = true) {
        $arrWhere = array();
        if (!empty($SiteID)) {
            $cacheFileName = "Site_getSite_" . $SiteID;
            $arrWhere[] = "site.SiteID = '" . $SiteID . "'";
        } else {
            $cacheFileName = "Site_getSite";
        }
        $fields = "site.SiteID, CONCAT(site.SiteName,' - ', site.Address) as SiteName";

        $siteData = $this->getCacheData($cacheFileName, "site");
        if (empty($siteData)) {
            $arrWhere[] = "site.IsDeleted = '0' and site.IsActive='1'";
            $where = implode(" AND ", $arrWhere);
            $select = $this->_db->select()
                    ->from(array("site" => "site"), array(new Zend_DB_Expr($fields)))
                    ->where($where);
            $stateData = $this->fetchData($select, $cacheFileName, "site");
        }
        if ($convert) {
            $siteData = $this->fetchPairsData($select, $cacheFileName, "site");
        }
        return count($siteData) ? $siteData : array();
    }

    public function getGuard($UserID = '', $fields = '*', $convert = true) {
        $arrWhere = array();
        if (!empty($UserID)) {
            $cacheFileName = "User_getGuard_" . $UserID;
            $arrWhere[] = "user.UserID = '" . $UserID . "'";
        } else {
            $cacheFileName = "User_getGuard";
        }
        $fields = "user.UserID, CONCAT(user.FName,' ', user.LName, ' ', '[',role.RoleName,']') as Guardname";

        $guardData = $this->getCacheData($cacheFileName, "user");
        if (empty($guardData)) {
            $arrWhere[] = "user.RoleID != 1 AND user.IsDeleted = '0' and user.IsActive ='1'";
            $where = implode(" AND ", $arrWhere);
            $select = $this->_db->select()
                    ->from(array("user" => "user"), array(new Zend_DB_Expr($fields)))
                    ->join(array("role" => "role"), "user.RoleID = role.RoleID", array())
                    ->where($where);
            $guardData = $this->fetchData($select, $cacheFileName, "user");
        }
        if ($convert) {
            $guardData = $this->fetchPairsData($select, $cacheFileName, "user");
        }
        return count($guardData) ? $guardData : array();
    }

    public function getGuardCompetency($UserID = '', $fields = '*', $convert = true) {
        $arrWhere = array();
        if (!empty($UserID)) {
            $cacheFileName = "User_getGuard_" . $UserID;
            $arrWhere[] = "user.UserID = '" . $UserID . "'";
        } else {
            $cacheFileName = "User_getGuard";
        }
        $fields = "user.UserID, CONCAT(user.FName,' ', user.LName, ' ', '[',role.RoleName,']') as Guardname";

        $guardData = $this->getCacheData($cacheFileName, "user");
        if (empty($guardData)) {
            $arrWhere[] = "user.RoleID != 1 AND user.IsDeleted = '0' and user.IsActive ='1'";
            $where = implode(" AND ", $arrWhere);
            $subselect = $this->_db->select()->distinct()
                    ->from(array("guardcompetency" => "guardcompetency"), array("guardcompetency.UserID"))
                    ->where("guardcompetency.IsDeleted = '0'");

            $select = $this->_db->select()
                    ->from(array("user" => "user"), array(new Zend_DB_Expr($fields)))
                    ->join(array("role" => "role"), "user.RoleID = role.RoleID", array())
                    ->where($where);

            $select->where("user.UserID NOT IN (" . new Zend_Db_Expr($subselect) . ")");
            $guardData = $this->fetchData($select, $cacheFileName, "user");
        }
        if ($convert) {
            $guardData = $this->fetchPairsData($select, $cacheFileName, "user");
        }
        return count($guardData) ? $guardData : array();
    }

    public function getCoin($CoinID = '', $fields = '*', $convert = true) {
        $arrWhere = array();
        if (!empty($CoinID)) {
            $cacheFileName = "Coin_getcoin_" . $CoinID;
            $arrWhere[] = "coin.CoinID = '" . $CoinID . "'";
        } else {
            $cacheFileName = "Coin_getCoin_";
        }
        $fields = "coin.CoinID, coin.CoinName";

        $coinData = $this->getCacheData($cacheFileName, "coin");
        if (empty($coinData)) {

            $select = $this->_db->select()
                    ->from(array("coin" => "coin"), array(new Zend_DB_Expr($fields)))
                    ->where("coin.IsDeleted='0' AND coin.IsActive='1'");

            $coinData = $this->fetchData($select, $cacheFileName, "coin");
        }
        if ($convert) {
            $coinData = $this->fetchPairsData($select, $cacheFileName, "coin");
        }
        return count($coinData) ? $coinData : array();
    }

    public function getReasonCategory($ReasonCategoryID = '', $fields = '*', $convert = true) {
        $arrWhere = array();
        if (!empty($ReasonCategoryID)) {
            $cacheFileName = "ReasonCategory_ReasonCategory_" . $ReasonCategoryID;
            $arrWhere[] = "reasoncategory.ReasonCategoryID = '" . $ReasonCategoryID . "'";
        } else {
            $cacheFileName = "ReasonCategory_ReasonCategory";
        }
        $fields = "reasoncategory.ReasonCategoryID, reasoncategory.ReasoncategoryName";

        $reasoncategoryData = $this->getCacheData($cacheFileName, "reason");
        if (empty($reasoncategoryData)) {

            $select = $this->_db->select()
                    ->from(array("reasoncategory" => "reasoncategory"), array(new Zend_DB_Expr($fields)))
                    ->where("reasoncategory.IsDeleted='0' AND reasoncategory.IsActive='1'");

            $reasoncategoryData = $this->fetchData($select, $cacheFileName, "reasoncategory");
        }
        if ($convert) {
            $reasoncategoryData = $this->fetchPairsData($select, $cacheFileName, "reasoncategory");
        }
        return count($reasoncategoryData) ? $reasoncategoryData : array();
    }

    public function getSeverity($SeverityID = '', $fields = '*', $convert = true) {
        $arrWhere = array();
        if (!empty($ReasonCategoryID)) {
            $cacheFileName = "Severity_Severity_" . $SeverityID;
            $arrWhere[] = "severity.SeverityID = '" . $SeverityID . "'";
        } else {
            $cacheFileName = "Severity_Severity";
        }
        $fields = "severity.SeverityID, severity.SeverityName";

        $severityData = $this->getCacheData($cacheFileName, "severity");
        if (empty($severityData)) {

            $select = $this->_db->select()
                    ->from(array("severity" => "severity"), array(new Zend_DB_Expr($fields)))
                    ->where("severity.IsDeleted='0' AND severity.IsActive='1'");

            $severityData = $this->fetchData($select, $cacheFileName, "severity");
        }
        if ($convert) {
            $severityData = $this->fetchPairsData($select, $cacheFileName, "severity");
        }
        return count($severityData) ? $severityData : array();
    }

    public function getActivityType($ActivitytypeID = '', $fields = '*', $convert = true) {
        $arrWhere = array();
        if (!empty($ActivitytypeID)) {
            $cacheFileName = "Activitytype_getActivitytype_" . $ActivitytypeID;
            $arrWhere[] = "activitytype.ActivitytypeID = '" . $ActivitytypeID . "'";
        } else {
            $cacheFileName = "Activitytype_getActivitytype_";
        }
        $fields = "activitytype.ActivitytypeID, activitytype.ActivitytypeName";

        $activitytypeData = $this->getCacheData($cacheFileName, "activitytype");
        if (empty($activitytypeData)) {

            $select = $this->_db->select()
                    ->from(array("activitytype" => "activitytype"), array(new Zend_DB_Expr($fields)))
                    ->where("activitytype.IsDeleted='0' AND activitytype.IsActive='1'");

            $activitytypeData = $this->fetchData($select, $cacheFileName, "activitytype");
        }
        if ($convert) {
            $activitytypeData = $this->fetchPairsData($select, $cacheFileName, "activitytype");
        }
        return count($activitytypeData) ? $activitytypeData : array();
    }

    public function getLocationBySite($SiteID = '', $fields = '*', $convert = true) {
        $arrWhere = array();
        $arrWhere[] = "location.IsDeleted = '0' and location.IsActive='1'";
        if (!empty($SiteID)) {
            $cacheFileName = "Location_getLocation_" . $SiteID;
            $arrWhere[] = "location.SiteID = '" . $SiteID . "'";
        } else {
            $cacheFileName = "Location_getLocation_";
        }
        $fields = "location.LocationID, location.Location";

        $locationData = $this->getCacheData($cacheFileName, "location");
        if (empty($locationData)) {
            $where = implode(" AND ", $arrWhere);
            $select = $this->_db->select()
                    ->from(array("location" => "location"), array(new Zend_DB_Expr($fields)))
                    ->join(array("site" => "site"), "site.SiteID = location.SiteID AND site.IsDeleted = '0'", array())
                    ->where($where);
            if ($this->_session->RoleID != 1)
                $select->join(array('siteguard' => 'siteguard'), 'site.SiteID = siteguard.SiteID', array())
                        ->where("siteguard.UserID = ?", $this->_session->UserID);
            $locationData = $this->fetchData($select, $cacheFileName, "location");
        }
        if ($convert) {
            $locationData = $this->fetchPairsData($select, $cacheFileName, "location");
        }
        return count($locationData) ? $locationData : array();
    }

    public function sendMail($to, $sub, $bcc, $from, $body) {
        //$body = str_replace($fieldName, $fieldValue, $body);
        $config = Zend_Registry::get('application');
        $translate = Zend_Registry::get("translate");
        $options = array();
        if ($config->smtpAuth == true) {
            $options = array(
                'auth' => 'login',
                'ssl' => 'ssl',
                'port' => $config->smtpPort,
                'username' => $config->smtpUsername,
                'password' => $config->smtpPassword);
        }
        $tr = new Zend_Mail_Transport_Smtp($config->smtpHost, $options);
        Zend_Mail::setDefaultTransport($tr);
        $mail = new Zend_Mail();
        $mail->setBodyHtml($body);
        $mail->setFrom($from);
        if (is_array($to)) {
            foreach ($to as $email) {
                $mail->addTo($email);
            }
        } else {
            $mail->addTo($to);
        }
        $mail->setSubject($sub);
        //$mail->addBcc($bcc);
        try {
            if ($mail->send())
                return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function CurlRequest($url, $param, $token = '', $url_encoded = true) {

        $application = Zend_Registry::get("application");

        $auth = '';
        if (!empty($token)) {
            $auth = "Authorization: Bearer " . $token;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        if($url_encoded)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("cache-control: no-cache", 'content-type: application/x-www-form-urlencoded', $auth));
        }
        else
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("cache-control: no-cache", 'content-type: application/json;', $auth));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $curl_response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $err = curl_error($ch);

        if ($info['http_code'] == '200' || $info['http_code'] == '202') {
            if (empty($curl_response)) {
                return false; #InvalidInput HTTP/1.1 408 Request Timeout
            } else {
                return json_decode($curl_response, true);
            }
        } else {
            return array('ResponseCode' => '500', 'ResponseDescription' => 'Internal Server Error' . json_encode($curl_response)); #InvalidInput HTTP/1.1 500 Internal Server Error
        }
    }

    public function validateImage($ext) {
        switch (strtolower($ext)) {
            case "jpg":
            case "jpeg":
            case "png":
            case "gif":
            case "bmp":
                return true;
            default :
                return false;
        }
    }

}

?>
