<?php $this->placeholder()->set('title','Protected Page') ?>
<div class="container grid-container mdl-layout__content">
<div class="row grid-x mdl-grid">
<div class="col-sm-2 columns cell small-12 medium-2 large-2 mdl-cell mdl-cell--2-col">
  <?= $this->view('partial/nav',['title'=>'Protected Demo','nav'=>[
      ['caption'=>'Public','route'=>$route['publicpage']],
      ['caption'=>'Protected','route'=>$route['protectedpage']],
      ['caption'=>'Fully Auth','route'=>$route['fullauthpage']],
      ['caption'=>'Form','route'=>$route['product']],
      ['caption'=>'Api Demo','route'=>$route['apidemo']],
  ],'current'=>$route['publicpage']]) ?>
</div>
<div class="col-sm-10 columns cell small-12 medium-10 large-10 mdl-cell mdl-cell--10-col">
<aside>
  <div><?= $this->view('partial/alertbox',['messages'=>$flashMessages->get('notice')]) ?></div>
</aside>
<h1>Public/Protected Page demo</h1>
<h3>Setup users</h3>
<pre>
    $ bin/myapp user-add -p password test@test.com
</pre>
<h3>Login</h3>
Click to "protected" menu and Login.
</div>
</div>
</div><!--class="container"-->
