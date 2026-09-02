<?php 
$editSlider = $data['editSlider'] ?? null; 
$isEditMode = ($editSlider !== null);
$sliders = $data['slider'] ?? [];
$sId = (int)($editSlider['id'] ?? 0);
?>