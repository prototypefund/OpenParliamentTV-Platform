<?php include_once(__DIR__ . '/../../header.php'); ?>
<main class="container subpage">
	<div class="row" style="position: relative; z-index: 1">
		<div class="col-12">
			<h2>Detail View Person</h2>

            <?php

            include_once (__DIR__."/../../../api/v1/api.php");
            $item = apiV1("person",$_REQUEST["id"]);
            print_r($item);

            ?>

		</div>
	</div>
</main>
<?php include_once(__DIR__ . '/../../footer.php'); ?>