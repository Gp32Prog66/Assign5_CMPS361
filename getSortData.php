<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="styles.css">
<title>Assignment 5</title>

<div class="center">

<h1>Music Selection</h1>

    <?php
    $API_URL = "http://localhost:8006/api/v1/selection";

    //Adding CSS
    

    //Fetching Data
    $response = file_get_contents($API_URL);

    //Decode JSON
    $data = json_decode($response, true);


    //Validating that Data Exists
    if ($data && is_array($data)) {
        //Building Table
    
        //Pagination
        $limit = 10;
        $totalRecords = count($data);
        $totalPages = ceil($totalRecords / $limit); //Calculation to Set Number of Pages Per Record
    
        //Capture Current Page. Also sets default page
    
        //Could be causing issues
    
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1; //P instead of p
    
        //Calculate Starting Index of the Current Page
        if ($currentPage < 1) {
            $currentPage = 1;
        } elseif ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }


        //Sort Logic
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id'; // Default sort by 'id'
        $sortMusic = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'desc' : 'asc'; // Default order is 'asc'
    
        // Sort the data based on column and music
        usort($data, function ($a, $b) use ($sortColumn, $sortMusic) {
            if ($sortMusic == 'asc') {
                return strcmp($a[$sortColumn], $b[$sortColumn]);
            } else {
                return strcmp($b[$sortColumn], $a[$sortColumn]);
            }
        });

        $starIndex = ($currentPage - 1) * $limit;
        $pageData = array_slice($data, $starIndex, $limit);

        function toggleMusic($selectMusic)
        {
            return $selectMusic == 'asc' ? 'desc' : 'asc';
        }



        // <thead> not <thread>
        //echo "<thread>";
    
        // Function to toggle sort order
    


        //Basic HTML GridVieww
        echo "<table border='1' cellpadding='10'>";
        echo "<thead>";

        echo "<tr>";
        echo "<th><a href='?page=$currentPage&sort=id&order=" . toggleMusic($sortMusic) . "'>id</a></th>";
        echo "<th><a href='?page=$currentPage&sort=artist&order=" . toggleMusic($sortMusic) . "'>artist</a></th>";
        echo "<th><a href='?page=$currentPage&sort=album&order=" . toggleMusic($sortMusic) . "'>album</a></th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";


        //Loop Through The Data
        foreach ($pageData as $post) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($post['id']) . "</td>";
            echo "<td>" . htmlspecialchars($post['artist']) . "</td>";
            echo "<td>" . htmlspecialchars($post['album']) . "</td>";
        }

        echo "</tbody>";
        echo "</table>";

        echo "<div style='margin-top: 20px;'>";

        //Display Previous link if not on first page
        if ($currentPage > 1) {
            echo '<a href=?page=' . ($currentPage - 1) . '&sort=' . $sortColumn . '&order=' . $sortMusic . '">Previous</a> ';
        }

        //Displaying Page Numbers
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                echo "<strong> $i </strong";
            } else {
                echo '<a href=?page' . $i . '&sort=' . $sortColumn . '&order=' . $sortMusic . '">' . $i . '</a> ';
            }

            //Next Page
            if ($currentPage < $totalPages) {
                //$currentPage + 1 not $currentPage - 1
                echo '<a href=?page=' . ($currentPage + 1) . '&sort=' . $sortColumn . '&order=' . $sortMusic . '">Next</a> ';
            }
            echo "</div>";
        }
    } else {
        echo "Data isn't available or an error is occuring ";
    }
    ?>
</div>

</html>