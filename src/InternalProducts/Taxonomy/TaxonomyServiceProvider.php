<?php
/**
 *  TaxonomyServiceProvider boots necessary methods.
 */

namespace OWC\OpenPub\InternalProducts\Taxonomy;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

/**
 * TaxonomyServiceProvider boots necessary methods.
 */
class TaxonomyServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->plugin->loader->addAction('init', $this, 'createTerms');
    }

    /**
     * asdf
     */
    public function createTerms()
    {
        $termCreator = new TermCreator('openpub-type');

        $termCreator->createIfNotExists('Internal');
        $termCreator->createIfNotExists('External');
    }
}
