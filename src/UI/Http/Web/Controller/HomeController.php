<?php

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
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function index(  Request $request ): Response {
				return $this->render( 'home/index.html.twig', [
			'message' => "Mi tityulo con script in titl +ADw-script+AD4-alert('hola')+ADsAPA-/script+AD4-"
		] );
	}
}
