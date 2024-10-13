document.addEventListener('DOMContentLoaded', function () {
    const uploadForm = document.querySelector('form');
    const fileInput = document.querySelector('input[type="file"]');

    uploadForm.addEventListener('submit', function (e) {
        const file = fileInput.files[0];
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!validTypes.includes(file.type)) {
            alert('Please upload a valid image file (JPEG, PNG, or GIF)');
            e.preventDefault();
        }

        if (file.size > 2 * 1024 * 1024) { // Maksimum ukuran file 2MB
            alert('File is too large. Maximum size is 2MB');
            e.preventDefault();
        }
    });
});
