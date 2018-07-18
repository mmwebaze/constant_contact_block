<?php

namespace Drupal\constant_contact_block\Controller;

use Drupal\constant_contact_block\authentication\ConstantContactAuth2;
use Drupal\constant_contact_block\services\AuthenticationServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConstantContactController.
 */
class ConstantContactController extends ControllerBase {

  /**
   * @var \Drupal\constant_contact_block\services\AuthenticationServiceInterface
   */
  protected $authenticationService;
  /**
   * ConstantContactController constructor.
   *
   * @param \Drupal\constant_contact_block\services\AuthenticationServiceInterface $authenticationService
   */
  public function __construct(AuthenticationServiceInterface $authenticationService) {
    $this->authenticationService = $authenticationService;
  }

  /**
   * Get Authorization
   *
   */
  public function getAuthorization(){

    //$auth = new ConstantContactAuth2();
    //$url = $auth->getAuthorizationUrl();
    $url = $this->authenticationService->getAuthorizationUrl();

    return array(
      '#markup' => '<a href='.$url.' target="_blank">Authorize App</a>',
    );
  }

  /**
   * Getcode
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function getCode(Request $request) {
    $code = $request->query->get('code');
    $session = $request->getSession();

    $response = $this->authenticationService->getAccessToken($code);
    $accessTokenResponse = json_decode($response);
    //print_r($accessTokenResponse->access_token);die();
    $session->set('access_token', $accessTokenResponse->access_token);

    return new JsonResponse($accessTokenResponse );
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('constant_contact_block.authentication')
    );
  }
}
