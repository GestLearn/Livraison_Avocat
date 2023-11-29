<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require '../controllers/config.php'; 
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Set Status</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <script src="https://unpkg.com/pdf-lib@1.4.0/dist/pdf-lib.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    
    
    <?php


    // Check if the ID parameter is set in the URL
    if (isset($_GET['id'])) {
        $pkg_id = $_GET['id'];
    } else {
        // Handle the case where the ID is not provided.
        echo "Invalid request: ID parameter not provided.";
        exit;
    }
    

    function getStatusData($pkg_id) {
        global $conn;
        $sql = "SELECT * FROM status WHERE pkg_info_id = $pkg_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Fetch the data (assuming you have only one row per pkg_id)
            $row = $result->fetch_assoc();
            return $row; // Return the status data as an associative array
        } else {
            return null; // Status data not found
        }
    }

    $statusData = getStatusData($pkg_id);

    if ($statusData) {
        $statusOption = $statusData["statusOption"];

        if ($statusOption === "Absence") {
            $passage1 = $statusData["passage1"];
            $passage2 = $statusData["passage2"];
            $passage3 = $statusData["passage3"];
        }
    }
    ?>
    
    <div class="container">
        <h1>Set Status</h1>
        <?php
            // Check if the ID parameter is set in the URL
            if (isset($_GET['id'])) {
                $pkg_id = $_GET['id'];
            } else {
                // Handle the case where the ID is not provided.
                echo "Invalid request: ID parameter not provided.";
                exit;
            }
        ?>
        <form action="/controllers/process_status.php" method="post"  enctype="multipart/form-data">
          
            
              
              
            <div class="form-group">
                <label for="statusOption">Status Option:</label>
                <select class="form-control" id="statusOption" name="statusOption">
                <option value="Hand-delivered">Hand-delivered</option>
                <option value="TrustedPerson">Passed on to a trusted person in the company</option>
                <option value="FamilyOver18">Passed on to a Family over 18</option>
                <option value="Absence">Absence</option>
                </select>
            </div>

            <div class="form-group" id="whoGotItGroup" style="display: none;">
                <label for="whoGotIt">Name of Who Got It:</label>
                <input type="text" class="form-control" id="whoGotIt" name="whoGotIt" placeholder="Enter name">
            </div>

            <div class="form-group" id="relationshipGroup" style="display: none;">
                <label for="relationship">Relationship:</label>
                <input type="text" class="form-control" id="relationship" name="relationship"
                placeholder="Enter relationship">
            </div>

            <div class="form-group" id="passageDetailsGroup" style="display: none;">
                <label>Passage Details:</label>
                <?php if ($passage1) { ?>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="passage1" name="passage1" value="Passage 1" checked>
                        <label class="form-check-label">Passage 1: Date + time</label>
                    </div>
                <?php } else { ?>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="passage1" name="passage1" value="Passage 1">
                        <label class="form-check-label">Passage 1: Date + time</label>
                    </div>
                <?php } ?>

                <?php if ($passage2) { ?>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="passage2" name="passage2" value="Passage 2" checked> 
                        <label class="form-check-label">Passage 2: Date + time</label>
                    </div>
                <?php } else { ?>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="passage2" name="passage2" value="Passage 2">
                        <label class="form-check-label">Passage 2: Date + time</label>
                    </div>
                <?php } ?>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="passage3" name="passage3" value="Passage 3">
                    <label class="form-check-label">Passage 3: Date + time</label>
                </div>
            </div>


            <div class="form-group" id="absenceDetailsGroup">
                <label for="photos">Hanging (attach photos):</label>
                <input type="file" class="form-control-file" id="photos" name="photos[]" accept="image/*" multiple>
                <input type="hidden" name="fileCount" id="fileCount" value="0">
                <textarea class="form-control" id="letterAddress" name="letterAddress" placeholder="Letter + door address"></textarea>
                <div id="fileError" style="color: red;"></div>
            </div>



            <div class="form-group"  style="display: ;">
                <label for="id">ID:</label>
                <input type="text" class="form-control" id="pkg_id" name="pkg_id" value="<?php echo $pkg_id; ?>" readonly>
            </div>
        
            
            <button type="submit" class="btn btn-primary">Save</button>
            
            
         
        </form>
    </div>

    <script src="../public/js/modal.js"></script>
</body>

</html>
