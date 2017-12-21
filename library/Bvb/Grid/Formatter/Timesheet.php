<?php

class Bvb_Grid_Formatter_Timesheet implements Bvb_Grid_Formatter_FormatterInterface {

    /**
     * Constructor
     * @param array $options
     */
    public function __construct($options = array()) {
        
    }

    /**
     * Formats a given value
     * @see library/Bvb/ Grid/Formatter/Bvb_Grid_Formatter_FormatterInterface::format()
     */
    public function format($value) {
        if (trim($value) == null || empty(trim($value))) {
            $val = "<span style='width:100%;float:left;background-color: #ffff00;text-align:center;'>&nbsp;</span>";
        }
        else
        {
            $val = "<span style='width:100%;float:left;text-align:center;'>".$value."</span>";
        }        
        return $val;
    }

}
