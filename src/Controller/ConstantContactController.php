<?php

namespace Drupal\constant_contact_block\Controller;

use Drupal\constant_contact_block\authentication\ConstantContactAuth2;
use Drupal\constant_contact_block\services\AuthenticationServiceInterface;
use Drupal\constant_contact_block\services\ConstantContactDataInterface;
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
  protected $constantContactDataService;

  /**
   * ConstantContactController constructor.
   *
   * @param \Drupal\constant_contact_block\services\AuthenticationServiceInterface $authenticationService
   * @param \Drupal\constant_contact_block\services\ConstantContactDataInterface $constantContactDataService
   */
  public function __construct(AuthenticationServiceInterface $authenticationService,
                              ConstantContactDataInterface $constantContactDataService) {
    $this->authenticationService = $authenticationService;
    $this->constantContactDataService = $constantContactDataService;
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
    $session->set('access_token', $accessTokenResponse->access_token);

    return new JsonResponse($accessTokenResponse );
  }
  public function getContactLists(){

    $lists = $this->constantContactDataService->getContactLists();
    //print_r($lists);

    $rows = array();
    foreach ($lists as $list){
      $rows[$list->id] = [
        'id' => $list->id, 'name' => $list->name, 'list_id' => $list->list_id,
        'modified_date' => $list->modified_date, 'status' => $list->status,
        'contact_count' => $list->contact_count, 'created_date' => $list->created_date,
      ];
    }

    $build = array(
      'table' => [
        '#theme' => 'table',
        '#header' => array(
          'id' => $this->t('id'), 'name' => $this->t('name'),
          'list_id' => $this->t('list id'), 'modified_date' => $this->t('modified date'),
          'status' => $this->t('status'), 'contact_count' => $this->t('contact count'),
          'created_date' => $this->t('created date')
        ),
        '#rows' => $rows,
        '#empty' => t('No contact lists found.'),
      ],

    );

    $build['pager'] = array(
      '#type' => 'pager'
    );
    return $build;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('constant_contact_block.authentication'),
      $container->get('constant_contact_block.data_manager')
    );
  }
}
