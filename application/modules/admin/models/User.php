<?php

class Admin_Model_User extends Application_Model_Base {

    protected $_db;
    protected $_name = "user";
    private $user;
    private $usertoken;
    public $_translate;
    public $_session;

    public function __construct() {
        parent::__construct();
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $this->_session = Zend_Registry::get("session");

        $config = array(
            'name' => 'user',
            'primary' => 'UserID'
        );
        $this->user = new Zend_Db_Table($config);
        $config = array(
            'name' => 'usertoken',
            'primary' => 'ID'
        );
        $this->usertoken = new Zend_Db_Table($config);

        $this->_translate = Zend_Registry::get("translate");
    }

    public function getGridUsers($searchData = array()) {
        $grid = $this->setGrid('user');
        $where = "user.IsDeleted = '0' and user.UserID != 1";

        $where .= $this->getSearchWhereString($searchData);
        $select = $this->_db->select()
                ->from(array('user' => 'user'), array('user.UserID', 'user.Username',
                    'CONCAT(user.FName," ",user.LName) as Name',
                    'user.JoiningDate',
                    'user.MobileNo',
                    '(CASE WHEN (user.IsActive = "1") THEN "active" ELSE "inactive" END) as status'
                ))
                ->joinLeft(array("user2" => "user"), "user2.UserID = user.SponsorID AND user2.IsDeleted = '0' AND user2.IsActive = '1'", array("CONCAT(user2.FName,' ',user2.LName) AS SponsorName"))
                ->where($where)
                ->order('user.Username Asc');
        $fselect = $this->_db->select()
                ->from(array("t" => new Zend_Db_Expr("(SELECT @i:=0)")), array('(@i:=@i+1) as Serialno'))
                ->from(array("temp" => new Zend_Db_Expr('(' . $select . ')')), array(
            'temp.UserID', 'temp.Username', 'temp.Name', 'temp.SponsorName', 'temp.JoiningDate', 'temp.MobileNo', 'temp.status'));


        $grid->query($fselect);
        $this->queryLog($grid->getSelect(), true);
        return $grid;
    }

    public function addUser($params, $file) {
        try {
            $this->_db->beginTransaction();

            $JoiningDate = explode("/", $params['JoiningDate']);
            $tmp = mktime(0, 0, 0, $JoiningDate[1], $JoiningDate[0], $JoiningDate[2]);
            $params['JoiningDate'] = date("Y-m-d", $tmp);

            $DOB = explode("/", $params['DOB']);
            $tmp = mktime(0, 0, 0, $DOB[1], $DOB[0], $DOB[2]);
            $params['DOB'] = date("Y-m-d", $tmp);

            $userData = array();
            if (isset($file['Photo']) && !empty($file['Photo']['name'])) {
                $ext = substr($file['Photo']['name'], strrpos($file['Photo']['name'], "."));
                $filename = $params['UserID'] . $ext;
                $newpath = ROOT_PATH . "/public/profile/";
                if (!file_exists($newpath)) {
                    mkdir($newpath, 0755, true);
                }
                if (move_uploaded_file($file['Photo']['tmp_name'], $newpath . $filename)) {
                    $userData['Photo'] = $filename;
                }
            }
            $userData += array(
                'UserTypeID' => $params['UserTypeID'],
                'SponsorID' => $params['SponsorID'],
                'Username' => $params['Username'],
                'Password' => md5($params['Password']),
                'FName' => $params['FName'],
                'LName' => $params['LName'],
                'JoiningDate' => $params['JoiningDate'],
                'Email' => $params['Email'],
                'Phone' => $params['Phone'],
                'MobileNo' => $params['MobileNo'],
                'DOB' => $params['DOB'],
                'Address' => $params['Address'],
                'StateID' => $params['StateID'],
                'ZipCode' => $params['ZipCode'],
                'CreatedBy' => $this->_session->UserID,
                'IsActive' => $params['IsActive']
            );
            $userID = $this->user->insert($userData);
            $this->queryLog();
            $this->_db->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback($e);
            return false;
        }
    }

