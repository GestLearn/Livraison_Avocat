$(document).ready(function () {
    var table = $('#table').DataTable({
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                $('input', this.header()).on('keyup change', function () {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                });
            });
        }
    });

    // Add a click event listener to the "Download" button in each row
    $('#table tbody').on('click', '.download-btn', function () {
        var rowData = table.row($(this).parents('tr')).data(); // Get the data of the clicked row
        generatePdfForRow(rowData); // Generate a PDF for the clicked row data
    });

    function generatePdfForRow(rowData) {
        var tableHeaders = table.columns().header().toArray(); // Get an array of table headers

        // Create a new div element for the PDF content
        var pdfContent = document.createElement('div');

        // Create a new div element for the footer
        var footerDiv = document.createElement('div');
        footerDiv.style.position = 'absolute';
        footerDiv.style.bottom = '20px'; // Adjust the distance from the bottom as needed
        footerDiv.style.left = '0';
        footerDiv.style.width = '100%';
        footerDiv.style.textAlign = 'center';

        // Check if the signature filename is available (assuming the column name is "signatureFileName")
        var signatureFileName = rowData[rowData.length - 2]; // Assuming the last column contains the signature filename

        // Check if the signatureFileName is a valid image file (e.g., has an image file extension)
        var validImageExtensions = ["jpg", "jpeg", "png", "gif"]; // Add more extensions if needed
        var fileExtension = signatureFileName.split('.').pop().toLowerCase();

        if (signatureFileName && signatureFileName !== "" && validImageExtensions.includes(fileExtension)) {
            // Create an image element for the signature
            var signatureImage = document.createElement('img');
            var imagePath = './uploads/' + signatureFileName;
            signatureImage.src = imagePath; // Update the path accordingly
            signatureImage.style.maxWidth = '300px'; // Set the maximum width for the signature image

            // Add the signature image to the footer div and align it to the right
            signatureImage.style.float = 'right';
            footerDiv.appendChild(signatureImage);
        }

        // Combine table headers and table data before creating the PDF content
        var rowContent = "";
        for (var i = 0; i < rowData.length - 3; i++) {
            rowContent += tableHeaders[i].textContent + ': ' + rowData[i] + '\n';
            
            // Add space between content elements
            rowContent += '<br>';
        }

        // Add the row content to the PDF content
        pdfContent.innerHTML += rowContent;

        // Add the footer div to the PDF content
        pdfContent.appendChild(footerDiv);

        // Set explicit dimensions for the pdfContent element (letter-sized page in portrait orientation)
        pdfContent.style.width = '8.5in';
        pdfContent.style.height = '7in';

        var opt = {
            margin: 1,
            filename: 'row_data.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        // Generate PDF from the PDF content element
        html2pdf().from(pdfContent).set(opt).save();
    }

});