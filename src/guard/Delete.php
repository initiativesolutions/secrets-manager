<?php

namespace SecretsManager\Guard;

class Delete extends Guard
{

    public function delete(string $token)
    {
        $secrets = $this->readJsonSecrets();

        if (isset($secrets[$token])) {
            unset($secrets[$token]);
            $this->saveSecrets($secrets);
        } else {
            throw new \Exception("Can't delete token ! [$token] not exist here [{$this->getFilePath()}]");
        }
    }

}