<?php

namespace Drupal\constant_contact_block\Controller;

use Drupal\constant_contact_block\authentication\ConstantContactAuth2;
use Drupal\constant_contact_block\services\AuthenticationServiceInterface;
use Drupal\constant_contact_block\services\ConstantContactDataInterface;
use Drupal\constant_contact_block\services\ConstantContactInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
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
  protected $constantContactService;

  /**
   * ConstantContactController constructor.
   *
   * @param \Drupal\constant_contact_block\services\AuthenticationServiceInterface $authenticationService
   * @param \Drupal\constant_contact_block\services\ConstantContactDataInterface $constantContactDataService
   * @param \Drupal\constant_contact_block\services\ConstantContactInterface $constantContactService
   */
  public function __construct(AuthenticationServiceInterface $authenticationService,
                              ConstantContactDataInterface $constantContactDataService, ConstantContactInterface $constantContactService) {
    $this->authenticationService = $authenticationService;
    $this->constantContactDataService = $constantContactDataService;
    $this->constantContactService = $constantContactService;
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

    $rows = array();
    foreach ($lists as $list){
      $rows[$list->id] = [
        'id' => $list->id, 'name' => $list->name, 'list_id' => $list->list_id,
        'modified_date' => $list->modified_date, 'status' => $list->status,
        'contact_count' => $list->contact_count, 'created_date' => $list->created_date,
        Link::fromTextAndUrl('Delete', Url::fromUserInput('/admin/constant_contact_block/list_delete/'.$list->id)),
      ];
    }

    $build = array(
      'table' => [
        '#theme' => 'table',
        '#header' => array(
          'id' => $this->t('id'), 'name' => $this->t('name'),
          'list_id' => $this->t('list id'), 'modified_date' => $this->t('modified date'),
          'status' => $this->t('status'), 'contact_count' => $this->t('contact count'),
          'created_date' => $this->t('created date'), 'operations' => $this->t('operations')
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
  public function importContactLists(Request $request){

    $importStatus = $request->attributes->get('importStatus');
    $form = $this->formBuilder()->getForm('Drupal\constant_contact_block\Form\ConstantContactImportListForm');

    if ($importStatus == 0){
      drupal_set_message('Import Constant Contact lists locally. Any list already imported will be deleted first.', 'warning');
    }
    else{
      $remoteLists = $this->constantContactService->getContactLists();
      $remoteLists = json_decode($remoteLists);
      $this->constantContactDataService->deleteTable('constant_contact_lists');

      foreach ($remoteLists as $remoteList){
        //print_r();
        $this->constantContactDataService->addContactList(json_encode($remoteList));



      }
      drupal_set_message('Lists have been imported');
      return $this->redirect('constant_contact_block.view_lists');

    }

    /*return array(
      '#type' => 'markup',
      '#theme' => 'constant_contact_block',
      '#attached' => array(
        'library' => array(
          'constant_contact_block/constant_contact_block.import_lists'
        )
      ),
    );*/
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('constant_contact_block.authentication'),
      $container->get('constant_contact_block.data_manager'),
      $container->get('constant_contact_block.manager_service')
    );
  }
}
