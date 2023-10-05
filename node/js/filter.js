// import { jsPDF } from "../../jspdf";

$(document).ready(function(){
    $('table#table tfoot th').each( function (){
      var title = $(this).text();
      $(this).html('<input type="text" placeholder="Search '+title+'" />');
    });

    var table = $('#table').DataTable({
      initComplete: function (){
        this.api().columns().every( function (){
          var that = this;
          $('input', this.footer()).on('keyup change clear', function(){
            if(that.search()!==this.value){
              that.search(this.value).draw();
            }
          });
        });
      }
    });

    // // Function to generate a PDF from selected rows
    // $('#generatePDF').on('click', function() {
    //   var selectedRows = [];
    //   $('.row-checkbox:checked').each(function() {
    //     var $row = $(this).closest('tr');
    //     var rowData = table.row($row).data();
    //     selectedRows.push(rowData);
    //   });

    //   if (selectedRows.length > 0) {
    //     generatePDF(selectedRows);
    //   } else {
    //     alert('No rows selected for PDF generation.');
    //   }
    // });

    // // Function to generate a PDF using jsPDF
    // function generatePDF(rows) {
    //   var doc = new jsPDF();
    //   doc.text('Selected Rows', 10, 10);

    //   var y = 20;
    //   rows.forEach(function(row) {
    //     y += 10;
    //     doc.text('Project Name: ' + row[1], 10, y); // Assuming project name is in the second column (index 1)
    //     y += 10;
    //     doc.text('First Name: ' + row[2], 10, y); // Assuming first name is in the third column (index 2)
    //     // Add more fields as needed
    //   });

    //   doc.save('selected_rows.pdf');
    // }
});