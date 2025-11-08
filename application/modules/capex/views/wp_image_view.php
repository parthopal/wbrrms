<?php
defined('BASEPATH') or exit('No direct script access allowed');
$wp_image = json_decode($wp_image);
?>
<style>
    :root {
        --gap: 14px;
        --radius: 14px;
        --shadow: 0 6px 18px rgba(0, 0, 0, .08);
        --hover-scale: 1.035;
    }

    body {
        margin: 0;
        font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
        background: #f7f8fa;
        color: #111;
    }

    .wrap {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 16px;
    }

    .title {
        margin: 0 0 14px;
        font-weight: 700;
        letter-spacing: .2px;
    }

    .gallery {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--gap);
    }

    @media (max-width: 1024px) {
        .gallery {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .gallery {
            grid-template-columns: 1fr;
        }
    }

    .item {
        display: block;
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        background: #fff;
        text-decoration: none;
        outline: none;
    }

    .item:focus-visible {
        box-shadow: 0 0 0 3px #2563eb55, var(--shadow);
    }

    .item .frame {
        width: 100%;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        background: #e9eef3;
    }

    .item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transform: scale(1);
        transition: transform .25s ease;
    }

    .item:hover img {
        transform: scale(var(--hover-scale));
    }

    .caption {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 10px 12px;
        font-size: .9rem;
        line-height: 1.2;
        color: #fff;
        background: linear-gradient(to top, rgba(0, 0, 0, .45), rgba(0, 0, 0, 0));
        opacity: .0;
        transition: opacity .2s ease;
        pointer-events: none;
    }

    .item:hover .caption {
        opacity: 1;
    }

    /* ---------- PRINT (A4 Portrait) ---------- */
    @media print {
        body {
            background: #fff !important;
            margin: 0;
            -webkit-print-color-adjust: exact;
        }

        .wrap {
            margin: 0;
            padding: 5mm;
            /* width: 210mm; */
            /* A4 width */
            /* min-height: 297mm; */
            /* A4 height */
        }

        .gallery {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 8px;
            page-break-inside: avoid;
        }

        .item {
            box-shadow: none;
            border: 1px solid #ccc;
        }

        .caption {
            opacity: 1 !important;
            background: none !important;
            color: #000 !important;
            position: static;
            padding: 5px 0;
        }

        @page {
            size: A4 portrait;
            margin: 10mm 5mm;
        }

    }
</style>

<div class="wrap">
    <?php if (!empty($wp_image)): ?>
        <h1 class="title" style="text-align: center;"><?= $wp_image[0]->name ?></h1>
    <?php endif; ?>
    <h1>Progress Image Gallery</h1>

    <?php if (!empty($wp_image)): ?>
        <?php foreach ($wp_image as $row): ?>
            <h3 class="title">Progress: <?= $row->physical_progress ?>%</h3>
            <div class="gallery mb-3">
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <?php $img = $row->{"image" . $i}; ?>
                    <?php if (!empty($img)): ?>
                        <a class="item" href="<?= base_url($img) ?>" target="_blank" aria-label="Open image in new tab">
                            <div class="frame">
                                <img src="<?= base_url($img) ?>" alt="Progress <?= $row->physical_progress ?> - Image <?= $i ?>" loading="lazy" />
                            </div>
                            <div class="caption">
                                <?= "Progress {$row->physical_progress}% - Image {$i}" ?>
                            </div>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <h4 class="title"><?= $row->progress_remarks ?></h4>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No progress images found.</p>
    <?php endif; ?>
</div>