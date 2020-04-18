<?php $paginator->setPageRangeSize(3) ?>
<?php $paginator->setPageScrollingStyle('jumping') ?>
<div class="mdl-grid">
<div style="margin-left:auto">
<?php foreach($paginator->getPagesInRange() as $page) : ?>
    <a href="<?= $this->url()->fromRoute($route, null,array('query'=>array_merge($query,array('page' => $page)))) ?>" style="text-decoration:none;<?= ($paginator->getPage() == $page) ? ' pointer-events: none;':''?>"<?= ($paginator->getPage() == $page) ? ' disabled':''?>>
	<button class="mdl-button mdl-js-button mdl-button--icon"<?= ($paginator->getPage()==$page) ? ' disabled' : '' ?>>
		<?= $this->escape($page) ?>
	</button>
    </a>
<?php endforeach ?>
    <a href="<?= $this->url()->fromRoute($route,null,array('query'=>array_merge($query,array('page' => $paginator->getPreviousPage())))) ?>" style="text-decoration:none;<?= ($paginator->hasPreviousPage()) ? '':' pointer-events: none;'?>"<?= ($paginator->hasPreviousPage()) ? '':' disabled'?>>
	<button class="mdl-button mdl-js-button mdl-button--icon"<?= $paginator->hasPreviousPage() ? '' : ' disabled' ?>>
	    <i class="material-icons">navigate_before</i>
	</button>
    </a>
    <a href="<?= $this->url()->fromRoute($route, null,array('query'=>array_merge($query,array('page' => $paginator->getNextPage())))) ?>" style="text-decoration:none;<?= ($paginator->hasNextPage()) ? '':' pointer-events: none;'?>"<?= ($paginator->hasNextPage()) ? '':' disabled'?>>
	<button class="mdl-button mdl-js-button mdl-button--icon"<?= $paginator->hasNextPage() ? '' : ' disabled' ?>>
	    <i class="material-icons">navigate_next</i>
	</button>
    </a>
</div>
</div>
