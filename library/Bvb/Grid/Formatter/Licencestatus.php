<?php

class Bvb_Grid_Formatter_Licencestatus implements Bvb_Grid_Formatter_FormatterInterface 
{
    /**
     * Constructor
     * @param array $options
     */
    public function __construct($options = array())
    {

    }

    /**
     * Formats a given value
     * @see library/Bvb/ Grid/Formatter/Bvb_Grid_Formatter_FormatterInterface::format()
     */
    public function format($value)
    {
        
        $translate = Zend_Registry::get("translate");
        $val = "<div class='grid_".strtolower($value)."_button'>".$translate->_($value)."</div>";
        return  $val;
    }

}