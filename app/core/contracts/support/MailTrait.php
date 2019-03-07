<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 10/8/18
 * Time: 2:57 PM
 */

namespace App\core\contracts\support;
use App\core\LogTool;
use SendGrid\Mail\Mail;

trait MailTrait
{
    /**
     * @var array
     * Save email's data
     */
    protected $_emailData = [];

    /**
     * @var null|string
     * Email sending service's app key
     */
    protected $_email_service_app_key = null;

    /**
     * Set from field
     * @param $email
     * @param $fromName
     * @return Mailable|MailTrait
     */
    public function setEmailFrom($email, $fromName)
    {
        // TODO: Implement setEmailFrom() method.
        $this->_emailData['from'] = [
            'email'=>$email,
            'name'=>$fromName,
        ];
        return $this;
    }

    /**
     * Set email subject
     * @param $subject
     * @return Mailable|MailTrait
     */
    public function setEmailSubject($subject)
    {
        $this->_emailData['subject'] = $subject;
        return $this;
    }

    /**
     * Set to
     * @param $email
     * @param $toName
     * @return Mailable|MailTrait
     */
    public function addEmailTo($email, $toName)
    {
        // TODO: Implement addEmailTo() method.
        $this->_emailData['to'] = [
            'email'=>$email,
            'name'=>$toName,
        ];
        return $this;
    }

    /**
     * Set cc
     * @param $email
     * @param $toName
     * @return Mailable|MailTrait
     */
    public function addEmailCopyTo($email, $toName)
    {
        $this->_emailData['cc'][] = [
            'email'=>$email,
            'name'=>$toName,
        ];
        return $this;
    }

    /**
     * set email content
     * @param string $contentType
     * @param string $content
     * @return Mailable|MailTrait
     */
    public function addEmailContent($contentType = Mailable::CONTENT_TYPE_PLAIN, $content)
    {
        $this->_emailData['content'] = [
            'type'=>$contentType,
            'content'=>$content,
        ];
        return $this;
    }

    /**
     * Send email
     * @return mixed
     */
    public function sendEmail()
    {
        $email = new Mail();
        $email->setFrom($this->_emailData['from']['email'],$this->_emailData['from']['name']);
        $email->setSubject($this->_emailData['subject']);
        $email->addTo($this->_emailData['to']['email'],$this->_emailData['to']['name']);
        $email->addContent($this->_emailData['content']['type'],$this->_emailData['content']['content']);
        $sendGrid = new \SendGrid(
            $this->_email_service_app_key ? $this->_email_service_app_key : env('MAIL_SENDGRID_API_KEY')
        );

        try {
            $response = $sendGrid->send($email);
            return in_array($response->statusCode(),[Mailable::STATUS_OK, Mailable::STATUS_ACCEPTED]);
        } catch (\Exception $e) {
            LogTool::Info($e->getMessage());
            return false;
        }
    }
}