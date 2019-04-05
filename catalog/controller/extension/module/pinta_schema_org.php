<?php

class ControllerExtensionModulePintaSchemaOrg extends Controller
{

    private $setting = array();


    public function index()
    {
        $this->load->model('setting/setting');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');


        $setting = $this->model_setting_setting->getSetting('pinta_schema_org');
        $self = $this->model_setting_setting->getSetting('config');
        foreach ($setting as $key => $item) {
            $this->setting[$key] = $item;
        }
        if (!empty($this->setting)) {
            if ($this->setting['pinta_schema_org_status'] == 1) {
                if (isset($this->request->get['route'])) {

                    if ($this->request->get['route'] == 'product/product') {

                        if ($this->setting['pinta_schema_org_select_product'] == 1) {

                            $data['schema_product'] = $this->getProduct();
                            $data['meta'] = $this->metaDescription($self);
                        }

                    }

                }
                if ($this->setting['pinta_schema_org_select_breadcrumb_list'] == 1) {

                    $data['schema_breadcrumb_list'] = $this->jsonBreadcrumbList();


                }
                if ($this->setting['pinta_schema_org_select_organization'] == 1) {

                    $data['schema_organization'] = $this->jsonOrganization();

                }
                if ($this->setting['pinta_schema_org_select_website'] == 1) {

                    $data['schema_website'] = $this->jsonWebSite();

                }
                if ($this->setting['pinta_schema_org_select_place'] == 1) {

                    $data['schema_place'] = $this->jsonPlace($this->setting['pinta_schema_org_select_place_adress']);

                }
                if ($this->setting['pinta_schema_org_select_times'] == 1) {

                    $data['schema_time'] = $this->jsonTimes($this->setting['pinta_schema_org_select_time_full'],$this->setting['pinta_schema_org_select_place_adress']['name']);

                }

            }

            return $data;
        }


        return false;
    }

    public function jsonTimes($time, $name)
    {
        foreach ($time as $item){
            $str[] = $item['day'].' '.$item['time'];
        }
        $times = array("@context" => "http://schema.org",
            "@type" => "CivicStructure",
            "name" => $name,
            "openingHours" => $str,
        );

        $json = '<script type="application/ld+json">' . json_encode($times) . '</script>';

        return $json;
    }

    private function jsonPlace($adress)
    {

        $place = array(
            "@context" => "http://schema.org",
            "@graph" => array(

                "@type" => "Place",
                "address" => array(
                    "@type" => "PostalAddress",
                    "addressLocality" => $adress['city'],
                    "addressRegion" => $adress['region'],
                    "postalCode" => $adress['index'],
                    "streetAddress" => $adress['adress']
                ),
                "name" => $adress['name'],

            ),
        );

        $json = '<script type="application/ld+json">' . json_encode($place) . '</script>';

        return $json;

    }

    private function jsonWebSite()
    {

        $website = array(
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "url" => HTTPS_SERVER,
            "potentialAction" => array(
                "@type" => "SearchAction",
                "target" => HTTPS_SERVER . 'index.php?route=product/search&search={search}',
                "query-input" => "required name=search"
            )
        );
        $json = '<script type="application/ld+json">' . json_encode($website) . '</script>';

        return $json;

    }

    private function jsonOrganization()
    {

        $contact = array();
        $url = array();
        foreach ($this->setting['pinta_schema_org_select_organization_url'] as $key => $element) {
            if ($key !== 0) {
                $url[] = $element;
            }
        }

        foreach ($this->setting['pinta_schema_org_select_organization_telefon'] as $item) {
            $contact[] = array("@type" => "ContactPoint",
                "telephone" => $item,
                "contactType" => "customer service"
            );
        }

        $result = array(
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "url" => $this->setting['pinta_schema_org_select_organization_url'][0],
            "sameAS" => $url,
            "contactPoint" => $contact,
        );

        $json = '<script type="application/ld+json">' . json_encode($result) . '</script>';

        return $json;
    }

