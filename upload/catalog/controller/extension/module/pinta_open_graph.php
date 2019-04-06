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
        $this->load->model('tool/image');
        $server = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
        $images = $this->setting['pinta_open_graph_image_default'];
        $regexp='%[fhtps]{3,5}:\\/\\/[^\\/]+?\\/image/%';
        $image = preg_replace($regexp,'',$images);


        $imag = $this->model_tool_image->resize($image, $this->setting['pinta_open_graph_width_image'],
            $this->setting['pinta_open_graph_height_image']);


        $meta = '  <meta property="og:type"                   content="website" />
    <meta property="og:title"                  content="' . $self['config_name'] . '" />
    <meta property="og:description"            content="' . $self['config_meta_description'] . '" />
    <meta property="og:url"                    content="' . $server . $_SERVER['REQUEST_URI'] . '" />';
        $metaImg = ' <meta property="og:image"                  content="' .$imag . '" />
        <meta property="og:image:width"           content="' . $this->setting['pinta_open_graph_width_image'] . '" />
      <meta property="og:image:height"       content="' . $this->setting['pinta_open_graph_height_image'] . '" />';


        return $meta . $metaImg;


    }

    private function getProduct()
    {
        $productId = $this->request->get['product_id'];
        $product = $this->model_catalog_product->getProduct($productId);
        $server = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];

        $text = preg_replace('/(?:<|&lt;)\/?([a-zA-Z]+) *[^<\/]*?(?:>|&gt;)/', '', $product['description']);


        $meta = '  <meta property="og:type"                   content="og:product" />
    <meta property="og:title"                  content="' . $product['name'] . '" />
    <meta property="og:description"            content="' . substr(strip_tags($text), 0, 280) . '" />
    <meta property="og:url"                    content="' . $server . $_SERVER['REQUEST_URI'] . '" />
    <meta property="product:plural_title"      content="' . $product['name'] . '" />';
        $curency = '';
        foreach ($this->setting['pinta_open_graph_curency'] as $key => $val) {
            if ($val == 1) {
                $curency .= '
 <meta property="product:price:amount"      content="' . $this->getPrice($product['price'], $product['tax_class_id'], $key) . '"/>
    <meta property="product:price:currency"    content="' . $key . '"/>';
            }
        }


        $metaImg = '';

        if( $this->getImage()) {
            $metaImg .= ' <meta property="og:image"                  content="' . $this->getImage() . '" />';
            $metaImg .= ' <meta property="og:image:width"           content="' . $this->setting['pinta_open_graph_width_image'] . '" />';
            $metaImg .= ' <meta property="og:image:height"       content="' . $this->setting['pinta_open_graph_height_image'] . '" />';
        } else {
            $metaImg .= ' <meta property="og:image"                  content="' . $this->setting['pinta_open_graph_image_default'] . '" />';
            $metaImg .= ' <meta property="og:image:width"           content="' . $this->setting['pinta_open_graph_width_image'] . '" />';
            $metaImg .= ' <meta property="og:image:height"       content="' . $this->setting['pinta_open_graph_height_image'] . '" />';
        }

        return $meta . $metaImg.$curency;

    }

    private function getPrice($price, $tax, $currency)
    {
        $price = $this->currency->format($this->tax->calculate($price, $tax, $this->config->get('config_tax')), $currency);
        $prices = preg_match("/[0-9]+\.[0-9]+/", $price, $math);

        return $math[0];


    }

    private function getImage()
    {
        $this->load->model('tool/image');
        if(!empty($this->request->get['product_id'])) {
            $product = $this->model_catalog_product->getProduct($this->request->get['product_id']);


            if (!empty($product['image'])) {
                $image = $this->model_tool_image->resize($product['image'], $this->setting['pinta_open_graph_width_image'],
                    $this->setting['pinta_open_graph_height_image']);
            }

            return $image;

        }
        return false;
    }

}