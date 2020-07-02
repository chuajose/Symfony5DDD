<?php

declare( strict_types=1 );

namespace App\UI\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {
	/**
	 * @Route(
	 *     "/",
	 *     name="index",
	 *     methods={"GET"}
	 * )
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(  Request $request ): Response {
				return $this->render( 'home/index.html.twig', [
			'message' => "Entro"
		] );
	}
}
