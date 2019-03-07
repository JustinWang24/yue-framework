<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 9/8/18
 * Time: 2:26 PM
 */

namespace App\core\contracts\support;

interface Mailable
{
    const CONTENT_TYPE_PLAIN = 'text/plain';
    const CONTENT_TYPE_HTML = 'text/html';

    const STATUS_OK                 = 200;
    const STATUS_ACCEPTED           = 202;
    const STATUS_BAD_REQUEST        = 400;
    const STATUS_UNAUTHORIZED       = 401;
    const STATUS_FORBIDDEN          = 403;
    const STATUS_NOT_FOUND          = 404;
    const STATUS_METHOD_NOT_ALLOWED = 405;

    /**
     * Set from field
     * @param $email
     * @param $fromName
     * @return Mailable
     */
    public function setEmailFrom($email, $fromName);

    /**
     * Set email subject
     * @param $subject
     * @return Mailable
     */
    public function setEmailSubject($subject);

    /**
     * Set to
     * @param $email
     * @param $toName
     * @return Mailable
     */
    public function addEmailTo($email, $toName);

    /**
     * Set cc
     * @param $email
     * @param $toName
     * @return Mailable
     */
    public function addEmailCopyTo($email, $toName);

    /**
     * set email content
     * @param string $contentType
     * @param string $content
     * @return Mailable
     */
    public function addEmailContent($contentType=Mailable::CONTENT_TYPE_PLAIN, $content);

    /**
     * Send email
     * @return mixed
     */
    public function sendEmail();
}