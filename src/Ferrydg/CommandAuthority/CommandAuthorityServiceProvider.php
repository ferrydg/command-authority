<?php namespace Ferrydg\CommandAuthority;

use Illuminate\Support\ServiceProvider;

class CommandAuthorityServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->package('ferrydg/command-authority');

        $this->app['authority'] = $this->app->share(function($app){
            $user = $this->getUser();
            $authority = new CommandAuthority($user);
            $fn = $app['config']->get('command-authority::initialize', null);

            if($fn) {
                $fn($authority);
            }

            return $authority;
        });

        $this->app->alias('authority', 'Ferrydg\CommandAuthority\CommandAuthority');

        $this->app->bindShared('Laracasts\Commander\CommandBus', function ($app)
        {
            $default = $app->make('Laracasts\Commander\DefaultCommandBus');
            $translator = $app->make('Laracasts\Commander\CommandTranslator');

            return new AuthorityCommandBus($default, $app, $translator);
        });
    }

    protected function getUser()
    {
        return $this->app['auth']->user();
    }
}
