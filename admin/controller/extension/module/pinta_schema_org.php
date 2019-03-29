<?php

class ControllerExtensionModulePintaSchemaOrg extends Controller
{
    private $error = array();
    private $route = 'extension/module/pinta_schema_org';

    public function index()
    {
        $this->load->language('extension/module/pinta_schema_org');
        $this->load->model('setting/setting');


        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/admin_simple.js');
        $this->document->addStyle('view/stylesheet/admin_simple.css');

        $setting = $this->model_setting_setting->getSetting('pinta_schema_org');


        foreach ($setting as $key => $item) {
            $data[$key] = $item;
        }


        $this->load->model('extension/module/pinta_schema_org');


        $url = '';
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/pinta_schema_org', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['save'] = $this->url->link('extension/module/pinta_schema_org/save', 'user_token=' . $this->session->data['user_token'] . $url, true);


        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['column_enable'] = $this->language->get('column_enable');
        $data['colum_select'] = $this->language->get('colum_select');

        $data['colum_select_product'] = $this->language->get('colum_select_product');
        $data['colum_select'] = $this->language->get('colum_select');
        $data['colum_select'] = $this->language->get('colum_select');
        $data['colum_select'] = $this->language->get('colum_select');
        $data['colum_select'] = $this->language->get('colum_select');
        $data['colum_select'] = $this->language->get('colum_select');
        $data['colum_select'] = $this->language->get('colum_select');
        $data['colum_select'] = $this->language->get('colum_select');


        $data['user_token'] = $this->session->data['user_token'];


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }


        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }


        $pagination = new Pagination();

        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/module/pinta_schema_org', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        $data['link'] = '';

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/pinta_schema_org', $data));
    }

    public function save()
    {
        $url = '';
        $this->load->language('extension/module/simple');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/simple');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validationForm())) {


            $setting = $this->getInput($this->request->post);

            $this->model_extension_module_simple->setSetting($setting);

            $this->response->redirect($this->url->link('extension/module/simple', 'user_token=' . $this->session->data['user_token'] . $url, true));

        }

        $this->index();

    }

    protected function validationForm()
    {

        if (!$this->user->hasPermission('modify', 'extension/module/simple')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ($this->request->post['setting_min_symbol'] == 0) {
            $this->error['setting_min_symbol'] = $this->language->get('error_product');
        }

        if (($this->request->post['appearance_image_width'] < 25) || ($this->request->post['appearance_image_height'] < 25)) {
            $this->error['appearance_image_width'] = $this->language->get('error_size_image');
        }

        if (!preg_match('/^([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/i', $this->request->post['appearance_background'])) {

            $this->error['appearance_background'] = $this->language->get('error_hex');
        }
        if (!preg_match('/^([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/i', $this->request->post['appearance_background_hover'])) {

            $this->error['appearance_background_hover'] = $this->language->get('error_hex');
        }
        if (!preg_match('/^([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/i', $this->request->post['appearance_background_hover_gradient_to'])) {

            $this->error['appearance_background_hover_gradient_to'] = $this->language->get('error_hex');
        }
        if (!preg_match('/^([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/i', $this->request->post['appearance_background_hover_gradient_end'])) {

            $this->error['appearance_background_hover_gradient_end'] = $this->language->get('error_hex');
        }

        if ($this->request->post['desing_max_numbers'] == 0) {
            $this->error['desing_max_numbers'] = $this->language->get('error_desing_max_numbers');
        }

        return !$this->error;

    }


    public function install()
    {
        $this->load->model('setting/setting');

        $settings['pinta_schema_org_status'] = 1;
        $settings['pinta_schema_org_select_product'] = 1;

        $this->model_setting_setting->editSetting('pinta_schema_org', $settings);


    }

    public function uninstall()
    {
        $this->load->model('setting/setting');

        $this->model_setting_setting->deleteSetting('pinta_schema_org');
    }


    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/pinta_schema_org')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function configure()
    {
        $this->load->language('extension/extension/module');

        if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true));
        } else {
            $this->load->model('setting/extension');
            $this->load->model('user/user_group');

            $this->model_setting_extension->install('module', 'pinta_schema_org');

            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/pinta_schema_org');
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/pinta_schema_org');

            $this->install();

            $this->response->redirect($this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], true));
        }

    }


}

