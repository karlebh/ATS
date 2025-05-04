<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'parent_id' => $this->parent_id,
            'content' => $this->content,
            'commentable_id' => $this->commentable_id,
            'commentable_type' => $this->commentable_type,
            'replies' => CommentResource::collection($this->replies),
            'user' => $this->whenLoaded('user'),
            'files' => $this->files,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
