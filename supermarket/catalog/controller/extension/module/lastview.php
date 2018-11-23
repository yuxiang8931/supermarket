<?php
class ControllerExtensionModuleLastview extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/lastview');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}


		$rlimit = $setting['limit']; 
	$rstart = 0; 
	if (isset($this->request->get['product_id'])) {
			$this->recentview->add($this->request->get['product_id']);
			$rstart += 1;  
			$rlimit = $setting['limit'] + 1; 
	} 

	$data['products'] = array();
	$recent_view_products_array = $this->recentview->getRecentViewedProducts();
	if(count($recent_view_products_array) > $rstart){
		$reversed_recent_view_products = array_reverse($recent_view_products_array);
		$recent_viewed_products = array_slice($reversed_recent_view_products, $rstart, $rlimit);
		foreach ($recent_viewed_products as $product) {
			if (is_file(DIR_IMAGE . $product['image'])) {
				$image = $this->model_tool_image->resize( $product['image'], $setting['width'], $setting['height'] );
			} else {
				$image = $this->model_tool_image->resize( 'placeholder.png', $setting['width'], $setting['height'] );
			}

			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = array(
				'name'  => $option['name'],
				'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
				'type'  => $option['type']
				);
			}

			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$price = false;
			}

			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']);
			} else {
				$total = false;
			}

			$data['products'][] = array(
						'key'       => $product['key'],
						'thumb'     => $image,
						'name'      => substr($product['name'], 0,40),
						'model'     => $product['model'],
						'option'    => $option_data,
						'recurring' => ($product['recurring'] ? $product['recurring']['name'] : ''),
						'quantity'  => $product['quantity'],
						'price'     => $price,
						'total'     => $total,
						'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
						);
		}

		

		if ($data['products']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_theme') . '/template/extension/module/lastview.twig')) {
				return $this->load->view('extension/module/lastview', $data);
			}
		}
	}
}
	
}