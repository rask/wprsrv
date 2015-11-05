<?php

namespace Wprsrv\Traits;

/**
 * Trait CastsToPost
 *
 * Allows "extending" the final class WP\_Post. Essentially there is a magic getter
 * and caller which passed missing properties and methods over to the injected
 * WP\_Post instance. Classes that use this trait can create extended logic on top of
 * the WP\_Post logic and (mostly) pass those objects to places where WP_Post can be
 * passed to.
 *
 * @property Integer $ID Post ID.
 * @property String $post_status Post status.
 *
 * @since 0.1.0
 * @package Wprsrv\Traits
 */
trait CastsToPost
{
    /**
     * Injected WP_Post object.
     *
     * @since 0.1.0
     * @var \WP_Post
     */
    public $post;

    /**
     * Transient cache key prefix.
     *
     * @since 0.1.0
     * @var String
     */
    public $cachePrefix;

    /**
     * Magic getter.
     *
     * If the property is not available on the implementing class, attempt the
     * injected WP\_Post object.
     *
     * @since 0.1.0
     * @throws \Exception If property does not exist in injected WP_Post.
     *
     * @param String $key The propery key.
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
     * Magic setter.
     *
     * If the property is not available on the implementing class, attempt the
     * injected WP\_Post object.
     *
     * @since 0.1.0
     * @throws \Exception If property does not exist in injected WP_Post.
     *
     * @param String $key Key to use.
     * @param mixed $value Value to set.
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
     * Magic calls.
     *
     * If the method is not available on the implementing class, attempt the injected
     * WP\_Post object.
     *
     * @since 0.1.0
     * @throws \Exception If method does not exist in injected WP_Post.
     *
     * @param String|callable $method Method to call.
     * @param mixed $arguments Method arguments.
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
     * Constructor.
     *
     * Pass in either a post ID (integer) or a WP\_Post object. The post will be
     * available through the `post` property or through the magic getters and
     * setters.
     *
     * @since 0.1.0
     * @throws \InvalidArgumentException If the given post or ID is not an actual
     *                                   WP\_Post reference or the instantiation of
     *                                   the new WP\_Post fails.
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
     * Caching setup.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function setupCache()
    {
        $fqcn = trim(__CLASS__, '\\');

        $className = preg_replace('%^.*\\\\([a-zA-Z]+)$%', '$1', $fqcn);

        $this->cachePrefix = 'wprsrv_' . strtolower($className) . $this->ID . '_';
    }
}
