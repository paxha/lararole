<?php

namespace Lararole\Traits;

trait Activable
{
    public function toggleActive()
    {
        $this->active = ! $this->active;
        $this->save();
    }
}
