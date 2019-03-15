<?php

class ControllerExtensionModuleSimple extends Controller
{
    private $setting = array();
    private $min_symbol;
    private $search;

    public function searchs()
    {

        $this->load->model('extension/module/simple');
        if (empty($this->setting)) {
            $this->setting = $this->model_extension_module_simple->getSetting();
            foreach ($this->setting as $key => $item) {

                if (mb_strlen($item) > 2) {
                    $unser = unserialize($item);
                    $this->setting[$key] = $unser;
                } else {
                    $this->setting[$key] = $item;

                }
            }
        }
        if (!empty($this->request->post['search'])) {
            $this->search = trim(strip_tags(stripcslashes(htmlspecialchars($this->request->post['search']))));

        }
        $result = array();
        if ($this->setting['setting_input']['setting_status'] == 1) {
            $setting = $this->setting['setting_input'];
            $category = $this->setting['category_input'];
            $information = $this->setting['information_input'];
            $manufacturer = $this->setting['manufacturer_input'];
            $product = $this->setting['product_input'];
            $desing = $this->setting['desing_input'];
            $query = array();
            if (mb_strlen($this->search) >= $setting['setting_min_symbol']) {
                foreach ($category as $name => $val) {
                    if ($val == 1) {
                        $query[] = str_replace('category_', '', $name);
                    }
                }
                if (!empty($query)) {

                    $result['category'] = $this->model_extension_module_simple->getCategory($this->search, $query);

                    foreach ($result['category'] as $item) {
                        echo '<li><a href="http://open3.test/index.php?route=product/category&path=' . $item['category_id'] . '">' . $item['name'] . '</a></li>';
                    }
                }
                foreach ($product as $name => $val) {
                    if ($val == 1) {
                        $product_query[] = str_replace('product_', '', $name);
                    }
                }
                if (!empty($product_query)) {


                    $result['product'] = $this->model_extension_module_simple->getProduct($this->search, $product_query);

                    foreach ($result['product'] as $item) {
                        echo '<li><a href="http://open3.test/index.php?route=product/product&path='
                            . $item['category_id'] . '&product_id=' . $item['product_id'] . '" >' . $item['name'] . '</a></li>';
                    }
                }
            }
        }

    }

}
