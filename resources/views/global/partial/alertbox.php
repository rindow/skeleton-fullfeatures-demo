<div id="rindow-mdl-alertbox-content" class="rindow-mdl-noshow">
  <?php foreach ($messages as $message): ?>
    <div data-alert data-closable class="alert alert-success alert-dismissible show alert-box success callout" role="alert">
        <?= $this->escape($message) ?>
        <button data-close type="button" class="close close-button" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
  <?php endforeach ?>
</div>
