<?php

namespace Wprsrv\PostTypes;

/**
 * Class PostType
 *
 * Abstract base class for custom post types.
 *
 * @abstract
 * @since 0.1.0
 * @package Wprsrv\PostTypes
 */
abstract class PostType
{
    /**
     * Post type slug/identifier.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $post_type_slug = 'posttype';

    /**
     * Taxonomies to register for this post type.
     *
     * @since 0.1.0
     * @access protected
     * @var String[]
     */
    protected $taxonomies = [];

    /**
     * Constructor.
     *
     * @since 0.1.0
     * @return void
     */
    public function __construct()
    {
        $this->registerPostType();
        $this->setupPostTypeCapabilities();
        $this->registerTaxonomies();
    }

    /**
     * Register the post type.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected abstract function registerPostType();

    /**
     * Setup capabilities for the post type. Extend if needed.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function setupPostTypeCapabilities() {}

    /**
     * Register defined taxonomies for this post type.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function registerTaxonomies()
    {
        if (!empty($this->taxonomies)) {
            /**
             * Taxonomies to register for a post type.
             *
             * @since 0.1.0
             *
             * @param String[] $taxonomies Taxonomy slugs being registered.
             * @param String $postTypeSlug Post type slug for the post type.
             */
            $taxonomies = apply_filters('wprsrv/post_type_taxonomies', $this->taxonomies, $this->post_type_slug);

            foreach ($taxonomies as $taxSlug) {
                register_taxonomy_for_object_type($taxSlug, $this->post_type_slug);
            }
        }
    }
}
