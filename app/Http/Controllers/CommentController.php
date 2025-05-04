<?php

namespace App\Http\Controllers;

use App\Actions\Comment\StoreAction;
use App\Actions\Comment\UpdateAction;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    use ResponseTrait;

    public function store(CreateCommentRequest $request)
    {
        try {
            return (new StoreAction())->execute($request);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function update(UpdateCommentRequest $request, int $id)
    {
        try {
            return (new UpdateAction())->execute($request, $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function destroy(int $id)
    {
        $comment = Comment::find($id);

        if (! $comment) {
            return $this->notFoundResponse("Comment with id: {$id} not found");
        }

        return $comment->delete()
            ?  $this->successResponse('Comment deleted successfully')
            : $this->badRequestResponse('Could not delete comment');
    }
}
