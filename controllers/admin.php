<?php

/**
 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is
 * licensed under The BSD license.

 * ---
 * Copyright (c) 2011, Oxwall Foundation
 * All rights reserved.

 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 * following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice, this list of conditions and
 *  the following disclaimer.
 *
 *  - Redistributions in binary form must reproduce the above copyright notice, this list of conditions and
 *  the following disclaimer in the documentation and/or other materials provided with the distribution.
 *
 *  - Neither the name of the Oxwall Foundation nor the names of its contributors may be used to endorse or promote products
 *  derived from this software without specific prior written permission.

 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * @author Sardar Madumarov <madumarov@gmail.com>
 * @package ow_plugins.google_analytics
 * @since 1.0
 */
class GANALYTICS_CTRL_Admin extends ADMIN_CTRL_Abstract
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->setPageHeading(OW::getLanguage()->text('ganalytics', 'admin_index_heading'));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');

        $form = new Form('google_analytics_code');

        $element = new Textarea('google_analytics_code');
        $form->addElement($element);

        $submit = new Submit('submit');
        $submit->setValue(OW::getLanguage()->text('admin', 'save_btn_label'));
        $form->addElement($submit);

        if ( OW::getRequest()->isPost() && $form->isValid($_POST) )
        {
            $data = $form->getValues();
            if ( !empty($data['google_analytics_code']) && strlen(trim($data['google_analytics_code'])) > 0 )
            {
                $googleAnalyticsCode = htmlentities(trim($data['google_analytics_code']));

                if( !get_magic_quotes_gpc() )
                {
                    $googleAnalyticsCode = addslashes($googleAnalyticsCode);
                }

                OW::getConfig()->saveConfig('ganalytics', 'google_analytics_code', $googleAnalyticsCode);
                OW::getFeedback()->info(OW::getLanguage()->text('ganalytics', 'admin_index_google_analytics_code_save_success_message'));
            }
            else
            {
                OW::getFeedback()->error(OW::getLanguage()->text('ganalytics', 'admin_index_google_analytics_code_save_error_message'));
            }

            $this->redirect();
        }

        $googleAnalyticsCode = stripslashes(html_entity_decode(OW::getConfig()->getValue('ganalytics', 'google_analytics_code')));
        $element->setValue($googleAnalyticsCode);
        $this->addForm($form);
    }
}