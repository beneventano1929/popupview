<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class PopupView extends Module
{
    public function __construct()
    {
        $this->name = 'popupview';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0.0';
        $this->author = 'Emilio Grimaldi';
        $this->need_instance = 0;

        $this->bootstrap = true; // Abilita il supporto Bootstrap per il backend del modulo

        parent::__construct();

        $this->displayName = $this->l('Popup Dinamico per Prodotti');
        $this->description = $this->l('Attira l\'attenzione dei tuoi clienti mostrando popup dinamici con messaggi personalizzati sui prodotti in vendita.');
        $this->ps_versions_compliancy = ['min' => '1.7.0', 'max' => _PS_VERSION_];
        $this->confirmUninstall = $this->l('Sei sicuro di voler disinstallare il modulo? Tutte le impostazioni saranno perse.');
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('header') &&
            $this->installDefaultConfig();
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            Configuration::deleteByName('POPUPVIEW_MESSAGES') &&
            Configuration::deleteByName('POPUPVIEW_BG_COLOR') &&
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
