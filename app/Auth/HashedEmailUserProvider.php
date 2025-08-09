<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class HashedEmailUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) || !isset($credentials['email'])) {
            return null;
        }

        $query = $this->createModel()->newQuery();

        // Cari berdasarkan email_hash
        $query->where('email_hash', hash('sha256', strtolower($credentials['email'])));

        return $query->first();
    }

    public function retrieveById($identifier)
    {
        return $this->createModel()->newQuery()->find($identifier);
    }
}
