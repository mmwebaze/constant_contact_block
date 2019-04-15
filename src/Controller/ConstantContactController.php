<?php

namespace Drupal\constant_contact_block\Controller;

use Drupal\constant_contact_block\services\AuthenticationServiceInterface;
use Drupal\constant_contact_block\services\ConstantContactDataInterface;
use Drupal\constant_contact_block\services\ConstantContactInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Messenger\Messenger;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Config\ConfigFactory;

/**
 * Class ConstantContactController.
 */
class ConstantContactController extends ControllerBase {

  protected $authenticationService;
  protected $constantContactDataService;
  protected $constantContactService;
  protected $configFactory;
  protected $messenger;

  /**
   * ConstantContactController constructor.
   *
   * @param \Drupal\constant_contact_block\services\AuthenticationServiceInterface $authenticationService
   *   The authentication service.
   * @param \Drupal\constant_contact_block\services\ConstantContactDataInterface $constantContactDataService
   *   The constant contact data service.
   * @param \Drupal\constant_contact_block\services\ConstantContactInterface $constantContactService
   *   The constant contact service.
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   The configuration service.
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *   The messenger service.
   */
  public function __construct(AuthenticationServiceInterface $authenticationService,
                              ConstantContactDataInterface $constantContactDataService,
                              ConstantContactInterface $constantContactService,
  ConfigFactory $configFactory,
  Messenger $messenger) {
    $this->authenticationService = $authenticationService;
    $this->constantContactDataService = $constantContactDataService;
    $this->constantContactService = $constantContactService;
    $this->configFactory = $configFactory->getEditable('constant_contact_block.constantcontantconfig');
    $this->messenger = $messenger;
  }

  /**
   * Gets application authorization link.
   *
   * @return array
   *
   *   A render array.
   */
  public function getAuthorization() {
    $url = $this->authenticationService->getAuthorizationUrl();

    if (!$url) {
      return $this->redirect('constant_contact_block.constant_contant_config_form');
    }

    return [
      '#type' => 'markup',
      '#markup' => '<a href=' . $url . ' target="_blank">Authorize App</a>',
    ];
  }

  /**
   * Get code.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   *   The response object.
   */
  public function getCode(Request $request) {
    $code = $request->query->get('code');
    $session = $request->getSession();

    $response = $this->authenticationService->getAccessToken($code);
    $accessTokenResponse = json_decode($response);
    $session->set('access_token', $accessTokenResponse->access_token);
    $this->configFactory->set('auth_token', $accessTokenResponse->access_token)->save();

    return $this->redirect('constant_contact_block.main_menu');
  }

  /**
   * Displays constant contact lists.
   *
   * @return array
   *   The render array.
   */
  public function getContactLists() {

    $lists = $this->constantContactDataService->getContactLists();

    $rows = [];
    foreach ($lists as $list) {
      $rows[$list->id] = [
        'id' => $list->id, 'name' => $list->name, 'list_id' => $list->list_id,
        'modified_date' => $list->modified_date, 'status' => $list->status,
        'contact_count' => $list->contact_count, 'created_date' => $list->created_date,
        Link::fromTextAndUrl('Delete', Url::fromUserInput('/admin/constant_contact_block/list_delete/' . $list->id . '/' . $list->name)),
      ];
    }

    $build = [
      '#prefix' => '<div class="cc_block_lists">',
      '#suffix' => '</div>',
      'table' => [
        '#theme' => 'table',
        '#header' => [
          'id' => $this->t('id'),
          'name' => $this->t('name'),
          'list_id' => $this->t('list id'),
          'modified_date' => $this->t('modified date'),
          'status' => $this->t('status'),
          'contact_count' => $this->t('contact count'),
          'created_date' => $this->t('created date'),
          'operations' => $this->t('operations'),
        ],
        '#rows' => $rows,
        '#empty' => $this->t('No contact lists found locally.'),
      ],
      '#attached' => [
        'library' => [
          'constant_contact_block/list_view',
        ],
      ],
    ];

    $build['pager'] = [
      '#type' => 'pager',
    ];
    return $build;
  }

  /**
   * Displays imported lists.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   *   The response object.
   */
  public function importContactLists(Request $request) {

    $importStatus = $request->attributes->get('importStatus');
    $form = $this->formBuilder()->getForm('Drupal\constant_contact_block\Form\ConstantContactImportListForm');

    if ($importStatus == 0) {
      $this->messenger->addMessage('Import Constant Contact lists locally. Any list already imported will be deleted first.', 'warning');
    }
    else {
      $remoteLists = $this->constantContactService->getContactLists();
      $remoteLists = json_decode($remoteLists);
      $this->constantContactDataService->deleteTable('constant_contact_lists');

      foreach ($remoteLists as $remoteList) {
        $this->constantContactDataService->addContactList($remoteList);
      }
      $this->messenger->addMessage('Lists have been imported');
      return $this->redirect('constant_contact_block.view_lists');

    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('constant_contact_block.authentication'),
      $container->get('constant_contact_block.data_manager'),
      $container->get('constant_contact_block.manager_service'),
      $container->get('config.factory'),
      $container->get('messenger')
    );
  }

}
