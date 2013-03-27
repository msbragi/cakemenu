<div class="cakemenus form">
  <?php echo $this->Form->create('Menu', array('url' => array('controller' => 'cakemenu', 'action' => 'edit'))); ?>
  <fieldset>
    <legend><?php printf(__('Edit %s'), __('Cakemenu', true)); ?></legend>
    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('name', array('type' => 'text'));
    echo $this->Form->input('link', array('type' => 'text'));
    echo $this->Form->input('title', array('type' => 'text'));
    echo $this->Form->input('parent_id', array('empty' => true));
    echo $this->Form->input('display', array('type' => 'select', 'options' => $avalues, 'empty' => false));
    echo $this->Form->input('icon', array('type' => 'text'));
    ?>
  </fieldset>
  <?php echo $this->Form->end(__('Submit')); ?>
</div>