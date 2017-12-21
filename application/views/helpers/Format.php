<?php

class Zend_View_Helper_Format extends Zend_View_Helper_Abstract {

    public function format() {
        return $this;
    }

    public function dateformatPHP($date) {
        /*$objSession = Zend_Registry::get("session");
        if (!empty($objSession->DateFormat_PHP)) {
            preg_match_all("/(<([\w]+)[^>]*>)(.*?)(<\/\\2>)/", $date, $matches, PREG_SET_ORDER);
            if (count($matches)) {
                $strtotime = strtotime($matches[0][3]);
                $formatDate = date($objSession->DateFormat_PHP, $strtotime);
                $formatDate = $matches[0][1] . $formatDate . $matches[0][4];
            } else {
                $strtotime = strtotime($date);
                $formatDate = date($objSession->DateFormat_PHP, $strtotime);
            }
            return $formatDate;
        }*/
        
        $strtotime = strtotime($date);
        
        return date("d-m-Y h:i:s");
    }

}

?>
