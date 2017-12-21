<?php

class Zend_View_Helper_Removedecorators extends Zend_View_Helper_Abstract {

    public function removedecorators($form) {
        $formelements = $form->getElements();
        if (is_array($formelements) && !empty($formelements)) {
            foreach ($formelements as $key => $elements) {
                $element = $form->getElement($key);
                $element->removeDecorator("Label");
                $element->removeDecorator("DD");
                $element->addDecorator('HtmlTag', array('tag' => 'span'));
                $element->setDecorators(
                        array(
                            array('ViewHelper'),
                        )
                );
            }
        }
    }

}

?>
