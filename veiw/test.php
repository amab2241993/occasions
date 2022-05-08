<!DOCTYPE html>
<html>
    <head>
        <title>
            How to change cursor style using jQuery ?
        </title>
          
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
        </script>
          
        <script type="text/javascript">
            $(document).ready(function() {
                $("input[type='radio']").click(function() {
                    var radioValue = $("input[name='cursor']:checked").val();
                      
                    if(radioValue) {
                        $("#block").css("cursor", radioValue );
                    }});
            });
    </script>
    </head>
      
    <body>
        <h1 align ="center">
            Changing cursor style using jQuery
        </h1>
          
        <div style="border:2px solid green">
              
            <table width="100%" style="table-layout:fixed;">
                      
                <p align="center">
                    Click on the Radio button to
                    change the cursor style
                </p>
  
                <tr>
                    <td>
                        <input type="radio" name="cursor" value="alias" >
                        alias 
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="all-scroll" >
                        all-scroll
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="auto" >
                            auto
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="cell" >
                        cell
                    </td>
                </tr>
                  
                <tr> 
                    <td>
                        <input type="radio" name="cursor" value="context-menu" >
                        context-menu
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="col-resize" >
                        col-resize
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="copy" >
                        copy
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="crosshair" >
                        crosshair
                    </td>
                </tr>
                  
                <tr>
                    <td>
                        <input type="radio" name="cursor" value="default" >
                        default
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="e-resize" >
                        e-resize
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="ew-resize" >
                        ew-resize
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="help" >
                        help
                    </td>
                </tr>
                  
                <tr> 
                    <td>
                        <input type="radio" name="cursor" value="move" >
                        move
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="n-resize" >
                        n-resize
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="ne-resize" >
                        ne-resize
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="nw-resize" >
                        nw-resize
                    </td>
                </tr>
                  
                <tr> 
                    <td>
                        <input type="radio" name="cursor" value="ns-resize" >
                        ns-resize
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="no-drop" >
                        no-drop
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="none" >
                        none
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="not-allowed" >
                        not-allowed
                    </td>
                </tr>
                  
                <tr> 
                    <td>
                        <input type="radio" name="cursor" value="pointer" >
                        pointer
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="progress" >
                        progress
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="row-resize" >
                        row-resize
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="s-resize" >
                        s-resize
                    </td>
                </tr>
                  
                <tr>
                    <td>
                        <input type="radio" name="cursor" value="se-resize" >
                        se-resize
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="sw-resize" >
                        sw-resize
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="text" >
                        text
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="vertical-text" >
                        vertical-text
                    </td>
                </tr>
                  
                <tr> 
                    <td>
                        <input type="radio" name="cursor" value="w-resize" >
                        w-resize
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="wait" >
                        wait
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="zoom-in" >
                        zoom-in
                    </td>
                    <td>
                        <input type="radio" name="cursor" value="zoom-out" >
                        zoom-out
                    </td>
                </tr>
            </table>
        </div>
          
        <section>
            <label>
                <h1>Output:</h1>
            </label> 
              
            <div id="block" style="padding:10px;border:2px solid red;">
                Hello welcome
            </div>
        </section>
    </body>
  
</html>        