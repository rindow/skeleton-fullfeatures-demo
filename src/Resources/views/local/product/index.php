<?php $this->placeholder()->set('title','Form Demo') ?>
<div class="container grid-container mdl-layout__content">
<div class="row grid-x mdl-grid">
<div class="col-sm-2 columns cell small-12 medium-2 large-2 mdl-cell mdl-cell--2-col-desktop mdl-cell--2-col-tablet mdl-cell--12-col-phone">
  <?= $this->view('partial/nav',['title'=>'Form Demo','nav'=>[
      ['caption'=>'Product','route'=>$route['product']],
      ['caption'=>'Category','route'=>$route['category']],
      ['caption'=>'Protected','route'=>$route['protectedpage']],
      ['caption'=>'Api Demo','route'=>$route['apidemo']],
      ['caption'=>'Add New','route'=>$route['new']],
  ],'current'=>$route['product']]) ?>
</div>
<div class="col-sm-10 columns cell small-12 medium-10 large-10 mdl-cell mdl-cell--10-col-desktop mdl-cell--10-col-tablet mdl-cell--12-col-phone">
<aside>
  <div><?= $this->view('partial/alertbox',['messages'=>$flashMessages->get('notice')]) ?></div>
</aside>
<h1>Form Demo</h1>
<div>
<h3 style="display:inline;vertical-align:middle;">Product List</h3>
<a href="<?= $this->url()->fromRoute($route['new']) ?>">
<button type="button" class="btn btn-primary mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored">
  <i style="vertical-align:middle;" class="material-icons" data-feather="file-text">add</i>
</button>
</a>
</div>

<p>Total Items: <?= $items->getTotalItems() ?></p>
<?= $this->view($paginatorTemplate,array('paginator'=>$items,'route'=>$route['index'],'query'=>array())) ?>

<table class="table table-hover hover mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="width:100%">
<thead>
	<tr>
        <th class="mdl-data-table__cell--non-numeric">Id</th>
        <th class="mdl-data-table__cell--non-numeric">Name</th>
        <th class="mdl-data-table__cell--non-numeric">Category</th>
        <th class="mdl-data-table__cell--non-numeric">Color</th>
		<th class="mdl-data-table__cell--non-numeric" colspan="2">Action</th>
	</tr>
</thead>
<tbody>
<?php foreach($items as $item) : ?>
	<tr>
		<td class="mdl-data-table__cell--non-numeric"><?= $this->escape($item->id) ?></td>
        <td class="mdl-data-table__cell--non-numeric"><?= $this->escape($item->name) ?></td>
        <td class="mdl-data-table__cell--non-numeric"><?= $this->escape($item->category->name) ?></td>
		<td class="mdl-data-table__cell--non-numeric">
<?php foreach($item->colors as $color) : ?>
			<?= $colors[$color] ?>
<?php endforeach ?>
		</td>
		<td class="mdl-data-table__cell--non-numeric">
            <a class="button secondary tiny radius btn btn-sm btn-default mdl-button mdl-js-button mdl-button--raised" href="<?= $this->url()->fromRoute($route['edit'], ['id' => $item->id]) ?>">Edit</a>
		</td>
		<td class="mdl-data-table__cell--non-numeric">
            <a class="button secondary tiny radius btn btn-sm btn-default mdl-button mdl-js-button mdl-button--raised" href="<?= $this->url()->fromRoute($route['delete'], ['id' => $item->id]) ?>">Delete</a>
		</td>
	</tr>
<?php endforeach ?>
</tbody>
</table>

</div>
</div>
</div><!--class="container"-->
