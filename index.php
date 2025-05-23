 <?php
 $conn=new mysqli("localhost","root","","canary");
 $sql="INSERT INTO test VALUES (1,'test')";
 if($conn->query($sql))
 {
     echo "New record created successfully";
    }
    else
    {
        echo "Error: ".$sql."<br>".$conn->error;
    }   
    conn->close();
    ?>