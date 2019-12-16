<?php

namespace Lararole\Containers;

use Lararole\Models\Module;

class ModuleServiceContainer
{
    public function all()
    {
        return Module::all();
    }

    public function root()
    {
        return Module::root()->get();
    }

    public function leaf()
    {
        return Module::leaf()->get();
    }
}
