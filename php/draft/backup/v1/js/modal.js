$(document).ready(function () {
    function showInputGroups(selectedOption) {
      $("#whoGotItGroup, #relationshipGroup, #passageDetailsGroup, #absenceDetailsGroup").hide();

      if (selectedOption === "TrustedPerson" || selectedOption === "FamilyOver18") {
        $("#whoGotItGroup, #relationshipGroup").show();
      } else if (selectedOption === "Absence") {
        $("#passageDetailsGroup, #absenceDetailsGroup").show();
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