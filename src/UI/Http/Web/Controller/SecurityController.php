<?php
declare( strict_types=1 );
namespace App\UI\Http\Web\Controller;

use App\Application\Auth\Dto\RegisterUserDto;
use App\Application\Auth\Exceptions\RegisterUserException;
use App\Application\Auth\RegisterUseCase;
use App\Domain\Auth\Exception\EmailNotValidException;
use App\Domain\Auth\Exception\UsernameNotValidException;
use App\Domain\Auth\Repository\AuthRepositoryInterface;
use Assert\AssertionFailedException;
use Cassandra\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class SecurityController extends AbstractController
{
	private AuthRepositoryInterface $userRepository;
	private UserPasswordEncoderInterface $userPasswordEncoder;

	public function __construct(
		UserPasswordEncoderInterface $userPasswordEncoder,
		AuthRepositoryInterface $userRepository
	){
		$this->userPasswordEncoder = $userPasswordEncoder;
		$this->userRepository = $userRepository;
	}


	/**
	 * @Route("/login", name="app_login")
	 * @param AuthenticationUtils $authenticationUtils
	 *
	 * @return Response
	 */
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();


		return $this->render('signin/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
	}

	/**
	 * @Route("/register", name="app_register")
	 * @param Request $request
	 * @param ValidatorInterface $validator
	 *
	 * @return Response
	 */
	public function register(Request $request, ValidatorInterface $validator): Response
	{
		$messages = null;
		if($_POST){
			try {
				$registerUserDto = new RegisterUserDto(
					$request->get('name'),
					$request->get('username'),
					$request->get('email'),
					$request->get('password'),
				);

				$errors = $validator->validate($registerUserDto);


				if (count($errors) > 0) {

					foreach ($errors as $violation) {
						$violations= [
							'propertyPath' => $violation->getPropertyPath(),
							'message' => $violation->getMessage(),
							'code' => $violation->getCode(),
						];
						$propertyPath = $violation->getPropertyPath();
						$message = ($propertyPath ? $propertyPath.': ' : '').$violation->getMessage();
						$messages[] = [
							'title' =>'An error occurred',
							'field' => $propertyPath,
							'detail' => $message,
							'violations' => $violations,
						];
					}
				}else{
					$create          = new RegisterUseCase( $this->userRepository, $this->userPasswordEncoder );
					$user = $create( $registerUserDto );
					$token = new UsernamePasswordToken($user, null, 'main', ['ROLE_USER']);
					$this->get('security.token_storage')->setToken($token);
					$this->get('session')->set('_security_main', serialize($token));
					return $this->redirectToRoute('myProfile');
				}

			} catch ( \InvalidArgumentException $exception ) {
				$messages[] = [
					'detail' =>  $exception->getMessage(),
				];
			}
		}
		return $this->render('signup/index.html.twig', [ 'errors' => $messages]);
	}
}
