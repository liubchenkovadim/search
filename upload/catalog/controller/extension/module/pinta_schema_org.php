<?php

class ControllerExtensionModulePintaSchemaOrg extends Controller
{

    private $setting = array();
    private $enable = false;
    private $product = false;

    public function schema()
    {
        $this->load->model('setting/setting');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');

        $productId = $this->request->post['product_id'];
        $setting = $this->model_setting_setting->getSetting('pinta_schema_org');
        foreach ($setting as $key => $item) {
            $this->setting[$key] = $item;
        }
        if ($this->setting['pinta_schema_org_status'] == 1) {
            $this->enable = true;
        } else {
            return false;
        }
        if ($this->setting['pinta_schema_org_select_product'] == 1) {
            $this->product = true;
        }
        if ($this->product) {
            $categoryId = $this->request->post['path'];
            $product = $this->model_catalog_product->getProduct($productId);
            $imagesdb = $this->model_catalog_product->getProductImages($productId);
            $images = array();
            foreach ($imagesdb as $item) {
                $images[] = '<link itemprop="image" href="' . HTTPS_SERVER . $item['image'] . '" />';

            }
            $categorys = $this->getCategory();
            $price = $this->getPrice($product);

            $reviews = $this->review();
            $reviewCount = $reviews['total'];
            $rew = '';

            foreach ($reviews['results'] as $review) {
                $rew = array(

                    "@type"=> "Review",
                    "reviewRating"=> array(
                        "@type" => "Rating",
                        "ratingValue"=>$review['rating'],
                        "bestRating"=> "5",
                    ),
                    "author"=> array(
                        "@type"=> "Person",
                        "name"=> $review['author'],
                    ),

                );

            }
            $jsoncode = array(

                "@context"=>'https://schema.org/',
                "@type" =>"Product",
                "name"=> $product['name'] ,
                "image"=> HTTPS_SERVER . $product['image'],
                "description"=> $product['description'],
                "sku"=>  $product['sku'],
                "mpn"=>  $product['mpn'],
                "brand"=> array(
                    "@type"=> "Thing",
                    "name"=> $product['manufacturer'],
                ),
                "review"=> $rew,

                "aggregateRating" =>array(
                    "@type"=> "AggregateRating",
                    "ratingValue"=> $product['rating'],
                    "reviewCount"=> $product['reviews'],
                ),
                "offers"=>array(
                    "@type"=> "Offer",
                    "url"=>$this->request->post['url'],
                    "priceCurrency"=>$this->session->data['currency'] ,
                    "price"=> $price ,
                    "itemCondition"=> "https://schema.org/UsedCondition",
                    "availability"=> "https://schema.org/InStock",
                    "seller"=> array(
                        "@type"=> "Organization",
                        "name"=> "Executive Objects"
                    ),
                ),
            );


            foreach ($reviews['results'] as $review) {
                $rew .= ' <div itemprop="review" itemtype="http://schema.org/Review" itemscope>
                             <div itemprop="author" itemtype="http://schema.org/Person" itemscope>
                                      <meta itemprop="name" content="' . $review['author'] . '" />
                              </div>
                           <div itemprop="reviewRating" itemtype="http://schema.org/Rating" itemscope>
                                <meta itemprop="ratingValue" content="' . $review['rating'] . '" />
                                  <meta itemprop="bestRating" content="5" />
                             </div>
                          </div>';

            }

            $result = '<div>
                      <div itemtype="http://schema.org/Product" itemscope>';
            $result .= '<meta itemprop="mpn" content="' . $product['mpn'] . '" />';
            $result .= '<meta itemprop="name" content="' . $product['name'] . '" />';
            foreach ($images as $image) {
                $result .= $image;
            }
            $result .= '<meta itemprop="description" content="' . $product['description'] . '" />';
            $result .= ' <div itemprop="offers" itemtype="http://schema.org/Offer" itemscope>';
            $result .= ' <link itemprop="url" href="' .$this->request->post['url'] . '" />';
            $result .= '<meta itemprop="availability" content="https://schema.org/InStock" />';
            $result .= '  <meta itemprop="priceCurrency" content="' . $this->session->data['currency'] . '" />';
            $result .= '  <meta itemprop="itemCondition" content="https://schema.org/UsedCondition" />';
            $result .= ' <meta itemprop="price" content="' . $price . '" />';

            $result .= ' <div itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating" itemscope>
                                  <meta itemprop="reviewCount" content="' . $product['reviews'] . '" />
                                 <meta itemprop="ratingValue" content="' . $product['rating'] . '" />
                         </div>';
            $result .= $rew;
            $result .= ' <meta itemprop="sku" content="'.$product['sku'].'" />
                            <div itemprop="brand" itemtype="http://schema.org/Thing" itemscope>
                               <meta itemprop="name" content="'.$product['manufacturer'].'" />
                             </div>
                         </div>
                    </div>';


            $json = ' <div name="schema_org" style="width:1px;height: :1px;"">' . $result . '</div>';

        }

        //echo $json;
        $json = '<script type="application/ld+json">'.json_encode($jsoncode).'</script>';
        echo  $json;
        // $this->response->setOutput($this->load->view('extension/module/pinta_schema_org', $data));


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

        if (isset($this->request->post['path'])) {
            $path = '';

            $parts = explode('_', (string)$this->request->post['path']);

            $category_id = (int)array_pop($parts);


        }
        // Set the last category breadcrumb
        //  $category_info = $this->model_catalog_category->getCategory($category_id);

        // return $category_info['name'];
    }


    public function review()
    {
        $this->load->language('product/product');

        $this->load->model('catalog/review');

        $page = '';

        $review['total'] = $this->model_catalog_review->getTotalReviewsByProductId(40);

        $review['results'] = $this->model_catalog_review->getReviewsByProductId(40);


        return $review;
    }

}