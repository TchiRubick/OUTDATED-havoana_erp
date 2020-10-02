<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<?php $this->view('partials/header') ?>

<body>
	<?php $this->view('partials/menu') ?>
	<?php $this->view('pages/' . $page ) ?>
</body>

<?php $this->view('partials/footer') ?>
