document.getElementById('exportPdf').onclick = async function () {
    const { PDFDocument, rgb, StandardFonts, degrees } = PDFLib;

    // Create a new PDF document
    const pdfDoc = await PDFDocument.create();
    let pageHeight = 650; // Initial page height
    let currentPage = pdfDoc.addPage([650, pageHeight]); // Initialize the first page
    let y = currentPage.getHeight() - 50; // Initialize Y-coordinate for positioning text

    // Define font and font size for text
    const font = await pdfDoc.embedFont(StandardFonts.Helvetica);
    const fontSize = 13;

    var table = document.querySelector('table'); // Select your table
    var rowCounter = 0; // Initialize a row counter
    var rows = table.querySelectorAll('tr');

    // Remove the first row from the NodeList and store it separately
    var firstRow = rows[0];
    var headerContentArray = [];

    // Get all the table headers in the first row
    var thElements = firstRow.querySelectorAll('th');

    thElements.forEach(function (th) {
        var headerContent = th.textContent;
        headerContentArray.push(headerContent);
    });
    rows = Array.prototype.slice.call(rows, 1);

    // Loop through the table rows
    for (const row of rows) {
        var cells = row.querySelectorAll('td, th');
        let lineHeight = fontSize * 1.2; // Line height
        let cellHeight = lineHeight; // Height of the current cell's content

        const lastCell = cells[cells.length - 3]; // Get the last cell
        const lastItem = lastCell.textContent.trim(); // Get the text content of the last cell

        console.log("lastItem: ", lastItem);

        // Check the content of the last item and modify it accordingly
        if (lastItem.includes('Set Status for the second passage')) {
            // Set it as Absence Passage 1
            lastCell.textContent = 'Absence Passage 1';
        } else if (lastItem.includes('Set Status for the third passage')) {
            // Set it as Absence Passage 2
            lastCell.textContent = 'Absence Passage 2';
        } else if (lastItem.includes('Set Status here')) {
            console.log("----------------------------------------");
            lastCell.textContent = 'Not delivered';
        }

        for (var i = 0; i < cells.length - 2; i++) { // Skip the last cell (td or th)
            const text = headerContentArray[i] + ' : ' + cells[i].textContent.replace(/\n/g, ''); // Remove newline characters

            // Calculate the required height for the current cell's content
            const textWidth = font.widthOfTextAtSize(text, fontSize);
            const cellWidth = 500; // Adjust the width as needed
            const lines = Math.ceil(textWidth / cellWidth);
            cellHeight += lines * lineHeight;

            // Check if the cell content exceeds the current page height
            if (cellHeight > pageHeight) {
                // Create a new page with the same size
                currentPage = pdfDoc.addPage([650, pageHeight]);
                y = currentPage.getHeight() - 50; // Reset Y-coordinate for the new page
            }

            // Add the text to the current page
            currentPage.drawText(text, {
                x: 50, // Adjust the X-coordinate as needed
                y: y,
                size: fontSize,
                font: font,
                color: rgb(0, 0, 0),
            });
            y -= lineHeight; // Adjust Y-coordinate for the next line
        }

        // Check if the current cell contains the signature file name
        var signatureFileName = cells[cells.length - 2].textContent.trim(); // Assuming the file name is in the cell
        console.log("signatureFileName:", signatureFileName)
         var validImageExtensions = ["jpg", "jpeg", "png", "gif"]; // Add more extensions if needed
        var fileExtension = signatureFileName.split('.').pop().toLowerCase();
        console.log("fileExtension:", fileExtension)
        console.log("validImageExtensions:", validImageExtensions.includes(fileExtension))
        console.log("signatureFileName !== ''", signatureFileName !== "")
        if (signatureFileName && signatureFileName !== "" && validImageExtensions.includes(fileExtension)) {
            console.log("enter if // Load the signature image")
            // Load the signature image
            
            const res = await fetch('./uploads/' + signatureFileName);
            // console.log("res await")
            const imageData = await res.arrayBuffer();

            // Embed the image as an XObject
            const image = await pdfDoc.embedPng(imageData);

            // Calculate image dimensions and position
            const imageWidth = 100; // Adjust the width as needed
            const imageHeight = (imageWidth / image.width) * image.height;

            // Check if the image exceeds the current page height
            if (y - imageHeight < 50) {
                // Create a new page with the same size
                currentPage = pdfDoc.addPage([650, pageHeight]);
                y = currentPage.getHeight() - 50; // Reset Y-coordinate for the new page
            }

            // Draw the image onto the current page
            currentPage.drawImage(image, {
                x: 50, // Adjust the X-coordinate as needed
                y: y - imageHeight,
                width: imageWidth,
                height: imageHeight,
                rotate: degrees(0), // Adjust the rotation angle as needed
            });
            y -= imageHeight; // Adjust Y-coordinate for the next content
        }else{
            console.log("not enter if // no Load the signature image")
        }

        // Draw a line after each row
        const lineWidth = 500; // Adjust the line width as needed
        currentPage.drawLine({
            start: { x: 50, y: y + 10 }, // Adjust the Y-coordinate and position as needed
            end: { x: 50 + lineWidth, y: y + 10 }, // Adjust the Y-coordinate and position as needed
            thickness: 1, // Adjust the line thickness as needed
            color: rgb(0, 0, 0), // Adjust the line color as needed
        });

        y -= lineHeight; // Adjust Y-coordinate for the next row
        rowCounter++; // Increment the row counter

        // Create a new page every 2 rows
        if (rowCounter % 2 === 0) {
            currentPage = pdfDoc.addPage([650, pageHeight]);
            y = currentPage.getHeight() - 50; // Reset Y-coordinate for the new page
        }
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
    a.download = 'table.pdf'; // Change the filename as needed
    a.style.display = 'none';
    document.body.appendChild(a);

    // Trigger a click event to download the PDF
    a.click();

    // Clean up
    URL.revokeObjectURL(pdfUrl);
    document.body.removeChild(a);
};
