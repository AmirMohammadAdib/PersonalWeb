</div>
</div>
<!-- /Right-bar -->

</div>
<!-- END wrapper -->



<script>
	var resizefunc = [];
</script>

<!-- ckEditor -->
<script src="../../ckeditor5/build/ckeditor.js"></script>
<script>
	ClassicEditor
		.create(document.querySelector('.editor'), {

			toolbar: {
				items: [
					'heading',
					'|',
					'bold',
					'italic',
					'link',
					'bulletedList',
					'numberedList',
					'|',
					'outdent',
					'indent',
					'|',
					'imageUpload',
					'blockQuote',
					'insertTable',
					'mediaEmbed',
					'undo',
					'redo',
					'code',
					'codeBlock',
					'fontBackgroundColor',
					'fontColor',
					'fontSize',
					'highlight'
				]
			},
			language: 'fa',
			image: {
				toolbar: [
					'imageTextAlternative',
					'imageStyle:full',
					'imageStyle:side',
					'linkImage'
				]
			},
			table: {
				contentToolbar: [
					'tableColumn',
					'tableRow',
					'mergeTableCells'
				]
			},
			licenseKey: '',


		})
		.then(editor => {
			window.editor = editor;








		})
		.catch(error => {
			console.error('Oops, something went wrong!');
			console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
			console.warn('Build id: fotmady28o1t-fx1wlfayz8ed');
			console.error(error);
		});
</script>
<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap-rtl.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>

<!-- KNOB JS -->
<!--[if IE]>
        <script type="text/javascript" src="assets/plugins/jquery-knob/excanvas.js"></script>
        <![endif]-->
<script src="assets/plugins/jquery-knob/jquery.knob.js"></script>

<!--Morris Chart-->
<script src="assets/plugins/morris/morris.min.js"></script>
<script src="assets/plugins/raphael/raphael-min.js"></script>

<!-- Dashboard init -->
<script src="assets/pages/jquery.dashboard.js"></script>

<!-- App js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


</body>