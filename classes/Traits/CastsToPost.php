<?php

namespace Wprsrv\Traits;

/**
 * Trait CastsToPost
 *
 * Allows "extending" the final class WP\_Post. Essentially there is a magic getter and caller which passed missing
 * properties and methods over to the injected WP\_Post instance. Classes that use this trait can create extended
 * logic on top of the WP\_Post logic and (mostly) pass those objects to places where WP_Post can be passed to.
 *
 * @property Integer $ID Post ID.
 * @property String $post_status Post status.
 *
 * @package Wprsrv\Traits
 */
trait CastsToPost
{
    /**
     * Injected WP_Post object.
     *
     * @var \WP_Post
     */
    public $post;

    /**
     * Transient cache key prefix.
     *
     * @var String
     */
    public $cachePrefix;

    /**
     * Magic getter
     *
     * If the property is not available on the implementing class, attempt the injected WP\_Post object.
     *
     * @throws \Exception
     *
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (property_exists($this->post, $key)) {
            return $this->post->{$key};
        }

        throw new \Exception(sprintf('Object %s does not contain property %s.', __CLASS__, $key));
    }

    /**
     * Magic setter
     *
     * If the property is not available on the implementing class, attempt the injected WP\_Post object.
     *
     * @throws \Exception
     *
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        if (property_exists($this->post, $key)) {
            $this->post->{$key} = $value;
        }

        throw new \Exception(sprintf('Object %s does not contain property %s.', __CLASS__, $key));
    }

    /**
     * Magic calls
     *
     * If the method is not available on the implementing class, attempt the injected WP\_Post object.
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this->post, $method) && is_callable([$this->post, $method])) {
            return $this->post->{$method}($arguments);
        }

        throw new \BadMethodCallException(sprintf('Method %s not found in object %s', $method, __CLASS__));
    }

    /**
     * Constructor
     *
     * Pass in either a post ID (integer) or a WP\_Post object. The post will be available through the `post` property
     * or through the magic getters and setters.
     *
     * @throws \InvalidArgumentException If the given post or ID is not an actual WP\_Post reference or the
     *     instantiation of the new WP\_Post fails.
     *
     * @param \WP_Post|Integer $post Post object or ID to use for injection.
     *
     * @return void
     */
    public function __construct($post)
    {
        if (is_numeric($post)) {
            $this->post = get_post((int) $post);
        } elseif ($post instanceof \WP_Post) {
            $this->post = $post;
        } else {
            throw new \InvalidArgumentException(sprintf('Cannot instantiate %s, invalid post object or id given.', __CLASS__));
        }

        $this->setupCache();
    }

    /**
     * Setup caching setup.
     *
     * @access protected
     * @return void
     */
    protected function setupCache()
    {
        $this->cachePrefix = 'wprsrv_' . strtolower(__CLASS__) . $this->ID . '_';
    }
}
