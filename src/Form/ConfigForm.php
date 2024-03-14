<?php
/*
 * Copyright the Collabora Online contributors.
 *
 * SPDX-License-Identifier: MPL-2.0
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace Drupal\collabora_online\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ConfigForm extends ConfigFormBase {

    const SETTINGS = 'collabora_online.settings';

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'collabora_configform';
    }

    /**
     * {@inheritdoc}
     */
    public function getEditableConfigNames() {
        return [
            static::SETTINGS
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config(static::SETTINGS);

        $form['server'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Collabora Online server URL'),
            '#default_value' => $config->get('collabora')['server'],
            '#required' => TRUE,
        ];

        $form['wopi_base'] = [
            '#type' => 'textfield',
            '#title' => $this->t('WOPI host base URL. Likely https://&lt;drupal_server&gt;/collabora'),
            '#default_value' => $config->get('collabora')['wopi_base'],
            '#required' => TRUE,
        ];

        $form['key_id'] = [
            '#type' => 'textfield',
            '#title' => $this->t('JWT private key ID'),
            '#default_value' => $config->get('collabora')['key_id'],
            '#required' => TRUE,
        ];

        $form['disable_cert_check'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Disable TLS certificate check for COOL.'),
            '#default_value' => $config->get('collabora')['disable_cert_check'],
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $wopi_base = rtrim($form_state->getValue('wopi_base'), '/');

        $this->config(static::SETTINGS)
            ->set('collabora.server', $form_state->getValue('server'))
            ->set('collabora.wopi_base', $wopi_base)
            ->set('collabora.key_id', $form_state->getValue('key_id'))
            ->set('collabora.disable_cert_check', $form_state->getValue('disable_cert_check'))
            ->save();

        parent::submitForm($form, $form_state);
  }
}
