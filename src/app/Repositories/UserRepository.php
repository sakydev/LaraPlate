<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function get(int $userId): ?User
    {
        return (new User())->where('id', $userId)->first();
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @return Collection<int, User>
     * */
    public function list(array $parameters, int $page, int $limit): Collection
    {
        $skip = ($page * $limit) - $limit;

        $users = new User();
        foreach ($parameters as $name => $value) {
            $users = $users->where($name, $value);
        }

        return $users->skip($skip)->take($limit)->orderBy('id', 'DESC')->get();
    }

    /**
     * @param array<string, mixed> $input
     * */
    public function create(array $input): User
    {
        return User::create([
            'username' => $input['username'],
            'email' => $input['email'],
            'status' => User::ACTIVE_STATE,
            'level' => $input['level'] ?? User::DEFAULT_LEVEL,
            'password' => Hash::make($input['password']),
        ]);
    }

    /**
     * @param array<string, mixed> $fieldValuePairs
     * */
    public function update(User $user, array $fieldValuePairs): User
    {
        $user->fill($fieldValuePairs)->save();

        return $user->refresh();
    }

    /**
     * @param array<string, mixed> $fieldValuePairs
     * */
    public function updateById(int $userId, array $fieldValuePairs): int
    {
        return (new User())->where('id', $userId)->update($fieldValuePairs);
    }

    public function publish(User $user): User
    {
        $user->status = User::ACTIVE_STATE;
        $user->save();

        return $user;
    }

    public function draft(User $user): User
    {
        $user->status = User::INACTIVE_STATE;
        $user->save();

        return $user;
    }
}
