<?php
App::uses('CakemenuAppController', 'Cakemenu.Controller');
App::uses('Menu', 'Cakemenu.Model');
App::uses('Cakemenu', 'Cakemenu.Helper/View');

class CakemenuController extends CakemenuAppController {
	public $name = 'Cakemenu';
	public $uses = array('Cakemenu.Menu');

	public function index() {
		$menu_list = $this->Menu->generateTreeList(null, null, null, '<span class="indent">|—</span>');
		//Get links
		$links = $this->Menu->find('list', array('fields'=>array('id', 'link')));
		//Get display
		$displays = $this->Menu->find('list', array('fields'=>array('id', 'display')));
		//Get title
		$titles = $this->Menu->find('list', array('fields'=>array('id', 'title')));
		$this->set('menu_list', $menu_list);
		$this->set('links', $links);
		$this->set('titles', $titles);
		$this->set('displays', $displays);
	}

	public function preview($type = null){
		$this->set('type', $type);
		$menu = $this->Menu->find('threaded');
		$this->set('menu', $menu);
	}

	public function move($id = null, $direction = 'down'){
		if($direction == 'down'){
			$this->Menu->moveDown(intval($id));
		} else {
			$this->Menu->moveUp(intval($id));
		}
		$this->redirect(array('action'=>'index'));
	}

	public function recover(){
		$this->Menu->recover($this->Menu);
		$this->redirect(array('action'=>'index'));
	}

	public function edit($id = null) {
		if (!empty($this->data)) {
			if ($this->Menu->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved'), 'Menu'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.'), 'Menu'), 'error');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Menu->read(null, $id);
		}
		$parents = $this->Menu->generateTreeList(null, null, null, '___');
		$avalues = array('-999' => 'Always', '1' => 'When not logged','2' => 'When logged');
		$this->set(compact('parentCakemenus', 'parents', 'avalues'));
	}

	public function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s'), 'Menu'), 'warning');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Menu->removeFromTree($id)) {
			$this->Menu->delete($id);
			$this->Session->setFlash(sprintf(__('%s deleted'), 'Menu'), 'success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted'), 'Menu'), 'error');
		$this->redirect(array('action' => 'index'));
	}
}
?>