<?php

class ControllerExtensionModuleSimple extends Controller
{
    private $route = 'extension/module/simple';

    public function index()
    {
        $this->load->language('extension/module/simple');

        $this->document->setTitle($this->language->get('heading_title'));

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
            'product_name' => array('name' => 'Name',
                'id' => 'name',
            ),
            'product_manufacturer' => array('name' => 'Manufacturer',
                'id' => 'manufacturer',
            ),
            'product_ean' => array('name' => 'EAN',
                'id' => 'EAN',
            ),
            'product_upc' => array('name' => 'UPC',
                'id' => 'upc',
            ),
            'product_option' => array('name' => 'Option',
                'id' => 'option',
            ),
            'product_meta_description' => array('name' => 'Meta description',
                'id' => 'meta_description',
            ),
            'product_description' => array('name' => 'Description',
                'id' => 'description',
            ),
            'product_model' => array('name' => 'Model',
                'id' => 'model',
            ),
            'product_isbn' => array('name' => 'ISBN',
                'id' => 'isbn',
            ),
            'product_mpn' => array('name' => 'MPN',
                'id' => 'mpn',
            ),
            'product_tag' => array('name' => 'TAG',
                'id' => 'tag',
            ),
            'product_meta_keyword' => array('name' => 'Meta Keyword',
                'id' => 'meta_keyword',
            ),
            'product_category' => array('name' => 'Category',
                'id' => 'category',
            ),
            'product_sku' => array('name' => 'Sku',
                'id' => 'sku',
            ),
            'product_jan' => array('name' => 'JAN',
                'id' => 'jan',
            ),
            'product_attribute' => array('name' => 'Attribute',
                'id' => 'attribute',
            ),
            'product_meta_title' => array('name' => 'Meta Title',
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


        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['column_product'] = $this->language->get('column_product');
        $data['column_author'] = $this->language->get('column_author');
        $data['column_rating'] = $this->language->get('column_rating');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_chench'] = $this->language->get('column_chench');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_after'] = $this->language->get('entry_after');
        $data['entry_before'] = $this->language->get('entry_before');
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

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {


            $setting = $this->getInput($this->request->post);

            $this->model_extension_module_simple->setSetting($setting);
        }

        $this->response->redirect($this->url->link('extension/module/simple', 'user_token=' . $this->session->data['user_token'] . $url, true));

    }

    protected function getInput($data)
    {

        $result = array();

        foreach ($data as $key => $val) {

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
        }
        foreach ($result as $k => $v) {
            $results[$k] = serialize($v);
        }
        var_dump($results);

        return $results;
    }

    protected function getInputDefault()
    {
        $result['setting_input'] = serialize(array(
            'setting_status' => 1,
            'setting_min_symbol' => 2,
            'setting_suggestions' => 1,
        ));
        $result['category_input'] = serialize(array(
            'category_name' => 0,
            'category_meta_title' => 0,
        ));
        $result['information_input'] = serialize(array(
            'information_title' => 0,
            'information_description' => 0,
            'information_meta_title' => 0,
            'information_meta_description' => 0,
            'information_meta_keyword' => 0,
        ));
        $result['manufacturer_input'] = serialize(array(
            'manufacturer_name' => 0
        ));
        $result['product_input'] = serialize(array(
            'product_name' => 0,
            'product_manufacturer' => 0,
            'product_ean' => 0,
            'product_upc' => 0,
            'product_option' => 0,
            'product_meta_description' => 0,
            'product_description' => 0,
            'product_model' => 0,
            'product_isbn' => 0,
            'product_mpn' => 0,
            'product_tag' => 0,
            'product_meta_keyword' => 0,
            'product_category' => 0,
            'product_sku' => 0,
            'product_jan' => 0,
            'product_attribute' => 0,
            'product_meta_title' => 0,
        ));
        $result['desing_input'] = serialize(array(
            'desing_max_numbers' => 7,
            'desing_links' => 0,
            'desing_price' => 0,
            'desing_image' => 0,
        ));
        return $result;
    }

    public
    function install()
    {
        //создать таблицу в бд

        $this->model_extension_module_simple->createDatabase($this->getInputDefault());

        $this->validate();


    }

    public
    function uninstall()
    {
        //создать таблицу в бд
        $this->load->model('extension/simple');
        $this->model_extension_simple_ajax_search->dropDatabase();

    }

    public
    function getSetting()
    {

    }


    protected
    function validate()
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
}

