<?php
namespace AppBundle\Service\Google\Credential;

class FileSystemImplementation implements CredentialInterface {

    public function getStoredCredentials($userId) {
        $filename = '/tmp/' . $userId . ".json";
        if(!file_exists($filename)) {
            throw new \RuntimeException('user credentials not found');
        }

        return file_get_contents($filename);
    }

    public function storeCredentials($userId, $credentials) {
        $filename = '/tmp/' . $userId . ".json";
        try {
            $file = new \SplFileObject($filename, "w");
            $file->fwrite($credentials);
        }
        catch(\Exception $ex) {
            throw new \RuntimeException($ex);
        }
    }
}