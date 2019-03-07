<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 10/8/18
 * Time: 2:49 PM
 */

namespace App\core\logger;
use App\core\contracts\support\Mailable;
use Monolog\Handler\MailHandler;
use App\core\contracts\support\MailTrait;

class CoreLogMailHandler extends MailHandler implements Mailable
{
    const DEFAULT_SUPPORT_EMAIL = 'justinwang24@yahoo.com.au';
    const DEFAULT_SUPPORT_PERSON = 'Justin Wang';

    use MailTrait {
        // Define an alias for trait's function: sendEmail
        sendEmail as public traitSendMail;
    }

    /**
     * Send a mail with the given content
     *
     * @param string $content formatted email body to be sent
     * @param array $records the array of log records that formed this content
     */
    protected function send($content, array $records)
    {
        // Only send when SendGrid api key exist and in production mode
        if(env('SENDGRID_API_KEY',null) && !env('DEV_MODE', false)){
            $this->_email_service_app_key = env('SENDGRID_API_KEY',null);

            $this->setEmailFrom(
                env('SUPPORT_EMAIL_ADDRESS',self::DEFAULT_SUPPORT_EMAIL),
                env('SUPPORT_EMAIL_NAME', self::DEFAULT_SUPPORT_PERSON)
            )
                ->setEmailSubject('Critical error at '.env('APP_NAME'))
                ->addEmailTo(
                    env('SUPPORT_EMAIL_ADDRESS',self::DEFAULT_SUPPORT_EMAIL),
                    env('SUPPORT_EMAIL_NAME', self::DEFAULT_SUPPORT_PERSON)
                )
                ->addEmailContent(Mailable::CONTENT_TYPE_PLAIN,$content)
                ->sendEmail();

        }
    }

    /**
     * @override trait's sendEmail function
     * @return mixed
     */
    public function sendEmail()
    {
        return $this->traitSendMail();
    }
}