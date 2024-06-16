document.addEventListener("DOMContentLoaded", function() {
const narratives = document.querySelectorAll('.inline-ckeditor');

narratives.forEach(element => {
	InlineEditor
		.create(element, {
			toolbar: [
				'heading', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'
			],
			heading: {
				options: [
					{ model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
					{ model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
					{ model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
				]
			},
		})
		.then(editor => {
			editor.model.document.on('change:data', () => {
				autoSave(editor);
			});
		})
		.catch(error => {
			console.error(error);
		});
});
	function autoSave(editor) {
		const data = editor.getData('html');
		const element = editor.sourceElement;

		const narrativeId = element.getAttribute('data-kNarrative');
		const teacherId = element.getAttribute('data-kTeach');

		$.ajax({
			url: '/narrative/update_inline', // Change this to your save endpoint
			method: 'POST',
			data: {
				narrText: data,
				kNarrative: narrativeId,
				kTeach: teacherId,
				// Add any additional data here, such as CSRF tokens, IDs, etc.
			},
			success: function(response) {
				let statusElement = document.querySelector(`div[data-kNarrative="${narrativeId}"].status`);
				statusElement.innerHTML = JSON.parse(response)['datestamp'];
				statusElement.classList.add('fade-out');
				// Use a timeout to remove the class after the transition
					// Remove both classes after the transition completes
				setTimeout(() => {
					statusElement.classList.remove( 'fade-out');
				}, 2000); // 2000ms matches the duration of the transition

			},
			error: function(xhr, status, error) {
				console.error('Auto-save failed:', error);
			}
		});
	}
});