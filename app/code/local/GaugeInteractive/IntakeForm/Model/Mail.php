<?php

/**
 * Created by Gauge Interactive
 * Date: 6/16/15
 * Time: 1:34 PM
 */
class GaugeInteractive_IntakeForm_Model_Mail extends Mage_Core_Model_Abstract
{
    const XML_PATH_INTAKEFORM_ADDRESSTO = 'intakeform/settings/addressto';

    public function send($data)
    {
        $mail = $this->init($data);

        try {
            //Sending the email
            $mail->send();
        } catch (Exception $ex) {
            Mage::getSingleton('core/session')->addError('Unable to send email. Sample of a custom notification error from GaugeInteractive_IntakeForm_IndexController.');
        }
    }

    public function init($data)
    {
        $mail = $this->createMail($data);

        //copy the temp. uploaded file to uploads folder
        $tmp_path = $_FILES["attachment"]["tmp_name"];

        $content = file_get_contents($tmp_path, true);

        $attachment = $this->attach($content, $data);

        $mail->addAttachment($attachment);

        return $mail;
    }

    public function createMail($data)
    {
        $addressTo = Mage::getStoreConfig(self::XML_PATH_INTAKEFORM_ADDRESSTO);

        // Setting up email and setting parameters
        $mail = new Zend_Mail();
        $mail->setBodyText($data['comment']);
        $mail->setFrom($data['email'], $data['name']);
        $mail->addTo($addressTo, 'Some Recipient');
        $mail->setSubject($data['name'] . ' - Intake Form');

        return $mail;
    }


    public function attach($content, $data)
    {
        $attachment = new Zend_Mime_Part($content);
        $attachment->type = 'application/pdf';
        $attachment->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
        $attachment->encoding = Zend_Mime::ENCODING_BASE64;
        $attachment->filename = str_replace(' ', '_', $data['name']) . '_intakeform.pdf'; // name of file

        return $attachment;
    }
}