<!-- Dual use of "fundation 6" and "fundation 5" -->
<?php
if(isset($max)){$paginator->setItemMaxPerPage($max);}else{$paginator->setItemMaxPerPage(5);}
if(isset($scrolling)){$paginator->setPageScrollingStyle($scrolling);}else{$paginator->setPageScrollingStyle('jumping');}
if(isset($range)){$paginator->setPageRangeSize($range);}else{$paginator->setPageRangeSize(5);}
?>
<nav aria-label="Pagination" class="pagination-centered">
<ul class="pagination text-center">
	<li class="pagination-previous arrow<?= $paginator->hasPreviousPage() ? '' : ' unavailable disabled' ?>">
		<?php if($paginator->hasPreviousPage()) : ?><a href="<?= $this->url()->fromRoute($route,null,array('query'=>array_merge($query,array('page' => $paginator->getPreviousPage())))) ?>"><?php endif ?>
			&laquo; Previous
		<?php if($paginator->hasPreviousPage()) : ?></a><?php endif ?>
	</li>
	<li>&hellip;</li>
<?php foreach($paginator->getPagesInRange() as $page) : ?>
	<li<?= ($paginator->getPage()==$page) ? ' class="current"' : '' ?>>
		<a href="<?= $this->url()->fromRoute($route, null,array('query'=>array_merge($query,array('page' => $page)))) ?>">
			<?=$page?>
		</a>
	</li>
<?php endforeach ?>
	<li>&hellip;</li>
	<li class="pagination-next arrow<?= $paginator->hasNextPage() ? '' : ' unavailable disabled' ?>">
		<?php if($paginator->hasNextPage()) : ?><a href="<?= $this->url()->fromRoute($route, null,array('query'=>array_merge($query,array('page' => $paginator->getNextPage())))) ?>"><?php endif ?>
			Next &raquo;
		<?php if($paginator->hasNextPage()) : ?></a><?php endif ?>
	</li>
</ul>
</nav>
