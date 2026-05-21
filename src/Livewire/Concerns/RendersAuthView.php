<?php

declare(strict_types=1);

namespace Componist\Auth\Livewire\Concerns;

use Componist\Auth\Support\AuthView;
use Componist\Auth\Support\ComponistAuthConfig;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\View\View;
use LogicException;

trait RendersAuthView
{
    protected function authView(AuthView $view): ViewContract
    {
        /** @var View $builder */
        $builder = view($view->value);

        $extended = $builder->extends(ComponistAuthConfig::layoutComponent());

        if (! $extended instanceof View) {
            throw new LogicException('Extended auth view must be an instance of '.View::class.'.');
        }

        $sectioned = $extended->section('content');

        if (! $sectioned instanceof View) {
            throw new LogicException('Auth view section must return an instance of '.View::class.'.');
        }

        return $sectioned;
    }
}
