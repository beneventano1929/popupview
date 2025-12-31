<?php    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('header') &&
            $this->installDefaultConfig();
    public function uninstall()
    {
        return parent::uninstall() &&
            Configuration::deleteByName('POPUPVIEW_MESSAGES') &&
            Configuration::deleteByName('POPUPVIEW_BG_COLOR') &&
            Configuration::deleteByName('POPUPVIEW_TEXT_COLOR') &&
            Configuration::deleteByName('POPUPVIEW_DELAY') &&
            Configuration::deleteByName('POPUPVIEW_REPEAT_INTERVAL');
    }

    private function installDefaultConfig()
    {
        $defaultMessages = Tools::jsonEncode([
            'Solo {X} pezzi rimasti! Ordina ora per non perderli.',
            '{Y} persone stanno guardando questo prodotto ora!',
            'Ultime {X} unità disponibili! Affrettati!',
            'Offerta limitata! {Y} persone lo hanno aggiunto al carrello.',
            'Compra ora! Scorte limitate disponibili.'
        ]);

        return Configuration::updateValue('POPUPVIEW_MESSAGES', $defaultMessages)
            && Configuration::updateValue('POPUPVIEW_BG_COLOR', '#ff0000') // Rosso di default
            && Configuration::updateValue('POPUPVIEW_TEXT_COLOR', '#ffffff') // Bianco di default
            && Configuration::updateValue('POPUPVIEW_DELAY', 2)
            && Configuration::updateValue('POPUPVIEW_REPEAT_INTERVAL', 0);
    }

    public function getContent()
    {
        $output = '';
        $errors = [];

        if (Tools::isSubmit('submit_popupview')) {
            $messages = [];
            for ($i = 1; $i <= 5; $i++) {
                $message = trim((string) Tools::getValue('POPUPVIEW_MESSAGE_' . $i));
                if ($message !== '') {
                    if (!Validate::isCleanHtml($message)) {
                        $errors[] = $this->l('Inserisci messaggi validi (HTML non consentito).');
                        break;
                    }
                    $messages[] = $message;
                }
            }

            if (empty($errors)) {
                if (empty($messages)) {
                    $errors[] = $this->l('Inserisci almeno un messaggio per il popup.');
                } else {
                    $delay = max(0, (int) Tools::getValue('POPUPVIEW_DELAY'));
                    $repeatInterval = max(0, (int) Tools::getValue('POPUPVIEW_REPEAT_INTERVAL'));
                    $bgColor = Tools::getValue('POPUPVIEW_BG_COLOR');
                    $textColor = Tools::getValue('POPUPVIEW_TEXT_COLOR');

                    if (!Validate::isColor($bgColor) || !Validate::isColor($textColor)) {
                        $errors[] = $this->l('Inserisci colori validi in formato hex.');
                    }

                    if (empty($errors)) {
                        Configuration::updateValue('POPUPVIEW_MESSAGES', Tools::jsonEncode($messages));
                        Configuration::updateValue('POPUPVIEW_BG_COLOR', $bgColor);
                        Configuration::updateValue('POPUPVIEW_TEXT_COLOR', $textColor);
                        Configuration::updateValue('POPUPVIEW_DELAY', $delay);
                        Configuration::updateValue('POPUPVIEW_REPEAT_INTERVAL', $repeatInterval);

                        $output .= $this->displayConfirmation($this->l('Le impostazioni sono state aggiornate con successo.'));
                    }
                }
            }
        }

        $messages = json_decode(Configuration::get('POPUPVIEW_MESSAGES'), true) ?: [];
        $messages = array_values($messages);
        $messages = array_pad($messages, 5, '');
        $bgColor = Configuration::get('POPUPVIEW_BG_COLOR');
        $textColor = Configuration::get('POPUPVIEW_TEXT_COLOR');
        $delay = (int) Configuration::get('POPUPVIEW_DELAY');
        $repeatInterval = (int) Configuration::get('POPUPVIEW_REPEAT_INTERVAL');

        $this->context->smarty->assign([
            'form_action' => $_SERVER['REQUEST_URI'],
            'messages' => $messages,
            'bg_color' => $bgColor,
            'text_color' => $textColor,
            'delay' => $delay,
            'repeat_interval' => $repeatInterval,
            'errors' => $errors
        ]);

        return $output . $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    public function hookDisplayFooter($params)
    {
        $messages = json_decode(Configuration::get('POPUPVIEW_MESSAGES'), true) ?: [];
        $messages = array_values(array_filter($messages, static function ($message) {
            return trim((string) $message) !== '';
        }));

        if (empty($messages)) {
            return '';
        }

        $bgColor = Configuration::get('POPUPVIEW_BG_COLOR');
        $textColor = Configuration::get('POPUPVIEW_TEXT_COLOR');

        if (!Validate::isColor($bgColor)) {
            $bgColor = '#ff0000';
        }

        if (!Validate::isColor($textColor)) {
            $textColor = '#ffffff';
        }

        $delay = max(0, (int) Configuration::get('POPUPVIEW_DELAY'));
        $repeatInterval = max(0, (int) Configuration::get('POPUPVIEW_REPEAT_INTERVAL'));

        $this->context->smarty->assign([
            'popup_messages_json' => Tools::jsonEncode($messages),
            'popup_bg_color' => $bgColor,
            'popup_text_color' => $textColor,
            'popup_config_json' => Tools::jsonEncode([
                'delay' => $delay,
                'repeatInterval' => $repeatInterval,
            ]),
        ]);

        return $this->display(__FILE__, 'views/templates/hook/popup.tpl');
    }
            Configuration::deleteByName('POPUPVIEW_TEXT_COLOR');
    }

    private function installDefaultConfig()
    {
        $defaultMessages = json_encode([
            'Solo {X} pezzi rimasti! Ordina ora per non perderli.',
            '{Y} persone stanno guardando questo prodotto ora!',
            'Ultime {X} unità disponibili! Affrettati!',
            'Offerta limitata! {Y} persone lo hanno aggiunto al carrello.',
            'Compra ora! Scorte limitate disponibili.'
        ]);

        Configuration::updateValue('POPUPVIEW_MESSAGES', $defaultMessages);
        Configuration::updateValue('POPUPVIEW_BG_COLOR', '#ff0000'); // Rosso di default
        Configuration::updateValue('POPUPVIEW_TEXT_COLOR', '#ffffff'); // Bianco di default
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submit_popupview')) {
            $messages = [
                Tools::getValue('POPUPVIEW_MESSAGE_1'),
                Tools::getValue('POPUPVIEW_MESSAGE_2'),
                Tools::getValue('POPUPVIEW_MESSAGE_3'),
                Tools::getValue('POPUPVIEW_MESSAGE_4'),
                Tools::getValue('POPUPVIEW_MESSAGE_5')
            ];

            Configuration::updateValue('POPUPVIEW_MESSAGES', json_encode($messages));
            Configuration::updateValue('POPUPVIEW_BG_COLOR', Tools::getValue('POPUPVIEW_BG_COLOR'));
            Configuration::updateValue('POPUPVIEW_TEXT_COLOR', Tools::getValue('POPUPVIEW_TEXT_COLOR'));

            $output .= $this->displayConfirmation($this->l('Le impostazioni sono state aggiornate con successo.'));
        }

        $messages = json_decode(Configuration::get('POPUPVIEW_MESSAGES'), true);
        $bgColor = Configuration::get('POPUPVIEW_BG_COLOR');
        $textColor = Configuration::get('POPUPVIEW_TEXT_COLOR');

        $this->context->smarty->assign([
            'form_action' => $_SERVER['REQUEST_URI'],
            'messages' => $messages,
            'bg_color' => $bgColor,
            'text_color' => $textColor
        ]);

        return $output . $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    public function hookDisplayFooter($params)
    {
        $messages = Configuration::get('POPUPVIEW_MESSAGES');
        $bgColor = Configuration::get('POPUPVIEW_BG_COLOR');
        $textColor = Configuration::get('POPUPVIEW_TEXT_COLOR');

        $this->context->smarty->assign([
            'popup_messages' => $messages,
            'popup_bg_color' => $bgColor,
            'popup_text_color' => $textColor
        ]);

        return $this->display(__FILE__, 'views/templates/hook/popup.tpl');
    }

    public function hookHeader()
    {
        $this->context->controller->registerStylesheet(
            'popupview-css',
            $this->_path . 'assets/css/popupview.css'
        );

        $this->context->controller->registerJavascript(
            'popupview-js',
            $this->_path . 'assets/js/popupview.js',
            ['position' => 'bottom', 'priority' => 150]
        );
    }
}
