<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');


				$data['facebook'] = $this->config->get('config_facebook');
				$data['twitter'] = $this->config->get('config_twitter');
				$data['gplus'] = $this->config->get('config_gplus');
				$data['linkedin'] = $this->config->get('config_linkedin');
				$data['pinterest'] = $this->config->get('config_pinterest');
				$data['instagram'] = $this->config->get('config_instagram');
				$data['youtube'] = $this->config->get('config_youtube');

				$data['language'] = $this->session->data['language'];
				$data['arabic_address']= $this->config->get('config_arabic_address');
				$data['arabic_email']= $this->config->get('config_arabic_email');
				
		$this->load->model('catalog/information');

				if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}
				$data['name'] = $this->config->get('config_name');
		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$data['address'] = $this->config->get('config_address');
		$data['email'] = $this->config->get('config_email');
		$data['telephone'] = $this->config->get('config_telephone');
		$data['about'] = $this->config->get('config_comment');
				

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		
				$data['powered'] = sprintf($this->language->get('text_powered'), date('Y', time()), $this->config->get('config_name'));
				

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		$data['scripts'] = $this->document->getScripts('footer');
		
		return $this->load->view('common/footer', $data);
	}
}
