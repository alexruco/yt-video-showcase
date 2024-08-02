<?php
#video-showcase.php
?>
<div class="col-md-12" id="yt-showcase-title-container">
    <h2>Vídeos</h2>
    <hr>
</div>

<div class="yt-showcase-container" id="yt-showcase-main-container">
    <div class="yt-showcase-column yt-showcase-column-left">
        <div class="yt-showcase-box yt-showcase-box-large" id="featured-thumbnail-container" data-video-id="<?php echo esc_attr(get_option('yt_video_showcase_featured_video_id')); ?>">
            <img id="featured-thumbnail" src="<?php echo esc_attr(get_option('yt_video_showcase_custom_image_featured_video')) ? esc_attr(get_option('yt_video_showcase_custom_image_featured_video')) : ''; ?>" alt="Featured Video">
            <span class="play-button">
                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play" class="svg-inline--fa fa-play fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.41 214.66L72.41 3.66C34.62-17.07 0 1.99 0 48.01V464c0 45.53 34.11 65.61 72.41 44.34l352-208.01c38.2-20.24 38.22-68.45 0-88.67z"></path></svg>
            </span>
        </div>
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
            <div class="yt-showcase-box yt-showcase-box-small" data-video-id="<?php echo esc_attr($video_id); ?>">
                <div class="yt-showcase-inner-box">
                    <img id="thumbnail-<?php echo $index + 1; ?>" src="<?php echo esc_attr($custom_thumbnail) ? esc_attr($custom_thumbnail) : ''; ?>" alt="More Video">
                    <span class="play-button">
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play" class="svg-inline--fa fa-play fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.41 214.66L72.41 3.66C34.62-17.07 0 1.99 0 48.01V464c0 45.53 34.11 65.61 72.41 44.34l352-208.01c38.2-20.24 38.22-68.45 0-88.67z"></path></svg>
                    </span>
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

<!-- Modal -->
<div id="modalDiv" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="embed-responsive embed-responsive-16by9">
            <iframe id="yt-video" class="embed-responsive-item" src="" allowfullscreen></iframe>
        </div>
    </div>
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

    var modal = document.getElementById('modalDiv');
    var closeModalBtn = document.querySelector('.modal .close');
    var iframe = document.getElementById('yt-video');

    document.querySelectorAll('.yt-showcase-box').forEach(function (element) {
        element.addEventListener('click', function () {
            var videoId = this.getAttribute('data-video-id');
            iframe.src = 'https://www.youtube.com/embed/' + videoId;
            modal.style.display = 'block';
        });
    });

    closeModalBtn.addEventListener('click', function () {
        modal.style.display = 'none';
        iframe.src = '';
    });

    window.addEventListener('click', function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
            iframe.src = '';
        }
    });
});
</script>

<style>
.play-button {
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 2em;
    opacity: 0.8;
    pointer-events: none; /* Ensure the play button doesn't interfere with clicking the thumbnail */
}
.yt-showcase-box-large, .yt-showcase-box-small {
    position: relative; /* Ensure the play button is positioned relative to the thumbnail */
}

.yt-showcase-box-large img,
.yt-showcase-box-small img {
    display: block;
}

.yt-showcase-box-large .play-button,
.yt-showcase-box-small .play-button {
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(0, 0, 0, 0.4); /* Slight background for visibility */
    border-radius: 50%; /* Make the play button circular */
}
.yt-showcase-box-large .play-button {
    width: 10%;
    height: 10%;
    left: 50%;
}

.yt-showcase-box-small .play-button {
    width: 7%;
    height: 5%;
    left: 25%;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100vw;
    height: 100vh;
    padding-top:50px;
    max-width: 100vw;
    max-height: 100vh;
    overflow: auto;
    background-color: rgb(0, 0, 0);
    background-color: rgba(0, 0, 0, 0.85);
}

.modal-content {
    position: relative;
    margin: auto;
    padding: 0;
    
    width: 80%;
    max-width: 800px;
    animation-name: modalopen;
    animation-duration: 0.4s;
}

@keyframes modalopen {
    from {
        top: -300px;
        opacity: 0;
    }
    to {
        top: 0;
        opacity: 1;
    }
}

.close {
    color: #fff;
    float: right;
    font-size: 28px;
    font-weight: bold;
    margin-left:5px;
}

.close:hover,
.close:focus {
    color: #555;
    text-decoration: none;
    cursor: pointer;
}

.embed-responsive-16by9 {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
}

.embed-responsive-16by9 iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.yt-showcase-box {
    cursor: pointer;
    padding: 5px 5px;
    display: inline-block;
    border-radius: 4px;
}


</style>
