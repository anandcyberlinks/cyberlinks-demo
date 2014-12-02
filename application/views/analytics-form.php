<p><strong>Play Video</strong></p>

 <form action="<?php echo site_url('analytics?device=3g');?>" method='get' enctype='multipart/form-data'>   
<table>
    <tr>
        <td>user id. :</td><td><input type='text' name='user_id' value='2'></td>
        <td>Content id :</td><td><select name='id'>
            <option value='879'>879</option>
            <option value='880'>880</option>
            <option value='881'>881</option>
            <option value='882'>882</option>
            <option value='883'>883</option>
            <option value='884'>884</option>
            <option value='885'>885</option>
            <option value='886'>886</option>
        </select></td>
        <td>Latitude :</td><td><input type='text' name='lat'></td>
    </tr> 
    <tr><td>Longitude</td><td><input type='text' name='lng'></td>
    <td>Device</td><td><input type='text' name='device' value='3g'></td>
    </tr>
<tr><td colspan='3'> <input type='submit' value='submit'></td></tr>
</table>
    </form>      
