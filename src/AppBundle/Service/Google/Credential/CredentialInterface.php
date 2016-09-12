<?php
namespace AppBundle\Service\Google\Credential;

interface CredentialInterface {

	 /**
     * Retrieved stored credentials for the provided user ID.
     * @param string $userId User's ID.
     * @return string Json representation of the OAuth 2.0 credentials.
     * @throws \RuntimeException
     */
	public function getStoredCredentials($userId);

	/**
     * Store OAuth 2.0 credentials in the application's database.
     * @param string $userId User's ID.
     * @param string $credentials Json representation of the OAuth 2.0 credentials to store.
     * @throws \RuntimeException
     */
	public function storeCredentials($userId, $credential);
}