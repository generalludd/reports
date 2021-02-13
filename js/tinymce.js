tinymce.init({
	selector: "textarea.tinymce",
	plugins: [
		'advlist lists paste code help wordcount'
	],
	toolbar: 'undo redo | ' +
'bold italic | bullist numlist | ' +
'removeformat | help',
	menubar: false,
	browser_spellcheck: true,
});

