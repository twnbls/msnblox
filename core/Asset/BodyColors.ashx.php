<?php
header('Content-Type: application/xml');
include "/../core/config.php";

$UserId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$NOOBv2_COLORS = [
    'head'     => 102,
    'torso'    => 102,
    'leftarm'  => 102,
    'rightarm' => 102,
    'leftleg'  => 102,
    'rightleg' => 102
];

$finalColors = $NOOBv2_COLORS;

if ($UserId > 0) {
    $stmt = $conn->prepare("SELECT head, torso, leftarm, rightarm, leftleg, rightleg FROM bodycolors WHERE userid = ? LIMIT 1");
    $stmt->bind_param("i", $UserId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $finalColors = [
            'head'     => (int)$row['head'],
            'torso'    => (int)$row['torso'],
            'leftarm'  => (int)$row['leftarm'],
            'rightarm' => (int)$row['rightarm'],
            'leftleg'  => (int)$row['leftleg'],
            'rightleg' => (int)$row['rightleg']
        ];
    }

    $stmt->close();
}
?>
<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="http://www.roblox.com/roblox.xsd"
        version="4">
    <External>null</External>
    <External>nil</External>
    <Item class="BodyColors" referent="RBX0">
        <Properties>
            <int name="HeadColor"><?php echo $finalColors['head']; ?></int>
            <int name="LeftArmColor"><?php echo $finalColors['leftarm']; ?></int>
            <int name="LeftLegColor"><?php echo $finalColors['leftleg']; ?></int>
            <string name="Name">Body Colors</string>
            <int name="RightArmColor"><?php echo $finalColors['rightarm']; ?></int>
            <int name="RightLegColor"><?php echo $finalColors['rightleg']; ?></int>
            <int name="TorsoColor"><?php echo $finalColors['torso']; ?></int>
            <bool name="archivable">true</bool>
        </Properties>
    </Item>
</roblox>