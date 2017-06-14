tinymce.init({ selector:'textarea.tinymce',
	menubar: false,
	min_height:400,
	block_formats:'Paragraph=p;Heading=h3;Subhead=h4;Section=h5;Subsection=h6',
	toolbar: 'bold,italic,formatselect,bullist,numlist,blockquote,|,cut,copy,paste,pastetext,pasteword,removeformat,code,|,undo,redo,searchreplace',
	invalid_styles: 'color font-size font-family line-height font-weight',
	plugins: 'paste,code,wordcount,lists,searchreplace',
	invalid_elements: 'div,font,a,html,head,body',
	setup: function (editor) {
	        editor.on('change', function () {
	            editor.save();
	        });
	    },
	    browser_spellcheck: true,
	    contextmenu: false

	});