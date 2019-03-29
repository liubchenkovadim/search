<?php

class ControllerExtensionModuleSimple extends Controller
{
    private $error = array();
    private $route = 'extension/module/simple';

    public function index()
    {
        $this->load->language('extension/module/simple');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/admin_simple.js');
        $this->document->addStyle('view/stylesheet/admin_simple.css');



        $this->load->model('extension/module/simple');
        $this->install();


        $url = '';
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/simple', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['save'] = $this->url->link('extension/module/simple/save', 'user_token=' . $this->session->data['user_token'] . $url, true);




        $result = $this->model_extension_module_simple->getSetting();

        if (!empty($result)) {


            foreach ($result as $inputs => $item) {

                if (mb_strlen($item) > 2) {
                    $unser = unserialize($item);
                    $data[$inputs] = $unser;
                } else {
                    $data[$inputs] = $item;

                }
            }


        }
        $data['product_inputs'] = array(
            'product_name' => array('name' => $this->language->get('column_name'),
                'id' => 'name',
            ),

            'product_ean' => array('name' => $this->language->get('column_ean'),
                'id' => 'ean',
            ),
            'product_upc' => array('name' => $this->language->get('column_upc'),
                'id' => 'upc',
            ),
            'product_option' => array('name' => 'Option',
                'id' => 'option',
            ),
            'product_meta_description' => array('name' => $this->language->get('column_meta_description'),
                'id' => 'meta_description',
            ),
            'product_description' => array('name' => $this->language->get('column_description'),
                'id' => 'description',
            ),
            'product_model' => array('name' => $this->language->get('column_model'),
                'id' => 'model',
            ),
            'product_isbn' => array('name' => $this->language->get('column_isbn'),
                'id' => 'isbn',
            ),
            'product_mpn' => array('name' => $this->language->get('column_mpn'),
                'id' => 'mpn',
            ),
            'product_tag' => array('name' => $this->language->get('column_tag'),
                'id' => 'tag',
            ),
            'product_meta_keyword' => array('name' => $this->language->get('column_meta_keyword'),
                'id' => 'meta_keyword',
            ),

            'product_sku' => array('name' => $this->language->get('column_sku'),
                'id' => 'sku',
            ),
            'product_jan' => array('name' => $this->language->get('column_jan'),
                'id' => 'jan',
            ),
            'product_attribute' => array('name' => $this->language->get('column_attribute'),
                'id' => 'attribute',
            ),
            'product_meta_title' => array('name' => $this->language->get('column_meta_title'),
                'id' => 'meta_title',
            ),

        );

        if (!empty($data['product_input'])) {
            foreach ($data['product_inputs'] as $key => $i) {
                if (!empty($data['product_input'][$key])) {
                    $data['product_inputs'][$key] += ['value' => $data['product_input'][$key]];


                }
            }
        }
        $data['suggestions'] = $this->getSuggestion();

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['column_setting'] = $this->language->get('column_setting');
        $data['column_custom'] = $this->language->get('column_custom');
        $data['column_design'] = $this->language->get('column_design');
        $data['column_history'] = $this->language->get('column_history');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_min_symbol'] = $this->language->get('column_min_symbol');
        $data['column_suggestions'] = $this->language->get('column_suggestions');
        $data['column_meta_title'] = $this->language->get('column_meta_title');
        $data['column_information'] = $this->language->get('column_information');
        $data['column_title'] = $this->language->get('column_title');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_meta_keyword'] = $this->language->get('column_meta_keyword');
        $data['column_manufacturer'] = $this->language->get('column_manufacturer');
        $data['column_product'] = $this->language->get('column_product');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_link'] = $this->language->get('column_link');
        $data['column_max'] = $this->language->get('column_max');
        $data['column_image'] = $this->language->get('column_image');
        $data['column_input'] = $this->language->get('column_input');
        $data['column_meta_keyword'] = $this->language->get('column_meta_keyword');
        $data['column_manufacturer'] = $this->language->get('column_manufacturer');
        $data['column_product'] = $this->language->get('column_product');

        $data['column_save_value'] = $this->language->get('column_save_value');
        $data['column_information'] = $this->language->get('column_information');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_title'] = $this->language->get('column_title');
        $data['column_input'] = $this->language->get('column_input');
        $data['column_meta_keyword'] = $this->language->get('column_meta_keyword');
        $data['column_manufacturer'] = $this->language->get('column_manufacturer');
        $data['column_product'] = $this->language->get('column_product');
        $data['colum_image_size'] = $this->language->get('colum_image_size');
        $data['colum_image_width'] = $this->language->get('colum_image_width');
        $data['colum_image_height'] = $this->language->get('colum_image_height');
        $data['colum_li_hover'] = $this->language->get('colum_li_hover');
        $data['colum_format_hex'] = $this->language->get('colum_format_hex');
        $data['colum_hover_background'] = $this->language->get('colum_hover_background');
        $data['colum_type_li'] = $this->language->get('colum_type_li');
        $data['colum_title_cropping'] = $this->language->get('colum_title_cropping');
        $data['colum_number_cropping'] = $this->language->get('colum_number_cropping');
        $data['text_format_hex'] = $this->language->get('text_format_hex');
        $data['text_format_gradient'] = $this->language->get('text_format_gradient');



        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['error_notext'] = $this->language->get('error_notext');
        $data['type_product'] = $this->language->get('type_product');
        $data['entry_state'] = $this->language->get('entry_state');
        $data['save_sitemap'] = $this->language->get('save_sitemap');
        $data['type_category'] = $this->language->get('type_category');
        $data['entry_binding'] = $this->language->get('entry_binding');

        $data['save_button'] = $this->language->get('save_button');

        $data['user_token'] = $this->session->data['user_token'];


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['setting_min_symbol'])) {
            $data['error_setting_min_symbol'] = $this->error['setting_min_symbol'];
        } else {
            $data['error_setting_min_symbol'] = '';
        }

        if (isset($this->error['appearance_image_width'])) {
            $data['error_appearance_image_width'] = $this->error['appearance_image_width'];
        } else {
            $data['error_appearance_image_width'] = '';
        }

        if (isset($this->error['appearance_background'])) {
            $data['error_appearance_background'] = $this->error['appearance_background'];
        } else {
            $data['error_appearance_background'] = '';
        }

        if (isset($this->error['appearance_background_hover'])) {
            $data['error_appearance_background_hover'] = $this->error['appearance_background_hover'];
        } else {
            $data['error_appearance_background_hover'] = '';
        }

        if (isset($this->error['desing_max_numbers'])) {
            $data['error_desing_max_numbers'] = $this->error['desing_max_numbers'];
        } else {
            $data['error_desing_max_numbers'] = '';
        }
        if (isset($this->error['appearance_background_hover_gradient_to'])) {
            $data['error_appearance_background_hover_gradient_to'] = $this->error['appearance_background_hover_gradient_to'];
        } else {
            $data['error_appearance_background_hover_gradient_to'] = '';
        }
        if (isset($this->error['appearance_background_hover_gradient_end'])) {
            $data['error_appearance_background_hover_gradient_end'] = $this->error['appearance_background_hover_gradient_end'];
        } else {
            $data['error_appearance_background_hover_gradient_end'] = '';
        }
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }


        $pagination = new Pagination();

        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/module/simple', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        $data['link'] = '';

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/simple', $data));
    }

    public function save()
    {
        $url = '';
        $this->load->language('extension/module/simple');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/simple');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')&&($this->validationForm()) ){


            $setting = $this->getInput($this->request->post);

            $this->model_extension_module_simple->setSetting($setting);

            $this->response->redirect($this->url->link('extension/module/simple', 'user_token=' . $this->session->data['user_token'] . $url, true));

        }

        $this->index();

    }
    protected  function validationForm()
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

        if(!preg_match('/^([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/i', $this->request->post['appearance_background'])) {

            $this->error['appearance_background'] = $this->language->get('error_hex');
        }
        if(!preg_match('/^([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/i', $this->request->post['appearance_background_hover'])) {

            $this->error['appearance_background_hover'] = $this->language->get('error_hex');
        }
        if(!preg_match('/^([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/i', $this->request->post['appearance_background_hover_gradient_to'])) {

            $this->error['appearance_background_hover_gradient_to'] = $this->language->get('error_hex');
        }
        if(!preg_match('/^([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/i', $this->request->post['appearance_background_hover_gradient_end'])) {

            $this->error['appearance_background_hover_gradient_end'] = $this->language->get('error_hex');
        }

        if ($this->request->post['desing_max_numbers'] == 0)  {
            $this->error['desing_max_numbers'] = $this->language->get('error_desing_max_numbers');
        }

        return !$this->error;

    }
    protected function getInput($data)
    {

        $result = array();

        foreach ($data as $key => $val) {
            if (preg_match('#smart_name_.*#', $key)) {
                if (!empty($val)) {
                    $key = str_replace('smart_name_', '', $key);
                    $suggestion[$key] = $val;
                }
            }

            if ((preg_match('#setting_.*#', $key))&&($key != 'setting_id')) {
                $result['setting_input'][$key] = $val;

            }
            elseif (preg_match('#category_.*#', $key)) {
                $result['category_input'][$key] = $val;

            }
            elseif (preg_match('#information_.*#', $key)) {
                $result['information_input'][$key] = $val;

            }
            elseif (preg_match('#manufacturer_.*#', $key)) {

                $result['manufacturer_input'][$key] = $val;


            }
            elseif (preg_match('#product_.*#', $key)) {
                $result['product_input'][$key] = $val;

            }
            elseif (preg_match('#desing_.*#', $key)) {
                $result['desing_input'][$key] = $val;
            }
            elseif (preg_match('#appearance_.*#', $key)) {
                $result['appearance'][$key] = $val;
            }
        }
        if(!empty($suggestion)) {
            $this->setSuggestion($suggestion);

        }
        foreach ($result as $k => $v) {
            $results[$k] = serialize($v);
        }


        return $results;
    }

    protected function getInputDefault()
    {
        $result['setting_input'] = serialize(array(
            'setting_status' => 1,
            'setting_min_symbol' => 1,
            'setting_suggestions' => 1,


        ));
        $result['category_input'] = serialize(array(
            'category_name' => 1,
            'category_meta_title' => 1,
        ));
        $result['information_input'] = serialize(array(
            'information_title' => 1,
            'information_description' => 1,
            'information_meta_title' => 1,
            'information_meta_description' => 1,
            'information_meta_keyword' => 1,
        ));
        $result['manufacturer_input'] = serialize(array(
            'manufacturer_name' => 1
        ));
        $result['product_input'] = serialize(array(
            'product_name' => 1,
            'product_manufactur_id' => 1,
            'product_ean' => 1,
            'product_upc' => 1,
            'product_option' => 1,
            'product_meta_description' => 1,
            'product_description' => 1,
            'product_model' => 1,
            'product_isbn' => 1,
            'product_mpn' => 1,
            'product_tag' => 1,
            'product_meta_keyword' => 1,
            'product_category' => 1,
            'product_sku' => 1,
            'product_jan' => 1,
            'product_attribute' => 1,
            'product_meta_title' => 1,
        ));
        $result['desing_input'] = serialize(array(
            'desing_max_numbers' => 7,
            'desing_links' => 1,
            'desing_price' => 1,
            'desing_image' => 1,
            'desing_title_cropping' => 0,
            'desing_cropping' => 25,
            'desing_type' => 1,
        ));
        $result['appearance'] =  serialize(array(
            'appearance_image_width' => 25,
            'appearance_image_height' => 25,
            'appearance_background' => 'fff',
            'appearance_background_hover_on' => 0,
            'appearance_background_hover' => '229ac8',
            'appearance_background_hover_gradient_on'=> 0,
            'appearance_background_hover_gradient_to' => '229ac8',
            'appearance_background_hover_gradient_end' => '229ac8'

        ));
        return $result;
    }

    public    function install()
    {
        //создать таблицу в бд

        $this->load->model('extension/module/simple');


        $this->model_extension_module_simple->createDatabase($this->getInputDefault());

        $this->validate();


    }

    public    function uninstall()
    {
        //создать таблицу в бд
        $this->load->model('extension/module/simple');
        $this->model_extension_module_simple->dropDatabase();

    }



    protected    function validate()
    {
        if (!$this->user->hasPermission('modify', $this->route)) {
            $this->load->model('user/user_group');
            $user_groups = $this->model_user_user_group->getUserGroups();
            $admin_user_group_id = null;
            foreach ($user_groups as $user_group) {
                if ($user_group['name'] === 'Administrator') {
                    $admin_user_group_id = $user_group['user_group_id'];
                    break;
                }
            }
            if (!is_null($admin_user_group_id)) {
                $this->model_user_user_group->addPermission(
                    $admin_user_group_id,
                    "access",
                    "extension/module/simple");
                $this->model_user_user_group->addPermission(
                    $admin_user_group_id,
                    "modify",
                    "extension/module/simple");
            }
        }

    }

    public function getSuggestion()
    {
        $this->load->model('extension/module/simple');
        $suggestion = $this->model_extension_module_simple->getSuggetion();

        return $suggestion;


    }

    public function setSuggestion($suggestion)
    {
        $this->load->model('extension/module/simple');
        $this->model_extension_module_simple->setSuggetion($suggestion);




    }
}

