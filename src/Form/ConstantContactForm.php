<?php

namespace Drupal\constant_contact_block\Form;

use Drupal\constant_contact_block\items\Contact;
use Drupal\constant_contact_block\items\EmailAddress;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\Messenger;

class ConstantContactForm extends FormBase
{

    /**
     * @var \Drupal\Core\Messenger\Messenger
     */
    protected $messenger;
    private $fields = array();
    private $formId;

    public function __construct($formId, array $fields, Messenger $messenger)
    {
        $this->formId = $formId;
        $this->fields = $fields;
        $this->messenger = $messenger;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'constant_contact_block_form_' . $this->formId;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $parameter = NULL)
    {
        $fields = \Drupal::service('constant_contact_block.fields_manager')->loadFields();
        $selectedFields = $this->fields['fields'];

        foreach ($selectedFields as $selectedField) {
            $form[$selectedField] = array(
                '#type' => $fields[$selectedField]['type'],
                '#title' => $fields[$selectedField]['title'],
                '#required' => $fields[$selectedField]['required']
            );
        }

        $form['email'] = array(
            '#type' => 'email',
            '#title' => t('Email:'),
            '#required' => TRUE,
        );

        $form['email_lists'] = array(
            '#type' => 'checkboxes',
            '#multiple' => TRUE,
            '#title' => t('Lists:'),
            '#options' => $this->fields['lists'],
            '#required' => TRUE,
        );
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Register'),
            '#button_type' => 'primary',
        );
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $email = $form_state->getValue('email');
        $selectedLists = $form_state->getValue('email_lists');
        //@to_do replace with dependency injection
        $constantContactManger = \Drupal::service('constant_contact_block.manager_service');

        $lists = [];
        foreach ($selectedLists as $selectedList) {
            if ($selectedList != 0) {
                $listObj = new \stdClass();
                $listObj->id = $selectedList;
                array_push($lists, $listObj);
            }
        }
        $fields = $this->fields['fields'];
        $submittedFields = array();
        foreach ($fields as $field){
            $submittedFields[$field] = $form_state->getValue($field);
        }

        $contact = new Contact($submittedFields['first_name'], $submittedFields['last_name'], $submittedFields['company_name'],
            'ACTIVE', [new EmailAddress($email)], $lists);

        $checkContact = $constantContactManger->checkContactExistsByEmail($email);
        $message = NULL;
        if (empty($checkContact)) {
            $message = $constantContactManger->addContact($contact);

        } else {
            $message = $constantContactManger->updateContant($checkContact, $lists);
        }
        if (count(json_decode($message))) {
            $this->messenger->addMessage('You have been added to the email lists');
        } else {
            $this->messenger->addMessage('Error adding you to email lists');
        }
    }
}