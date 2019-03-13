<?php

class ControllerCatalogCastom extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('catalog/castom');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/castom');
$this->install();

        $this->getList();
    }

    public function install()
    {
        $this->load->model('catalog/castom');
        $e = $this->db->query("CHECK TABLE " . DB_PREFIX . "castom")->row['Msg_type'];


        if ($e !== 'status') {
            $this->model_catalog_castom->createTable();


        }

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
                "catalog/castom");
            $this->model_user_user_group->addPermission(
                $admin_user_group_id,
                "modify",
                "catalog/castom");
        }

    }

    public function add()
    {
        $this->load->language('catalog/review');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/review');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_review->addReview($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_product'])) {
                $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_author'])) {
                $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/review', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('catalog/castom');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/castom');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_castom->editCastom($this->request->get['castom_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';


            $this->response->redirect($this->url->link('catalog/castom', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('catalog/review');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/review');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $review_id) {
                $this->model_catalog_review->deleteReview($review_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_product'])) {
                $url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_author'])) {
                $url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['filter_date_added'])) {
                $url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/review', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

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
            'href' => $this->url->link('catalog/castom', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('catalog/castom/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('catalog/castom/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['savexml'] = $this->url->link('catalog/castom/saveXml', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['castom'] = array();


        $results = $this->model_catalog_castom->getEnables();

        if (!empty($results)) {
            foreach ($results as $result) {
                $data['castoms'][] = array(
                    'castom_id' => $result['id'],
                    'before' => $result['before'],
                    'after' => $result['after'],
                    'binding' => $this->binding($result['binding']),
                    'enable' => $result['enable'],
                    'edit' => $this->url->link('catalog/castom/edit', 'user_token=' . $this->session->data['user_token'] . '&castom_id=' . $result['id'] . $url, true)
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
        $pagination->url = $this->url->link('catalog/castom', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        $data['link'] = '';
        if (!empty($_POST['savexml'])) {
            if (file_exists('sitemap.xml')) {
                unlink('sitemap.xml');
            }
            if ($this->saveXml()) {
                $data['link'] = HTTP_CATALOG . 'admin/sitemap.xml';
            }


        }
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/castom_list', $data));
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['castom_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_before'] = $this->language->get('entry_before');
        $data['entry_after'] = $this->language->get('entry_after');
        $data['entry_binding'] = $this->language->get('entry_binding');
        $data['entry_date_added'] = $this->language->get('entry_date_added');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_enable'] = $this->language->get('entry_enable');

        $data['help_product'] = $this->language->get('help_product');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['save_sitemap'] = $this->language->get('save_sitemap');
        $data['entry_binding'] = $this->language->get('entry_binding');
        $data['entry_state'] = $this->language->get('entry_state');
        $data['save_button'] = $this->language->get('save_button');


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['before'])) {
            $data['error_before'] = $this->error['before'];
        } else {
            $data['error_before'] = '';
        }

        if (isset($this->error['after'])) {
            $data['error_after'] = $this->error['after'];
        } else {
            $data['error_after'] = '';
        }

        if (isset($this->error['binding'])) {
            $data['error_binding'] = $this->error['binding'];
        } else {
            $data['error_binding'] = '';
        }

        if (isset($this->error['rating'])) {
            $data['error_rating'] = $this->error['rating'];
        } else {
            $data['error_rating'] = '';
        }

        $url = '';


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/castom', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['castom_id'])) {
            $data['action'] = $this->url->link('catalog/castom/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('catalog/castom/edit', 'user_token=' . $this->session->data['user_token'] . '&castom_id=' . $this->request->get['castom_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('catalog/castom', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['castom_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $review_info = $this->model_catalog_castom->getCastomId($this->request->get['castom_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];

        $this->load->model('catalog/castom');

        if (isset($this->request->post['castom_id'])) {
            $data['castom_id'] = $this->request->post['castom_id'];
        } elseif (!empty($review_info)) {
            $data['castom_id'] = $review_info['id'];
        } else {
            $data['castom_id'] = '';
        }

        if (isset($this->request->post['before'])) {
            $data['before'] = $this->request->post['before'];
        } elseif (!empty($review_info)) {
            $data['before'] = $review_info['before'];
        } else {
            $data['before'] = '';
        }

        if (isset($this->request->post['after'])) {
            $data['after'] = $this->request->post['after'];
        } elseif (!empty($review_info)) {
            $data['after'] = $review_info['after'];
        } else {
            $data['after'] = '';
        }

        if (isset($this->request->post['binding'])) {
            $data['binding'] = $this->request->post['binding'];
        } elseif (!empty($review_info)) {
            $data['binding'] = $this->binding($review_info['binding']);
        } else {
            $data['binding'] = '';
        }

        if (isset($this->request->post['enable'])) {
            $data['enable'] = $this->request->post['enable'];
        } elseif (!empty($review_info)) {
            $data['enable'] = $review_info['enable'];
        } else {
            $data['enable'] = '';
        }


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/castom_form', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'catalog/castom')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['before']) {
            $this->error['before'] = $this->language->get('error_before');
        }

        if (!($this->request->post['after'])) {
            $this->error['after'] = $this->language->get('error_after');
        }


        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'catalog/review')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function binding($type)
    {
        if ($type == 'product') {
            $result = $this->language->get('type_product');
        } elseif ($type == 'category') {
            $result = $this->language->get('type_category');
        } else {
            $result = 'Такого не може быть';
        }
        return $result;
    }

    protected function saveXml()
    {
        $this->load->model('catalog/castom');

        if (!empty($_POST['savexml'])) {
            if (file_exists('sitemap.xml')) {
                unlink('sitemap.xml');
            }


            $dom = new domDocument("1.0", "utf-8");

            $urlset = $dom->createElement("urlset"); // Создаём корневой элемент
            $urlset->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
            $urlset->setAttribute("xmlns:image", "http://www.google.com/schemas/sitemap-image/1.1");

            $dom->appendChild($urlset);


            $product_ids = $this->model_catalog_castom->getProductsId();

            foreach ($product_ids as $product_id) {

                $url = $dom->createElement('url');

                $locvalue = HTTP_SERVER . 'index.php?route=product/product&product_id=' . $product_id['product_id'];

                $url->appendChild($dom->createElement('loc', htmlspecialchars($locvalue)));

                $image = HTTP_SERVER . $product_id['image'];

                $images = $dom->createElement('image:image');
                $images->appendChild($dom->createElement('image:loc', htmlspecialchars($image)));
                $url->appendChild($images);

                $urlset->appendChild($url);

            }
        }

        $dom->formatOutput = true;
        $dom->save("sitemap.xml"); // Сохраняем полученный XML-документ в файл
        if (file_exists('sitemap.xml')) {
            $link = linkinfo('sitemap.xml');

        } else {
            echo 'Ошибка: файл не сохранен';
        }

        return $link;

    }

}