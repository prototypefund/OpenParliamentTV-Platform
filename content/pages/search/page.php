<?php include_once(__DIR__ . '/../../header.php'); ?>
<main class="container-fluid">
	<?php include_once('content.filter.php'); ?>
	<div class="row m-0" style="position: relative; z-index: 1">
		<div id="speechListContainer" class="col">
			<div class="resultWrapper">
				<?php include_once('content.result.php'); ?>
			</div>
			<div class="loadingIndicator">
				<div class="workingSpinner" style="position: fixed; top: 65%;"></div>
			</div>
		</div>
	</div>
</main>
<?php include_once(__DIR__ . '/../../footer.php'); ?>
<script type="text/javascript" src="<?= $config["dir"]["root"] ?>/content/pages/search/client/search.js"></script>