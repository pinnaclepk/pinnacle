<?php

class Zend_View_Helper_Getoptions extends Zend_View_Helper_Abstract {

    public function Getoptions() {
        return $this;
    }

    public function usertype($userTypeID = NULL) {
        $translate = Zend_Registry::get("translate");
        $html = '';
        $html .= '<select id = "UserTypeID" name = "UserTypeID">';
        $html .= '<option value = "">';
        $html .= $translate->_("select");
        $html .= '</option>';
        $commonObj = new Application_Model_Common();
        $UserTypeID = '';
        $fields = "usertype.UserTypeID, usertype.TypeName";
        $userTypeData = $commonObj->getUserType($UserTypeID, $fields);
        
        if (is_array($userTypeData) && !empty($userTypeData)) {
            foreach ($userTypeData as $key => $value) {
                $selected = isset($userTypeID) && !empty($userTypeID) && ($userTypeID == $key) ? "selected" : '';
                $html .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
            }
        }
        return $html . '</select>';
    }

    public function coin($CoinID = NULL) {
        $translate = Zend_Registry::get("translate");
        $html = '';
        $html .= '<select id = "CoinID" name = "CoinID" class="form-control">';
        $html .= '<option value = "">';
        $html .= $translate->_("select");
        $html .= '</option>';
        $commonObj = new Application_Model_Common();
        $coinID = '';
        $coinData = $commonObj->getCoin();
        if (is_array($coinData) && !empty($coinData)) {
            foreach ($coinData as $key => $value) {
                $selected = isset($coinID) && !empty($coinID) && ($coinID == $key) ? "selected" : '';
                $html .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
            }
        }
        return $html . '</select>';
    }
    
    public function CompetencyType($competencyTypeID = NULL) {
        $translate = Zend_Registry::get("translate");
        $html = '';
        $html .= '<select id = "CompetencyTypeID" name = "CompetencyTypeID" class="form-control">';
        $html .= '<option value = "">';
        $html .= $translate->_("select");
        $html .= '</option>';
        $commonObj = new Application_Model_Common();
        $competencyTypeID = '';
        $competencyTypeData = $commonObj->getCompetencytype();
        if (is_array($competencyTypeData) && !empty($competencyTypeData)) {
            foreach ($competencyTypeData as $key => $value) {
                $selected = isset($competencyTypeID) && !empty($competencyTypeID) && ($competencyTypeID == $key) ? "selected" : '';
                $html .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
            }
        }
        return $html . '</select>';
    }
    
    public function reasoncategory($ReasoncategoryID = NULL) {
        $translate = Zend_Registry::get("translate");
        $html = '';
        $html .= '<select id = "ReasoncategoryID" name = "ReasoncategoryID" class="form-control">';
        $html .= '<option value = "">';
        $html .= $translate->_("select");
        $html .= '</option>';
        $commonObj = new Application_Model_Common();
        //$reasonID = '';
        $reasonData = $commonObj->getReasonCategory($ReasoncategoryID);
        if (is_array($reasonData) && !empty($reasonData)) {
            foreach ($reasonData as $key => $value) {
                $selected = isset($ReasoncategoryID) && !empty($ReasoncategoryID) && ($ReasoncategoryID == $key) ? "selected" : '';
                $html .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
            }
        }
        return $html . '</select>';
    }
    
    public function site($ReasonID = NULL) {
        $translate = Zend_Registry::get("translate");
        $html = '';
        $html .= '<select id = "SiteID" name = "SiteID" class="form-control">';
        $html .= '<option value = "">';
        $html .= $translate->_("select");
        $html .= '</option>';
        $commonObj = new Application_Model_Common();
        $siteID = '';
        $siteData = $commonObj->getSite();
        if (is_array($siteData) && !empty($siteData)) {
            foreach ($siteData as $key => $value) {
                $selected = isset($siteID) && !empty($siteID) && ($siteID == $key) ? "selected" : '';
                $html .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
            }
        }
        return $html . '</select>';
    }
    
    public function client($clientID = NULL) {
        $translate = Zend_Registry::get("translate");
        $html = '';
        $html .= '<select id = "ClientID" name = "ClientID" class="form-control">';
        $html .= '<option value = "">';
        $html .= $translate->_("select");
        $html .= '</option>';
        $commonObj = new Application_Model_Common();
        //$clientID = '';
        $clientData = $commonObj->getClient();
        if (is_array($clientData) && !empty($clientData)) {
            foreach ($clientData as $key => $value) {
                $selected = isset($clientID) && !empty($clientID) && ($clientID == $key) ? "selected" : '';
                $html .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
            }
        }
        return $html . '</select>';
    }
}

?>
