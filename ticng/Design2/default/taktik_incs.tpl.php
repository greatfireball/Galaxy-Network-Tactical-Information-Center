<h2>Incommings</h2>
<table class="border_table">
    <tr>
        <th>User</th>
        <th colspan="2">Userfleet</th>
        <th colspan="2">Incommings</th>
        <th colspan="2">Deff</th>
    </tr>
<? 
foreach ($taktik as $user) {
    echo "    <tr>\n";
    echo "        <td>{$user['user']}</td>\n";
    echo "        <td>\n";
    for ($i = 0; $i < count($user['outgoing']); $i++) {
       echo $user['outgoing'][$i]->toTaktikString(0); if ($i != count($user['outgoing'])-1) echo '<br>';
    } 
    echo "        </td>\n";
    echo "        <td>\n";
    for ($i = 0; $i < count($user['outgoing']); $i++) {
       echo $user['outgoing'][$i]->getETA(); if ($i != count($user['outgoing'])-1) echo '<br>';
    } 
    echo "        </td>\n";
    echo "        <td>\n";
    for ($i = 0; $i < count($user['incs']); $i++) {
       echo $user['incs'][$i]->toTaktikString(1); if ($i != count($user['incs'])-1) echo '<br>';
    } 
    echo "        </td>\n";
    echo "        <td>\n";
    for ($i = 0; $i < count($user['incs']); $i++) {
       echo $user['incs'][$i]->getETA(); if ($i != count($user['incs'])-1) echo '<br>';
    } 
    echo "        </td>\n";
    echo "        <td>\n";
    for ($i = 0; $i < count($user['deff']); $i++) {
       echo $user['deff'][$i]->toTaktikString(1); if ($i != count($user['deff'])-1) echo '<br>';
    } 
    echo "        </td>\n";
    echo "        <td>\n";
    for ($i = 0; $i < count($user['deff']); $i++) {
       echo $user['deff'][$i]->getETA(); if ($i != count($user['deff'])-1) echo '<br>';
    } 
    echo "        </td>\n";
    echo "    </tr>\n";
}
?>
</table>
