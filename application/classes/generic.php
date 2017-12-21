<?php

function getMonths() {
    $months = array("January" => "January",
        "February" => "February",
        "March" => "March",
        "April" => "April",
        "May" => "May",
        "June" => "June",
        "July" => "July",
        "August" => "August",
        "September" => "September",
        "October" => "October",
        "November" => "November",
        "December" => "December");

    return $months;
}

function getMonthsValues() {
    $months = array("01" => "January",
        "02" => "February",
        "03" => "March",
        "04" => "April",
        "05" => "May",
        "06" => "June",
        "07" => "July",
        "08" => "August",
        "09" => "September",
        "10" => "October",
        "11" => "November",
        "12" => "December");

    return $months;
}

function getYears() {
    $year = array();
    for ($i = date("Y") - 5; $i <= date("Y"); $i++) {

        $year["{$i}"] = $i;
    }

    return $year;
}

function getPrecision() {
    return array("0" => "0", "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6");
}

function getStatusValues() {
    $translate = Zend_Registry::get("translate");
    return array(
        "1" => $translate->_("active"),
        "0" => $translate->_("inactive")
    );
}

function getYesNo() {
    $translate = Zend_Registry::get("translate");
    return $yesno = array(
        "1" => $translate->_("lbl_yes"),
        "0" => $translate->_("lbl_no")
    );
}
function get1hourDuration() {
    $format = 'h:i A'; // 9:30pm
    $times = array();
    foreach (range(0, 23, 1.0) as $increment) {
        $increment = number_format($increment, 2);
        list($hour, $minutes) = explode('.', $increment);
        $date = new DateTime($hour . ':' . $minutes * .6);
        $time = $date->format($format);
        $times[$time] = $time;
    }
    return $times;    
}
function getBreakduration()
{
    for($i=0; $i<=120; $i= $i+15)
    {
        $times[$i] = $i . " Mins";
    }
    return $times;
}

function getSeverity()
{
    return array("Low"=>"Low",
        "Med"=>"Medium",
        "High"=>"High",
        "Critical"=>"Critical");
}

function gethalfhourDuration() {
    $format = 'h:i A'; // 9:30pm
    $times = array();
    foreach (range(0, 23, 0.5) as $increment) {
        $increment = number_format($increment, 2);
        list($hour, $minutes) = explode('.', $increment);
        $date = new DateTime($hour . ':' . $minutes * .6);
        $time = $date->format($format);
        $times[$time] = $time;
    }
    return $times;    
}

function getViewData() {
    $translate = Zend_Registry::get("translate");
    return array(
        "1" => $translate->_("lbl_weekly"),
        "0" => $translate->_("lbl_custom")
        
        
    );
}
function getDateFilter() {
    $translate = Zend_Registry::get("translate");
    return array(
        "0" => $translate->_("lbl_daily"),
        "1" => $translate->_("lbl_weekly")
    );
}
?>