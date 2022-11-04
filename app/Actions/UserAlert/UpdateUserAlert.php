<?php

namespace App\Actions\UserAlert;

use App\Models\UserAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UpdateUserAlert
{
    /**
     * Create a UserAlert.
     *
     * @param  array  $input
     * @return \App\Models\UserAlert
     */
    public function update(UserAlert $useralert, array $input)
    {
        Validator::make($input, [
            'shown' => ['required'],
        ])->validate();

        return DB::transaction(function () use ($input, $useralert) {
            $useralert->forceFill([
                'shown' => $input['shown'],
            ])->save();
        });
    }
}
