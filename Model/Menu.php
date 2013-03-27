<?php

App::uses('CakemenuAppModel', 'Cakemenu.Model');
App::uses('TreeBehavior', 'Model/Behavior');

class Menu extends CakemenuAppModel {

	public $name = 'Menu';
	public $useTable = 'cakemenu';
	public $displayField = 'name';
	public $actsAs = array('Tree');

	public $validate = array(
		'name' => 'notempty',
		'link' => 'notempty',
	);

}
?>