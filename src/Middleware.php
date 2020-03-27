<?php

namespace Core\Localization;

use Closure;
use Core\Localization\Contracts\NegotiatorInterface;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Container\Container as Application;

class Middleware
{
	/**
	 * Negotiates language
	 * @var \Core\Localization\NegotiatorInterface
	 */
	private $negotiator;

	/**
	 * Translator interface.
	 * @var \Illuminate\Contracts\Translation\Translator
	 */
	private $translator;

	/**
	 * Middleware constructor.
	 * @param NegotiatorInterface $negotiator 
	 */
	public function __construct(NegotiatorInterface $negotiator, Translator $translator)
	{
		$this->negotiator = $negotiator;		
		$this->translator = $translator;
	}

    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	// Get locale from request then set application locale.
        $locale = $this->negotiator->negotiateLanguage($request);
        $this->translator->setLocale($locale);

        // Do what we need.
    	$response = $next($request);

    	// Return response with the Content-Language header. 
    	$response->headers->set('Content-Language', $locale);
        return $response;
    }

}