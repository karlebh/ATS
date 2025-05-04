<?php

namespace App\Actions\Comment;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Models\PurchaseOrder;
use App\Models\Task;
use App\Models\TemporaryFile;
use App\Models\User;
use App\Notifications\CommentCreated;
use App\Notifications\CommentReplied;
use App\Traits\ResponseTrait;
use App\Traits\UtilityTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

class StoreAction
{
    use ResponseTrait, UtilityTrait;

    public function execute(CreateCommentRequest $request)
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

        $requestData = $request->validated();

        $requestData['commentable_type'] = $requestData['commentable_type'] == 'task' ? Task::class : PurchaseOrder::class;

        $comment = Comment::create([
            ...Arr::except($requestData, ['upload_id']),
            'user_id' => auth()->id(),
        ]);

        if (! $comment) {
            $this->deleteTemporaryUploadedFiles($uploadedFiles);
            return $this->badRequestResponse('Could not create create');
        }

        $this->sendNotificationToConcernedUsers($comment);

        if ($uploadedFiles) {
            $this->processFiles($uploadedFiles, $comment);
        }

        return $this->successResponse('Comment created successfully', ['comment' => $comment->load('user')]);
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
                new CommentReplied(
                    commentOwner: auth()->user(),
                    comment: $comment->load('commentable'),
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
                new CommentCreated(
                    commentOwner: auth()->user(),
                    comment: $comment->load('commentable'),
                )
            );
        }
    }
}
