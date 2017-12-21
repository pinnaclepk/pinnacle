<?php

class Bvb_Grid_Formatter_Anchor implements Bvb_Grid_Formatter_FormatterInterface {

    /**
     * Constructor
     *
     * @param array $options
     *
     * @return void
     */
    public function __construct($options = array()) {
        $this->_options = $options;

        return;
    }

    public function format($value) {
        if ($value != 0) {
            /* $finds = '/Total/';
              $pos = strpos($this->_options['url'], $finds);

              if($pos){



              // /pid/{{provider_id}}/ppid/{{provider_product_id}}
              $url = substr($this->_options['url'], 0,$pos).$finds;
              $value = '<a href="' .$url . '">' . $value . '</a>';
              }
              else
              {
              $value = '<a href="' . $this->_options['url'] . '">' . $value . '</a>';
              }
             * 
             */
            $url = $this->_options['url'];
            $parameters = $this->_options['params'];
            if (!empty($parameters)) {
                if ($parameters['ProviderID'] != '') {
                    $url .= '/pid/' . $parameters['ProviderID'];
                }
                if ($parameters['ProviderProductID'] != '') {
                    $url .= '/ppid/' . $parameters['ProviderProductID'];
                }
            }
            $value = '<a href="' . $url . '">' . $value . '</a>';
        }

        return $value;
    }

}