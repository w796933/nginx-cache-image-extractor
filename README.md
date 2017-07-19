# nginx-cache-image-extractor
Simple tool to extract images from nginx cache folder

Use nginx-cache-inspector.sh to find cache file and tee to file

$ nginx-cache-inspector.sh "jpg" /var/cache/nginx |& tee listimageincache.txt

Use file created before as input of extractfilefromcache.php . Please read code and modify part of create output folder, change it match to your file created above
