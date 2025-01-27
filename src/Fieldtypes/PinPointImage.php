<?php

namespace Weareframework\PinpointImage\Fieldtypes;

use Statamic\Exceptions\AssetContainerNotFoundException;
use Statamic\Facades\AssetContainer;
use Statamic\Facades\GraphQL;
use Statamic\Fields\Fields as BlueprintFields;
use Statamic\Fields\Fieldtype;
use Statamic\Fieldtypes\Assets\UndefinedContainerException;
use Weareframework\PinpointImage\GraphQL\PinPointImageFieldType;

class PinPointImage extends Fieldtype
{
    protected $categories = ['media', 'relationship'];
    protected $defaultValue = [
        'image' => '',
        'annotations' => []
    ];
    protected $selectableInForms = true;

    protected function configFieldItems(): array
    {
        return [
            'mode' => [
                'display' => __('Mode'),
                'instructions' => __('statamic::fieldtypes.assets.config.mode'),
                'type' => 'select',
                'default' => 'list',
                'options' => [
                    'grid' => __('Grid'),
                    'list' => __('List'),
                ],
                'width' => 50,
            ],
            'container' => [
                'display' => __('Container'),
                'instructions' => __('statamic::fieldtypes.assets.config.container'),
                'type' => 'asset_container',
                'max_items' => 1,
                'mode' => 'select',
                'width' => 50,
            ],
            'folder' => [
                'display' => __('Folder'),
                'instructions' => __('statamic::fieldtypes.assets.config.folder'),
                'type' => 'asset_folder',
                'max_items' => 1,
                'width' => 50,
            ],
            'restrict' => [
                'display' => __('Restrict'),
                'instructions' => __('statamic::fieldtypes.assets.config.restrict'),
                'type' => 'toggle',
                'width' => 50,
            ],
            'allow_uploads' => [
                'display' => __('Allow Uploads'),
                'instructions' => __('statamic::fieldtypes.assets.config.allow_uploads'),
                'type' => 'toggle',
                'default' => true,
                'width' => 50,
            ],
            'show_filename' => [
                'display' => __('Show Filename'),
                'instructions' => __('statamic::fieldtypes.assets.config.show_filename'),
                'type' => 'toggle',
                'default' => true,
                'width' => 50,
            ],
        ];
    }

    public function canHaveDefault()
    {
        return false;
    }

    /*
     * how to load your data in
     */
    public function preProcess($values)
    {
        if (is_null($values) || empty($values)) {
            return null;
        }

        return $values;
    }

    public function process($data)
    {
        return $data;
    }

    protected function fields()
    {
        return new BlueprintFields($this->configFieldItems());
    }

    public function preload()
    {
        return [
            'default' => $this->defaultValue(),
            'data' => $this->getItemData($this->field->value() ?? []),
            'container' => $this->container()->handle(),
        ];
    }

    public function getItemData($items)
    {
        return $items;
    }


    protected function container()
    {
        if ($configured = $this->config('container')) {
            if ($container = AssetContainer::find($configured)) {
                return $container;
            }

            throw new AssetContainerNotFoundException($configured);
        }

        if (($containers = AssetContainer::all())->count() === 1) {
            return $containers->first();
        }

        throw new UndefinedContainerException;
    }

    public function preProcessIndex($data)
    {
        return [];
    }

    public function toGqlType()
    {
        return GraphQL::type(PinPointImageFieldType::NAME);
    }
}
