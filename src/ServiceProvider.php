<?php

namespace Weareframework\PinpointImage;

use Statamic\Providers\AddonServiceProvider;
use Weareframework\PinpointImage\Fieldtypes\PinPointImage;
use Weareframework\PinpointImage\Tags\PinpointImageTag;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    protected $scripts = [
        __DIR__.'/../resources/dist/js/cp.js',
    ];

    protected $tags = [
        PinpointImageTag::class,
    ];

    protected $fieldtypes = [
        PinPointImage::class,
    ];

    public function boot()
    {
        parent::boot();
        Statamic::booted(function () {
            $this->bootAddonViews();
        });
    }

    protected function bootAddonViews(): self
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views/tags', 'pinpoint-image');

        $this->publishes([
            __DIR__.'/../resources/views/tags' => resource_path('views/vendor/pinpoint-image/tags'),
        ], 'pinpoint-image-views');

        return $this;
    }
}
