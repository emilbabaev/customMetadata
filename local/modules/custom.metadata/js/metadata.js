document.addEventListener('DOMContentLoaded', function () {
    const saveButton = document.getElementById('metadata-save');
    if (saveButton) {
        saveButton.addEventListener('click', function () {
            const title = document.getElementById('metadata-title').value;
            const description = document.getElementById('metadata-description').value;
            const url = window.location.pathname;

            var request = BX.ajax.runAction('custom:metadata.api.metadata.save', {
                data: {
                    url: url,
                    title: title,
                    description: description
                }
            });

            request.then(function (response) {
                alert(response.data.status === 'success' ? 'Метаданные сохранены' : 'Ошибка: ' + response.data.message);
            });
        });
    }
});