<?php

class Profile_Model_Profile extends Application_Model_Base {

    protected $_db;
    protected $_name = "User";
    private $user;
    private $userLog;
    public $_translate;

    public function __construct() {
        parent::__construct();
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $config = array(
            'name' => 'User',
            'primary' => 'UserID'
        );
        $this->user = new Zend_Db_Table($config);
        $config1 = array(
            'name' => 'UserLog',
            'primary' => 'UserLogID'
        );
        $this->userLog = new Zend_Db_Table($config1);
        $this->_translate = Zend_Registry::get("translate");
    }

    public function editUserProfile($params) {
        try {
            $this->_db->beginTransaction();
            $query = $this->_db->select()
                    ->from(array("User" => "User"), array("User.UserID", "User.UserName", "User.UserLogID"))
                    ->join(array("UserLog" => "UserLog"), "UserLog.UserLogID = User.UserLogID", array("UserLog.Password", "UserLog.FName", "UserLog.LName", "UserLog.Phone", "UserLog.Email", "UserLog.UserInfo", "UserLog.CountryID", "UserLog.DateFormatID", "UserLog.DepartmentID", "UserLog.TimeZoneID", "UserLog.SessionTimeOut", "UserLog.DecimalPrecision", "UserLog.AllowMultipleLogin", "UserLog.AllowPartition", "UserLog.IsActive", "UserLog.UpdateByUserID", "UserLog.UpdateTime", "UserLog.IsDeleted"))
                    ->where("UserLog.IsDeleted = '0' AND UserLog.IsActive = '1' AND User.UserID = ?  ", $params['UserID']);
            $userProfile = $this->fetchRowData($query);
            $AllowPartition = 0;
            $userObj = new User_Model_User();

            if (isset($params['AllowPartition']) && count($params['AllowPartition'])) {
                $AllowPartition = array_sum($params['AllowPartition']);
            } else {
                $userData = $userObj->getUserByID($params["UserID"]);
                $AllowPartition = $userData['AllowPartition'];
            }
            $userLogInfoData = $this->saveExtraInformation("USER", $params);
            $userData = array(
                'Password' => $userProfile['Password'],
                'FName' => $params['FName'],
                'Phone' => $params['Phone'],
                'LName' => $params['LName'],
                'Email' => $params['Email'],
                'CountryID' => $params['CountryID'],
                'DateFormatID' => $params['DateFormatID'],
                'DepartmentID' => $userProfile['DepartmentID'],
                'TimeZoneID' => $params['TimeZoneID'],
                'SessionTimeOut' => $params['SessionTimeOut'],
                'DecimalPrecision' => $params['DecimalPrecision'],
                'AllowMultipleLogin' => $params['AllowMultipleLogin'],
                'AllowPartition' => $AllowPartition,
                'IsActive' => $userProfile['IsActive'],
                'UserID' => $params['UserID'],
                'UserInfo' => $userLogInfoData,
            );
            $userLogId = $this->userLog->insert($userData);
            $this->queryLog();
            $updateuserData = array(
                "UserLogID" => $userLogId
            );
            $where = "UserID = " . $params['UserID'];
            $this->user->update($updateuserData, $where);
            $this->queryLog();
            $this->_db->commit();
            $cacheFileName = "User_getUserByID_" . $params['UserID'];
            $this->removeCache($cacheFileName);
            $this->removeCache($cacheFileName);
            return true;
        } catch (Exception $e) {
            $this->rollback($e);
            return false;
        }
    }

    public function changeUserPassword($params) {
        try {
            $this->_db->beginTransaction();
            $query = $this->_db->select()
                    ->from(array("User" => "User"), array("User.UserID", "User.UserName", "User.UserLogID"))
                    ->join(array("UserLog" => "UserLog"), "UserLog.UserLogID = User.UserLogID", array("UserLog.Password", "UserLog.FName", "UserLog.LName", "UserLog.Phone", "UserLog.Email", "UserLog.UserInfo", "UserLog.CountryID", "UserLog.DateFormatID", "UserLog.DepartmentID", "UserLog.TimeZoneID", "UserLog.SessionTimeOut", "UserLog.DecimalPrecision", "UserLog.AllowMultipleLogin", "UserLog.AllowPartition", "UserLog.IsActive", "UserLog.UpdateByUserID", "UserLog.UpdateTime", "UserLog.IsDeleted"))
                    ->where("UserLog.IsDeleted = '0' AND UserLog.IsActive = '1' AND User.UserID = ?  ", $params['UserID']);
            $userProfile = $this->fetchRowData($query);
            $userData = array(
                'Password' => md5(trim($params['Password'])),
                'FName' => $userProfile['FName'],
                'Phone' => $userProfile['Phone'],
                'LName' => $userProfile['LName'],
                'Email' => $userProfile['Email'],
                'CountryID' => $userProfile['CountryID'],
                'DateFormatID' => $userProfile['DateFormatID'],
                'DepartmentID' => $userProfile['DepartmentID'],
                'TimeZoneID' => $userProfile['TimeZoneID'],
                'AllowMultipleLogin' => $userProfile['AllowMultipleLogin'],
                'AllowPartition' => $userProfile['AllowPartition'],
                'IsActive' => $userProfile['IsActive'],
                'UserID' => $params['UserID'],
                'UserInfo' => $userProfile['UserInfo'],
            );
            $userLogId = $this->userLog->insert($userData);
            $this->queryLog();
            $updateuserData = array(
                "UserLogID" => $userLogId
            );
            $where = "UserID = " . $params['UserID'];
            $this->user->update($updateuserData, $where);
            $this->queryLog();
            $this->_db->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback($e);
        }
    }

}

?>
