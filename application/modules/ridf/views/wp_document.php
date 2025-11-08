<?php
defined('BASEPATH') or exit('No direct script access allowed');
$document = json_decode($document);
?>

<style>
    .title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }
    .road-section { margin-bottom: 30px; }
    .images { display: flex; gap: 10px; margin-top: 10px; }
    .image-box { border: 1px solid #007bff; padding: 10px; width: 150px; height: 100px; text-align: center; }
    .image-box img { max-width: 100%; max-height: 80px; }
</style>

<?php foreach ($document as $item): ?>
    <div class="road-section">
        <div class="title">Road name: <u><?= $item->name ?></u></div>
        <div><b>Physical Progress:</b> <?= $item->physical_progress ?>%</div>
        <div class="images">
            <?php if (!empty($item->image1)): ?>
                <div class="image-box">
                    <img src="<?= base_url($item->image1) ?>" alt="Image1">
                </div>
            <?php endif; ?>
            <?php if (!empty($item->image2)): ?>
                <div class="image-box">
                    <img src="<?= base_url($item->image2) ?>" alt="Image2">
                </div>
            <?php endif; ?>
            <?php if (!empty($item->image3)): ?>
                <div class="image-box">
                    <img src="<?= base_url($item->image3) ?>" alt="Image3">
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
