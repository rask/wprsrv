<?php

namespace Wprsrv;

/**
 * Class Email
 *
 * Email sender class.
 *
 * @since 0.1.0
 * @package Wprsrv
 */
class Email
{
    /**
     * Type slug. The type of email this class is sending.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $type = null;

    /**
     * Email subject line.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $subject;

    /**
     * Email content body.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $body;

    /**
     * Email receiver address.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $to;

    /**
     * Additional headers for sending.
     *
     * @since 0.1.0
     * @access protected
     * @var mixed
     */
    protected $headers;

    /**
     * Template file to use. Set absolute file path.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $template;

    /**
     * Callback to trigger when email send fails.
     *
     * @since 0.1.0
     * @access protected
     * @var callable
     */
    protected $failureCallback;

    /**
     * Callback to trigger when sending succeeds.
     *
     * @since 0.1.0
     * @access protected
     * @var callable
     */
    protected $successCallback;

    /**
     * Failure callback argument array.
     *
     * @since 0.1.0
     * @access protected
     * @var mixed[]
     */
    protected $failureCbArgs;

    /**
     * Success callback arguments array.
     *
     * @since 0.1.0
     * @access protected
     * @var mixed[]
     */
    protected $successCbArgs;

    /**
     * Directories where templates should be looked for.
     *
     * @since 0.1.1
     * @access protected
     * @var String[]
     */
    protected $templateDirectories;

    /**
     * Constructor. Set the email type.
     *
     * @since 0.1.0
     *
     * @param String $type Type of email.
     *
     * @return void.
     */
    public function __construct($type)
    {
        $this->type = $type;

        $this->setupTemplateDirectories();
    }

    /**
     * Setup template directories for email templates. Attempt theme directories
     * first in case devs want to override the email templates. Child themes should
     * override parent themes.
     *
     * @since 0.1.1
     * @access protected
     * @return void
     */
    protected function setupTemplateDirectories()
    {
        $pluginEmailTemplates = wprsrv()->pluginDirectory . '/includes/templates/email';
        $templateDir = get_template_directory();
        $themeDir = get_stylesheet_directory();
        $templateEmailTemplates = $templateDir . '/wprsrv/email';
        $themeEmailTemplates = $themeDir . '/wprsrv/email';

        $this->templateDirectories = [
            $themeEmailTemplates,
            $templateEmailTemplates,
            $pluginEmailTemplates
        ];
    }

    /**
     * Locate an email template file. Attempt the designated template directories
     * and then fallback to the plugin's own template.
     *
     * @since 0.1.1
     * @access protected
     *
     * @param String $template Filename without path.
     *
     * @return String|null
     */
    protected function locateEmailTemplate($template)
    {
        $tmplFile = null;

        foreach ($this->templateDirectories as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            if (file_exists($dir . '/' . $template)) {
                $tmplFile = $dir . '/' . $template;

                // Stop looking and use the first one which is available.
                break;
            }
        }

        return $tmplFile;
    }

    /**
     * Set the wanted email template to use when sending.
     *
     * @since 0.1.0
     *
     * @param String $templateFile Template file name, will be searched for in
     *                             various directories.
     *
     * @return self
     */
    public function setTemplate($templateFile)
    {
        /**
         * Allow adjusting the filename of the email template file to load. Useful
         * for loading a slightly different file depending on other configuration.
         *
         * @since 0.1.1
         *
         * @param String $templateFile Template filename with no path.
         * @param \Wprsrv\Email self This email instance.
         */
        $templateFile = apply_filters('wprsrv/email_template_file_name', $templateFile, $this);

        // Prevent directory traversing for security.
        $templateFile = basename($templateFile);

        $template = $this->locateEmailTemplate($templateFile);

        if ($template === null) {
            throw new \InvalidArgumentException('Invalid template file for email template: ' . $templateFile);
        }

        $this->template = $template;

        return $this;
    }

    /**
     * Send with an array of data. The template file will be filled with the data.
     *
     * @since 0.1.0
     *
     * @param array $args
     *
     * @return void
     */
    public function sendWith(Array $args)
    {
        if ($this->template === null) {
            throw new \Exception('Cannot send email without a template.');
        }

        extract($args);

        $template = include($this->template);

        /**
         * Filter a type template for an email.
         *
         * Allows filtering the template content for a certain type of email.
         *
         * @since 0.1.0
         *
         * @param String $template The loaded template.
         */
        $template = apply_filters('wprsrv/email_template/' . $this->type, $template);

        $this->body = $template;

        $this->send();
    }

    /**
     * Simple send. If the template contains no customized data this is a good option.
     *
     * @since 0.1.0
     * @return void
     */
    public function send()
    {
        /**
         * Filter a type template for an email.
         *
         * Allows filtering the template content for a certain type of email.
         *
         * @since 0.1.0
         *
         * @param String $template The loaded template.
         */
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
     * @since 0.1.0
     * @return String
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the subject for this email.
     *
     * @since 0.1.0
     *
     * @param String $subject The subject.
     *
     * @return self
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Return the body of the email.
     *
     * @since 0.1.0
     * @return String
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the email body.
     *
     * @since 0.1.0
     *
     * @param String $body
     *
     * @return self
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get the receiving address.
     *
     * @since 0.1.0
     * @return String
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set the receiving address.
     *
     * @since 0.1.0
     *
     * @param String $to
     *
     * @return self
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get the email headers.
     *
     * @since 0.1.0
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set the email headers.
     *
     * @since 0.1.0
     *
     * @param mixed $headers
     *
     * @return self
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set the email sending failure callback.
     *
     * @since 0.1.0
     *
     * @param callable $failureCallback
     * @param mixed[] $args
     *
     * @return self
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
     * @since 0.1.0
     *
     * @param callable $successCallback
     * @param mixed[] $args
     *
     * @return self
     */
    public function setSuccessCallback($successCallback, Array $args = [])
    {
        $this->successCallback = $successCallback;
        $this->successCbArgs = $args;

        return $this;
    }
}
