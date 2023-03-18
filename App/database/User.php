<?php

namespace App\database;

class User extends BaseModel
{

    protected $timestamps = false;
    public function tableName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return [
            'id',
            'mobile',
            'full_name',
            'created_at',
            'updated_at'
        ];
    }
}