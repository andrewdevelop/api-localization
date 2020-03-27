<?php

namespace Core\Localization;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Translation\Translator;
use Core\Localization\Middleware;
use Core\Localization\LanguageRepository;
use Core\Localization\NegotiatorInterface;
use Core\Localization\LanguageNegotiator;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Register service.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/locale.php', 'locale');
        $this->app->configure('locale');

        $repository = new LanguageRepository;

		$default = $this->getDefaultLocale();
		$supported = $this->getSupportedLocales();

        $this->app->bind(NegotiatorInterface::class, function() use ($default, $supported) {
        	return new LanguageNegotiator($default, $supported);
        });

        // Fix missing Lumen binding.
        $this->app->alias('translator', Translator::class);

        // Register global middleware.
        $this->app->middleware(Middleware::class);
    }

    /** Get default locale. */
    protected function getDefaultLocale()
    {
        return $this->app->config->get('locale.default');
    }

    /** Lists supported locales. */
    protected function getSupportedLocales()
    {
        $repository = new LanguageRepository;
        $filter = $this->app->config->get('locale.supported');
        $filter = is_array($filter) ? $filter : explode(',', $filter);
        return $repository->filter($filter);
    }
}
