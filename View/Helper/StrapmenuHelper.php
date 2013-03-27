<?php
/**
 * Strapmenu helper responsible for generating and displaying the menu nodes as menu
 * Uses twitter Bootstrap library:
 * http://twitter.github.com
 * Based on cakemenu helper of  Nik chankov <contact@chankov.net>
 *
 * @author Marco Sbragi <m.sbragi@nospace.net>
 * @date 29.11.2012
 */
App::uses('AppHelper', 'View/Helper');
class StrapmenuHelper extends AppHelper {
	public $helpers = array('Html');

	var $_url   = null;
	var $_links = array();
	var $_crumb = array();

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		/*
		 * Normalize url
		 */
		$url = Router::normalize($View->request->url);
		$url = Router::parse($url);
		$this->_url = $url;
	}

	/**
	 * Function generate is used to build html nodes of the menu
	 * @param array $data result from the Cakemenu Component
	 * @param integer $level level of the current subling
	 * @param array $path path of the elements to the current node. (if provided)
	 * @return string html output of the menu
	 */
	function generate($data, $level = 0, $path = array()){
		if($data == null){
			return '';
		}

		foreach($data as $key => $value) {
			$sub = '';
			if(isset($value['children']) && count($value['children']) > 0){
				$sub = $this->generate($value['children'], $level+1, $path);
			}
			$link = $this->_getLink($value, $sub);

			$options = array();
			if($sub){
				$link = $link . $sub;
				if($level) {
					$options['class'] = 'dropdown-submenu';
				} else {
					$options['class'] = 'dropdown';
				}
 			}

			$li[] = "\n".str_repeat("\t", ($level+1)) . $this->Html->tag('li', $link, $options) . "\n".str_repeat("\t", $level);
		}

		$options = array();
		if($sub) {
			$options['class'] = 'nav';
		} else {
			$options['class'] = 'dropdown-menu';
		}
		$tree = "\n".str_repeat("\t", $level) . $this->Html->tag('ul', implode("\n", $li), $options) . "\n".str_repeat("\t", $level);
		return $tree;
	}

	function _getLink( $value, $sub ) {
		$link = '';
		if($value['Menu']['link'] == '') {
			$value['Menu']['link'] = '#';
		}
		if($value['Menu']['link'] != '#') {
			if(eregi('^array', $value['Menu']['link'])){
				eval("\$parse = ".$value['Menu']['link'].";");
				if(is_array($parse)){
					$link = $parse;
				}
			} else {
				$link = $value['Menu']['link'];
			}
		}
		$options = array();
		$options['escape'] = false;
		$options['title'] = $value['Menu']['title'] ? $value['Menu']['title'] : $value['Menu']['name'];
		$name = $value['Menu']['name'];
		if($sub) {
			$options['class']       = 'dropdown-toggle';
			$options['data-toggle'] = 'dropdown';
			$options['data-hover']  = 'dropdown';
			$name                  .= ' <b class="caret"></b>';
		}
		if($value['Menu']['icon']) {
			$name = '<i class="' . $value['Menu']['icon'] . '"></i>&nbsp;' . $name . " ";
		}
		if($value['Menu']['link'] != '#') {
			$this->_links[$value['Menu']['id']] = $link;
		}
		return $this->Html->link($name, $link, $options);
	}

	/*
	 * Add crumb segment based on menu item selected
	 */
	function addCrumb($name, $link, $title) {
		if($link == '#' || $link == '') {
			$link = null;
		}
		$this->Html->addCrumb($name, $link, array('title' => $title ? $title : $name));
	}

	/*
	 * get crumb path based on menu item selected
	 */
	function getCrumb($return = true) {
		$links = array();
		$url   = Router::url($this->_url);

		// Normalize url with action and without
		foreach($this->_links as $k => $v) {
			$link  = Router::normalize($v);
			$link  = Router::parse($link);
			$links[$k] = array(
				'0' => Router::url($link), // Perfect url
				'1' => ''
			);
			$link['action'] = '';
			$links[$k]['1'] = Router::url($link); // Without action
		}

		$found = false;
		// Search for perfect link first and without action next
		for( $i = 0; $i <= 1; $i++ ) {
			foreach($links as $k => $v) {
				if(strpos($url, $v[$i]) !== false) {
					$found = $k;
					break;
				}
			}
			if($found) {
				break;
			}
		}

		if($found) {
			$menu = new Menu();
			$data = $menu->getPath($found, array('id', 'name','title', 'link'));
			foreach($data as $k => $v) {
				$this->addCrumb($v['Menu']['name'], $v['Menu']['link'], $v['Menu']['title']);
			}

			// Not a perfect match add action and params
			if($i == 1) {
				$this->Html->addCrumb($this->_url['action']);
				foreach($this->_url['pass'] as $k => $v) {
					$this->Html->addCrumb($v);
				}
			}
			if($return) {
				$start = array(
					'text' => 'Home',
					'url' => '/',
					'escape' => false
				);
				$crumb = $this->Html->getCrumbList(array('separator' => "<span class='divider'>/</span>"), $start);
				$crumb = str_replace("<ul>", "<ul class='breadcrumb'>",  $crumb);
				return $crumb;
			}
		}
		return '';
	}

	function test() {
		$this->getCrumb();
	}
}