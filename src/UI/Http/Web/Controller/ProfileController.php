<?php

declare( strict_types=1 );

/**
 * Created by jeek.
 * User: Jose Manuel Suárez Bravo
 * Date: 9/04/19
 * Time: 15:38
 */

namespace App\UI\Http\Web\Controller;


use App\Domain\Auth\Model\UserId;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use App\Domain\Auth\ValueObject\Username;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Annotation\Route;


final class ProfileController extends AbstractController {

	private $userRepository;

	public function __construct(
		AuthRepositoryInterface $userRepository
){
		$this->userRepository  = $userRepository;

	}


	/**
	 * @Route(
	 *     "/profile",
	 *     name="myProfile",
	 *     methods={"GET"}
	 * )
	 *
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function myProfile(Request $request){

		$user = $this->getUser();


		$data = [
			'id' => $user->getId(),
			'email' => $user->getEmail(),
			'created_at' => $user->getCreatedAt(),
			'username' => $user->getUsername(),
			'name' => $user->getName(),
		];





		return $this->render( 'profile/me.html.twig', [ 'data' => $data] );
	}


}
