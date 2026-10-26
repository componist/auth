<?php

declare(strict_types=1);

namespace Componist\Auth\Livewire\Concerns;

use Componist\Auth\Support\AuthView;
use Componist\Auth\Support\ComponistAuthConfig;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\View\View;

trait RendersAuthView
{
    protected function authView(AuthView $view): ViewContract
    {
        /** @var View $builder */
        $builder = view($view->value);

        return $builder
            ->extends(ComponistAuthConfig::layoutComponent())
            ->section('content');
    }
}
