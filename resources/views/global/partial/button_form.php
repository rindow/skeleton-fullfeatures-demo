<form action="<?= $this->url()->fromRoute($route) ?>" method="post">
	<?= isset($form['csrf_token']) ? $this->form()->widget($form['csrf_token']) : '' ?>
	<input type="hidden" name="id" value="<?= $id ?>">
	<input type="submit" value="<?= $text ?>" class="<?= $class ?>">
</form>
