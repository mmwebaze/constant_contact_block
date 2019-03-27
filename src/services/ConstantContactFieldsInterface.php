<?php

namespace Drupal\constant_contact_block\services;


interface ConstantContactFieldsInterface
{
    /**
     * Loads constant contact fields from a yaml file.
     *
     * @return array $fields
     * Returns an array of constant contact fields
     */
    public function loadFields();
}