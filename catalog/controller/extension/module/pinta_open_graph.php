<?php

class ControllerExtensionModulePintaOpenGraph extends Controller
{

    private $setting = array();


    public function index()
    {
        $this->load->model('setting/setting');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');


        $setting = $this->model_setting_setting->getSetting('pinta_open_graph');
        $self = $this->model_setting_setting->getSetting('config');
        foreach ($setting as $key => $item) {
            $this->setting[$key] = $item;
        }
        if (!empty($this->setting)) {
            if ($this->setting['pinta_open_graph_status'] == 1) {

                // $data['open_graph_standart'] = $this->jsonBreadcrumbList();

                if (isset($this->request->get['route'])) {

                    if ($this->request->get['route'] == 'product/product') {

                        $data['schema_product'] = $this->getProduct();
                    }
                    $data['dafault'] = $this->getDefault($self);
                } else {

                    $data['dafault'] = $this->getDefault($self);
                }

            }

              return $data;
        }


        return false;
    }

    private function getDefault($self)
    {


        return false;
    }

    private function getProduct()
    {
        $productId = $this->request->get['product_id'];
        $server =substr( $_SERVER['HTTP_REFERER'], 0, -1);


        $product = $this->model_catalog_product->getProduct($productId);
      //  var_dump($this->config->get('config_tax'). $this->session->data['currency']);
        $meta = '  <meta property="og:type"                   content="og:product" />
    <meta property="og:title"                  content="'.$product['name'].'" />
    <meta property="og:image"                  content="'.HTTPS_SERVER.'/'.$product['image'].'" />
    <meta property="og:description"            content="'.$product['description'].'" />
    <meta property="og:url"                    content="'.$server.$_SERVER['REQUEST_URI'].'" />
    <meta property="product:plural_title"      content="'.$product['name'].'" />
    <meta property="product:price:amount"      content="'.$this->getPrice($product['price'],$product['tax_class_id'],'USD').'"/>
    <meta property="product:price:currency"    content="USD"/>
    <meta property="product:price:amount"      content="'.$this->getPrice($product['price'],$product['tax_class_id'],'UAH').'"/>
    <meta property="product:price:currency"    content="UAH"/>';



       return $meta;

    }

    private function getPrice($price,$tax,$currency)
    {
        $result = $this->currency->format($this->tax->calculate($price, $tax, $this->config->get('config_tax')), $currency);

        return $result;

    }

}