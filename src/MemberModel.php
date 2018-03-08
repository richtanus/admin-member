<?php

namespace GG\Admin\Member;

use Illuminate\Database\Eloquent\Model;

class MemberModel extends Model
{
    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('admin.database.connection') ?: config('database.default'));

        $this->setTable(config('admin.extensions.member.table', 'users'));
    }
}
