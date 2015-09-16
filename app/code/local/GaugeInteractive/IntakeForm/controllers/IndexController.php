<?php

class GaugeInteractive_IntakeForm_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function sendAction()
    {
        // form data
        $data = $this->getRequest()->getParams();

        try {
            Mage::getModel('intakeform/mail')->send($data);

            $message = $this->__('Intake Form was sent successfully.');
            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("intakeform"));
            Mage::getSingleton('core/session')->addSuccess($message);

        } catch (Exception $ex) {
            $message = $this->__('There is an error sending the intake form.');
            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("intakeform"));
            Mage::getSingleton('core/session')->addError($message);
            Mage::log($ex->getMessage(), null, 'exception.log');
        }
    }
}