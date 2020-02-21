<?php

namespace Matteao\TokenGuard;

use \Illuminate\Auth\SessionGuard;

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

        $user = null;

        $id = $this->session->get($this->getName());

        if (!is_null($id) && $this->user = $this->provider->retrieveById($id)) {
            $this->fireAuthenticatedEvent($this->user);
        }

        return $this->user = $user;
    }

    protected function clearUserDataFromStorage()
    {
        $this->session->remove($this->getName());
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getName()
    {
        return  $this->request->bearerToken();
    }
}
