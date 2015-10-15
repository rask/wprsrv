<?php

namespace Wprsrv;

class Email
{
    /**
     * Type slug.
     *
     * @access protected
     * @var String
     */
    protected $type = null;

    /**
     * Email subject line.
     *
     * @access protected
     * @var String
     */
    protected $subject;

    /**
     * Email content body.
     *
     * @access protected
     * @var String
     */
    protected $body;

    /**
     * Email receiver address.
     *
     * @access protected
     * @var String
     */
    protected $to;

    /**
     * Additional headers for sending.
     *
     * @access protected
     * @var mixed
     */
    protected $headers;

    /**
     * Template file to use. Set absolute file path.
     *
     * @access protected
     * @var String
     */
    protected $template;

    /**
     * Callback to trigger when email send fails.
     *
     * @access protected
     * @var callable
     */
    protected $failureCallback;

    /**
     * Callback to trigger when sending succeeds.
     *
     * @access protected
     * @var callable
     */
    protected $successCallback;

    /**
     * Failure callback argument array.
     *
     * @access protected
     * @var mixed[]
     */
    protected $failureCbArgs;

    /**
     * Success callback arguments array.
     *
     * @access protected
     * @var mixed[]
     */
    protected $successCbArgs;

    /**
     * Constructor. Set the email type.
     *
     * @param String $type Type of email.
     *
     * @return void.
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Set the wanted email template to use when sending.
     *
     * @param $templateFile
     */
    public function setTemplate($templateFile)
    {
        $tmplDir = wprsrv()->pluginDirectory . RDS . 'includes' . RDS . 'templates' . RDS . 'email';

        $template = $tmplDir . RDS . $templateFile;

        if (!file_exists($template)) {
            throw new \InvalidArgumentException('Invalid template file for email template: ' . $templateFile);
        }

        $this->template = $template;

        return $this;
    }

    /**
     * Send with an array of data. The template file will be filled with the data.
     *
     * @param array $args
     */
    public function sendWith(Array $args)
    {
        extract($args);

        $template = include($this->template);

        $template = apply_filters('wprsrv/email_template/' . $this->type, $template);

        $this->body = $template;

        $this->send();
    }

    /**
     * Simple send. If the template contains no customized data this is a good option.
     *
     * @return void
     */
    public function send()
    {
        $template = apply_filters('wprsrv/email_template/' . $this->type, $this->template);

        $this->body = trim($this->body);

        if (!$this->body || empty($this->body)) {
            $this->body = include($template);
        }

        if (strpos($this->body, '{$[a-z]+}') !== false) {
            //TODO sending a parameterized email with no parameters!
        }

        add_filter('wp_mail_content_type', function () {
            return 'text/html';
        });

        $sent = wp_mail($this->to, $this->subject, $this->body, $this->headers);

        // Fire callbacks on email success conditions.
        if ($sent === false && is_callable($this->failureCallback)) {
            call_user_func_array($this->failureCallback, $this->failureCbArgs);
        } elseif (is_callable($this->successCallback)) {
            call_user_func_array($this->successCallback, $this->successCbArgs);
        }

        // Fire actions on email success conditions.
        // FIXME are the above callback hassles needed when using actions?
        if ($sent === false) {
            do_action('wprsrv/email_failed', $this);
        } else {
            do_action('wprsrv/email_succeeded', $this);
        }
    }

    /**
     * Get the subject of the email.
     *
     * @return String
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the subject for this email.
     *
     * @param String $subject The subject.
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Return the body of the email.
     *
     * @return String
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the email body.
     *
     * @param String $body
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get the receiving address.
     *
     * @return String
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set the receiving address.
     *
     * @param String $to
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get the email headers.
     *
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set the email headers.
     *
     * @param mixed $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set the email sending failure callback.
     *
     * @param callable $failureCallback
     * @param mixed[] $args
     */
    public function setFailureCallback($failureCallback, Array $args = [])
    {
        $this->failureCallback = $failureCallback;
        $this->failureCbArgs = $args;

        return $this;
    }

    /**
     * Sent the email sending success callback.
     *
     * @param callable $successCallback
     * @param mixed[] $args
     */
    public function setSuccessCallback($successCallback, Array $args = [])
    {
        $this->successCallback = $successCallback;
        $this->successCbArgs = $args;

        return $this;
    }
}