    private function getBreadcrumbList()
    {
        if (isset($this->request->get['route'])) {
            if ($this->request->get['route'] == 'product/category') {

                $this->load->language('product/category');

                $this->load->model('catalog/category');

                $this->load->model('catalog/product');


                $data['breadcrumbs'] = array();

                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/home')
                );

                if (isset($this->request->get['path'])) {

                    $path = '';

                    $parts = explode('_', (string)$this->request->get['path']);

                    $category_id = (int)array_pop($parts);

                    foreach ($parts as $path_id) {
                        if (!$path) {
                            $path = (int)$path_id;
                        } else {
                            $path .= '_' . (int)$path_id;
                        }

                        $category_info = $this->model_catalog_category->getCategory($path_id);

                        if ($category_info) {
                            $data['breadcrumbs'][] = array(
                                'text' => $category_info['name'],
                                'href' => $this->url->link('product/category', 'path=' . $path)
                            );
                        }
                    }
                } else {
                    $category_id = 0;
                }

                $category_info = $this->model_catalog_category->getCategory($category_id);

                if ($category_info) {


                    // Set the last category breadcrumb
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
                    );

                }
                return ($data['breadcrumbs']);

            } elseif ($this->request->get['route'] == 'information/information') {

                $this->load->language('information/information');

                $this->load->model('catalog/information');

                $data['breadcrumbs'] = array();

                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/home')
                );

                if (isset($this->request->get['information_id'])) {
                    $information_id = (int)$this->request->get['information_id'];
                } else {
                    $information_id = 0;
                }

                $information_info = $this->model_catalog_information->getInformation($information_id);

