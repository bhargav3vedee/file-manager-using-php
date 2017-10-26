<?php

function pp($a)
{
    echo "<pre>";   print_r($a);    echo "</pre>";
}

$variable = ['file_name', 'ext', 'mdate', 'path', 'file_txt'];

foreach($variable as $var)
{
    $$var = (isset($_POST[$var]))? ($_POST[$var])    :   "";
}


if(isset($_POST['save']))
{
    foreach($_POST as $key=>$val)
    {
        $$key = $val;
    }
    
    if(isset($path) && $path != "")
    {
        if(is_file($path))
        {
            $path = dirname($path);
        }
    }
    else
    {
        $path = realpath(getcwd());
    }
   
    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

    $files = [];    $Files = [];

    foreach ($objects as $info) 
    {
        if(is_file($info->getPathname()))
        {
            $files[] = $info->getPathname();  
        }
    }
    
    $File = $files;
    
    $Extension = [];
    if($ext != "")
    {
        $Extension = explode(',', $ext);
    }
    
    if($file_name != "")
    {
        $Files = [];
        
        foreach($File as $id => $name)
        {
            $BaseName = basename($name);
            
            if(strstr($BaseName, $file_name))
            {
                $Files[$id] = $name;
            }
        }
        $File = $Files;
    }
    
    if(!empty($Extension))
    {
        $Files = [];
        
        foreach($File as $id => $name)
        {
            $ezt = end(explode(".",$name));
            
            if(in_array($ezt, $Extension))
            {
                $Files[$id] = $name;
            }
        }
        
        $File = $Files;
    }
    
    if(isset($mdate) && $mdate != "")
    {
        $Files = [];
        $d = explode(" - ", $mdate);
                
        $sdate = strtotime($d[0]." 00:00:00");
        $edate = strtotime($d[1]." 23:59:59");
        
        foreach($File as $id => $name)
        {
            $mtime = filemtime($name);
            
            if($mtime >= $sdate && $edate >= $mtime)
            {
                $Files[$id] = $name;
            }
        }
        
        $File = $Files;
    }
    
    $lineNo = [];
    $AvailLine = 0;
    
    if(isset($file_txt) && $file_txt != "")
    {
        $Files = [];
        
        foreach($File as $id => $name)
        {
            
            $Lines = [];
            
            $lines = file($name);
            
            foreach ($lines as $line_num => $line) 
            {
                if(strstr($line, $file_txt))
                {
                    array_push($Lines, $line_num+1);
                }   
            }
            
            if(!empty($Lines))
            {
                $Files[$id] = $name;
                $fet = implode(",", $Lines);
                $lineNo[$id] = $fet;
            }
        }
        
        $AvailLine = 1;
        $File = $Files;    
    }

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
                <link rel="stylesheet" href="file_browser/css/bootstrap.min.css"/>
                 <!-- Latest compiled and minified CSS -->
                
                <link href="file_browser/css/jqueryFileTree.css" rel="stylesheet" type="text/css" media="screen" />
                
                <link rel="stylesheet" type="text/css" media="all" href="file_browser/css/daterangepicker.css" />
                <link rel="stylesheet" type="text/css" href="file_browser/css/tags-input.css"/>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
                <link rel="stylesheet" type="text/css" href="file_browser/css/dataTables.bootstrap.min.css"></link>
                
                <!-- jQuery library -->
                
                <script src="file_browser/js/jquery-1.12.4.js"></script>
                
                <script src="file_browser/js/dataTables.bootstrap.min.js"></script>
                
                <script type="text/javascript" src="file_browser/js/moment.js"></script>
                    
                <!-- Latest compiled JavaScript -->
                <script src="file_browser/js/jquery.min.js"></script>
                
                <script src="file_browser/js/jquery.easing.js" type="text/javascript"></script>
                <script src="file_browser/js/jqueryFileTree.js" type="text/javascript"></script>
                
                <script type="text/javascript" src="file_browser/js/tags-input.js"></script>
                
		<script type="text/javascript" src="file_browser/js/moment.js"></script>
                <script type="text/javascript" src="file_browser/js/daterangepicker.js"></script>
                <script src="file_browser/js/jquery.dataTables.min.js"></script>    
		<script type="text/javascript">
                    $(document).ready( function() {

                            var root = '<?php echo getcwd(); ?>'+'/';

                            $('#fileTreeDemo_1').fileTree({ root: root, script: 'file_browser/connectors/jqueryFileTree.php' }, function(file) { 

                            });
                            
                            updateConfig();
                            
                    });
                    
                    $(document).ready(function() {
                        $('#myTable').DataTable();
                    } );
                    
                    function applyDate()
                    {
                        $('#config-demo').val($('#start_date').val()+" - "+$('#end_date').val());
                    }
                    function updateConfig() 
                    {
                      var options = {};
                      options.autoUpdateInput = false;
                        options.cancelClass = $('#cancelClass').val();
                      $('#config-demo').daterangepicker(options, function(start, end, label) {  }); 
                    }
                    
		</script>
                
                
                
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                
                
          
<style type="text/css">
      .demo { position: relative; }
      .demo i {
        position: absolute; bottom: 10px; right: 24px; top: auto; cursor: pointer;
      }
      
      
    .example {
            float: left;
            margin: 15px;
            align : center;
    }

    .demo {
            min-width: 600px;
            height: 370px;
            border-top: solid 1px #BBB;
            border-left: solid 1px #BBB;
            border-bottom: solid 1px #FFF;
            border-right: solid 1px #FFF;
            background: #FFF;
            overflow: scroll;
            padding: 5px;
    }
	 
</style>

	</head>
	
	<body>
            
            <div class="row">
                <center><h3>File Browser</h3></center>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="example">
                        <h4>Select Directory From Where You Want to Find Files</h4>
                        <div id="fileTreeDemo_1" class="demo"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                         <div class="form-group">
                          <label for="path">Search File From Path :</label>
                          <input type="text" name="path" class="form-control" value="<?php echo $path; ?>" id="path" />
                        </div>
                         
                        <div class="form-group">
                          <label for="fname">Search File By Name :</label>
                          <input type="text" name="file_name" class="form-control" value="<?php echo $file_name; ?>" id="fname" />
                        </div>
                        <div class="form-group">
                          <label for="txt">Search Text From File :</label>
                          <input type="text" class="form-control" name="file_txt" value="<?php echo $file_txt; ?>" id="txt" />
                        </div>
                        <div class="form-group">
                          <label for="mdt">Search File By Modified Date :</label>
<!--                          <input type="text" class="form-control" name="daterange" id="mdt"/>-->
                            <input type="text" id="config-demo" value="<?php echo $mdate; ?>" name="mdate" class="form-control"/>
                        </div>
                        <div class="form-group">
                          <label for="simple">Search File Of Specific Extension :</label>
                          <input type="text" id='simple' type='tags' name="ext" value="<?php echo $ext; ?>" class="form-control" />
                        </div>
                         
                        <button name="save" type="submit" class="btn btn-primary">Submit</button>
                    </form> 
                </div>
            </div>  
            
            
            <script type="text/javascript">
		for (let input of document.querySelectorAll('form #simple')) {
			tagsInput(input);
		}
            </script>
            
	</body>
	
</html>

<?php

if(isset($_POST['save']))
{
echo "<br><br><table class='table' id='myTable'>"
    . "<thead>"
        . "<tr>"
            . "<th>#</th>"
            . "<th>File Name</th>"
            . "<th>Last Modified Time</th>";

            if($AvailLine == 1)
            {
                echo "<th>Line No.</th>";
            }

echo "". "</tr>"
    . "</thead><tbody>";

$i = 1;

foreach($File as $file=>$val)
{
    echo "<tr>"
            . "<td>".$i."</td>"
            . "<td width='60%'>".$files[$file]."</td>"
            . "<td>".date('Y-m-d H:i:s', filemtime($files[$file]))."</td>";
         
            if($AvailLine == 1)
            {
                echo "<td>".$lineNo[$file]."</td>";
            }
    
    echo "". "</tr>";
    $i++;
}

echo "</tbody></table>";
}
?>