<?php $displayWhen = array('' => 'Always', '-999' => 'Always', '1' => 'When Not logged', '2' => 'When logged'); ?>
<h2><?php echo __('Menu tree'); ?></h2>
<div class="row">
	<div class="cakemenu index span9">
		<table class="cakemenu table table-condensed table-striped">
			<tr>
				<th><?php echo __('ID'); ?></th>
				<th><?php echo __('Menu'); ?></th>
				<th><?php echo __('Link'); ?></th>
				<th><?php echo __('Title'); ?></th>
				<th><?php echo __('Display'); ?></th>
				<th>A c t i o n s</th>
			</tr>
			<?php foreach ($menu_list as $key => $node): ?>
			<tr>
				<td><?php echo $key; ?>&nbsp;</td>
				<td><?php echo $node; ?>&nbsp;</td>
				<td><?php echo @$links[$key]; ?>&nbsp;</td>
				<td><?php echo @$titles[$key]; ?>&nbsp;</td>
				<td><?php echo @$displayWhen[$displays[$key]]; ?>&nbsp;</td>
				<td class="actions"><?php echo $this->Html->link(__('down'), array('action' => 'move', $key, 'down'), array('class' => 'btn btn-small btn-inverse')); ?>
					<?php echo $this->Html->link(__('up'), array('action' => 'move', $key, 'up'), array('class' => 'btn btn-small btn-primary')); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $key), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $key), array('class' => 'btn btn-mini btn-danger'), sprintf(__('Are you sure you want to delete # %s?'), $key)); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
	<div class="span3">
		<div class="well sidebar-nav">
			<ul class="nav nav-list">
				<li class="title">Menu</li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(sprintf(__('New %s'), __('Menu')), array('action' => 'edit')); ?></li>
				<li><?php echo $this->Html->link(__('Recover hierarchy'), array('action' => 'recover')); ?></li>
			</ul>
		</div>
	</div>
</div>