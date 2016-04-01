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