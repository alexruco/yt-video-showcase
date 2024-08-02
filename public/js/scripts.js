document.addEventListener('DOMContentLoaded', function() {
    const apiKey = ytVideoShowcase.apiKey;
    const featuredVideoId = ytVideoShowcase.featuredVideoId;
    const videoIds = ytVideoShowcase.videoIds;

    function initializeVideoShowcase() {
        updateFeaturedVideo(featuredVideoId);
        videoIds.forEach((videoId, index) => {
            updateVideoDetails(videoId, index);
        });
        ensureLightbox();
    }

    async function fetchVideoDetails(videoId) {
        const url = `https://www.googleapis.com/youtube/v3/videos?id=${videoId}&key=${apiKey}&part=snippet`;
        try {
            const response = await fetch(url);
            const data = await response.json();
            if (data.items.length > 0) {
                return data.items[0].snippet;
            }
        } catch (error) {
            console.error('Error fetching video details:', error);
        }
    }

    function updateVideoDetails(videoId, index) {
        fetchVideoDetails(videoId).then(snippet => {
            if (!snippet) return;
            const thumbnail = document.getElementById(`thumbnail-${index + 1}`);
            if (thumbnail) {
                thumbnail.src = snippet.thumbnails.high.url;
                thumbnail.alt = snippet.title;
            }
            populateVideoDetails(snippet, index);
        });
    }

    function populateVideoDetails(snippet, index) {
        const dateElement = document.getElementById(`date-${index + 1}`);
        const titleElement = document.getElementById(`title-${index + 1}`);
        const descriptionElement = document.getElementById(`description-${index + 1}`);

        if (dateElement) {
            dateElement.textContent = new Date(snippet.publishedAt).toLocaleDateString();
        }
        if (titleElement) {
            titleElement.textContent = snippet.title;
        }
        if (descriptionElement) {
            descriptionElement.textContent = summarizeText(snippet.description, 15);
        }
    }

    function summarizeText(description, wordLimit) {
        const words = description.split(' ');
        return words.slice(0, wordLimit).join(' ') + (words.length > wordLimit ? '...' : '');
    }

    function updateFeaturedVideo(videoId) {
        fetchVideoDetails(videoId).then(snippet => {
            if (!snippet) return;
            const featuredThumbnail = document.getElementById('featured-thumbnail');
            if (featuredThumbnail) {
                featuredThumbnail.src = snippet.thumbnails.high.url;
                featuredThumbnail.alt = snippet.title;
            }
            const featuredLink = document.getElementById('featured-video-link');
            if (featuredLink) {
                featuredLink.href = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
            }
        });
    }

    function ensureLightbox() {
        let lightbox = document.getElementById('videoLightbox');
        if (!lightbox) {
            lightbox = document.createElement('div');
            lightbox.id = 'videoLightbox';
            lightbox.style.display = 'none';
            lightbox.innerHTML = '<video id="lightboxVideo" controls></video><button onclick="closeLightbox()">Close</button>';
            document.body.appendChild(lightbox);
        }
    }

    window.openLightbox = function(videoUrl) {
        const lightbox = document.getElementById('videoLightbox');
        const video = document.getElementById('lightboxVideo');
        if (video) {
            video.src = videoUrl;
        }
        if (lightbox) {
            lightbox.style.display = 'block';
        }
    };

    window.closeLightbox = function() {
        const lightbox = document.getElementById('videoLightbox');
        const video = document.getElementById('lightboxVideo');
        if (video) {
            video.pause();
            video.src = '';
        }
        if (lightbox) {
            lightbox.style.display = 'none';
        }
    };

    initializeVideoShowcase();
});
