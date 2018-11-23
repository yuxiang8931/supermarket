<?php
class ControllerExtensionModuleManufacturers extends Controller {
	public function index() {
		$this->load->language('product/manufacturer');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_brands'] = $this->language->get('text_brands');
		$data['text_index'] = $this->language->get('text_index');
		
		$data['brands'] = array();
		
		$this->load->model('catalog/manufacturer');
		
		$results = $this->model_catalog_manufacturer->getManufacturersByOrder();
		
		$this->load->model('tool/image');
		
		foreach ($results as $result) {

			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 100, 100);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', 100, 100);
			}

			$data['brands'][] = array(
				'name'  => $result['name'],
				'image'  => $image,
				'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
			);
		}


		if ($data['brands']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_theme') . '/template/extension/module/manufacturers.twig')) {
				return $this->load->view('extension/module/manufacturers', $data);
			}
		}


	}
	
}
