<?php

if (! function_exists('base_lang')) {
    /**
     * Get base lang from session.
     *
     * @return string
     */
    function base_lang()
    {
        return session('baseLang') === null? config('app.locale') : session('baseLang');
    }
}