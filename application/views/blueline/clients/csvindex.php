<style>
pre {
	color: #B1E3FC;
    background-color: #333;
}
</style>
<div class="col-sm-12  col-md-12 main">
		<div class="row">
             <br>

            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
				<div class="bs-callout bs-callout-default">
	                <h4>Error</h4>
	                <p style="font-size: 12px;">Please notify us at by <a href="mailto:marketing@avisorsacademy.com">clicking here</a> and please attach the CSV file used when this error occured.</p>
	            </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success') == TRUE): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>

        </div>
</div>
