let narrativeEditor;
document.addEventListener("DOMContentLoaded", function() {
  const textEditors = document.querySelectorAll('.ckeditor');
  textEditors.forEach(textEditor => {

    ClassicEditor
      .create(textEditor, {
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
          autosave: {
            save(textEditor) {
              // The saveData() function must return a promise
              // which should be resolved when the data is successfully saved.
              if (textEditor.classList.contains('auto-save')) {
                setInterval(function () {
                  if (textEditor.dataset.target === 'narrative') {
                    save_continue_narrative();
                  } else if (textEditor.dataset.target === 'template') {
                    save_continue_template();
                  }
                }, 10000); // Auto-save every 30 seconds
              }
            }
          }
        }
      )
      .then(editor => {
        window.editor = editor;
      })
      .catch(handleSampleError);
  });
  function handleSampleError(error) {

    const message = [
      'Oops, something went wrong!',
    ].join('\n');

    console.error(message);
    console.error(error);
  }
});