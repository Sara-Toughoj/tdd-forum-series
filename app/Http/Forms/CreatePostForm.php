<?php

namespace App\Http\Forms;

use App\Exceptions\ThrottleException;
use App\Models\Reply;
use App\Rules\SpamFree;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreatePostForm extends FormRequest
{

    public function authorize()
    {
        return Gate::allows('create', new Reply);
    }


    protected function failedAuthorization()
    {
        throw new ThrottleException('You are replying too frequently. Please take a break.');
    }


    public function rules()
    {
        return [
            'body' => ['required', new SpamFree()]
        ];
    }

    public function persist($thread)
    {
        return $thread->addReply([
            'body' => request()->body,
            'user_id' => auth()->id(),
        ])->load('owner');
    }
}
