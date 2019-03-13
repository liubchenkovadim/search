<?php
class ControllerExtensionModuleSimple extends Controller
{
    private $route = 'extension/module/simple';

    public function index()
    {
       // $this->load->language('extension/module/simple');

//        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/simple');
        $this->install();

        $this->getList();
    }
    protected function getList()
    {
        $url = '';
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/simple/simple', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['save'] = $this->url->link('extension/module/simple/save', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['setting'] = array();


        $results = $this->extension_module_simple->getSetting();

        if (!empty($results)) {
            foreach ($results as $result) {
                $data['castoms'][] = array(
                    'castom_id' => $result['id'],
                    'before' => $result['before'],
                    'after' => $result['after'],
                    'binding' => $this->binding($result['binding']),
                    'enable' => $result['enable'],
                    'edit' => $this->url->link('extension/simple/simple/edit', 'user_token=' . $this->session->data['user_token'] . '&castom_id=' . $result['id'] . $url, true)
                );
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
        $pagination->url = $this->url->link('extension/simple/simple', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        $data['link'] = '';

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/simple/simple', $data));
    }
        public function install()
    {
        //создать таблицу в бд

        $this->model_extension_module_simple->createDatabase();
        $this->validate();



    }
    public function uninstall()
    {
        //создать таблицу в бд
        $this->load->model('extension/simple');
        $this->model_extension_simple_ajax_search->dropDatabase();

    }
    protected function validate()
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

