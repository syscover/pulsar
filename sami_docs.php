<?php
use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Symfony\Component\Finder\Finder;
use Sami\Parser\Filter\TrueFilter;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('config')
    ->exclude('database')
    ->exclude('lang')
    ->exclude('views')
    ->exclude('routes.php')
    ->in('src')
;

return new Sami($iterator, [
    'theme'                => 'default',
    'title'                => 'CMS API',
    'build_dir'            => __DIR__.'/docs',
    'cache_dir'            => __DIR__.'/docs/cache',
    'remote_repository'    => new GitHubRemoteRepository('syscover/cms', 'src'),
    'default_opened_level' => 2,
]);