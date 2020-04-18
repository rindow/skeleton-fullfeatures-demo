<aside class="d-none d-sm-block hidden-xs hide-for-small-only mdl-cell--hide-tablet mdl-cell--hide-phone">
  <h6>
      <?= $this->escape($title) ?>
  </h6>
  <nav>
    <ul class="nav flex-column nav-pills nav-stacked vertical menu side-nav mdl-list">
      <?php foreach($nav as $link): ?>
      <li class="nav-item<?php if($link['route']==$current): ?> active<?php endif;?> mdl-list__item">
        <a class="nav-link<?php if($link['route']==$current): ?> active<?php endif;?> mdl-list__item-primary-content" href="<?= $this->url()->fromRoute($link['route']) ?>">
          <span class="<?php if($link['route']==$current): ?>mdl-button--primary<?php endif; ?>"><?= $this->escape($link['caption']) ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </nav>
</aside>
<aside class="d-block d-sm-none visible-xs-block show-for-small-only mdl-cell--hide-desktop">
  <span>
      <?= $this->escape($title) ?>
  </span>
  <nav>
    <ul class="nav nav-pills menu sub-nav mdl-navigation">
      <?php foreach($nav as $link): ?>
      <li class="nav-item<?php if($link['route']==$current): ?> active<?php endif;?> mdl-button mdl-js-button">
        <a class="nav-link<?php if($link['route']==$current): ?> active<?php endif;?>" style="text-decoration:none;" href="<?= $this->url()->fromRoute($link['route']) ?>">
          <span class="<?php if($link['route']==$current): ?>mdl-button--primary<?php endif; ?>"><?= $this->escape($link['caption']) ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </nav>
</aside>
