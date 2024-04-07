<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "assignee_id" => $this->assignee_id,
            "due_date" => $this->due_date,
            "status_id" => $this->status_id,
            "dependencies" => $this->dependencies
        ];
    }
}