    public function editUser($params, $file = '') {
        try {
            $this->_db->beginTransaction();
            $LicenceExpDate = explode("/", $params['LicenceExpiryDate']);
            $tmp = mktime(0, 0, 0, $LicenceExpDate[1], $LicenceExpDate[0], $LicenceExpDate[2]);
            $params['LicenceExpiryDate'] = date("Y-m-d", $tmp);

            $JoiningDate = explode("/", $params['JoiningDate']);
            $tmp = mktime(0, 0, 0, $JoiningDate[1], $JoiningDate[0], $JoiningDate[2]);
            $params['JoiningDate'] = date("Y-m-d", $tmp);

            $DOB = explode("/", $params['DOB']);
            $tmp = mktime(0, 0, 0, $DOB[1], $DOB[0], $DOB[2]);
            $params['DOB'] = date("Y-m-d", $tmp);

            $userData = array();
            if (isset($file['Photo']) && !empty($file['Photo']['name'])) {
                $ext = substr($file['Photo']['name'], strrpos($file['Photo']['name'], "."));
                $filename = $params['UserID'] . $ext;
                $newpath = ROOT_PATH . "/public/profile/";
                if (!file_exists($newpath)) {
                    mkdir($newpath, 0755, true);
                }
                if (move_uploaded_file($file['Photo']['tmp_name'], $newpath . $filename)) {
                    $userData['Photo'] = $filename;
                }
            }

            $userData += array(
                'UserTypeID' => $params['UserTypeID'],
                'SponsorID' => $params['SponsorID'],
                'FName' => $params['FName'],
                'LName' => $params['LName'],
                'JoiningDate' => $params['JoiningDate'],
                'Email' => $params['Email'],
                'Phone' => $params['Phone'],
                'MobileNo' => $params['MobileNo'],
                
                'DOB' => $params['DOB'],
                'Address' => $params['Address'],
                'StateID' => $params['StateID'],
                'ZipCode' => $params['ZipCode'],
                'IsActive' => $params['IsActive']
            );

            $where = "UserID = " . $params['UserID'];
            $userID = $this->user->update($userData, $where);
            $this->_db->commit();
            $this->removeCacheByTag(array("user"));
            $this->removeCacheByTag(array("grid_user"));
            return true;
        } catch (Exception $e) {
            $this->rollback($e);
            return false;
        }
    }

    public function getUserByID($userID) {
        $cacheFileName = "User_getUserByID_" . $userID;
        $userData = $this->getCacheData($cacheFileName, "user");
        if (empty($userData)) {
            $query = $this->_db->select()
                    ->from(array("user" => "user"), array("user.UserID", "user.Username", "user.FName", "user.LName", "user.Email", "user.UserTypeID",
                        "user.IsActive", "user.CreatedBy", "DATE_FORMAT(user.JoiningDate,'%d/%m/%Y') as JoiningDate", "user.Phone", "user.MobileNo", "DATE_FORMAT(user.DOB,'%d/%m/%Y') AS DOB",
                        "user.Address", "user.ZipCode", "user.Photo"
                        ))
                    ->joinLeft(array("user1" => "user"), "user1.UserID = user.CreatedBy AND user1.IsDeleted = '0' AND user1.IsActive = '1'", array("CONCAT(user1.FName,' ',user1.LName) AS UpdatedBy"))
                    ->joinLeft(array("user2" => "user"), "user2.UserID = user.SponsorID AND user2.IsDeleted = '0' AND user2.IsActive = '1'", array("user.SponsorID", "CONCAT(user2.FName,' ',user2.LName) AS SponsorName"))
                    ->join(array('usertype' => 'usertype'), 'user.UserTypeID = usertype.ID AND usertype.IsActive = "1"', array("usertype.TypeName"))
                    ->joinLeft(array('state' => 'state'), 'user.StateID = state.StateID AND state.IsDeleted = "0"', array("state.StateID", "state.StateName"))
                    ->where("user.IsDeleted = '0' AND user.UserID = ?  ", $userID);

            $userData = $this->fetchRowData($query, $cacheFileName, "user");
        }

        return count($userData) > 0 ? $userData : array();
    }

    public function delete($userID) {
        try {
            $this->_db->beginTransaction();

            $row['IsDeleted'] = '1';
            $dt = date("Y-m-d H:i:s");
            //$row['UpdateTime'] = $dt;
            //$row['CreatedBy'] = $this->_session->UserID;
            $where = "UserID = " . $userID;
            $userLogID = $this->user->update($row, $where);
            $this->queryLog();
            $result = $this->_db->commit();
            $this->removeCacheByTag(array("user"));
            $this->removeCacheByTag(array("grid_user"));
            if ($result) {
                return true;
            }
        } catch (Exception $e) {
            $this->rollback($e);
        }
    }

    public function authenticateUser($params) {
        $cacheFileName = '';
        $query = $this->_db->select()
                ->from(array("user" => "user"))
                ->where("user.IsDeleted = '0' AND user.IsActive = '1' AND user.Username = '" . $params['UserName'] . "' AND user.Password= '" . md5($params['Password']) . "'");

        $userLogin = $this->fetchRowData($query, $cacheFileName, "user");
        $returnUser = false;
        if ($userLogin) {
            $objSession = Zend_Registry::get("session");
            $objSession->UserID = $userLogin['UserID'];
            $objSession->UserTypeID = $userLogin['UserTypeID'];
            $objSession->UserName = $userLogin['Username'];
            $objSession->Name = $userLogin['FName'] . ' ' . $userLogin['LName'];
            $objSession->Email = $userLogin['Email'];
            $objSession->Photo = $userLogin['Photo'];
            $objSession->SessionTimeOut = 15;
            $objSession->setExpirationSeconds($objSession->SessionTimeOut * 60);
            $returnUser = TRUE;
        }

        return $returnUser;
    }

    public function userExist($params) {
        $select = $this->_db->select()
                ->from('user', array("COUNT(user.UserID) as cnt"))
                ->where("user.IsDeleted ='0'  AND user.Username = ?  ", $params);

        $userCount = $this->fetchRowData($select);
        return $userCount['cnt'] == '0' ? false : true;
    }