                if ($information_info) {

                    $data['breadcrumbs'][] = array(
                        'text' => $information_info['title'],
                        'href' => $this->url->link('information/information', 'information_id=' . $information_id)
                    );

                }
                return ($data['breadcrumbs']);
            } elseif ($this->request->get['route'] == 'product/product') {
                $this->load->language('product/product');

                $data['breadcrumbs'] = array();

                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/home')
                );

                $this->load->model('catalog/category');

                if (isset($this->request->get['path'])) {
                    $path = '';

                    $parts = explode('_', (string)$this->request->get['path']);

                    $category_id = (int)array_pop($parts);

                    foreach ($parts as $path_id) {
                        if (!$path) {
                            $path = $path_id;
                        } else {
                            $path .= '_' . $path_id;
                        }

                        $category_info = $this->model_catalog_category->getCategory($path_id);

                        if ($category_info) {
                            $data['breadcrumbs'][] = array(
                                'text' => $category_info['name'],
                                'href' => $this->url->link('product/category', 'path=' . $path)
                            );
                        }
                    }

                    // Set the last category breadcrumb
                    $category_info = $this->model_catalog_category->getCategory($category_id);

                    if ($category_info) {
                        $url = '';
                        $data['breadcrumbs'][] = array(
                            'text' => $category_info['name'],
                            'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
                        );
                    }
                }

                $this->load->model('catalog/manufacturer');

                if (isset($this->request->get['manufacturer_id'])) {
                    $data['breadcrumbs'][] = array(
                        'text' => $this->language->get('text_brand'),
                        'href' => $this->url->link('product/manufacturer')
                    );

                    $url = '';
                    $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

                    if ($manufacturer_info) {
                        $data['breadcrumbs'][] = array(
                            'text' => $manufacturer_info['name'],
                            'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
                        );
                    }
                }

                if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
                    $url = '';
                    $data['breadcrumbs'][] = array(
                        'text' => $this->language->get('text_search'),
                        'href' => $this->url->link('product/search', $url)
                    );
                }

                if (isset($this->request->get['product_id'])) {
                    $product_id = (int)$this->request->get['product_id'];
                } else {
                    $product_id = 0;
                }

                $this->load->model('catalog/product');

                $product_info = $this->model_catalog_product->getProduct($product_id);

                if ($product_info) {
                    $url = '';

                    $data['breadcrumbs'][] = array(
                        'text' => $product_info['name'],
                        'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
                    );


                }
                return ($data['breadcrumbs']);

            } elseif (isset($this->request->get['route'])) {
                $route = $this->request->get['route'];
                $this->load->language($route);


                $data['breadcrumbs'] = array();

                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/home')
                );
                if ($route == 'account/login') {
                    $data['breadcrumbs'][] = array(
                        'text' => $this->language->get('text_account'),
                        'href' => $this->url->link('account/account', '', true)
                    );

                    $data['breadcrumbs'][] = array(
                        'text' => $this->language->get('text_login'),
                        'href' => $this->url->link('account/login', '', true)
                    );
                } else {

                    $data['breadcrumbs'][] = array(
                        'text' => $this->language->get('heading_title'),
                        'href' => $this->url->link($route)
                    );
                }


                return ($data['breadcrumbs']);
            }
        }
        return false;

    }

    private function jsonBreadcrumbList()
    {


        $list = $this->getBreadcrumbList();
        if ($list) {
            foreach ($list as $key => $item) {
                if ($item['text'] == '<i class="fa fa-home"></i>') {
                    $item['text'] = 'home';
                }
                $li[] = array(
                    "@type" => "ListItem",
                    "position" => $key + 1,
                    "name" => $item['text'],
                    "item" => $item['href'],
                );
            }

            $result = array(
                "@context" => "https://schema.org",
                "@type" => "BreadcrumbList",
                "itemListElement" => $li,
            );

            $json = '<script type="application/ld+json">' . json_encode($result) . '</script>';

            return $json;
        }
        return false;
    }

    private function getProduct()
    {
        $productId = $this->request->get['product_id'];


        $product = $this->model_catalog_product->getProduct($productId);


        $categorys = $this->getCategory();

        $price = $this->getPrice($product);

        $reviews = $this->review();

        $rews = array();

        if (!empty($reviews['results'])) {

            foreach ($reviews['results'] as $review) {

                $rews[] = array(

                    "@type" => "Review",
                    "reviewRating" => array(
                        "@type" => "Rating",
                        "ratingValue" => $review['rating'],
                        "bestRating" => "5",
                    ),
                    "author" => array(
                        "@type" => "Person",
                        "name" => $review['author'],
                    ),


                );

            }
        }


        $aggregateRating = '';
        if ($product['rating'] != 0) {
            $aggregateRating = array(
                "@type" => "AggregateRating",
                "ratingValue" => $product['rating'],
                "reviewCount" => $product['reviews'],
            );
        }
        $jsoncode = array(

            "@context" => 'https://schema.org/',
            "@type" => "Product",
            "name" => $product['name'],
            "image" => HTTPS_SERVER . $product['image'],
            "description" => $product['description'],
            "sku" => $product['sku'],
            "mpn" => $product['mpn'],
            "brand" => array(
                "@type" => "Thing",
                "name" => $product['manufacturer'],
            ),

            "review" => $rews,

            "aggregateRating" => $aggregateRating,
            "offers" => array(
                "@type" => "Offer",
                "url" => HTTPS_SERVER . $_SERVER['REQUEST_URI'],
                "priceCurrency" => $this->session->data['currency'],
                "price" => $price,
                "itemCondition" => "https://schema.org/UsedCondition",
                "availability" => "https://schema.org/InStock",

            ),
        );


        $json = '<script type="application/ld+json">' . json_encode($jsoncode) . '</script>';


        return $json;

    }

    public function metaDescription($self)
    {
        $this->load->model('catalog/product');
        $productId = $this->request->get['product_id'];
        $this->load->language('extension/module/pinta_schema_org');


        $product = $this->model_catalog_product->getProduct($productId);

        if (empty($product['meta_description'])) {
            $meta = '<meta name="description" content="' . $product['name'] . ' ' . $this->language->get('buy') . '  ' . $self['config_name'] .
                ' |' . $this->language->get('telefon') . ' ' . $self['config_telephone'] . ' 
        " >';
        } else {
            $meta = '<meta name="description" content="' . $product['meta_description'] .
                ' |' . $this->language->get('telefon') . ' ' . $self['config_telephone'] . ' ">';
        }
        return $meta;

    }

    private function getPrice($product)
    {

        $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
        $prices = preg_match("/[0-9]+\.[0-9]+/", $price, $math);

        return $math[0];


    }

    private function getCategory()
    {

        $this->load->model('catalog/category');

        if (isset($this->request->get['path'])) {
            $path = '';

            $parts = explode('_', (string)$this->request->get['path']);

            $category_id = (int)array_pop($parts);


            // Set the last category breadcrumb
            $category_info = $this->model_catalog_category->getCategory($category_id);
        }
        if (empty($category_info['name'])) {
            $categorys = 'Thing';
        } else {
            $categorys = $category_info['name'];
        }

        return $categorys;
    }


    public function review()
    {
        $this->load->language('product/product');

        $this->load->model('catalog/review');

        $page = '';

        $review['total'] = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

        $review['results'] = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id']);


        return $review;
    }

}