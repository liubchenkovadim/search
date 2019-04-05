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
         $this->document->addScript('view/javascript/admin_schema.js');
        $this->document->addStyle('view/stylesheet/admin_schema.css');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') &&($this->validationForm())) {

            $this->model_setting_setting->editSettingValue('config','config_telephone', $this->request->post['pinta_schema_org_select_organization_telefon'][0]);

            $this->model_setting_setting->editSetting('pinta_schema_org', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module/pinta_schema_org', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }


        $setting = $this->model_setting_setting->getSetting('pinta_schema_org');


        foreach ($setting as $key => $item) {
            $data[$key] = $item;
        }



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

        $data['save'] = $this->url->link('extension/module/pinta_schema_org', 'user_token=' . $this->session->data['user_token'] . $url, true);


        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['column_enable'] = $this->language->get('column_enable');
        $data['colum_select'] = $this->language->get('colum_select');
        $data['button_save'] = $this->language->get('button_save');

        $data['colum_select_place'] = $this->language->get('colum_select_place');
        $data['colum_select_place_name'] = $this->language->get('colum_select_place_name');
        $data['colum_select_place_city'] = $this->language->get('colum_select_place_city');
        $data['colum_select_place_region'] = $this->language->get('colum_select_place_region');
        $data['colum_select_place_adress'] = $this->language->get('colum_select_place_adress');
        $data['colum_select_place_index'] = $this->language->get('colum_select_place_index');
        $data['colum_select_time'] = $this->language->get('colum_select_time');
        $data['colum_select_place_time_day'] = $this->language->get('colum_select_place_time_day');
        $data['colum_select_place_time_time'] = $this->language->get('colum_select_place_time_time');
        $data['placeholder_day'] = $this->language->get('placeholder_days');
        $data['placeholder_time'] = $this->language->get('placeholder_time');
        $data['erorr_fild'] = $this->language->get('erorr_fild');






        $data['colum_select_product'] = $this->language->get('colum_select_product');
        $data['colum_select_breadcrumb_list'] = $this->language->get('colum_select_breadcrumb_list');
        $data['colum_select_organization'] = $this->language->get('colum_select_organization');
        $data['colum_select_website'] = $this->language->get('colum_select_website');
        $data['addres_site'] = $this->language->get('addres_site');
        $data['telefon'] = $this->language->get('telefon');
        $data['on'] = $this->language->get('on');

        $data['off'] = $this->language->get('off');




        $data['user_token'] = $this->session->data['user_token'];


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        if (isset($this->error['erorr_pinta_schema_org_select_organization_telefon'])) {
            foreach ($this->error['erorr_pinta_schema_org_select_organization_telefon'] as $key => $item) {
                if (isset($item)) {
                    $data['error_telefon'][$key] = $item;
                } else {
                    $data['error_telefon'] [$key] = '';
                }

            }
        }


        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
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



    protected function validationForm()
    {

        if (!$this->user->hasPermission('modify', 'extension/module/pinta_schema_org')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

     /*   if ($this->request->post['setting_min_symbol'] == 0) {
            $this->error['setting_min_symbol'] = $this->language->get('error_product');
        }

        if (($this->request->post['appearance_image_width'] < 25) || ($this->request->post['appearance_image_height'] < 25)) {
            $this->error['appearance_image_width'] = $this->language->get('error_size_image');
        }
*/
    foreach ($this->request->post['pinta_schema_org_select_organization_telefon'] as $key => $item)
        if (!preg_match('/^\+\d{3}\d{3}\d{2}\d{2}\d{2}$/', $item)) {

            $this->error['erorr_pinta_schema_org_select_organization_telefon'][$key] = $this->language->get('error_phone_number');
        }




        return !$this->error;

    }


    public function install()
    {
        $this->load->model('setting/setting');
        $config = $this->model_setting_setting->getSetting('config');

        $settings['pinta_schema_org_status'] = 1;
        $settings['pinta_schema_org_select_product'] = 1;
        $settings['pinta_schema_org_select_breadcrumb_list'] = 1;
        $settings['pinta_schema_org_select_organization'] = 1;
        $settings['pinta_schema_org_select_website'] = 1;
        $settings['pinta_schema_org_select_place'] = 0;
        $settings['pinta_schema_org_select_times'] = 0;





        $settings['pinta_schema_org_select_organization_telefon'] = array(
            0 =>$config['config_telephone'],);
        $settings['pinta_schema_org_select_organization_url'] = array(
            0 =>HTTPS_CATALOG,
        );



        $this->model_setting_setting->editSetting('pinta_schema_org', $settings);


    }

    public function uninstall()
    {
        $this->load->model('setting/setting');

        $this->model_setting_setting->deleteSetting('pinta_schema_org');
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