    public function changeUserPassword($params) {
        try {
            $select = $this->_db->select()
                    ->from('user', array("COUNT(user.UserID) as cnt"))
                    ->where("user.IsDeleted ='0' AND user.UserID = ?", $this->_session->UserID)
                    ->where("user.password=?", md5($params['CurrentPassword']));

            $data = $this->fetchRowData($select);
            if ($data['cnt'] == 1) {
                $this->_db->beginTransaction();
                $userData = array(
                    'Password' => md5(trim($params['Password']))
                );
                $where = "UserID = " . $this->_session->UserID;
                $userLogId = $this->user->update($userData, $where);
                $this->queryLog();

                $this->_db->commit();
                return true;
            } else
                return false;
        } catch (Exception $e) {
            $this->rollback($e);
        }
    }

    public function addToken($email, $token) {
        $select = $this->_db->select()
                ->from('user', array("user.UserID", "user.Email"))
                ->where("user.IsDeleted ='0' AND user.IsActive='1' AND user.Username = ?", $email);

        $data = $this->fetchRowData($select);
        if ($data['UserID']) {
            try {
                $this->_db->beginTransaction();
                $tokenData = array(
                    'UserID' => $data['UserID'],
                    'Token' => md5($token),
                    'ExpiryTime' => date("Y-m-d h:i:s", mktime(date('h') + 1, date('i'), date('s'), date('m'), date('d'), date('Y')))
                );

                $tokenID = $this->usertoken->insert($tokenData);
                $this->_db->commit();
                return $data;
            } catch (Exception $e) {
                $this->rollback($e);
                return false;
            }
        } else {
            return false;
        }
    }

    public function deactivateUser($UserID) {
        if ($UserID) {
            try {
                $userData = array(
                    'IsActive' => '0',
                );
                $where = "UserID='" . $UserID . "'";
                $tokenID = $this->user->update($userData, $where);
            } catch (Exception $e) {
                $this->rollback($e);
            }
        }
    }

    public function verifyToken($tokenid) {
        $sql = $this->_db->select()->distinct()
                ->from("user_token", array("user_token.UserID", "UNIX_TIMESTAMP(user_token.ExpiryTime) as ExpiryTime"))
                ->join(array("user" => "user"), "user.UserID = user.UserID and user_token.IsActive='1' and user.IsDeleted='0'", array())
                ->where("user_token.token=?", md5(trim($tokenid)));
        $data = $this->fetchRowData($sql);
        if (!empty($data)) {
            if ($data['ExpiryTime'] > time())
                $msg = array(
                    "UserID" => 0, "msg" => $this->_translate->_("token_expired"));
            else
                $msg = array("UserID" => $data['UserID'], "msg" => "valid");
        } else {
            $msg = array("UserID" => $data['UserID'], "msg" => $this->_translate->_("invalid_token"));
        }

        return $msg;
    }

    public function setPassword($params) {
        try {
            $this->_db->beginTransaction();

            $row['Password'] = md5($params['password']);
            $where = "UserID = " . $params['UserID'];
            $userID = $this->user->update($row, $where);
            $this->queryLog();

            $row1['IsActive'] = '0';
            //$where = "UserID = " . $params['UserID'];
            $userID = $this->usertoken->update($row1, $where);
            $this->queryLog();

            $result = $this->_db->commit();
            $this->removeCacheByTag(array("user"));
            $this->removeCacheByTag(array("grid_user"));
            if ($result) {
                return true;
            }
        } catch (Exception $e) {
            $this->rollback($e);
        }
    }

    public function getGuardName($UserID) {
        $client['GuardName'] = '';
        if (!empty($UserID)) {
            $select = $this->_db->select()
                    ->from('user', array("CONCAT(FName,' ',LName) as GuardName"))
                    ->where("user.IsDeleted ='0'  AND user.id = '" . $UserID . "'");
            $client = $this->fetchRow($select);
        }
        return $client['GuardName'];
    }

    public function getSponsorList($UserTypeID, $isAjax = false) {

        $cacheFileName = '';
        $select = $this->_db->select()
                ->from('user', array("user.UserID", "CONCAT(user.FName,' ',user.LName) as SponsorName"))
                ->where("user.IsDeleted ='0' AND user.IsActive = '1'");
        switch ($UserTypeID) {
            case "2":
                $select->where("user.UserTypeID IN (1)");
                break;
            case 3:
                $select->where("user.UserTypeID IN (1,2)");
                break;
            case 4:
                $select->where("user.UserTypeID IN (1,2,3)");
                break;
        }

        $sponsorData = $this->fetchData($select, $cacheFileName, "sponsor");

        if ($isAjax) {
            $site = array();
            foreach ($sponsorData as $key => $value) {
                $sponsor[$value["UserID"]] = ucwords($value["SponsorName"]);
            }

            return $sponsor;
        }
        else
            return $sponsorData;
            
        return array();
    }

}

?>
 