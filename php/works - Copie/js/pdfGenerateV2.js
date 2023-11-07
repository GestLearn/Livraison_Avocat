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
    $('#table tbody').on('click', '.download-btn', async function () {
        var rowData = table.row($(this).parents('tr')).data(); // Get the data of the clicked row
        await generatePdfForRow(rowData); // Generate a PDF for the clicked row data
    });




    async function generatePdfForRow(rowData) {
        var tableHeaders = table.columns().header().toArray(); // Get an array of table headers

        // Create a new PDF document
        const { PDFDocument, rgb, StandardFonts, degrees } = PDFLib;
        const pdfDoc = await PDFDocument.create();
        const page = pdfDoc.addPage([600, 400]);
    
        // Define font and font size for text
        const font = await pdfDoc.embedFont(StandardFonts.Helvetica);
        const fontSize = 14;
    
        // Y-coordinate for positioning text
        let y = page.getHeight() - 50;
    
        // Check if the signature filename is available (assuming the column name is "signatureFileName")
        var signatureFileName = rowData[rowData.length - 2]; // Assuming the last column contains the signature filename
    
        // Check if the signatureFileName is a valid image file (e.g., has an image file extension)
        var validImageExtensions = ["jpg", "jpeg", "png", "gif"]; // Add more extensions if needed
        var fileExtension = signatureFileName.split('.').pop().toLowerCase();
    
        if (signatureFileName && signatureFileName !== "" && validImageExtensions.includes(fileExtension)) {
            // Load the signature image
            const imageBytes = await fetch('./uploads/' + signatureFileName).then((res) => res.arrayBuffer());
    
            // Embed the image as an XObject
            const image = await pdfDoc.embedPng(imageBytes);
    
            // Calculate image dimensions and position
            const imageWidth = 200; // Adjust the width as needed
            const imageHeight = (imageWidth / image.width) * image.height;
            const imageX = page.getWidth() - imageWidth - 50; // Adjust the X-coordinate as needed
            const imageY = 50; // Adjust the Y-coordinate as needed
    
            // Draw the image onto the PDF page
            page.drawImage(image, {
                x: imageX,
                y: imageY,
                width: imageWidth,
                height: imageHeight,
                rotate: degrees(0), // Adjust the rotation angle as needed
            });
        }
    
        var tableHeaders = table.columns().header().toArray();
        const lastItem = rowData[rowData.length - 3]; // Get the last item
        console.log("lastItem: ",lastItem,"-----includes", lastItem.includes('Set Status for the second passage'))
        // Check the content of the last item and modify it accordingly
        if (lastItem.includes('Set Status for the second passage')) {
            // Set it as Absence Passage 1
            rowData[rowData.length - 3] = 'Absence Passage 1';
        } else if (lastItem.includes('Set Status for the third passage')) {
            // Set it as Absence Passage 2
            rowData[rowData.length - 3] = 'Absence Passage 2';
        } else if(lastItem.includes('Set Status here')){
            rowData[rowData.length - 3] = 'Not delivered';
        }
        // Combine table headers and table data before creating the PDF content
        for (var i = 0; i < rowData.length - 2; i++) {
            const text = tableHeaders[i].textContent + ': ' + rowData[i];
            
            // Add space between content elements
            y -= fontSize * 1.2; // Adjust the line height as needed
    
            // Add the text to the page
            page.drawText(text, {
                x: 50, // Adjust the X-coordinate as needed
                y: y,
                size: fontSize,
                font: font,
                color: rgb(0, 0, 0),
            });
        }
    
        // Create a PDF bytes array
        const pdfBytes = await pdfDoc.save();
    
        // Create a Blob containing the PDF data
        const pdfBlob = new Blob([pdfBytes], { type: 'application/pdf' });
    
        // Create a URL for the Blob
        const pdfUrl = URL.createObjectURL(pdfBlob);
    
        // Create a link for downloading the PDF
        var a = document.createElement('a');
        a.href = pdfUrl;
        a.download = 'downloaded.pdf'; // Change the filename as needed
        a.style.display = 'none';
        document.body.appendChild(a);
    
        // Trigger a click event to download the PDF
        a.click();
    
        // Clean up
        URL.revokeObjectURL(pdfUrl);
        document.body.removeChild(a);
    }
    
});