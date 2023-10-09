document.getElementById('exportPdf').onclick = function () {
    var table = document.querySelector('table'); // Select your table
    var tableContent = '';

    var rowCounter = 0; // Initialize a row counter

    // Loop through table rows and cells to extract content
    table.querySelectorAll('tr').forEach(function (row) {
        var cells = row.querySelectorAll('td, th');
        for (var i = 0; i < cells.length - 3; i++) { // Skip the last cell (td or th)
            tableContent += cells[i].textContent + '<br>'; // Separate cells with line breaks
        }

        // Check if the current cell contains the signature file name
        var signatureFileName = cells[cells.length - 2].textContent; // Assuming the file name is in the cell
        console.log('signatureFileName', signatureFileName)
        var validImageExtensions = ["jpg", "jpeg", "png", "gif"]; // Add more extensions if needed
        var fileExtension = signatureFileName.split('.').pop().toLowerCase();

        if (signatureFileName && signatureFileName !== "" && validImageExtensions.includes(fileExtension)) {
            // Create an image element for the signature
            var signatureImage = document.createElement('img');
            var imagePath = './uploads/' + signatureFileName;
            signatureImage.src = imagePath; // Update the path accordingly
            signatureImage.style.maxWidth = '100px'; // Set the maximum width for the signature image

            // Add the signature image to the table content
            tableContent += signatureImage.outerHTML;
        }

        tableContent += '<br><br>'; // Separate rows with additional line breaks
        tableContent += '<hr>';
        rowCounter++; // Increment the row counter

        // Add a page break every 3 rows
        if (rowCounter % 3 === 0) {
            tableContent += '<div style="page-break-before: always;"></div>';
        }
    });

    // Create a new div element for the PDF content
    var pdfContent = document.createElement('div');

    // Add the table content to the PDF content
    pdfContent.innerHTML = tableContent;
    pdfContent.style.marginBottom = '50%';

    var timestamp = new Date().toISOString().replace(/[-:.]/g, '_'); // Format timestamp
    var opt = {
        margin: 0.5,
        filename: 'myfile_' + timestamp + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    // Generate PDF from the PDF content element
    html2pdf().from(pdfContent).set(opt).save();
}
