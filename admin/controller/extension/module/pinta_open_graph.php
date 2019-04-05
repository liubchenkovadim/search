<?php

class ControllerExtensionModulePintaOpenGraph extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/module/pinta_open_graph');
        $this->load->model('setting/setting');


        $this->document->setTitle($this->language->get('heading_title'));
         $this->document->addScript('view/javascript/admin_open_graph.js');
        $this->document->addStyle('view/stylesheet/admin_open_graph.css');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') &&($this->validationForm())) {


            $this->model_setting_setting->editSetting('pinta_open_graph', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module/pinta_open_graph', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }


        $setting = $this->model_setting_setting->getSetting('pinta_open_graph');


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
            'href' => $this->url->link('extension/module/pinta_open_graph', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['save'] = $this->url->link('extension/module/pinta_open_graph', 'user_token=' . $this->session->data['user_token'] . $url, true);


        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['column_enable'] = $this->language->get('column_enable');
        $data['colum_select'] = $this->language->get('colum_select');
        $data['button_save'] = $this->language->get('button_save');
        $data['status'] = $this->language->get('status');
        $data['on'] = $this->language->get('on');
        $data['off'] = $this->language->get('off');
        $data['colum_upload'] = $this->language->get('colum_upload');
        $data['colum_status'] = $this->language->get('colum_status');
        $data['colum_chose_file'] = $this->language->get('colum_chose_file');
        $data['colum_width'] = $this->language->get('colum_width');
        $data['colum_height'] = $this->language->get('colum_height');
        $data['error_file_load'] = $this->language->get('error_file_load');
        $data['off'] = $this->language->get('off');
        $data['off'] = $this->language->get('off');
        $data['off'] = $this->language->get('off');
        $data['off'] = $this->language->get('off');
        $data['off'] = $this->language->get('off');
        $data['off'] = $this->language->get('off');
        $data['off'] = $this->language->get('off');





        $data['user_token'] = $this->session->data['user_token'];

        $this->load->model('localisation/currency');

        $data['currencies'] = array();

        $results = $this->model_localisation_currency->getCurrencies();

        foreach ($results as $result) {
            if ($result['status']) {
                $data['currencies'][] = array(
                    'title'        => $result['title'],
                    'code'         => $result['code'],
                    'symbol_left'  => $result['symbol_left'],
                    'symbol_right' => $result['symbol_right']
                );
            }
        }

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
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $pagination = new Pagination();

        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/module/pinta_open_graph', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        $data['link'] = '';

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/pinta_open_graph', $data));
    }



    protected function validationForm()
    {

        if (!$this->user->hasPermission('modify', 'extension/module/pinta_open_graph')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        $f_type=$_FILES['pinta_open_graph_image_default']['type'];

        if ($f_type== "image/gif" OR $f_type== "image/png" OR $f_type== "image/jpeg" OR $f_type== "image/JPEG" OR $f_type== "image/PNG" OR $f_type== "image/GIF")
        {
            if($_FILES["pinta_open_graph_image_default"]["size"] > 1024*20*1024)
            {
                $this->error['warning'] = $this->language->get('error_file_size');


            }
            // Проверяем загружен ли файл
           elseif(is_uploaded_file($_FILES["pinta_open_graph_image_default"]["tmp_name"]))
            {
                // Если файл загружен успешно, перемещаем его
                // из временной директории в конечную
                move_uploaded_file($_FILES["pinta_open_graph_image_default"]["tmp_name"], DIR_IMAGE.$_FILES["pinta_open_graph_image_default"]["name"]);
                $this->request->post['pinta_open_graph_image_default'] = HTTPS_CATALOG.'image/'.$_FILES["pinta_open_graph_image_default"]["name"];
            } else {
                $this->error['warning'] = $this->language->get('error_file_load');
                return false;

            }
        }
        else
        {
            $this->error['warning'] = $this->language->get('error_file_type');


        }

     /*   if ($this->request->post['setting_min_symbol'] == 0) {
            $this->error['setting_min_symbol'] = $this->language->get('error_product');
        }

        if (($this->request->post['appearance_image_width'] < 25) || ($this->request->post['appearance_image_height'] < 25)) {
            $this->error['appearance_image_width'] = $this->language->get('error_size_image');
        }
*/
/*    foreach ($this->request->post['pinta_schema_org_select_organization_telefon'] as $key => $item)
        if (!preg_match('/^\+\d{3}\d{3}\d{2}\d{2}\d{2}$/', $item)) {

            $this->error['erorr_pinta_schema_org_select_organization_telefon'][$key] = $this->language->get('error_phone_number');
        }*/




        return !$this->error;

    }


    public function install()
    {
        $this->load->model('setting/setting');

        $settings['pinta_open_graph_status'] = 1;
        $settings['pinta_open_graph_image_default'] = '';
        $settings['pinta_open_graph_width_image'] = 200;
        $settings['pinta_open_graph_height_image'] = 200;





        $this->model_setting_setting->editSetting('pinta_open_graph', $settings);


    }

    public function uninstall()
    {
        $this->load->model('setting/setting');

        $this->model_setting_setting->deleteSetting('pinta_open_graph');
    }




    public function configure()
    {
        $this->load->language('extension/extension/module');

        if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true));
        } else {
            $this->load->model('setting/extension');
            $this->load->model('user/user_group');

            $this->model_setting_extension->install('module', 'pinta_open_graph');

            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/pinta_open_graph');
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/pinta_open_graph');

            $this->install();

            $this->response->redirect($this->url->link('design/layout', 'user_token=' . $this->session->data['user_token'], true));
        }

    }


}

