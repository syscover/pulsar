<?php

if (! function_exists('base_lang')) {
    /**
     * Get base lang from session.
     *
     * @return string
     */
    function base_lang()
    {
        if(session('baseLang') === null)
        {
            session(['baseLang' => \Syscover\Pulsar\Models\Lang::getBaseLang()]);
        }

        return session('baseLang');
    }
}

if (! function_exists('is_current_resource')) {
    /**
     * check if current resource is equeal to proposal resource
     *
     * @param   $proposal
     * @param   bool $open
     * @return  string
     */
    function is_current_resource($proposal, $open = false)
    {
        $resource = request()->route()->getAction()['resource'];

        if((is_array($proposal) && in_array($resource, $proposal)) || $resource === $proposal)
        {
            if($open)
                return ' class="current open"';
            else
                return ' class="current"';
        }
    }
}

if (! function_exists('is_allowed')) {
    /**
     * check if a action is allowed on resource
     *
     * @param $resource
     * @param $action
     * @return bollean
     */
    function is_allowed($resource, $action)
    {
        return session('userAcl')->allows($resource, $action);
    }
}

if (! function_exists('trans_has')) {
    /**
     * Determine if a translation exists.
     *
     * @param  string  $key
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return bool
     */
    function trans_has($key, $locale = null, $fallback = true)
    {
        return app('translator')->has($key, $locale, $fallback);
    }
}