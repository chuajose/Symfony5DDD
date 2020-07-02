<?php

declare( strict_types=1 );

namespace App\Infrastructure\oAuth2Server\Bridge;

use App\Domain\Auth\Repository\ClientRepositoryInterface as AppClientRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

final class ClientRepository implements ClientRepositoryInterface
{
	/**
	 * @var AppClientRepositoryInterface
	 */
	private AppClientRepositoryInterface $appClientRepository;

	/**
	 * ClientRepository constructor.
	 *
	 * @param AppClientRepositoryInterface $appClientRepository
	 */
	public function __construct(AppClientRepositoryInterface $appClientRepository)
	{
		$this->appClientRepository = $appClientRepository;
	}

	/**
	 * @param $clientIdentifier
	 * @param null $grantType
	 * @param null $clientSecret
	 * @param bool $mustValidateSecret
	 *
	 * @return ClientEntityInterface|null
	 */
	public function getClientEntity(
		$clientIdentifier,
		$grantType = null,
		$clientSecret = null,
		$mustValidateSecret = true
	): ?ClientEntityInterface {

		$appClient = $this->appClientRepository->findActive($clientIdentifier);
		if ($appClient === null) {
			return null;
		}
		if ($mustValidateSecret && !hash_equals($appClient->getSecret(), (string)$clientSecret)) {
			return null;
		}

		return new Client($clientIdentifier, $appClient->getName(), $appClient->getRedirect());
	}

	public function validateClient( $clientIdentifier, $clientSecret, $grantType ) {
		// TODO: Implement validateClient() method.
	}

}
