<?php $this->placeholder()->set('title',($new?'New':'Edit').' Product') ?>
<div class="container grid-container mdl-layout__content">
<div class="row grid-x mdl-grid">
<div class="col-sm-2 columns cell small-12 medium-2 large-2 mdl-cell mdl-cell--2-col">
  <?= $this->view('partial/nav',['title'=>'Form Demo','nav'=>[
      ['caption'=>'Product','route'=>$route['product']],
      ['caption'=>'Category','route'=>$route['category']],
      ['caption'=>'Protected','route'=>$route['protectedpage']],
      ['caption'=>'Api Demo','route'=>$route['apidemo']],
      ['caption'=>'Add New','route'=>$route['new']],
  ],'current'=>$route['new']]) ?>
</div>
<div class="col-sm-10 columns cell small-12 medium-10 large-10 mdl-cell mdl-cell--10-col">
<aside>
  <div><?= $this->view('partial/alertbox',array('messages'=>$flashMessages->get('notice'))) ?></div>
</aside>
<h3><?= ($new?'New':'Edit')?> Product</h3>
<?php $this->form()->addElement($form,'submit','update',($new?'Add':'Update'),' ') ?>
<?= $this->form()->raw($form) ?>
</div>
</div>
</div><!--class="container"-->
