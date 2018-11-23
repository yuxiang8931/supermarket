<?php
class ControllerCommonSearch extends Controller {

				public function autosearch() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/product');
            $this->load->model('tool/image');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            $filter_data = array(
                'filter_name'  => $filter_name,
            );

            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
            	// echo "<pre>";print_r($result);exit();
             if (isset($result['special'])) {
                    $special  = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $special = '';
            }

            if (is_file(DIR_IMAGE . $result['image'])) {
              $image  = $this->model_tool_image->resize($result['image'], "75", "75");
            }else {
              $image = $this->model_tool_image->resize('placeholder.png', "75", "75");
            }
            


                $option_data = array();

                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'href'       => $this->url->link('product/product', '&product_id=' . $result['product_id']),
                    'price'      => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),

                    'special'      => $special,
                    'image'      => $image
                );//echo "<pre>";print_r($json);exit();
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
				
	public function index() {
		$this->load->language('common/search');

		$data['text_search'] = $this->language->get('text_search');

		if (isset($this->request->get['search'])) {
			$data['search'] = $this->request->get['search'];
		} else {
			$data['search'] = '';
		}

		return $this->load->view('common/search', $data);
	}
}