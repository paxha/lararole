<?php

namespace Lararole\Traits;

trait Activable
{
    public function toggleActive()
    {
        $this->active = !$this->active;
        $this->save();
    }

    public function markAsActive()
    {
        $this->active = true;
        $this->save();
    }

    public function markAsInactive()
    {
        $this->active = false;
        $this->save();
    }
}