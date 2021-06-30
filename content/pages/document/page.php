<?php 
include_once(__DIR__ . '/../../header.php'); 
?>
<main class="container subpage">
	<div class="detailsHeader">
		<div class="row" style="position: relative; z-index: 1">
			<div class="col-12">
				<div class="row align-items-center">
					<div class="col-4 col-md-3 col-lg-2">
						<div class="rounded-circle">
							<span class="icon-doc-text" style="position: absolute;top: 50%;left: 50%;font-size: 70px;transform: translateX(-50%) translateY(-50%);"></span>
						</div>
					</div>
					<div class="col-6 col-md-9 col-lg-10">
						<h2><?= $apiResult["data"]["attributes"]["label"] ?></h2>
						<?= $apiResult["data"]["attributes"]["labelAlternative"] ?>
						<div>Source:</div>
						<a target="_blank" href="<?= $apiResult["data"]["attributes"]["sourceURI"] ?>"><?= $apiResult["data"]["attributes"]["sourceURI"] ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="media-tab" data-toggle="tab" href="#media" role="tab" aria-controls="media" aria-selected="true"><span class="icon-hypervideo"></span> Related Media</a>
				</li>
				<li class="nav-item ml-auto">
					<a class="nav-link" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data" aria-selected="true"><span class="icon-download"></span> Data</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade show active" id="media" role="tabpanel" aria-labelledby="media-tab">
					<div id="speechListContainer">
						<div class="resultWrapper">
							<?php include_once('content.result.php'); ?>
						</div>
						<div class="loadingIndicator">
							<div class="workingSpinner" style="position: fixed; top: 65%;"></div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade bg-white" id="data" role="tabpanel" aria-labelledby="data-tab">
					[ITEM DATA]
				</div>
			</div>
		</div>
	</div>
</main>
<?php include_once(__DIR__ . '/../../footer.php'); ?>
<script type="text/javascript" src="<?= $config["dir"]["root"] ?>/content/client/js/searchResults.js"></script>
<script type="text/javascript">
	$(document).ready( function() {
		updateMediaList("documentID=<?= $apiResult["data"]["id"] ?>");
	});
</script>