<?php

class Zend_View_Helper_Roledropdown extends Zend_View_Helper_Abstract {

    public function roledropdown($roleID=NULL) {
        $translate = Zend_Registry::get("translate");
        $html = '';
        $html .= '<select id = "RoleID" name = "RoleID" class="form-control">';
        $html .= '<option value = "">';
        $html .= $translate->_("select");
        $html .= '</option>';
        $deptObj = new Role_Model_Role();
        $RoleID = '';
        $fields = "Role.RoleID, Role.RoleName";
        $roleData = $deptObj->getRole($RoleID, $fields);
        if (is_array($roleData) && !empty($roleData)) {
            foreach ($roleData as $key => $value) {
              $selected = isset($roleID) && !empty($roleID) && ($roleID == $key) ? "selected" : '';
                $html .= '<option value="' . $key . '"'.$selected.'>' . $value . '</option>';
            }
        }
        return $html . '</select>';
    }

}
?>
