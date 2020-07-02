<?php

declare( strict_types=1 );

namespace App\UI\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProfileController extends AbstractController {

	/**
	 * @Route(
	 *     "/profile",
	 *     name="myProfile",
	 *     methods={"GET"}
	 * )
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function myProfile( Request $request ): Response {

		$user = $this->getUser();
		if ( null === $user ) {
			return $this->redirectToRoute('login');
		}

		$data = [
			'id'         => $user->getId(),
			'email'      => $user->getEmail(),
			'created_at' => $user->getCreatedAt(),
			'username'   => $user->getUsername(),
			'name'       => $user->getName(),
		];

		return $this->render( 'profile/me.html.twig', [ 'data' => $data ] );

	}
}
