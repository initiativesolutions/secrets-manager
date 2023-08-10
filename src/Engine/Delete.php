<?php

namespace SecretsManager\Engine;

use SecretsManager\Exception\NoSecretTokenException;

class Delete extends Engine
{

    public function delete(string $token)
    {
        $secrets = $this->readJsonSecrets();

        if (isset($secrets[$token])) {
            unset($secrets[$token]);
            $this->saveSecrets($secrets);
        } else {
            throw new NoSecretTokenException($token, $this->getFilePath());
        }
    }

}