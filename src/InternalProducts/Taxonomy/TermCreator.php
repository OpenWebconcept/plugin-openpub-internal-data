<?php
/**
 * Termcreator to create term if not exist..
 */

namespace OWC\OpenPub\InternalProducts\Taxonomy;

/**
 * Termcreator to create term if not exist.
 */
class TermCreator
{

    /**
     * Taxonomy which will be created if not exist.
     *
     * @var string
     */
    private $taxonomy;

    /**
     * Boots the TermCreator.
     *
     * @param string $taxonomy
     */
    public function __construct($taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * If term does not exist, insert it.
     *
     * @param string $term
     */
    public function createIfNotExists($term)
    {
        if (is_null(term_exists($term, $this->taxonomy))) {
            wp_insert_term($term, $this->taxonomy);
        }
    }
}
