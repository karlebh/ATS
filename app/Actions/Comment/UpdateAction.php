<?php

namespace App\Actions\Comment;

use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\TemporaryFile;
use App\Models\User;
use App\Notifications\CommentUpdated;
use App\Notifications\ReplyUpdated;
use App\Traits\ResponseTrait;
use App\Traits\UtilityTrait;
use Illuminate\Support\Facades\Notification;

class UpdateAction
{
    use ResponseTrait, UtilityTrait;


    public function execute(UpdateCommentRequest $request, int $id)
    {
        $query = TemporaryFile::where('user_id', auth()->id());

        if ($query->exists() && ! $request->safe()->upload_id) {
            return $this->badRequestResponse('Uplaod needs an upload id to properly attach the files to this comment. Please include it .');
        }

        if (
            $query->exists() &&
            $query->where('upload_id', $request->safe()->upload_id)->doesntExist()
        ) {
            return $this->badRequestResponse('Invalid upload id');
        }

        $uploadedFiles = $query->where('upload_id', $request->safe()->upload_id)->get();

        if (! $request->validated() && $uploadedFiles) {
            $this->deleteTemporaryUploadedFiles($uploadedFiles);
        }
        // 
        $comment = Comment::find($id);

        if (! $comment) {
            $this->deleteTemporaryUploadedFiles($uploadedFiles);
            return $this->notFoundResponse('Comment does not exist');
        }

        if (empty($request->all()) && $uploadedFiles->isEmpty()) {
            return $this->successResponse(
                'No data were passed, therefore no changes made',
                ['purchase_order' => $comment]
            );
        }

        if ($uploadedFiles->isNotEmpty()) {
            $this->processFiles($uploadedFiles, $comment);
        }

        $requestData = $request->validated();

        $comment->content = $requestData['content'];
        $comment->save();

        if (! $comment->wasChanged()) {
            $this->deleteTemporaryUploadedFiles($uploadedFiles);
        }

        $this->sendNotificationToConcernedUsers($comment);

        $comment = $comment->load(['replies', 'user'])->refresh();

        return $this->successResponse('Comment updated successfully', ['comment' => $comment]);
    }

    private function sendNotificationToConcernedUsers($comment)
    {

        if ($comment->parent_id) {
            $parent =  Comment::with('user')->find($comment->parent_id);

            if (! $parent) {
                $this->notFoundResponse("Could not find comment with id: {$comment->parent_id}");
            }

            Notification::sendNow(
                $comment->user,
                new ReplyUpdated(
                    commentOwner: auth()->user(),
                    commentable: $comment->commentable,
                    comment: $comment,
                )
            );
        } else {

            $concernedUsersId =
                array_unique(
                    array_merge(
                        [$comment->commentable->user_id],
                        $comment->commentable->assignedMembers->pluck('id')->toArray()
                    )
                );

            $users = User::find($concernedUsersId);
            $userIds = implode(',', $users->pluck('id')->toArray());

            if (! $users) {
                $this->notFoundResponse("Could not find users with these ids: {$userIds}");
            }

            Notification::sendNow(
                $users,
                new CommentUpdated(
                    commentOwner: auth()->user(),
                    comment: $comment->load('commentable'),
                )
            );
        }
    }
}
