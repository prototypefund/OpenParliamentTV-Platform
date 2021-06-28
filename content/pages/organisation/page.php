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
							<img src="<?= $apiResult["data"]["attributes"]["thumbnailURI"] ?>" alt="..." class="img-fluid" style="position: absolute; top: 50%; transform: translateY(-50%) translateX(-50%);left: 50%;width: 80%;">
						</div>
					</div>
					<div class="col-6 col-md-9 col-lg-10">
						<h2><?= $apiResult["data"]["attributes"]["labelAlternative"] ?></h2>
						<div><?= $apiResult["data"]["attributes"]["label"] ?></div>
						<div><?= $apiResult["data"]["attributes"]["abstract"] ?></div>
						<a href="<?= $apiResult["data"]["attributes"]["websiteURI"] ?>"><?= $apiResult["data"]["attributes"]["websiteURI"] ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<ul class="nav nav-tabs transparent" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="media-tab" data-toggle="tab" href="#media" role="tab" aria-controls="media" aria-selected="true"><span class="icon-hypervideo"></span> Related Media</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="people-tab" data-toggle="tab" href="#people" role="tab" aria-controls="people" aria-selected="false"><span class="icon-torso"></span> People</a>
				</li>
			</ul>
			<div class="tab-content transparent">
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
				<div class="tab-pane fade" id="people" role="tabpanel" aria-labelledby="people-tab">
					[CONTENT]
				</div>
			</div>
		</div>
	</div>
</main>
<?php include_once(__DIR__ . '/../../footer.php'); ?>
<script type="text/javascript" src="<?= $config["dir"]["root"] ?>/content/client/js/searchResults.js"></script>
<script type="text/javascript">
	$(document).ready( function() {
		updateMediaList("organisationID=<?= $apiResult["data"]["id"] ?>");
	});
</script>