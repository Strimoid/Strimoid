<video-yt>
    <iframe frameborder="0" allowfullscreen src={ this.source }></iframe>

    <style>
        video-yt {
            display: block;
        }

        video-yt iframe {
            width: 100%;
            height: 100%;
        }
    </style>

    <script>
        this.source = '//www.youtube.com/embed/' + opts.vid +'?rel=0&autoplay=1';
    </script>
</video-yt>
