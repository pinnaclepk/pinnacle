<?php

/**
 * Breadcrumb view helper
 *
 * This helper is automated and uses the
 * current module, controller & action to
 * generated the breadcrumb. A custom breadcrumb
 * can be specified, using the set method.
 *
 * @todo Refactor code to allow building of a "path"
 * to current location. Use this in conjuction with
 * "path building" idea to subcategorise controllers
 * and still have a nice breadcrumb trail.
 *
 * @licence Use at will - no strings.
 */
class Zend_View_Helper_Breadcrumb extends Zend_View_Helper_Abstract {

    /**
     * Request Object
     *
     * @var Zend_Controller_Request_Abstract
     */
    protected $_request;

    /**
     * Breadcrumb separator
     *
     * @var string
     */
    protected $_separator = '&rsaquo;';

    /**
     * Breadcrumb
     *
     * @var array
     */
    protected $_breadcrumb = array();

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        $fc = Zend_Controller_Front::getInstance();
        $this->_request = $fc->getRequest();
    }

    /**
     * Set the breadcrumb separator
     *
     * @param string $separator
     */
    public function setSeparator($separator) {
        $this->_separator = $separator;
    }

    /**
     * Set custom breadcrumb
     *
     * @param array $breadcrumb
     * @return My_View_Helper_Breadcrumb
     */
    public function set(array $breadcrumb) {
        $this->_breadcrumb = $breadcrumb;

        return $this;
    }

    /**
     * breadcrumb
     *
     * @param array $breadcrumb Set a custom breadcrumb
     * @return My_View_Helper_Breadcrumb
     */
    public function breadcrumb(array $breadcrumb = array()) {

        if (empty($this->_breadcrumb)) {
            if (!empty($breadcrumb)) {

                $this->set($breadcrumb);
            } else {

                $module = $this->_request->getModuleName();
                $obj = new Application_Model_Module();
                $moduleData = $obj->getModuleAccessInfo();


                foreach ($moduleData as $key => $value) {
                    if ($value['name'] == $module) {
                        $menuArr = $obj->getMenuArray();
                        $menuItems = $menuArr[$key];
                        $this->getArr($menuItems);
                    }
                }
                if (count($this->_breadcrumb)) {
                    $this->_breadcrumb[count($this->_breadcrumb) - 1]['url'] = null;
                }
            }
        }

        return $this;
    }

    private function getArr($menuInfo) {
        foreach ($menuInfo as $key => $value) {
            if (is_array($value)) {
                $this->_breadcrumb[] = array(
                    'title' => $key,
                    'url' => ''
                );
                $value = $this->getArr($value);
            } else {
                $this->_breadcrumb[] = array(
                    'title' => $key,
                    'url' => ''
                );
            }
        }
        return $menuInfo;
    }

    public function __toString() {
        //$breadcrumb = '<ol class="breadcrumb">';
        $breadcrumb = '';
        foreach ($this->_breadcrumb as $i => $bc) {
            $nextimg = '';
            if (count($this->_breadcrumb) > ($i + 1)) {
                //$nextimg = '<img src="/template/images/icons/grid/grid_next.png" />&nbsp;&nbsp;&nbsp;';
                $breadcrumb .= "<li>" . $this->view->escape($bc['title']) . '&nbsp;&nbsp;&nbsp;' . "</li>". $nextimg;
            } else {
                $breadcrumb .= '<li><b>' . $this->view->escape($bc['title']) . '</b></li>';
            }
        }
        $breadcrumb = trim(trim($breadcrumb));
        return $breadcrumb ;
    }

}

?>
