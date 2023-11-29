$(document).ready(function () {
    function showInputGroups(selectedOption) {
      $("#whoGotItGroup, #relationshipGroup, #passageDetailsGroup").hide();

      if (selectedOption === "TrustedPerson" || selectedOption === "FamilyOver18") {
        $("#whoGotItGroup, #relationshipGroup").show();
      } else if (selectedOption === "Absence") {
        $("#passageDetailsGroup").show();
      }
    }

    // Handle the change event of the statusOption select input
    $("#statusOption").change(function () {
      var selectedOption = $(this).val();
      showInputGroups(selectedOption);
    });

    // Show the relevant input group based on the initially selected option
    var selectedOption = $("#statusOption").val();
    showInputGroups(selectedOption);
  });



  //imgs max to 2
  document.getElementById('photos').addEventListener('change', function() {
    var fileCount = this.files.length;
    var maxFiles = 2; // Set the maximum number of files allowed

    // Update the hidden input with the file count
    document.getElementById('fileCount').value = fileCount;

    // Check if the file count exceeds the limit
    if (fileCount > maxFiles) {
        document.getElementById('fileError').textContent = 'You can only upload up to 2 files.';
        this.value = ''; // Clear the file input
    } else {
        document.getElementById('fileError').textContent = '';
    }
});