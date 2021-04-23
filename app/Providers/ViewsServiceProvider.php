<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewsServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        Paginator::defaultSimpleView('pagination::simple-default');
        Paginator::defaultView('pagination::default');

        Blade::directive('action', fn ($e) => "<?php echo action($e); ?>");

        Blade::directive('asset', fn ($e) => "<?php echo asset($e); ?>");

        Blade::directive('config', fn ($e) => "<?php echo config($e); ?>");

        Blade::directive(
            'gravatar',
            fn ($e) => "https://www.gravatar.com/avatar/<?php echo md5(mb_strtolower(trim($e))); ?>?s=128&d=mm&r=pg"
        );

        Blade::directive(
            'markdown',
            fn ($e) => "<?php echo new Illuminate\Support\HtmlString((new League\CommonMark\CommonMarkConverter([], League\CommonMark\Environment::createCommonMarkEnvironment()))->convertToHtml($e)); ?>"
        );

        Blade::directive('mix', fn ($e) => "<?php echo mix($e); ?>");

        Blade::directive('old', fn ($e) => "<?php echo old($e); ?>");

        Blade::directive('route', fn ($e) => "<?php echo route($e); ?>");

        Blade::directive('secureAsset', fn ($e) => "<?php echo secure_asset($e); ?>");

        Blade::directive('url', fn ($e) => "<?php echo url($e); ?>");

        View::composer('*', fn ($v) => $v->withUser($this->app['auth']->user()));
    }
}
