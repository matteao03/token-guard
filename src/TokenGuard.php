<?php

namespace Matteao\TokenGuard;

use \Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Cache;

class TokenGuard extends SessionGuard
{
    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if ($this->loggedOut) {
            return;
        }

        if (!is_null($this->user)) {
            return $this->user;
        }

        $id = Cache::get($this->getName());


        if (!is_null($id) && $this->user = $this->provider->retrieveById($id)) {
            $this->updateCache($this->getName(), $id); //更新cache

            $this->fireAuthenticatedEvent($this->user);
        }

        return $this->user;
    }

    public function attempt(array $credentials = [], $remember = false)
    {
        $this->fireAttemptEvent($credentials, $remember);

        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $sessionId = $this->login($user, $remember);

            return $sessionId;
        }

        $this->fireFailedEvent($user, $credentials);

        return false;
    }

    public function login(AuthenticatableContract $user, $remember = false)
    {
        $sessionId = $this->session->getId();
        $this->updateCache($sessionId, $user->getAuthIdentifier());

        $this->fireLoginEvent($user, $remember);

        $this->setUser($user);

        return $sessionId;
    }

    protected function updateCache($key, $id)
    {
        Cache::put($key, $id, 3600 * 24);
    }

    protected function clearUserDataFromStorage()
    {
        Cache::forget($this->getName());
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getName()
    {
        return  $this->request->bearerToken() ?: '';
    }
}
