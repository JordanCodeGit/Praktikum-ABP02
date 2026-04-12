<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$profil = [
    ['nama' => 'Jordan',    'pekerjaan' => 'Web Developer',  'lokasi' => 'Purwokerto'],
    ['nama' => 'Angkawijaya',    'pekerjaan' => 'UI/UX Designer',  'lokasi' => 'Banyumas'],
    ['nama' => 'Angka',    'pekerjaan' => 'Data Analyst',    'lokasi' => 'Jawa Tengah'],
    ['nama' => 'Wijaya',    'pekerjaan' => 'Backend Engineer','lokasi' => 'Indonesia'],
    ['nama' => 'Jong',   'pekerjaan' => 'DevOps Engineer', 'lokasi' => 'Bumi'],
];

echo json_encode($profil);
?>