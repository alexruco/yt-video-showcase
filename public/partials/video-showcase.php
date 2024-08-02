<?php
#video-showcase.php
?>
<div class="col-md-12" id="yt-showcase-title-container">
    <h2>Vídeos</h2>
    <hr>
</div>

<div class="yt-showcase-container" id="yt-showcase-main-container">
    <div class="yt-showcase-column yt-showcase-column-left">
        <a id="featured-video-link" href="https://www.youtube.com/watch?v=<?php echo esc_attr(get_option('yt_video_showcase_featured_video_id')); ?>" target="_blank">
            <div class="yt-showcase-box-large" id="featured-thumbnail-container" data-video-id="<?php echo esc_attr(get_option('yt_video_showcase_featured_video_id')); ?>">
                <img id="featured-thumbnail" src="<?php echo esc_attr(get_option('yt_video_showcase_custom_image_featured_video')) ? esc_attr(get_option('yt_video_showcase_custom_image_featured_video')) : ''; ?>" alt="Featured Video">
            </div>
        </a>
    </div>
    <div class="yt-showcase-column yt-showcase-column-right">
        <?php
        $video_ids = explode(',', get_option('yt_video_showcase_video_ids'));
        foreach ($video_ids as $index => $video_id) {
            $custom_thumbnail = '';
            if ($index == 0) {
                $custom_thumbnail = get_option('yt_video_showcase_custom_image_thumbnail_1');
            } elseif ($index == 1) {
                $custom_thumbnail = get_option('yt_video_showcase_custom_image_thumbnail_2');
            } elseif ($index == 2) {
                $custom_thumbnail = get_option('yt_video_showcase_custom_image_thumbnail_3');
            }
            ?>
            <div class="yt-showcase-box-small" data-video-id="<?php echo esc_attr($video_id); ?>">
                <div class="yt-showcase-inner-box">
                    <a href="https://www.youtube.com/watch?v=<?php echo esc_attr($video_id); ?>" target="_blank">
                        <img id="thumbnail-<?php echo $index + 1; ?>" src="<?php echo esc_attr($custom_thumbnail) ? esc_attr($custom_thumbnail) : ''; ?>" alt="More Video">
                    </a>
                </div>
                <div class="yt-showcase-inner-box">
                    <div class="video-details-date" id="date-<?php echo $index + 1; ?>"></div>
                    <div class="video-details-title" id="title-<?php echo $index + 1; ?>"></div>
                    <div class="video-details-description" id="description-<?php echo $index + 1; ?>"></div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php 
$cta_text = get_option('yt_video_showcase_cta_text');
$cta_url = get_option('yt_video_showcase_cta_url');
?>
<div class="col-md-12 mktv-cta-container">
    <a href="<?php echo($cta_url)?>">
        <button id="videos-cta-button"><?php echo($cta_text)?></button>
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const apiKey = ytVideoShowcase.apiKey;
    const featuredVideoId = ytVideoShowcase.featuredVideoId;
    const videoIds = ytVideoShowcase.videoIds;

    const customThumbnails = {
        featured: "<?php echo esc_js(get_option('yt_video_showcase_custom_image_featured_video')); ?>",
        0: "<?php echo esc_js(get_option('yt_video_showcase_custom_image_thumbnail_1')); ?>",
        1: "<?php echo esc_js(get_option('yt_video_showcase_custom_image_thumbnail_2')); ?>",
        2: "<?php echo esc_js(get_option('yt_video_showcase_custom_image_thumbnail_3')); ?>"
    };

    async function fetchVideoDetails(videoId) {
        const response = await fetch(`https://www.googleapis.com/youtube/v3/videos?id=${videoId}&key=${apiKey}&part=snippet,contentDetails,statistics`);
        const data = await response.json();
        return data.items[0].snippet;
    }

    function getFirstWords(description, wordsCount) {
        return description.split(' ').slice(0, wordsCount).join(' ') + (description.split(' ').length > wordsCount ? '...' : '');
    }

    function updateVideoDetails(videoId, index) {
        fetchVideoDetails(videoId).then(snippet => {
            const customThumbnail = customThumbnails[index] ? customThumbnails[index] : snippet.thumbnails.high.url;
            document.getElementById(`thumbnail-${index + 1}`).src = customThumbnail;
            document.getElementById(`date-${index + 1}`).textContent = new Date(snippet.publishedAt).toLocaleDateString();
            document.getElementById(`title-${index + 1}`).textContent = snippet.title;
            document.getElementById(`description-${index + 1}`).textContent = getFirstWords(snippet.description, 10);
            document.getElementById(`yt-showcase-main-container`).style.visibility = 'visible';
        }).catch(error => {
            console.error('Error fetching video details:', error);
        });
    }

    function updateFeaturedVideo(videoId) {
        fetchVideoDetails(videoId).then(snippet => {
            const customThumbnail = customThumbnails.featured ? customThumbnails.featured : snippet.thumbnails.high.url;
            document.getElementById('featured-thumbnail').src = customThumbnail;
            document.getElementById('featured-video-link').href = `https://www.youtube.com/watch?v=${videoId}`;
        }).catch(error => {
            console.error('Error fetching featured video details:', error);
        });
    }

    // Add a slight delay to ensure the DOM is fully loaded before making changes
    setTimeout(() => {
        updateFeaturedVideo(featuredVideoId);
        videoIds.forEach((videoId, index) => {
            updateVideoDetails(videoId, index);
        });
    }, 100);
});
</script>
