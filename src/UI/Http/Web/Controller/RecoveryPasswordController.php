<?php

declare( strict_types=1 );

/**
 * Created by jeek.
 * User: Jose Manuel Suárez Bravo
 * Date: 22/03/19
 * Time: 11:06
 */

namespace App\UI\Http\Web\Controller;

use App\Domain\Auth\Model\PasswordRecovery;
use App\Domain\Auth\Model\User;
use App\Domain\Auth\Model\UserId;
use App\Domain\Auth\Repository\ClientRepositoryInterface;
use App\Domain\Auth\Repository\PasswordRecoveryRepositoryInterface;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use App\Domain\Auth\ValueObject\Password;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Infrastructure\Mailer\Sender\Adapter\MailerInterface;
use App\Infrastructure\Mailer\Sender\SenderInterface;

final class RecoveryPasswordController extends AbstractController{

	private $passwordRecovery;
	private $userRepository;
	private $userPasswordEncoder;
	private $tokenGenerator;
	private $mailer;

	function __construct( PasswordRecoveryRepositoryInterface $passwordRecovery, AuthRepositoryInterface $userRepository, UserPasswordEncoderInterface $userPasswordEncoder,  TokenGeneratorInterface $tokenGenerator, SenderInterface $mailer ) {

		$this->userRepository      = $userRepository;
		$this->userPasswordEncoder = $userPasswordEncoder;
		$this->tokenGenerator = $tokenGenerator;
		$this->passwordRecovery = $passwordRecovery;
		$this->mailer = $mailer;
	}


	/**
	 * @Route(
	 *     "/password/forgotten",
	 *     name="forgotresetPassword",
	 *     methods={"GET"}
	 * )
	 *
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function forgotresetPassword( Request $request): Response {


		return $this->render( 'password/forgot_password.html.twig', [

		] );


	}



	/**
	 * @Route(
	 *     "/password/forgotten",
	 *     name="forgotPasswordPost",
	 *     methods={"POST"}
	 * )
	 *
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function forgotPasswordPost( Request $request): Response {

		$user = $this->userRepository->findOneByEmail($request->get('email'));
		$token = $this->tokenGenerator->generateToken();

		$expired =new \DateTimeImmutable('now');
		$expired = $expired->add(new \DateInterval('PT45M')); // added 45 minutes

		$passwordRecovery = new PasswordRecovery(rand(1,10), $user, $token, $expired );


		if($existOld = $this->passwordRecovery->findOneByUser($user)){

			$this->passwordRecovery->remove($existOld);
		}

		$this->passwordRecovery->save($passwordRecovery);
		$this->mailer->send( $_ENV['MAILER_FROM'], [ $user->getEmail() ], 'emails/user/password_recovery.html.twig', [
			'token' =>$token,
		] );
		return $this->render( 'password/forgot_password.html.twig', [

		] );

	}

	/**
	 * @Route(
	 *     "/password/reset/{token}",
	 *     name="resetPassword",
	 *     methods={"GET"}
	 * )
	 *
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function resetPassword( Request $request, $token): Response {

		$passwordRecovery = $this->passwordRecovery->findOneByToken($token);




		if(null === $passwordRecovery){

			dd('error');
		}
		$now = new \DateTime("now");

		if($now > $passwordRecovery->getExpired()){
			$this->passwordRecovery->remove($passwordRecovery);
			dd('Ya no es posible utiliar el token, solintua una nueo');

		}
		return $this->render( 'password/reset_password.html.twig', [
				'email' => $passwordRecovery->getUser()->getEmail(),
			'token' => $token
		] );


	}

	/**
	 * @Route(
	 *     "/password/reset/{token}",
	 *     name="resetPasswordPost",
	 *     methods={"POST"}
	 * )
	 *
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function resetPasswordPost( Request $request, $token): Response {

		//$user = $this->userRepository->findOneByResetToken($token);
		$pass = $this->passwordRecovery->findOneByToken($token);
		$user = $pass->getUser();
		$password = new Password($request->get('password'));
		$password = $this->userPasswordEncoder->encodePassword($user, $password->toString());

		$user->setPassword($password);
		$user->setResetToken(null);
		$this->userRepository->save($user);
		return $this->render( 'password/reset_password.html.twig', [
			'email' => $user->getEmail(),
			'token' => ''
		] );


	}
}
