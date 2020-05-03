<?php

namespace Lararole\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Module extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'key' => $this->id,
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'alias' => $this->alias,
            'icon' => $this->icon,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];

        if ($this->children->count()) {
            $array['children'] = new ModuleCollection($this->children);
        }

        return $array;
    }
}
