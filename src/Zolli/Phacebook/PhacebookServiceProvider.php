<?php namespace Zolli\Phacebook;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class PhacebookServiceProvider extends
    ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = FALSE;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->bind('Phacebook', function() {
            return new Phacebook();
        });

        $this->app->booting(function() {
            $aliasLoader = AliasLoader::getInstance();
            $aliasLoader->alias('Phacebook', 'Zolli\Phacebook\Facades\Phacebook');
        });
    }

    public function boot() {
        $this->package('Zolli/Phacebook');

        include __DIR__.'/../../routes.php';
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return ['Phacebook'];
    }

}
