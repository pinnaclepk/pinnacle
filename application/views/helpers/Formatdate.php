<?php

class Zend_View_Helper_Formatdate extends Zend_View_Helper_Abstract {

    public function formatdate($date, $format = '') {
        return date($format, strtotime($date));
    }

}
?>